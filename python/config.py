import os
from dotenv import load_dotenv

# Carga variables de entorno compartidas por los scripts Python.
load_dotenv(dotenv_path=os.path.join(os.path.dirname(__file__), "../node/python/env"))

RAPIDAPI_KEY = os.getenv("RAPIDAPI_KEY")
LEAGUES_CONFIG = os.getenv("LEAGUES_CONFIG")
MONGO_DSN = os.getenv("MONGO_DSN")
FOTMOB_FIXTURES_URL = os.getenv(
    "FOTMOB_FIXTURES_URL",
    "https://fotmob4.p.rapidapi.com/api/fotmob/v1/league/details/fixtures",
)
APP_URL = os.getenv("APP_URL")