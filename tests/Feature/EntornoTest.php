<?php

// tests/Feature/EntornoTest.php
namespace Tests\Feature;

use Tests\TestCase;

class EntornoTest extends TestCase
{
    public function test_entorno_es_testing()
    {
        $this->assertEquals('testing', app()->environment());
    }
}
