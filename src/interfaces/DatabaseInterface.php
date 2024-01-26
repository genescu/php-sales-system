<?php declare(strict_types=1);
namespace genescu\components\Interfaces;

interface DatabaseInterface
{
    public function connect(): void;
}
