<?php

namespace App\TaskManager\Repositories;

use App\TaskManager\Models\Task;
use App\Base\Models\User;
use App\Base\Repositories\UserRepository;
use App\Project\Models\Project;
use App\Project\Repositories\ProjectRepository;
use App\Project\Models\ProjectUsersDuration;


class TaskRepository
{
    protected $model;

    public function __construct(Task $task)
    {
        $this->model = $task;
    }

    public function getAllTaskWithAssignee($type, $entityId)
    {   
        // dd(request('cycle_id'));
        $query = request('cycle_id') ? $this->model->where('cycle_id', request('cycle_id')) : $this->model->whereNull('cycle_id');
        $query = request('status_id') ? $query->where('status_id', request('status_id')) : $query;
        $query = request('assigned_to') ? $query->where('assigned_to', request('assigned_to')) : $query;
        $query = request('due_on') ? $query->where('due_on', request('due_on')) : $query;
        $query = request('tag_id') ? $query->whereHas('tags', function ($query) {
            $query->where('id', request('tag_id'));
        })->with('tags') : $query;

        return $query->where(['taskable_type' => $type, 'taskable_id' => $entityId])
                     ->with('user:id,avatar,username,name')
                     ->with('creator:id,avatar')
                     ->with('related:id,name')
                     ->with('status:id,name,color')
                     ->with('tags:id,label')
                     ->get(['id', 'name', 'notes', 'assigned_to', 'due_on','task_duration','related_to', 'completed', 'parent_id', 'status_id', 'created_by', 'created_at']);
    }


    public function getAssigneeOfTasks($type, $entityId)
    {   
        // dd(request('cycle_id'));
        $query = request('cycle_id') ? $this->model->where('cycle_id', request('cycle_id')) : $this->model->whereNull('cycle_id');
        $query = request('status_id') ? $query->where('status_id', request('status_id')) : $query;
        $query = request('assigned_to') ? $query->where('assigned_to', request('assigned_to')) : $query;
        $query = request('due_on') ? $query->where('due_on', request('due_on')) : $query;
        $query = request('tag_id') ? $query->whereHas('tags', function ($query) {
            $query->where('id', request('tag_id'));
        })->with('tags') : $query;

        return $query->where(['taskable_type' => $type, 'taskable_id' => $entityId])->groupBy('assigned_to')->get(['assigned_to'])->toArray();

                
    }


    public function getTasksOfParticularAssigne($type, $entityId, $assigned_to)
    {   
        // dd(request('cycle_id'));
        $query = request('cycle_id') ? $this->model->where('cycle_id', request('cycle_id')) : $this->model->whereNull('cycle_id');
        $query = request('status_id') ? $query->where('status_id', request('status_id')) : $query;        
        $query = request('due_on') ? $query->where('due_on', request('due_on')) : $query;  

        return $query->where(['taskable_type' => $type, 'taskable_id' => $entityId, 'assigned_to' => $assigned_to])
                     ->with('user:id,avatar,username,name')
                     ->with('creator:id,avatar')
                     ->with('status:id,name,color')
                     ->get(['id', 'name', 'assigned_to', 'due_on','task_duration','related_to', 'completed','status_id']);
                
    }


    public function getAllTaskWithAssigneeAndDuration($type, $entityId)
    {

        $assignes = $this->getAssigneeOfTasks($type, $entityId);

        if (count($assignes) > 0 && !empty($assignes)) {
            
            $userRepository = new UserRepository(new User);
            $final_collection = array();
            foreach ($assignes as $assigne) {
                $user = $userRepository->getUserNameAndColourById($assigne['assigned_to']);

                $username = $user['username'];
                $final_collection[$username]['tasks'] = $this->getTasksOfParticularAssigne($type, $entityId, $assigne['assigned_to']);
                $final_collection[$username]['duration'] = $this->getTaskDurationForAssigne($final_collection[$username]['tasks'],$assigne['assigned_to'],$entityId);
                $final_collection[$username]['assigned_to'] = $assigne['assigned_to'];
                $final_collection[$username]['colour'] = empty($user['colour']) ? '#667eea' : $user['colour'];
            }

        }
        else{
            $final_collection = array();   
        }
        $final_collection = $this->comparefinalCollection($entityId,$final_collection);            /*Sort Array Before*/

        krsort($final_collection);       

        return $final_collection;

    }


    public function getTaskDurationForAssigne($data,$assigned_to,$entityId){
        $projectUsersDuration = new ProjectUsersDuration;
            $has_duration = $projectUsersDuration->where(['project_id' => $entityId, 'user_id' => $assigned_to])->first();
            $total_duration = 0;
            foreach ($data as $value) {
                if (!empty($value['task_duration']) && $value['task_duration'] != null) {                    
                    $total_duration += $this->convertTimeArrayToSeconds($value['task_duration']);                   
                }
            }
            if ($has_duration) {                        
                $projectUsersDuration->where(['project_id' => $entityId, 'user_id' => $assigned_to])->update(['duration' => $total_duration]);

            }else{
                $projectUsersDuration->project_id = $entityId;
                $projectUsersDuration->user_id = $assigned_to;
                $projectUsersDuration->duration = $total_duration;
                $projectUsersDuration->save();  
            }   
           $newformat =  secondsToTime($total_duration,false);
           return $newformat;
    }


    public function comparefinalCollection($entityId,$final_collection){
        $project = new Project();                
        $members = $project->where(['id' => $entityId])->with('members:user_id,username,avatar,name,colour')->get()->pluck('members')->toArray();

        if (count($final_collection) > 0 && !empty($final_collection)) {
            foreach ($members[0] as $member) {
                    $assigned_username = $this->arrayValueExists($final_collection,$member['username']);
                    if ($assigned_username) {                    
                        $final_collection[$assigned_username]['assigned_to'] = $member['user_id'];
                        $final_collection[$assigned_username]['colour'] = $member['colour'];
                        $final_collection[$assigned_username]['duration'] = 0;                    
                        $projectUsersDuration = new ProjectUsersDuration;
                        $projectUsersDuration->where(['project_id' => $entityId, 'user_id' => $member['user_id']])->update(['duration' => 0]); 
                    }
            }            
        }
        else{

            foreach ($members[0] as $member) {
                $final_collection[$member['username']]['assigned_to'] = $member['user_id'];
                $final_collection[$member['username']]['colour'] = $member['colour'];
                $final_collection[$member['username']]['duration'] = 0;                    
                $projectUsersDuration = new ProjectUsersDuration;
                $projectUsersDuration->where(['project_id' => $entityId, 'user_id' => $member['user_id']])->update(['duration' => 0]);                     
            }        

        }
        
        
        return $final_collection;
        
    }

    public function arrayValueExists($data,$assigned_username){

        // Username is always unique    

        if (count($data) > 0 && !empty($data)) { 
            $has_username = false;           
            foreach ($data as $username => $value) {
                
               if ($username == $assigned_username ) {
                   $has_username = true;
                   break;
               }else{                    
                   $has_username = false;
               }
            }            
            if ($has_username == false) {
                return $assigned_username;
            }else{
                return false;
            }

        }
    }




    public function convertTimeArrayToSeconds($data){
            
            $seconds = 0;
            
            foreach ($data as $unit => $value) {
                if (!empty($value) && $value > 0 && $unit == 'HH' ){
                    $seconds += $value * 60 * 60;
                }
                if (!empty($value) && $value > 0 && $unit == 'mm') {
                    $seconds += $value * 60;        
                }
            }

            return intval($seconds);            
    }   

    public function getTotalDurationWithAssignee($type, $entityId)
    {   
        
        $query = request('cycle_id') ? $this->model->where('cycle_id', request('cycle_id')) : $this->model->whereNull('cycle_id');
        $query = request('status_id') ? $query->where('status_id', request('status_id')) : $query;
        $query = request('assigned_to') ? $query->where('assigned_to', request('assigned_to')) : $query;
        $query = request('due_on') ? $query->where('due_on', request('due_on')) : $query;
        $query = request('tag_id') ? $query->whereHas('tags', function ($query) {
            $query->where('id', request('tag_id'));
        })->with('tags') : $query;

        return $query->where(['taskable_type' => $type, 'taskable_id' => $entityId])->get(['id', 'assigned_to','task_duration']);
    }



    /**
     * @param  array                 $data
     * @return \App\Base\Models\Task
     */
    public function create(array $data): Task
    {
        return $this->model->create([
            'name'              => $data['name'] ?? null ,
            'assigned_to'       => $data['assigned_to'],
            'created_by'        => auth()->user()->id,
            'notes'             => $data['notes'] ?? null,
            'due_on'            => $data['due_on'] ?? null,
            'related_to'        => $data['related_to'] ?? null,
            'taskable_type'     => $data['group_type'],
            'taskable_id'       => $data['group_id'],
            'status_id'         => $data['status_id'] ?? 1,
            'cycle_id'          => null,
            'task_duration'     => $data['task_duration'] ?? null,
        ]);
    }

    public function userCurrentlyWorkingOn(int $userId)
    {
        return $this->model->where('assigned_to', $userId)
                           ->whereHas('status', function ($query) {
                               $query->where('name', 'In Progress');
                           })
                           ->with('taskable:id,name')
                           ->with('steps')
                           ->orderBy('due_on')
                           ->get();
    }
}
