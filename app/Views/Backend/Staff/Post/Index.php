<?= $this->extend('Backend/Layout/Main'); ?>

<?= $this->section('MainContent'); ?>
<main id="main" class="main">
    <div class="pagetitle">
        <h1><?= lang('Pages.post.title'); ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url("/admin/dashboard"); ?>"><?= lang('Global.home'); ?></a></li>
                <li class="breadcrumb-item">Staff</li>
                <li class="breadcrumb-item active"><?= lang('Pages.post.title'); ?></li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-xxl-3 col-lg-6 col-md-6">
                <div class="card info-card card-info">
                    <div class="card-body">
                        <h5 class="card-title"><?= lang('Pages.post.total_category'); ?></h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-bookmarks"></i>
                            </div>
                            <div class="text-end">
                                <h6><?= numberFormat($countTotalCategory); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-lg-6 col-md-6">
                <div class="card info-card card-success">
                    <div class="card-body">
                        <h5 class="card-title"><?= lang('Pages.post.total_post_published'); ?></h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-clipboard-check"></i>
                            </div>
                            <div class="text-end">
                                <h6><?= numberFormat($countTotalPostPublished); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-lg-6 col-md-6">
                <div class="card info-card card-danger">
                    <div class="card-body">
                        <h5 class="card-title"><?= lang('Pages.post.total_post_hidden'); ?></h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-clipboard-x"></i>
                            </div>
                            <div class="text-end">
                                <h6><?= numberFormat($countTotalPostHidden); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-lg-6 col-md-6">
                <div class="card info-card card-warning">
                    <div class="card-body">
                        <h5 class="card-title"><?= lang('Pages.post.total_post_viewed'); ?></h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-eye"></i>
                            </div>
                            <div class="text-end">
                                <h6><?= numberFormat(isset($countTotalPostViewed['viewers']) ? $countTotalPostViewed['viewers'] : 0); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="accordion accordion-flush" id="accordionManageCategory">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="accordionManageCategory-headingOne">
                                    <button class="accordion-button collapsed card-title" type="button" data-bs-toggle="collapse" data-bs-target="#accordionManageCategory-collapseOne" aria-expanded="false" aria-controls="accordionManageCategory-collapseOne">
                                        <?= lang('Pages.post.post_category'); ?>
                                    </button>
                                </h2>
                                <div id="accordionManageCategory-collapseOne" class="accordion-collapse collapse" aria-labelledby="accordionManageCategory-headingOne" data-bs-parent="#accordionManageCategory">
                                    <div class="accordion-body">
                                        <table class="table table-bordered table-striped" width="100%">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-center" width="10%">ID</th>
                                                    <th scope="col"><?= lang('Pages.post.category_name'); ?></th>
                                                    <th scope="col" width="10%" class="text-center"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="tableListCategory"></tbody>
                                        </table>
                                        <div class="alert alert-info show small">
                                            <?= lang('Pages.post.info_delete_category'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="accordion accordion-flush" id="accordionFilterTableListPost">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="accordionFilterTableListPost-headingOne">
                                    <button class="accordion-button collapsed card-title" type="button" data-bs-toggle="collapse" data-bs-target="#accordionFilterTableListPost-collapseOne" aria-expanded="false" aria-controls="accordionFilterTableListPost-collapseOne">
                                        <?= lang('Global.filter'); ?>
                                    </button>
                                </h2>
                                <div id="accordionFilterTableListPost-collapseOne" class="accordion-collapse collapse" aria-labelledby="accordionFilterTableListPost-headingOne" data-bs-parent="#accordionFilterTableListPost">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="filterTableListPostByCategory" class="form-label"><?= lang('Global.category'); ?></label>
                                                <select id="filterTableListPostByCategory" name="filterTableListPostByCategory" class="form-select shadow-none">
                                                    <option value="">-- <?= lang('Global.select'); ?> --</option>
                                                    <?php foreach ($getListPostCategory as $row) : ?>
                                                        <option value="<?= $row['category']; ?>"><?= $row['category']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="filterTableListPostByAuthor" class="form-label"><?= lang('Global.writer'); ?></label>
                                                <select id="filterTableListPostByAuthor" name="filterTableListPostByAuthor" class="form-select shadow-none">
                                                    <option value="">-- <?= lang('Global.select'); ?> --</option>
                                                    <?php foreach ($getListPostAuthor as $row) : ?>
                                                        <option value="<?= $row['author']; ?>"><?= $row['author']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="filterTableListPostByStatus" class="form-label"><?= lang('Global.status'); ?></label>
                                                <select id="filterTableListPostByStatus" name="filterTableListPostByStatus" class="form-select shadow-none">
                                                    <option value="">-- <?= lang('Global.select'); ?> --</option>
                                                    <?php foreach ($getListPostStatus as $row) : ?>
                                                        <option value="<?= $row['status']; ?>"><?= getStatusPostInString($row['status']); ?></option>
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
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="accordion accordion-flush" id="accordionTableListPost">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="accordionTableListPost-headingOne">
                                    <button class="accordion-button card-title" type="button" data-bs-toggle="collapse" data-bs-target="#accordionTableListPost-collapseOne" aria-expanded="true" aria-controls="accordionTableListPost-collapseOne">
                                        <?= lang('List.post'); ?>
                                    </button>
                                </h2>
                                <div id="accordionTableListPost-collapseOne" class="accordion-collapse collapse show" aria-labelledby="accordionTableListPost-headingOne" data-bs-parent="#accordionTableListPost">
                                    <div class="accordion-body">
                                        <a href="<?= base_url('/admin/post/add'); ?>" type="button" class="btn btn-primary rounded-pill mb-4 shadow-none"><i class="bi bi-plus-lg me-1"></i> ADD NEW POST</a>
                                        <div class="table-responsive">
                                            <table id="tableListPost" class="table table-bordered table-striped table-hover small dataTable" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" width="5%">#</th>
                                                        <th scope="col" width="10%"><?= lang('Global.category'); ?></th>
                                                        <th scope="col"><?= lang('Global.title'); ?></th>
                                                        <th scope="col" width="7%"><?= lang('Global.image'); ?></th>
                                                        <th scope="col" width="10%"><?= lang('Global.writer'); ?></th>
                                                        <th scope="col" width="10%"><?= lang('Global.total_views'); ?></th>
                                                        <th scope="col" width="7%"><?= lang('Global.status'); ?></th>
                                                        <th scope="col" width="15%"><?= lang('Global.created_time'); ?></th>
                                                        <th scope="col" width="10%"><?= lang('Global.options'); ?></th>
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
<input type="hidden" id="csrfProtection" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
<!-- Script -->
<script type="text/javascript">
    $(document).ready(function() {
        tableListCategory();

        // table list category
        function tableListCategory() {
            // CSRF Hash
            let csrfName = $("#csrfProtection").attr('name'); // CSRF Token name
            let csrfHash = $("#csrfProtection").val(); // CSRF hash

            $.ajax({
                url: base_url + "admin/post/table-category",
                method: "POST",
                dataType: "JSON",
                cache: false,
                data: {
                    [csrfName]: csrfHash
                },
                success: function(response) {
                    // Update CSRF token
                    $("#csrfProtection").val(response.token);

                    let html;
                    html = '<tr>';
                    html += '<td contenteditable="false" class="align-middle"></td>';
                    html += '<td id="fieldInputCategoryName" contenteditable="true" class="align-middle"></td>';
                    html += '<td class="align-middle text-center"><button type="button" class="btn btn-sm btn-success shadow-none" id="buttonAddCategory"><i class="bi bi-plus-lg"></i></button></td>';
                    html += '</tr>';
                    for (let count = 0; count < response.data.length; count++) {
                        html += '<tr>';
                        html += '<td class="align-middle text-center">' + response.data[count].id + '</td>';
                        html += '<td class="align-middle" data-category-id="' + response.data[count].id + '">' + response.data[count].name + '</td>';
                        html += '<td class="align-middle text-center"><button type="button" data-category-id="' + response.data[count].id + '" class="btn btn-sm btn-danger shadow-none" id="buttonDeleteCategory"><i class="bi bi-trash"></i></button></td>';
                        html += '</tr>';
                    }
                    $("#tableListCategory").html(html);
                },
                error: function(xhr, status, error) {
                    // alert(xhr.responseText);
                    alert('<?= lang('Validation.failed_to_get_category'); ?>');
                }
            });
        }

        // add category
        $(document).on('click', '#buttonAddCategory', function() {
            // CSRF Hash
            let csrfName = $("#csrfProtection").attr('name'); // CSRF Token name
            let csrfHash = $("#csrfProtection").val(); // CSRF hash

            let categoryName = $("#fieldInputCategoryName").text();
            if (categoryName == '') {
                $("#toast_message_error").text('<?= lang('Validation.please_enter_category_first'); ?>');
                toast_failed.show();
                return false;
            }
            $.ajax({
                url: base_url + "admin/post/add-category",
                method: "POST",
                dataType: "JSON",
                cache: false,
                data: {
                    categoryName: categoryName,
                    [csrfName]: csrfHash
                },
                success: function(response) {
                    // Update CSRF hash
                    $("#csrfProtection").val(response.token);

                    if (response.status === true) {
                        $("#toast_message_success").text(response.message);
                        toast_success.show();
                        tableListCategory();
                    } else {
                        $("#toast_message_error").text(response.message);
                        toast_failed.show();
                    }
                },
                error: function(xhr, status, error) {
                    // alert(xhr.responseText);
                    alert('<?= lang('Validation.failed_to_add_category'); ?>');
                }
            });
        });

        // delete category
        $(document).on('click', '#buttonDeleteCategory', function() {
            // CSRF Hash
            let csrfName = $("#csrfProtection").attr('name'); // CSRF Token name
            let csrfHash = $("#csrfProtection").val(); // CSRF hash

            let id = $(this).data('category-id');
            if (confirm('<?= lang('Pages.post.confirm_delete_category'); ?>')) {
                $.ajax({
                    url: base_url + "admin/post/delete-category",
                    method: "POST",
                    dataType: "JSON",
                    cache: false,
                    data: {
                        id: id,
                        [csrfName]: csrfHash
                    },
                    success: function(response) {
                        // Update CSRF hash
                        $("#csrfProtection").val(response.token);

                        if (response.status === true) {
                            $("#toast_message_success").text(response.message);
                            toast_success.show();
                            tableListCategory();
                        } else {
                            $("#toast_message_error").text(response.message);
                            toast_failed.show();
                        }
                    },
                    error: function(xhr, status, error) {
                        // alert(xhr.responseText);
                        alert('<?= lang('Validation.failed_to_delete_category'); ?>');
                    }
                });
            }
        });

        // table list post
        let tableListPost = $("#tableListPost").DataTable({
            "processing": true,
            "serverSide": true,
            "serverMethod": "POST",
            "searching": true,
            "deferRender": true,
            "ajax": {
                "url": base_url + "admin/post",
                "data": function(data) {
                    // CSRF
                    let csrfName = $("#csrfProtection").attr('name');
                    let csrfHash = $("#csrfProtection").val();

                    // custom filter values
                    let filterPostByCategory = $("#filterTableListPostByCategory").val();
                    let filterPostByAuthor = $("#filterTableListPostByAuthor").val();
                    let filterPostByStatus = $("#filterTableListPostByStatus").val();

                    data.searchPostByCategory = filterPostByCategory;
                    data.searchPostByAuthor = filterPostByAuthor;
                    data.searchPostByStatus = filterPostByStatus;
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
            "oLanguage": {
                "sLengthMenu": "<?= lang('Datatables.sLengthMenu') . ' : _MENU_'; ?>",
                "sSearch": "<?= lang('Datatables.sSearch') . ' :_INPUT_'; ?>",
                "sZeroRecords": "<?= lang('Datatables.sZeroRecords'); ?>",
                "sInfo": "<?= lang('Datatables.showing') . ' _START_ ' . lang('Datatables.to') . ' _END_ ' . lang('Datatables.of') . ' _TOTAL_ ' . lang('Datatables.entires'); ?>",
                "sInfoFiltered": "(<?= lang('Datatables.filtered_from') . ' _MAX_ ' . lang('Datatables.total_recors'); ?>)",
                "sInfoEmpty": "<?= lang('Datatables.sInfoEmpty'); ?>",
            },
            "language": {
                "paginate": {
                    "next": '<i class="bi bi-arrow-right"></i>',
                    "previous": '<i class="bi bi-arrow-left"></i>'
                }
            },
            "pageLength": 10,
            "aLengthMenu": [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            "ordering": true,
            "order": [
                [7, 'desc']
            ],
            "columnDefs": [{
                    "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8],
                    "className": "align-middle",
                },
                {
                    "targets": [0, 3, 7],
                    "orderable": false,
                },
                {
                    "targets": [0, 3, 4, 5, 6, 8],
                    "className": "text-center",
                },
                {
                    "targets": [6],
                    "render": function(data, type, row) {
                        if (row.status == 1 || row.status == '1') {
                            return '<span class="badge rounded-pill bg-success">Publish</span>';
                        } else {
                            return '<span class="badge rounded-pill bg-danger">Hidden</span>';
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
                "data": "category"
            }, {
                "data": "title"
            }, {
                "data": "images"
            }, {
                "data": "author"
            }, {
                "data": "viewers"
            }, {
                "data": "status"
            }, {
                "data": "created_time"
            }, {
                "data": "options"
            }]
        });

        // custom filter
        $("#filterTableListPostByCategory").on('change', function() {
            tableListPost.draw();
        });
        $("#filterTableListPostByAuthor").on('change', function() {
            tableListPost.draw();
        });
        $("#filterTableListPostByStatus").on('change', function() {
            tableListPost.draw();
        });

        // delete
        $(document).on('click', ".button_delete_post", function() {
            // CSRF Hash
            let csrfName = $("#csrfProtection").attr('name'); // CSRF Token name
            let csrfHash = $("#csrfProtection").val(); // CSRF hash

            let slug = $(this).attr('id');
            if (confirm('<?= lang('Pages.post.confirm_delete_post'); ?>')) {
                $.ajax({
                    url: base_url + "admin/post/delete",
                    method: "POST",
                    dataType: "JSON",
                    cache: false,
                    data: {
                        slug: slug,
                        [csrfName]: csrfHash
                    },
                    success: function(response) {
                        // Update CSRF hash
                        $("#csrfProtection").val(response.token);

                        if (response.status === true) {
                            $("#toast_message_success").text(response.message);
                            toast_success.show();
                            tableListPost.draw();
                        } else {
                            $("#toast_message_error").text(response.message);
                            toast_failed.show();
                        }
                    },
                    error: function(xhr, status, error) {
                        // alert(xhr.responseText);
                        alert('<?= lang('Validation.failed_to_delete_post'); ?>');
                    }
                });
            }
        });
    });
</script>
<?= $this->endSection(); ?>