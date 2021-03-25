<?php

class Struct{
    private $name;
    private $id;
    private $balance;

    public function __construct($id, $bal){
        $this->id = $id;
        $this->balance = $bal;
    }

}