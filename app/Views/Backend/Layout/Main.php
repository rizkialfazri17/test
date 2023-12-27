<?php
// Load Models
use App\Models\WebsiteConfigurationModel;

// Preload models
$web_config = new WebsiteConfigurationModel;

// Base Website Configuration
$website_name = $web_config->getValue('website_name');
$website_language = $web_config->getValue('website_language');

// set website title
$website_title = (!empty($WebsiteTitle) ? $WebsiteTitle . ' - ' . $website_name : $web_config->getValue('website_title'));
?>
<!DOCTYPE html>
<html lang="<?= $website_language; ?>">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <!-- Title -->
    <title><?= $website_title; ?></title>
    <!-- HTML Meta Tag -->
    <meta name="title" content="<?= $website_title; ?>">
    <meta name="description" content="<?= ($website_language === 'en' ? 'Admin Panel for ' . $website_name : 'Admin Panel untuk ' . $website_name) ?>">
    <meta name="application-name" content="Admin Panel <?= $website_name; ?>">
    <meta name="author" content="<?= $website_name; ?>">
    <meta name="language" content="<?= $website_language; ?>">
    <meta name="generator" content="Admin Panel">
    <meta name="referrer" content="origin-when-cross-origin">
    <meta name="revisit-after" content="7">
    <meta name="webcrawlers" content="all">
    <meta name="rating" content="general">
    <meta name="resource-type" content="website">
    <meta name="distribution" content="global">
    <meta name="spiders" content="all">
    <meta name="robots" content="noindex, nofollow, nocache">
    <meta name="googlebot" content="index, nofollow, noimageindex, max-video-preview:-1, max-image-preview:large, max-snippet:-1">
    <meta name="type" content="website">
    <meta name="copyright" content="(c) <?= date('Y') ?> <?= $website_name; ?>, all rights reserved.">
    <link rel="canonical" href="<?= current_url(); ?>">

    <!-- Google / Search Engine Tags -->
    <meta itemprop="name" content="<?= $website_title; ?>">
    <meta itemprop="description" content="<?= ($website_language === 'en' ? 'Admin Panel for ' . $website_name : 'Admin Panel untuk ' . $website_name) ?>">
    <meta itemprop="image" content="<?= base_url('/og-image.png'); ?>">

    <!-- Open Graph / Facebook Tags -->
    <meta property="og:title" content="<?= $website_title; ?>">
    <meta property="og:description" content="<?= ($website_language === 'en' ? 'Admin Panel for ' . $website_name : 'Admin Panel untuk ' . $website_name) ?>">
    <meta property="og:url" content="<?= current_url(); ?>">
    <meta property="og:site_name" content="<?= $website_name; ?>">
    <meta property="og:locale" content="<?= $website_language; ?>">
    <meta property="og:image" content="<?= base_url('/og-image.png'); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="<?= $website_name; ?>">
    <meta property="og:type" content="website">

    <!-- Twitter Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $website_title; ?>">
    <meta name="twitter:description" content="<?= ($website_language === 'en' ? 'Admin Panel for ' . $website_name : 'Admin Panel untuk ' . $website_name) ?>">
    <meta name="twitter:url" content="<?= current_url(); ?>">
    <meta name="twitter:site" content="<?= $website_name; ?>">
    <meta name="twitter:creator" content="<?= $website_name; ?>">
    <meta name="twitter:image" content="<?= base_url('/og-image.png'); ?>">

    <!-- Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('/apple-touch-icon.png'); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('/favicon-32x32.png'); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('/favicon-16x16.png'); ?>">
    <link rel="icon" type="image/xicon" href="<?= base_url('/favicon.ico'); ?>">
    <link rel="manifest" href="<?= base_url('/webmanifest.json'); ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap">

    <!-- CSS -->
    <link type="text/css" href="<?= base_url('/assets/vendor/bootstrap-5.1.3/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?= base_url('/assets/vendor/bootstrap-icons/css/bootstrap-icons.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?= base_url('/assets/vendor/datatables/datatables.min.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?= base_url('/assets/vendor/daterangepicker-master/daterangepicker.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?= base_url('/assets/vendor/summernote/summernote-lite.min.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?= base_url('/assets/css/admin-style.css'); ?>" rel="stylesheet">

    <!-- JQuery -->
    <script type="text/javascript" src="<?= base_url('/assets/js/jquery.min.js'); ?>"></script>

    <!-- Extend JS -->
    <script type="text/javascript" src="<?= base_url('/assets/vendor/datatables/datatables.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('/assets/vendor/summernote/summernote-lite.min.js'); ?>"></script>

    <script type="text/javascript">
        const base_url = "<?= base_url('/' . service('request')->getLocale() . '/'); ?>";

        $(document).ready(function() {
            $(".text-editor").summernote({
                tabsize: 2,
                height: 250,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onImageUpload: function(files) {
                        for (let i = 0; i < files.length; i++) {
                            $.upload(files[i]);
                        }
                    },
                    onMediaDelete: function(target) {
                        $.delete(target[0].src);
                    }
                },
            });

            $.upload = function(file) {
                let out = new FormData();
                out.append('file', file, file.name);
                $.ajax({
                    method: 'POST',
                    url: base_url + 'upload-image-handler',
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: out,
                    success: function(img) {
                        $('.text-editor').summernote('insertImage', img);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error(textStatus + " " + errorThrown);
                    }
                });
            };

            $.delete = function(src) {
                $.ajax({
                    method: 'POST',
                    url: base_url + 'delete-image-handler',
                    cache: false,
                    data: {
                        src: src
                    },
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error(textStatus + " " + errorThrown);
                    }
                });
            };
        });
    </script>
</head>

<body>
    <?= $this->include('Backend/Layout/Header') ?>
    <?= $this->include('Backend/Layout/Aside') ?>
    <?= $this->renderSection('MainContent'); ?>
    <?= $this->include('Backend/Layout/Footer') ?>

    <!-- Toast -->
    <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 999">
        <div id="toast-success" class="toast align-items-center bg-success text-white border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
            <div class="d-flex">
                <div class="toast-body" id="toast_message_success"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>

        <div id="toast-failed" class="toast align-items-center bg-danger text-white border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
            <div class="d-flex">
                <div class="toast-body" id="toast_message_error"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>

        <?php if (!empty(session()->getFlashdata('message_access_not_permitted'))) : ?>
            <div id="toast-access" class="toast align-items-center bg-danger text-white border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
                <div class="d-flex">
                    <div class="toast-body">
                        <?= session()->getFlashData('message_access_not_permitted'); ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- JS -->
    <script type="text/javascript" src="<?= base_url('/assets/vendor/bootstrap-5.1.3/js/bootstrap.bundle.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('/assets/vendor/chart.js/chart.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('/assets/vendor/daterangepicker-master/moment.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('/assets/vendor/daterangepicker-master/daterangepicker.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('/assets/js/admin-main.js'); ?>"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            setInterval('getTime()', 1000);
        });

        function getTime() {
            let currentTime = new Date();
            let currentHours = currentTime.getHours();
            let currentMinutes = currentTime.getMinutes();
            let currentSeconds = currentTime.getSeconds();

            currentMinutes = (currentMinutes < 10 ? '0' : '') + currentMinutes;
            currentSeconds = (currentSeconds < 10 ? '0' : '') + currentSeconds;

            let timeOfDay = (currentHours < 12) ? 'AM' : 'PM';
            currentHours = (currentHours > 12) ? currentHours - 12 : currentHours;
            currentHours = (currentHours == 0) ? 12 : currentHours;
            currentHours = (currentHours < 10) ? '0' + currentHours : currentHours;

            <?php if (service('request')->getLocale() === 'id') { ?>
                let stringWaktuSekarang = (currentTime.getHours() < 10 ? '0' + currentTime.getHours() : currentTime.getHours()) + ':' + currentMinutes + ':' + currentSeconds;
                $("#date_time_now").html('<i class="bi bi-calendar"></i> <?= hari() . ', ' . date('d') . ' ' . bulan() . ' ' . date('Y'); ?> <i class="bi bi-clock"></i> <time>' + stringWaktuSekarang + '</time>');
            <?php } else { ?>
                let currentTimeString = currentHours + ':' + currentMinutes + ':' + currentSeconds + ' ' + timeOfDay;
                $("#date_time_now").html('<i class="bi bi-calendar"></i> <?= date('l, d F Y'); ?> <i class="bi bi-clock"></i> <time>' + currentTimeString + '</time>');
            <?php } ?>
        }

        // theme color
        if (localStorage.getItem("data-theme") === null) {
            // jika theme belum ada di local storage maka set default
            document.body.setAttribute("data-theme", "light");
            $("#theme-switcher").html('<i class="bi bi-sun-fill"></i>');
            localStorage.setItem("data-theme", "light");
        } else {
            if (localStorage.getItem("data-theme") === 'light') {
                document.body.setAttribute("data-theme", "light");
                $("#theme-switcher").html('<i class="bi bi-sun-fill"></i>');
            } else {
                document.body.setAttribute("data-theme", "dark");
                $("#theme-switcher").html('<i class="bi bi-moon-fill"></i>');
            }
        }

        // theme switcher
        $(document).on('click', "#theme-switcher", function() {
            if (localStorage.getItem("data-theme") === 'dark') {
                document.body.setAttribute("data-theme", "light");
                localStorage.setItem("data-theme", "light");
                $("#theme-switcher").html('<i class="bi bi-sun-fill"></i>');
            } else {
                document.body.setAttribute("data-theme", "dark");
                localStorage.setItem("data-theme", "dark");
                $("#theme-switcher").html('<i class="bi bi-moon-fill"></i>');
            }
        });

        // toast
        const toastSuccess = document.getElementById('toast-success');
        const toastFailed = document.getElementById('toast-failed');
        const toast_success = new bootstrap.Toast(toastSuccess); // toast_success.show();
        const toast_failed = new bootstrap.Toast(toastFailed); // toast_failed.show();

        <?php if (!empty(session()->getFlashdata('message_access_not_permitted'))) : ?>
            const toastAccess = document.getElementById('toast-access');
            new bootstrap.Toast(toastAccess).show();
        <?php endif; ?>
    </script>
</body>

</html>