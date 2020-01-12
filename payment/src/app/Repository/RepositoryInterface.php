<?php

namespace App\Repository;

/**
 * Interface RepositoryInterface
 * @package App\Repository
 */
interface RepositoryInterface
{
    public function getById(int $id);

    public function getAll();

    public function create(array $data);

    public function update(int $id, array $data);
}
