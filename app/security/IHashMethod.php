<?php

interface IHashMethod {

    /**
     * Een array met alle beschikbare Hash Methods.
     */
    public const AVAILABLE_METHODES = [StandardHashMethod::class];

    public function hash(string $input) : HashResult;

}
