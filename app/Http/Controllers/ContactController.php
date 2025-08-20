<?php

namespace App\Http\Controllers;
use App\Services\ContactService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function index()
    {
        return $this->contactService->getAll();
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'subject' => 'required|string|max:255',
                'message' => 'required|string',
            ]
        );

        return $this->contactService->create($data);
    }

    public function show($id)
    {
        return $this->contactService->find($id);
    }

    public function update($id, Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255',
                'subject' => 'sometimes|required|string|max:255',
                'message' => 'sometimes|required|string',
            ]
        );

        return $this->contactService->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->contactService->delete($id);
    }
}
