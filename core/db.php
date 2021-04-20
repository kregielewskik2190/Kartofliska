<?php

class Database {
    private static $instance = null;
    private $db;

    public function __construct(){
        $host = 'mysql-970513.vipserv.org';
        $user = 'kregielew_kartof';
        $pass = 'MugDzHtR8T9LHtDnIF4k7tjCgwZwMKQz';
        $db_name = 'kregielew_kartof';
        try {
            $this->db = new PDO("mysql:host={$host};dbname={$db_name}", $user, $pass);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die ("DB Error: ".$e->getMessage());
        }
    }

    public static function getDB() {
        if(self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->db;
    }
}