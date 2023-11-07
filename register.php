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

//==============================================================================
// Imports
//==============================================================================

use includes\classes\Database;

//==============================================================================
// Declarations
//==============================================================================

$requiredFields = [
    "username" => "Username",
    "password" => "Password",
    "passwordConfirmation" => "Password confirmation",
    "email" => "E-mail",
    "phone" => "Phone"
];

// Show message or error
$message = [
    "display" => false,
    "error" => false,
    "message" => ""
];

if (isset($_GET["message"])) {
    $message["display"] = true;
    $message["message"] = match($_GET["message"]) {
        "200" => "Registration was successful!",
        default => ""
    };

    if (empty($message["message"])) {
        $message["display"] = false;
    }
}

//==============================================================================
// POST
//==============================================================================

if ($_POST)
{
    try {
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

        if ($_POST["password"] != $_POST["passwordConfirmation"])
        {
            throw new Exception(
                "The passwords are not matching!"
            );
        }

        $database = new Database(
            DB_HOSTNAME,
            DB_USERNAME,
            DB_PASSWORD,
            DB_DATABASE
        );

        $users = $database->isUserExist(
            $_POST["username"],
            $_POST["email"],
            $_POST["phone"]
        );

        if (!empty($users)) {
            throw new Exception(
                "The user already exists!"
            );
        }

        $database->insertIntoTable(
            "users",
            [
                "username" => $_POST["username"],
                "password" => encryption($_POST["password"]),
                "email" => $_POST["email"],
                "phone" => $_POST["phone"]
            ]
        );

        header("Location: {$_SERVER["PHP_SELF"]}?message=200");
        exit;
    } catch (Exception $exception) {
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
        <h1 class="title">Register</h1>
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
                <tr class="fields">
                    <td>
                        <label for="passwordConfirmation">Password confirmation</label>
                    </td>
                    <td>
                        <input id="passwordConfirmation" type="password" name="passwordConfirmation" placeholder="************" required/>
                    </td>
                </tr>
                <tr class="fields">
                    <td>
                        <label for="email">E-mail address</label>
                    </td>
                    <td>
                        <input id="email" type="email" name="email" autocomplete="off" placeholder="john@example.com" required/>
                    </td>
                </tr>
                <tr class="fields">
                    <td>
                        <label for="phone">Phone address</label>
                    </td>
                    <td>
                        <input id="phone" type="tel" name="phone" autocomplete="off" placeholder="+36 ** *** ****" required/>
                    </td>
                </tr>
                <tr class="submit">
                    <td colspan="2">
                        <input id="submit" type="submit" value="Register"/>
                    </td>
                </tr>
            </table>
        </form>
<?php

// Footer
require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/footer.php");
