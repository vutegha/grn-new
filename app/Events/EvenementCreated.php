<?php

namespace App\Events;

use App\Models\Evenement;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EvenementFeaturedCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $evenement;

    /**
     * Create a new event instance.
     */
    public function __construct(Evenement $evenement)
    {
        $this->evenement = $evenement;
    }
}
