import requests


class FotmobClient:
    def __init__(self, api_key: str, fixtures_url: str):
        self.fixtures_url = fixtures_url
        self.headers = {
            "x-rapidapi-host": "fotmob4.p.rapidapi.com",
            "x-rapidapi-key": api_key,
            "Content-Type": "application/json",
        }

    def get_fixtures(self, league_id: str, season: str) -> list[dict]:
        params = {"league_id": league_id, "season": season}
        response = requests.get(
            self.fixtures_url,
            headers=self.headers,
            params=params,
            timeout=30,
        )
        response.raise_for_status()
        return response.json()
