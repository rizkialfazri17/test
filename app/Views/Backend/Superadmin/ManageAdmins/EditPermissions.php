<?= $this->extend('Backend/Layout/Main'); ?>

<?= $this->section('MainContent'); ?>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Edit Permissions</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url("/admin/dashboard"); ?>">Home</a></li>
                <li class="breadcrumb-item">Superadmin</li>
                <li class="breadcrumb-item"><a href="<?= base_url("/admin/manage-admins"); ?>">Manage Admins</a></li>
                <li class="breadcrumb-item active">Edit Permissions</li>
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
                                <label for="search_edit_permissions_username" class="form-label">Username (ID)</label>
                                <input type="text" class="form-control shadow-none" name="search_edit_permissions_username" placeholder="Search by Username" value="<?= (isset($getPermissionsAdminData) ? $getPermissionsAdminData['username'] : ''); ?>" <?= (isset($getPermissionsAdminData) ? 'disabled' : ''); ?> />
                            </div>
                            <div class="text-center">
                                <input type="hidden" id="csrfProtection" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                <button type="submit" class="btn btn-primary shadow-none rounded-pill" style="width: 100px;" <?= (isset($getPermissionsAdminData) ? 'disabled' : ''); ?>>Search</button>
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
            <?php if (isset($getPermissionsAdminData) || !empty($getPermissionsAdminData)) : ?>
                <div class="col-xxl-9">
                    <div class="card">
                        <div class="card-body pt-3">
                            <ul class="nav nav-tabs nav-tabs-bordered mb-3">
                                <?php if (isset($getPermissionsAdminSession)) : ?>
                                    <?php if (session()->get('role') >= 3 || getExistPermission($getPermissionsAdminSession['permissions'], 'edit_admins') === TRUE) : ?>
                                        <li class="nav-item"><a href="<?= base_url('/admin/manage-admins/edit/' . (isset($getPermissionsAdminData) ? $getPermissionsAdminData['username'] : '')); ?>" class="nav-link">Edit Account</a></li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <li class="nav-item"><a href="<?= base_url('/admin/manage-admins/edit-permissions/' . (isset($getPermissionsAdminData) ? $getPermissionsAdminData['username'] : '')); ?>" class="nav-link active">Edit Permission</a></li>
                            </ul>
                            <form id="form_update_permissions" method="post" novalidate>
                                <h6 class="text-warning"><i class="bi bi-star-fill"></i> Admin</h6>
                                <div class="row mb-3">
                                    <legend class="col-form-label col-sm-2 pt-0">Manage Users</legend>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input shadow-none" name="manage_users" value="manage_users" <?= (getExistPermission($getPermissionsAdminData['permissions'], 'manage_users') === TRUE ? 'checked' : ''); ?> />
                                            <label class="form-check-label" for="manage_users"> Manage Users </label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input shadow-none" name="add_users" value="add_users" <?= (getExistPermission($getPermissionsAdminData['permissions'], 'add_users') === TRUE ? 'checked' : ''); ?> />
                                            <label class="form-check-label" for="add_users"> Add Users </label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input shadow-none" name="edit_users" value="edit_users" <?= (getExistPermission($getPermissionsAdminData['permissions'], 'edit_users') === TRUE ? 'checked' : ''); ?> />
                                            <label class="form-check-label" for="edit_users"> Edit Users </label>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="text-danger"><i class="bi bi-lightning-fill"></i> Superadmin</h6>
                                <div class="row mb-3">
                                    <legend class="col-form-label col-sm-2 pt-0">Manage Admins</legend>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input shadow-none" name="manage_admins" value="manage_admins" <?= (getExistPermission($getPermissionsAdminData['permissions'], 'manage_admins') === TRUE ? 'checked' : ''); ?> />
                                            <label class="form-check-label" for="manage_admins"> Access List Admin </label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input shadow-none" name="add_admins" value="add_admins" <?= (getExistPermission($getPermissionsAdminData['permissions'], 'add_admins') === TRUE ? 'checked' : ''); ?> />
                                            <label class="form-check-label" for="add_admins"> Add Admin Account </label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input shadow-none" name="edit_admins" value="edit_admins" <?= (getExistPermission($getPermissionsAdminData['permissions'], 'edit_admins') === TRUE ? 'checked' : ''); ?> />
                                            <label class="form-check-label" for="edit_admins"> Edit Admin Account </label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input shadow-none" name="delete_admins" value="delete_admins" <?= (getExistPermission($getPermissionsAdminData['permissions'], 'delete_admins') === TRUE ? 'checked' : ''); ?> />
                                            <label class="form-check-label" for="delete_admins"> Delete Admin Account </label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input shadow-none" name="edit_admin_permissions" value="edit_admin_permissions" <?= (getExistPermission($getPermissionsAdminData['permissions'], 'edit_admin_permissions') === TRUE ? 'checked' : ''); ?> />
                                            <label class="form-check-label" for="edit_admin_permissions"> Edit Admin Permission </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <legend class="col-form-label col-sm-2 pt-0">Website Settings</legend>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input shadow-none" name="edit_website_settings" value="edit_website_settings" <?= (getExistPermission($getPermissionsAdminData['permissions'], 'edit_website_settings') === TRUE ? 'checked' : ''); ?> />
                                            <label class="form-check-label" for="edit_website_settings"> Edit Website Settings </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <legend class="col-form-label col-sm-2 pt-0">Logs</legend>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input shadow-none" name="manage_logs" value="manage_logs" <?= (getExistPermission($getPermissionsAdminData['permissions'], 'manage_logs') === TRUE ? 'checked' : ''); ?> />
                                            <label class="form-check-label" for="manage_logs"> Access View Logs </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-2 mt-3">
                                        <input type="hidden" name="username" value="<?= $getPermissionsAdminData['username']; ?>" />
                                        <input type="hidden" id="csrfProtection" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                        <button type="submit" class="btn btn-success shadow-none rounded-pill" id="button_update_permissions" style="width: 150px;">Save Changes</button>
                                    </div>
                                    <div class="col-sm-10">
                                        <div id="alert_validation_update_admin_permissions" class="alert text-center" role="alert" hidden>
                                            <span id="alert_message_update_admin_permissions"></span>
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
        $("#form_update_permissions").on('submit', function(e) {
            e.preventDefault();
            const formUpdatePermissions = new FormData(this);
            $.ajax({
                url: base_url + "admin/manage-admins/update-permissions",
                method: "POST",
                dataType: "JSON",
                data: formUpdatePermissions,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#button_update_permissions").attr("disabled", 'disabled').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
                    $(".form-control").removeClass('is-invalid');
                    $(".invalid-feedback").html('');
                },
                complete: function() {
                    $("#button_update_permissions").removeAttr("disabled", 'disabled').text('Save Changes');
                },
                success: function(response) {
                    // csrf callback
                    $("#csrfProtection").val(response.token);

                    if (response.status === true) {
                        $("#alert_message_update_admin_permissions").html(response.message);
                        $("#alert_validation_update_admin_permissions").fadeIn(500).removeClass('alert-danger').addClass('alert-success').removeAttr("hidden", 'hidden');
                        setInterval(() => {
                            location.reload();
                        }, 3000);
                    } else {
                        $("#alert_message_update_admin_permissions").html(response.message);
                        $("#alert_validation_update_admin_permissions").fadeIn(500).removeClass('alert-success').addClass('alert-danger').removeAttr("hidden", 'hidden').delay(2500).fadeOut(500);
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