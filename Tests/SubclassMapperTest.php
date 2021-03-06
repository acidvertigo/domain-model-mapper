<?php

namespace DMM\Test;

use DMM\Mapper;

class PostMapper extends Mapper
{
    protected $modelClass = 'DMM\Test\Fixtures\Post';

    public function __construct(\PDO $pdo)
    {
        parent::__construct($pdo, 'post', 'post_id');
    }

    public function findById($id)
    {
        $sql =
            "SELECT *
             FROM `post`
             WHERE post_id = :id";
        $bindings = array(
            'id' => $id
        );
        return $this->fetchItem($sql, $bindings);
    }

    public function findByRating($rating)
    {
        $sql =
            "SELECT *
             FROM `post`
             WHERE rating = :rating";
        $bindings = array(
            'rating' => $rating
        );
        return $this->fetchCollection($sql, $bindings);
    }
}

class AnotherPostMapper extends PostMapper
{
    protected $modelCollectionClass = 'DMM\Test\Fixtures\PostCollection';
}

class SubclassMapperTest extends FixtureBasedTestCase
{
    protected $mapper;

    public function setUp()
    {
        parent::setUp();
        $this->mapper = new PostMapper($this->pdo);
    }

    public function testItemFindersReturnSpecifiedModelClass()
    {
        $model = $this->mapper->findById(1);
        $this->assertInstanceOf('DMM\Test\Fixtures\Post', $model);
    }

    public function testCollectionFindersReturnCollectionObject()
    {
        $models = $this->mapper->findByRating(3);
        $this->assertInstanceOf('\Generator', $models);
    }
    
    public function testCollectionFindersReturnSpecifiedCollectionObject()
    {
        $mapper = new AnotherPostMapper($this->pdo);
        $models = $mapper->findByRating(3);
        $this->assertInstanceOf('\Generator', $models);
    }
}
