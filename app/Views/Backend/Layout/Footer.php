<?php
// Load Models
use App\Models\WebsiteConfigurationModel;

// Preload models
$web_config = new WebsiteConfigurationModel;

// Base Website Configuration
$website_name = $web_config->getValue('website_name');
?>
<footer id="footer" class="footer">
    <div class="copyright"> &copy; Copyright <?= date('Y'); ?> <strong><span><?= $website_name; ?></span></strong>. All Rights Reserved.</div>
</footer>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>