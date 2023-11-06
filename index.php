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

// Two-factor authentication
require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/classes/TwoFactorAuthentication.php");

//==============================================================================
// Imports
//==============================================================================

use includes\classes\TwoFactorAuthentication;

//==============================================================================
// POST
//==============================================================================

if ($_POST)
{
    display("POST", $_POST);
}

//==============================================================================
// Display the page
//==============================================================================

$code = TwoFactorAuthentication::generateCode(
    CODE_TYPE,
    CODE_LENGTH
);

display("Code", $code);

TwoFactorAuthentication::sendEmail(
    "hollerjanika@gmail.com",
    "Thesis - Login",
    $code
)

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>2FA</title>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="assets/css/style.css"/>
        <script src="assets/js/script.js" defer></script>
    </head>
    <body>
        <form method="POST" action="<?= $_SERVER["PHP_SELF"] ?>">
            <div>
                <label for="username">
                    Username
                </label>
                <input id="username" type="text" name="username" autocomplete="off"/>
            </div>
            <div>
                <label for="password">
                    Password
                </label>
                <input id="password" type="password" name="password"/>
            </div>
            <div>
                <input id="submit" type="submit" value="Submit"/>
            </div>
        </form>
    </body>
</html>
