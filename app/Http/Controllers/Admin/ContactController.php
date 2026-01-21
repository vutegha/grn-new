<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        $this->authorize('viewAny', Contact::class);
$query = Contact::query();

        // Filtrer par statut si spécifié
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Recherche par nom, email ou sujet
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('sujet', 'like', "%{$search}%");
            });
        }

        $contacts = $query->latest()->paginate(20);

        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        
        $this->authorize('view', $contact);
        
        // Marquer comme lu si c'est nouveau
        if ($contact->statut === 'nouveau') {
            $contact->marquerCommeLu();
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        
        $this->authorize('update', $contact);
        
        $request->validate([
            'statut' => 'required|in:nouveau,lu,traite,ferme',
            'reponse' => 'nullable|string|max:2000'
        ]);

        $contact->update([
            'statut' => $request->statut,
            'reponse' => $request->reponse,
            'traite_a' => $request->statut === 'traite' ? now() : $contact->traite_a
        ]);

        return redirect()->route('admin.contacts.show', $contact)
                         ->with('success', 'Le contact a été mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        
        $this->authorize('delete', $contact);
        
        $contact->delete();

        return redirect()->route('admin.contacts.index')
                         ->with('success', 'Le contact a été supprimé avec succès.');
    }
}
