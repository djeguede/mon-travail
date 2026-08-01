<?php

namespace App\Http\Controllers;
use App\Models\Company;
use App\Models\CompanyCategory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use RealRashid\Support\Facades\Storage;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;



class CompanyController extends Controller
{
   


public function create (): view|RedirectResponse 
   {

    if (auth()->user()->company){
        Alert::toast('Vous avez déjà une entreprise!', 'info');
        return redirect()->route('company.edit');
    }

    $categories = CompanyCategory::all();

    return view('company.create', compact('categories'));
   }









   public function store (Request $request): RedirectResponse 
   {

    $this->validateCompany($request);

    $company = new company () ;

    if ($this->companySave($company , $request)){
        Alert::toast('Entreprise créée ! Vous pouvez désormais ajouter des publications.', 'success');
    } else {
       Alert::toast('Echoué!' , 'Erreur');
    }
    
    return redirect()->route('account.authorSection');

   }





   public function edit():View {

    $company = auth()->user()->company;
    $categories = CompanyCategories::all();

    return view('company.edit' , compact('company', 'categories'));

   }


   public function update(Request $request, Company $company):RedirectResponse 
   {
    $this->validateCompanyUpdate($request);

    $company = auth()->user()->company;
    
    if ($this->companyUpdate($company, $request)){
        Alert::toast('Entreprise mise à jour avec succès ! ', 'succès ');
    } else {
        Alert::toast('Echoué!' , 'Erreur');
    }

    return redirect()->route('account.authorSection');
   }




   protected function validateCompany(Request $request): array 
   {
     return $request->validate([
        'title'=>['required', 'string' , 'min:5'], 
        'description' => ['required', 'string', 'min:5'],
        'logo'=>['required', 'image', 'max:3000'],
        'category'=>['required'],
        'website'=>['required', 'string'],
        'cover_img'=> ['nullable', 'image' , 'max:4000'],
    ]);
   }




   public function validateCompanyUpdate (Request $request): array
   {
    return $request->validate([
            'title' => ['required', 'string', 'min:5'],
            'description' => ['required', 'string', 'min:5'],
            'logo' => ['nullable', 'image', 'max:3000'],
            'category' => ['required'],
            'website' => ['required', 'string'],
            'cover_img' => ['nullable', 'image', 'max:4000'],
        ]);
   }



   protected function companySave(Company $company , Request $request) : bool 
   {
        $company->user_id = auth()->id();
        $company->title = $request->title;
        $company->description = $request->description;
        $company->company_category_id = $request->category;
        $company->website = $request->website;

         // Logo
        $logoName = $this->getFileName($request->file('logo'));
        $request->file('logo')->storeAs('public/companies/logos', $logoName);
        $company->logo = 'storage/companies/logos/' . $logoName;

            // Image de couverture
        if ($request->hasFile('cover_img')) {
            $coverName = $this->getFileName($request->file('cover_img'));
            $request->file('cover_img')->storeAs('public/companies/cover', $coverName);
            $company->cover_img = 'storage/companies/cover/' . $coverName;
        } else {
            $company->cover_img = 'nocover';
        }

        return $company->save();
   }




   protected function companyUpdate (Company $company, Request $request): bool
   {
        $company->title = $request->title;
        $company->description = $request->description;
        $company->company_category_id = $request->category;
        $company->website = $request->website;
        
         if ($request->hasFile('logo')) {

            if ($company->logo && Storage::exists('public/companies/logos/' . basename($company->logo))) {
                Storage::delete('public/companies/logos/' . basename($company->logo));
            }

            $logoName = $this->getFileName($request->file('logo'));
            $request->file('logo')->storeAs('public/companies/logos', $logoName);

            $company->logo = 'storage/companies/logos/' . $logoName;
        }
        
        if ($request->hasFile('cover_img')) {

            if (
                $company->cover_img &&
                $company->cover_img !== 'nocover' &&
                Storage::exists('public/companies/cover/' . basename($company->cover_img))
            ) {
                Storage::delete('public/companies/cover/' . basename($company->cover_img));
            }

            $coverName = $this->getFileName($request->file('cover_img'));
            $request->file('cover_img')->storeAs('public/companies/cover', $coverName);

            $company->cover_img = 'storage/companies/cover/' . $coverName;
        }

        return $company->save();

   }

   

   protected function getFileName($file): string
    {
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        return $fileName . '_' . time() . '.' . $extension;
    }




    public function destroy(): RedirectResponse
    {
        $company = auth()->user()->company;

        if (!$company) {
            return redirect()->route('account.authorSection');
        }

        if ($company->logo) {
            Storage::delete('public/companies/logos/' . basename($company->logo));
        }

        if ($company->cover_img && $company->cover_img !== 'nocover') {
            Storage::delete('public/companies/cover/' . basename($company->cover_img));
        }

        $company->delete();

        Alert::toast('Entreprise supprimée avec succès !', 'succès');

        return redirect()->route('account.authorSection');
    }
   
}
