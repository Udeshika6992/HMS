<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">Patient Details</h1>
        <p class="lead">View patient information and medical history</p>
    </div>
</section>

<!-- Patient Information -->
<section class="container mb-5">
    <div class="row">
        <!-- Patient Profile Card -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="<?php echo UPLOAD_URL; ?>profiles/default-avatar.png" 
                         class="rounded-circle mb-3" 
                         style="width: 120px; height: 120px;">
                    <h4>John Doe</h4>
                    <p class="text-muted">Patient ID: P001</p>
                    
                    <hr>
                    
                    <div class="text-start">
                        <p><i class="fas fa-calendar-alt text-primary me-2"></i> DOB: Jan 01, 1990 (34 yrs)</p>
                        <p><i class="fas fa-venus-mars text-primary me-2"></i> Gender: Male</p>
                        <p><i class="fas fa-tint text-danger me-2"></i> Blood Group: O+</p>
                        <p><i class="fas fa-phone text-primary me-2"></i> Phone: 077-1234567</p>
                        <p><i class="fas fa-envelope text-primary me-2"></i> Email: john.doe@email.com</p>
                        <p><i class="fas fa-map-marker-alt text-primary me-2"></i> Address: 123 Main St, Kandy</p>
                    </div>
                    
                    <hr>
                    
                    <h6>Emergency Contact</h6>
                    <p class="mb-1">Jane Doe (Spouse)</p>
                    <p>077-7654321</p>
                </div>
            </div>
        </div>

        <!-- Medical History -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="#history" data-bs-toggle="tab">
                                Medical History
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#vitals" data-bs-toggle="tab">
                                Vitals
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#prescriptions" data-bs-toggle="tab">
                                Prescriptions
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Medical History Tab -->
                        <div class="tab-pane active" id="history">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Diagnosis</th>
                                            <th>Treatment</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Feb 15, 2024</td>
                                            <td>Upper Respiratory Infection</td>
                                            <td>Antibiotics</td>
                                            <td>
                                                <button class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Jan 20, 2024</td>
                                            <td>Hypertension</td>
                                            <td>Lifestyle changes</td>
                                            <td>
                                                <button class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Vitals Tab -->
                        <div class="tab-pane" id="vitals">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>BP</th>
                                            <th>Heart Rate</th>
                                            <th>Weight</th>
                                            <th>BMI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Feb 15, 2024</td>
                                            <td>120/80</td>
                                            <td>72</td>
                                            <td>72.5 kg</td>
                                            <td>24.2</td>
                                        </tr>
                                        <tr>
                                            <td>Jan 20, 2024</td>
                                            <td>125/85</td>
                                            <td>76</td>
                                            <td>73.0 kg</td>
                                            <td>24.4</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVitalsModal">
                                    <i class="fas fa-plus"></i> Add Vitals
                                </button>
                            </div>
                        </div>

                        <!-- Prescriptions Tab -->
                        <div class="tab-pane" id="prescriptions">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Medicine</th>
                                            <th>Dosage</th>
                                            <th>Frequency</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Feb 15, 2024</td>
                                            <td>Amoxicillin</td>
                                            <td>500mg</td>
                                            <td>Twice daily</td>
                                            <td><span class="badge bg-success">Active</span></td>
                                        </tr>
                                        <tr>
                                            <td>Jan 20, 2024</td>
                                            <td>Paracetamol</td>
                                            <td>650mg</td>
                                            <td>As needed</td>
                                            <td><span class="badge bg-secondary">Completed</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPrescriptionModal">
                                    <i class="fas fa-plus"></i> Add Prescription
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add Vitals Modal -->
<div class="modal fade" id="addVitalsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add Patient Vitals</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo BASE_URL; ?>doctor/add-vitals/1">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Blood Pressure (Systolic)</label>
                            <input type="number" class="form-control" name="blood_pressure_systolic">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Blood Pressure (Diastolic)</label>
                            <input type="number" class="form-control" name="blood_pressure_diastolic">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Heart Rate</label>
                            <input type="number" class="form-control" name="heart_rate">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Temperature</label>
                            <input type="text" class="form-control" name="temperature">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Weight (kg)</label>
                            <input type="text" class="form-control" name="weight">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Height (m)</label>
                            <input type="text" class="form-control" name="height">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Vitals</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Prescription Modal -->
<div class="modal fade" id="addPrescriptionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add Prescription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo BASE_URL; ?>doctor/add-prescription/1">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Medicine Name</label>
                            <input type="text" class="form-control" name="medicine_name[]" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Dosage</label>
                            <input type="text" class="form-control" name="dosage[]" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Frequency</label>
                            <input type="text" class="form-control" name="frequency[]" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Duration</label>
                            <input type="text" class="form-control" name="duration[]">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="quantity[]">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Refills</label>
                            <input type="number" class="form-control" name="refills[]" value="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Instructions</label>
                        <textarea class="form-control" name="instructions[]" rows="2"></textarea>
                    </div>
                    <hr>
                    <button type="button" class="btn btn-success btn-sm" onclick="addMedicineRow()">
                        <i class="fas fa-plus"></i> Add Another Medicine
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Prescription</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function addMedicineRow() {
    // In real app, this would clone the medicine row
    alert('Add medicine row functionality');
}
</script>