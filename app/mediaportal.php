<?php

include_once "constants.php";

/**
 * Handige methodes voor ons mediaportaal.
 */
class MediaPortal {

    /**
     * Verkrijg de categorie afbeelding in Base64 encoding.
     *
     * @param int $id De categorie ID.
     * @return string afbeelding in base64.
     * @throws Exception
     */
    public static function getCategoryImage(int $id) {
        // Check eerst of $id een getal is
        if (filter_var($id, FILTER_VALIDATE_INT) == false) {
            throw new Exception("Fout in categorie nummer");
        }

        $filename = "mp/categorie/$id/thumb.jpg";

        // Als het bestand bestaat
        if (file_exists($filename)) {
            return base64_encode(file_get_contents($filename));
        } else {
            throw new Exception("Bestand niet gevonden");
        }
    }

    /**
     * Van deze klasse mogen geen instanties gemaakt worden, dus maak de constructor privé.
     */
    private function __construct() {
        throw new AssertionError("Gebruik getConnection() !!!");
    }

    /**
     * Instanties van deze klasse mogen niet gekloond worden, dus maak clone() privé.
     */
    private function __clone() {
        throw new AssertionError("Het is niet toegestaan om deze klasse te klonen!");
    }
}
