import http.client
import os
import urllib.parse
from dotenv import load_dotenv

class Soccer:
    
    def __init__(self):
        load_dotenv()
        self.API_PATH = os.getenv('API_PATH')
        self.API_KEY = os.getenv('API_KEY')
        self.LEAGUES_FILE = os.getenv('LEAGUES_FILE')
        self.FIXTURES_PATH = os.getenv('FIXTURES_PATH')
        
    def __str__(self):
        return f"{self.API_PATH} => {self.API_KEY}"
        
    def getLeagues(self, season, name):
        conn = http.client.HTTPSConnection(self.API_PATH)

        headers = {'x-rapidapi-host': self.API_PATH, 'x-rapidapi-key': self.API_KEY}
        params = urllib.parse.urlencode({'season': season, 'name': name})

        conn.request("GET", "/leagues?" + params, headers=headers)

        response = conn.getresponse()
        data = response.read()

        file = open(self.LEAGUES_FILE, "w")
        file.write(data.decode("utf-8"))
        file.close()
        
    def getFixtures(self, season, league, timezone):
        conn = http.client.HTTPSConnection(self.API_PATH)

        headers = {'x-rapidapi-host': self.API_PATH, 'x-rapidapi-key': self.API_KEY}
        params = urllib.parse.urlencode({'season': season, 'league': league, 'timezone': timezone})

        conn.request("GET", "/fixtures?" + params, headers=headers)

        response = conn.getresponse()
        data = response.read()

        os.makedirs(self.FIXTURES_PATH, exist_ok=True)
        file = open(self.FIXTURES_PATH + f"{league}-{season}.json", "w")
        file.write(data.decode("utf-8"))
        file.close()