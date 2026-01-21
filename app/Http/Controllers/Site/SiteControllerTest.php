<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiteControllerTest extends Controller
{
    /**
     * Version de test de subscribeNewsletter sans FormRequest
     */
    public function subscribeNewsletterTest(Request $request)
    {
        \Log::info('🧪 TEST Controller method called', [
            'ip' => $request->ip(),
            'data' => $request->all()
        ]);
        
        try {
            // Validation simple
            $request->validate([
                'email' => 'required|email|max:255',
            ]);
            
            \Log::info('🧪 TEST Validation passed');
            
            $ip = $request->ip();
            $validatedData = [
                'email' => $request->email,
                'nom' => $request->nom ?: 'Test User Controller',
            ];
            
            // Utiliser la même logique que l'original
            $preferences = ['actualites' => true, 'publications' => true];
            
            \DB::beginTransaction();
            
            try {
                $existing = \DB::table('newsletters')
                              ->where('email', $validatedData['email'])
                              ->lockForUpdate()
                              ->first();
                
                \Log::info('🧪 TEST Checked existing', ['found' => $existing ? true : false]);
                
                if ($existing && $existing->actif) {
                    \DB::rollback();
                    return back()->with('info', 'Cette adresse email est déjà inscrite (TEST).');
                }

                if ($existing && !$existing->actif) {
                    // Réactiver
                    \DB::table('newsletters')
                        ->where('email', $validatedData['email'])
                        ->update([
                            'actif' => 1,
                            'nom' => $validatedData['nom'],
                            'preferences' => json_encode($preferences),
                            'updated_at' => now(),
                        ]);
                    
                    \DB::commit();
                    \Log::info('🧪 TEST Reactivated successfully');
                    return back()->with('success', 'Newsletter réactivée avec succès (TEST) !');
                }

                // Nouvelle inscription
                $token = bin2hex(random_bytes(32));
                $inserted = \DB::table('newsletters')->insert([
                    'email' => $validatedData['email'],
                    'nom' => $validatedData['nom'],
                    'token' => $token,
                    'actif' => 1,
                    'preferences' => json_encode($preferences),
                    'emails_sent_count' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                if ($inserted) {
                    \DB::commit();
                    \Log::info('🧪 TEST Inserted successfully');
                    return back()->with('success', 'Inscription réussie (TEST) ! Contrôleur fonctionne.');
                } else {
                    \DB::rollback();
                    \Log::error('🧪 TEST Insert failed');
                    return back()->with('error', 'Échec insertion (TEST).');
                }
                
            } catch (\Exception $e) {
                \DB::rollback();
                throw $e;
            }
            
        } catch (\Exception $e) {
            \Log::error('🧪 TEST Controller error', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return back()->with('error', 'Erreur contrôleur test: ' . $e->getMessage());
        }
    }
}
