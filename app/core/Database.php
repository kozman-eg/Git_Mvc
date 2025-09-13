<?php

namespace Core;

use PDO;
use PDOException;

defined('ROOTPATH') or exit('access allwoed');

// Database connection


trait Database
{
    private function connect()
    {
        $string = "mysql:host=" . DBHOST . ";port=3306;dbname=" . DBNAME;
        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        ];

        try {
            $con = new PDO($string, DBUSER, DBPASS, $options);
            return $con;
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    public function query($query, $data = [])
    {
        $con = $this->connect();
        $stm = $con->prepare($query);
        $success = $stm->execute($data);

        $isSelect = stripos(trim($query), 'SELECT') === 0 
                    || stripos(trim($query), 'DESCRIBE') === 0
                    || stripos(trim($query), 'SHOW') === 0;

        if ($isSelect) {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);
            return [
                'success' => $success,
                'data'    => $result,
                'count'   => $stm->rowCount()
            ];
        } else {
            return [
                'success' => $success,
                'data'    => [], 
                'count'   => $stm->rowCount()
            ];
        }
    }
}
