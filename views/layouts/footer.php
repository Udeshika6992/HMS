    </main>
    <!-- =============================================
         MAIN CONTENT ENDS HERE
         ============================================= -->

    <!-- =============================================
         FOOTER
         ============================================= -->
    <footer class="footer mt-5 py-4 bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5><i class="fas fa-hospital me-2"></i><?php echo APP_NAME; ?></h5>
                    <p>Providing quality healthcare services to the community.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo BASE_URL; ?>" class="text-white-50">Home</a></li>
                        <li><a href="<?php echo BASE_URL; ?>about" class="text-white-50">About Us</a></li>
                        <li><a href="<?php echo BASE_URL; ?>contact" class="text-white-50">Contact</a></li>
                        <li><a href="<?php echo BASE_URL; ?>privacy" class="text-white-50">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Contact Info</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i>Main Street, Deltota</li>
                        <li><i class="fas fa-phone me-2"></i>081-1234567</li>
                        <li><i class="fas fa-envelope me-2"></i>info@deltotahospital.lk</li>
                    </ul>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- =============================================
         BOOTSTRAP JS (CDN)
         ============================================= -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- =============================================
         JQUERY (CDN)
         ============================================= -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- =============================================
         MAIN CUSTOM JAVASCRIPT
         ============================================= -->
    <script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
    
    <!-- =============================================
         ROLE-SPECIFIC JAVASCRIPT
         ============================================= -->
    <?php if (isset($role)): ?>
        <?php if ($role == 'admin'): ?>
            <script src="<?php echo BASE_URL; ?>assets/js/admin.js"></script>
        <?php elseif ($role == 'doctor'): ?>
            <script src="<?php echo BASE_URL; ?>assets/js/doctor.js"></script>
        <?php elseif ($role == 'patient'): ?>
            <script src="<?php echo BASE_URL; ?>assets/js/patient.js"></script>
        <?php endif; ?>
    <?php endif; ?>
    
    <!-- =============================================
         CHART.JS (if needed)
         ============================================= -->
    <?php if (isset($include_chart) && $include_chart): ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php endif; ?>
    
    <!-- =============================================
         DATATABLES (if needed)
         ============================================= -->
    <?php if (isset($include_datatables) && $include_datatables): ?>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <?php endif; ?>
    
    <!-- =============================================
         PAGE-SPECIFIC JAVASCRIPT
         ============================================= -->
    <?php if (isset($page_js) && is_array($page_js)): ?>
        <?php foreach ($page_js as $js_file): ?>
            <script src="<?php echo BASE_URL; ?>assets/js/<?php echo $js_file; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- =============================================
         CUSTOM INLINE SCRIPT
         ============================================= -->
    <script>
        // Base URL for AJAX requests
        const BASE_URL = '<?php echo BASE_URL; ?>';
        
        // Auto-hide flash messages after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.flash-message .alert').forEach(function(alert) {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Load notifications for logged in users
        <?php if (isset($_SESSION['user_id'])): ?>
        function loadNotifications() {
            $.ajax({
                url: BASE_URL + 'api/get-notifications',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        let count = response.data.unread_count;
                        $('#notificationCount').text(count);
                        
                        let listHtml = '';
                        response.data.notifications.forEach(function(notif) {
                            listHtml += '<li><a class="dropdown-item" href="' + notif.link + '">';
                            listHtml += '<small class="text-muted">' + notif.time + '</small><br>';
                            listHtml += notif.message;
                            listHtml += '</a></li>';
                        });
                        
                        if (listHtml === '') {
                            listHtml = '<li><a class="dropdown-item text-center" href="#">No notifications</a></li>';
                        }
                        
                        $('#notificationList').html(listHtml);
                    }
                }
            });
        }
        
        // Load notifications every 30 seconds
        loadNotifications();
        setInterval(loadNotifications, 30000);
        <?php endif; ?>
    </script>
</body>
</html>