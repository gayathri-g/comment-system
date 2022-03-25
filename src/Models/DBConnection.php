<?php

namespace App\Models;

use \PDO;

class DBConnection 
{
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'tvstestjob';

    public function connect()
    {
        try {
            $_con = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);    
            $_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $_con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        return $_con;
    }
}
