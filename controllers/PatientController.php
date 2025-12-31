<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Patient.php';
require_once __DIR__ . '/../ai/PredictorFactory.php';

class PatientController
{
    public function showProgress($patientId)
    {
        $db = Database::getInstance()->getConnection();

        $patient = new Patient($db, $patientId);
        $visits = $patient->getVisits();

        $predictor = PredictorFactory::createPredictor("rule");
        $progress = $predictor->predict($visits);

        require __DIR__ . '/../views/patient/progress.php';
    }
}
