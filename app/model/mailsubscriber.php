<?php

class MailSubscriber {

    /**
     * zet alle beschikbare informatie van de klant in de database
     *
     * Het nieuwe Customer ID wordt gereturned.
     */
    public static function insert($database, $email, $customerId, bool $isSubscribed) : bool {

        $query = "INSERT INTO MailSubscriber (Email,  CustomerID,  IsSubscribed )
                  VALUES                     (:email, :customerId, :isSubscribed)";

        $stmt = $database->prepare($query);

        // We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt->bindValue(":email",           $email,                 PDO::PARAM_STR);
        $stmt->bindValue(":customerId",      $customerId,            PDO::PARAM_INT);
        $stmt->bindValue(":isSubscribed",    $isSubscribed ? 1 : 0,  PDO::PARAM_INT);

        try {
            $stmt->execute();
            return true;

        } catch (Exception $ignored) {
            if (IS_DEBUGGING_ENABLED) {
                printf("%s", $ignored->getMessage());
            }
            return false;
        }
    }
}
