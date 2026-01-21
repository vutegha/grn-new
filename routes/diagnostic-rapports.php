<?php
// Page de diagnostic accessible via /diagnostic-rapports

use App\Models\Rapport;
use Illuminate\Support\Facades\Route;

Route::get('/diagnostic-rapports', function () {
    $queryRapports = Rapport::published()
        ->with('categorie')
        ->whereDoesntHave('actualites');
    
    $rapports = $queryRapports->get();
    
    $html = '<!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Diagnostic Rapports</title>
        <style>
            body { font-family: Arial; padding: 20px; }
            h1 { color: #2d5a3f; }
            .success { color: green; font-weight: bold; }
            .error { color: red; font-weight: bold; }
            table { border-collapse: collapse; width: 100%; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #2d5a3f; color: white; }
            .linked { background-color: #ffe6e6; }
            .not-linked { background-color: #e6ffe6; }
        </style>
    </head>
    <body>
        <h1>🔍 Diagnostic Filtre Rapports</h1>
        
        <h2>Statistiques</h2>
        <ul>
            <li>Total rapports: <strong>' . Rapport::count() . '</strong></li>
            <li>Rapports publiés: <strong>' . Rapport::published()->count() . '</strong></li>
            <li>Rapports avec actualités: <strong class="error">' . Rapport::has("actualites")->count() . '</strong></li>
            <li>Rapports SANS actualités: <strong class="success">' . Rapport::doesntHave("actualites")->count() . '</strong></li>
        </ul>
        
        <h2>✅ Rapports retournés par la requête filtrée (devrait être ' . $rapports->count() . ')</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Publié</th>
                    <th>A des actualités</th>
                    <th>Catégorie</th>
                </tr>
            </thead>
            <tbody>';
    
    foreach ($rapports as $rapport) {
        $hasActualites = $rapport->hasActualites();
        $rowClass = $hasActualites ? 'linked' : 'not-linked';
        $html .= '<tr class="' . $rowClass . '">
                    <td>' . $rapport->id . '</td>
                    <td>' . htmlspecialchars($rapport->titre) . '</td>
                    <td>' . ($rapport->is_published ? '✅ Oui' : '❌ Non') . '</td>
                    <td>' . ($hasActualites ? '❌ OUI (ERREUR!)' : '✅ Non') . '</td>
                    <td>' . ($rapport->categorie ? $rapport->categorie->nom : 'Aucune') . '</td>
                  </tr>';
    }
    
    $html .= '</tbody></table>
        
        <h2>❌ Rapports qui DEVRAIENT ETRE EXCLUS (liés à des actualités)</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Actualités liées</th>
                </tr>
            </thead>
            <tbody>';
    
    $rapportsLies = Rapport::has('actualites')->get();
    foreach ($rapportsLies as $rapport) {
        $actualites = $rapport->actualites->pluck('titre')->join(', ');
        $html .= '<tr>
                    <td>' . $rapport->id . '</td>
                    <td>' . htmlspecialchars($rapport->titre) . '</td>
                    <td>' . htmlspecialchars($actualites) . '</td>
                  </tr>';
    }
    
    $html .= '</tbody></table>
        
        <p style="margin-top: 30px; padding: 15px; background: #f0f9f4; border-left: 4px solid #2d5a3f;">
            <strong>✅ Conclusion:</strong> La requête filtrée retourne <strong>' . $rapports->count() . ' rapports</strong>. 
            Si ce nombre correspond aux "Rapports SANS actualités", le filtre fonctionne correctement !
        </p>
        
        <p><a href="/publications" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background: #2d5a3f; color: white; text-decoration: none; border-radius: 5px;">Voir la page Publications</a></p>
    </body>
    </html>';
    
    return $html;
});
