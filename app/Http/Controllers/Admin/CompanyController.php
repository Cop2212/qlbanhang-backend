<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'tax_code' => 'nullable|string|max:50',
            'representative' => 'nullable|string|max:255',
            'established_year' => 'nullable|integer',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string'
        ]);

        $company = Company::first();

        if (!$company) {
            $company = Company::create($data);
        } else {
            $company->update($data);
        }

        return back()->with('success', 'Cập nhật thành công');
    }
}
