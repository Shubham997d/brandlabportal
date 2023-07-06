<?php

namespace App\Base\Repositories;

use App\Base\Models\User;
use App\Base\Models\Notification;
use DB;

class ActivityLogRepository
{
    protected $model;

    public function __construct(Notification $notification)
    {
            $this->model = $notification;
    }    

    public function getActivityLog($request){
        return $this->filterQuery($request);
    }

    public function filterQuery($request){
            
            $data = $request->all();            
            $this->model = isset($data['dateRange']) ?  $this->model->whereDate('created_at', '>=', $data['dateRange']['startDate'])->whereDate('created_at', '<=', $data['dateRange']['endDate']) :  $this->model;
            $this->model = (isset($data['user']) && isset($data['user']['value'])) ?  $this->model->where('notifiable_id', $data['user']['value']) :  $this->model;
            return $this->model->where('visible_to_admin',true)->orderBy("created_at", "desc")->paginate(config('constants.logs.activity.pagination'));
    }

    
}
