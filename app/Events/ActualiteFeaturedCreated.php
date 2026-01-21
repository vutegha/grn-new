<?php

namespace App\Events;

use App\Models\Actualite;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActualiteFeaturedCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $actualite;

    /**
     * Create a new event instance.
     */
    public function __construct(Actualite $actualite)
    {
        $this->actualite = $actualite;
    }
}
