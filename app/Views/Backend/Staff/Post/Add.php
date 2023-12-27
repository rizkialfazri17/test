<?= $this->extend('Backend/Layout/Main'); ?>

<?= $this->section('MainContent'); ?>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Add New Post</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url("/admin/dashboard"); ?>">Home</a></li>
                <li class="breadcrumb-item">Staff</li>
                <li class="breadcrumb-item"><a href="<?= base_url("/admin/manage-post"); ?>">Manage Post</a></li>
                <li class="breadcrumb-item active">Add New Post</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body pt-4">
                        <form id="form_add_post" novalidate>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="post_category" class="form-label">*Post Category</label>
                                    <select id="post_category" name="post_category" class="form-select shadow-none" required="required">
                                        <?php foreach ($getListCategory as $row) : ?>
                                            <option value="<?= $row['name']; ?>"><?= $row['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback post_category"></div>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="post_title" class="form-label">*Post Title</label>
                                    <input type="text" class="form-control shadow-none" id="post_title" name="post_title" maxlength="255" required="required" />
                                    <div class="invalid-feedback post_title"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="post_description" class="form-label">*Post Description</label>
                                    <input type="text" class="form-control shadow-none" id="post_description" name="post_description" maxlength="255" required="required" />
                                    <div class="invalid-feedback post_description"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="post_content" class="form-label">Post Content</label>
                                    <textarea class="text-editor" id="post_content" name="post_content"></textarea>
                                    <div class="invalid-feedback post_content"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="post_status" class="form-label">*Post Status</label>
                                    <select id="post_status" name="post_status" class="form-select shadow-none" required="required">
                                        <option value="0">Hidden</option>
                                        <option value="1">Publish</option>
                                    </select>
                                    <div class="invalid-feedback post_status"></div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="post_is_view" class="form-label">*Show Viewers</label>
                                    <select id="post_is_view" name="post_is_view" class="form-select shadow-none" required="required">
                                        <option value="0">Hidden</option>
                                        <option value="1">Show</option>
                                    </select>
                                    <div class="invalid-feedback post_is_view"></div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="post_author" class="form-label">*Author</label>
                                    <input type="text" class="form-control shadow-none" id="post_author" name="post_author" value="<?= session()->get('name'); ?>" maxlength="24" required="required" />
                                    <div class="invalid-feedback post_author"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="post_images" class="form-label">Post Images</label>
                                    <img style="display: block; margin-bottom: 10px;" id="review_image" alt="Post Image" width="120" height="75" hidden />
                                    <input type="file" class="form-control shadow-none" id="post_images" name="post_images" />
                                    <div class="invalid-feedback post_images"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-4 mt-2 mb-3">
                                    <input type="hidden" id="csrfProtection" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                    <button type="submit" class="btn btn-primary rounded-pill shadow-none mb-3" id="button_add_post" style="width: 150px;">Add New Post</button>
                                    <a href="javascript:window.history.back();" class="btn btn-outline-secondary rounded-pill shadow-none mb-3" style="width: 150px;">Back</a>
                                </div>
                                <div class="col-xl-8">
                                    <div id="alert_validation_add_post" class="alert text-center" role="alert" hidden>
                                        <span id="alert_message_add_post"></span>
                                    </div>
                                </div>
                            </div>
                        </form>
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
        $("#post_images").on('change', function() {
            let image_url = window.URL.createObjectURL(this.files[0]);
            $("#review_image").removeAttr('hidden').attr('src', image_url);
        });

        // add
        $("#form_add_post").on('submit', function(e) {
            e.preventDefault();
            const formAddPostData = new FormData(this);
            $.ajax({
                url: base_url + "admin/manage-post/save",
                method: "POST",
                dataType: "JSON",
                data: formAddPostData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#button_add_post").attr("disabled", 'disabled').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
                    $(".form-control").removeClass('is-invalid');
                    $(".invalid-feedback").html('');
                },
                complete: function() {
                    $("#button_add_post").removeAttr("disabled", 'disabled').text('Add New Post');
                },
                success: function(response) {
                    // CSRF callback update token
                    $("#csrfProtection").val(response.token);

                    if (response.status === true) {
                        $("#alert_message_add_post").html(response.message);
                        $("#alert_validation_add_post").fadeIn(500).removeClass('alert-danger').addClass('alert-success').removeAttr("hidden", 'hidden');
                        setInterval(() => {
                            window.location.href = base_url + "admin/manage-post";
                        }, 2500);
                    } else {
                        if (response.message instanceof Object) {
                            $.each(response.message, function(field, params) {
                                $("#" + field).addClass('is-invalid');
                                $("." + field).html('<i class="bi bi-exclamation-octagon"></i> ' + params);
                            });
                        } else {
                            $("#alert_message_add_post").html(response.message);
                            $("#alert_validation_add_post").fadeIn(500).addClass('alert-danger').removeAttr("hidden", 'hidden').delay(2500).fadeOut(500);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    // alert(xhr.responseText);
                    alert('Failed to add data, please contact developer!');
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>