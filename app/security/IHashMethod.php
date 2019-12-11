<?php

interface IHashMethod {

    public function hash(string $input) : HashResult;

}
