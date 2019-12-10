<?php

class OrderLine {

    public const TABLE_NAME = "OrderLines";

    /**
     * Maak een OrderLine aan bij een bestaande Order.
     *
     * @param PDO $database
     * @param int $orderId
     * @param int $productId
     * @param int $productQty
     * @return PDOStatement
     */
    public static function insert(PDO $database, int $orderId, int $orderTableId, int $productId, int $productQty) : PDOStatement {

        extract(Product::getbyid($database, $productId)->fetch(PDO::FETCH_ASSOC));

        // FIXME expected delivery date en IsUndersupplyBackordered
        $query = "INSERT INTO OrderLines (OrderLineID,                                       OrderID,  StockItemID, Description,  PackageTypeID, Quantity,    TaxRate,     PickedQuantity, LastEditedBy,  LastEditedWhen)
                  VALUES                 ((SELECT MAX(O.OrderLineID) + 1 FROM OrderLines O), :orderId, :productId,  :description, :packageId,    :productQty, :productTax, :pickedQty,     :lastEditedBy, CURRENT_DATE())";

        $stmt = $database->prepare($query);

        // We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt->bindValue(":orderId",      $orderTableId,      PDO::PARAM_INT);
        $stmt->bindValue(":productId",    $productId,         PDO::PARAM_INT);
        $stmt->bindValue(":description",  $orderId,           PDO::PARAM_INT);
        $stmt->bindValue(":packageId",    $OuterPackageID,    PDO::PARAM_INT);
        $stmt->bindValue(":productQty",   $productQty,        PDO::PARAM_INT);
        $stmt->bindValue(":productTax",   $TaxRate,           PDO::PARAM_INT);
        $stmt->bindValue(":pickedQty",    $productQty,        PDO::PARAM_INT);
        $stmt->bindValue(":lastEditedBy",    1,         PDO::PARAM_INT); // FIXME

        // Voer de query uit
        $stmt->execute();

        return $stmt;
    }


    /**
     * Leest alle producten uit de database.
     *
     * @param PDO $database Een database connectie object (verkrijg met Database::getConnectie();)
     * @param $orderId int
     * @return PDOStatement
     */
    public static function get($database, $orderId) {

        $query = "SELECT
                      l.StockItemID, l.Quantity
                  FROM
                      OrderLines l
                  WHERE
                       l.description = :orderId";

        $stmt = $database->prepare($query);

        // We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt->bindValue(":orderId",   $orderId,    PDO::PARAM_STR);

        // Voer de query uit
        $stmt->execute();

        return $stmt;
    }
}
