<?php

//==============================================================================
// Includes
//==============================================================================

// Database
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/classes/Database.php");

//==============================================================================
// Process
//==============================================================================

try
{
  // Connection
  $database = new Database();

  $database->deleteDatabase();

  $database->createDatabase();

  $database->selectDatabase();

  $database->createTable(
    "users",
    [
      "id" => "INT NOT NULL AUTO_INCREMENT PRIMARY KEY",
      "username" => "VARCHAR(100) NOT NULL",
      "password" => "VARCHAR(50) NOT NULL",
    ]
  );

  $database->insertIntoTable(
    "users",
    [
      "username" => "Jani",
      "password" => "pw123"
    ]
  );
}
catch (Exception $exception)
{
  display(
    "Database create | Exception caught",
    $exception->getMessage()
  );
}