/**
 * Patient Panel JavaScript
 */

// =====================================================
// Appointment Booking
// =====================================================
document.addEventListener('DOMContentLoaded', function() {
    var departmentSelect = document.getElementById('department');
    var doctorSelect = document.getElementById('doctor_id');
    var dateInput = document.getElementById('appointment_date');
    var timeSlots = document.getElementById('timeSlots');
    
    if (departmentSelect) {
        departmentSelect.addEventListener('change', function() {
            loadDoctors(this.value);
        });
    }
    
    if (doctorSelect) {
        doctorSelect.addEventListener('change', function() {
            if (this.value && dateInput.value) {
                loadTimeSlots(this.value, dateInput.value);
            }
        });
    }
    
    if (dateInput) {
        dateInput.addEventListener('change', function() {
            if (doctorSelect.value && this.value) {
                loadTimeSlots(doctorSelect.value, this.value);
            }
        });
    }
});

function loadDoctors(departmentId) {
    if (!departmentId) return;
    
    var doctorSelect = document.getElementById('doctor_id');
    doctorSelect.innerHTML = '<option value="">Loading...</option>';
    
    ajaxRequest(
        baseUrl + 'api/get-doctors-by-department?department_id=' + departmentId,
        'GET',
        null,
        function(response) {
            var options = '<option value="">Select Doctor</option>';
            response.data.forEach(function(doctor) {
                options += '<option value="' + doctor.id + '">Dr. ' + 
                          doctor.full_name + ' - ' + doctor.specialization + 
                          '</option>';
            });
            doctorSelect.innerHTML = options;
        },
        function(error) {
            alert('Error loading doctors: ' + error.message);
        }
    );
}

function loadTimeSlots(doctorId, date) {
    var timeSlots = document.getElementById('timeSlots');
    timeSlots.innerHTML = '<div class="spinner"></div>';
    
    ajaxRequest(
        baseUrl + 'patient/check-availability?doctor_id=' + doctorId + '&date=' + date,
        'GET',
        null,
        function(response) {
            var html = '';
            if (response.data.available_slots.length > 0) {
                response.data.available_slots.forEach(function(slot, index) {
                    html += '<div class="col-md-3 mb-2">';
                    html += '<div class="form-check">';
                    html += '<input class="form-check-input" type="radio" ';
                    html += 'name="appointment_time" value="' + slot + '" ';
                    html += 'id="slot' + index + '" required>';
                    html += '<label class="form-check-label btn btn-outline-primary w-100" ';
                    html += 'for="slot' + index + '">' + slot + '</label>';
                    html += '</div></div>';
                });
            } else {
                html = '<div class="col-12"><p class="text-warning text-center">';
                html += 'No available slots for this date</p></div>';
            }
            timeSlots.innerHTML = html;
        },
        function(error) {
            timeSlots.innerHTML = '<p class="text-danger">Error loading slots</p>';
        }
    );
}

// =====================================================
// Progress Charts
// =====================================================
function initProgressChart() {
    var ctx = document.getElementById('progressChart');
    if (!ctx) return;
    
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Weight (kg)',
                data: [],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: false
                }
            }
        }
    });
    
    return chart;
}