<?php

namespace App\Repositories\Interfaces;

interface AppointmentRepositoryInterface
{
    public function all();
    public function create(array $data);
    public function delete($id);
    public function update(array $data, $id);
    public function find($id);
    public function findPostCode($postcode);
    public function findUser($id);
}