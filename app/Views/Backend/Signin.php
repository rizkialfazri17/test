<?php
// Load Models
use App\Models\WebsiteConfigurationModel;

// Preload models
$web_config = new WebsiteConfigurationModel;

// Base Website Configuration
$website_name = $web_config->getValue('website_name');
$website_language = $web_config->getValue('website_language');
?>
<!DOCTYPE html>
<html lang="<?= $website_language; ?>">

<head>
   <meta charset="utf-8">
   <meta content="width=device-width, initial-scale=1.0" name="viewport">
   <!-- Title -->
   <title><?= $website_title . ' - ' . $website_name; ?></title>
   <!-- HTML Meta Tag -->
   <meta name="title" content="<?= $website_title . ' - ' . $website_name; ?>">
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
   <meta itemprop="name" content="<?= $website_title . ' - ' . $website_name; ?>">
   <meta itemprop="description" content="<?= ($website_language === 'en' ? 'Admin Panel for ' . $website_name : 'Admin Panel untuk ' . $website_name) ?>">
   <meta itemprop="image" content="<?= base_url('/og-image.png'); ?>">

   <!-- Open Graph / Facebook Tags -->
   <meta property="og:title" content="<?= $website_title . ' - ' . $website_name; ?>">
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
   <meta name="twitter:title" content="<?= $website_title . ' - ' . $website_name; ?>">
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
   <link type="text/css" href="<?= base_url('/assets/css/admin-style.css'); ?>" rel="stylesheet">

   <!-- Jquery 3.5.1 -->
   <script type="text/javascript" src="<?= base_url('/assets/js/jquery.min.js'); ?>"></script>

   <script type="text/javascript">
      const base_url = "<?= base_url('/' . service('request')->getLocale() . '/'); ?>";
   </script>
</head>

<body>
   <main>
      <div class="container">
         <section class="section min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
               <div class="row justify-content-center">
                  <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8 d-flex flex-column align-items-center justify-content-center">
                     <div class="card mb-3">
                        <div class="card-body">
                           <div class="p-1 mb-4 border-bottom">
                              <h1 class="card-title text-center pb-0 fs-4"><?= lang('Pages.signin.title') . ' ' . $website_name; ?></h1>
                           </div>
                           <?php if (!empty(session()->getFlashdata('failed_login'))) : ?>
                              <div class="alert alert-danger text-center fade show" role="alert">
                                 <?= session()->getFlashData('failed_login'); ?>
                              </div>
                              <meta http-equiv="refresh" content="2; url=<?= base_url('/' . service('request')->getLocale() . '/admin/signin'); ?>" />
                           <?php endif; ?>
                           <?php if (!empty(session()->getFlashdata('success_signout'))) : ?>
                              <div class="alert alert-success text-center fade show" role="alert">
                                 <?= session()->getFlashData('success_signout'); ?>
                              </div>
                              <meta http-equiv="refresh" content="2; url=<?= base_url('/' . service('request')->getLocale() . '/admin/signin'); ?>" />
                           <?php endif; ?>
                           <?php if (!empty(session()->getFlashdata('failed_signout'))) : ?>
                              <div class="alert alert-danger text-center fade show" role="alert">
                                 <?= session()->getFlashData('failed_signout'); ?>
                              </div>
                              <meta http-equiv="refresh" content="2; url=<?= base_url('/' . service('request')->getLocale() . '/admin/signin'); ?>" />
                           <?php endif; ?>
                           <div class="alert alert-success text-center" role="alert" style="display: none;"></div>
                           <div class="alert alert-danger text-center" role="alert" style="display: none;"></div>
                           <form class="row g-3" id="form-signin" novalidate>
                              <div class="col-12 mb-2">
                                 <div class="form-floating">
                                    <input type="text" class="form-control shadow-none" id="username" name="username" placeholder="Username" pattern="[a-zA-Z0-9]{1,24}" maxlength="24" required <?= (session()->getFlashData('success_signout') ? 'disabled' : ''); ?> />
                                    <label for="username"><i class="bi bi-person-fill"></i> <?= lang('Label.username_or_email'); ?></label>
                                    <div class="invalid-feedback username"></div>
                                 </div>
                              </div>
                              <div class="col-12 mb-2">
                                 <div class="form-floating">
                                    <input type="password" class="form-control shadow-none" id="password" name="password" placeholder="Password" autocomplete="off" pattern="[a-zA-Z0-9]{1,24}" maxlength="24" required <?= (session()->getFlashData('success_signout') ? 'disabled' : ''); ?> />
                                    <label for="password"><i class="bi bi-lock-fill"></i> <?= lang('Label.password'); ?></label>
                                    <span class="button-show-password" id="show-password"><i class="bi bi-eye-fill"></i></span>
                                    <div class="invalid-feedback password"></div>
                                 </div>
                              </div>
                              <div class="col-12">
                                 <div class="form-check">
                                    <input type="checkbox" class="form-check-input shadow-none" id="remember_me" name="remember_me" />
                                    <label class="form-check-label" for="remember_me"><?= lang('Label.remember_me'); ?></label>
                                 </div>
                              </div>
                              <div class="col-12">
                                 <input type="hidden" id="csrfProtection" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" />
                                 <button type="submit" class="btn btn-lg btn-costume shadow-none w-100" id="button-signin" <?= (session()->getFlashData('success_signout') ? 'disabled' : ''); ?>><?= lang('Button.signin'); ?></button>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
      </div>
   </main>

   <script type="text/javascript">
      // theme color
      if (localStorage.getItem("data-theme") === null) {
         // jika theme belum ada di local storage maka set default light
         document.body.setAttribute("data-theme", "light");
         $("#change_theme").html('<i class="bi bi-sun-fill"></i>');
         localStorage.setItem("data-theme", "light");
      } else {
         if (localStorage.getItem("data-theme") === 'light') {
            document.body.setAttribute("data-theme", "light");
            $("#change_theme").html('<i class="bi bi-sun-fill"></i>');
         } else {
            document.body.setAttribute("data-theme", "dark");
            $("#change_theme").html('<i class="bi bi-moon-fill"></i>');
         }
      }

      $(document).ready(function() {
         $("#show-password").on('click', function() {
            let columnInput = $("#password");

            if (columnInput.attr('type') === 'password') {
               columnInput.attr('type', 'text');
               $("#show-password").html('<i class="bi bi-eye-slash-fill"></i>');
            } else {
               columnInput.attr('type', 'password');
               $("#show-password").html('<i class="bi bi-eye-fill"></i>');
            }
         });

         $("#form-signin").on('submit', function(e) {
            e.preventDefault();
            $.ajax({
               url: base_url + "admin/signin",
               method: "POST",
               dataType: "JSON",
               data: $("#form-signin").serialize(),
               cache: false,
               beforeSend: function() {
                  $("#button-signin").attr("disabled", 'disabled').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <?= lang('Button.processing'); ?>');
                  $(".form-control").removeClass('is-invalid').attr("disabled", 'disabled');
                  $(".invalid-feedback").html('');
               },
               complete: function() {
                  $("#button-signin").removeAttr("disabled", 'disabled').text('<?= lang('Button.signin'); ?>');
                  $(".form-control").removeAttr("disabled", 'disabled');
               },
               success: function(response) {
                  // csrf callback
                  $("#csrfProtection").val(response.token);

                  if (response.status === true) {
                     $(".form-control").addClass('is-valid');
                     $(".alert-success").text(response.message);
                     $(".alert-success").slideDown('slow').removeAttr('hidden').delay(1000).slideUp('slow', function() {
                        window.location.href = base_url + "admin/dashboard";
                     });
                  } else {
                     if (response.message instanceof Object) {
                        $.each(response.message, function(field, params) {
                           $("#" + field).addClass('is-invalid');
                           $("." + field).html('<i class="bi bi-exclamation-octagon"></i> ' + params);
                        });
                     } else {
                        $(".form-control").addClass('is-invalid');
                        $(".alert-danger").text(response.message);
                        $(".alert-danger").slideDown('slow').removeAttr('hidden').delay(2000).slideUp('slow', function() {
                           $(".form-control").removeClass('is-invalid');
                           $(".alert-danger").text('');
                        });
                     }
                  }
               },
               error: function(xhr, status, error) {
                  // alert(xhr.responseText);
                  alert('<?= lang('Validation.failed_to_signin') ?>');
               }
            });
         });
      });
   </script>

   <!-- JS -->
   <script type="text/javascript" src="<?= base_url('/assets/vendor/bootstrap-5.1.3/js/bootstrap.bundle.min.js'); ?>"></script>
   <script type="text/javascript" src="<?= base_url('/assets/js/admin-main.js'); ?>"></script>
</body>

</html>