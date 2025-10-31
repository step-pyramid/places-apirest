<?php
// app/Config/Database.php
namespace App\Config;

class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $charset;
    public $conn;

    public function __construct() {
        // Read database configuration from ini file
        $config = parse_ini_file(__DIR__ . '/database.ini');
        $this->host = $config['host'];
        $this->db_name = $config['dbname'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->charset = $config['charset'];
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new \PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset,
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch(\PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}