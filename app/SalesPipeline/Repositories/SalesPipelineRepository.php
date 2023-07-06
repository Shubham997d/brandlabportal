<?php

namespace App\SalesPipeline\Repositories;

use App\SalesPipeline\Models\SalesPipeline;

class SalesPipelineRepository
{
    protected $model;

    public function __construct(SalesPipeline $salespipelines)
    {
        $this->model = $salespipelines;
    }

    public function getAllSalesPipelines()
    {
        return [];
        //return $this->model->get(['name', 'description']);
    }

    public function getLatestSalesPipelines($total)
    {
        return $this->model->orderBy('created_at', 'desc')->take($total)->get();
    }

    public function updateSalesPipelines($data)
    {
        // return $this->model->where('id', $data['id'])->update([
        //     'name'         => $data['name'] ?? null,
        //     'description'  => $data['description'] ?? null,
        //     'deadline'     => $data['deadline'] ?? null,
        //     'cost'         => $data['cost'] ?? null,
        //     'office_id'    => $data['office_id'] ?? null,
        //     'team_id'      => $data['team_id'] ?? null,
        //     'status'       => $data['status'] ?? config('constants.project.status.in_progress'), // Set Default To In Progress
        // ]);
    }
}
