<?php

/**
 * <p>Random string generator</p>
 * @param int $length
 * @return string
 */
function randomStringGenerator(int $length = 0): string
{
  $characters  = "0123456789";
  $characters .= "abcdefghijklmnopqrstuvwxyz";
  $characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

  $return = "";

  $min = 0;
  $max = strlen($characters) - 1;

  for ($index = 0; $index < $length; $index++)
  {
    $randomNumber = rand($min, $max);

    $return .= $characters[$randomNumber];
  }

  return $return;
}

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
