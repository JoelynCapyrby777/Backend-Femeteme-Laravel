<?php

namespace App\Exceptions\Association;

use Symfony\Component\HttpKernel\Exception\HttpException;

class AssociationNotFoundException extends HttpException
{
    public function __construct()
    {
        parent::__construct(404, 'Asociación no encontrada.');
    }
}
