<?= $this->extend('Backend/Layout/Main'); ?>

<?= $this->section('MainContent'); ?>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Edit Admin</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url("/admin/dashboard"); ?>">Home</a></li>
                <li class="breadcrumb-item">Superadmin</li>
                <li class="breadcrumb-item"><a href="<?= base_url("/admin/manage-admins"); ?>">Manage Admins</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-xxl-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Search Admin</h5>
                        <form class="row g-3" method="post" novalidate>
                            <div class="col-12">
                                <label for="search_edit_admin_username" class="form-label">Username (ID)</label>
                                <input type="text" class="form-control shadow-none" name="search_edit_admin_username" placeholder="Search by Username" value="<?= (isset($getDataAdmin) ? $getDataAdmin['username'] : ''); ?>" <?= (isset($getDataAdmin) ? 'disabled' : ''); ?> />
                            </div>
                            <div class="col-12">
                                <label for="search_edit_admin_email" class="form-label">Email</label>
                                <input type="email" class="form-control shadow-none" name="search_edit_admin_email" placeholder="Search by Email" value="<?= (isset($getDataAdmin) ? $getDataAdmin['email'] : ''); ?>" <?= (isset($getDataAdmin) ? 'disabled' : ''); ?> />
                            </div>
                            <div class="col-12">
                                <label for="search_edit_admin_name" class="form-label">Name</label>
                                <input type="text" class="form-control shadow-none" name="search_edit_admin_name" placeholder="Search by Name" value="<?= (isset($getDataAdmin) ? $getDataAdmin['name'] : ''); ?>" <?= (isset($getDataAdmin) ? 'disabled' : ''); ?> />
                            </div>
                            <div class="text-center">
                                <input type="hidden" id="csrfProtection" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                <button type="submit" class="btn btn-primary shadow-none rounded-pill" <?= (isset($getDataAdmin) ? 'disabled' : ''); ?> style="width: 100px;">Search</button>
                                <a href="<?= base_url('/admin/manage-admins'); ?>" class="btn btn-outline-secondary rounded-pill shadow-none" style="width: 100px;">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php if (!empty(session()->getFlashdata('error'))) : ?>
                <div class="col-xxl-9">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error'); ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (isset($getDataAdmin) || !empty($getDataAdmin)) : ?>
                <div class="col-xxl-9">
                    <div class="card">
                        <div class="card-body pt-3">
                            <ul class="nav nav-tabs nav-tabs-bordered mb-3">
                                <li class="nav-item"><a href="<?= base_url('/admin/manage-admins/edit/' . (isset($getDataAdmin) ? $getDataAdmin['username'] : '')); ?>" class="nav-link active">Edit Account</a></li>
                                <?php if (isset($getPermissionsAdminSession)) : ?>
                                    <?php if (session()->get('role') >= 3 || getExistPermission($getPermissionsAdminSession['permissions'], 'edit_admin_permissions') === TRUE) : ?>
                                        <li class="nav-item"><a href="<?= base_url('/admin/manage-admins/edit-permissions/' . (isset($getDataAdmin) ? $getDataAdmin['username'] : '')); ?>" class="nav-link">Edit Permission</a></li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                            <form id="form_update_admin_account" method="post" novalidate>
                                <div class="row mb-3">
                                    <label for="data_edit_admin_username" class="col-sm-3 col-form-label">Username</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control shadow-none" id="data_edit_admin_username" name="data_edit_admin_username" value="<?= $getDataAdmin['username']; ?>" pattern="[a-zA-Z0-9]{6,24}" maxlength="24" required />
                                        <div class="invalid-feedback data_edit_admin_username"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="data_edit_admin_password" class="col-sm-3 col-form-label">Password
                                        <p style="font-size: 11px; padding: 0; margin: 0;">(If you don't want to replace it, leave it blank)</p>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control shadow-none" id="data_edit_admin_password" name="data_edit_admin_password" pattern="[a-zA-Z0-9]{6,24}" maxlength="24" />
                                        <div class="invalid-feedback data_edit_admin_password"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="data_edit_admin_email" class="col-sm-3 col-form-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control shadow-none" id="data_edit_admin_email" name="data_edit_admin_email" value="<?= $getDataAdmin['email']; ?>" required />
                                        <div class="invalid-feedback data_edit_admin_email"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="data_edit_admin_name" class="col-sm-3 col-form-label">Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control shadow-none" id="data_edit_admin_name" name="data_edit_admin_name" value="<?= $getDataAdmin['name']; ?>" pattern="[a-zA-Z0-9 ]{3,48}" maxlength="48" required />
                                        <div class="invalid-feedback data_edit_admin_name"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="data_edit_admin_avatar" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                                    <div class="col-md-2 col-lg-1 mb-2">
                                        <?php
                                        if (!empty($getDataAdmin['avatar'])) {
                                            $getAvatar = (FCPATH . 'assets/images/avatar/' . $getDataAdmin['avatar']);

                                            if (file_exists($getAvatar)) {
                                                echo '<img style="display: block; margin-bottom: 5px; border-radius: 50%;" id="review_image" src="' . base_url('/assets/images/avatar/' . $getDataAdmin['avatar']) . '" alt="Profile ' . $getDataAdmin['name'] . '" height="38" width="38" />';
                                            } else {
                                                echo '<img style="display: block; margin-bottom: 5px; border-radius: 50%;" id="review_image" src="' . base_url('/assets/images/avatar/default.png') . '" alt="Profile ' . $getDataAdmin['name'] . '" height="38" width="38" />';
                                            }
                                        } else {
                                            echo '<img style="display: block; margin-bottom: 5px; border-radius: 50%;" id="review_image" src="' . base_url('/assets/images/avatar/default.png') . '" alt="Profile ' . $getDataAdmin['name'] . '" height="38" width="38" />';
                                        } ?>
                                    </div>
                                    <div class="col-md-7 col-lg-8">
                                        <input type="file" class="form-control shadow-none" id="data_edit_admin_avatar" name="data_edit_admin_avatar" />
                                        <div class="invalid-feedback data_edit_admin_avatar"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="data_edit_admin_role" class="col-sm-3 col-form-label">Role</label>
                                    <div class="col-sm-9">
                                        <select class="form-select shadow-none" id="data_edit_admin_role" name="data_edit_admin_role" aria-label="Default select example">
                                            <option value="1" <?= ($getDataAdmin['role'] == 1 ? 'selected' : ''); ?>>Staff</option>
                                            <option value="2" <?= ($getDataAdmin['role'] == 2 ? 'selected' : ''); ?>>Admin</option>
                                            <option value="3" <?= ($getDataAdmin['role'] == 3 ? 'selected' : ''); ?>>Superadmin</option>
                                        </select>
                                        <div class="invalid-feedback data_edit_admin_role"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="data_edit_admin_status" class="col-sm-3 col-form-label">Status</label>
                                    <div class="col-sm-9">
                                        <select class="form-select shadow-none" id="data_edit_admin_status" name="data_edit_admin_status" aria-label="Default select example">
                                            <option value="0" <?= ($getDataAdmin['status'] == 0 ? 'selected' : ''); ?>>Deactive</option>
                                            <option value="1" <?= ($getDataAdmin['status'] == 1 ? 'selected' : ''); ?>>Active</option>
                                            <option value="2" <?= ($getDataAdmin['status'] == 2 ? 'selected' : ''); ?>>Suspend</option>
                                        </select>
                                        <div class="invalid-feedback data_edit_admin_status"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3 mt-3">
                                        <input type="hidden" name="username" value="<?= $getDataAdmin['username']; ?>" />
                                        <input type="hidden" id="csrfProtection" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                        <button type="submit" class="btn btn-success rounded-pill shadow-none" id="button_update_admin_account" style="width: 150px;">Save Changes</button>
                                    </div>
                                    <div class="col-sm-9">
                                        <div id="alert_validation_update_admin_account" class="alert text-center" role="alert" hidden>
                                            <span id="alert_message_update_admin_account"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
<!-- Script -->
<script type="text/javascript">
    $(document).ready(function() {
        // review file upload
        $("#data_edit_admin_avatar").on('change', function() {
            let image_url = window.URL.createObjectURL(this.files[0]);
            $("#review_image").removeAttr('hidden').attr('src', image_url);
        });

        // update
        $("#form_update_admin_account").on('submit', function(e) {
            e.preventDefault();
            const formUpdateAdminAccountData = new FormData(this);
            $.ajax({
                url: base_url + "admin/manage-admins/update",
                method: "POST",
                dataType: "JSON",
                data: formUpdateAdminAccountData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#button_update_admin_account").attr("disabled", 'disabled').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
                    $(".form-control").removeClass('is-invalid');
                    $(".invalid-feedback").html('');
                },
                complete: function() {
                    $("#button_update_admin_account").removeAttr("disabled", 'disabled').text('Save Changes');
                },
                success: function(response) {
                    // csrf callback
                    $("#csrfProtection").val(response.token);

                    if (response.status === true) {
                        $("#alert_message_update_admin_account").html(response.message);
                        $("#alert_validation_update_admin_account").fadeIn(500).removeClass('alert-danger').removeClass('alert-info').addClass('alert-success').removeAttr("hidden", 'hidden');
                        setInterval(() => {
                            window.location.href = base_url + "admin/manage-admins/edit";
                        }, 2500);
                    } else {
                        if (response.message instanceof Object) {
                            $.each(response.message, function(field, params) {
                                $("#" + field).addClass('is-invalid');
                                $("." + field).html('<i class="bi bi-exclamation-octagon"></i> ' + params);
                            });
                        } else {
                            $("#alert_message_update_admin_account").html(response.message);
                            if (response.message == 'Nothing has been changed!') {
                                $("#alert_validation_update_admin_account").fadeIn(500).removeClass('alert-danger').addClass('alert-info').removeAttr("hidden", 'hidden').delay(2500).fadeOut(500);
                            } else {
                                $("#alert_validation_update_admin_account").fadeIn(500).removeClass('alert-info').addClass('alert-danger').removeAttr("hidden", 'hidden').delay(2500).fadeOut(500);
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
    });
</script>
<?= $this->endSection(); ?>