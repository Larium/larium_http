<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Http\Session\Handler;

class MysqlSessionHandler implements SessionHandlerInterface
{

    private $mysqli;
    
    private $options;

    private $name;

    public function __construct(\mysqli $mysqli, array $options=array())
    {
        $default = array(
            'table' => 'sess_table',
            'id'    => 'sess_id',
            'data'  => 'sess_data',
            'time'  => 'sess_time'
        );

        $this->mysqli = $mysqli;
        $this->options = array_merge($default, $options);
    }

    public function close()
    {
        return true;
    }

    public function destroy($session_id)
    {
        $table = $this->options['table'];
        $id    = $this->options['id'];

        $sql = "DELETE FROM $table WHERE $id = ?";
        $stmt = $this->mysqli->prepare($sql);
        if (false === $stmt) {
            throw new \Exception($this->mysqli->error);
        }

        $stmt->bind_param('s', $session_id);
        $stmt->execute();
        $stmt->close();

        if ( 0 !== $stmt->errno ) {
            throw new \Exception($stmt->error);
        }
        
        return true;
    }

    public function gc($maxlifetime)
    {
        $table = $this->options['table'];
        $time  = $this->options['time'];

        $sql = "DELETE FROM $table WHERE $time < ?";
        $stmt = $this->mysqli->prepare($sql);
        if (false === $stmt) {
            throw new \Exception($this->mysqli->error);
        }

        $stmt->bind_param('i', time() - $maxlifetime);
        $stmt->execute();
        
        if ( 0 !== $stmt->errno ) {
            $stmt->close();
            throw new \Exception($stmt->error);
        }
        $stmt->close();
        
        return true; 
    }

    public function open($save_path, $name)
    {
        $this->name = $name;

        return true; 
    }

    public function read($session_id)
    {

        $content = $this->get_session($session_id);

        if ($content) {
            $data = $this->options['data'];
            return base64_decode($content[$data]);
        } else {

            return '';
        }
    }

    public function write($session_id, $session_data)
    {
        $content = $this->get_session($session_id);

        $table = $this->options['table'];
        $data  = $this->options['data'];
        $id    = $this->options['id'];
        $time  = $this->options['time'];

        $session_data = base64_encode($session_data);
        
        if (null === $content) {
            $sql = "INSERT INTO $table ($id, $data, $time) VALUES (?, ?, ?)";
            $stmt = $this->mysqli->prepare($sql);
            if (false === $stmt) {
                throw new \Exception($this->mysqli->error);
            }

            $time = time();
            $stmt->bind_param('ssi', $session_id, $session_data, $time);
            $stmt->execute();
            if ( 0 !== $stmt->errno ) {
                $stmt->close();
                throw new \Exception($stmt->error);
            }
            $stmt->close();

        } else {
            $sql = "UPDATE $table SET $data = ? WHERE $id = ?";
            $stmt = $this->mysqli->prepare($sql);
            if (false === $stmt) {
                throw new \Exception($this->mysqli->error);
            }

            $stmt->bind_param('ss', $session_data, $session_id);
            $stmt->execute();
            if ( 0 !== $stmt->errno ) {
                $stmt->close();
                throw new \Exception($stmt->error);
            }       
            $stmt->close();
        }

        return true;
    }

    private function get_session($session_id)
    {

        $table = $this->options['table'];
        $data  = $this->options['data'];
        $id    = $this->options['id'];

        $sql = "SELECT $data FROM $table WHERE $id = ?";
        $stmt = $this->mysqli->prepare($sql);
        if (false === $stmt) {
            throw new \Exception($this->mysqli->error);
        }

        $stmt->bind_param('s', $session_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ( 0 !== $stmt->errno ) {
            $stmt->close();
            throw new \Exception($stmt->error);
        }
       
        $stmt->close();

        return $result->fetch_assoc();       
    }
}
