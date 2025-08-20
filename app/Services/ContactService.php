<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Contact;

class ContactService
{
    public function getAll()
    {
        return Contact::orderByDesc('created_at')->paginate(10);
    }

    public function create(array $data)
    {
        return Contact::create($data);
    }

    public function find($id)
    {
        return Contact::find($id);
    }

    public function update($id, array $data)
    {
        $contact = Contact::find($id);

        if ($contact) {
            $contact->update($data);
            return $contact;
        }

        return null;
    }

    public function delete($id)
    {
        $contact = Contact::find($id);

        if ($contact) {
            $contact->delete();
            return true;
        }

        return false;
    }
}
