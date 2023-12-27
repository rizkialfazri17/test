<?= $this->extend('Backend/Layout/Main'); ?>

<?= $this->section('MainContent'); ?>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>List Admin</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url("/admin/dashboard"); ?>">Home</a></li>
                <li class="breadcrumb-item">Superadmin</li>
                <li class="breadcrumb-item">Manage Admin</li>
                <li class="breadcrumb-item active">List Admin</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-xxl-3 col-lg-6 col-md-6">
                <div class="card info-card card-info">
                    <div class="card-body">
                        <h5 class="card-title">Total Admins</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="text-end">
                                <h6><?= numberFormat($countTotalAdmin); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-lg-6 col-md-6">
                <div class="card info-card card-warning">
                    <div class="card-body">
                        <h5 class="card-title">Counts of Admins Active</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-person-check"></i>
                            </div>
                            <div class="text-end">
                                <h6><?= numberFormat($countTotalAdminActive); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-lg-6 col-md-6">
                <div class="card info-card card-danger">
                    <div class="card-body">
                        <h5 class="card-title">Counts of Admins Suspended</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-person-x"></i>
                            </div>
                            <div class="text-end">
                                <h6><?= numberFormat($countTotalAdminSuspended); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-lg-6 col-md-6">
                <div class="card info-card card-success">
                    <div class="card-body">
                        <h5 class="card-title">Number of Admins Currently Online</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <div class="text-end">
                                <h6><?= numberFormat($countTotalAdminIsLogin); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <?php if (isset($getPermissionsAdminSession)) { ?>
                <?php if (session()->get('role') >= 3 || getExistPermission($getPermissionsAdminSession['permissions'], 'add_admins') === TRUE) { ?>
                    <div class="col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="accordion accordion-flush" id="accordionAddNewAdmin">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="accordionAddNewAdmin-headingOne">
                                            <button class="accordion-button collapsed card-title" type="button" data-bs-toggle="collapse" data-bs-target="#accordionAddNewAdmin-collapseOne" aria-expanded="false" aria-controls="accordionAddNewAdmin-collapseOne">
                                                Add New Admin
                                            </button>
                                        </h2>
                                        <div id="accordionAddNewAdmin-collapseOne" class="accordion-collapse collapse" aria-labelledby="accordionAddNewAdmin-headingOne" data-bs-parent="#accordionAddNewAdmin">
                                            <div class="accordion-body">
                                                <form class="row g-3" id="form_add_admin" method="post" novalidate>
                                                    <div class="col-12">
                                                        <label for="add_admin_username" class="form-label">Username (ID)</label>
                                                        <input type="text" class="form-control shadow-none" id="add_admin_username" name="add_admin_username" placeholder="Username" pattern="[a-zA-Z0-9]{6,24}" maxlength="24" autocomplete="off" required="required" />
                                                        <div class="invalid-feedback add_admin_username"></div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="add_admin_password" class="form-label">Password</label>
                                                        <div class="input-group">
                                                            <input type="password" class="form-control shadow-none" id="add_admin_password" name="add_admin_password" placeholder="Password" pattern="[a-zA-Z0-9]{6,24}" maxlength="24" autocomplete="off" required="required" />
                                                            <span class="input-group-text button-visible-password" id="visible-password"><i class="bi bi-eye-fill"></i></span>
                                                            <div class="invalid-feedback add_admin_password"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="add_admin_email" class="form-label">Email</label>
                                                        <input type="email" class="form-control shadow-none" id="add_admin_email" name="add_admin_email" placeholder="Email" autocomplete="off" required="required">
                                                        <div class="invalid-feedback add_admin_email"></div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="add_admin_name" class="form-label">Name</label>
                                                        <input type="text" class="form-control shadow-none" id="add_admin_name" name="add_admin_name" placeholder="Name" pattern="[a-zA-Z0-9 ]{3,48}" maxlength="48" autocomplete="off" required="required">
                                                        <div class="invalid-feedback add_admin_name"></div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="add_admin_avatar" class="form-label">Photo Profile <i class="small">(Can be left blank)</i></label>
                                                        <img style="display: block; margin-bottom: 10px; border-radius: 50%;" id="review_image" alt="Avatar" width="50" height="50" hidden />
                                                        <input type="file" class="form-control shadow-none" id="add_admin_avatar" name="add_admin_avatar">
                                                        <div class="invalid-feedback add_admin_avatar"></div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="add_admin_role" class="form-label">Role</label>
                                                        <select class="form-select shadow-none" id="add_admin_role" name="add_admin_role">
                                                            <option value="1">Staff</option>
                                                            <option value="2">Admin</option>
                                                            <?php if (session()->get('role') == 3) : ?>
                                                                <option value="3">Superadmin</option>
                                                            <?php endif; ?>
                                                        </select>
                                                        <div class="invalid-feedback add_admin_role"></div>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <label class="col-form-label">Status</label>
                                                        <div class="form-check form-switch mt-1">
                                                            <input type="checkbox" class="form-check-input shadow-none" id="add_admin_status" name="add_admin_status" checked>
                                                        </div>
                                                    </div>
                                                    <div class="text-center">
                                                        <input type="hidden" id="csrfProtection" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                                        <button type="submit" class="btn btn-primary shadow-none rounded-pill" style="width: 100px;" id="button_add_admin">Submit</button>
                                                        <button type="reset" class="btn btn-outline-secondary shadow-none rounded-pill" style="width: 100px;">Reset</button>
                                                    </div>
                                                    <div id="alert_validation_add_admin" class="alert text-center mt-4 mb-0" role="alert" hidden>
                                                        <span id="alert_message_add_admin"></span>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9">
                    <?php } else { ?>
                        <div class="col-xl-12">
                        <?php } ?>
                    <?php } else { ?>
                        <div class="col-xl-12">
                        <?php } ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="accordion accordion-flush" id="accordionFilterTableListAdmin">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="accordionFilterTableListAdmin-headingOne">
                                            <button class="accordion-button collapsed card-title" type="button" data-bs-toggle="collapse" data-bs-target="#accordionFilterTableListAdmin-collapseOne" aria-expanded="false" aria-controls="accordionFilterTableListAdmin-collapseOne">
                                                Filter Table
                                            </button>
                                        </h2>
                                        <div id="accordionFilterTableListAdmin-collapseOne" class="accordion-collapse collapse" aria-labelledby="accordionFilterTableListAdmin-headingOne" data-bs-parent="#accordionFilterTableListAdmin">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="filterTableListAdminByRole" class="form-label">Role</label>
                                                        <select id="filterTableListAdminByRole" name="filterTableListAdminByRole" class="form-select shadow-none">
                                                            <option value="">-- Select Role --</option>
                                                            <?php foreach ($getListRole as $row) : ?>
                                                                <option value="<?= $row['role']; ?>"><?= getRoleString($row['role']); ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="filterTableListAdminByStatus" class="form-label">Status</label>
                                                        <select id="filterTableListAdminByStatus" name="filterTableListAdminByStatus" class="form-select shadow-none">
                                                            <option value="">-- Select Status --</option>
                                                            <?php foreach ($getListStatus as $row) : ?>
                                                                <option value="<?= $row['status']; ?>"><?= getStatusString($row['status']); ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="filterTableListAdminByIsLogin" class="form-label">Logged In Status</label>
                                                        <select id="filterTableListAdminByIsLogin" name="filterTableListAdminByIsLogin" class="form-select shadow-none">
                                                            <option value="">-- Select Logged In Status --</option>
                                                            <?php foreach ($getIsLogin as $row) : ?>
                                                                <option value="<?= $row['is_login']; ?>"><?= getStatusLoggedInString($row['is_login']); ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="accordion accordion-flush" id="accordionTableListAdmin">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="accordionTableListAdmin-headingOne">
                                            <button class="accordion-button card-title" type="button" data-bs-toggle="collapse" data-bs-target="#accordionTableListAdmin-collapseOne" aria-expanded="true" aria-controls="accordionTableListAdmin-collapseOne">
                                                Table List Admin
                                            </button>
                                        </h2>
                                        <div id="accordionTableListAdmin-collapseOne" class="accordion-collapse collapse show" aria-labelledby="accordionTableListAdmin-headingOne" data-bs-parent="#accordionTableListAdmin">
                                            <div class="accordion-body">
                                                <div class="table-responsive">
                                                    <table id="tableListAdmin" class="table table-bordered table-striped table-hover small dataTable" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="5%">#</th>
                                                                <th scope="col" width="15%">Username ID</th>
                                                                <th scope="col" width="15%">Name</th>
                                                                <th scope="col" width="12%">Role</th>
                                                                <th scope="col" width="10%">Status</th>
                                                                <th scope="col" width="12%">Logged In</th>
                                                                <th scope="col">Last Login Time</th>
                                                                <th scope="col" width="15%">Options</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        </div>
    </section>
</main>
<div class="modal fade" id="view_detail_admin" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="view_detail_admin_title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3" id="view_detail_admin_avatar"></div>
                <table class="table table-striped table-hover small" width="100%" style="margin: 0; padding: 0;">
                    <tr>
                        <td width="35%" class="align-middle">Name</td>
                        <td width="1%" class="text-center align-middle">:</td>
                        <td width="64%" class="align-middle" id="view_detail_admin_name"></td>
                    </tr>
                    <tr>
                        <td width="35%" class="align-middle">Username</td>
                        <td width="1%" class="text-center align-middle">:</td>
                        <td width="64%" class="align-middle" id="view_detail_admin_username"></td>
                    </tr>
                    <tr>
                        <td width="35%" class="align-middle">Email</td>
                        <td width="1%" class="text-center align-middle">:</td>
                        <td width="64%" class="align-middle" id="view_detail_admin_email"></td>
                    </tr>
                    <tr>
                        <td width="35%" class="align-middle">Role</td>
                        <td width="1%" class="text-center align-middle">:</td>
                        <td width="64%" class="align-middle" id="view_detail_admin_role"></td>
                    </tr>
                    <tr>
                        <td width="35%" class="align-middle">Status</td>
                        <td width="1%" class="text-center align-middle">:</td>
                        <td width="64%" class="align-middle" id="view_detail_admin_status"></td>
                    </tr>
                    <tr>
                        <td width="35%" class="align-middle">Login Status</td>
                        <td width="1%" class="text-center align-middle">:</td>
                        <td width="64%" class="align-middle" id="view_detail_admin_is_login"></td>
                    </tr>
                    <tr>
                        <td width="35%" class="align-middle">Created Time</td>
                        <td width="1%" class="text-center align-middle">:</td>
                        <td width="64%" class="align-middle" id="view_detail_admin_created_time"></td>
                    </tr>
                    <tr>
                        <td width="35%" class="align-middle">Last Login Time</td>
                        <td width="1%" class="text-center align-middle">:</td>
                        <td width="64%" class="align-middle" id="view_detail_admin_last_login_time"></td>
                    </tr>
                    <tr>
                        <td width="35%" class="align-middle">Last Login IP</td>
                        <td width="1%" class="text-center align-middle">:</td>
                        <td width="64%" class="align-middle" id="view_detail_admin_last_login_time"></td>
                    </tr>
                    <tr>
                        <td width="35%" class="align-middle">Updated Time</td>
                        <td width="1%" class="text-center align-middle">:</td>
                        <td width="64%" class="align-middle" id="view_detail_admin_updated_time"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="csrfProtection" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
<!-- Script -->
<script type="text/javascript">
    $(document).ready(function() {
        // table
        let tableListAdmin = $("#tableListAdmin").DataTable({
            "processing": true,
            "serverSide": true,
            "serverMethod": "POST",
            "searching": true,
            "deferRender": true,
            "ajax": {
                "url": base_url + "admin/manage-admins/table",
                "data": function(data) {
                    // CSRF
                    let csrfName = $("#csrfProtection").attr('name');
                    let csrfHash = $("#csrfProtection").val();

                    // custom filter values
                    let filterByRole = $("#filterTableListAdminByRole").val();
                    let filterByStatus = $("#filterTableListAdminByStatus").val();
                    let filterByIsLogin = $("#filterTableListAdminByIsLogin").val();

                    data.searchByRole = filterByRole;
                    data.searchByStatus = filterByStatus;
                    data.searchByIsLogin = filterByIsLogin;
                    return {
                        data: data,
                        [csrfName]: csrfHash // CSRF Token
                    };
                },
                dataSrc: function(data) {
                    // Update token hash
                    $("#csrfProtection").val(data.token);

                    // Datatable data
                    return data.aaData;
                }
            },
            // "dom": '<"row mb-3"<"col-md-3 mt-1 mb-2"l><"col-md-6"B><"col-md-3 mt-1"f>>' +
            //     '<"row"<"col-md-12"tr>>' +
            //     '<"row"<"col-md-5"i><"col-md-7"p>>',
            // "buttons": [
            //     'copy',
            //     'excel',
            //     'csv',
            //     'pdf',
            //     'print',
            //     'colvis',
            // ],
            "oLanguage": {
                "sLengthMenu": "Show: _MENU_",
            },
            "pageLength": 5,
            "aLengthMenu": [
                [5, 10, 25, 50, 100],
                [5, 10, 25, 50, 100]
            ],
            "ordering": true,
            "order": [
                [6, 'desc']
            ],
            "columnDefs": [{
                    "targets": [0, 1, 2, 3, 4, 5, 6],
                    "className": "align-middle",
                },
                {
                    "targets": [0, 7],
                    "orderable": false,
                },
                {
                    "targets": [0, 3, 4, 5, 7],
                    "className": "text-center",
                },
                {
                    "targets": [3],
                    "render": function(data, type, row) {
                        if (row.role == 1 || row.role == '1') {
                            return '<span class="text-primary">Staff</span>';
                        } else if (row.role == 2 || row.role == '2') {
                            return '<span class="text-warning">Admin</span>';
                        } else if (row.role == 3 || row.role == '3') {
                            return '<span class="text-danger">Superadmin</span>';
                        } else if (row.role == 3 || row.role == '4') {
                            return '<span class="text-info">Developer</span>';
                        } else {
                            return '<span class="text-secondary">Unkown</span>';
                        }
                    }
                },
                {
                    "targets": [4],
                    "render": function(data, type, row) {
                        if (row.status == 0 || row.status == '0') {
                            return '<span class="badge rounded-pill bg-warning">Deactive</span>';
                        } else if (row.status == 1 || row.status == '1') {
                            return '<span class="badge rounded-pill bg-success">Active</span>';
                        } else if (row.status == 2 || row.status == '2') {
                            return '<span class="badge rounded-pill bg-danger">Suspended</span>';
                        } else {
                            return '<span class="badge rounded-pill bg-secondary">Unknown</span>';
                        }
                    }
                },
                {
                    "targets": [5],
                    "render": function(data, type, row) {
                        if (row.is_login == 0 || row.is_login == '0') {
                            return '<span class="badge bg-danger">Offline</span>';
                        } else if (row.is_login == 1 || row.is_login == '1') {
                            return '<span class="badge bg-success">Online</span>';
                        } else {
                            return '<span class="badge bg-secondary">Unkown</span>';
                        }
                    }
                }
            ],
            "columns": [{
                "data": null,
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1 + '.';
                }
            }, {
                "data": "username"
            }, {
                "data": "name"
            }, {
                "data": "role"
            }, {
                "data": "status"
            }, {
                "data": "is_login"
            }, {
                "data": "last_login_time"
            }, {
                "data": "options"
            }, ]
        });

        // custom filter
        $("#filterTableListAdminByRole").on('change', function() { // $("#filterTableListAdminByRole").change(function() { 
            tableListAdmin.draw();
        });
        $("#filterTableListAdminByStatus").on('change', function() {
            tableListAdmin.draw();
        });
        $("#filterTableListAdminByIsLogin").on('change', function() {
            tableListAdmin.draw();
        });

        // view detail
        $(document).on('click', ".button_view_detail_admin", function() {
            const username = $(this).attr('id');
            $.ajax({
                url: base_url + "admin/manage-admins/view/" + username,
                method: "POST",
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    if (response.status === true) {
                        if (response.message.role == 1 || response.message.role == '1') {
                            $("#view_detail_admin_role").html('<span class="text-primary">Staff</span>');
                        } else if (response.message.role == 2 || response.message.role == '2') {
                            $("#view_detail_admin_role").html('<span class="text-warning">Admin</span>');
                        } else if (response.message.role == 3 || response.message.role == '3') {
                            $("#view_detail_admin_role").html('<span class="text-danger">Superadmin</span>');
                        } else if (response.message.role == 3 || response.message.role == '4') {
                            $("#view_detail_admin_role").html('<span class="text-info">Developer</span>');
                        } else {
                            $("#view_detail_admin_role").html('<span class="text-secondary">Unkown</span>');
                        }

                        if (response.message.status == 0 || response.message.status == '0') {
                            $("#view_detail_admin_status").html('<span class="badge rounded-pill bg-warning">Deactive</span>');
                        } else if (response.message.status == 1 || response.message.status == '1') {
                            $("#view_detail_admin_status").html('<span class="badge rounded-pill bg-success">Active</span>');
                        } else if (response.message.status == 2 || response.message.status == '2') {
                            $("#view_detail_admin_status").html('<span class="badge rounded-pill bg-danger">Suspended</span>');
                        } else {
                            $("#view_detail_admin_status").html('<span class="badge rounded-pill bg-secondary">Unknown</span>');
                        }

                        if (response.message.is_login == 0 || response.message.is_login == '0') {
                            $("#view_detail_admin_is_login").html('<span class="badge bg-danger">Offline</span>');
                        } else if (response.message.is_login == 1 || response.message.is_login == '1') {
                            $("#view_detail_admin_is_login").html('<span class="badge bg-success">Online</span>');
                        } else {
                            $("#view_detail_admin_is_login").html('<span class="badge bg-secondary">Unkown</span>');
                        }

                        $("#view_detail_admin_title").text('View Admin Account: ' + response.message.username);
                        $("#view_detail_admin_avatar").html(response.message.avatar);
                        $("#view_detail_admin_username").html(response.message.username);
                        $("#view_detail_admin_email").html(response.message.email);
                        $("#view_detail_admin_name").html(response.message.name);
                        $("#view_detail_admin_created_time").html(response.message.created_time);
                        $("#view_detail_admin_last_login_time").html(response.message.last_login_time);
                        $("#view_detail_admin_last_login_ip").html(response.message.last_login_ip);
                        $("#view_detail_admin_updated_time").html(response.message.updated_time);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    // alert(xhr.responseText);
                    alert('Failed to get data, please contact developer!');
                }
            });
        });

        // button visible password
        $(document).on('click', "#visible-password", function() {
            let columnInput = $("#add_admin_password");

            if (columnInput.attr('type') === 'password') {
                columnInput.attr('type', 'text');
                $("#visible-password").html('<i class="bi bi-eye-slash-fill"></i>');
            } else {
                columnInput.attr('type', 'password');
                $("#visible-password").html('<i class="bi bi-eye-fill"></i>');
            }
        });

        // review file upload
        $("#add_admin_avatar").on('change', function() {
            let image_url = window.URL.createObjectURL(this.files[0]);
            $("#review_image").removeAttr('hidden').attr('src', image_url);
        });

        // add
        $("#form_add_admin").on('submit', function(e) {
            e.preventDefault();
            const formAddAdmin = new FormData(this);
            // ajax start
            $.ajax({
                url: base_url + "admin/manage-admins/add",
                method: "POST",
                dataType: "JSON",
                data: formAddAdmin,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#button_add_admin").attr("disabled", 'disabled').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
                    $(".form-control").removeClass('is-invalid');
                    $(".invalid-feedback").html('');
                },
                complete: function() {
                    $("#button_add_admin").removeAttr("disabled", 'disabled').text('Submit');
                },
                success: function(response) {
                    // csrf callback
                    $("#csrfProtection").val(response.token);

                    if (response.status === true) {
                        $("#alert_message_add_admin").html(response.message);
                        $("#alert_validation_add_admin").slideDown(500).removeClass('alert-danger').addClass('alert-success').removeAttr("hidden", 'hidden').delay(2000).slideUp(500);
                        $("#form_add_admin")[0].reset();
                        let drawTableAfterSubmit = setInterval(() => {
                            tableListAdmin.draw();
                            clearInterval(drawTableAfterSubmit);
                        }, 2500);
                    } else {
                        if (response.message instanceof Object) {
                            $.each(response.message, function(field, params) {
                                $("#" + field).addClass('is-invalid');
                                $("." + field).html('<i class="bi bi-exclamation-octagon"></i> ' + params);
                            });
                        } else {
                            $("#alert_message_add_admin").html(response.message);
                            $("#alert_validation_add_admin").slideDown(500).addClass('alert-danger').removeAttr("hidden", 'hidden').delay(2000).slideUp(500);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    // alert(xhr.responseText);
                    alert('Failed to add data, please contact developer!');
                }
            });
        });

        // delete
        $(document).on('click', ".button_delete_admin", function() {
            // CSRF Hash
            let csrfName = $("#csrfProtection").attr('name'); // CSRF Token name
            let csrfHash = $("#csrfProtection").val(); // CSRF hash

            let username = $(this).attr('id');
            if (confirm('Are you sure you want to delete this account?')) {
                $.ajax({
                    url: base_url + "admin/manage-admins/delete",
                    method: "POST",
                    dataType: "JSON",
                    cache: false,
                    data: {
                        username: username,
                        [csrfName]: csrfHash
                    },
                    success: function(response) {
                        // Update CSRF hash
                        $("#csrfProtection").val(response.token);

                        if (response.status === true) {
                            tableListAdmin.draw();
                            alert(response.message);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // alert(xhr.responseText);
                        alert('Failed to delete data, please contact developer!');
                    }
                });
            }
        });
    });
</script>
<?= $this->endSection(); ?>