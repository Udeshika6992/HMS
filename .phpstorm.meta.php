<?php
namespace PHPSTORM_META {
    // Map for model() method
    override(\Controller::model(0), map([
        'User' => \UserModel::class,
        'Doctor' => \DoctorModel::class,
        'Patient' => \PatientModel::class,
        'Appointment' => \AppointmentModel::class,
        'Department' => \DepartmentModel::class,
        'Setting' => \SettingModel::class,
        'MedicalRecord' => \MedicalRecordModel::class,
    ]));
}