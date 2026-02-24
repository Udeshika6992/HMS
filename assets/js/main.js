/**
 * ========================================
 * HOSPITAL MANAGEMENT SYSTEM - MAIN JAVASCRIPT
 * Author: M.G. Udeshika Saman Kumari
 * Project: Delthota Divisional Hospital HMS
 * ========================================
 */

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    
    // ========================================
    // 1. INITIALIZATION & GLOBAL SETTINGS
    // ========================================
    
    // Initialize tooltips
    initTooltips();
    
    // Initialize popovers
    initPopovers();
    
    // Initialize toast notifications
    initToasts();
    
    // Initialize form validation
    initFormValidation();
    
    // Initialize date pickers
    initDatePickers();
    
    // Initialize select2 replacements
    initEnhancedSelects();
    
    // Initialize sidebar toggle
    initSidebar();
    
    // Initialize mobile menu
    initMobileMenu();
    
    // ========================================
    // 2. TOOLTIPS & POPOVERS
    // ========================================
    
    function initTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                animation: true,
                delay: { show: 500, hide: 100 }
            });
        });
    }
    
    function initPopovers() {
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl, {
                animation: true,
                trigger: 'hover'
            });
        });
    }
    
    // ========================================
    // 3. TOAST NOTIFICATIONS
    // ========================================
    
    function initToasts() {
        const toastElList = [].slice.call(document.querySelectorAll('.toast'));
        toastElList.map(function(toastEl) {
            return new bootstrap.Toast(toastEl, {
                autohide: true,
                delay: 5000
            }).show();
        });
    }
    
    // Show custom toast notification
    window.showToast = function(message, type = 'success', title = 'Notification') {
        const toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            // Create toast container if it doesn't exist
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(container);
        }
        
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}</strong><br>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        document.getElementById('toast-container').insertAdjacentHTML('beforeend', toastHtml);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 5000 });
        toast.show();
        
        // Remove toast after it's hidden
        toastElement.addEventListener('hidden.bs.toast', function() {
            this.remove();
        });
    };
    
    // ========================================
    // 4. FORM VALIDATION
    // ========================================
    
    function initFormValidation() {
        const forms = document.querySelectorAll('.needs-validation');
        
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    // Scroll to first error
                    const firstInvalid = form.querySelector(':invalid');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstInvalid.focus();
                    }
                }
                
                form.classList.add('was-validated');
            }, false);
        });
        
        // Real-time validation
        const inputs = document.querySelectorAll('.form-control[required], .form-select[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validateField(this);
                }
            });
        });
    }
    
    function validateField(field) {
        if (field.checkValidity()) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
        }
    }
    
    // ========================================
    // 5. DATE PICKERS
    // ========================================
    
    function initDatePickers() {
        const dateInputs = document.querySelectorAll('input[type="date"]');
        
        // Set min date to today for appointment booking
        const appointmentDateInputs = document.querySelectorAll('.appointment-date');
        appointmentDateInputs.forEach(input => {
            const today = new Date().toISOString().split('T')[0];
            input.setAttribute('min', today);
        });
        
        // Set max date for date of birth (must be at least 1 year old)
        const dobInputs = document.querySelectorAll('.date-of-birth');
        dobInputs.forEach(input => {
            const maxDate = new Date();
            maxDate.setFullYear(maxDate.getFullYear() - 1);
            input.setAttribute('max', maxDate.toISOString().split('T')[0]);
        });
    }
    
    // ========================================
    // 6. ENHANCED SELECTS (Searchable dropdowns)
    // ========================================
    
    function initEnhancedSelects() {
        const selects = document.querySelectorAll('.select-search');
        
        selects.forEach(select => {
            // Simple search functionality
            const searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.className = 'form-control form-control-sm mb-2';
            searchInput.placeholder = 'Search...';
            
            select.parentNode.insertBefore(searchInput, select);
            
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const options = select.options;
                
                for (let i = 0; i < options.length; i++) {
                    const optionText = options[i].text.toLowerCase();
                    if (optionText.indexOf(searchTerm) > -1) {
                        options[i].style.display = '';
                    } else {
                        options[i].style.display = 'none';
                    }
                }
            });
        });
    }
    
    // ========================================
    // 7. SIDEBAR TOGGLE
    // ========================================
    
    function initSidebar() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                sidebar.classList.toggle('collapsed');
                
                // Save state to localStorage
                const isCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            });
            
            // Restore sidebar state
            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true') {
                sidebar.classList.add('collapsed');
            }
        }
    }
    
    function initMobileMenu() {
        const menuToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.querySelector('.sidebar');
        
        if (menuToggle && sidebar) {
            menuToggle.addEventListener('click', function(e) {
                e.preventDefault();
                sidebar.classList.toggle('show');
            });
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                        sidebar.classList.remove('show');
                    }
                }
            });
        }
    }
    
    // ========================================
    // 8. APPOINTMENT BOOKING
    // ========================================
    
    // Doctor selection change
    const doctorSelect = document.getElementById('doctor_id');
    if (doctorSelect) {
        doctorSelect.addEventListener('change', function() {
            const doctorId = this.value;
            const dateInput = document.getElementById('appointment_date');
            
            if (doctorId && dateInput.value) {
                fetchAvailableSlots(doctorId, dateInput.value);
            }
        });
    }
    
    // Date selection change
    const dateInput = document.getElementById('appointment_date');
    if (dateInput) {
        dateInput.addEventListener('change', function() {
            const doctorId = document.getElementById('doctor_id')?.value;
            
            if (doctorId && this.value) {
                fetchAvailableSlots(doctorId, this.value);
            }
        });
    }
    
    // Fetch available time slots
    window.fetchAvailableSlots = function(doctorId, date) {
        const slotContainer = document.getElementById('time-slots');
        if (!slotContainer) return;
        
        slotContainer.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        
        fetch(`/api/get-available-slots?doctor_id=${doctorId}&date=${date}`)
            .then(response => response.json())
            .then(data => {
                if (data.slots && data.slots.length > 0) {
                    let html = '<div class="time-slots-grid">';
                    data.slots.forEach(slot => {
                        html += `<button type="button" class="time-slot btn btn-outline-primary" data-time="${slot}">${slot}</button>`;
                    });
                    html += '</div>';
                    slotContainer.innerHTML = html;
                    
                    // Add click handlers
                    document.querySelectorAll('.time-slot').forEach(btn => {
                        btn.addEventListener('click', function() {
                            document.querySelectorAll('.time-slot').forEach(b => b.classList.remove('selected'));
                            this.classList.add('selected');
                            document.getElementById('appointment_time').value = this.dataset.time;
                        });
                    });
                } else {
                    slotContainer.innerHTML = '<div class="alert alert-warning">No available slots for this date.</div>';
                }
            })
            .catch(error => {
                console.error('Error fetching slots:', error);
                slotContainer.innerHTML = '<div class="alert alert-danger">Error loading available slots.</div>';
            });
    };
    
    // ========================================
    // 9. AI SYMPTOM CHECKER
    // ========================================
    
    const symptomForm = document.getElementById('symptom-form');
    if (symptomForm) {
        symptomForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const symptoms = document.getElementById('symptoms').value;
            const resultDiv = document.getElementById('ai-result');
            
            if (!symptoms.trim()) {
                showToast('Please enter your symptoms', 'warning');
                return;
            }
            
            resultDiv.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status">Loading...</div></div>';
            
            fetch('/api/ai-symptom-checker', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ symptoms: symptoms })
            })
            .then(response => response.json())
            .then(data => {
                if (data.suggestions && data.suggestions.length > 0) {
                    let html = '<h5>Possible Conditions:</h5><div class="suggestions-list">';
                    data.suggestions.forEach(item => {
                        html += `
                            <div class="ai-suggestion-card">
                                <div class="disease">${item.disease}</div>
                                <div class="specialization">Consult: ${item.specialization}</div>
                                <span class="ai-badge">AI Suggestion</span>
                            </div>
                        `;
                    });
                    html += '</div>';
                    resultDiv.innerHTML = html;
                } else {
                    resultDiv.innerHTML = '<div class="alert alert-info">No matching conditions found. Please consult a doctor.</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultDiv.innerHTML = '<div class="alert alert-danger">Error processing request. Please try again.</div>';
            });
        });
    }
    
    // ========================================
    // 10. PATIENT PROGRESS TRACKING
    // ========================================
    
    // Add new progress record
    const progressForm = document.getElementById('progress-form');
    if (progressForm) {
        progressForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('/api/add-progress', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Progress added successfully!', 'success');
                    this.reset();
                    
                    // Refresh progress chart if exists
                    if (typeof updateProgressChart === 'function') {
                        updateProgressChart();
                    }
                } else {
                    showToast(data.message || 'Error adding progress', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error adding progress record', 'danger');
            });
        });
    }
    
    // ========================================
    // 11. PRINT REPORTS
    // ========================================
    
    window.printReport = function(printSection) {
        const printContents = document.getElementById(printSection).innerHTML;
        const originalContents = document.body.innerHTML;
        
        document.body.innerHTML = `
            <html>
                <head>
                    <title>Print Report</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { padding: 20px; }
                        @media print {
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    ${printContents}
                </body>
            </html>
        `;
        
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    };
    
    // ========================================
    // 12. EXPORT TO CSV
    // ========================================
    
    window.exportToCSV = function(tableId, filename = 'export.csv') {
        const table = document.getElementById(tableId);
        if (!table) return;
        
        const rows = table.querySelectorAll('tr');
        const csvData = [];
        
        rows.forEach(row => {
            const rowData = [];
            const cols = row.querySelectorAll('td, th');
            cols.forEach(col => {
                rowData.push('"' + col.innerText.replace(/"/g, '""') + '"');
            });
            csvData.push(rowData.join(','));
        });
        
        const csvString = csvData.join('\n');
        const blob = new Blob([csvString], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        
        a.href = url;
        a.download = filename;
        a.click();
        
        window.URL.revokeObjectURL(url);
    };
    
    // ========================================
    // 13. SEARCH & FILTER
    // ========================================
    
    const searchInputs = document.querySelectorAll('.table-search');
    searchInputs.forEach(input => {
        input.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const tableId = this.dataset.table;
            const table = document.getElementById(tableId);
            
            if (!table) return;
            
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.indexOf(searchTerm) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
    
    // ========================================
    // 14. CONFIRMATION DIALOGS
    // ========================================
    
    const deleteButtons = document.querySelectorAll('[data-confirm]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.dataset.confirm || 'Are you sure you want to delete this item?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
    
    // ========================================
    // 15. AUTO-DISMISS ALERTS
    // ========================================
    
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
    
    // ========================================
    // 16. LOADING STATE FOR BUTTONS
    // ========================================
    
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (this.form && this.form.checkValidity()) {
                const originalText = this.innerHTML;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Loading...';
                this.disabled = true;
                
                // Re-enable after 10 seconds (safety)
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 10000);
            }
        });
    });
    
    // ========================================
    // 17. PASSWORD STRENGTH INDICATOR
    // ========================================
    
    const passwordInput = document.getElementById('password');
    const strengthIndicator = document.getElementById('password-strength');
    
    if (passwordInput && strengthIndicator) {
        passwordInput.addEventListener('keyup', function() {
            const password = this.value;
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[A-Z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[$@#&!]+/)) strength++;
            
            const strengthText = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
            const strengthClass = ['danger', 'warning', 'info', 'primary', 'success'];
            
            strengthIndicator.innerHTML = `
                <div class="progress mt-2" style="height: 5px;">
                    <div class="progress-bar bg-${strengthClass[strength-1] || 'secondary'}" 
                         style="width: ${strength * 20}%"></div>
                </div>
                <small class="text-${strengthClass[strength-1] || 'secondary'}">
                    ${strengthText[strength-1] || 'Enter password'}
                </small>
            `;
        });
    }
    
    // ========================================
    // 18. IMAGE PREVIEW
    // ========================================
    
    const imageInputs = document.querySelectorAll('input[type="file"][data-preview]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function() {
            const previewId = this.dataset.preview;
            const preview = document.getElementById(previewId);
            
            if (preview && this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
    
    // ========================================
    // 19. DARK MODE TOGGLE
    // ========================================
    
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (darkModeToggle) {
        // Check for saved preference
        const darkMode = localStorage.getItem('darkMode') === 'true';
        
        if (darkMode) {
            document.body.classList.add('dark-mode');
            darkModeToggle.checked = true;
        }
        
        darkModeToggle.addEventListener('change', function() {
            if (this.checked) {
                document.body.classList.add('dark-mode');
                localStorage.setItem('darkMode', 'true');
            } else {
                document.body.classList.remove('dark-mode');
                localStorage.setItem('darkMode', 'false');
            }
        });
    }
    
    // ========================================
    // 20. RESPONSIVE TABLE SCROLL
    // ========================================
    
    const tables = document.querySelectorAll('.table-responsive');
    tables.forEach(table => {
        table.addEventListener('touchstart', function(e) {
            this.style.overflowX = 'auto';
        });
    });
    
    // ========================================
    // 21. BACK TO TOP BUTTON
    // ========================================
    
    const backToTop = document.getElementById('backToTop');
    if (backToTop) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTop.style.display = 'block';
            } else {
                backToTop.style.display = 'none';
            }
        });
        
        backToTop.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
    
    // ========================================
    // 22. CHARACTER COUNTER
    // ========================================
    
    const textareas = document.querySelectorAll('textarea[maxlength]');
    textareas.forEach(textarea => {
        const counter = document.createElement('small');
        counter.className = 'text-muted float-end';
        textarea.parentNode.appendChild(counter);
        
        function updateCounter() {
            const max = textarea.maxLength;
            const current = textarea.value.length;
            counter.textContent = `${current}/${max}`;
            
            if (current >= max) {
                counter.classList.add('text-danger');
            } else {
                counter.classList.remove('text-danger');
            }
        }
        
        textarea.addEventListener('input', updateCounter);
        updateCounter();
    });
    
    // ========================================
    // 23. AUTOCOMPLETE OFF FOR FORMS
    // ========================================
    
    const sensitiveForms = document.querySelectorAll('form[data-autocomplete="off"]');
    sensitiveForms.forEach(form => {
        form.setAttribute('autocomplete', 'off');
    });
    
    // ========================================
    // 24. PREVENT DOUBLE SUBMIT
    // ========================================
    
    const forms = document.querySelectorAll('form[data-prevent-double-submit]');
    forms.forEach(form => {
        let submitted = false;
        
        form.addEventListener('submit', function(e) {
            if (submitted) {
                e.preventDefault();
                showToast('Form already submitted. Please wait...', 'warning');
                return;
            }
            
            submitted = true;
        });
    });
    
    // ========================================
    // 25. INITIALIZATION COMPLETE
    // ========================================
    
    console.log('HMS JavaScript initialized successfully!');
    
    // Dispatch custom event for other scripts
    document.dispatchEvent(new Event('hms:initialized'));
});

// ========================================
// EXPORT FUNCTIONS FOR GLOBAL USE
// ========================================

// Make functions globally available
window.HMS = {
    showToast: window.showToast,
    printReport: window.printReport,
    exportToCSV: window.exportToCSV,
    fetchAvailableSlots: window.fetchAvailableSlots,
    updateChartData: window.updateChartData
};