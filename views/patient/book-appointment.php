<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">Book Appointment</h1>
        <p class="lead">Schedule your visit with our specialist doctors</p>
    </div>
</section>

<!-- Booking Form -->
<section class="container mb-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Appointment Details</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?= BASE_URL; ?>patient/do-book-appointment" id="appointmentForm">
                        <!-- Department Selection -->
                        <div class="mb-3">
                            <label for="department" class="form-label">Select Department</label>
                            <select class="form-select" id="department" name="department_id" required>
                                <option value="">-- Choose Department --</option>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?= $dept['id']; ?>">
                                        <?= htmlspecialchars($dept['department_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Doctor Selection -->
                        <div class="mb-3">
                            <label for="doctor_id" class="form-label">Select Doctor</label>
                            <select class="form-select" id="doctor_id" name="doctor_id" required disabled>
                                <option value="">-- First select a department --</option>
                            </select>
                        </div>

                        <!-- Date Selection -->
                        <div class="mb-3">
                            <label for="appointment_date" class="form-label">Select Date</label>
                            <input type="date" class="form-control" id="appointment_date" 
                                   name="appointment_date" min="<?= date('Y-m-d'); ?>" 
                                   max="<?= date('Y-m-d', strtotime('+30 days')); ?>" required disabled>
                        </div>

                        <!-- Time Slots -->
                        <div class="mb-3">
                            <label class="form-label">Available Time Slots</label>
                            <div class="row" id="timeSlots">
                                <div class="col-12">
                                    <p class="text-muted text-center py-3">Select a doctor and date to see available slots</p>
                                </div>
                            </div>
                        </div>

                        <!-- Symptoms -->
                        <div class="mb-3">
                            <label for="symptoms" class="form-label">Describe your symptoms</label>
                            <textarea class="form-control" id="symptoms" name="symptoms" 
                                      rows="4" placeholder="Please describe your symptoms or reason for visit" required></textarea>
                        </div>

                        <!-- Form Buttons -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= BASE_URL; ?>patient/dashboard" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-check"></i> Book Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Loading Spinner CSS -->
<style>
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(0,0,0,.1);
        border-radius: 50%;
        border-top-color: #3498db;
        animation: spin 1s ease-in-out infinite;
        margin-right: 10px;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .loading-text {
        color: #666;
        font-style: italic;
    }
</style>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const departmentSelect = document.getElementById('department');
    const doctorSelect = document.getElementById('doctor_id');
    const dateInput = document.getElementById('appointment_date');
    const timeSlots = document.getElementById('timeSlots');
    
    // Department change event
    departmentSelect.addEventListener('change', function() {
        const departmentId = this.value;
        
        if (departmentId) {
            // Show loading state
            doctorSelect.innerHTML = '<option value="">Loading doctors...</option>';
            doctorSelect.disabled = true;
            dateInput.disabled = true;
            dateInput.value = '';
            timeSlots.innerHTML = '<div class="col-12"><p class="text-muted text-center py-3">Select a doctor and date to see available slots</p></div>';
            
            // Fetch doctors via AJAX
            fetch('<?= BASE_URL; ?>api/get-doctors-by-department?department_id=' + departmentId)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.length > 0) {
                        let options = '<option value="">-- Select Doctor --</option>';
                        data.data.forEach(doctor => {
                            options += `<option value="${doctor.id}">Dr. ${doctor.full_name} - ${doctor.specialization || 'General'}</option>`;
                        });
                        doctorSelect.innerHTML = options;
                        doctorSelect.disabled = false;
                    } else {
                        doctorSelect.innerHTML = '<option value="">No doctors available in this department</option>';
                        doctorSelect.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    doctorSelect.innerHTML = '<option value="">Error loading doctors</option>';
                    doctorSelect.disabled = true;
                });
        } else {
            doctorSelect.innerHTML = '<option value="">-- First select a department --</option>';
            doctorSelect.disabled = true;
            dateInput.disabled = true;
            timeSlots.innerHTML = '<div class="col-12"><p class="text-muted text-center py-3">Select a doctor and date to see available slots</p></div>';
        }
    });
    
    // Doctor change event
    doctorSelect.addEventListener('change', function() {
        if (this.value && this.value !== '') {
            dateInput.disabled = false;
        } else {
            dateInput.disabled = true;
            dateInput.value = '';
            timeSlots.innerHTML = '<div class="col-12"><p class="text-muted text-center py-3">Select a doctor and date to see available slots</p></div>';
        }
    });
    
    // Date change event
    dateInput.addEventListener('change', function() {
        const doctorId = doctorSelect.value;
        const date = this.value;
        
        if (doctorId && date) {
            // Show loading
            timeSlots.innerHTML = '<div class="col-12 text-center py-3"><span class="loading-spinner"></span><span class="loading-text">Checking availability...</span></div>';
            
            // Fetch available slots
            fetch('<?= BASE_URL; ?>api/check-doctor-availability?doctor_id=' + doctorId + '&date=' + date)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.available_slots.length > 0) {
                        let slotsHtml = '';
                        data.data.available_slots.forEach((slot, index) => {
                            slotsHtml += `
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="appointment_time" 
                                               value="${slot}" id="slot${index}" required>
                                        <label class="form-check-label btn btn-outline-primary w-100" for="slot${index}">
                                            ${slot}
                                        </label>
                                    </div>
                                </div>
                            `;
                        });
                        timeSlots.innerHTML = slotsHtml;
                    } else {
                        timeSlots.innerHTML = '<div class="col-12"><p class="text-warning text-center">No available slots for this date</p></div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    timeSlots.innerHTML = '<div class="col-12"><p class="text-danger text-center">Error checking availability</p></div>';
                });
        }
    });
    
    // Form submission
    document.getElementById('appointmentForm').addEventListener('submit', function(e) {
        const symptoms = document.getElementById('symptoms').value;
        if (symptoms.length < 10) {
            e.preventDefault();
            alert('Please describe your symptoms (minimum 10 characters)');
            return;
        }
        
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').innerHTML = '<span class="loading-spinner"></span> Booking...';
    });
});
</script>