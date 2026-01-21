<?php

namespace App\Events;

use App\Models\Rapport;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RapportCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $rapport;

    /**
     * Create a new event instance.
     */
    public function __construct(Rapport $rapport)
    {
        $this->rapport = $rapport;
    }
}
