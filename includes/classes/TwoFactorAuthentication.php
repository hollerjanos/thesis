<?php

//==============================================================================
// Two-factor authentication
//==============================================================================

// Creator: Holler Janos
// First release: 2023-11-01 11:35:00
// Latest update: 2023-11-01 11:35:00
// Editor: PhpStorm 2022.2.3

class TwoFactorAuthentication
{
  //============================================================================
  // Static methods
  //============================================================================

  /**
   * <p>Create code</p>
   * <pre>
   * +------+---------+-------------------+-------------------+----------+
   * | Type | Numbers | Lowercase letters | Uppercase letters | Specials |
   * +------+---------+-------------------+-------------------+----------+
   * |   1  |         |                   |                   |     +    |
   * |   2  |         |                   |         +         |          |
   * |   3  |         |                   |         +         |     +    |
   * |   4  |         |         +         |                   |          |
   * |   5  |         |         +         |                   |     +    |
   * |   6  |         |         +         |         +         |          |
   * |   7  |         |         +         |         +         |     +    |
   * |   8  |    +    |                   |                   |          |
   * |   9  |    +    |                   |                   |     +    |
   * |  10  |    +    |                   |         +         |          |
   * |  11  |    +    |                   |         +         |     +    |
   * |  12  |    +    |         +         |                   |          |
   * |  13  |    +    |         +         |                   |     +    |
   * |  14  |    +    |         +         |         +         |          |
   * |  15  |    +    |         +         |         +         |     +    |
   * +------+---------+-------------------+-------------------+----------+
   * </pre>
   * @param int $type
   * @param int $length
   * @return string
   */
  static public function generateCode(
    int $type = 8,
    int $length = 6
  ): string
  {
    $numbers          = "0123456789";
    $lowercaseLetters = "abcdefghijklmnopqrstuvwxyz";
    $uppercaseLetters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $specials         = "~`!@#$%^&*()-_+={}[]|\/:;\"'<>,.?";

    $characters = match ($type)
    {
      1       => $specials,
      2       => $uppercaseLetters,
      3       => $uppercaseLetters.$specials,
      4       => $lowercaseLetters,
      5       => $lowercaseLetters.$specials,
      6       => $lowercaseLetters.$uppercaseLetters,
      7       => $lowercaseLetters.$uppercaseLetters.$specials,
      default => $numbers,
      9       => $numbers.$specials,
      10      => $numbers.$uppercaseLetters,
      11      => $numbers.$uppercaseLetters.$specials,
      12      => $numbers.$lowercaseLetters,
      13      => $numbers.$lowercaseLetters.$specials,
      14      => $numbers.$lowercaseLetters.$uppercaseLetters,
      15      => $numbers.$lowercaseLetters.$uppercaseLetters.$specials
    };

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
}