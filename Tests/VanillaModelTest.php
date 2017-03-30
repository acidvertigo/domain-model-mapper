<?php

namespace DMM\Test;

use DMM\BaseDomainModel;

class VanillaModelTest extends \PHPUnit_Framework_TestCase 
{
    private $model;
    
    private $modelData = array(
        'id' => 1455,
        'name' => 'John Terry',
        'salary' => 120000
    );
    
    public function setUp()
    {
        $this->model = new BaseDomainModel('id');
        $this->model->__load($this->modelData);
    }
    
    public function testIdentityMethodReturnsIdentityFieldAndValue()
    {
        $this->assertSame(array('id' => $this->modelData['id']), $this->model->__identity());
    }
    
    public function testPropertiesCanBeReadMagically()
    {
        $this->assertSame($this->modelData['name'], $this->model->name);
        $this->assertSame($this->modelData['salary'], $this->model->salary);
    }
    
    public function testMagicIssetMethod()
    {
        foreach (array_keys($this->modelData) as $key) {
            $this->assertTrue(isset($this->model->{$key}));
        }
    }
    
    public function testMagicUnsetMethod()
    {
        unset($this->model->name);
        $this->assertFalse(isset($this->model->Name));
    }
    
    public function testUnknownFieldsReturnNull()
    {
        $this->assertNull($this->model->UnknownField);
    }
    
    public function testMagicToArrayReturnsAllData()
    {
        $this->assertSame($this->modelData, $this->model->__toArray());
    }
    
    public function testNoValidationErrorsAreReturned()
    {
        $this->assertSame(array(), $this->model->getValidationErrors());
    }
    
    public function testFieldsCanBeSet()
    {
        $this->model->salary = 150000;
        $this->assertSame(150000, $this->model->salary);
    }
}
