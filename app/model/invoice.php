<?php

class Invoice {

    public const TABLE_NAME = "Invoices";

    /**
     * Maak een nieuwe invoice aan waar we de payment ID in de comments opslaan.
     *
     * @param PDO $database
     * @param int $customerId
     * @param int $orderId
     * @param int $paymentId
     * @return PDOStatement
     */
    public static function insert(PDO $database, int $customerId, int $orderId, string $paymentId) {

        $query = "INSERT INTO Invoices (InvoiceID, CustomerID,  BillToCustomerID, DeliveryMethodID, ContactPersonID, AccountsPersonID, SalespersonPersonID, PackedByPersonID, InvoiceDate,    IsCreditNote, TotalDryItems, TotalChillerItems, CustomerPurchaseOrderNumber, LastEditedBy,  LastEditedWhen)
                  VALUES               (:orderId,  :customerId, :billingId,       1,                1,               1,                1,                   1,                CURRENT_DATE(), 0,            1,             1,                 :paymentId,                  :lastEditedBy, CURRENT_DATE())";

        $stmt = $database->prepare($query);

        // We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt->bindValue(":customerId",   $customerId,      PDO::PARAM_INT);
        $stmt->bindValue(":billingId",    $customerId,      PDO::PARAM_INT);
        $stmt->bindValue(":paymentId",    $paymentId,       PDO::PARAM_STR);
        $stmt->bindValue(":orderId",      $orderId,         PDO::PARAM_INT);
        $stmt->bindValue(":lastEditedBy", 1,                PDO::PARAM_INT); // TODO

        // Voer de query uit
        $stmt->execute();

        return $stmt;
    }

    /**
     * Verkrijg een transactie bij ID.
     *
     * @param PDO $database       Database connectie object
     * @param int $id             Transactie ID
     * @return PDOStatement|null  Als er niets gevonden is wordt er NULL gereturned, anders een statement.
     */
    public static function get(PDO $database, int $orderId) {

        if (filter_var($orderId, FILTER_VALIDATE_INT) == false) {
            error_log("Order ID moet een integer zijn!");
            return null;
        }

        $query = "SELECT
                      i.CustomerPurchaseOrderNumber, i.CustomerID
                  FROM
                      " . self::TABLE_NAME . " i
                  WHERE i.InvoiceID = :orderId";

        $stmt = $database->prepare($query);

        // We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt->bindValue(":orderId",      $orderId,       PDO::PARAM_INT);

        // Voer de query uit
        $stmt->execute();

        return $stmt;
    }
}
