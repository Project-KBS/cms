<?php

/**
 * Deze klasse bevat handige methode om gebruikers login/logout.<br />
 * <br />
 * Wanneer de gebruiker wordt ingelogd wordt dit opgeslagen in de sessie.
 */
class Authentication {

    private const KEY_EMAIL = "auth_email";

    /**
     * Login als de gebruiker met $password.<br />
     * <br />
     * Als de credentials kloppen dan wordt TRUE gereturned, anders FALSE.
     *
     * @param $database
     * @param $email
     * @param $password
     * @return bool
     */
    public static function login($database, $email, $password) : bool {

        $row = Account::get($database, $email)->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            // Geen account gevonden met dit email adres
            return false;
        }

        extract($row);

        if (!(isset($PasswordHashResult) && isset($PasswordHashMethod))) {
            // Geen data gevonden bij dit account
            return false;
        }

        if (!password_verify($password, $PasswordHashResult)) {
            return false;
        }

        $_SESSION[self::KEY_EMAIL] = $email;

        return true;
    }

    /**
     * Eindig de login sessie.
     *
     * @return void
     */
    public static function logout() : void {
        if (isset($_SESSION[self::KEY_EMAIL])) {
            unset($_SESSION[self::KEY_EMAIL]);
        }
    }

    /**
     * Check of de gebruiker ingelogd is.
     *
     * @return bool
     */
    public static function isLoggedIn() : bool {
        return isset($_SESSION[self::KEY_EMAIL]);
    }

    /**
     * Verkrijg e-mail van de ingelogde gebruiker, of null als niemand ingelogd is.
     *
     * @return string|null
     */
    public static function getEmail() {
        if (isset($_SESSION[self::KEY_EMAIL])) {
            return $_SESSION[self::KEY_EMAIL];
        }
        return null;
    }

    /**
     * Van deze klasse mogen geen instanties gemaakt worden, dus maak de constructor privé.
     */
    private function __construct() {
        throw new AssertionError("Gebruik een statische method !!!");
    }

    /**
     * Instanties van deze klasse mogen niet gekloond worden, dus maak clone() privé.
     */
    private function __clone() {
        throw new AssertionError("Het is niet toegestaan om deze klasse te klonen!");
    }
}
