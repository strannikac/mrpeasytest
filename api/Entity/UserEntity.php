<?php

namespace Entity;

class UserEntity 
{
    public int $id;
    public string $username;
    public string $password;
    public int $counter;
    public string $token;
    public int $lastUpdate;

    public function __construct(object $row)
    {
        $this->id = isset($row->id) ? $row->id : NULL;
        $this->username = isset($row->username) ? $row->username : '';
        $this->password = isset($row->password) ? $row->password : '';
        $this->counter = isset($row->counter) ? $row->counter : 0;
        $this->token = isset($row->token) ? $row->token : '';
        $this->lastUpdate = isset($row->last_update) ? $row->last_update : 0;
    }
}

?>