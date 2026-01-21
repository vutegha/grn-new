<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactInfoController extends Controller
{
    /**
     * Afficher la liste des informations de contact
     */
    public function index()
    {
        $contactInfos = ContactInfo::ordered()->get()->groupBy('type');
        
        $breadcrumbs = [
            ['name' => 'Tableau de bord', 'url' => route('admin.dashboard')],
            ['name' => 'Informations de contact', 'url' => null]
        ];

        return view('admin.contact-info.index', compact('contactInfos', 'breadcrumbs'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $breadcrumbs = [
            ['name' => 'Tableau de bord', 'url' => route('admin.dashboard')],
            ['name' => 'Informations de contact', 'url' => route('admin.contact-info.index')],
            ['name' => 'Nouvelle information', 'url' => null]
        ];

        return view('admin.contact-info.create', compact('breadcrumbs'));
    }

    /**
     * Enregistrer une nouvelle information de contact
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:bureau_principal,bureau_regional,point_focal,autre',
            'nom' => 'required|string|max:255',
            'titre' => 'nullable|string|max:255',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'pays' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:255',
            'telephone_secondaire' => 'nullable|string|max:255',
            'responsable_nom' => 'nullable|string|max:255',
            'responsable_fonction' => 'nullable|string|max:255',
            'responsable_email' => 'nullable|email|max:255',
            'responsable_telephone' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'horaires' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'ordre' => 'nullable|integer|min:0',
            'actif' => 'boolean'
        ], [
            'type.required' => 'Le type est obligatoire',
            'nom.required' => 'Le nom est obligatoire',
            'pays.required' => 'Le pays est obligatoire',
            'email.email' => 'L\'email doit être valide',
            'responsable_email.email' => 'L\'email du responsable doit être valide',
            'photo.image' => 'Le fichier doit être une image',
            'photo.mimes' => 'L\'image doit être au format: jpeg, png, jpg, gif',
            'photo.max' => 'L\'image ne doit pas dépasser 2MB',
            'latitude.between' => 'La latitude doit être entre -90 et 90',
            'longitude.between' => 'La longitude doit être entre -180 et 180'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->except('photo');
            
            // Gérer l'upload de la photo
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $path = $photo->storeAs('contact_infos', $filename, 'public');
                $data['photo'] = $path;
            }

            ContactInfo::create($data);

            return redirect()->route('admin.contact-info.index')
                ->with('success', 'Information de contact créée avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur création contact info: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la création.')
                ->withInput();
        }
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(ContactInfo $contactInfo)
    {
        $breadcrumbs = [
            ['name' => 'Tableau de bord', 'url' => route('admin.dashboard')],
            ['name' => 'Informations de contact', 'url' => route('admin.contact-info.index')],
            ['name' => 'Modifier', 'url' => null]
        ];

        return view('admin.contact-info.edit', compact('contactInfo', 'breadcrumbs'));
    }

    /**
     * Mettre à jour une information de contact
     */
    public function update(Request $request, ContactInfo $contactInfo)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:bureau_principal,bureau_regional,point_focal,autre',
            'nom' => 'required|string|max:255',
            'titre' => 'nullable|string|max:255',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'pays' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:255',
            'telephone_secondaire' => 'nullable|string|max:255',
            'responsable_nom' => 'nullable|string|max:255',
            'responsable_fonction' => 'nullable|string|max:255',
            'responsable_email' => 'nullable|email|max:255',
            'responsable_telephone' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'horaires' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'ordre' => 'nullable|integer|min:0',
            'actif' => 'boolean'
        ], [
            'type.required' => 'Le type est obligatoire',
            'nom.required' => 'Le nom est obligatoire',
            'pays.required' => 'Le pays est obligatoire',
            'email.email' => 'L\'email doit être valide',
            'responsable_email.email' => 'L\'email du responsable doit être valide',
            'photo.image' => 'Le fichier doit être une image',
            'photo.mimes' => 'L\'image doit être au format: jpeg, png, jpg, gif',
            'photo.max' => 'L\'image ne doit pas dépasser 2MB',
            'latitude.between' => 'La latitude doit être entre -90 et 90',
            'longitude.between' => 'La longitude doit être entre -180 et 180'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->except('photo');
            
            // Gérer l'upload de la photo
            if ($request->hasFile('photo')) {
                // Supprimer l'ancienne photo si elle existe
                if ($contactInfo->photo && \Storage::disk('public')->exists($contactInfo->photo)) {
                    \Storage::disk('public')->delete($contactInfo->photo);
                }
                
                $photo = $request->file('photo');
                $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $path = $photo->storeAs('contact_infos', $filename, 'public');
                $data['photo'] = $path;
            }

            $contactInfo->update($data);

            return redirect()->route('admin.contact-info.index')
                ->with('success', 'Information de contact mise à jour avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur mise à jour contact info: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour.')
                ->withInput();
        }
    }

    /**
     * Supprimer une information de contact
     */
    public function destroy(ContactInfo $contactInfo)
    {
        try {
            $contactInfo->delete();

            return redirect()->route('admin.contact-info.index')
                ->with('success', 'Information de contact supprimée avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur suppression contact info: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la suppression.');
        }
    }

    /**
     * Activer/Désactiver une information de contact
     */
    public function toggleActive(ContactInfo $contactInfo)
    {
        try {
            $contactInfo->update(['actif' => !$contactInfo->actif]);

            return response()->json([
                'success' => true,
                'message' => $contactInfo->actif ? 'Information activée' : 'Information désactivée',
                'actif' => $contactInfo->actif
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification'
            ], 500);
        }
    }
}
