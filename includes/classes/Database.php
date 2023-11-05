<?php

//==============================================================================
// Database
//==============================================================================

// Creator: Holler Janos
// First release: 2023-10-30 19:24:00
// Latest update: 2023-11-01 11:35:00
// Editor: PhpStorm 2022.2.3

//==============================================================================
// Namespace
//==============================================================================

namespace includes\classes;

//==============================================================================
// Includes
//==============================================================================

// Functions
require_once("../functions.php");

//==============================================================================
// Imports
//==============================================================================

use PDO;
use Exception;

//==============================================================================
// Process
//==============================================================================

class Database
{
    //============================================================================
    // Properties
    //============================================================================

    private string $hostname;
    private string $username;
    private string $password;

    private PDO $db;

    private bool $debug = true;

    //============================================================================
    // Construct
    //============================================================================

    public function __construct(
        string $hostname = "localhost",
        string $username = "root",
        string $password = ""
    )
    {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;

        $this->connection();

        $this->setAttributes();
    }

    /**
     * <p>Database connection</p>
     * @return void
     */
    private function connection(): void
    {
        // Data source name
        $dsn = "mysql:host=$this->hostname";

        $this->db = new PDO($dsn, $this->username, $this->password);
    }

    /**
     * <p>Set attributes</p>
     * @return void
     */
    private function setAttributes(): void
    {
        $this->db->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
        $this->db->setAttribute(
            PDO::ATTR_DEFAULT_FETCH_MODE,
            PDO::FETCH_ASSOC
        );
    }

    /**
     * <p>Create database</p>
     * @param string $database
     * @return bool
     */
    public function createDatabase(
        string $database
    ): bool {
        try
        {
            $sql = "CREATE DATABASE IF NOT EXISTS `$database`;";

            if ($this->debug)
                display(
                    "Database | createDatabase()",
                    [
                        "sql" => $sql
                    ]
                );

            $statement = $this->db->prepare($sql);
            $statement->execute();

            return true;
        }
        catch (Exception $exception)
        {
            if ($this->debug)
                exception(
                    "Database | createDatabase()",
                    [
                        "message" => $exception->getMessage()
                    ]
                );

            return false;
        }
    }

    /**
     * <p>Select database</p>
     * @param string $database
     * @return bool
     */
    public function selectDatabase(
        string $database
    ): bool {
        try
        {
            $sql = "USE `$database`;";

            if ($this->debug)
                display(
                    "Database | selectDatabase()",
                    [
                        "sql" => $sql
                    ]
                );

            $statement = $this->db->prepare($sql);
            $statement->execute();

            return true;
        }
        catch (Exception $exception)
        {
            if ($this->debug)
                exception(
                    "Database | selectDatabase()",
                    [
                        "message" => $exception->getMessage()
                    ]
                );

            return false;
        }
    }

    /**
     * <p>Create table</p>
     * @param string $name
     * @param array $config
     * @return bool
     */
    public function createTable(string $name, array $config): bool
    {
        try
        {
            $attributes = [];
            foreach ($config as $key => $item)
                $attributes[] = "`$key` $item";
            $attributes = implode(", ", $attributes);

            $sql  = "CREATE TABLE IF NOT EXISTS `$name` ($attributes);";

            if ($this->debug)
                display(
                    "Database | createTable()",
                    [
                        "sql" => $sql,
                        "config" => $config
                    ]
                );

            $statement = $this->db->prepare($sql);
            $statement->execute();

            return true;
        }
        catch (Exception $exception)
        {
            if ($this->debug)
                exception(
                    "Database | createTable()",
                    $exception->getMessage()
                );

            return false;
        }
    }

    /**
     * <p>Insert into table</p>
     * @param string $name
     * @param $data
     * @return bool
     */
    public function insertIntoTable(string $name, $data): bool
    {
        try
        {
            $keys = "`" . implode("`, `", array_keys($data)) . "`";
            $items = ":" . implode(", :", array_keys($data));

            $sql  = "INSERT INTO `$name` ($keys) VALUES ($items);";

            if ($this->debug)
                display(
                    "Database | insertIntoTable()",
                    [
                        "sql" => $sql,
                        "data" => $data
                    ]
                );

            $statement = $this->db->prepare($sql);
            $statement->execute($data);

            return true;
        }
        catch (Exception $exception)
        {
            if ($this->debug)
                exception(
                    "Database | insertIntoTable()",
                    [
                        "message" => $exception->getMessage()
                    ]
                );

            return false;
        }
    }

    /**
     * <p>Delete database</p>
     * @param string $database
     * @return bool
     */
    public function deleteDatabase(
        string $database
    ): bool {
        try
        {
            $sql = "DROP DATABASE IF EXISTS `$database`;";

            if ($this->debug)
                display(
                    "Database | deleteDatabase()",
                    [
                        "sql" => $sql
                    ]
                );

            $statement = $this->db->prepare($sql);
            $statement->execute();

            return true;
        }
        catch (Exception $exception)
        {
            if ($this->debug)
                exception(
                    "Database | deleteDatabase()",
                    [
                        "message" => $exception->getMessage()
                    ]
                );

            return false;
        }
    }
}
