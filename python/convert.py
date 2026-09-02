import os
import json
import requests
from datetime import datetime
from zoneinfo import ZoneInfo
from dotenv import load_dotenv
from pymongo import MongoClient

load_dotenv(dotenv_path=os.path.join(os.path.dirname(__file__), "../node/python/env"))

RAPIDAPI_KEY   = os.getenv("RAPIDAPI_KEY")
LEAGUES_CONFIG = os.getenv("LEAGUES_CONFIG")
MONGO_DSN      = os.getenv("MONGO_DSN")

# ---------- Conexión a MongoDB ----------
mongo_client     = MongoClient(MONGO_DSN)
mongo_db         = mongo_client["quiniela"]
ligas_col        = mongo_db["ligas"]
partidos_col     = mongo_db["partidos"]
ligas_config_col = mongo_db["ligas_config"]

# ---------- Leer configuración de ligas desde MongoDB ----------
leagues = list(ligas_config_col.find({}, {"_id": 0}))
if not leagues:
    raise ValueError("No se encontraron ligas en la colección 'ligas_config' de MongoDB.")

print(f"Procesando {len(leagues)} liga(s)...")

# ---------- Llamada a la API ----------
url = "https://fotmob4.p.rapidapi.com/api/fotmob/v1/league/details/fixtures"
headers = {
    "x-rapidapi-host": "fotmob4.p.rapidapi.com",
    "x-rapidapi-key": RAPIDAPI_KEY,
    "Content-Type": "application/json",
}


def convert_league(league: dict) -> None:
    fotmob_league_id = league["fotmob_league_id"]
    fotmob_season    = league["fotmob_season"]
    league_id        = league["league_id"]
    league_name      = league["league_name"]
    league_country   = league["league_country"]
    league_logo      = league["league_logo"]
    league_flag      = league["league_flag"]
    season           = league["year"]

    params = {"league_id": fotmob_league_id, "season": fotmob_season}

    print(f"  [{league_name}] Consultando liga {fotmob_league_id}, temporada {fotmob_season}...")
    res = requests.get(url, headers=headers, params=params, timeout=30)
    res.raise_for_status()
    source = res.json()
    print(f"  [{league_name}] Recibidos {len(source)} partidos.")

    response = []

    for match in source:
        utc_time = match["status"]["utcTime"]  # "2026-08-21T19:00:00Z"
        dt = datetime.fromisoformat(utc_time.replace("Z", "+00:00"))
        timestamp = int(dt.timestamp())
        dt_mx = dt.astimezone(ZoneInfo("America/Mexico_City"))

        finished  = match["status"].get("finished", False)
        started   = match["status"].get("started", False)
        cancelled = match["status"].get("cancelled", False)

        # Extraer marcador desde scoreStr ("1 - 3") cuando el partido tiene resultado
        score_str  = match["status"].get("scoreStr", "")
        home_goals = None
        away_goals = None
        if score_str and " - " in score_str:
            parts      = score_str.split(" - ")
            home_goals = int(parts[0].strip())
            away_goals = int(parts[1].strip())

        # Usar reason del status para códigos más precisos (FT, HT, CANC, etc.)
        reason = match["status"].get("reason", {})
        if cancelled:
            status_long, status_short, elapsed = "Match Cancelled", "CANC", None
        elif finished:
            status_long  = reason.get("long",  "Match Finished")
            status_short = reason.get("short", "FT")
            elapsed      = 90
        elif started:
            status_long  = reason.get("long",  "First Half")
            status_short = reason.get("short", "1H")
            elapsed      = None
        else:
            status_long, status_short, elapsed = "Not Started", "NS", None

        round_num  = match.get("round", "1")
        round_name = f"Regular Season - {round_num}"

        entry = {
            "fixture": {
                "id": int(match["id"]),
                "referee": None,
                "timezone": "UTC",
                "date": dt_mx.strftime("%Y-%m-%dT%H:%M:%S%z"),  # Horario México
                "timestamp": timestamp,
                "periods": {"first": None, "second": None},
                "venue": {"id": None, "name": None, "city": None},
                "status": {
                    "long": status_long,
                    "short": status_short,
                    "elapsed": elapsed,
                    "extra": None,
                },
            },
            "league": {
                "id": league_id,
                "name": league_name,
                "country": league_country,
                "logo": league_logo,
                "flag": league_flag,
                "season": season,
                "round": round_name,
                "standings": True,
            },
            "teams": {
                "home": {
                    "id": int(match["home"]["id"]),
                    "name": match["home"]["name"],
                    "logo": f"https://images.fotmob.com/image_resources/logo/teamlogo/{match['home']['id']}.png",
                    "winner": None,
                },
                "away": {
                    "id": int(match["away"]["id"]),
                    "name": match["away"]["name"],
                    "logo": f"https://images.fotmob.com/image_resources/logo/teamlogo/{match['away']['id']}.png",
                    "winner": None,
                },
            },
            "goals": {"home": home_goals, "away": away_goals},
            "score": {
                "halftime":  {"home": None, "away": None},
                "fulltime":  {"home": home_goals, "away": away_goals},
                "extratime": {"home": None, "away": None},
                "penalty":   {"home": None, "away": None},
            },
        }

        response.append(entry)

    output = {
        "get": "fixtures",
        "parameters": {"league": str(league_id), "season": str(season)},
        "errors": [],
        "results": len(response),
        "paging": {"current": 1, "total": 1},
        "response": response,
    }

    # ---------- Guardar fixtures en MongoDB ----------
    partidos_col.replace_one(
        {"parameters.league": str(league_id), "parameters.season": str(season)},
        output,
        upsert=True,
    )

    print(f"  [{league_name}] Guardado en MongoDB (quiniela.partidos) ({len(response)} partidos).")

    # Calcular fechas de inicio y fin de la temporada desde los fixtures
    dates = sorted(r["fixture"]["date"][:10] for r in response)
    return {
        "id":    league_id,
        "name":  league_name,
        "logo":  league_logo,
        "flag":  league_flag,
        "start": dates[0]  if dates else None,
        "end":   dates[-1] if dates else None,
    }


# ---------- Procesar todas las ligas ----------
leagues_meta = []
for league in leagues:
    meta = convert_league(league)
    leagues_meta.append(meta)

# ---------- Actualizar catálogo de ligas en MongoDB (quiniela.ligas) ----------
for meta in leagues_meta:
    ligas_col.replace_one({"id": meta["id"]}, meta, upsert=True)

print("Catálogo de ligas actualizado en MongoDB (quiniela.ligas).")
print("Listo.")

