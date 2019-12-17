<?php


//Hierin worden de security maatregelen voor het formulier op registreren.php gedaan om te controleren of de input in de database opgeslagen kan worden
class Form
{

    public static function Email($input){
        $input = trim($input);
        if(strlen($input) > 320 || strlen($input)<1){
            return false;

        }
        // controleert of de email wel een @ en . heeft
        if(filter_var("$input", FILTER_VALIDATE_EMAIL) === false ){
            return false;
        }

        return true;
    }

    public static function Wachtwoord($input){
        $input = trim($input);
        if(strlen($input) > 255 || strlen($input)<1){
            return false;

        }
        return true;
    }

    public static function Voornaam($input){
        $input = trim($input);
        if(strlen($input) > 45 || strlen($input)<1){
            return false;

        }

        return true;
    }

    public static function Tussenvoegsel($input){
        $input = trim($input);
        if(strlen($input) > 30 ){
            return false;

        }

        return true;
    }

    public static function Achternaam($input){
        $input = trim($input);
        if(strlen($input) > 45 || strlen($input)<1){
            return false;

        }

        return true;
    }

    public static function Straatnaam($input){
        $input = trim($input);
        if(strlen($input) > 45 || strlen($input)<1){
            return false;

        }

        return true;
    }

    public static function Huisnummer($input){
        if(intval($input) <= 0){
            return false;
        }

        return true;
    }

    public static function Toevoeging($input){
        $input = trim($input);
        if(strlen($input) > 45 ){
            return false;

        }

        return true;
    }

    public static function Postcode($input){
        $input = str_replace(" ","", $input);
        if(strlen($input) === 6){

            //Controleert of de eerste 4 waarden cijfers zijn
            for($i=0; $i <= 3; $i++){
                if(!is_numeric($input[$i])){
                    return false;
                }
            }

            // controleert of de laatste 2 waarden in het alfabet voorkomen
            for($i=4; $i<=5; $i++){
                if(!ctype_alpha($input[$i]."")){
                    return false;
                }
            }

        } else {
            return false;
        }

        return true;
    }

    public static function Woonplaats($input){
        $input = trim($input);
        if(strlen($input) > 45 || strlen($input)<1){
            return false;

        }

    return true;
}










}
