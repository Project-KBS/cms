<?php

class Field
{

    /**
     * @var string
     */
    public $naam;

    /**
     * @var mixed
     */
    public $var;

    /**
     * @var string
     */
    public $id;

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
    public function __construct(string $naam, string $id, $var, bool $required)
    {
        $this->naam = $naam;
        $this->id = $id;
        $this->var = $var;
        $this->required = $required;
    }


    /**
     * @return string
     */
    public function getNaam(): string
    {
        return $this->naam;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getVar()
    {
        return $this->var;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

}

class Register
{

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
    public function __construct(string $naam, bool $required)
    {
        $this->naam = $naam;
        $this->required = $required;
    }

    /**
     * @return string
     */
    public function getNaam(): string
    {
        return $this->naam;
    }


    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }
}

?>
