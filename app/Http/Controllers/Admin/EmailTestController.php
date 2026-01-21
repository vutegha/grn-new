<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class EmailTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:viewAny,App\Models\User')->only(['index']); // Seuls les admins peuvent tester les emails
    }

    /**
     * Afficher la page de test d'email
     */
    public function index()
    {
        $config = [
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'encryption' => config('mail.mailers.smtp.encryption'),
            'username' => config('mail.mailers.smtp.username'),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
        ];

        return view('admin.email-test.index', compact('config'));
    }

    /**
     * Envoyer un email de test
     */
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            Mail::raw($request->message, function (Message $message) use ($request) {
                $message->to($request->email)
                        ->subject($request->subject)
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

            return response()->json([
                'success' => true,
                'message' => 'Email envoyé avec succès à ' . $request->email
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tester la connexion SMTP
     */
    public function testConnection()
    {
        try {
            // Test simple de la configuration
            $transport = Mail::getSwiftMailer()->getTransport();
            
            return response()->json([
                'success' => true,
                'message' => 'Configuration SMTP valide',
                'config' => [
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'encryption' => config('mail.mailers.smtp.encryption'),
                    'username' => config('mail.mailers.smtp.username'),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de configuration : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tester la configuration email
     */
    public function testConfig()
    {
        try {
            $config = config('mail');
            $errors = [];
            
            // Vérifications de base
            if (empty($config['from']['address'])) {
                $errors[] = 'MAIL_FROM_ADDRESS non configuré';
            }
            
            if (empty($config['from']['name'])) {
                $errors[] = 'MAIL_FROM_NAME non configuré';
            }
            
            if (!empty($errors)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Configuration incomplète : ' . implode(', ', $errors)
                ]);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Configuration email valide',
                'config' => [
                    'from' => $config['from']['address'],
                    'name' => $config['from']['name'],
                    'mailer' => $config['default']
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors du test : ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Tester l'envoi d'email de réinitialisation
     */
    public function testPasswordReset(Request $request)
    {
        $email = $request->get('email', 'admin@example.com');
        
        try {
            // Vérifier si un utilisateur avec cet email existe
            $user = \App\Models\User::where('email', $email)->first();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Aucun utilisateur trouvé avec cet email',
                    'email' => $email
                ]);
            }
            
            // Tenter d'envoyer un email de réinitialisation
            $status = \Illuminate\Support\Facades\Password::sendResetLink(
                ['email' => $email]
            );
            
            return response()->json([
                'status' => $status === \Illuminate\Auth\Passwords\PasswordBroker::RESET_LINK_SENT ? 'success' : 'error',
                'message' => $status,
                'user_found' => true,
                'email_tested' => $email
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de l\'envoi',
                'error' => $e->getMessage(),
                'email' => $email
            ]);
        }
    }
}
