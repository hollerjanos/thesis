<?php

//==============================================================================
// Includes
//==============================================================================

// Constants
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/constants.php");

// Database
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/classes/Database.php");

//==============================================================================
// Imports
//==============================================================================

use includes\classes\Database;

//==============================================================================
// Process
//==============================================================================

try
{
    // Connection
    $database = new Database(
        DB_HOSTNAME,
        DB_USERNAME,
        DB_PASSWORD
    );

    $database->deleteDatabase(DB_DATABASE);

    $database->createDatabase(DB_DATABASE);

    $database->selectDatabase(DB_DATABASE);

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
