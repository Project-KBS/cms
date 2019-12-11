<?php

class Review {

    public static function insert($database,  $StockItemID, $Title, $Description, $Score) {
        $query =   'INSERT INTO review ("Email", "StockItemID", "Title", "Description", "Score","CreatedWhen")
                    VALUES ("job@job", :StockItemID, :title, :description, :score, current_timestamp()';
        $stmt = $database->prepare($query);

        //We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen

        //De email gebruiken we pas wanneer we een account kunnen aanmaken tot dan wordt het gehardcode
        //Je moet een mailadres invullen hier boven, die in je accounts staat in de database

        //$stmt->bindValue(":email", $Email, PDO::PARAM_STR);
        $stmt->bindValue(":StockItemID", $StockItemID,  PDO::PARAM_INT);
        $stmt->bindValue(":title",       $Title,        PDO::PARAM_STR);
        $stmt->bindValue(":description", $Description,  PDO::PARAM_STR);
        $stmt->bindValue(":score",       $Score,        PDO::PARAM_INT);


        //voer de query uit
        $stmt->execute();

        //return type void
    }
}
