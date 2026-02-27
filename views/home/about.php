<!-- Page Header -->
<section class="hero-section">
    <div class="container text-center">
        <h1 class="display-4">About Us</h1>
        <p class="lead">Learn more about Deltota Divisional Hospital</p>
    </div>
</section>

<!-- About Content -->
<section class="container mb-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4"><?php echo $hospital_name; ?></h2>
                    <p class="lead text-center">Established in <?php echo $established; ?></p>
                    <hr>
                    
                    <h4>Our Story</h4>
                    <p><?php echo $description; ?></p>
                    
                    <h4 class="mt-4">Our Mission</h4>
                    <p><?php echo $mission; ?></p>
                    
                    <h4 class="mt-4">Our Vision</h4>
                    <p><?php echo $vision; ?></p>
                </div>
            </div>
        </div>
    </div>
</section>