<?php

/**
 * Deze klasse bevat handige methoden voor interactie met de winkelmand sessie.
 */
class Cart {

    /**
     * Deze functie verkrijgt de winkelmand uit user's sessie.
     *
     * Hij returnt een array met productId als index en aantal als value.
     *
     * @return array (int => int)
     */
    public static function get() {
        // TODO

        // ff tijdelijk: voeg 6 stuks van product 1 toe.
        //                 en 9 stuks van product 7
        //                 en 1 stuk  van product 9
        return array(1 => 6,
                     7 => 9,
                     9 => 1);
    }

    /**
     * Van deze klasse mogen geen instanties gemaakt worden, dus maak de constructor privé.
     */
    private function __construct() {
        throw new AssertionError("Gebruik een statische method !!!");
    }

    /**
     * Instanties van deze klasse mogen niet gekloond worden, dus maak clone() privé.
     */
    private function __clone() {
        throw new AssertionError("Het is niet toegestaan om deze klasse te klonen!");
    }
}
