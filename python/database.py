from pymongo import MongoClient


def get_collections(mongo_dsn: str):
    """Retorna las colecciones de trabajo para quiniela."""
    client = MongoClient(mongo_dsn)
    db = client["quiniela"]
    return {
        "client": client,
        "ligas": db["ligas"],
        "partidos": db["partidos"],
        "ligas_config": db["ligas_config"],
    }
