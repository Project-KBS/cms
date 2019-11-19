<?php

/**
 * Als dit op TRUE staat, dan worden foutmeldingen van PDO enzo getoond.  <br /><br />
 *
 * Gebruik dit dus alleen onder het testen, en niet in production!
 */
const IS_DEBUGGING_ENABLED = true;

/**
 * Dit is hoeveel producten er normaal worden getoond
 */
const DEFAULT_PRODUCT_RETURN_AMOUNT = 10;

/**
 * Dit is hoeveel producten er maximaal getoond kunnen worden (beveiliging tegen server overload en DDoS)
 */
const MAX_PRODUCT_RETURN_AMOUNT = 1000;

/**
 * Dit is hoeveel producten er minimaal getoond kunnen worden
 */
const MIN_PRODUCT_RETURN_AMOUNT = 1;
/**
 * Dit is de default sorteer volgorde: A-Z
 */
const DEFAULT_PRODUCT_SORT_ORDER = "ASC";

