<?php

namespace DMM;

class FixtureBasedTestCase extends \PHPUnit_Extensions_Database_TestCase
{
    protected $dbName = 'test';
    protected $host = '127.0.0.1';
    protected $username = 'travis';
    protected $password = '';

    protected $pdo;
    protected $fixtureFile = 'seed.xml';

    public function __construct()
    {
        $dsn = sprintf("mysql:host=%s;dbname=%s", $this->dbName, $this->host);
        $this->pdo = new \PDO($dsn, $this->username, $this->password);
    }

    protected function getConnection()
    {
        return $this->createDefaultDBConnection($this->pdo, $this->dbName);
    }

    protected function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__.'/fixtures/'.$this->fixtureFile);
    }
}
