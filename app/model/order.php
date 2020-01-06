<?php

class Order {

    public const TABLE_NAME = "Orders";

    private static function getNextId(PDO $database) : int {

        $query = "SELECT (SELECT MAX(O.OrderID) FROM Orders O) + 1 NextId";

        $stmt = $database->query($query);

        if ($stmt) {
            return intval($stmt->fetchColumn(0));
        } else {
            return -1;
        }
    }

    /**
     * Maak een nieuwe invoice aan waar we de payment ID in de comments opslaan.
     *
     * @param PDO $database
     * @param int $customerId
     * @return int Order ID van de nieuw aangemaakte order
     */
    public static function insert(PDO $database, int $customerId) : int {

        $orderId = self::getNextId($database);

        // FIXME expected delivery date en IsUndersupplyBackordered
        $query = "INSERT INTO Orders (OrderID,                                   CustomerID,  ContactPersonID, SalespersonPersonID, OrderDate,      ExpectedDeliveryDate, IsUndersupplyBackordered, LastEditedBy,  LastEditedWhen)
                  VALUES             (:orderId, :customerId, :contactId,      1,                   CURRENT_DATE(), CURRENT_DATE(),       1,                        :lastEditedBy, CURRENT_DATE())";

        $stmt = $database->prepare($query);

        // We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt->bindValue(":orderId",      $orderId,         PDO::PARAM_INT);
        $stmt->bindValue(":customerId",   $customerId,      PDO::PARAM_INT);
        $stmt->bindValue(":contactId",    $customerId,      PDO::PARAM_INT);
        $stmt->bindValue(":lastEditedBy",     1,      PDO::PARAM_INT); // FIXME

        // Voer de query uit
        $stmt->execute();

        return $orderId;
    }
}
