<?php

//==============================================================================
// Includes
//==============================================================================

// Constants
require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/constants.php");

// Database
require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/classes/Database.php");

//==============================================================================
// Imports
//==============================================================================

use includes\classes\Database;

//==============================================================================
// Process
//==============================================================================

try {
    // Connection
    $database = new Database(
        DB_HOSTNAME,
        DB_USERNAME,
        DB_PASSWORD,
        DB_DATABASE
    );

    $database->deleteDatabase();

    $database->createDatabase();

    $database->selectDatabase();

    $database->createTable(
        "users",
        [
            "`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY",
            "`username` VARCHAR(100) NOT NULL",
            "`password` VARCHAR(50) NOT NULL",
            "`email` VARCHAR(50) NOT NULL",
            "`phone` VARCHAR(50) NOT NULL"
        ]
    );

    $database->createTable(
        "2fa_codes",
        [
            "`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY",
            "`user_id` INT NOT NULL",
            "FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)",
            "`code` VARCHAR(100) NOT NULL",
        ]
    );
} catch (Exception $exception) {
    display(
        "Database create | Exception caught",
        $exception->getMessage()
    );
}
