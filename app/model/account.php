<?php

class Account {

    public const TABLE_NAME = "Account";

    /**
     * Verkrijg alle data van een account.
     *
     * @param PDO    $database
     * @param string $email
     * @return PDOStatement
     */
    public static function get(PDO $database, string $email) : PDOStatement {

        $query = "SELECT
                      A.PasswordHashResult, A.PasswordHashMethod,
                      A.FirstName, A.MiddleName, A.LastName, A.CustomerID,
                      A.AddressStreet, A.AddressNumber, A.AddressToevoeging, A.AddressCity, A.AddressPostalCode,
                      A.LastIpAddress, A.LastUserAgent
                  FROM
                      Account A
                  WHERE
                      A.Email = :email";


        $stmt = $database->prepare($query);


        // We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt->bindValue(":email",         $email,        PDO::PARAM_STR);


        $stmt->execute();

        return $stmt;
    }

    /**
     * Registreer een nieuw account in deze database.
     *
     * Gooit een exception als al een account met deze email bestaat.
     *
     * @param PDO    $database
     * @param string $email
     * @param string $plaintextPassword
     * @param string $firstName
     * @param string $middleName
     * @param string $lastName
     * @param string $addrStreet
     * @param string $addrNumber      Wordt tijdelijk geaccepteerd als string omdat hij ook zo gebind wordt.
     * @param string $addrToevoeging
     * @param string $addrCity
     * @param string $addrPostal
     * @param string $lastIp
     * @param string $lastUa
     * @throws PDOException
     */
    public static function insert(PDO $database, string $email, string $plaintextPassword,
                                                 string $firstName, string $middleName, string $lastName,
                                                 string $addrStreet, string $addrNumber, string $addrToevoeging,
                                                 string $addrCity, string $addrPostal,
                                                 string $lastIp, string $lastUa) : void {

        $query = "INSERT INTO
                      Account
                          (Email, PasswordHashResult, PasswordHashMethod,
                           FirstName, MiddleName, LastName,
                           AddressStreet, AddressNumber, AddressToevoeging, AddressCity, AddressPostalCode,
                           LastIpAddress, LastUserAgent)
                  VALUES
                          (:email, :hashResult, :hashMethod,
                           :firstName, :middleName, :lastName,
                           :addrStreet, :addrNum, :addrExtra,
                           :addrCity, :addrPostal,
                           :lastIp, :lastUa)";

        $stmt = $database->prepare($query);

        error_log($plaintextPassword);

        // Mocht er een nieuwe hash methode moeten komen kan deze lijn eenvoudig vervangen worden
        $hashResult = StandardHashMethod::getInstance()->hash($plaintextPassword);

        // We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen

        $stmt->bindValue(":email",       $email,                                   PDO::PARAM_STR);
        $stmt->bindValue(":hashResult",  $hashResult->getHash(),                   PDO::PARAM_STR);
        $stmt->bindValue(":hashMethod",  $hashResult->getMethod(),                 PDO::PARAM_STR);
        $stmt->bindValue(":firstName",   $firstName,                               PDO::PARAM_STR);
        $stmt->bindValue(":middleName",  $middleName,                              PDO::PARAM_STR);
        $stmt->bindValue(":lastName",    $lastName,                                PDO::PARAM_STR);
        $stmt->bindValue(":addrStreet",  $addrStreet,                              PDO::PARAM_STR);
        $stmt->bindValue(":addrNum",     $addrNumber,                              PDO::PARAM_STR);
        $stmt->bindValue(":addrExtra",   $addrToevoeging,                          PDO::PARAM_STR);
        $stmt->bindValue(":addrCity",    $addrCity,                                PDO::PARAM_STR);
        $stmt->bindValue(":addrPostal",  $addrPostal,                              PDO::PARAM_STR);
        $stmt->bindValue(":lastIp",      $lastIp,                                  PDO::PARAM_STR);
        $stmt->bindValue(":lastUa",      $lastUa,                                  PDO::PARAM_STR);

        $stmt->execute();
    }

    public static function update(PDO $database, string $email, $plaintextPassword, array $changedValues) : void {

        $hashResult = null;
        if ($plaintextPassword != null) {
            // Mocht er een nieuwe hash methode moeten komen kan deze lijn eenvoudig vervangen worden
            $hashResult = StandardHashmethod::getInstance()->hash($plaintextPassword);
        }

        $properties = array("PasswordHashResult" => "hashResult->getHash()",
                            "PasswordHashMethod" => "hashResult->getMethod()",
                            "FirstName"          => "firstName",
                            "MiddleName"         => "middleName",
                            "LastName"           => "lastName",
                            "AddressStreet"      => "addrStreet",
                            "AddressNumber"      => "addrNumber",
                            "AddressToevoeging"  => "addrToevoeging",
                            "AddressCity"        => "addrCity",
                            "AddressPostalCode"  => "addrPostal",
                            "LastIpAddress"      => "lastIp",
                            "LastUserAgent"      => "lastUa");

        $queryPropertyString = " ";

        extract($changedValues);

        $first = true;
        foreach ($properties as $propertyName => $propertyVar) {
            if (isset(${$propertyVar}) && ${$propertyVar} != null) {
                $queryPropertyString .= sprintf("%sA.%s = :%s", $first ? "" : ",", $propertyName, "prepared$propertyName");
                $first = false;
            }
        }

        // Aangezien $queryPropertyString alleen maar wordt opgebouwd van
        // predefined property names kan er hier geen sql injection plaatsvinden.
        $query = "UPDATE Account A
                    SET
                        " . $queryPropertyString . "
                    WHERE
                        Email = :email";

        $stmt = $database->prepare($query);

        foreach ($properties as $propertyName => $propertyVar) {
            if (isset(${$propertyVar}) && ${$propertyVar} != null) {
                $stmt->bindValue(":prepared$propertyName", "${$propertyVar}", PDO::PARAM_STR);
            }
        }

        $stmt->bindValue(":email",       $email,                                   PDO::PARAM_STR);

        $stmt->execute();

    }

}
