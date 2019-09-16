<?php
// src/Services/ComplexObject.php
namespace App\Services;

class ComplexObject
{
    private $foo;
    private $env;

    public function __construct($foo, $env) {
        $this->foo = $foo;
        $this->env = $env;
    }

    public function getFoo() {
        return $this->foo;
    }

    public function setFoo($_foo) {
        $this->foo = $_foo;
    }

    public function getEnv() {
        return $this->env;
    }

    public function setEnv($_env) {
        $this->env = $_env;
    }

    public function doSomething() {
        // ...
    }
}