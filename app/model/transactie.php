<?php

class Transactie {

    /**
     * Maak een nieuwe (onvoltooide) transactie aan in de database.
     *
     * @param PDO $database       Database connectie object
     * @param int $paymentId      Mollie payment ID
     * @param int $klantId        Customer ID
     * @param int $invoiceId      Invoice ID
     * @param float $amountExcl   Prijs exclusief btw
     * @param float $amountTax    Btw
     * @param float $amountIncl   Prijs inclusief btw
     * @return PDOStatement
     */

    public static function insert(PDO $database, int $paymentId, int $klantId, float $amountExcl, float $amountTax, float $amountIncl) {

        $query = "INSERT INTO CustomerTransactions (CustomerTransactionID, CustomerID, TransactionTypeID, InvoiceID, PaymentMethodID, TransactionDate, AmountExcludingTax, TaxAmount, TransactionAmount, OutstandingBalance, FinalizationDate, IsFinalized, LastEditedBy, LastEditedWhen)
                  VALUES (:transId, :customerId, :typeId, :invoiceId, :methodId, CURRENT_DATE(), :amountExcl, :amountTax, :amountIncl, :balance, :dateFinal, :isFinal, :lastEditedBy, CURRENT_DATE())";

        $stmt = $database->prepare($query);

        // We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt->bindValue(":transId",      $paymentId,  PDO::PARAM_INT);
        $stmt->bindValue(":customerId",   $klantId,    PDO::PARAM_INT);
        $stmt->bindValue(":typeId",       1,     PDO::PARAM_INT); // TODO
        $stmt->bindValue(":invoiceId",    null,  PDO::PARAM_NULL);
        $stmt->bindValue(":methodId",     14,    PDO::PARAM_INT); // TODO
        $stmt->bindValue(":amountExcl",   $amountExcl, PDO::PARAM_STR);
        $stmt->bindValue(":amountTax",    $amountTax,  PDO::PARAM_STR);
        $stmt->bindValue(":amountIncl",   $amountIncl, PDO::PARAM_STR);
        $stmt->bindValue(":balance",      0,     PDO::PARAM_INT);
        $stmt->bindValue(":dateFinal",    null,  PDO::PARAM_NULL);
        $stmt->bindValue(":isFinal",      null,  PDO::PARAM_NULL);
        $stmt->bindValue(":lastEditedBy", 1,     PDO::PARAM_INT);

        // Voer de query uit
        $stmt->execute();

        return $stmt;
    }
}
