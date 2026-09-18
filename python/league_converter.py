from datetime import datetime
from zoneinfo import ZoneInfo


def _build_match_entry(match: dict, league: dict) -> dict:
    utc_time = match["status"]["utcTime"]
    dt = datetime.fromisoformat(utc_time.replace("Z", "+00:00"))
    timestamp = int(dt.timestamp())
    dt_mx = dt.astimezone(ZoneInfo("America/Mexico_City"))

    finished = match["status"].get("finished", False)
    started = match["status"].get("started", False)
    cancelled = match["status"].get("cancelled", False)

    score_str = match["status"].get("scoreStr", "")
    home_goals = None
    away_goals = None
    if score_str and " - " in score_str:
        parts = score_str.split(" - ")
        home_goals = int(parts[0].strip())
        away_goals = int(parts[1].strip())

    reason = match["status"].get("reason", {})
    if cancelled:
        status_long, status_short, elapsed = "Match Cancelled", "CANC", None
    elif finished:
        status_long = reason.get("long", "Match Finished")
        status_short = reason.get("short", "FT")
        elapsed = 90
    elif started:
        status_long = reason.get("long", "First Half")
        status_short = reason.get("short", "1H")
        elapsed = None
    else:
        status_long, status_short, elapsed = "Not Started", "NS", None

    round_num = match.get("round", "1")
    round_name = f"Regular Season - {round_num}"

    return {
        "fixture": {
            "id": int(match["id"]),
            "referee": None,
            "timezone": "UTC",
            "date": dt_mx.strftime("%Y-%m-%dT%H:%M:%S%z"),
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
            "id": league["league_id"],
            "name": league["league_name"],
            "country": league["league_country"],
            "logo": league["league_logo"],
            "flag": league["league_flag"],
            "season": league["year"],
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
            "halftime": {"home": None, "away": None},
            "fulltime": {"home": home_goals, "away": away_goals},
            "extratime": {"home": None, "away": None},
            "penalty": {"home": None, "away": None},
        },
    }


def _build_league_meta(league: dict, response: list[dict]) -> dict:
    league_logo_dark = league["league_logo"].split("leaguelogo/")
    league_logo_dark.insert(1, "leaguelogo/dark/")
    league_logo_dark = "".join(league_logo_dark)
    dates = sorted(r["fixture"]["date"][:10] for r in response)

    return {
        "id": league["league_id"],
        "name": league["league_name"],
        "logo_light": league["league_logo"],
        "logo_dark": league_logo_dark,
        "flag": league["league_flag"],
        "start": dates[0] if dates else None,
        "end": dates[-1] if dates else None,
    }


def convert_league_payload(league: dict, fixtures: list[dict]) -> tuple[dict, dict]:
    response = [_build_match_entry(match, league) for match in fixtures]

    output = {
        "get": "fixtures",
        "parameters": {
            "league": str(league["league_id"]),
            "season": str(league["year"]),
        },
        "errors": [],
        "results": len(response),
        "paging": {"current": 1, "total": 1},
        "response": response,
    }

    return output, _build_league_meta(league, response)
