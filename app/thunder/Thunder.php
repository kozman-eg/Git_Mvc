<?php

namespace Thunder;
// http://localhost/MVC/Git_Mvc/public/login
// C:\xampp\htdocs\MVC\Git_Mvc
defined('CPATH') or exit('access allwoed');

class Thunder
{

    private $version = '1.0.0';
    public function db($argv)
    {
        $db = new Database;
        $word = new \Core\Inflector;

        $mode = $argv[1] ?? null;
        $param1 = $argv[2] ?? null;
        $DS =  DIRECTORY_SEPARATOR;
        $parts = explode(":", $mode);
        // clean class name: remove any invalid chars at the start
        $param1 = preg_replace("/^[^a-zA-Z_]+/", "", $param1);

        // check if class name starts with a number
        if (preg_match("/^[0-9]/", $param1)) {
            die("\n\rClass names can't start with a number");
        }


        $param1 = $word->isPlural($param1) ? $word->singularize($param1) :  $param1;
        $param1 = ucfirst($param1) ?? '';


        switch ($parts[1]) {

            case 'cerate':

                if (empty($param1)) {
                    die("\n\rPlaease provide a database name\r\n");
                }

                $query = "CREATE DATABASE IF NOT EXISTS " . $param1;
                $db->query($query);
                die("\n\Database  $param1 created successfully!\r\n");

                break;

            case "table":
                if (!$param1) {
                    echo "Please provide a table name.\n";
                    return;
                }

                $param1 = $word->pluralize(strtolower($param1));
                $query = "DESCRIBE " . $param1;


                $rows = $db->query($query);

                if (!$rows) {
                    echo "No table found with name: $param1\n";
                    return;
                }
                print_r($rows['data']);
                try {
                    if ($rows && !empty($rows['data'])) {
                        // تنسيقات الطباعة
                        echo "\n+-----------------+-----------------+------+-----+---------+----------------+\n";
                        echo   "| Field           | Type            | Null | Key | Default | Extra          |\n";
                        echo   "+-----------------+-----------------+------+-----+---------+----------------+\n";

                        foreach ($rows['data'] as $row) {
                            printf(
                                "| %-15s | %-15s | %-4s | %-3s | %-7s | %-14s |\n",
                                $row->Field,
                                $row->Type,
                                $row->Null,
                                $row->Key,
                                $row->Default ?? NULL,
                                $row->Extra
                            );
                        }

                        echo "+-----------------+-----------------+------+-----+---------+----------------+\n\n";
                    } else {
                        die("\n\rthis $param1 exist but her empty" . PHP_EOL . "\r\n");
                    }
                } catch (\PDOException $e) {
                    die("\n\rthis $param1 not exist" . PHP_EOL . "\r\n");
                }
                break;

            case 'seed':
                break;
            case 'drop':

                if (empty($param1)) {
                    die("\n\rPlaease provide a database name\r\n");
                }

                $query = "drop database " . $param1;
                $db->query($query);
                die("\n\drop Database  $param1 successfully!\r\n");

                break;
            default:
                die("\n\rUndnown commend $argv[1] \n\r");
                break;
        }
    }
    public function make($argv)
    {
        $word = new \Core\Inflector;

        $mode = $argv[1] ?? null;
        $classname = $argv[2] ?? null;
        $DS =  DIRECTORY_SEPARATOR;
        $parts = explode(":", $mode);
        //check if class name id empty
        if (empty($classname)) {
            die("\n\rPlease provida a class name \r\n");
        }
        // clean class name: remove any invalid chars at the start
        $classname = preg_replace("/^[^a-zA-Z_]+/", "", $classname);

        // check if class name starts with a number
        if (preg_match("/^[0-9]/", $classname)) {
            die("\n\rClass names can't start with a number");
        }


        $classname = $word->isPlural($classname) ? $word->singularize($classname) :  $classname;
        $classname = ucfirst($classname) ?? '';


        switch ($parts[1]) {

            case 'controller':

                $filename = __DIR__ . $DS . ".." . $DS . "controllers" . $DS . $classname . ".php";

                if (empty($classname)) {
                    die("\n\rPlaease provide a controller name\r\n");
                }
                if (file_exists($filename)) {
                    die("\n\rThat controller already exists\r\n");
                }

                $template = file_get_contents(__DIR__ . $DS . 'samples' . $DS . 'controller-sample.php');

                $content = str_replace("{CLASSNAME}", $classname, $template);
                $content = str_replace("{classname}", strtolower($classname), $content);

                if (file_put_contents($filename, $content)) {
                    die("\n\rController $classname created successfully!\r\n");
                } else {
                    die("\n\rFailed to create Controller due to an error\r\n");
                }
                break;
            case 'model':

                $filename = __DIR__ . $DS . ".." . $DS . "models" . $DS . $classname . ".php";

                if (empty($classname)) {
                    die("\n\rPlaease provide a model name\r\n");
                }
                if (file_exists($filename)) {
                    die("\n\rThat model already exists\r\n");
                }

                $template = file_get_contents(__DIR__ . $DS . 'samples' . $DS . 'model-sample.php');

                $content = str_replace("{CLASSNAME}", $classname, $template);
                // onlu as 's' at the and of table name if it doesnt exits

                $content = str_replace("{table}", strtolower($word->pluralize($classname)), $content);

                if (file_put_contents($filename, $content)) {
                    die("\n\model $classname created successfully!\r\n");
                } else {
                    die("\n\rFailed to create Controller due to an error\r\n");
                }
                break;
            case 'migration':
                break;
            case 'seeder':
                break;
            case 'cerate':
                break;
            case 'seed':
                break;
            case 'table':
                break;
            case 'drop':
                break;
            case 'refresh':
                break;
            case 'tollback':
                break;
            default:
                die("\n\rUndnown commend $argv[1] \n\r");
                break;
        }
    }
    public function migeate()
    {
        echo "\n\rthis id the make funtion\n\r";
    }
    public function help()
    {
        echo "
        Thunder v$this->version Command Line Tool

        Database 
            db:cerate           Creatr a new database schema.
            db:seed             Runs the specifies seeder ti populate known data into database.
            db:table            Retrieves information on the selecter table.
            db:drop             Drop/Dalete a database.
            migrate             Lpcates and tuns a migration from the specifed plugin folder.
            migrate:refresh     Does a rollback by a latest to refresh the currdnt state of the database.
            migrate:tollback    Runs the 'down' method for a migration in the specifiled plugin folder.
  
        Generators
            make:migration      Generates a new migration file.
            make:controller     Generates a new controller file.
            make:model          Generates a new model file.
            make:seeder         Generates a new seder file.
        ";
    }
}
