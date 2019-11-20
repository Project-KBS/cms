<?php

include_once "constants.php";

/**
 * Handige methodes voor ons mediaportaal.
 */
class MediaPortal {

    /**
     * Verkrijg de categorie afbeelding in Base64 encoding.
     *
     * @param int $categoryId De categorie ID.
     * @return string Een afbeelding in base64.
     * @throws Exception Als er geen afbeelding bestaat voor de categorie.
     */
    public static function getCategoryImage(int $categoryId) {
        // Check eerst of $id een getal is
        if (filter_var($categoryId, FILTER_VALIDATE_INT) == false) {
            throw new Exception("Fout in categorie nummer");
        }

        $filename = "mp/categorie/$categoryId/thumb.jpg";

        // Als het bestand bestaat
        if (file_exists($filename)) {
            return base64_encode(file_get_contents($filename));
        } else {
            throw new Exception("Bestand niet gevonden");
        }
    }

    /**
     * Verkrijg een array met base64-encoded foto's behorend tot $productId
     *
     * @param int $productId
     * @return array Met base64 encoded foto's
     * @throws Exception
     */
    public static function getProductImages(int $productId) {
        if (filter_var($productId, FILTER_VALIDATE_INT) == false) {
            throw new Exception("Fout in product nummer");
        }

        $directory = "mp/product/$productId/";

        // Als de product directory bestaat
        if (file_exists($directory) && is_dir($directory)) {
            $images = [];
            $paths = glob($directory . "**.{jpg,png}", GLOB_BRACE);
            foreach ($paths as $path) {
                $images[] = base64_encode(file_get_contents($path));
            }
            return $images;
        } else {
            throw new Exception("Bestand niet gevonden");
        }
    }

    /**
     * Verkrijg een array met video's filepaths behorend tot $productId
     *
     * @param int $productId
     * @return array Met base64 encoded video filepaths
     * @throws Exception
     */
    public static function getProductVideos(int $productId) {
        if (filter_var($productId, FILTER_VALIDATE_INT) == false) {
            throw new Exception("Fout in product nummer");
        }

        $directory = "mp/product/$productId/";

        // Als de product directory bestaat
        if (file_exists($directory) && is_dir($directory)) {
            return glob($directory . "**.{mp4,ogg}", GLOB_BRACE);
        } else {
            throw new Exception("Bestand niet gevonden");
        }
    }

    /**
     * Van deze klasse mogen geen instanties gemaakt worden, dus maak de constructor privé.
     */
    private function __construct() {
        throw new AssertionError("Gebruik de static methods !!!");
    }

    /**
     * Instanties van deze klasse mogen niet gekloond worden, dus maak clone() privé.
     */
    private function __clone() {
        throw new AssertionError("Het is niet toegestaan om deze klasse te klonen!");
    }
}
