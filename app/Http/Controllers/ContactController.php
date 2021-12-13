<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\ContactRepositoryInterface;
use Illuminate\Support\Facades\Validator;


class ContactController extends Controller
{
    private $contact;

    public function __construct(ContactRepositoryInterface $contact)
    {
        $this->contact = $contact;
    }

    public function allContacts()
    {
        return response()->json([
            $this->contact->all()
        ]);
    }

    public function addContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'surname' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:contacts',
            'phone' => 'required|digits:10'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $contact = $this->contact->create($request->all());

        return response()->json([
            'message' => 'Contact successfully added.',
            'user' => $contact
        ], 201);
    }

    public function updateContact(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|between:2,100',
            'surname' => 'string|between:2,100',
            'email' => 'string|email|max:100|unique:contacts',
            'phone' => 'digits:10'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $data = $this->contact->update($validator->validated(), $id);

        return response()->json([
            'message' => 'Contact successfully updated.',
            'contact' => $this->contact->find($id),
        ]);
    }

    public function showContact($id)
    {
        return response()->json([
            $this->contact->find($id)
        ]);
    }

    public function deleteContact($id)
    {
        $contact = $this->contact->find($id);

        if ($contact != null) {

            $deleted = $this->contact->delete($id);

            return response()->json([
                'message' => 'This contact succesfully deleted.',
                'deletedData' =>  $contact
            ], 200);
        } else {
            return response()->json(
                [
                    'message' => 'No such contact was added.',
                ],
                403
            );
        }
    }
}