<?php

namespace App\Exceptions\Association;

use Exception;

class AssociationValidationException extends Exception
{
    public function __construct($message = "Datos inválidos para la asociación.")
    {
        parent::__construct($message);
    }

}
