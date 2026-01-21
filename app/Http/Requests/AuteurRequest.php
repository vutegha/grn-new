<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuteurRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $auteurId = $this->route('auteur') ? $this->route('auteur')->id : null;

        return [
            'nom' => [
                'required',
                'string',
                'max:100',
                'min:2',
                'regex:/^[a-zA-ZÀ-ÿ\s\-\'\.]+$/',
            ],
            'prenom' => [
                'nullable',
                'string',
                'max:100',
                'min:2',
                'regex:/^[a-zA-ZÀ-ÿ\s\-\'\.]+$/',
            ],
            'email' => [
                'nullable',
                'email:rfc,dns',
                'max:255',
                Rule::unique('auteurs', 'email')->ignore($auteurId),
            ],
            'institution' => [
                'nullable',
                'string',
                'max:255',
                'min:2',
            ],
            'biographie' => [
                'nullable',
                'string',
                'max:2000',
                'min:10',
            ],
            'photo' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:2048', // 2MB
                'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000',
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nom' => 'nom',
            'prenom' => 'prénom',
            'email' => 'adresse email',
            'institution' => 'institution',
            'biographie' => 'biographie',
            'photo' => 'photo de profil',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.min' => 'Le nom doit contenir au moins :min caractères.',
            'nom.max' => 'Le nom ne peut pas dépasser :max caractères.',
            'nom.regex' => 'Le nom ne peut contenir que des lettres, espaces, tirets et apostrophes.',
            
            'prenom.min' => 'Le prénom doit contenir au moins :min caractères.',
            'prenom.max' => 'Le prénom ne peut pas dépasser :max caractères.',
            'prenom.regex' => 'Le prénom ne peut contenir que des lettres, espaces, tirets et apostrophes.',
            
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée par un autre auteur.',
            'email.max' => 'L\'adresse email ne peut pas dépasser :max caractères.',
            
            'institution.min' => 'Le nom de l\'institution doit contenir au moins :min caractères.',
            'institution.max' => 'Le nom de l\'institution ne peut pas dépasser :max caractères.',
            
            'biographie.min' => 'La biographie doit contenir au moins :min caractères.',
            'biographie.max' => 'La biographie ne peut pas dépasser :max caractères.',
            
            'photo.image' => 'Le fichier doit être une image.',
            'photo.mimes' => 'L\'image doit être au format JPEG, JPG, PNG, GIF ou WebP.',
            'photo.max' => 'La taille de l\'image ne peut pas dépasser :max ko (2MB).',
            'photo.dimensions' => 'L\'image doit faire au minimum 100x100 pixels et au maximum 2000x2000 pixels.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Nettoyer et normaliser les données
        $this->merge([
            'nom' => $this->cleanString($this->nom),
            'prenom' => $this->cleanString($this->prenom),
            'email' => $this->cleanEmail($this->email),
            'institution' => $this->cleanString($this->institution),
            'biographie' => $this->cleanText($this->biographie),
        ]);
    }

    /**
     * Clean string input (remove extra spaces, trim)
     */
    private function cleanString(?string $value): ?string
    {
        if (!$value) return null;
        
        return trim(preg_replace('/\s+/', ' ', $value));
    }

    /**
     * Clean email input
     */
    private function cleanEmail(?string $value): ?string
    {
        if (!$value) return null;
        
        return strtolower(trim($value));
    }

    /**
     * Clean text input (preserve line breaks but normalize spaces)
     */
    private function cleanText(?string $value): ?string
    {
        if (!$value) return null;
        
        // Préserver les sauts de ligne mais normaliser les espaces sur chaque ligne
        $lines = explode("\n", $value);
        $cleanedLines = array_map(function($line) {
            return trim(preg_replace('/\s+/', ' ', $line));
        }, $lines);
        
        return implode("\n", $cleanedLines);
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validation personnalisée pour éviter les noms identiques
            if ($this->nom && $this->prenom) {
                $auteurId = $this->route('auteur') ? $this->route('auteur')->id : null;
                
                $existingAuteur = \App\Models\Auteur::where('nom', $this->nom)
                    ->where('prenom', $this->prenom)
                    ->when($auteurId, function($query) use ($auteurId) {
                        return $query->where('id', '!=', $auteurId);
                    })
                    ->first();
                
                if ($existingAuteur) {
                    $validator->errors()->add('nom', 'Un auteur avec ce nom et prénom existe déjà.');
                }
            }

            // Validation pour éviter les emails temporaires ou suspects
            if ($this->email) {
                $suspiciousDomains = [
                    '10minutemail.com', 'tempmail.org', 'guerrillamail.com',
                    'mailinator.com', 'temp-mail.org', 'throaway.email'
                ];
                
                $emailDomain = substr(strrchr($this->email, '@'), 1);
                if (in_array($emailDomain, $suspiciousDomains)) {
                    $validator->errors()->add('email', 'Les adresses emails temporaires ne sont pas autorisées.');
                }
            }
        });
    }

    /**
     * Handle a failed authorization attempt.
     */
    protected function failedAuthorization(): void
    {
        abort(403, 'Vous n\'avez pas les permissions nécessaires pour effectuer cette action.');
    }

    /**
     * Get the validated data from the request with additional processing.
     */
    public function validatedWithProcessing(): array
    {
        $validated = $this->validated();
        
        // Traitement spécial pour la photo si elle est présente
        if ($this->hasFile('photo')) {
            // La gestion de l'upload sera faite dans le contrôleur
            $validated['photo'] = $this->file('photo');
        }
        
        // Convertir les valeurs vides en null
        foreach ($validated as $key => $value) {
            if ($value === '') {
                $validated[$key] = null;
            }
        }
        
        return $validated;
    }
}
