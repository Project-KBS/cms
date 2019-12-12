<?php


class Form
{

    public static function Email($input){
        $input = trim($input);


        return true;
    }

    public static function Wachtwoord($input){

        return true;
    }

    public static function Voornaam($input){

        return true;
    }

    public static function Tussenvoegsel($input){

        return true;
    }

    public static function Achternaam($input){

        return true;
    }

    public static function Straatnaam($input){

        return true;
    }

    public static function Huisnummer($input){

        return true;
    }

    public static function Toevoeging($input){

        return true;
    }

    public static function Postcode($input){
        $input = str_replace(" ","", $input);
        if(strlen($input) === 6){

            for($i=0; $i <= 3; $i++){
                if(!is_numeric($input[$i])){
                    return false;
                }
            }

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

    return true;
}










}
