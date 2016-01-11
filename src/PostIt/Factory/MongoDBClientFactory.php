<?php

namespace PostIt\Factory;

use PostIt\Service\MongoDBClient;

class MongoDBClientFactory
{
    private $databasename;
    private $collectionName;

    /**
     * MongoDBClientFactory constructor.
     * @param $databasename
     * @param $collectionName
     */
    public function __construct($databasename, $collectionName)
    {
        $this->databasename = $databasename;
        $this->collectionName = $collectionName;
    }

    /**
     * @return \MongoClient
     */
    public function create()
    {
        $m = new \MongoClient();
        $db = $m->{$this->databasename};

        $mongoDbClient = new MongoDBClient($db, $this->collectionName);

        return $mongoDbClient;
    }
}
