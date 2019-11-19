<?php

class Product {

    public const TABLE_NAME = "stockitems";

    /**
     * Leest alle producten uit de database.
     *
     * @param PDO      $database   Een database connectie object (verkrijg met Database::getConnectie();)
     * @param int      $limit      Hoeveel producten er gereturned moeten worden. (default en max values staan in constants.php)
     *
     * @return PDOStatement
     */
    public static function read($database, $limit = 1000) {
        // Als limiet geen integer is, of niet binnen de grenzen valt, wordt de standaard limiet gehanteerd.
        if (filter_var($limit, FILTER_VALIDATE_INT) == false
            || $limit < MIN_PRODUCT_RETURN_AMOUNT
            || $limit > MAX_PRODUCT_RETURN_AMOUNT) {

            $limit = DEFAULT_PRODUCT_RETURN_AMOUNT;
        }

        $query = "SELECT
                      p.StockItemID, p.StockItemName, s.SupplierName, c.ColorName, u.PackageTypeName UnitPackageTypeName, o.PackageTypeName OuterPackageTypeName,
                      p.Brand, p.Size, p.LeadTimeDays, p.QuantityPerOuter, p.IsChillerStock, p.Barcode, p.TaxRate, p.UnitPrice, p.RecommendedRetailPrice,
                      p.TypicalWeightPerUnit, p.MarketingComments, p.InternalComments, p.Photo, p.CustomFields, p.Tags, p.SearchDetails,
                      p.LastEditedBy, p.ValidFrom, p.ValidTo
                  FROM
                      " . self::TABLE_NAME . " p
                  LEFT JOIN suppliers s ON p.SupplierID = s.SupplierID
                  LEFT JOIN colors c ON p.ColorID = c.ColorID
                  LEFT JOIN packagetypes u ON p.UnitPackageID = u.PackageTypeID
                  LEFT JOIN packagetypes o ON p.OuterPackageID = o.PackageTypeID
                  LIMIT :limiet";

        $stmt = $database->prepare($query);

        // We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt->bindValue(":limiet",   $limit,    PDO::PARAM_INT);

        // Voer de query uit
        $stmt->execute();

        return $stmt;
    }

    /**
     * Zoekt in de database naar producten met een bepaalde StockItemID
     * Geeft dan vanuit de database de kolommen van de select
     * Filtert de search zodat deze een integer is
     *
     * @param PDO      $database   Een database connectie object (verkrijg met Database::getConnectie();)
     * @param int      $limit      Hoeveel producten er gereturned moeten worden. (default en max values staan in constants.php)
     * @param int      $Search     Zoekt op de StockItemID
     * @return PDOStatement
     */
    public static function getbyid($database, $Search, $limit = 1000) {
        // Als limiet geen integer is, of niet binnen de grenzen valt, wordt de standaard limiet gehanteerd.
        if (filter_var($limit, FILTER_VALIDATE_INT) == false
            || $limit < MIN_PRODUCT_RETURN_AMOUNT
            || $limit > MAX_PRODUCT_RETURN_AMOUNT) {

            $limit = DEFAULT_PRODUCT_RETURN_AMOUNT;
        }

        if (filter_var($Search, FILTER_VALIDATE_INT) == false) {

            $Search = DEFAULT_PRODUCT_RETURN_AMOUNT;
        }

        $query = "SELECT
                      p.StockItemID, p.StockItemName, s.SupplierName, c.ColorName, u.PackageTypeName UnitPackageTypeName, o.PackageTypeName OuterPackageTypeName,
                      p.Brand, p.Size, p.LeadTimeDays, p.QuantityPerOuter, p.IsChillerStock, p.Barcode, p.TaxRate, p.UnitPrice, p.RecommendedRetailPrice,
                      p.TypicalWeightPerUnit, p.MarketingComments, p.InternalComments, p.Photo, p.CustomFields, p.Tags, p.SearchDetails,
                      p.LastEditedBy, p.ValidFrom, p.ValidTo, gr.StockGroupName, h.QuantityOnHand, g.StockGroupID
                  FROM
                      " . self::TABLE_NAME . " p
                  LEFT JOIN suppliers s ON p.SupplierID = s.SupplierID
                  LEFT JOIN colors c ON p.ColorID = c.ColorID
                  LEFT JOIN packagetypes u ON p.UnitPackageID = u.PackageTypeID
                  LEFT JOIN packagetypes o ON p.OuterPackageID = o.PackageTypeID
                  LEFT JOIN stockitemstockgroups g ON p.StockItemID = g.StockItemID
                  LEFT JOIN stockgroups gr ON gr.StockGroupID = g.StockGroupID
                  LEFT JOIN stockitemholdings h on h.StockItemID = p.StockItemID
                  WHERE p.StockItemID = :ItemID
                  LIMIT :limiet";

        $stmt = $database->prepare($query);

        // We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt->bindValue(":limiet",   $limit,    PDO::PARAM_INT);
        $stmt->bindValue(":ItemID",   $Search,    PDO::PARAM_INT);

        // Voer de query uit
        $stmt->execute();

        return $stmt;
    }


    /**
     * Zoekt in de product tabel naar $zoekterm.
     *
     * @param PDO $database Een database connectie object (verkrijg met Database::getConnectie();)
     * @param string $zoekterm Waar je op wilt zoeken
     * @param string $OrderBy Dit wordt letterlijk in de query gezet zonder sql injection protectie dus wees veilig lol.
     * @param int $limit Hoeveel producten er gereturned moeten worden. (default en max values staan in constants.php)
     *
     * @return PDOStatement
     */
    public static function zoek($database, $zoekterm, $OrderBy = "p.RecommendedRetailPrice " . DEFAULT_PRODUCT_SORT_ORDER, $StartFrom,  $limit = DEFAULT_PRODUCT_RETURN_AMOUNT) {
        // Als $limit geen integer is, of niet binnen de grenzen valt, wordt de standaard limiet gehanteerd.
        if (filter_var($limit, FILTER_VALIDATE_INT) == false
            || $limit < MIN_PRODUCT_RETURN_AMOUNT
            || $limit > MAX_PRODUCT_RETURN_AMOUNT) {

            $limit = DEFAULT_PRODUCT_RETURN_AMOUNT;
        }


        $query = "SELECT
                      p.StockItemID, p.StockItemName, s.SupplierName, c.ColorName, u.PackageTypeName UnitPackageTypeName, o.PackageTypeName OuterPackageTypeName,
                      p.Brand, p.Size, p.LeadTimeDays, p.QuantityPerOuter, p.IsChillerStock, p.Barcode, p.TaxRate, p.UnitPrice, p.RecommendedRetailPrice,
                      p.TypicalWeightPerUnit, p.MarketingComments, p.InternalComments, p.Photo, p.CustomFields, p.Tags, p.SearchDetails,
                      p.LastEditedBy, p.ValidFrom, p.ValidTo, g.StockGroupID
                  FROM
                      " . self::TABLE_NAME . " p
                  LEFT JOIN suppliers s ON p.SupplierID = s.SupplierID
                  LEFT JOIN colors c ON p.ColorID = c.ColorID
                  LEFT JOIN packagetypes u ON p.UnitPackageID = u.PackageTypeID
                  LEFT JOIN packagetypes o ON p.OuterPackageID = o.PackageTypeID
                  LEFT JOIN stockitemstockgroups g ON p.StockItemID = g.StockItemID                  
                  WHERE p.StockItemName LIKE :zoekterm
                  ORDER BY " . $OrderBy . "
                  LIMIT :begin, :limiet";

        $stmt = $database->prepare($query);

        // Zet wildcards aan het begin en eind van de zoekterm
        $zoekterm = "%" . $zoekterm . "%";

        // We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt->bindValue(":zoekterm", $zoekterm, PDO::PARAM_STR);
        $stmt->bindValue(":limiet",   $limit,    PDO::PARAM_INT);
        $stmt->bindValue(":begin", $StartFrom, PDO::PARAM_INT);
        // Voer de query uit
        $stmt->execute();

        return $stmt;
    }
    /** Interne ID van dit product*/
    public $StockItemID;
    /** Naam van dit product*/
    public $StockItemName;

    /** Naam van de leverancier */
    public $SupplierName;

    /** Kleur van dit product (Black, Red, Blue, etc.) */
    public $ColorName;

    /** Verpakking om elk product (plastic folie, bubbeltjesplastic, etc.)*/
    public $UnitPackageTypeName;
    /** Buitenverpakking (kartonnen doos, pellet, etc.)*/
    public $OuterPackageTypeName;

    /** Merk van dit product, kan leeg zijn of NULL zijn */
    public $Brand;
    /** Fysieke grootte van het product (100mm, 20cm, etc.) */
    public $Size;
    /** Hoeveel dagen voordat het leverbaar is */
    public $LeadTimeDays;
    /** Hoeveel producten kunnen er in een outer package? Zie $OuterPackageTypeName */
    public $QuantityPerOuter;
    /** Of het product in de diepvries ligt */
    public $IsChillerStock;
    /** Barcode voor dit product */
    public $Barcode;

    /** Aantal belasting (in procenten) dat moet worden toegevoegd */
    public $TaxRate;
    /** Verkoopprijs aan leveranciers van dit product (excl. belasting)*/
    public $UnitPrice;
    /** Verkoopprijs aan klanten van dit product (incl. belasting)*/
    public $RecommendedRetailPrice;

    /** Gewicht per product (incl. verpakking)*/
    public $TypicalWeightPerUnit;
    /** Marketing opmerkingen over dit product (wordt gedeeld buiten de organisatie) */
    public $MarketingComments;
    /** Interne opmerkingen over dit product (wordt niet gedeeld buiten de organisatie) */
    protected $InternalComments;
    /** Foto van dit product*/
    public $Photo;
    /** JSON met custom fields */
    public $CustomFields;
    /** Advertising tags voor dit product (zitten ook in customfields) */
    public $Tags;
    protected $SearchDetails;

    protected $LastEditedBy;
    protected $ValidFrom;
    protected $ValidTo;

    public function __construct() {

    }
}

?>
