/**
 * Doctor Panel JavaScript
 */

// =====================================================
// Appointment Management
// =====================================================
function updateAppointmentStatus(appointmentId, status) {
    var modal = document.getElementById('statusModal');
    var form = document.getElementById('statusForm');
    
    if (modal && form) {
        form.action = baseUrl + 'doctor/update-appointment-status/' + appointmentId;
        document.getElementById('status').value = status;
        new bootstrap.Modal(modal).show();
    }
}

function startConsultation(appointmentId) {
    window.location.href = baseUrl + 'doctor/consultation/' + appointmentId;
}

// =====================================================
// Prescription Management
// =====================================================
function addMedicineRow() {
    var container = document.getElementById('prescriptionRows');
    var row = document.createElement('div');
    row.className = 'prescription-row mb-3';
    row.innerHTML = `
        <div class="row">
            <div class="col-md-4">
                <input type="text" class="form-control" name="medicine_name[]" 
                       placeholder="Medicine Name" required>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" name="dosage[]" 
                       placeholder="Dosage" required>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" name="frequency[]" 
                       placeholder="Frequency" required>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" name="duration[]" 
                       placeholder="Duration">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm" 
                        onclick="this.closest('.prescription-row').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(row);
}

// =====================================================
// Schedule Management
// =====================================================
function toggleAvailability(doctorId, status) {
    ajaxRequest(
        baseUrl + 'doctor/toggle-availability/' + doctorId,
        'POST',
        { status: status },
        function(response) {
            location.reload();
        },
        function(error) {
            alert('Error updating availability: ' + error.message);
        }
    );
}

function saveSchedule() {
    var form = document.getElementById('scheduleForm');
    var formData = new FormData(form);
    
    ajaxRequest(
        baseUrl + 'doctor/update-schedule',
        'POST',
        Object.fromEntries(formData),
        function(response) {
            alert('Schedule updated successfully');
        },
        function(error) {
            alert('Error updating schedule: ' + error.message);
        }
    );
}