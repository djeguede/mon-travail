<?php

namespace App\Http\Controllers;

use App\Models\CompanyCategories;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CompanyCategoryController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'category_name'=>'required|min:5',
        ]);

        CompanyCategory::create([
            'category_name' => $request->category_name,
        ]);

        Alert::toast('Categorie crée !','succès');
        return redirect()->route('account.dashboard ');
    }

    public function edit(CompanyCategory $category)
    {
        return view('company-category.edit', compact('category'));
    }

    public function update(Request $request, CompanyCategory $category)
    {
        $request->validate([
            'category_name' => 'required|min:5',
        ]);

        $category->update([
            'category_name' => $request->category_name,
        ]);

        Alert::toast('Category Updated!', 'success');

        return redirect()->route('account.dashboard');
    }

     public function destroy(CompanyCategory $category)
    {
        $category->delete();

        Alert::toast('Category Delete!', 'success');

        return redirect()->route('account.dashboard');
    }
}
