<?php

namespace Core\Connector;

use \PDO;
use \PDOStatement;

/**
 * Class Db (connector for db, extends from PDO)
 */
class Db extends PDO 
{
    protected $hasActiveTransaction = false;

    public $result = NULL;

    static protected Db $_instance;

    protected PDOStatement $statement;
    
    protected function __construct()
    {
        $dsn = "sqlite:" . PATH_TO_SQLITE_FILE;

        return parent::__construct($dsn);
    }

    /**
     * get instance
     * @return self
     */
    static public function getInstance()
    {
        if(!isset(self::$_instance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * begin transaction
     * @return bool
     */
    public function beginTransaction(): bool
    {
        if ( $this->hasActiveTransaction ) {
            return false;
        }

        $this->hasActiveTransaction = parent::beginTransaction();
        return $this->hasActiveTransaction;
    }

    /**
     * commit transaction
     * @return bool
     */
    public function commit(): bool
    {
        $result = parent::commit();
        $this->hasActiveTransaction = false;
        return $result;
    }

    /**
     * rollback transaction
     * @return bool
     */
    public function rollback(): bool
    {
        $result = parent::rollback();
        $this->hasActiveTransaction = false;
        return $result;
    }

    /**
     * run sql & return count of affected rows
     * @param string sql
     * @return int
     */
    public function execSql(string $sql): int
    {
        return $this->exec($sql);
    }

    /**
     * run sql & return result
     * @param string sql
     * @return void
     */
    public function querySql(string $sql): void
    {
        $this->statement = $this->query($sql);
    }

    /**
     * prepare sql for executing
     * @param string sql
     * @param array options
     * @return void
     */
    public function prepareSql(string $sql, array $options = []): void
    {
        $this->statement = $this->prepare($sql, $options);
    }

    /**
     * get last ID
     * @return int
     */
    public function getLastId(): int
    {
        return $this->lastInsertId();
    }

    /**
     * get sql error info
     * @return array
     */
    public function errInfo(): array
    {
        return $this->errorInfo();
    }

    /**
     * bind column for statement
     * @param column
     * @param var
     * @return bool
     */
    public function bindColumn($column, $var): bool
    {
        return $this->statement->bindColumn($column, $var);
    }

    /**
     * bind param for statement
     * @param param
     * @param var
     * @param int type
     * @return bool
     */
    public function bindParam($param, $var, int $type): bool
    {
        return $this->statement->bindColumn($param, $var, $type);
    }

    /**
     * bind value for statement
     * @param param
     * @param value
     * @param int type
     * @return bool
     */
    public function bindValue($param, $value, int $type): bool
    {
        return $this->statement->bindValue($param, $value, $type);
    }

    /**
     * close cursor for statement
     * @return bool
     */
    public function closeCursor(): bool
    {
        return $this->statement->closeCursor();
    }

    /**
     * column count for statement
     * @return PDOStatement or null
     */
    public function columnCount(): ?PDOStatement
    {
        $result = $this->statement->columnCount();

        if ($result) {
            return $result;
        }

        return null;
    }

    /**
     * debug dump params for statement
     * @return bool or null
     */
    public function debugDumpParams(): ?bool
    {
        return $this->statement->debugDumpParams();
    }

    /**
     * get sql error info for statement
     * @return array
     */
    public function errInfoStatement(): array
    {
        return $this->statement->errorInfo();
    }

    /**
     * execute sql for statement
     * @param array params
     * @return bool
     */
    public function executeStatement(array $params = []): bool
    {
        return $this->statement->execute($params);
    }

    /**
     * get one row from set
     * @return ?object
     */
    public function fetch(): ?object
    {
        $obj = $this->statement->fetch(PDO::FETCH_OBJ);

        if ($obj) {
            return $obj;
        }

        return null;
    }

    /**
     * get all rows in set
     * @return array
     */
    public function fetchAll(): array
    {
        return $this->statement->fetchAll();
    }

    /**
     * get one row from set
     * @param ?string class
     * @param array constructorArgs
     * @return object
     */
    public function fetchObject(?string $class = "stdClass", array $constructorArgs = []): object
    {
        return $this->statement->fetchObject($class, $constructorArgs);
    }

    /**
     * get one column from set
     * @param int column
     * @return object
     */
    public function fetchColumn(int $column = 0): object
    {
        return $this->statement->fetchColumn($column);
    }

    /**
     * get row count from set
     * @return int
     */
    public function rowCount(): int 
    {
        return $this->statement->rowCount();
    }
}