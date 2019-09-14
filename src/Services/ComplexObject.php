<?php
// src/Services/ComplexObject.php
namespace App\Services;

class ComplexObject
{
    private $foo;

    public function __construct($foo) {
        $this->foo = $foo;
    }

    public function getFoo() {
        return $this->foo;
    }

    public function setFoo($_foo) {
        $this->foo = $_foo;
    }

    public function doSomething() {
        // ...
    }
}