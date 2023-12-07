<?php

namespace App\Service;

use App\Entity\Program;

class ProgramDuration 
{
    public function calculate(Program $program) : string
    {
        $totalProgramTimeInMinutes = 0;

        foreach($program->getSeasons() as $key => $season) {
            foreach($season->getEpisodes() as $key => $episode) {
                $totalProgramTimeInMinutes += $episode->getDuration();
            }
        }

        $days = floor($totalProgramTimeInMinutes/(60*24)); 
        $hours = floor(($totalProgramTimeInMinutes - $days*(60*24))/60);
        $minutes = $totalProgramTimeInMinutes - ($days*(60*24)) - ($hours*60);

        return "Durée totale : " . $days . " jour(s), " . $hours . " heure(s), " . $minutes . " minute(s).";

    }
}