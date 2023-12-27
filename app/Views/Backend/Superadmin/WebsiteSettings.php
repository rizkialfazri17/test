<?= $this->extend('Backend/Layout/Main'); ?>

<?= $this->section('MainContent'); ?>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Website Settings</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url("/admin/dashboard"); ?>">Home</a></li>
                <li class="breadcrumb-item">Superadmin</li>
                <li class="breadcrumb-item active">Website Settings</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="nav flex-column nav-pills pt-4" id="website-settings-pills-tab" role="tablist" aria-orientation="vertical">
                            <?php $i = 0; ?>
                            <?php foreach ($getListCategory as $tab) : ?>
                                <?php $i++; ?>
                                <button class="nav-link <?= ($i == 1 ? 'active' : ''); ?>" id="website-settings-pills-<?= $tab['category']; ?>-tab" data-bs-toggle="pill" data-bs-target="#website-settings-pills-<?= $tab['category']; ?>" type="button" role="tab" aria-controls="website-settings-pills-<?= $tab['category']; ?>" aria-selected="<?= ($i == 1 ? 'true' : 'false'); ?>"><?= ucfirst($tab['category']); ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content" id="website-settings-pills-tabContent">
                            <?php $num = 0; ?>
                            <?php foreach ($getListCategory as $tab) : ?>
                                <?php $num++; ?>
                                <div class="tab-pane fade <?= ($num == 1 ? 'show active' : ''); ?>" id="website-settings-pills-<?= $tab['category']; ?>" role="tabpanel" aria-labelledby="website-settings-pills-<?= $tab['category']; ?>-tab">
                                    <h5 class="card-title">Configuration Website Settings for <?= ucfirst($tab['category']); ?></h5>
                                    <form method="POST" id="<?= 'form_website_settings_' . $tab['category']; ?>" novalidate>
                                        <?php $cat = $tab['category']; ?>
                                        <?php foreach ($getListAll as $row) : ?>
                                            <?php if ($row['category'] == $cat) : ?>
                                                <div class="row mb-2">
                                                    <label for="<?= $row['name']; ?>" class="col-xxl-4 col-sm-12 col-form-label">
                                                        <?= $row['title']; ?>
                                                        <p class="text-muted" style="font-size: 12px; margin: 0; padding: 0;"><?= $row['description']; ?></p>
                                                    </label>
                                                    <div class="col-xxl-8">
                                                        <?php if ($row['type'] == 'color') {
                                                            echo '<input type="color" class="form-control form-control-color shadow-none" id="' . $row['name'] . '" name="' . $row['name'] . '" value="' . $row['value'] . '" />';
                                                            echo '<div class="invalid-feedback ' . $row['name'] . '"></div>';
                                                        } else if ($row['type'] == 'file') {
                                                            if ($row['name'] == 'website_favicon') {
                                                                echo '<img id="review_image_website_favicon" style="margin-bottom: 10px; border-radius: 50%;" src="' . base_url('/favicon.ico') . '" alt="Favicon" height="38" width="38" />';
                                                            }
                                                            if ($row['name'] == 'website_image') {
                                                                echo '<img id="review_image_website_image" style="margin-bottom: 10px;" src="' . base_url('/og-image.png') . '" alt="OG Image" height="38" width="65" />';
                                                            }
                                                            echo '<input type="file" class="form-control shadow-none" id="' . $row['name'] . '" name="' . $row['name'] . '" value="' . $row['value'] . '" />';
                                                            echo '<div class="invalid-feedback ' . $row['name'] . '"></div>';
                                                        } else if ($row['type'] == 'textarea') {
                                                            echo '<textarea class="form-control shadow-none" style="height: 62px" id="' . $row['name'] . '" name="' . $row['name'] . '" value="' . $row['value'] . '">' . $row['value'] . '</textarea>';
                                                        } else {
                                                            echo '<input type="' . $row['type'] . '" class="form-control shadow-none" id="' . $row['name'] . '" name="' . $row['name'] . '" value="' . $row['value'] . '" />';
                                                            echo '<div class="invalid-feedback ' . $row['name'] . '"></div>';
                                                        } ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <div class="row">
                                            <div class="col-sm-4 mt-3">
                                                <input type="hidden" id="form_website_config" name="form_website_config" value="<?= $tab['category']; ?>" />
                                                <input type="hidden" id="csrfProtection" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" />
                                                <button type="submit" class="btn btn-success shadow-none mb-2 rounded-pill" id="button_save_change_website_settings">Save Changes</button>
                                            </div>
                                            <div class="col-sm-8">
                                                <div id="<?= 'alert_validation_change_website_settings_' . $tab['category']; ?>" class="alert text-center" role="alert" hidden>
                                                    <span id="<?= 'alert_message_change_website_settings_' . $tab['category']; ?>"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<!-- Script -->
<script type="text/javascript">
    $(document).ready(function() {
        // review file upload
        $("#website_favicon").on('change', function() {
            let image_url = window.URL.createObjectURL(this.files[0]);
            $("#review_image_website_favicon").attr('src', image_url);
        });

        // review file upload
        $("#website_image").on('change', function() {
            let image_url = window.URL.createObjectURL(this.files[0]);
            $("#review_image_website_image").attr('src', image_url);
        });

        <?php foreach ($getListCategory as $tab) : ?>
            $("<?= '#form_website_settings_' . $tab['category']; ?>").on('submit', function(e) {
                e.preventDefault();
                const form_data_<?= $tab['category']; ?> = new FormData(this);
                // ajax start
                $.ajax({
                    url: base_url + "admin/website-settings/update",
                    method: "POST",
                    dataType: "JSON",
                    data: form_data_<?= $tab['category']; ?>,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        $("#button_save_change_website_settings").attr("disabled", 'disabled').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
                        $(".form-control").removeClass('is-invalid').attr("disabled", 'disabled');
                        $(".invalid-feedback").html('');
                    },
                    complete: function() {
                        $("#button_save_change_website_settings").removeAttr("disabled", 'disabled').text('Save Changes');
                        $(".form-control").removeAttr("disabled", 'disabled');
                    },
                    success: function(response) {
                        // csrf callback
                        $("#csrfProtection").val(response.token);

                        if (response.status === true) {
                            $("<?= '#alert_message_change_website_settings_' . $tab['category']; ?>").html(response.message);
                            $("<?= '#alert_validation_change_website_settings_' . $tab['category']; ?>").fadeIn(500).removeClass('alert-danger').removeClass('alert-info').addClass('alert-success').removeAttr("hidden", 'hidden').delay(2000).fadeOut(500);
                            setInterval(() => {
                                window.location.href = base_url + "admin/website-settings";
                            }, 2500);
                        } else {
                            if (response.message instanceof Object) {
                                $.each(response.message, function(field, params) {
                                    $("#" + field).addClass('is-invalid');
                                    $("." + field).html('<i class="bi bi-exclamation-octagon"></i> ' + params);
                                });
                            } else {
                                $("<?= '#alert_message_change_website_settings_' . $tab['category']; ?>").html(response.message);
                                if (response.message == 'No changes!') {
                                    $("<?= '#alert_validation_change_website_settings_' . $tab['category']; ?>").fadeIn(500).removeClass('alert-danger').addClass('alert-info').removeAttr("hidden", 'hidden').delay(2000).fadeOut(500);
                                } else {
                                    $("<?= '#alert_validation_change_website_settings_' . $tab['category']; ?>").fadeIn(500).removeClass('alert-info').addClass('alert-danger').removeAttr("hidden", 'hidden').delay(2000).fadeOut(500);
                                }
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        // alert(xhr.responseText);
                        alert('Failed to edit data, please contact developer!');
                    }
                });
            });
        <?php endforeach; ?>
    });
</script>
<?= $this->endSection(); ?>