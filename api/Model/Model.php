<?php

namespace Model;

use Core\Connector\Db;

abstract class Model
{
    private Db $db;
    protected string $table = '';

    public function __construct()
    {
        $this->db = Db::getInstance();

        $this->createTables();
    }

    protected function createTables(): void {
        $commands = [
            'CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY,
                username NVARCHAR (75) UNIQUE,
                password NVARCHAR (255),
                token VARCHAR (255),
                counter INTEGER NOT NULL,
                last_update INTEGER NOT NULL
            )'
        ];

        foreach ($commands as $command) {
            $this->exec($command);
        }
    }

    /**
     * prepare query
     * @param string $sql
     * @return void
     */
    public function prepare(string $sql): void
    {
        $this->db->prepareSql($sql);
    }

    /**
     * Prepare and exec sql
     * @param string $sql
     * @return bool
     */
    public function execPrepared(string $sql): bool 
    {
        $this->prepare($sql);
    	return $this->db->executeStatement();
    }

    /**
     * Exec sql and return number of affected rows
     * @param string $sql
     * @return int
     */
    public function exec(string $sql): int 
    {
    	return $this->db->execSql($sql);
    }

    /**
     * Query sql and create statement object
     * @param string $sql
     * @return void
     */
    public function query(string $sql): void
    {
        $this->db->querySql($sql);
    }

    /**
     * Get last id
     * @return int
     */
    public function getLastId(): int 
    {
        return $this->db->getLastId();
    }

    /**
     * Select all rows
     * @param string $sql
     * @return array
     */
	public function select(string $sql): array 
    {
        $this->query($sql);

        $result = $this->db->fetchAll();

        if (count($result) < 1) {
            return [];
        }

        return $result;
	}

    /**
     * Select one row
     * @param string $sql
     * @return ?object
     */
	public function selectOne(string $sql): ?object 
    {
        $this->db->querySql($sql);
		return $this->db->fetch();
	}

    /**
     * Select one row by id
     * @param int $id
     * @param array $fields
     * @param string $name (field of id)
     * @return ?object
     */
	public function selectById(int $id, array $fields = [], string $name = 'id'): ?object 
    {
		return $this->selectOneByField($name, "{$id}", $fields);
	}

    /**
     * Select one row by field
     * @param string $name
     * @param string $value
     * @param array $fields
     * @return ?object
     */
	public function selectOneByField(string $name, string $value, array $fields = []): ?object 
    {
		$fieldNames = '*';
		if (count($fields) > 0) {
			$fieldNames = '`' . implode('`, `', $fields) . '`';
		}

		$sql = 'SELECT ' . $fieldNames . ' FROM `' . $this->table . '`
            WHERE `' . $name . '` = \'' . $value . '\'';

        return $this->selectOne($sql);
	}

    /**
     * Select all rows by where cause
     * @param array $fields
     * @param string $where
     * @param string $orderBy
     * @return array
     */
	public function selectBy(array $fields = [], string $where = '', string $orderBy = ''): array 
    {
		$fieldNames = '*';
		if (count($fields) > 0) {
			$fieldNames = '`' . implode('`, `', $fields) . '`';
		}

		$sql = 'SELECT ' . $fieldNames . ' FROM `' . $this->table . '`';

        if ($where != '') {
            $sql .= ' WHERE ' . $where;
        }

        if ($orderBy != '') {
            $sql .= ' ORDER BY ' . $orderBy;
        }

        return $this->select($sql);
	}

    /**
     * Check unique value
     * @param string $field
     * @param $value
     * @return bool
     */
	public function isUniqueValue(string $field, $value): bool 
    {
		$sql = 'SELECT COUNT(*) AS count FROM `' . $this->table . '` 
			WHERE `' . $field . '` = \'' . $value . '\'';

        $row = $this->selectOne($sql);

        if ($row->count > 0) {
			return false;
		}

		return true;
	}

    /**
     * Save some data only by id
     * @param array $arr
     * @param $id
     * @param string $name
     * @return int
     */
	public function updateById(array $arr, $id, string $name = 'id'): int 
    {
		$setFields = [];

		foreach($arr as $field => $val) {
            $setFields[] = '`' . $field . '` = \'' . $val . '\'';
		}

        if (count($setFields) > 0) {
            $sql = 'UPDATE `' . $this->table . '` 
                SET ' . implode(',', $setFields) . ' 
                WHERE `' . $name . '` = \'' . $id . '\'';
    
            return $this->exec($sql);
        }

        return 0;
	}

    /**
     * Save some data only by where cause
     * @param array $arr
     * @param string $where
     * @return int
     */
	public function updateBy(array $arr, string $where): int 
    {
		$setFields = [];

		foreach($arr as $field => $val) {
            $setFields[] = '`' . $field . '` = \'' . $val . '\'';
		}

        if (count($setFields) > 0) {
            $sql = 'UPDATE `' . $this->table . '` 
                SET ' . implode(',', $setFields) . ' 
                WHERE ' . $where;
    
            return $this->exec($sql);
        }

        return 0;
	}

    /**
     * Add row 
     * @param array or object
     * @return int
     */
	public function insert($arr): int 
    {
		$fields = [];
		$values = [];

		foreach($arr as $field => $val) {
			$fields[] = '`' . $field . '`';
			$values[] = '\'' . $val . '\'';
		}

        if (count($fields) > 0) {
            $sql = 'INSERT INTO `' . $this->table . '` 
                (' . implode(',', $fields) . ') 
                VALUES (' . implode(',', $values) . ')';
    
            return $this->exec($sql);
        }

        return 0;
	}

    /**
     * Delete row by id
     * @param int $id
     * @param string $name
     * @return int
     */
	public function deleteById(int $id, string $name = 'id'): int 
    {
		$sql = 'DELETE FROM `' . $this->table . '` 
			WHERE `' . $name . '` = \'' . $id . '\'';

        return $this->exec($sql);
	}

    /**
     * Delete row(s) by where cause
     * @param string $where
     * @return int
     */
	public function deleteBy(string $where): int 
    {
		$sql = 'DELETE FROM `' . $this->table . '` 
			WHERE ' . $where;

        return $this->exec($sql);
	}
}