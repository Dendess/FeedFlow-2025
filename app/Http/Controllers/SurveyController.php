<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey; // <-- essentiel

class SurveyController extends Controller
{
    public function publicShow($token)
    {
        $survey = Survey::where('token', $token)->first();

        if (! $survey) {
            abort(404, "Sondage introuvable");
        }

        $now = now();

        if ($survey->start_date && $now->lt($survey->start_date)) {
            return response("Ce sondage n'est pas encore ouvert.", 403);
        }

        if ($survey->end_date && $now->gt($survey->end_date)) {
            return response("Ce sondage est expiré.", 403);
        }

        return view('surveys.public-show', [
            'survey' => $survey,
        ]);
    }
}
