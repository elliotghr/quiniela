from datetime import datetime
from config import FOTMOB_FIXTURES_URL, MONGO_DSN, RAPIDAPI_KEY
from database import get_collections
from fotmob import FotmobClient
from league_converter import convert_league_payload


def convert_league(league: dict, fotmob_client: FotmobClient, partidos_col):
    fotmob_league_id = league["fotmob_league_id"]
    fotmob_season = league["fotmob_season"]
    league_name = league["league_name"]
    league_date_end = league["league_date_end"]

    if league_date_end:
        # Covertir a date y compara contra la fecha actual
        league_date_end_dt = datetime.fromisoformat(league_date_end)
        if league_date_end_dt.date() < datetime.now().date():
            print(f"  [{league_name}] La liga ha finalizado. No se procesarán más partidos.")
            return

    print(f"  [{league_name}] Consultando liga {fotmob_league_id}, temporada {fotmob_season}...")
    source = fotmob_client.get_fixtures(fotmob_league_id, fotmob_season)
    print(f"  [{league_name}] Recibidos {len(source)} partidos.")
    output, league_meta = convert_league_payload(league, source)

    # ---------- Guardar fixtures en MongoDB ----------
    partidos_col.replace_one(
        {
            "parameters.league": output["parameters"]["league"],
            "parameters.season": output["parameters"]["season"],
        },
        output,
        upsert=True,
    )

    print(
        f"  [{league_name}] Guardado en MongoDB (quiniela.partidos) "
        f"({len(output['response'])} partidos)."
    )
    return league_meta


def main() -> None:
    collections = get_collections(MONGO_DSN)
    ligas_col = collections["ligas"]
    partidos_col = collections["partidos"]
    ligas_config_col = collections["ligas_config"]

    leagues = list(ligas_config_col.find({}, {"_id": 0}))
    if not leagues:
        raise ValueError("No se encontraron ligas en la colección 'ligas_config' de MongoDB.")

    print(f"Procesando {len(leagues)} liga(s)...")
    print(f"Usando endpoint: {FOTMOB_FIXTURES_URL}")

    fotmob_client = FotmobClient(RAPIDAPI_KEY, FOTMOB_FIXTURES_URL)

    leagues_meta = []
    for league in leagues:
        meta = convert_league(league, fotmob_client, partidos_col)
        if not meta:
            continue
        leagues_meta.append(meta)

    for meta in leagues_meta:
        ligas_col.replace_one({"id": meta["id"]}, meta, upsert=True)

    print("Catálogo de ligas actualizado en MongoDB (quiniela.ligas).")
    print("Listo.")


if __name__ == "__main__":
    main()

