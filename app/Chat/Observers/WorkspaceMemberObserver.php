<?php

namespace App\Chat\Observers;

use App\Chat\Models\Workspace;
use App\Chat\Models\WorkspaceMember;
use App\Chat\Events\DirectMessageCreated;

class WorkspaceMemberObserver
{
    /**
     * Handle the workspace "created" event.
     *
     * @param  \App\WorkspaceMember $workspaceMember
     * @return void
     */
    public function created(WorkspaceMember $workspaceMember)
    {
        // event(new DirectMessageCreated($workspace));
        // dd($workspace);
    }

    /**
     * Handle the workspace "updated" event.
     *
     * @param  \App\WorkspaceMember $workspaceMember
     * @return void
     */
    public function updated(WorkspaceMember $workspaceMember)
    {
    }

    /**
     * Handle the workspace "deleted" event.
     *
     * @param  \App\WorkspaceMember $workspaceMember
     * @return void
     */
    public function deleted(WorkspaceMember $workspaceMember)
    {
    }

    /**
     * Handle the workspace "restored" event.
     *
     * @param  \App\WorkspaceMember $workspaceMember
     * @return void
     */
    public function restored(WorkspaceMember $workspaceMember)
    {
    }

    /**
     * Handle the workspace "force deleted" event.
     *
     * @param  \App\WorkspaceMember $workspaceMember
     * @return void
     */
    public function forceDeleted(WorkspaceMember $workspaceMember)
    {
    }
}
