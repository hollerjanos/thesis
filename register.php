<?php

//==============================================================================
// Display errors
//==============================================================================

ini_set("display_startup_errors", true);
ini_set("display_errors", true);

//==============================================================================
// Includes
//==============================================================================

// Functions
require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/functions.php");

// Constants
require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/constants.php");

// Constants
require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/classes/Database.php");

//==============================================================================
// Imports
//==============================================================================

use includes\classes\Database;

//==============================================================================
// Declarations
//==============================================================================

$requiredFields = [
    "username",
    "password",
    "passwordConfirmation",
    "email",
    "phone"
];

$isError  = false;
$errorMessage = "";

//==============================================================================
// POST
//==============================================================================

if ($_POST)
{
    try {
        $errors = [];

        foreach ($requiredFields as $requiredField) {
            if (!isset($_POST[$requiredField])) {
                $errors[] = $requiredField;
            }
        }

        if (!empty($errors)) {
            throw new Exception(
                "Missing field(s): " . implode(", ", $errors)
            );
        }

        if ($_POST["password"] != $_POST["passwordConfirmation"]) {
            throw new Exception(
                "The passwords are not matching!"
            );
        }

        $database = new Database(
            DB_HOSTNAME,
            DB_USERNAME,
            DB_PASSWORD
        );

        $database->selectDatabase("2fa");

        $data = $database->selectDataFromTable(
            [ "*" ],
            "users",
            [],
            [],
            [ "id" ],
            []
        );
        $users = $data->fetchAll();

        foreach ($users as $user) {
            if ($user["username"] == $_POST["username"]) {
                throw new Exception(
                    "The username already exist!"
                );
            }
            if ($user["email"] == $_POST["email"]) {
                throw new Exception(
                    "This email already in use!"
                );
            }
            if ($user["phone"] == $_POST["phone"]) {
                throw new Exception(
                    "The given phone number is already in the system!"
                );
            }
        }

        display("data", $users);

        $database->insertIntoTable(
            "users",
            [
                "username" => $_POST["username"],
                "password" => md5($_POST["password"]),
                "email" => $_POST["email"],
                "phone" => $_POST["phone"]
            ]
        );
    } catch (Exception $exception) {
        $isError  = true;
        $errorMessage = $exception->getMessage();
    }
}

//==============================================================================
// Display the page
//==============================================================================

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>2FA - Register</title>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="assets/css/style.css"/>
        <script src="assets/js/script.js" defer></script>
    </head>
    <body>
        <div>
            <h3>Register</h3>
        </div>
        <?php
        if ($isError) {
            echo "<p class=\"error\">$errorMessage</p>";
        }
        ?>
        <div>
            <form method="POST" action="<?= $_SERVER["PHP_SELF"] ?>">
                <table>
                    <tr>
                        <td>
                            <label for="username">Username</label>
                        </td>
                        <td>
                            <input id="username" type="text" name="username" autocomplete="off"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="password">Password</label>
                        </td>
                        <td>
                            <input id="password" type="password" name="password"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="passwordConfirmation">Password confirmation</label>
                        </td>
                        <td>
                            <input id="passwordConfirmation" type="password" name="passwordConfirmation"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="email">E-mail address</label>
                        </td>
                        <td>
                            <input id="email" type="email" name="email"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="phone">Phone address</label>
                        </td>
                        <td>
                            <input id="phone" type="tel" name="phone"/>
                        </td>
                    </tr>
                </table>
                <div>
                    <input id="submit" type="submit" value="Submit"/>
                </div>
            </form>
        </div>
    </body>
</html>
