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
        $string = "mysql:host=" . DBHOST . ";port=3307;dbname=" . DBNAME;
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
        $check = $stm->execute($data);

        if ($check) {

            if (stripos(trim($query), 'SELECT') === 0) {
                $result = $stm->fetchAll(PDO::FETCH_OBJ);
                return [
                    'data' => $result,
                    'count' => $stm->rowCount()
                ];
            } else {
                // استعلام تعديل أو حذف أو إدراج
                return [
                    'success' => true,
                    'count' => $stm->rowCount()
                ];
            }
        }
        return false;
    }
}
