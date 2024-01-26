<?php declare(strict_types=1);
namespace genescu\components\Interfaces;

interface TableInterface
{
    public function insert(array $data): void;

    public function filter(array $criteria): array;
}
