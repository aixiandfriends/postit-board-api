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

    public function show($id)
    {
        return $this->db->{$this->collectionName}->findOne(array('_id' => new \MongoId($id)));
    }

    public function insert($data)
    {
        return $this->db->{$this->collectionName}->insert($data);
    }

    public function update($id, $content)
    {
        return $this->db->{$this->collectionName}->update(['_id' => new \MongoId($id)], $content);
    }

    public function delete($id)
    {
        return $this->db->{$this->collectionName}->remove(array('_id' => new \MongoId($id)));
    }
}
