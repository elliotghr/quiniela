<?php

namespace App\Libraries;

use MongoDB\Client;
use MongoDB\Collection;

class MongoLib
{
    private $client;
    private $db;
    private $collection;

    public function __construct($databaseName,$collectionName)
    {
        $uri = env('MONGO_DSN'); // Usa el DSN desde .env
        $this->client = new Client($uri);

        $this->db = $this->client->selectDatabase($databaseName);
        $this->collection = $this->db->selectCollection($collectionName);
    }

    public function getEntryList($filter = [], $options = []) 
    {
        // Busca todos los registros
        return $this->collection->find($filter, $options)->toArray();
    }

    public function getEntry($filter = [], $options = [])  
    {
        // Busca solo un registro
        return $this->collection->findOne($filter, $options);
    }

    public function insert($data)
    {
        // Inserta documento
        $result = $this->collection->insertOne($data);
        
        // Obtener el ID del nuevo documento insertado
        $insertedId = $result->getInsertedId();
        return $insertedId;
    }

    public function updateEntry($filter, $newData, $options = [])
    {
        return $this->collection->updateOne($filter, ['$set' => $newData], $options);
    }

    public function rawUpdate($filter, $update, $options = [])
    {
        return $this->collection->updateOne($filter, $update, $options);
    }

    public function deleteEntry($filter)
    {
        return $this->collection->deleteOne($filter);
    }

    public function aggregate($pipeline)
    {
        return $this->collection->aggregate($pipeline);
    }
}