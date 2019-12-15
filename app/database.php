<?php

include_once "constants.php";

/**
 * Alle info over de database connectie met de MySQL server.<br /><br />
 *
 * Deze klasse is een <i>utility class</i>, wat betekent dat er van deze klasse geen instanties gemaakt kunnen worden.<br />
 * Gebruik `getConnection()` om het connectie object te verkrijgen.
 */
class Database {

    /**
     * De URL naar de MySQL server waar de database is (localhost betekent op je eigen laptop)
     */
    private const HOST = "localhost";

    /**
     * De naam van de database
     */
    private const DATABASE = "wideworldimporters";

    /**
     * De gebruiker waarmee je in de database server inlogt
     */
    private const USERNAME = "api-local";

    /**
     * Het wachtwoord voor de bovenstaande gebruiker.
     */
    private const PASSWORD = "";

    /**
     * Dit is het globale database connectie object.<br /><br />
     *
     * Verkrijg het via `getConnection()`.
     *
     * @var PDO
     */
    private static $connection = null;

    /**
     * Verkrijg het globale database connectie object.<br /><br />
     *
     * De connectie wordt aangemaakt als hij nog niet beschikbaar is.
     *
     * @return PDO
     */
    public static function getConnection() {
        // Als de connectie nog niet gemaakt is
        if (self::$connection == null) {

            // Gooi dit in een try-catch blok omdat PDO exceptions geeft
            try {
                // Verbind met de database via PDO
                self::$connection = new PDO("mysql:host=" . Database::HOST . ";dbname=" . Database::DATABASE, Database::USERNAME, Database::PASSWORD);

                // MySQL werkt default met de ASCII character set dus geef aan dat we UTF-8 willen gebruiken.
                self::$connection->exec("set names utf8");

            } catch (PDOException $exception) {
                // Dit gebeurt als de database server uit staat of ./db/setup.sql nog niet is gerund
                error_log("Connection error: " . $exception->getMessage());
            }

            // Als de debug modus aan staat, laat alle PDO exceptions zien aan de gebruiker, anders niet.
            if (IS_DEBUGGING_ENABLED) {
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                ini_set('display_errors', 1);
                ini_set('log_errors', 1);
            } else {
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                ini_set('display_errors', 0);
                ini_set('log_errors', 1);
            }
        }

        // Return het connectie object.
        return self::$connection;
    }

    /**
     * Deze functie checkt of een PDO database connectie nog werkt / actief is.
     *
     * @param PDO      $database   Database connectie object.
     * @param bool     $retry      Als hij niet werkt, zullen we hem proberen te herstellen?
     *
     * @return bool
     */
    public static function isConnectionValid($database, $retry = TRUE) {
        if ($database == null) {
            return FALSE;
        }

        try {
            // Zorg dat hij een exception geeft als de testquery niet lukt.
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Voer de testquery uit.
            $database->query("SELECT 1");

            // Success! Reset nu de error mode naar de default. (zie opmerkingen bij getConnection() hierover voor meer info).
            if (IS_DEBUGGING_ENABLED) {
                $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } else {
                $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
            }

            // Return dat het goed is gegaan.
            return TRUE;

        } catch (PDOException $exception) {
            // Als er de optie is om nog een keer te proberen, maak een nieuwe connectie aan en check opnieuw.
            if ($retry) {

                // Reset de connectie.
                self::$connection = null;
                $database = self::getConnection();

                // Check opnieuw met de nieuwe connectie, en return het resultaat de nieuwe check.
                return self::isConnectionValid($database, FALSE);

            } else {
                // Connectie werkt niet meer. Meld het in de log.
                error_log("Connection error: " . $exception->getMessage());
                return FALSE;
            }
        }
    }

    /**
     * Van deze klasse mogen geen instanties gemaakt worden, dus maak de constructor privé.
     */
    private function __construct() {
        throw new AssertionError("Gebruik getConnection() !!!");
    }

    /**
     * Instanties van deze klasse mogen niet gekloond worden, dus maak clone() privé.
     */
    private function __clone() {
        throw new AssertionError("Het is niet toegestaan om deze klasse te klonen!");
    }
}

?>
