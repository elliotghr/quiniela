import os

from Soccer import Soccer
from dotenv import load_dotenv

load_dotenv()
YEAR = os.getenv('YEAR')

soccer = Soccer()
soccer.getLeagues(YEAR, "Premier League")

del soccer