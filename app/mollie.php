<?php

/**
 * Deze class zorgt voor een verbinding met het Mollie API object.
 */
class Mollie {

    /**
     * Dit is het mollie client object.
     */
    private static $apiObject = null;

    /**
     * Verkrijg de mollie client.
     *
     * @return \Mollie\Api\MollieApiClient
     */
    public static function getApi() : \Mollie\Api\MollieApiClient {

        if (self::$apiObject == null) {

            try {
                // Maak een nieuw client object aan
                self::$apiObject = new \Mollie\Api\MollieApiClient();

                // deze key zorgt ervoor dat de betaling wordt gekoppeld aan ons account bij Mollie, zodat we daar de betalingen kunnen zien en de betaalmethodes aanpassen.
                self::$apiObject->setApiKey("test_3mSHuyjnfdsnyBFRKv6P7ucgqPh4Tc");

            } catch (Exception $exception) {
                error_log("Fout bij het verbinden met Mollie!!: " . $exception->getMessage());
            }
        }

        return self::$apiObject;
    }

}
