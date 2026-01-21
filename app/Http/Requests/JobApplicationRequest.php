<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Informations personnelles avec nettoyage strict
            'first_name' => 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ\s\-\']+$/u',
            'last_name' => 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ\s\-\']+$/u',
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                function ($attribute, $value, $fail) {
                    $job = $this->route('job');
                    if ($job instanceof \App\Models\JobOffer) {
                        $exists = \App\Models\JobApplication::where('email', $value)
                            ->where('job_offer_id', $job->id)
                            ->exists();
                        if ($exists) {
                            $fail('Vous avez déjà postulé pour cette offre d\'emploi.');
                        }
                    }
                }
            ],
            'phone' => 'nullable|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
            'address' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date|before:today|after:' . now()->subYears(100)->toDateString(),
            'gender' => 'nullable|in:masculin,feminin,autre',
            'nationality' => 'nullable|string|max:100|regex:/^[a-zA-ZÀ-ÿ\s\-\']+$/u',
            
            // Champs texte avec protection XSS
            'education' => 'nullable|string|max:2000',
            'experience' => 'nullable|string|max:2000', 
            'skills' => 'nullable|string|max:1000',
            'motivation_letter' => 'required|string|min:50|max:3000',
            
            // Fichiers avec validation stricte
            'cv_file' => [
                'required',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120', // 5MB
                function ($attribute, $value, $fail) {
                    if ($value) {
                        // Vérifier le type MIME réel
                        $realMimeType = $value->getMimeType();
                        $allowedMimes = [
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                        ];
                        if (!in_array($realMimeType, $allowedMimes)) {
                            $fail('Le format du fichier CV n\'est pas autorisé.');
                        }
                        
                        // Vérifier la taille du fichier
                        if ($value->getSize() > 5242880) { // 5MB en bytes
                            $fail('Le fichier CV ne peut pas dépasser 5MB.');
                        }
                    }
                }
            ],
            'portfolio_file' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,zip',
                'max:10240', // 10MB
                function ($attribute, $value, $fail) {
                    if ($value) {
                        // Vérifier le type MIME réel
                        $realMimeType = $value->getMimeType();
                        $allowedMimes = [
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/zip',
                            'application/x-zip-compressed'
                        ];
                        if (!in_array($realMimeType, $allowedMimes)) {
                            $fail('Le format du fichier portfolio n\'est pas autorisé.');
                        }
                        
                        // Vérifier la taille du fichier
                        if ($value->getSize() > 10485760) { // 10MB en bytes
                            $fail('Le fichier portfolio ne peut pas dépasser 10MB.');
                        }
                    }
                }
            ],
            
            // Réponses aux critères
            'criteria_responses' => 'nullable|array|max:20',
            'criteria_responses.*' => 'nullable|string|max:1000',
            
            // Honeypot anti-spam
            'website' => 'nullable|max:0', // Champ caché, doit rester vide
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Le prénom est obligatoire.',
            'first_name.regex' => 'Le prénom ne doit contenir que des lettres, espaces, tirets et apostrophes.',
            'last_name.required' => 'Le nom est obligatoire.',
            'last_name.regex' => 'Le nom ne doit contenir que des lettres, espaces, tirets et apostrophes.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.regex' => 'Format d\'email invalide.',
            'phone.regex' => 'Le numéro de téléphone contient des caractères non autorisés.',
            'nationality.regex' => 'La nationalité ne doit contenir que des lettres.',
            'birth_date.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'birth_date.after' => 'La date de naissance n\'est pas valide.',
            'motivation_letter.required' => 'La lettre de motivation est obligatoire.',
            'motivation_letter.min' => 'La lettre de motivation doit contenir au moins 50 caractères.',
            'motivation_letter.max' => 'La lettre de motivation ne peut pas dépasser 3000 caractères.',
            'cv_file.required' => 'Le CV est obligatoire.',
            'cv_file.mimes' => 'Le CV doit être au format PDF, DOC ou DOCX.',
            'cv_file.max' => 'Le CV ne peut pas dépasser 5MB.',
            'portfolio_file.mimes' => 'Le portfolio doit être au format PDF, DOC, DOCX ou ZIP.',
            'portfolio_file.max' => 'Le portfolio ne peut pas dépasser 10MB.',
            'criteria_responses.max' => 'Trop de réponses aux critères.',
            'website.max' => 'Erreur de validation. Veuillez réessayer.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Nettoyer et sécuriser les données avant validation
        $this->merge([
            'email' => strtolower(trim($this->email ?? '')),
            'first_name' => $this->sanitizeText($this->first_name ?? ''),
            'last_name' => $this->sanitizeText($this->last_name ?? ''),
            'phone' => preg_replace('/[^0-9\+\-\s\(\)]/', '', $this->phone ?? ''),
            'nationality' => $this->sanitizeText($this->nationality ?? ''),
            'address' => $this->sanitizeHtml($this->address ?? ''),
            'education' => $this->sanitizeHtml($this->education ?? ''),
            'experience' => $this->sanitizeHtml($this->experience ?? ''),
            'skills' => $this->sanitizeHtml($this->skills ?? ''),
            'motivation_letter' => $this->sanitizeHtml($this->motivation_letter ?? ''),
        ]);
        
        // Nettoyer les réponses aux critères
        if ($this->has('criteria_responses') && is_array($this->criteria_responses)) {
            $cleanResponses = [];
            foreach ($this->criteria_responses as $key => $response) {
                $cleanResponses[$key] = $this->sanitizeHtml($response ?? '');
            }
            $this->merge(['criteria_responses' => $cleanResponses]);
        }
    }
    
    /**
     * Nettoyer le texte simple (noms, nationalité)
     */
    private function sanitizeText(?string $text): string
    {
        if (!$text) return '';
        
        // Supprimer les balises HTML
        $text = strip_tags($text);
        
        // Supprimer les caractères de contrôle
        $text = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $text);
        
        // Nettoyer les espaces
        $text = trim(preg_replace('/\s+/', ' ', $text));
        
        // Capitaliser correctement
        return ucwords(strtolower($text));
    }
    
    /**
     * Nettoyer le contenu HTML (descriptions, lettres)
     */
    private function sanitizeHtml(?string $html): string
    {
        if (!$html) return '';
        
        // Supprimer toutes les balises HTML sauf les basiques autorisées
        $allowedTags = '<p><br><strong><em><ul><ol><li>';
        $html = strip_tags($html, $allowedTags);
        
        // Échapper les caractères spéciaux
        $html = htmlspecialchars($html, ENT_QUOTES, 'UTF-8', false);
        
        // Supprimer les attributs potentiellement dangereux
        $html = preg_replace('/(<[^>]+)(on\w+|style|script)[^>]*>/i', '$1>', $html);
        
        // Nettoyer les espaces multiples
        $html = trim(preg_replace('/\s+/', ' ', $html));
        
        return $html;
    }
}
