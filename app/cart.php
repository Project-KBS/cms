<?php

/**
 * Deze klasse bevat handige methoden voor interactie met de winkelmand sessie.
 */
class Cart {

    /**
     * Deze functie verkrijgt de winkelmand producten uit user's sessie en/of post data.<br />
     *
     * Hij returnt een array met productId als index en aantal als value.<br />
     *
     * De array kan dus ook leeg zijn!!!
     *
     * @return array (int => int)
     */
    public static function get() {
        $result = array();

        // Loop over alle post en sessie elementen
        foreach ([$_POST, $_SESSION] as $array) {
            foreach ($array as $index => $value) {
                $index_num = intval($index);
                $value_num = intval($value);

                // Check of ze beide integers integers zijn en groter dan 0
                if ($index_num > 0 && $value_num > 0) {
                    $result[$index_num] = $value_num;
                }
            }
        }

        return $result;
    }

    /**
     * Deze functie voegt een product toe aan de winkelmand (user sessie).<br />
     *
     * Aantal en productId moeten positief zijn en de sessie moet gestart zijn.
     *
     * @param $productId
     * @param $aantal
     * @return void
     */
    public static function add($productId, $aantal) {
        // Controleer of productId en aantal integers zijn en of ze hoger dan 0 zijn.
        session_status() === 0;
        if (is_int($productId) && $productId > 0 &&
            is_int($aantal)    && $aantal > 0    ) {
            $_SESSION[$productId] = $aantal;
        }
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
