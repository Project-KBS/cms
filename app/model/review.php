<?php

class Review {

    public static function insert($database, $Email,  $StockItemID, $Title, $Description, $Score) {
        $query =   "INSERT INTO review (Email,      StockItemID,    Title,      Description,    Score)
                    VALUES (           :email,      :StockItemID,   :title,     :description,   :score)";

        $stmt = $database->prepare($query);

        //We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen

        //De email gebruiken we pas wanneer we een account kunnen aanmaken tot dan wordt het gehardcode
        //Je moet een mailadres invullen hier boven, die in je accounts staat in de database

        $stmt->bindValue(":email",       $Email,        PDO::PARAM_STR);
        $stmt->bindValue(":StockItemID", $StockItemID,  PDO::PARAM_INT);
        $stmt->bindValue(":title",       $Title,        PDO::PARAM_STR);
        $stmt->bindValue(":description", $Description,  PDO::PARAM_STR);
        $stmt->bindValue(":score",       $Score,        PDO::PARAM_INT);


        //voer de query uit
        $stmt->execute();

        //return type void
    }

    public static function read($database, $StockItemID){
        $query = "SELECT
                      Email, UpdatedWhen, StockItemID, Title, Description, Score
                  FROM
                      Review
                  WHERE
                      StockItemID = :StockItemID";

        $stmt = $database->prepare($query);
        //We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt->bindValue(":StockItemID", $StockItemID, PDO::PARAM_INT);

        $stmt->execute();

        return($stmt);
    }

    public static function update($database, $Email,  $StockItemID, $Title, $Description, $Score) {
        $query =   "UPDATE review
                    SET Title = :title, Description = :description, Score = :score, UpdatedWhen = NOW()
                    WHERE Email = :email AND StockItemID = :StockItemID";

        $stmt = $database->prepare($query);

        //We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen

        //De email gebruiken we pas wanneer we een account kunnen aanmaken tot dan wordt het gehardcode
        //Je moet een mailadres invullen hier boven, die in je accounts staat in de database

        $stmt->bindValue(":email",       $Email,        PDO::PARAM_STR);
        $stmt->bindValue(":StockItemID", $StockItemID,  PDO::PARAM_INT);
        $stmt->bindValue(":title",       $Title,        PDO::PARAM_STR);
        $stmt->bindValue(":description", $Description,  PDO::PARAM_STR);
        $stmt->bindValue(":score",       $Score,        PDO::PARAM_INT);


        //voer de query uit
        $stmt->execute();

        //return type void
    }

    public static function readOne($database, $StockItemID, $Email){
        $query = "SELECT Title, Description, Score
                  FROM review
                  WHERE StockItemID = :StockItemID AND Email = :email";

        $stmt = $database->prepare($query);

        //We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt->bindValue(":StockItemID", $StockItemID, PDO::PARAM_INT);
        $stmt->bindValue(":email",       $Email,       PDO::PARAM_STR);

        $stmt->execute();

        return($stmt);
    }

    public static function delete($database, $StockItemID, $Email){

        $query =   "DELETE
                    FROM review 
                    WHERE StockItemID = :StockItemID AND Email = :email";

        $stmt = $database->prepare($query);

        //We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt = $database->prepare($query);

        //We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt->bindValue(":StockItemID", $StockItemID, PDO::PARAM_INT);
        $stmt->bindValue(":email",       $Email,       PDO::PARAM_STR);

        $stmt->execute();

        return($stmt);
    }
}

