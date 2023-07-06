<?php

namespace App\Project\Repositories;

use App\Project\Models\Asset;
use App\Base\Models\User;
use App\Project\Models\ProjectUser;
use App\Base\Repositories\UserRepository;
use App\Project\Repositories\ProjectRepository;
use App\Project\Models\ProjectUsersDuration;


class AssetRepository
{
    protected $model;

    public $assets,$params;
    

    public function __construct(Asset $asset)
    {
        $this->model = $asset;
    }

    public function getAssets($params)
    {          
        $this->params = $params;
        $this->queryForAllAssets($this->params);
        $this->getAssetsNotAvailableInData($this->params);
        return $this->assets;
    }

    public function create($params)    {          
           return $this->model->create([
                  'created_by' =>  auth()->user()->id, 
                  'project_id' =>  $params['project_id'],
                  'status_id' =>  1  // default is active
           ]);
    }

    public function update($params)    {          
        $this->model = $this->model->where('id',$params['asset']['id'])->where('project_id',$params['project_id'])->first();
        $this->model->update([
            'asset_type' => $params['asset']['asset_type'] ?? null,
            'asset_cost' => $params['asset']['asset_cost'] ?? null,
            'notes' => $params['notes'] ?? null,
        ]);
        return $this->model->refresh();
    }

    public function delete($params)    {          
        return $this->model->where('id',$params['id'])->delete();
    }

    public function queryForAllAssets(){
            $this->assets = $this->model->where('assets.project_id','=',$this->params['project_id'])                     
                     ->get(['assets.id','asset_type','asset_cost','assets.created_at']);
    }                     

    public function getAssetsNotAvailableInData($params){            
            $this->assets = (count($this->assets) === 0) ?  [] : $this->assets;                        
    }    

    public function getAllProjectUserNames(){
        return array_column(ProjectUser::join('users','users.id','project_user.user_id')->where('project_id',$this->params['project_id'])->get(['username'])->toArray(),'username');
    }    

    public function getTotalAssetAmount($project_id){        
        return $this->model->where('project_id',$project_id)->sum('asset_cost');
    }


}






// uncomment functions if assets needs to be grouped by users

// public function addUserNamesInCollection(){
//     $diff_usernames = array_diff($this->getAllProjectUserNames(),$this->assets->keys()->toArray());
//     foreach ($diff_usernames as $username) {
//         $user = User::where('username',$username)->first(['id as assigned_to','colour']);
//         $this->assets = $this->assets->put($username, Asset::hydrate([['assigned_to' => $user->assigned_to,'colour' => $user->colour, 'assets' => []]]));            
//     }        
// }

// public function getFormattedCollection(){
//     $this->addUserNamesInCollection();
//     $data =  array();        
//     $assets = $this->assets->map(function ($group,$username) use ($data)  {                      
//             $data['assigned_to'] = $group->first()->assigned_to;
//             $data['colour'] =  $group->first()->colour;
//             $data['assets'] = isset($group->first()->assets) ? $group->first()->assets : $group->all();
//             return $data;
//     });
//     return $assets;
// }

//     public function getFormattedCollection(){
//         $this->addUserNamesInCollection();
//         $data =  array();        
//         $assets = $this->assets->map(function ($group,$username) use ($data)  {                      
//                 $data['assigned_to'] = $group->first()->assigned_to;
//                 $data['colour'] =  $group->first()->colour;
//                 $data['assets'] = isset($group->first()->assets) ? $group->first()->assets : $group->all();
//                 return $data;
//         });
//         return $assets;
// }