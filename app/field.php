<?php class Field {

/**
* @var string
*/
public $naam;

/**
* @var mixed
*/
public $var;

/**
* @var bool
*/
public $required;

/**
* Field constructor.
*
* @param $naam
* @param $var
* @param $required
*/
public function __construct(string $naam, $var, bool $required) {
$this->naam = $naam;
$this->var = $var;
$this->required = $required;
}


/**
* @return string
*/
public function getNaam() : string {
return $this->naam;
}

/**
* @return mixed
*/
public function getVar() {
return $this->var;
}

/**
* @return bool
*/
public function isRequired() : bool {
return $this->required;
}

}

class Register{

    /**
     * @var string
     */
    public $naam;

    /**
     * @var bool
     */
    public $required;

    /**
     * Field constructor.
     *
     * @param $naam
     * @param $required
     */
    public function __construct(string $naam, bool $required) {
        $this->naam = $naam;
        $this->required = $required;
    }

    /**
     * @return string
     */
    public function getNaam() : string {
        return $this->naam;
    }



    /**
     * @return bool
     */
    public function isRequired() : bool {
        return $this->required;
    }
}

?>
