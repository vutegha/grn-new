<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmailSettingController extends Controller
{
    /**
     * Affichage de la page de configuration des emails
     */
    public function index()
    {
        $this->authorize('manage_email_settings');
        
        $emailSettings = EmailSetting::orderBy('key')->get();
        
        $breadcrumbs = [
            ['name' => 'Tableau de bord', 'url' => route('admin.dashboard')],
            ['name' => 'Configuration emails', 'url' => null]
        ];

        return view('admin.email-settings.index', compact('emailSettings', 'breadcrumbs'));
    }

    /**
     * Mettre à jour une configuration email
     */
    public function update(Request $request, EmailSetting $emailSetting)
    {
        $this->authorize('manage_email_settings');
        $validator = Validator::make($request->all(), [
            'emails' => 'required|array|min:1',
            'emails.*' => 'required|email|max:255',
            'active' => 'boolean'
        ], [
            'emails.required' => 'Au moins une adresse email est requise.',
            'emails.*.email' => 'Chaque adresse doit être un email valide.',
            'emails.*.required' => 'Toutes les adresses email sont obligatoires.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Nettoyer et valider les emails
            $emails = array_map('trim', $request->emails);
            $emails = array_filter($emails); // Supprimer les entrées vides
            $emails = array_values(array_unique($emails)); // Supprimer les doublons et réindexer

            if (empty($emails)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Au moins une adresse email valide est requise.'
                ], 422);
            }

            $emailSetting->update([
                'emails' => $emails,
                'active' => $request->boolean('active', true)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Configuration mise à jour avec succès.',
                'data' => $emailSetting->fresh()
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur mise à jour configuration email: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour.'
            ], 500);
        }
    }

    /**
     * Tester l'envoi d'email avec les configurations actuelles
     */
    public function testEmail(Request $request)
    {
        \Log::info('Test email request received', $request->all());
        
        $validation = $request->validate([
            'email_setting_id' => 'required|exists:email_settings,id',
            'test_email' => 'required|email'
        ]);
        
        \Log::info('Validation passed', $validation);

        try {
            $emailSetting = EmailSetting::findOrFail($request->email_setting_id);
            
            \Log::info('EmailSetting found', [
                'id' => $emailSetting->id,
                'key' => $emailSetting->key,
                'active' => $emailSetting->active,
                'emails' => $emailSetting->emails
            ]);
            
            if (!$emailSetting->active) {
                \Log::warning('EmailSetting is inactive', ['id' => $emailSetting->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Cette configuration email est désactivée.'
                ], 400);
            }

            // Envoyer un email de test à toutes les adresses configurées
            $testEmails = $emailSetting->emails;
            $testEmails[] = $request->test_email; // Ajouter l'email de test
            
            \Log::info('Sending test emails to', ['emails' => $testEmails]);
            
            foreach ($testEmails as $email) {
                \Log::info('Sending to', ['email' => $email]);
                \Mail::raw(
                    "Ceci est un email de test pour la configuration '{$emailSetting->label}'.\n\n" .
                    "Configuration: {$emailSetting->key}\n" .
                    "Envoyé le: " . now()->format('d/m/Y à H:i:s') . "\n\n" .
                    "Si vous recevez cet email, la configuration fonctionne correctement.",
                    function ($message) use ($email, $emailSetting) {
                        $message->to($email)
                                ->subject("Test de configuration email - {$emailSetting->label}");
                    }
                );
                \Log::info('Email sent successfully to', ['email' => $email]);
            }

            \Log::info('All test emails sent successfully');
            
            return response()->json([
                'success' => true,
                'message' => "Email de test envoyé à " . count($testEmails) . " adresse(s).",
                'recipients' => $testEmails
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur test email: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi du test: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ajouter une nouvelle adresse email à une configuration
     */
    public function addEmail(Request $request, EmailSetting $emailSetting)
    {
        $request->validate([
            'email' => 'required|email|max:255'
        ]);

        $currentEmails = $emailSetting->emails;
        $newEmail = trim($request->email);

        if (in_array($newEmail, $currentEmails)) {
            return response()->json([
                'success' => false,
                'message' => 'Cette adresse email existe déjà dans la configuration.'
            ], 422);
        }

        $currentEmails[] = $newEmail;
        $emailSetting->update(['emails' => $currentEmails]);

        return response()->json([
            'success' => true,
            'message' => 'Adresse email ajoutée avec succès.',
            'data' => $emailSetting->fresh()
        ]);
    }

    /**
     * Supprimer une adresse email d'une configuration
     */
    public function removeEmail(Request $request, EmailSetting $emailSetting)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $currentEmails = $emailSetting->emails;
        $emailToRemove = trim($request->email);

        if (!in_array($emailToRemove, $currentEmails)) {
            return response()->json([
                'success' => false,
                'message' => 'Cette adresse email n\'existe pas dans la configuration.'
            ], 422);
        }

        $newEmails = array_values(array_filter($currentEmails, function($email) use ($emailToRemove) {
            return $email !== $emailToRemove;
        }));

        if (empty($newEmails) && $emailSetting->required) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer cette adresse: au moins une adresse est requise pour cette configuration.'
            ], 422);
        }

        $emailSetting->update(['emails' => $newEmails]);

        return response()->json([
            'success' => true,
            'message' => 'Adresse email supprimée avec succès.',
            'data' => $emailSetting->fresh()
        ]);
    }
}
