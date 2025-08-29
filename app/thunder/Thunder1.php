<?php

/**
 * Thunder CLI Tool
 * version 1.0.0
 */

namespace Thunder;

defined('CPATH') or exit('access allwoed');

class Thunders
{
    private $version = '1.0.0';
    private  $DS =  DIRECTORY_SEPARATOR;

    public function run($tunder, $command = null, $arg = null)
    {
        $classname = ucfirst($arg) ?? '';

        $parts = explode(":", $command);

        switch ($parts[0]) {
            case "make":
                $this->make($parts[1] ?? '', $arg);
                break;

            case "db":
                $this->db($parts[1] ?? '', $arg);
                break;

            case "migrate":
                $this->migrate($parts[1] ?? '');
                break;

            case "help":
            default:
                $this->help();
                break;
        }
    }

    /**
     * Generators (make:controller, make:model, ...)
     */
    public function make($target, $classname)
    {
        $DS = $this->DS;
        $classname = ucfirst($classname) ?? '';

        $filename = __DIR__ . $DS . ".." . $DS . "controllers" . $DS . $classname . ".php";
        if (empty($classname)) {
            die("❌ Please provide a name. Example: php thunder make:controller Home\n");
        }

        $templatePath = __DIR__ . $DS . "samples";
        $controllerPath = dirname(__DIR__, 1) . $DS . "controllers";
        $modelPath = dirname(__DIR__, 1) . $DS . "models";
        $migrationPath = dirname(__DIR__, 1) . $DS . "migrations";
        $seederPath = dirname(__DIR__, 1) . $DS . "seeders";

        switch ($target) {
            case 'controller':
                $this->generateFile(
                    $templatePath . $DS . "controller-sample.php",
                    $controllerPath . $DS . "{$classname}.php",
                    $classname
                );
                break;

            case 'model':
                $this->generateFile(
                    $templatePath . $DS . "model-sample.php",
                    $modelPath . $DS . "{$classname}.php",
                    $classname
                );
                break;

            case 'migration':
                echo "📂 Creating migration file: $classname.php\n";
                break;

            case 'seeder':
                echo "🌱 Creating seeder file: $classname.php\n";
                break;

            default:
                print_r($target);
                print_r($classname);

                echo "❌ Unknown make command\n";
        }
    }

    /**
     * Helper to generate file from template
     */
    private function generateFile($templatePath, $outputPath, $classname)
    {
        if (!file_exists($templatePath)) {
            die("❌ Template not found: $templatePath\n");
        }

        $template = file_get_contents($templatePath);
        $content = str_replace("{CLASSNAME}", $classname, $template);
        $content = str_replace("{CLASSNAME}", strtolower($classname), $template);

        if (file_exists($outputPath)) {

            die("⚠️ File already exists: $outputPath\n");
        }

        file_put_contents($outputPath, $content);
        die("\n\rController $classname created successfully!\r\n✅ Created: $outputPath\n");
    }
    /**
     * Database commands
     */
    public function db($action, $arg = null)
    {
        switch ($action) {
            case 'create':
                echo "✅ Database '$arg' created successfully!\n";
                break;

            case 'seed':
                echo "🌱 Seeding database with '$arg'...\n";
                break;

            case 'table':
                echo "📋 Table info for '$arg'\n";
                break;

            case 'drop':
                echo "⚠️ Database '$arg' dropped!\n";
                break;

            default:
                echo "❌ Unknown db command\n";
        }
    }

    /**
     * Handle migration commands
     */
    private function migrate($action = '')
    {
        switch ($action) {
            case "refresh":
                echo "🔄 Migration refreshed...\n";
                break;
            case "rollback":
                echo "↩️ Migration rolled back...\n";
                break;
            case "":
                echo "🚀 Running migrations...\n";
                break;
            default:
                echo "❌ Unknown migration command\n";
        }
    }

    /**
     * Help Menu
     */
    public function help()
    {
        echo "
        Thunder v$this->version Command Line Tool

        Database 
            db:create           Create a new database schema.
            db:seed             Runs the specified seeder to populate known data into database.
            db:table            Retrieves information on the selected table.
            db:drop             Drop/Delete a database.

        Migration
            migrate             Locates and runs a migration from the specified plugin folder.
            migrate:refresh     Does a rollback and re-run to refresh the current state of the database.
            migrate:rollback    Runs the 'down' method for a migration in the specified plugin folder.

        Generators
            make:controller     Generates a new controller file.
            make:model          Generates a new model file.
            make:migration      Generates a new migration file.
            make:seeder         Generates a new seeder file.
            
            Usage:
            php thunder.php make:controller HomeController
            php thunder.php make:model Product
            ";
    }
}

// -------- Start --------


$parts = explode(":", $action);
if ($parts[0] == 'make') {
    $method = 'run';
} else {
    $method = $parts[0];
}
