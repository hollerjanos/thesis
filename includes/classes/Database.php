<?php

//==============================================================================
// Database
//==============================================================================

// Creator: Holler Janos
// First release: 2023-10-30 19:24:00
// Latest update: 2023-10-30 19:24:00
// Editor: Visual Studio Code

//==============================================================================
// Includes
//==============================================================================

// Functions
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/functions.php");

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
  private string $database;

  private PDO $db;

  private bool $debug = true;

  //============================================================================
  // Construct
  //============================================================================

  public function __construct(
    string $hostname = "localhost",
    string $username = "root",
    string $password = "",
    string $database = "2fa"
  )
  {
    $this->hostname = $hostname;
    $this->username = $username;
    $this->password = $password;
    $this->database = $database;

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
   * @return bool
   */
  public function createDatabase(): bool
  {
    try
    {
      $sql = "CREATE DATABASE IF NOT EXISTS `$this->database`;";

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
   * @return bool
   */
  public function selectDatabase(): bool
  {
    try
    {
      $sql = "USE `$this->database`;";

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
   * @return bool
   */
  public function deleteDatabase(): bool
  {
    try
    {
      $sql = "DROP DATABASE IF EXISTS `$this->database`;";

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