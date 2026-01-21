<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobOffer;
use App\Services\JobApplicationFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class JobApplicationController extends Controller
{
    /**
     * Afficher la liste des candidatures
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', JobApplication::class);
        
        $query = JobApplication::with([
            'jobOffer:id,title,location,slug',
            'reviewer:id,name'
        ])->orderBy('created_at', 'desc');

        // Utiliser le service de filtrage
        $filterService = app(JobApplicationFilterService::class);
        $query = $filterService->apply($query, $request);

        $applications = $query->paginate(20);
        $jobOffers = JobOffer::orderBy('title')->get();

        // Pour la sélection dans le filtre, déterminer quelle offre est sélectionnée
        $selectedJobOffer = null;
        if ($request->filled('job_offer')) {
            $selectedJobOffer = is_numeric($request->job_offer) 
                ? JobOffer::find($request->job_offer)
                : JobOffer::where('slug', $request->job_offer)->first();
        }

        return view('admin.job-applications.index', compact('applications', 'jobOffers', 'selectedJobOffer'));
    }

    /**
     * Afficher une candidature spécifique
     */
    public function show(JobApplication $application)
    {
        $this->authorize('view', $application);
        $application->load(['jobOffer', 'reviewer']);
        
        return view('admin.job-applications.show', compact('application'));
    }

    /**
     * Mettre à jour le statut d'une candidature
     */
    public function updateStatus(Request $request, JobApplication $application)
    {
        $this->authorize('update', $application);
        
        $request->validate([
            'status' => ['required', Rule::in(JobApplication::getValidStatuses())],
            'admin_notes' => 'nullable|string|max:2000|regex:/^[a-zA-Z0-9\s\p{P}\p{L}]*$/u',
            'score' => 'nullable|integer|between:0,100'
        ]);

        try {
            $oldStatus = $application->status;
            
            $application->updateStatus(
                $request->status,
                auth()->id(),
                $request->admin_notes,
                $request->score
            );

            // Optionnel : Envoyer un email de notification au candidat
            if ($oldStatus !== $request->status && in_array($request->status, ['accepted', 'rejected'])) {
                // Mail::to($application->email)->send(new JobApplicationStatusUpdate($application));
            }

            return back()->with('success', 'Statut de la candidature mis à jour avec succès.');
            
        } catch (\Exception $e) {
            Log::error('Error updating application status', [
                'application_id' => $application->id,
                'user_id' => auth()->id(),
                'requested_status' => $request->status,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Erreur lors de la mise à jour du statut.');
        }
    }

    /**
     * Télécharger le CV d'un candidat
     */
    public function downloadCV(JobApplication $application)
    {
        $this->authorize('view', $application);
        
        if (!$application->cv_path) {
            return back()->with('error', 'Aucun CV n\'est associé à cette candidature.');
        }

        try {
            if (!Storage::disk('public')->exists($application->cv_path)) {
                Log::warning('CV file not found', [
                    'application_id' => $application->id,
                    'cv_path' => $application->cv_path
                ]);
                return back()->with('error', 'Le fichier CV n\'existe pas ou a été supprimé.');
            }

            $filename = $application->first_name . '_' . $application->last_name . '_CV.pdf';
            return Storage::disk('public')->download($application->cv_path, $filename);
            
        } catch (\Exception $e) {
            Log::error('Error downloading CV', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Erreur lors du téléchargement du CV.');
        }
    }

    /**
     * Télécharger le portfolio d'un candidat
     */
    public function downloadPortfolio(JobApplication $application)
    {
        $this->authorize('view', $application);
        
        if (!$application->portfolio_path) {
            return back()->with('error', 'Aucun portfolio n\'est associé à cette candidature.');
        }

        try {
            if (!Storage::disk('public')->exists($application->portfolio_path)) {
                Log::warning('Portfolio file not found', [
                    'application_id' => $application->id,
                    'portfolio_path' => $application->portfolio_path
                ]);
                return back()->with('error', 'Le fichier portfolio n\'existe pas ou a été supprimé.');
            }

            $filename = $application->first_name . '_' . $application->last_name . '_Portfolio.pdf';
            return Storage::disk('public')->download($application->portfolio_path, $filename);
            
        } catch (\Exception $e) {
            Log::error('Error downloading portfolio', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Erreur lors du téléchargement du portfolio.');
        }
    }

    /**
     * Supprimer une candidature
     */
    public function destroy(JobApplication $application)
    {
        $this->authorize('delete', $application);
        
        // Supprimer les fichiers associés
        if ($application->cv_path) {
            Storage::disk('public')->delete($application->cv_path);
        }
        
        if ($application->portfolio_path) {
            Storage::disk('public')->delete($application->portfolio_path);
        }

        $application->delete();

        return redirect()->route('admin.job-applications.index')
            ->with('success', 'Candidature supprimée avec succès.');
    }

    /**
     * Exporter les candidatures en CSV
     */
    public function export(Request $request)
    {
        $query = JobApplication::with(['jobOffer']);

        // Appliquer les mêmes filtres que pour l'index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('job_offer_id')) {
            $query->where('job_offer_id', $request->job_offer_id);
        }

        $applications = $query->get();

        $filename = 'candidatures_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM pour Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Prénom',
                'Nom',
                'Email',
                'Téléphone',
                'Offre d\'emploi',
                'Statut',
                'Note',
                'Date de candidature',
                'Date de révision'
            ], ';');

            // Données
            foreach ($applications as $application) {
                fputcsv($file, [
                    $application->id,
                    $application->first_name,
                    $application->last_name,
                    $application->email,
                    $application->phone,
                    $application->jobOffer->title,
                    ucfirst($application->status),
                    $application->score,
                    $application->created_at->format('d/m/Y H:i'),
                    $application->reviewed_at ? $application->reviewed_at->format('d/m/Y H:i') : '-'
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Marquer plusieurs candidatures comme révisées
     */
    public function bulkReview(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:job_applications,id',
            'status' => 'required|in:reviewed,shortlisted,rejected'
        ]);

        JobApplication::whereIn('id', $request->application_ids)
            ->update([
                'status' => $request->status,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now()
            ]);

        $count = count($request->application_ids);
        
        return back()->with('success', "{$count} candidature(s) mise(s) à jour avec succès.");
    }

    /**
     * Statistiques des candidatures
     */
    public function statistics()
    {
        $stats = [
            'total_applications' => JobApplication::count(),
            'pending_applications' => JobApplication::where('status', 'pending')->count(),
            'reviewed_applications' => JobApplication::where('status', 'reviewed')->count(),
            'accepted_applications' => JobApplication::where('status', 'accepted')->count(),
            'rejected_applications' => JobApplication::where('status', 'rejected')->count(),
            'recent_applications' => JobApplication::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        $applicationsByJob = JobApplication::selectRaw('job_offer_id, COUNT(*) as count')
            ->with('jobOffer:id,title')
            ->groupBy('job_offer_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $applicationsByMonth = JobApplication::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        return view('admin.job-applications.statistics', compact('stats', 'applicationsByJob', 'applicationsByMonth'));
    }
}
