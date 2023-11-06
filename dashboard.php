<?php

//==============================================================================
// Display errors
//==============================================================================

ini_set("display_startup_errors", true);
ini_set("display_errors", true);

//==============================================================================
// Session
//==============================================================================

session_start();

//==============================================================================
// Validations
//==============================================================================

if (!isset($_SESSION["login"]) || !$_SESSION["login"])
{
    header("Location: /login.php");
    exit;
}

//==============================================================================
// Display the page
//==============================================================================

// Header
require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/header.php");

// Navigation bar
require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/navbar.php");

?>
        <h1 class="title">Dashboard</h1>
        <p class="success">You have successfully logged into the system!</p>
<?php

// Footer
require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/footer.php");
