<?php

namespace Model;

use Entity\UserEntity;

class UserModel extends Model
{
    public function __construct()
    {
        parent::__construct();

		$this->table = 'users';
    }

    /**
     * Select all users
     * @return array
     */
    public function selectAll(): array 
    {
        $sql = 'SELECT * FROM `' . $this->table . '`';

        return $this->select($sql);
    }

    /**
     * Select user by username
     * @return ?UserEntity
     */
    public function selectByUsername(string $username): ?UserEntity 
    {
        $row = $this->selectOneByField('username', $username);

        if (!isset($row->id)) {
            return null;
        }

        return new UserEntity($row);
    }

    /**
     * Select user by token
     * @return ?UserEntity
     */
    public function selectByToken(string $token): ?UserEntity
    {
        $row = $this->selectOneByField('token', $token);

        if (!isset($row->id)) {
            return null;
        }

        return new UserEntity($row);
    }

    /**
     * update token
     * @return int
     */
    public function updateToken(string $token, int $id, int $time): int
    {
        return $this->updateById(['token' => $token, 'last_update' => $time], $id);
    }

    /**
     * update counter
     * @return int
     */
    public function updateCounter(int $counter, int $id): int
    {
        return $this->updateById(['counter' => $counter], $id);
    }

    /**
     * create new user
     * @return int
     */
    public function insertRow(string $username, string $password, string $token): ?int
    {
        $maxRow = $this->selectOne('SELECT MAX(id) AS id FROM ' . $this->table);
        $maxId = isset($maxRow->id) ? $maxRow->id + 1 : 1;

        return $this->insert([
            'id' => $maxId, 
            'username' => $username, 
            'password' => $password, 
            'counter' => 0, 
            'token' => $token, 
            'last_update' => time()
        ]);
    }
}

?>