<?php

class StandardHashMethod implements IHashMethod {

    const ALGORITME      = PASSWORD_BCRYPT;
    const NAAM           = "BCRYPT";

    private static $INSTANCE;

    public static function getInstance() : StandardHashMethod {
        if (self::$INSTANCE == null) {
            self::$INSTANCE = new StandardHashMethod();
        }
        return self::$INSTANCE;
    }

    public function hash(string $input) : HashResult {
        $hashed = password_hash($input,  self::ALGORITME);

        return new HashResult($hashed, self::NAAM, StandardHashMethod::class);
    }

}
