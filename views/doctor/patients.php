<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">My Patients</h1>
        <p class="lead">View and manage your patients</p>
    </div>
</section>

<!-- Search Bar -->
<section class="container mb-4">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" 
                               placeholder="Search patients by name or phone...">
                        <button class="btn btn-primary" type="button" id="searchBtn">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Patients List -->
<section class="container mb-5">
    <div class="row">
        <?php for($i = 1; $i <= 6; $i++): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="<?php echo UPLOAD_URL; ?>profiles/default-avatar.png" 
                             class="rounded-circle me-3" 
                             style="width: 60px; height: 60px;">
                        <div>
                            <h5 class="card-title mb-1">John Doe <?php echo $i; ?></h5>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar-alt me-1"></i> Last visit: Feb 20, 2024
                            </p>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <i class="fas fa-phone text-primary me-2"></i> 077-1234567
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-tint text-danger me-2"></i> Blood Group: O+
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i> Allergies: Penicillin
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="<?php echo BASE_URL; ?>doctor/view-patient/<?php echo $i; ?>" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endfor; ?>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item disabled">
                <a class="page-link" href="#">Previous</a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link" href="#">Next</a>
            </li>
        </ul>
    </nav>
</section>

<script>
document.getElementById('searchBtn').addEventListener('click', function() {
    var keyword = document.getElementById('searchInput').value;
    if (keyword.length > 2) {
        // In real app, this would make an AJAX call
        alert('Searching for: ' + keyword);
    }
});
</script>