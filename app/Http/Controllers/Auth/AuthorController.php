<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\Company;
use Carbon\Carbon;

class AuthorController extends Controller
{
    /** Tableau de bord de l'utilisateur ayant le rôle "author" */
    public function authorSection()
    {
        $livePosts = null;
        $company = null;
        $applications = null;

        if ($this->hasCompany()) {
            // Sans le bloc if, la relation posts provoque une erreur
            $company = auth()->user()->company;
            $posts = $company->posts()->get();

            if ($company->posts->count()) {
                $livePosts = $posts->where('deadline', '>', Carbon::now())->count();
                $ids = $posts->pluck('id');
                $applications = JobApplication::whereIn('post_id', $ids)->get();
            }
        }

        // L'utilisateur n'a aucune entreprise associée
        return view('account.author-section')->with([
            'company' => $company,
            'applications' => $applications,
            'livePosts' => $livePosts
        ]);

    }

    /** Tableau de bord de l'employeur */
    // puis affiche les informations de cette entreprise ainsi que ses publications
    public function employer($employer)
    {
        $company = Company::find($employer)->with('posts')->first();
        return view('account.employer')->with([
            'company' => $company,
        ]);
    }

    // Vérifie si l'utilisateur connecté est associé à une entreprise
    protected function hasCompany()
    {
        return auth()->user()->company ? true : false;
    }



}
