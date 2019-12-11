<?php

class HashResult {

    public $hash;
    public $methodStr;
    public $methodClass;

    /**
     * Maak een nieuw resultaat aan met alle waarden.
     *
     * @param $hash
     * @param $methodStr
     * @param $methodClass
     */
    public function __construct($hash, $methodStr, $methodClass) {
        $this->hash = $hash;
        $this->methodStr = $methodStr;
        $this->methodClass = $methodClass;
    }

    /**
     * @return string
     */
    public function getHash() : string {
        return $this->hash;
    }

    /**
     * @return string
     */
    public function getMethod() : string {
        return $this->methodStr;
    }

    /**
     * @return string
     */
    public function getMethodClass() : string{
        return $this->methodClass;
    }
}
