<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">Medical Record</h1>
        <p class="lead">Add medical record for patient</p>
    </div>
</section>

<!-- Medical Record Form -->
<section class="container mb-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-notes-medical me-2"></i>Patient: <?php echo htmlspecialchars($patient['full_name']); ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>doctor/add-medical-record/<?php echo $appointment_id; ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="visit_type" class="form-label">Visit Type</label>
                                <select class="form-select" id="visit_type" name="visit_type" required>
                                    <option value="regular">Regular Visit</option>
                                    <option value="follow_up">Follow-up Visit</option>
                                    <option value="emergency">Emergency Visit</option>
                                    <option value="checkup">Checkup</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="record_date" class="form-label">Record Date</label>
                                <input type="date" class="form-control" id="record_date" name="record_date" 
                                       value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="chief_complaint" class="form-label">Chief Complaint</label>
                            <input type="text" class="form-control" id="chief_complaint" name="chief_complaint" 
                                   placeholder="Main reason for visit" required>
                        </div>

                        <div class="mb-3">
                            <label for="symptoms" class="form-label">Symptoms</label>
                            <textarea class="form-control" id="symptoms" name="symptoms" rows="3" 
                                      placeholder="Describe symptoms in detail"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="diagnosis" class="form-label">Diagnosis</label>
                            <textarea class="form-control" id="diagnosis" name="diagnosis" rows="3" 
                                      placeholder="Enter diagnosis" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="treatment_plan" class="form-label">Treatment Plan</label>
                            <textarea class="form-control" id="treatment_plan" name="treatment_plan" rows="3" 
                                      placeholder="Enter treatment plan"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="doctor_notes" class="form-label">Doctor's Notes</label>
                            <textarea class="form-control" id="doctor_notes" name="doctor_notes" rows="3" 
                                      placeholder="Additional notes"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="follow_up_required" name="follow_up_required" value="1">
                                    <label class="form-check-label" for="follow_up_required">Follow-up Required</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="follow_up_date" class="form-label">Follow-up Date</label>
                                <input type="date" class="form-control" id="follow_up_date" name="follow_up_date">
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3">Vital Signs</h5>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="blood_pressure_systolic" class="form-label">BP (Systolic)</label>
                                <input type="number" class="form-control" id="blood_pressure_systolic" name="blood_pressure_systolic">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="blood_pressure_diastolic" class="form-label">BP (Diastolic)</label>
                                <input type="number" class="form-control" id="blood_pressure_diastolic" name="blood_pressure_diastolic">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="heart_rate" class="form-label">Heart Rate</label>
                                <input type="number" class="form-control" id="heart_rate" name="heart_rate">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="temperature" class="form-label">Temperature (°C)</label>
                                <input type="text" class="form-control" id="temperature" name="temperature">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="weight" class="form-label">Weight (kg)</label>
                                <input type="text" class="form-control" id="weight" name="weight">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="height" class="form-label">Height (m)</label>
                                <input type="text" class="form-control" id="height" name="height">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="blood_sugar_fasting" class="form-label">Blood Sugar (Fasting)</label>
                                <input type="number" class="form-control" id="blood_sugar_fasting" name="blood_sugar_fasting">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="oxygen_saturation" class="form-label">Oxygen Saturation (%)</label>
                                <input type="number" class="form-control" id="oxygen_saturation" name="oxygen_saturation">
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3">Prescriptions</h5>

                        <div id="prescriptionRows">
                            <div class="prescription-row mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="medicine_name[]" 
                                               placeholder="Medicine Name">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" name="dosage[]" 
                                               placeholder="Dosage">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" name="frequency[]" 
                                               placeholder="Frequency">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" name="duration[]" 
                                               placeholder="Duration">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-success btn-sm" onclick="addMedicineRow()">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <a href="<?php echo BASE_URL; ?>doctor/appointments" class="btn btn-secondary me-2">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Medical Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function addMedicineRow() {
    var container = document.getElementById('prescriptionRows');
    var row = document.createElement('div');
    row.className = 'prescription-row mb-3';
    row.innerHTML = `
        <div class="row">
            <div class="col-md-4">
                <input type="text" class="form-control" name="medicine_name[]" 
                       placeholder="Medicine Name">
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" name="dosage[]" 
                       placeholder="Dosage">
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" name="frequency[]" 
                       placeholder="Frequency">
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" name="duration[]" 
                       placeholder="Duration">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm" 
                        onclick="this.closest('.prescription-row').remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(row);
}
</script>