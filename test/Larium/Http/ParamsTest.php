<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Http;

class ParamsTest extends \PHPUnit_Framework_TestCase
{

    public function testToArray()
    {
        $array = array('key'=>'value');
        $params = new Params($array);

        $this->assertEquals($array, $params->toArray(), '->toArray() should return the input array.');
        
        $this->assertEquals($array, $params->getArrayCopy(), '->getArrayCopy() should return the input array.');
    }
    
    public function testAdd()
    {

        $array = array('key'=>'value');
        $params = new Params($array);

        $params->add(array('k'=>'v'));

        $this->assertEquals(array('key'=>'value', 'k'=>'v'), $params->toArray());
        
        $params->add(array('key'=>'new'));
        
        $this->assertEquals(array('key'=>'new', 'k'=>'v'), $params->toArray());
    }
    
    public function testRemove()
    {

        $array = array('key'=>'value');
        $params = new Params($array);

        $params->add(array('k'=>'v'));

        $this->assertEquals(array('key'=>'value', 'k'=>'v'), $params->toArray());
        
        $params->remove('key');
        
        $this->assertEquals(array('k'=>'v'), $params->toArray());       
    }
    
    public function testGetValues()
    {

        $array = array('key'=>'value');
        $params = new Params($array);

        $params->add(array('k'=>array('foo'=>'bar')));

        $this->assertEquals('value', $params->key);
        $this->assertEquals('value', $params['key']);
        
        $this->assertEquals('bar', $params->k->foo);
        $this->assertEquals('bar', $params['k']['foo']);

        $this->assertNull($params->bar);
    }

    public function testCount()
    {
        $array = array('key'=>'value');
        $params = new Params($array);

        $this->assertEquals(1, $params->count());

        $params->add(array('k'=>'v'));
        
        $this->assertEquals(2, $params->count());
    }
}
