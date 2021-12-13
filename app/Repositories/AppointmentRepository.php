<?php

namespace App\Repositories;

use App\Models\Appointment;
use App\Repositories\Interfaces\AppointmentRepositoryInterface;


class AppointmentRepository implements AppointmentRepositoryInterface
{
    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function all()
    {
        return $this->appointment->all();
    }
    public function create(array $data)
    {
        return $this->appointment->create($data);
    }

    public function delete($id)
    {
        return $this->appointment->destroy($id);
    }

    public function update(array $data, $id)
    {
        return $this->appointment->where('id', $id)
            ->update($data);
    }

    public function find($id)
    {
        return $this->appointment->find($id);
    }

    public function findPostCode($postcode)
    {
        return $this->appointment->where('postcode', $postcode)
            ->get();
    }

    public function findUser($id)
    {
        return $this->appointment->where('user_id', $id)
            ->orderByDesc('id')
            ->first();
    }
}