<?php

namespace App\Events;

use App\Models\Survey;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SurveyClosed
{
    use Dispatchable, SerializesModels;

    public Survey $survey;


    public function __construct(Survey $survey)
    {
        $this->survey = $survey;
    }
}
