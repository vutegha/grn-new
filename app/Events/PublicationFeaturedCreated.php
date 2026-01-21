<?php

namespace App\Events;

use App\Models\Publication;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PublicationFeaturedCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $publication;

    /**
     * Create a new event instance.
     */
    public function __construct(Publication $publication)
    {
        $this->publication = $publication;
    }
}
