<?php

$code = $_GET["code"] ?? 0;

if ($code == 0) {
    header("Location: /");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Error</title>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="assets/css/style.css"/>
    </head>
    <body>
        <p class="error">ERROR CODE #<?= $code ?></p>
    </body>
</html>