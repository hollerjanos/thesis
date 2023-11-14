<?php

//==============================================================================
// Two-factor authentication
//==============================================================================

// Creator: Holler Janos
// First release: 2023-11-01 11:35:00
// Latest update: 2023-11-01 11:35:00
// Editor: PhpStorm 2022.2.3

//==============================================================================
// Namespace
//==============================================================================

namespace includes\classes;

//==============================================================================
// Includes
//==============================================================================

// Constants
require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/constants.php");

// PHP mailer
require_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");

//==============================================================================
// Imports
//==============================================================================

use Exception;
use Throwable;

use PHPMailer\PHPMailer\PHPMailer;

use Infobip\Api\SmsApi;
use Infobip\Configuration;
use Infobip\Model\SmsAdvancedTextualRequest;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;

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
            3       => $uppercaseLetters . $specials,
            4       => $lowercaseLetters,
            5       => $lowercaseLetters . $specials,
            6       => $lowercaseLetters . $uppercaseLetters,
            7       => $lowercaseLetters . $uppercaseLetters . $specials,
            default => $numbers,
            9       => $numbers . $specials,
            10      => $numbers . $uppercaseLetters,
            11      => $numbers . $uppercaseLetters . $specials,
            12      => $numbers . $lowercaseLetters,
            13      => $numbers . $lowercaseLetters . $specials,
            14      => $numbers . $lowercaseLetters . $uppercaseLetters,
            15      => $numbers . $lowercaseLetters . $uppercaseLetters . $specials
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

    /**
     * <p>Send E-mail</p>
     * @param string $username
     * @param string $address
     * @param string $subject
     * @param string $body
     * @param bool $debug
     * @return bool
     */
    public static function sendEmail(
        string $username,
        string $address,
        string $subject,
        string $body,
        bool $debug = false
    ): bool
    {
        try
        {
            $mail = new PhpMailer();

            $mail->isSMTP();
            $mail->Host = EMAIL_HOST;
            $mail->SMTPAuth = EMAIL_AUTH;
            $mail->Username = EMAIL_USERNAME;
            $mail->Password = EMAIL_PASSWORD;
            $mail->SMTPSecure = EMAIL_SMTP_SECURE;
            $mail->Port = EMAIL_PORT;

            $mail->setFrom(EMAIL_FROM, "Thesis");

            $mail->addAddress($address, $username);

            $mail->isHTML();

            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();

            return true;
        }
        catch (Exception $exception)
        {
            if ($debug)
            {
                exception(
                    "sendEmail",
                    [
                        "exception" => $exception
                    ]
                );
            }
            return false;
        }
    }

    /**
     * <p>Send SMS</p>
     * @param string $recipient
     * @param string $text
     * @param bool $debug
     * @return bool
     */
    public static function sendSMS(
        string $recipient,
        string $text,
        bool $debug = false
    ): bool
    {
        $baseURL = SMS_BASE_URL;
        $apiKey = SMS_API_KEY;

        $sender = SMS_SENDER;

        $configuration = new Configuration(host: $baseURL, apiKey: $apiKey);

        $sendSmsApi = new SmsApi(config: $configuration);

        $destination = new SmsDestination(
            to: $recipient
        );

        $message = new SmsTextualMessage(destinations: [$destination], from: $sender, text: $text);

        $request = new SmsAdvancedTextualRequest(messages: [$message]);

        try {
            $sendSmsApi->sendSmsMessage($request);

            return true;
        } catch (Throwable $throwable) {
            if ($debug)
            {
                exception(
                    "sendSMS",
                    [
                        "throwable" => $throwable
                    ]
                );
            }

            return false;
        }
    }
}
