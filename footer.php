<div class="red-line"></div>

<!-- footer.php -->
<footer class="text-white text-center py-3">

<style>
/* Footer */
footer {
    color: white;
    padding: 40px 0;
    background: #42536d; /* professional hospital tone */
}

.footer-content {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
}

.footer-section {
    flex: 1 1 300px;
    margin: 20px;
}

.footer-section h2 {
    padding-bottom: 10px;
    margin-bottom: 10px;
}

.footer-section p, 
.footer-section ul {
    margin: 0;
    padding: 0;
    list-style: none;
    line-height: 1.8;
}

.footer-section a {
    color: #fff;
    text-decoration: none;
}

.footer-section a:hover {
    color: #00e1ff;
}

.footer-bottom {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid #444;
    margin-top: 20px;
    margin-bottom: -30px;
}

.social-icon i {
    font-size: 20px;
}
</style>

<footer class="gray">
    <div class="footer-content">
        
        <!-- Contact Section -->
        <div class="footer-section contact">
            <h2>Contact Us</h2><br>
            <p>Location: No.12, Central Road, Kurunegala, Sri Lanka</p>
            <p>Phone: +94 71 234 5678</p>
            <p>Email: info@hospitalhms.com</p>
        </div>

    </div>

    <div class="footer-bottom">
        <p>&copy; <?php echo date("Y"); ?> Hospital Management System. All Rights Reserved.</p>
        <p>Follow us on:
            <a href="#" class="text-white me-2 social-icon"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-white me-2 social-icon"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-white me-2 social-icon"><i class="fab fa-instagram"></i></a>
        </p>
    </div>
</footer>

<!-- Bootstrap JS Bundle -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
