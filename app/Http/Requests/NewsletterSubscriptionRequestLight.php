<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewsletterSubscriptionRequestLight extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette demande.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Version allégée pour tests - Validation moins stricte
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email', // Sans rfc,dns pour éviter les problèmes de DNS
                'max:255',
            ],
            'nom' => [
                'nullable',
                'string',
                'max:100',
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
            ],
            // Champs honeypot allégés
            'website' => 'nullable|max:0',
            'phone' => 'nullable|max:0',
            'start_time' => 'nullable|numeric',
        ];
    }

    /**
     * Messages d'erreur simplifiés
     */
    public function messages(): array
    {
        return [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'website.max' => 'Tentative de spam détectée.',
            'phone.max' => 'Tentative de spam détectée.',
        ];
    }
}
