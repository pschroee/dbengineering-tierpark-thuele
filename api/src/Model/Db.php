<?php

namespace App\Model;

use \PDO;

class Db
{
    public function connect()
    {
        require_once __DIR__ . '/../../config.php';

        $conn_str = "mysql:host=$DBHOST;port=$DBPORT;dbname=$DBNAME";
        $conn = new PDO($conn_str, $DBUSER, $DBPASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }
}
