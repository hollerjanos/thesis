<?php

//==============================================================================
// Database
//==============================================================================

// Creator: Holler Janos
// First release: 2023-10-30 19:24:00
// Latest update: 2023-11-01 11:35:00
// Editor: PhpStorm 2022.2.3

//==============================================================================
// Namespace
//==============================================================================

namespace includes\classes;

//==============================================================================
// Includes
//==============================================================================

// Functions
require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/functions.php");

//==============================================================================
// Imports
//==============================================================================

use PDO;
use Exception;
use PDOStatement;

//==============================================================================
// Process
//==============================================================================

class Database
{
    //============================================================================
    // Properties
    //============================================================================

    private string $hostname;
    private string $username;
    private string $password;
    private string $database;

    private PDO $db;

    private bool $debug = DEBUG;

    private array $options = [
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    //============================================================================
    // Construct
    //============================================================================

    public function __construct(
        string $hostname,
        string $username,
        string $password,
        string $database
    ) {
        try {
            $this->hostname = $hostname;
            $this->username = $username;
            $this->password = $password;
            $this->database = $database;

            $this->connection();

            if (!$this->isDatabaseExist()) {
                throw new Exception(
                    ERROR_CODES[14299],
                    14299
                );
            }

            $this->selectDatabase();
        } catch (Exception $exception) {
            if ($this->debug) {
                exception(
                    "Database | __construct()",
                    [
                        "message" => $exception->getMessage()
                    ]
                );
            } else {
                $code = $exception->getCode();

                header("Location: /error.php?code=$code");
                exit;
            }
        }
    }

    /**
     * <p>Database connection</p>
     * @return void
     */
    private function connection(): void
    {
        // Data source name
        $dsn = "mysql:host=$this->hostname";

        $this->db = new PDO(
            $dsn,
            $this->username,
            $this->password,
            $this->options
        );
    }

    /**
     * <p>Create database</p>
     * @return bool
     */
    public function createDatabase(): bool {
        try {
            $sql = "CREATE DATABASE IF NOT EXISTS `$this->database`;";

            $params = null;

            if ($this->debug) {
                display(
                    "Database | createDatabase()",
                    [
                        "sql" => $sql,
                        "params" => $params
                    ]
                );
            }

            $statement = $this->db->prepare($sql);
            $statement->execute($params);

            return true;
        } catch (Exception $exception) {
            if ($this->debug) {
                exception(
                    "Database | createDatabase()",
                    [
                        "message" => $exception->getMessage()
                    ]
                );
            }

            return false;
        }
    }

    /**
     * <p>Select database</p>
     * @return bool
     */
    public function selectDatabase(): bool {
        try {
            $sql = "USE `$this->database`;";

            $params = null;

            if ($this->debug) {
                display(
                    "Database | selectDatabase()",
                    [
                        "sql" => $sql,
                        "params" => $params
                    ]
                );
            }

            $statement = $this->db->prepare($sql);
            $statement->execute($params);

            return true;
        } catch (Exception $exception) {
            if ($this->debug) {
                exception(
                    "Database | selectDatabase()",
                    [
                        "message" => $exception->getMessage()
                    ]
                );
            }

            return false;
        }
    }

    /**
     * <p>Create table</p>
     * @param string $name
     * @param array $config
     * @return bool
     */
    public function createTable(string $name, array $config): bool
    {
        try
        {
            $attributes = implode(", ", $config);

            $sql  = "CREATE TABLE IF NOT EXISTS `$name` ($attributes);";

            if ($this->debug)
                display(
                    "Database | createTable()",
                    [
                        "sql" => $sql,
                        "config" => $config
                    ]
                );

            $statement = $this->db->prepare($sql);
            $statement->execute();

            return true;
        }
        catch (Exception $exception)
        {
            if ($this->debug)
                exception(
                    "Database | createTable()",
                    $exception->getMessage()
                );

            return false;
        }
    }

    /**
     * <p>Insert into table</p>
     * @param string $table
     * @param $params
     * @return bool
     */
    public function insertIntoTable(string $table, $params): bool
    {
        try
        {
            $keys = "`" . implode("`, `", array_keys($params)) . "`";
            $items = ":" . implode(", :", array_keys($params));

            $sql  = "INSERT INTO `$table` ($keys) VALUES ($items);";

            if ($this->debug)
                display(
                    "Database | insertIntoTable()",
                    [
                        "sql" => $sql,
                        "params" => $params
                    ]
                );

            $statement = $this->db->prepare($sql);
            $statement->execute($params);

            return true;
        }
        catch (Exception $exception)
        {
            if ($this->debug)
                exception(
                    "Database | insertIntoTable()",
                    [
                        "message" => $exception->getMessage()
                    ]
                );

            return false;
        }
    }

    /**
     * <p>Delete database</p>
     * @return bool
     */
    public function deleteDatabase(): bool {
        try
        {
            $sql = "DROP DATABASE IF EXISTS `$this->database`;";

            $params = null;

            if ($this->debug) {
                display(
                    "Database | deleteDatabase()",
                    [
                        "sql" => $sql,
                        "params" => $params
                    ]
                );
            }

            $statement = $this->db->prepare($sql);
            $statement->execute($params);

            return true;
        }
        catch (Exception $exception)
        {
            if ($this->debug)
                exception(
                    "Database | deleteDatabase()",
                    [
                        "message" => $exception->getMessage()
                    ]
                );

            return false;
        }
    }

    /**
     * <p>Is user exist</p>
     * @param string $username
     * @param string $email
     * @param string $phone
     * @return array|bool
     */
    public function isUserExist(
        string $username = "",
        string $email = "",
        string $phone = ""
    ): array|bool {
        try {
            $params = [];
            $whereClauses = [];

            if (!empty($username)) {
                $whereClauses[] = "`users`.`username` = :username";
                $params["username"] = $username;
            }
            if (!empty($email)) {
                $whereClauses[] = "`users`.`email` = :email";
                $params["email"] = $email;
            }
            if (!empty($phone)) {
                $whereClauses[] = "`users`.`phone` = :phone";
                $params["phone"] = $phone;
            }

            $sql  = "SELECT `users`.*";
            $sql .= " FROM `users`";
            if (!empty($whereClauses)) {
                $sql .= " WHERE " . implode(" OR ", $whereClauses);
            }
            $sql .= " LIMIT 1;";

            if ($this->debug)
                display(
                    "Database | isUserExists()",
                    [
                        "sql" => $sql,
                        "params" => $params
                    ]
                );

            $statement = $this->db->prepare($sql);
            $statement->execute($params);
            $result = $statement->fetch();

            if (!$result) {
                return [];
            }

            return $result;
        } catch (Exception $exception) {
            if ($this->debug)
                exception(
                    "Database | isUserExists()",
                    [
                        "message" => $exception->getMessage()
                    ]
                );

            return false;
        }
    }

    /**
     * <p>Is database exist</p>
     * @return bool
     */
    public function isDatabaseExist(): bool
    {
        try {
            $sql  = "SELECT 1";
            $sql .= " FROM `INFORMATION_SCHEMA`.`SCHEMATA`";
            $sql .= " WHERE `SCHEMATA`.`SCHEMA_NAME` = \"$this->database\"";
            $sql .= " LIMIT 1;";

            $params = null;

            if ($this->debug) {
                display(
                    "Database | isDatabaseExist()",
                    [
                        "sql" => $sql,
                        "params" => $params
                    ]
                );
            }

            $statement = $this->db->prepare($sql);
            $statement->execute($params);
            $result = $statement->fetch();

            if (!$result) {
                return false;
            }

            return true;
        } catch (Exception $exception) {
            if ($this->debug) {
                exception(
                    "Database | isDatabaseExist()",
                    [
                        "message" => $exception->getMessage()
                    ]
                );
            }

            return false;
        }
    }

    /**
     * <p>Check login</p>
     * @param string $username
     * @param string $password
     * @return array|bool
     */
    public function checkLogin(
        string $username,
        string $password
    ): array|bool {
        try {
            $params = [
                "username" => $username,
                "password" => $password
            ];
            $whereClauses = [
                "`users`.`username` = :username",
                "`users`.`password` = :password"
            ];

            $sql  = "SELECT `users`.*";
            $sql .= " FROM `users`";
            if (!empty($whereClauses)) {
                $sql .= " WHERE " . implode(" AND ", $whereClauses);
            }
            $sql .= " LIMIT 1;";

            if ($this->debug)
                display(
                    "Database | checkLogin()",
                    [
                        "sql" => $sql,
                        "params" => $params
                    ]
                );

            $statement = $this->db->prepare($sql);
            $statement->execute($params);
            $result = $statement->fetch();

            if (!$result) {
                return [];
            }

            return $result;
        } catch (Exception $exception) {
            if ($this->debug)
                exception(
                    "Database | checkLogin()",
                    [
                        "message" => $exception->getMessage()
                    ]
                );

            return false;
        }
    }

    /**
     * @param $userID
     * @return array|bool
     */
    public function checkCode(
        $userID
    ): array|bool
    {
        try {
            $params = [
                "id" => $userID,
            ];

            $sql  = "SELECT `2fa_codes`.*";
            $sql .= " FROM `2fa_codes`";
            $sql .= " WHERE `2fa_codes`.`user_id` = :id";
            $sql .= " ORDER BY `id` DESC";
            $sql .= " LIMIT 1;";

            if ($this->debug)
                display(
                    "Database | checkCode()",
                    [
                        "sql" => $sql,
                        "params" => $params
                    ]
                );

            $statement = $this->db->prepare($sql);
            $statement->execute($params);
            $result = $statement->fetch();

            if (!$result) {
                return [];
            }

            return $result;
        } catch (Exception $exception) {
            if ($this->debug)
                exception(
                    "Database | checkCode()",
                    [
                        "message" => $exception->getMessage()
                    ]
                );

            return false;
        }
    }
}
