<?php

class OrderLine {

    public const TABLE_NAME = "OrderLines";

    /**
     * Maak een nieuwe invoice aan waar we de payment ID in de comments opslaan.
     *
     * @param PDO $database
     * @param int $orderId
     * @param int $productId
     * @param int $productQty
     * @return PDOStatement
     */
    public static function insert(PDO $database, int $orderId, int $productId, int $productQty) {

        extract(Product::getbyid($database, $productId)->fetch(PDO::FETCH_ASSOC));

        // FIXME expected delivery date en IsUndersupplyBackordered
        $query = "INSERT INTO OrderLines (OrderLineID,                                       OrderID,  StockItemID, Description,  PackageTypeID, Quantity,    TaxRate,     PickedQuantity, LastEditedBy,  LastEditedWhen)
                  VALUES                 ((SELECT MAX(O.OrderLineID) + 1 FROM OrderLines O), :orderId, :productId,  :description, :packageId,    :productQty, :productTax, :pickedQty,     :lastEditedBy, CURRENT_DATE())";

        $stmt = $database->prepare($query);

        // We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt->bindValue(":orderId",      $orderId,           PDO::PARAM_INT);
        $stmt->bindValue(":productId",    $productId,         PDO::PARAM_INT);
        $stmt->bindValue(":description",  $MarketingComments, PDO::PARAM_INT);
        $stmt->bindValue(":packageId",    $OuterPackageID,    PDO::PARAM_INT);
        $stmt->bindValue(":productQty",   $productQty,        PDO::PARAM_INT);
        $stmt->bindValue(":productTax",   $TaxRate,           PDO::PARAM_INT);
        $stmt->bindValue(":pickedQty",    $productQty,        PDO::PARAM_INT);
        $stmt->bindValue(":lastEditedBy",    1,         PDO::PARAM_INT); // FIXME

        // Voer de query uit
        $stmt->execute();

        return $stmt;
    }
}
