<?= $this->extend('Backend/Layout/Main'); ?>

<?= $this->section('MainContent'); ?>
<main id="main" class="main">
    <div class="pagetitle">
        <h1><?= lang('Pages.account_settings.title'); ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('/' . service('request')->getLocale() . '/admin/dashboard'); ?>"><?= lang('Menu.home'); ?></a></li>
                <li class="breadcrumb-item"><?= lang('Menu.user'); ?></li>
                <li class="breadcrumb-item active"><?= lang('Menu.account_settings'); ?></li>
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
                                <a href="<?= base_url('/' . service('request')->getLocale() . '/admin/my-profile'); ?>" class="nav-link"><?= lang('Pages.my_profile.overview'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/' . service('request')->getLocale() . '/admin/account-settings'); ?>" class="nav-link active"><?= lang('Menu.account_settings'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/' . service('request')->getLocale() . '/admin/change-password'); ?>" class="nav-link"><?= lang('Menu.change_password'); ?></a>
                            </li>
                        </ul>

                        <div class="profile-overview" id="profile-overview">
                            <form id="form_account_settings" enctype="multipart/form-data" novalidate>
                                <h5 class="card-title"><?= lang('Pages.my_profile.profile_details'); ?></h5>
                                <div class="row mb-3">
                                    <label for="photo_profile" class="col-md-4 col-lg-3 col-form-label"><?= lang('Label.photo_profile'); ?></label>
                                    <div class="col-md-8 col-lg-9">
                                        <?php if (!empty($DataProfile['photo'])) {
                                            $getPhoto = FCPATH . '/assets/images/photo/' . $DataProfile['photo'];

                                            if (file_exists($getPhoto)) {
                                                echo '<img src="' . base_url('/assets/images/photo/' . $DataProfile['photo']) . '" alt="' . lang('Global.photo_profile') . ' ' . $DataProfile['full_name'] . '" class="rounded-circle" height="70"width="70" />';
                                            } else {
                                                echo '<img src="' . base_url('/assets/images/photo/default.png') . '" alt="' . lang('Global.photo_profile') . ' ' . $DataProfile['full_name'] . '" class="rounded-circle" height="70"width="70" />';
                                            }
                                        } else {
                                            echo '<img src="' . base_url('/assets/images/photo/default.png') . '" alt="' . lang('Global.photo_profile') . '" class="rounded-circle" height="70"width="70" />';
                                        } ?>
                                        <div class="mt-3">
                                            <input type="file" class="form-control shadow-none" id="photo_profile" name="photo_profile" autocomplete="off" />
                                            <div class="invalid-feedback photo_profile"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="full_name" class="col-md-4 col-lg-3 col-form-label"><?= lang('Label.full_name'); ?></label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="text" class="form-control shadow-none" id="full_name" name="full_name" value="<?= (isset($DataProfile) ? $DataProfile['full_name'] : ''); ?>" autocomplete="off" maxlength="48" placeholder="<?= (service('request')->getLocale() == 'id' ? 'Ir. John Doe S.Pd' : 'John Doe') ?>" required="required" />
                                        <div class="invalid-feedback full_name"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="gender" class="col-md-4 col-lg-3 col-form-label"><?= lang('Label.gender'); ?></label>
                                    <div class="col-md-8 col-lg-9">
                                        <div class="form-check">
                                            <input class="form-check-input me-1" type="radio" id="gender" name="gender" value="1" <?= (isset($DataProfile) ? ($DataProfile['gender'] == 1 || $DataProfile['gender'] == '1' ? 'checked' : '') : ''); ?> />
                                            <label class="form-check-label" for="gender"> <?= lang('Global.male'); ?></label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input me-1" type="radio" id="gender" name="gender" value="2" <?= (isset($DataProfile) ? ($DataProfile['gender'] == 2 || $DataProfile['gender'] == '2' ? 'checked' : '') : ''); ?> />
                                            <label class="form-check-label" for="gender"> <?= lang('Global.female'); ?></label>
                                        </div>
                                        <div class="invalid-feedback gender"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="place_of_birth" class="col-md-4 col-lg-3 col-form-label"><?= lang('Label.place_of_birth'); ?></label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="text" class="form-control shadow-none" id="place_of_birth" name="place_of_birth" value="<?= (isset($DataProfile) ? $DataProfile['place_of_birth'] : ''); ?>" maxlength="32" required="required" />
                                        <div class="invalid-feedback place_of_birth"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="date_of_birth" class="col-md-4 col-lg-3 col-form-label"><?= lang('Label.date_of_birth'); ?></label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="date" class="form-control shadow-none" id="date_of_birth" name="date_of_birth" value="<?= (isset($DataProfile) ? date('m/d/Y', strtotime($DataProfile['date_of_birth'])) : ''); ?>" maxlength="10" required="required" />
                                        <div class="invalid-feedback date_of_birth"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="address" class="col-md-4 col-lg-3 col-form-label"><?= lang('Label.address'); ?></label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="text" class="form-control shadow-none" id="address" name="address" value="<?= (isset($DataProfile) ? $DataProfile['address'] : ''); ?>" placeholder="<?= (service('request')->getLocale() == 'id' ? 'Jalan Maju Terus Pantang Mundur No. 123 RT. 009 RW. 009 Kel. Suka Kec/Desa. Maju' : '123 Broadway Street New York, NY, 10001') ?>" required="required" />
                                        <div class="invalid-feedback address"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="country" class="col-md-4 col-lg-3 col-form-label"><?= lang('Label.country'); ?></label>
                                    <div class="col-md-8 col-lg-9">
                                        <select class="form-select shadow-none" aria-label="Default select country" id="country" name="country">
                                            <option value=""><?= lang('Global.select') . ' ' . lang('Label.country'); ?></option>
                                            <?php foreach ($CountryList as $row) : ?>
                                                <option value="<?= $row['name']; ?>" data-id="<?= $row['id']; ?>"><?= $row['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback country"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="state" class="col-md-4 col-lg-3 col-form-label"><?= lang('Label.state'); ?></label>
                                    <div class="col-md-8 col-lg-9">
                                        <select class="form-select shadow-none" aria-label="Default select state" id="state" name="state">
                                            <option value=""><?= lang('Global.select') . ' ' . lang('Label.state'); ?></option>
                                        </select>
                                        <div class="invalid-feedback state"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="city" class="col-md-4 col-lg-3 col-form-label"><?= lang('Label.city'); ?></label>
                                    <div class="col-md-8 col-lg-9">
                                        <select class="form-select shadow-none" aria-label="Default select city" id="city" name="city">
                                            <option value=""><?= lang('Global.select') . ' ' . lang('Label.city'); ?></option>
                                        </select>
                                        <div class="invalid-feedback city"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="zip_code" class="col-md-4 col-lg-3 col-form-label"><?= lang('Label.zip_code'); ?></label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="text" class="form-control shadow-none" id="zip_code" name="zip_code" value="<?= (isset($DataProfile) ? $DataProfile['zip_code'] : ''); ?>" autocomplete="off" maxlength="5" pattern="[0-9]{5}" oninput="this.value=this.value.replace(/[^0-9]/g,'');" required="required" />
                                        <div class="invalid-feedback zip_code"></div>
                                    </div>
                                </div>
                                <div class="text-center"> <button type="submit" class="btn btn-primary">Save Changes</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<input type="hidden" id="csrfProtection" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" />
<!-- Script -->
<script type="text/javascript">
    $(document).ready(function() {
        // review file upload
        $('#photo_profile').on('change', function() {
            let image_url = window.URL.createObjectURL(this.files[0]);
            $('#review_avatar').attr('src', image_url);
        });

        // fetch state data from country
        $('#country').on('change', function() {
            let urlJsonStateData = '<?= base_url('/json/states.json'); ?>';
            let countryID = $('#country option:selected').attr('data-id');

            $.getJSON(urlJsonStateData, function(data) {
                let stateList = '<option><?= lang('Global.select') . ' ' . lang('Label.state'); ?></option>';
                $.each(data, function(index, item) {
                    if (item.country_id == countryID) {
                        stateList += '<option value="' + item.name + '" data-id="' + item.id + '">' + item.name + '</option>';
                    }
                });
                $('#state').html(stateList);
            });
        });

        // fetch city data from state
        $('#state').on('change', function() {
            let urlJsonCityData = '<?= base_url('/json/cities.json'); ?>';
            let stateID = $('#state option:selected').attr('data-id');

            $.getJSON(urlJsonCityData, function(data) {
                let cityList = '<option><?= lang('Global.select') . ' ' . lang('Label.city'); ?></option>';
                $.each(data, function(index, item) {
                    if (item.state_id == stateID) {
                        cityList += '<option value="' + item.name + '" data-id="' + item.id + '">' + item.name + '</option>';
                    }
                });
                $('#city').html(cityList);
            });
        });

        // update profile
        $("#form_edit_my_profile").on('submit', function(e) {
            e.preventDefault();
            const formEditMyProfileData = new FormData(this);
            // ajax
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