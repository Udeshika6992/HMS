<?php

class ProgressAnalyzer
{

    public function getStatus($visitCount)
    {
        if ($visitCount === 0) {
            return "No Visits Recorded";
        }

        if ($visitCount <= 1) {
            return "New Patient";
        }

        if ($visitCount <= 3) {
            return "Under Observation";
        }

        if ($visitCount <= 5) {
            return "Stable Progress";
        }

        return "Needs Further Monitoring";
    }
}
