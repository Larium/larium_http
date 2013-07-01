<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Http\Session\Handler;

class FileSessionHandler implements SessionHandlerInterface
{
    protected $path;

    protected $name;

    public function __construct($path=null)
    {
        if (null === $path) {
            $path = ini_get('session.save_path');
            if (empty($path)) {
                $path = "/tmp";
            }
        }

        $this->path = rtrim($path, DIRECTORY_SEPARATOR);

        if (!is_dir($this->path)) {
            mkdir($this->path, 0777);
        }
    }

    public function close()
    {
        return true;
    }

    public function destroy($session_id)
    {
        $file = $this->filename($session_id);
        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function gc($maxlifetime)
    {
        foreach (glob("$this->path/sess_*") as $file) {
            if (   filemtime($file) + $maxlifetime < time() 
                && file_exists($file)
            ) {
                unlink($file);
            }
        }

        return true;       
    }

    public function open($save_path, $name)
    {
        $this->name = $name;

        return true; 
    }

    public function read($session_id)
    {
        return (string)@file_get_contents($this->filename($session_id));
    }

    public function write($session_id, $session_data)
    {
        return file_put_contents($this->filename($session_id), $session_data) === false 
            ? false 
            : true; 
    }

    protected function filename($session_id)
    { 
        return $this->path 
            . DIRECTORY_SEPARATOR 
            . "sess_$session_id";
    }
}
