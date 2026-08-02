<?php

namespace App\Http\Controllers;

use App\Models\CompanyCategory;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CompanyCategoryController extends Controller
{
    /**
     * Enregistrer une nouvelle catégorie.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|min:5',
        ]);

        CompanyCategory::create([
            'category_name' => $request->category_name,
        ]);

        Alert::toast('Catégorie créée avec succès !', 'success');

        return redirect()->route('account.dashboard');
    }

    /**
     * Afficher le formulaire de modification.
     */
    public function edit(CompanyCategory $category)
    {
        return view('company-category.edit', compact('category'));
    }

    /**
     * Mettre à jour une catégorie.
     */
    public function update(Request $request, CompanyCategory $category)
    {
        $request->validate([
            'category_name' => 'required|min:5',
        ]);

        $category->update([
            'category_name' => $request->category_name,
        ]);

        Alert::toast('Catégorie mise à jour avec succès !', 'success');

        return redirect()->route('account.dashboard');
    }

    /**
     * Supprimer une catégorie.
     */
    public function destroy(CompanyCategory $category)
    {
        $category->delete();

        Alert::toast('Catégorie supprimée avec succès !', 'success');

        return redirect()->route('account.dashboard');
    }
}