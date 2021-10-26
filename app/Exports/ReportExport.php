<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportExport implements FromCollection, WithHeadings
{
    private $collection;
    private $headings;
    /**
     *@return \Illuminate\Support\Collection
     */

    public function __construct(Collection $collection, $headings)
    {
        $this->collection = $collection;
        $this->headings = $headings;
    }

    public function collection()
    {
        return $this->collection;
    }

    public function headings(): array
    {
        return $this->headings;
    }
}
