import os

from Soccer import Soccer
from dotenv import load_dotenv

load_dotenv()
YEAR = os.getenv('YEAR')

soccer = Soccer()
soccer.getFixtures(YEAR, 39, "America/Mexico_City")

del soccer