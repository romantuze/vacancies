<?php

namespace App\Exports;

use App\Models\Questionaire;
use Maatwebsite\Excel\Concerns\FromCollection;

class QuestionairesExport implements FromCollection
{

	public function __construct($itemId)
    {
        $this->questionaires = Questionaire::where('id',$itemId);
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$questionaires = $this->questionaires;
		$questionaires = $questionaires->get();
        return $questionaires;
    }
}
