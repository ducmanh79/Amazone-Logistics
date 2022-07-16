<?php

namespace App\Listeners;

use App\Events\ChangeDataProcessed;
use App\Models\History;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ChangeDataProcessing
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  ChangeDataProcessed  $event
     * @return void
     */
    public function handle(ChangeDataProcessed $event)
    {
        History::insert([
            'content' => $event->message,
            'user_id' => auth('api')->user()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
