<?= $this->extend('Backend/Layout/Main'); ?>

<?= $this->section('MainContent'); ?>
<main id="main" class="main">
    <div class="pagetitle">
        <h1><?= lang('Pages.my_profile.title'); ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('/' . service('request')->getLocale() . '/admin/dashboard'); ?>"><?= lang('Menu.home'); ?></a></li>
                <li class="breadcrumb-item"><?= lang('Menu.user'); ?></li>
                <li class="breadcrumb-item active"><?= lang('Menu.my_profile'); ?></li>
            </ol>
        </nav>
    </div>
    <section class="section profile">
        <div class="row">
            <div class="col-xl-3">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <?php if (!empty($DataProfile['photo'])) {
                            $getPhoto = FCPATH . '/assets/images/photo/' . $DataProfile['photo'];

                            if (file_exists($getPhoto)) {
                                echo '<img src="' . base_url('/assets/images/photo/' . $DataProfile['photo']) . '" alt="' . lang('Global.photo_profile') . ' ' . $DataProfile['full_name'] . '" class="rounded-circle" height="120"width="120" />';
                            } else {
                                echo '<img src="' . base_url('/assets/images/photo/default.png') . '" alt="' . lang('Global.photo_profile') . ' ' . $DataProfile['full_name'] . '" class="rounded-circle" height="120"width="120" />';
                            }
                        } else {
                            echo '<img src="' . base_url('/assets/images/photo/default.png') . '" alt="' . lang('Global.photo_profile') . '" class="rounded-circle" height="120"width="120" />';
                        } ?>
                        <h2><?= (isset($DataProfile) ? $DataProfile['full_name'] : $DataAccount['username']); ?></h2>
                        <h3><?= getRoleString($DataAccount['role']); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-9">
                <div class="card">
                    <div class="card-body pt-3">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a href="<?= base_url('/' . service('request')->getLocale() . '/admin/my-profile'); ?>" class="nav-link active"><?= lang('Pages.my_profile.overview'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/' . service('request')->getLocale() . '/admin/account-settings'); ?>" class="nav-link"><?= lang('Menu.account_settings'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/' . service('request')->getLocale() . '/admin/change-password'); ?>" class="nav-link"><?= lang('Menu.change_password'); ?></a>
                            </li>
                        </ul>

                        <div class="profile-overview" id="profile-overview">
                            <h5 class="card-title"><?= lang('Pages.my_profile.account_details'); ?></h5>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><?= lang('Label.username'); ?></div>
                                <div class="col-lg-9 col-md-8"><?= $DataAccount['username']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><?= lang('Label.role'); ?></div>
                                <div class="col-lg-9 col-md-8"><?= getRoleString($DataAccount['role']); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><?= lang('Label.status'); ?></div>
                                <div class="col-lg-9 col-md-8">
                                    <?php if ($DataAccount['status'] == 1 || $DataAccount['status'] == '1') {
                                        echo '<span class="badge rounded-pill bg-success"><i class="bi bi-check-circle me-1"></i> ' . lang('Global.active') . '</span>';
                                    } else if ($DataAccount['status'] == 2 || $DataAccount['status'] == '2') {
                                        echo '<span class="badge rounded-pill bg-danger"><i class="bi bi-x-circle me-1"></i> ' . lang('Global.deactive') . '</span>';
                                    } else {
                                        echo '<span class="badge rounded-pill bg-warning"><i class="bi bi-exclamation-circle me-1"></i> ' . lang('Global.suspended') . '</span>';
                                    } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><?= lang('Label.online_status'); ?></div>
                                <div class="col-lg-9 col-md-8">
                                    <?php if ($DataAccount['is_login'] == 1 || $DataAccount['is_login'] == '1') {
                                        echo '<span class="badge border-success border-1 rounded-pill text-success"><i class="bi bi-circle-fill me-1"></i> ' . lang('Global.online') . '</span>';
                                    } else {
                                        echo '<span class="badge border-danger border-1 rounded-pill text-danger"><i class="bi bi-circle-fill me-1"></i> ' . lang('Global.offline') . '</span>';
                                    } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><?= lang('Label.creation_time'); ?></div>
                                <div class="col-lg-9 col-md-8">
                                    <time datetime="<?= date('l, d F Y - h:i:s A', strtotime($DataAccount['created_time'])); ?>">
                                        <?= date('l, d F Y - h:i:s A', strtotime($DataAccount['created_time'])); ?>
                                    </time>
                                    [<?= getTimeAgo(date('d-m-Y H:i:s', strtotime($DataAccount['created_time'])), date('d-m-Y H:i:s')); ?>]
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><?= lang('Label.last_time_logged_in'); ?></div>
                                <div class="col-lg-9 col-md-8">
                                    <time datetime="<?= date('l, d F Y - h:i:s A', strtotime($DataAccount['last_login_time'])); ?>">
                                        <?= date('l, d F Y - h:i:s A', strtotime($DataAccount['last_login_time'])); ?>
                                    </time>
                                    [<?= getTimeAgo(date('d-m-Y H:i:s', strtotime($DataAccount['last_login_time'])), date('d-m-Y H:i:s')); ?>]
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><?= lang('Label.last_ip_logged_in'); ?></div>
                                <div class="col-lg-9 col-md-8"><?= $DataAccount['last_login_ip']; ?></div>
                            </div>
                            <?php if (!empty($DataAccount['updated_time'])) : ?>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"><?= lang('Label.last_updated'); ?></div>
                                    <div class="col-lg-9 col-md-8">
                                        <time datetime="<?= date('l, d F Y - h:i:s A', strtotime($DataAccount['updated_time'])); ?>">
                                            <?= date('l, d F Y - h:i:s A', strtotime($DataAccount['updated_time'])); ?>
                                        </time>
                                        [<?= getTimeAgo(date('d-m-Y H:i:s', strtotime($DataAccount['updated_time'])), date('d-m-Y H:i:s')); ?>]
                                    </div>
                                </div>
                            <?php endif; ?>

                            <h5 class="card-title"><?= lang('Pages.my_profile.profile_details'); ?></h5>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><?= lang('Label.full_name'); ?></div>
                                <div class="col-lg-9 col-md-8">asd</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><?= lang('Label.gender'); ?></div>
                                <div class="col-lg-9 col-md-8">asd</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><?= lang('Label.nik'); ?></div>
                                <div class="col-lg-9 col-md-8">asd</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><?= lang('Label.place_date_of_birth'); ?></div>
                                <div class="col-lg-9 col-md-8">asd</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><?= lang('Label.address'); ?></div>
                                <div class="col-lg-9 col-md-8">asd</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><?= lang('Label.state'); ?></div>
                                <div class="col-lg-9 col-md-8">asd</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><?= lang('Label.city'); ?></div>
                                <div class="col-lg-9 col-md-8">asd</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><?= lang('Label.country'); ?></div>
                                <div class="col-lg-9 col-md-8">asd</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><?= lang('Label.zip_code'); ?></div>
                                <div class="col-lg-9 col-md-8">asd</div>
                            </div>

                            <h5 class="card-title"><?= lang('Pages.my_profile.contact_details'); ?></h5>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><?= lang('Label.email'); ?></div>
                                <div class="col-lg-9 col-md-8">asd</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><?= lang('Label.phone_number'); ?></div>
                                <div class="col-lg-9 col-md-8">asd</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><?= lang('Label.whatsapp'); ?></div>
                                <div class="col-lg-9 col-md-8">asd</div>
                            </div>
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
        $("#photo_profile").on('change', function() {
            let image_url = window.URL.createObjectURL(this.files[0]);
            $("#review_avatar").attr('src', image_url);
        });

        // update profile
        $("#form_edit_my_profile").on('submit', function(e) {
            e.preventDefault();
            const formEditMyProfileData = new FormData(this);
            $.ajax({
                url: base_url + "admin/my-profile/update",
                method: "POST",
                dataType: "JSON",
                data: formEditMyProfileData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#button_save_changes").attr("disabled", 'disabled').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
                    $(".form-control").removeClass('is-invalid').attr("disabled", 'disabled');
                    $(".invalid-feedback").html('');
                },
                complete: function() {
                    $("#button_save_changes").removeAttr("disabled", 'disabled').text('Save Changes');
                    $(".form-control").removeAttr("disabled", 'disabled');
                },
                success: function(response) {
                    // csrf callback
                    $("#csrf_token").val(response.token);

                    if (response.status === true) {
                        $("#alert_message_change_profile").html(response.message);
                        $("#alert_validation_change_profile").fadeIn(500).removeClass('alert-danger').removeClass('alert-info').addClass('alert-success').removeAttr("hidden", 'hidden');
                        setInterval(() => {
                            location.reload();
                        }, 2500);
                    } else {
                        if (response.message instanceof Object) {
                            $.each(response.message, function(field, params) {
                                $("#" + field).addClass('is-invalid');
                                $("." + field).html('<i class="bi bi-exclamation-octagon"></i> ' + params);
                            });
                        } else {
                            $("#alert_message_change_profile").html(response.message);
                            if (response.message == 'No data has changed!') {
                                $("#alert_validation_change_profile").fadeIn(500).removeClass('alert-danger').addClass('alert-info').removeAttr("hidden", 'hidden').delay(2500).fadeOut(500);
                            } else {
                                $("#alert_validation_change_profile").fadeIn(500).removeClass('alert-info').addClass('alert-danger').removeAttr("hidden", 'hidden').delay(2500).fadeOut(500);
                            }
                        }
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });

        // change password
        $("#form_change_password").on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: base_url + "admin/my-profile/change-password",
                method: "POST",
                dataType: "JSON",
                data: $("#form_change_password").serialize(),
                cache: false,
                beforeSend: function() {
                    $("#button_change_password").attr("disabled", 'disabled').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
                    $(".form-control").removeClass('is-invalid').attr("disabled", 'disabled');
                    $(".invalid-feedback").html('');
                },
                complete: function() {
                    $("#button_change_password").removeAttr("disabled", 'disabled').text('Change Password');
                    $(".form-control").removeAttr("disabled", 'disabled');
                },
                success: function(response) {
                    // csrf callback
                    $("#csrf_token").val(response.token);

                    if (response.status === true) {
                        $("#alert_message_change_password").html(response.message);
                        $("#alert_validation_change_password").fadeIn(500).removeClass('alert-danger').addClass('alert-success').removeAttr("hidden", 'hidden');
                        setInterval(() => {
                            window.location.href = base_url + "admin/signout";
                        }, 2500);
                    } else {
                        if (response.message instanceof Object) {
                            $.each(response.message, function(field, params) {
                                $("#" + field).addClass('is-invalid');
                                $("." + field).html('<i class="bi bi-exclamation-octagon"></i> ' + params);
                            });
                        } else {
                            $("#alert_message_change_password").html(response.message);
                            $("#alert_validation_change_password").fadeIn(500).addClass('alert-danger').removeAttr("hidden", 'hidden').delay(2500).fadeOut(500);
                        }
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>