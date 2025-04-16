<?php

// Load environment variables from .env file
require_once dirname(__DIR__) . '/core/env-loader.php';
loadEnv(dirname(__DIR__) . '/.env');  // Load the .env file

class Database {
    // Instance fields
    private $host;
    private $user;
    private $pass;
    private $name;
    private $conn;

    public function __construct()
    {
        // Get database credentials from environment variables
        $this->host = getenv('DB_HOST');
        $this->user = getenv('DB_USER');
        $this->pass = getenv('DB_PASS');
        $this->name = getenv('DB_NAME');

        // Debugging output to check the values
        if (empty($this->host) || empty($this->user) || empty($this->pass) || empty($this->name)) {
            die("Database credentials are missing or incorrect in the environment variables.");
        }

        // Attempt to connect to the database
        $this->connect();
    }

    private function connect() {
        // Create the connection
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name);

        // Check if the connection was successful
        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() 
    {
        return $this->conn;
    }

    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

?>
