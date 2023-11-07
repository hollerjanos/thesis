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

// Database
require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/classes/Database.php");

// Two-factor authentication
require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/classes/TwoFactorAuthentication.php");

//==============================================================================
// Imports
//==============================================================================

use includes\classes\Database;
use includes\classes\TwoFactorAuthentication;

//==============================================================================
// Session
//==============================================================================

session_start();

//==============================================================================
// Validations
//==============================================================================

if (!isset($_SESSION["id"])) {
    header("Location: /login.php");
    exit;
}

//==============================================================================
// Declarations
//==============================================================================

// Required fields
$requiredFields = [
    "code" => "Code"
];

// Show message or error
$message = [
    "display" => false,
    "error" => false,
    "message" => ""
];

if (isset($_GET["message"]))
{
    $message["display"] = true;
    $message["message"] = match($_GET["message"])
    {
        "200" => "Two-factor authentication was successful!",
        default => ""
    };

    if (empty($message["message"]))
    {
        $message["display"] = false;
    }
}

//==============================================================================
// POST
//==============================================================================

if ($_POST)
{
    try
    {
        $errors = [];

        foreach ($requiredFields as $requiredFieldKey => $requiredFieldItem)
        {
            if (!isset($_POST[$requiredFieldKey]))
            {
                $errors[] = $requiredFieldItem;
            }
        }

        if (!empty($errors))
        {
            throw new Exception(
                "Missing field(s): " . implode(", ", $errors)
            );
        }

        foreach ($requiredFields as $requiredFieldKey => $requiredFieldItem)
        {
            if (empty($_POST[$requiredFieldKey]))
            {
                $errors[] = $requiredFieldItem;
            }
        }

        if (!empty($errors))
        {
            throw new Exception(
                "Invalid field(s): " . implode(", ", $errors)
            );
        }

        $database = new Database(
            DB_HOSTNAME,
            DB_USERNAME,
            DB_PASSWORD,
            DB_DATABASE
        );

        $code = $database->checkCode(
            $_SESSION["id"]
        );

        if (empty($code))
        {
            header("Location: /login.php");
            exit;
        }

        if ($code["code"] != $_POST["code"])
        {
            throw new Exception(
                "Invalid code given!"
            );
        }

        $_SESSION["login"] = true;

        header("Location: /dashboard.php");
        exit;
    }
    catch (Exception $exception)
    {
        $message["display"] = true;
        $message["error"] = true;
        $message["message"] = $exception->getMessage();
    }
}

//==============================================================================
// Display the page
//==============================================================================

// Header
require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/header.php");

// Navigation bar
require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/navbar.php");

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>2FA - Two-factor authentication</title>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="assets/css/style.css"/>
        <script src="assets/js/script.js" defer></script>
    </head>
    <body>
        <h1 class="title">Two-factor authentication</h1>
    <?php if ($message["display"]) { ?>
        <p class="<?= $message["error"] ? "error" : "success" ?>"><?= $message["message"] ?></p>
    <?php } ?>
        <form method="POST" action="<?= $_SERVER["PHP_SELF"] ?>">
            <table>
                <tr class="fields">
                    <td>
                        <label for="code">Code</label>
                    </td>
                    <td>
                        <input id="code" type="text" name="code" autocomplete="off" placeholder="******" required/>
                    </td>
                </tr>
                <tr class="submit">
                    <td colspan="2">
                        <input id="submit" type="submit" value="Submit"/>
                    </td>
                </tr>
            </table>
        </form>
<?php

// Footer
require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/footer.php");
