<?php

/**
 * Description of sql
 *
 */
class SQL {
    protected static $host = null;
    protected static $user = null;
    protected static $pwd = null;
    protected static $db = null;
    
    public static function configure(string $host, string $user, string $pwd, string $db): void {
        static::$host = $host;
        static::$user = $user;
        static::$pwd = $pwd;
        static::$db = $db;
    }
    
    public static function redefineDbname(string $db): void {
        static::$db = $db;
    }
    
    protected static $instance = null;
    public static function getInstance(): SQL {
        if(null === static::$instance) {
            static::$instance = new SQL();
        }
        return static::$instance;
    }
    
    /**
     * PDO
     * 
     */
    protected $co = null;
    
    public function __construct() {
        $this->co = new PDO('mysql:host='.static::$host, static::$user, static::$pwd, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        $this->warmup();
    }
    
    public function warmup() {
        $this->co->query('create database if not exists `'.static::$db.'`');
        $this->co->query('use `'.static::$db.'`');
    }
    
    public function execQuery(string $query, array $args = []): PDOStatement {
        $stmt = $this->co->prepare($query);
        foreach($args as $k => $v) {
            $stmt->bindValue(':'.$k, $v);
        }
        $stmt->execute();
        return $stmt;
    }
    
    public function q(string $query, array $args = []): int {
        $stmt = $this->execQuery($query, $args);
        return $stmt->rowCount();
    }
    
    public function qa(string $query, array $args = []): array {
        $stmt = $this->execQuery($query, $args);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function qo(string $query, array $args = [], $def = null) {
        $stmt = $this->execQuery($query, $args);
        return $stmt->rowCount()? $stmt->fetch(PDO::FETCH_ASSOC):$def;
    }
    
    public function qi(string $query, array $args = [], $def = null) {
        $stmt = $this->execQuery($query, $args);
        return ($stmt->errorCode() == PDO::ERR_NONE)? $this->co->lastInsertId():$def;
    }
}
