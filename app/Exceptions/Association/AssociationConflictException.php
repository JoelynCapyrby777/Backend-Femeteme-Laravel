<?php

namespace App\Exceptions\Association;

use Exception;

class AssociationConflictException extends Exception
{
    public function __construct($message = "Conflicto con los datos de la asociación.")
    {
        parent::__construct($message);
    }

}
