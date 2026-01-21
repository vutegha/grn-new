<?php

namespace App\Events;

use App\Models\Projet;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $projet;

    /**
     * Create a new event instance.
     */
    public function __construct(Projet $projet)
    {
        $this->projet = $projet;
    }
}
