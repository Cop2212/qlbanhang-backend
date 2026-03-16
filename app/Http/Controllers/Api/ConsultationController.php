<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Consultation;

class ConsultationController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',

            // PHONE
            'phone' => [
                'required',
                'regex:/^(0|\+84)(3|5|7|8|9)[0-9]{8}$/'
            ],

            // EMAIL
            'email' => 'required|email:rfc,dns',

            'message' => 'nullable|string'
        ]);

        $data['status'] = 'pending';

        Consultation::create($data);

        return response()->json([
            'message' => 'Consultation saved successfully'
        ], 201);
    }
}
