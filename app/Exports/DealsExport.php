<?php

namespace App\Exports;

use App\Base\Models\User;
use App\SalesPipeline\Models\Deals;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DealsExport implements FromArray,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(array $data)
    {
        $this->data = $data;                
    }

    public function array(): array
    {
        return $this->data['items'];
    }

    public function headings(): array
    {
        return [ $this->data['headings'] ];
    }
}
