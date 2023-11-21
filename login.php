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
// Declarations
//==============================================================================

$requiredFields = [
    "username" => "Username",
    "password" => "Password"
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
        "200" => "Login was successful!",
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

        $user = $database->checkLogin(
            $_POST["username"],
            encryption($_POST["password"])
        );

        if (empty($user))
        {
            throw new Exception(
                "Invalid username or password!"
            );
        }

        // Save data
        $_SESSION["id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["password"] = $user["password"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["phone"] = $user["phone"];

        $code = TwoFactorAuthentication::generateCode(
            CODE_TYPE,
            CODE_LENGTH
        );

        $database->insertIntoTable(
            "2fa_codes",
            [
                "user_id" => $user["id"],
                "code" => $code,
            ]
        );

        if (TWO_FACTOR_AUTHENTICATION_TYPE == "email")
        {
            TwoFactorAuthentication::sendEmail(
                $user["username"],
                $user["email"],
                "Thesis - Two-factor authentication",
                "<p>Your code: <b>$code</b></p>"
            );
        }
        elseif (TWO_FACTOR_AUTHENTICATION_TYPE == "sms")
        {
            TwoFactorAuthentication::sendSMS(
                $user["phone"],
                "Your code:\n$code"
            );
        }

        header("Location: /2fa.php");
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
        <h1 class="title">Login</h1>
<?php if ($message["display"]) { ?>
        <p class="<?= $message["error"] ? "error" : "success" ?>"><?= $message["message"] ?></p>
<?php } ?>
        <form method="POST" action="<?= $_SERVER["PHP_SELF"] ?>">
            <table>
                <tr class="fields">
                    <td>
                        <label for="username">Username</label>
                    </td>
                    <td>
                        <input id="username" type="text" name="username" autocomplete="off" placeholder="John" required/>
                    </td>
                </tr>
                <tr class="fields">
                    <td>
                        <label for="password">Password</label>
                    </td>
                    <td>
                        <input id="password" type="password" name="password" placeholder="************" required/>
                    </td>
                </tr>
                <tr class="submit">
                    <td colspan="2">
                        <input id="submit" type="submit" value="Login"/>
                    </td>
                </tr>
            </table>
        </form>
<?php

// Footer
require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/footer.php");
