<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithProperties;

class SimpleArrayExport implements FromArray, WithTitle, WithProperties
{
    protected $data;
    protected $title;
    protected $company;

    public function __construct($data, $title, $company)
    {
        $this->data    = $data;
        $this->title   = $title;
        $this->company = $company;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function title(): string
    {
        return 'sheet1';
    }

    public function properties(): array
    {
        return [
            'title'       => $this->title,
            'creator'     => 'NumakPro ERP',
            'company'     => $this->company,
            'description' => $this->title,
        ];
    }
}
