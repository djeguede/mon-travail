<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class JobApplicationController extends Controller
{
    /**
     * Afficher toutes les candidatures.
     */
    public function index()
    {
        $applications = collect();

        $company = auth()->user()->company;

        if ($company) {
            $postIds = $company->posts()->pluck('id');

            $applications = JobApplication::with(['user', 'post'])
                ->whereIn('post_id', $postIds)
                ->latest()
                ->paginate(10);
        }

        return view('job-application.index', compact('applications'));
    }

    /**
     * Afficher une candidature.
     */
    public function show(JobApplication $jobApplication)
    {
        $jobApplication->load(['user', 'post.company']);

        return view('job-application.show', [
            'application' => $jobApplication,
            'applicant'   => $jobApplication->user,
            'post'       => $jobApplication->post,
            'company'     => $jobApplication->post->company,
        ]);
    }

    /**
     * Supprimer une candidature.
     */
    public function destroy(JobApplication $jobApplication)
    {
        $jobApplication->delete();

        Alert::toast('Application deleted successfully.', 'success');

        return redirect()->route('jobApplication.index');
    }
}