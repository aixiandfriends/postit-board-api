<?php

namespace PostIt\Service;

class MongoDBClient
{
    private $db;
    private $collectionName;

    public function __construct(\MongoDB $db, $collectionName)
    {
        $this->db = $db;
        $this->collectionName = $collectionName;
    }

    public function select()
    {
        return $this->db->{$this->collectionName}->find();
    }

    public function insert($data)
    {
        return $this->db->{$this->collectionName}->insert($data);
    }

    public function update()
    {

    }

    public function delete()
    {

    }
}
