<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewsletterSubscriptionRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette demande.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtient les règles de validation qui s'appliquent à la demande.
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email', // Temporairement sans rfc,dns pour tests
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'not_regex:/[<>"\'\(\);&|`]/', // Protection XSS
            ],
            'nom' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-zA-ZÀ-ÿ\s\-\'\.]+$/', // Lettres, espaces, tirets, apostrophes, points
                'not_regex:/[<>"\(\);&|`]/', // Protection XSS
            ],
            'preferences' => [
                'nullable',
                'array',
                'max:5'
            ],
            'preferences.*' => [
                'string',
                Rule::in(['actualites', 'publications', 'rapports', 'evenements', 'projets'])
            ],
            'redirect_url' => [
                'nullable',
                'url',
                'max:255',
                // Regex allégé pour accepter localhost et IP
                'regex:/^https?:\/\/[a-zA-Z0-9.-]+(:[0-9]+)?(\/.*)?$/',
            ],
            // Protection honeypot contre les bots
            'website' => 'nullable|max:0', // Champ invisible qui doit rester vide
            'phone' => 'nullable|max:0',   // Champ invisible qui doit rester vide
        ];
    }

    /**
     * Messages d'erreur personnalisés
     */
    public function messages(): array
    {
        return [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'email.regex' => 'Format d\'email invalide.',
            'email.not_regex' => 'L\'email contient des caractères non autorisés.',
            'nom.regex' => 'Le nom ne peut contenir que des lettres, espaces, tirets et apostrophes.',
            'nom.not_regex' => 'Le nom contient des caractères non autorisés.',
            'nom.max' => 'Le nom ne peut dépasser 100 caractères.',
            'preferences.max' => 'Maximum 5 préférences autorisées.',
            'preferences.*.in' => 'Préférence non valide.',
            'redirect_url.url' => 'URL de redirection invalide.',
            'website.max' => 'Tentative de spam détectée.',
            'phone.max' => 'Tentative de spam détectée.',
        ];
    }

    /**
     * Prépare les données pour la validation
     */
    protected function prepareForValidation(): void
    {
        // Nettoie et sécurise les données avant validation
        $this->merge([
            'email' => $this->sanitizeInput($this->input('email')),
            'nom' => $this->sanitizeInput($this->input('nom')),
        ]);
    }

    /**
     * Nettoie les données d'entrée
     */
    private function sanitizeInput(?string $input): ?string
    {
        if ($input === null) {
            return null;
        }

        // Supprime les balises HTML et JavaScript
        $input = strip_tags($input);
        
        // Supprime les caractères de contrôle
        $input = preg_replace('/[\x00-\x1F\x7F]/', '', $input);
        
        // Trim et normalise les espaces
        $input = trim($input);
        $input = preg_replace('/\s+/', ' ', $input);
        
        return $input;
    }

    /**
     * Obtient les données validées avec nettoyage supplémentaire
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        
        if ($key === null) {
            // Nettoyage final des données
            if (isset($validated['email'])) {
                $validated['email'] = strtolower($validated['email']);
            }
            
            if (isset($validated['nom'])) {
                $validated['nom'] = ucwords(strtolower($validated['nom']));
            }
        }
        
        return $validated;
    }
}
