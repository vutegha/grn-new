<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class ContentPublished extends Notification implements ShouldQueue
{
    use Queueable;

    protected $content;
    protected $contentType;

    /**
     * Create a new notification instance.
     */
    public function __construct(Model $content)
    {
        $this->content = $content;
        $this->contentType = $this->getContentType($content);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $title = $this->getContentTitle();
        $url = $this->getContentUrl();

        return (new MailMessage)
                    ->subject('Nouveau contenu publié sur IRI-UCBC')
                    ->greeting('Bonjour ' . $notifiable->name)
                    ->line("Un nouveau {$this->contentType} vient d'être publié sur le site.")
                    ->line("**Titre :** {$title}")
                    ->line("**Type :** " . ucfirst($this->contentType))
                    ->line("**Publié le :** " . $this->content->published_at->format('d/m/Y à H:i'))
                    ->action('Voir le contenu', $url)
                    ->line('Merci de votre attention.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'content_type' => $this->contentType,
            'content_id' => $this->content->id,
            'title' => $this->getContentTitle(),
            'url' => $this->getContentUrl(),
            'published_at' => $this->content->published_at,
        ];
    }

    /**
     * Déterminer le type de contenu
     */
    protected function getContentType(Model $content): string
    {
        $class = class_basename($content);
        
        return match($class) {
            'Actualite' => 'actualité',
            'Publication' => 'publication',
            'Projet' => 'projet',
            'Service' => 'service',
            'Rapport' => 'rapport',
            default => 'contenu'
        };
    }

    /**
     * Obtenir le titre du contenu
     */
    protected function getContentTitle(): string
    {
        return $this->content->titre ?? $this->content->nom ?? $this->content->title ?? 'Sans titre';
    }

    /**
     * Obtenir l'URL du contenu
     */
    protected function getContentUrl(): string
    {
        $baseUrl = config('app.url');
        
        return match($this->contentType) {
            'actualité' => "{$baseUrl}/actualite/{$this->content->slug}",
            'publication' => "{$baseUrl}/publications/{$this->content->slug}",
            'projet' => "{$baseUrl}/projet/{$this->content->slug}",
            'service' => "{$baseUrl}/service/{$this->content->slug}",
            'rapport' => "{$baseUrl}/rapports/{$this->content->id}",
            default => $baseUrl
        };
    }
}