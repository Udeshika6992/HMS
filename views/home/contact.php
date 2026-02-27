<!-- Page Header -->
<section class="hero-section">
    <div class="container text-center">
        <h1 class="display-4">Contact Us</h1>
        <p class="lead">Get in touch with us for any inquiries</p>
    </div>
</section>

<!-- Contact Content -->
<section class="container mb-5">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="card-title mb-4">Get in Touch</h3>
                    
                    <div class="mb-4">
                        <h5><i class="fas fa-map-marker-alt text-primary me-2"></i> Address</h5>
                        <p class="ms-4"><?php echo $address; ?></p>
                    </div>
                    
                    <div class="mb-4">
                        <h5><i class="fas fa-phone text-primary me-2"></i> Phone</h5>
                        <p class="ms-4"><?php echo $phone; ?></p>
                    </div>
                    
                    <div class="mb-4">
                        <h5><i class="fas fa-envelope text-primary me-2"></i> Email</h5>
                        <p class="ms-4"><?php echo $email; ?></p>
                    </div>
                    
                    <div class="mb-4">
                        <h5><i class="fas fa-clock text-primary me-2"></i> Working Hours</h5>
                        <ul class="list-unstyled ms-4">
                            <?php foreach ($working_hours as $day => $hours): ?>
                                <li><strong><?php echo $day; ?>:</strong> <?php echo $hours; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="card-title mb-4">Send us a Message</h3>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>contact/send">
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>