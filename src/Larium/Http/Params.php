<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Http;

class Params implements \ArrayAccess, \Countable
{
    protected $storage;

    public function __construct(array $storage = array())
    {
        $this->storage = $storage;
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    public function get($name)
    {
        return $this->offsetGet($name);
    }

    public function set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    public function add(array $array)
    {
        $this->storage = array_replace($this->storage, $array);
    }

    public function remove($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * Returns an array copy of storage.
     * 
     * @access public
     * @return array
     */
    public function getArrayCopy()
    {
        return $this->storage;
    }

    /**
     * alias of getArrayCopy
     * 
     * @access public
     * @return void
     */
    public function toArray()
    {
        return $this->getArrayCopy();
    }

    /* -(  Iterator  )------------------------------------------------------ */

    public function rewind()
    {
        reset($this->storage);
    }

    public function current()
    {
        return current($this->storage);
    }

    public function key()
    {
        return key($this->storage);
    }

    public function next()
    {
        next($this->storage);
    }

    public function valid()
    {
        return current($this->storage) !== false;
    }

    /* -(  ArrayAccess  )--------------------------------------------------- */
    
    public function offsetSet($offset, $value) 
    {
        if (is_null($offset)) {
            $this->storage[] = $value;
        } else {
            $this->storage[$offset] = $value;
        }
    }
    
    public function offsetExists($offset) 
    {
        return array_key_exists($offset, $this->storage);
    }
    
    public function offsetUnset($offset) {

        if ($this->offsetExists($offset)) {
            unset($this->storage[$offset]);
        }
    }

    public function offsetGet($offset) 
    {
        $value = $this->offsetExists($offset) 
            ? $this->storage[$offset] 
            : null;
        
        if (is_array($value)) {
            return new self($value);
        }

        return $value;
    }
    
    /* -( Countable ) ------------------------------------------------------ */

    public function count()
    {
        return count($this->storage);
    }
}
