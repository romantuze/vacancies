<?php

namespace App\Exports;

use App\Models\Work;
use Maatwebsite\Excel\Concerns\FromCollection;

class WorksExport implements FromCollection
{

	public function __construct($itemId)
    {
        $this->works = Work::where('id',$itemId);
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$works = $this->works;
		$works = $works->get();
        return $works;
    }
   
}
