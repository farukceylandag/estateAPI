<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Repositories\Interfaces\ContactRepositoryInterface;


class ContactRepository implements ContactRepositoryInterface
{
    protected $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function all()
    {
        return $this->contact->all();
    }
    public function create(array $data)
    {
        return $this->contact->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->contact->where('id', $id)
            ->update($data);
    }

    public function delete($id)
    {
        return $this->contact->destroy($id);
    }

    public function find($id)
    {
        return $this->contact->find($id);
    }
}