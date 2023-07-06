<?php

namespace App\Chat\Observers;

use App\Chat\Models\Workspace;
use App\Chat\Events\DirectMessageCreated;

class WorkspaceObserver
{
    /**
     * Handle the workspace "created" event.
     *
     * @param  \App\Workspace $workspace
     * @return void
     */
    public function created(Workspace $workspace)
    {
        // event(new DirectMessageCreated($workspace));
        // dd($workspace);
    }

    /**
     * Handle the workspace "updated" event.
     *
     * @param  \App\Workspace $workspace
     * @return void
     */
    public function updated(Workspace $workspace)
    {
    }

    /**
     * Handle the workspace "deleted" event.
     *
     * @param  \App\Workspace $workspace
     * @return void
     */
    public function deleted(Workspace $workspace)
    {
    }

    /**
     * Handle the workspace "restored" event.
     *
     * @param  \App\Workspace $workspace
     * @return void
     */
    public function restored(Workspace $workspace)
    {
    }

    /**
     * Handle the workspace "force deleted" event.
     *
     * @param  \App\Workspace $workspace
     * @return void
     */
    public function forceDeleted(Workspace $workspace)
    {
    }
}
