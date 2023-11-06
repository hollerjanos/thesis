<?php

/**
 * <p>Display</p>
 * <hr/>
 * <p>Fancy way to display data.</p>
 * @param string $title
 * @return void
 */
function display(string $title): void
{
    echo "<b>$title</b>";

    $numberOfArguments = func_num_args();
    $arguments = func_get_args();

    echo "<pre>";
    for ($index = 1; $index < $numberOfArguments; $index++)
    {
        var_dump($arguments[$index]);
    }
    echo "</pre>";
}

/**
 * <p>Exception</p>
 * <hr/>
 * <p>Fancy way to show exception.</p>
 * @param string $title
 * @return void
 */
function exception(string $title): void
{
    echo "<b style=\"color: #F00\">$title</b>";

    $numberOfArguments = func_num_args();
    $arguments = func_get_args();

    echo "<pre>";
    for ($index = 1; $index < $numberOfArguments; $index++)
    {
        var_dump($arguments[$index]);
    }
    echo "</pre>";
}

/**
 * <p>Encryption</p>
 * @param string $data
 * @return bool|string
 */
function encryption(string $data): bool|string
{
    return openssl_encrypt(
        $data,
        ENCRYPTION_CIPHER_ALGO,
        ENCRYPTION_PASSPHRASE,
        ENCRYPTION_OPTIONS,
        ENCRYPTION_IV
    );
}