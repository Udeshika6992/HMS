<?php
// Include header
require_once 'header.php';
?>
<?php
// Include view helpers
require_once __DIR__ . '/../helpers.php';
?>
<!-- Page specific content will be inserted here -->
<?php echo $content ?? ''; ?>

<?php
// Include footer
require_once 'footer.php';
?>