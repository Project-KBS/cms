<?php


class review
{

    public static function reviewSchrijven($database)
    {
        $query = "INSERT INTO review (`Email`, `StockItemID`, `Title`, `Description`, `Score`, `UpdatedWhen`, `CreatedWhen`)
                VALUES ('job@job', '7', '45', '45', '456', current_timestamp(), current_timestamp())";
        $stmt = $database->prepare($query);
        /*
       // We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt->bindValue(":email", $Email, PDO::PARAM_STR);

        $stmt->bindValue(":StockItemID", $StockItemID, PDO::PARAM_INT);
        $stmt->bindValue(":title", $Title, PDO::PARAM_STR);
        $stmt->bindValue(":description", $Description, PDO::PARAM_STR);
        $stmt->bindValue(":score", $Score, PDO::PARAM_INT);
*/

        //voer de query uit
        try{
            $stmt->execute();
        } catch (Exception $ignored){

        }
        return $stmt;
    }
}
