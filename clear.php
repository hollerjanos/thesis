<?php

//==============================================================================
// Session
//==============================================================================

session_start();

unset($_SESSION["id"]);
unset($_SESSION["username"]);
unset($_SESSION["password"]);
unset($_SESSION["email"]);
unset($_SESSION["phone"]);

session_destroy();

//==============================================================================
// Redirect
//==============================================================================

header("Location: /login.php");
exit;
