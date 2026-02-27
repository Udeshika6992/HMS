<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">Add Prescription</h1>
        <p class="lead">Create prescription for patient</p>
    </div>
</section>

<!-- Prescription Form -->
<section class="container mb-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-prescription me-2"></i>Patient: <?php echo htmlspecialchars($patient['full_name']); ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>doctor/add-prescription/<?php echo $patient['id']; ?>">
                        <div id="prescriptionRows">
                            <div class="prescription-row mb-4">
                                <h6>Medicine #1</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Medicine Name</label>
                                        <input type="text" class="form-control" name="medicine_name[]" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Dosage</label>
                                        <input type="text" class="form-control" name="dosage[]" 
                                               placeholder="e.g., 500mg" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Quantity</label>
                                        <input type="number" class="form-control" name="quantity[]" 
                                               placeholder="e.g., 30">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Frequency</label>
                                        <select class="form-select" name="frequency[]" required>
                                            <option value="">Select Frequency</option>
                                            <option value="Once daily">Once daily</option>
                                            <option value="Twice daily">Twice daily</option>
                                            <option value="Three times daily">Three times daily</option>
                                            <option value="Four times daily">Four times daily</option>
                                            <option value="Every 4 hours">Every 4 hours</option>
                                            <option value="Every 6 hours">Every 6 hours</option>
                                            <option value="Every 8 hours">Every 8 hours</option>
                                            <option value="As needed">As needed</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Duration</label>
                                        <input type="text" class="form-control" name="duration[]" 
                                               placeholder="e.g., 7 days">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Refills</label>
                                        <input type="number" class="form-control" name="refills[]" value="0">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Instructions</label>
                                    <textarea class="form-control" name="instructions[]" rows="2" 
                                              placeholder="e.g., Take after meals"></textarea>
                                </div>
                                <hr>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-success" onclick="addMedicineRow()">
                                <i class="fas fa-plus"></i> Add Another Medicine
                            </button>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>

                        <div class="text-end mt-4">
                            <a href="<?php echo BASE_URL; ?>doctor/view-patient/<?php echo $patient['id']; ?>" class="btn btn-secondary me-2">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Prescription
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
let medicineCount = 1;

function addMedicineRow() {
    medicineCount++;
    var container = document.getElementById('prescriptionRows');
    var row = document.createElement('div');
    row.className = 'prescription-row mb-4';
    row.innerHTML = `
        <h6>Medicine #${medicineCount} 
            <button type="button" class="btn btn-danger btn-sm float-end" 
                    onclick="this.closest('.prescription-row').remove()">
                <i class="fas fa-trash"></i> Remove
            </button>
        </h6>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Medicine Name</label>
                <input type="text" class="form-control" name="medicine_name[]" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Dosage</label>
                <input type="text" class="form-control" name="dosage[]" placeholder="e.g., 500mg" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" class="form-control" name="quantity[]" placeholder="e.g., 30">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Frequency</label>
                <select class="form-select" name="frequency[]" required>
                    <option value="">Select Frequency</option>
                    <option value="Once daily">Once daily</option>
                    <option value="Twice daily">Twice daily</option>
                    <option value="Three times daily">Three times daily</option>
                    <option value="Four times daily">Four times daily</option>
                    <option value="Every 4 hours">Every 4 hours</option>
                    <option value="Every 6 hours">Every 6 hours</option>
                    <option value="Every 8 hours">Every 8 hours</option>
                    <option value="As needed">As needed</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Duration</label>
                <input type="text" class="form-control" name="duration[]" placeholder="e.g., 7 days">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Refills</label>
                <input type="number" class="form-control" name="refills[]" value="0">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Instructions</label>
            <textarea class="form-control" name="instructions[]" rows="2" 
                      placeholder="e.g., Take after meals"></textarea>
        </div>
        <hr>
    `;
    container.appendChild(row);
}
</script>