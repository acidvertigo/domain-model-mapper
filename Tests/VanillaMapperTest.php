<?php

namespace DMM\Test;

use DMM\Mapper;
use DMM\Test\Fixtures\Post;

class VanillaMapperTest extends FixtureBasedTestCase
{
    protected $mapper;

    public function setUp()
    {
        parent::setUp();
        $this->mapper = new Mapper($this->pdo, 'post', 'post_id');
    }

    public function testFindReturnsModel()
    {
        $model = $this->mapper->find(['post_id' => 1], new Post);
        $this->assertSame('My First Post', $model->title);
    }

    public function testFindReturnsNullWhenIdentityMatch()
    {
        $model = $this->mapper->find(['post_id' => 0], new Post);
        $this->assertNull($model);
    }

    public function testInsertNewModel()
    {
        $model = new Post; 
        $model->title = "A new post";
        $model->contents = "Here is some content";
        $model->rating = 4;
        $model->date_created = date("Y-m-d H:i:s", time()); 

        $this->mapper->insert($model);
        $this->assertTrue($model->post_id > 0);
    }

    public function testUpdateModel()
    {
        $model = $this->mapper->find(['post_id' => 1], new Post);
        $model->title = "Updated title";
        $this->mapper->update($model);

        $freshModel = $this->mapper->find(['post_id' => 1], new Post);
        $this->assertSame("Updated title", $freshModel->title);
    }

    public function testSaveModelUpdatesCorrectly()
    {
        $model = $this->mapper->find(['post_id' => 1], new Post);
        $model->title = "Updated title";
        $this->mapper->save($model);

        $freshModel = $this->mapper->find(['post_id' => 1], new Post);
        $this->assertSame("Updated title", $freshModel->title);
    }

    public function testSaveModelInsertsCorrectly()
    {
        // The id 10 does not exist in the fixtures and so this 
        // should insert
        $model = new Post; 
        $model->post_id = 10;
        $model->title = "A new post";
        $model->contents = "Here is some content";
        $model->rating = 4;
        $model->date_created = date("Y-m-d H:i:s", time()); 

        $this->mapper->save($model);

        $freshModel = $this->mapper->find(['post_id' => 10], new Post);
        $this->assertSame("A new post", $freshModel->title);
    }

    public function testModelsCanBeDeleted()
    {
        $model = $this->mapper->find(['post_id' => 1], new Post); 
        $this->mapper->delete($model);

        $freshModel = $this->mapper->find(['post_id' => 1], new Post);
        $this->assertNull($freshModel);
    }

    public function testGetValidationErrorsCallsModel()
    {
        $model = $this->createMock('DMM\Test\Fixtures\Post', array('getValidationErrors'));
        $model->expects($this->once())
              ->method('getValidationErrors');
        $this->mapper->getValidationErrors($model);
    }
}
