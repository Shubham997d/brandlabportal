<?php

namespace App\Base\Providers;

use App\Team\Models\Team;
use App\Office\Models\Office;
use App\Observers\TeamObserver;
use App\Project\Models\Project;
use App\TaskManager\Models\Task;
use App\Observers\OfficeObserver;
use App\Chat\Models\DirectMessage;
use App\Discussion\Models\Discussion;
use App\Project\Observers\ProjectObserver;
use App\TaskManager\Observers\TaskObserver;
use App\SalesPipeline\Observers\DealOrganisationObserver;
use App\SalesPipeline\Models\DealOrganisation;
use App\Chat\Observers\DirectMessageObserver;
use App\Discussion\Observers\DiscussionObserver;
use App\SalesPipeline\Models\Deals;
use App\SalesPipeline\Observers\DealsObserver;
use App\Chat\Models\Workspace;
use App\Chat\Observers\WorkspaceObserver;
use App\Chat\Models\WorkspaceMember;
use App\Chat\Observers\WorkspaceMemberObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Task::observe(TaskObserver::class);
        // DealOrganisation::observe(DealOrganisationObserver::class);
        Deals::observe(DealsObserver::class);
        Workspace::observe(WorkspaceObserver::class);
        WorkspaceMember::observe(WorkspaceMemberObserver::class);
        DirectMessage::observe(DirectMessageObserver::class);
        // Project::observe(ProjectObserver::class);
        // Office::observe(OfficeObserver::class);
        // Team::observe(TeamObserver::class);
    }
}
