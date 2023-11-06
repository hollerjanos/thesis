<?php

//==============================================================================
// Display the navigation bar
//==============================================================================


?>
        <nav>
<?php if (isset($_SESSION["login"]) && $_SESSION["login"]) { ?>
            <a href="/clear.php">Logout</a>
<?php } else { ?>
            <a href="/register.php">Register</a>
            <a href="/login.php">Login</a>
<?php } ?>

        </nav>
