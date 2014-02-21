<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Http\Session;

use Larium\Http\Session\Handler\FileSessionHandler;

class Session implements SessionInterface, \ArrayAccess, \Countable
{
    protected $handler;

    protected $started = false;

    protected $params = array();

    protected $storage;

    /**
     * Creates a new Session instance.
     *
     * @param Larium\Http\Session\Handler\SessionHandlerInterface |
     *        \SessionHandlerInterface $handler
     * @param array $params Cookie params and name for current session.
     *
     * @access public
     * @return void
     */
    public function __construct($handler=null, array $params=array())
    {
        if (null !== $handler
            && ($handler instanceof \SessionHandlerInterafce
            || $handler instanceof SessionHandlerInterface)
        ) {
            $this->setSaveHandler($handler);
        }
        $this->setCookieParams($params);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return session_id();
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        if ($this->started) {
            throw new \RuntimeException("Session id must be set before session starts.");
        }

        session_id($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return session_name();
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        if (false===$this->started) {
            session_name($name);
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function regenerateId($delete_old_sesssion=false)
    {

        if ($delete_old_sesssion) {
            $this->storage = array();
        }

        $return = session_regenerate_id($delete_old_sesssion);

        session_write_close();
        $backup = $_SESSION;
        session_start();
        $_SESSION = $backup;

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, $value)
    {
        return $this->__set($name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        return $this->__get($name);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->storage = array();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($name)
    {
        $this->offsetUnset($name);
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return $this->__isset($name);
    }

    public function __get($name)
    {
        return array_key_exists($name, $this->storage)
            ? $this->storage[$name]
            : null;
    }

    public function __set($name, $value)
    {
        $this->storage[$name] = $value;
    }

    public function __isset($name)
    {
        return array_key_exists($name, $this->storage);
    }

    /**
     * {@inheritdoc}
     */
    public function getCookieParams()
    {
        return session_get_cookie_params();
    }

    /**
     * {@inheritdoc}
     */
    public function setCookieParams(array $params)
    {
        $default = array(
            'lifetime'      => 10800,
            'path'          => '/',
            'domain'        => '',
            'secure'        => false,
            'httponly'      => false,
            'session_name'  => 'PHPSESSID'
        );

        $this->params = array_merge($default, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        if (!$this->getId() || $this->started == false) {

            session_set_cookie_params(
                $this->params['lifetime'],
                $this->params['path'],
                $this->params['domain'],
                $this->params['secure'],
                $this->params['httponly']
            );

            $this->setName($this->params['session_name']);

            session_start();

            $this->started = true;
            $this->storage = &$_SESSION;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setSaveHandler($handler)
    {
        $this->handler = $handler;

        session_set_save_handler(
            array($this->handler, 'open'),
            array($this->handler, 'close'),
            array($this->handler, 'read'),
            array($this->handler, 'write'),
            array($this->handler, 'destroy'),
            array($this->handler, 'gc')
        );
    }

    public function getSaveHandler()
    {
        return $this->handler;
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        session_write_close();
    }

    /**
     * {@inheritdoc}
     */
    public function getExpire()
    {
        return session_cache_expire();
    }

    /**
     * {@inheritdoc}
     */
    public function setExpire($new_cache_expire)
    {
        session_cache_expire($new_cache_expire);
    }

    /**
     * {@inheritdoc}
     */
    public function setCacheLimiter($name)
    {
        $names = array('public', 'private_no_expire', 'private', 'nocache');

        if (!in_array($name, $names)) {
            throw new \InvalidArgumentException(sprintf("Invalid name %s. Should be one of %s", $name, implode(',',$names)));
        }

        session_cache_limiter($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheLimiter()
    {
        return session_cache_limiter();
    }

    /**
     * {@inheritdoc}
     */
    public function decode($data)
    {
       return session_decode($data);
    }

    /**
     * {@inheritdoc}
     */
    public function encode()
    {
        return session_encode();
    }

    /* -(  ArrayAccess  )--------------------------------------------------- */

    public function offsetExists($name)
    {
        return $this->__isset($name);
    }

    public function offsetGet($name)
    {
        return $this->$name;
    }

    public function offsetSet($name, $value)
    {
        $this->name = $value;
    }

    public function offsetUnset($name)
    {
        unset($this->storage[$name]);
    }

    /* -(  Countable  )---------------------------------------------------- */

    public function count()
    {
        return count($this->storage);
    }
}
