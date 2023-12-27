<?= $this->extend('Backend/Layout/Main'); ?>

<?= $this->section('MainContent'); ?>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Edit Post</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url("/admin/dashboard"); ?>">Home</a></li>
                <li class="breadcrumb-item">Staff</li>
                <li class="breadcrumb-item"><a href="<?= base_url("/admin/manage-post"); ?>">Manage Post</a></li>
                <li class="breadcrumb-item active">Edit Post</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Post : <?= $getPost['title']; ?></h5>

                        <form id="form_edit_post" novalidate>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="post_category" class="form-label">*Post Category</label>
                                    <select id="post_category" name="post_category" class="form-select shadow-none" required="required">
                                        <?php foreach ($getListCategory as $row) : ?>
                                            <option value="<?= $row['name']; ?>" <?= ($row['name'] == $getPost['category'] ? 'selected' : ''); ?>><?= $row['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback post_category"></div>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="post_title" class="form-label">*Post Title</label>
                                    <input type="text" class="form-control shadow-none" id="post_title" name="post_title" value="<?= $getPost['title']; ?>" maxlength="255" required="required" />
                                    <div class="invalid-feedback post_title"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="post_description" class="form-label">*Post Description</label>
                                    <input type="text" class="form-control shadow-none" id="post_description" name="post_description" value="<?= $getPost['description']; ?>" maxlength="255" required="required" />
                                    <div class="invalid-feedback post_description"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="post_content" class="form-label">Post Content</label>
                                    <textarea class="text-editor" id="post_content" name="post_content"><?= $getPost['content']; ?></textarea>
                                    <div class="invalid-feedback post_content"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="post_status" class="form-label">*Post Status</label>
                                    <select id="post_status" name="post_status" class="form-select shadow-none" required="required">
                                        <option value="0" <?= ($getPost['status'] == 0 ? 'selected' : ''); ?>>Hidden</option>
                                        <option value="1" <?= ($getPost['status'] == 1 ? 'selected' : ''); ?>>Publish</option>
                                    </select>
                                    <div class="invalid-feedback post_status"></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="post_is_view" class="form-label">*Show Viewers</label>
                                            <select id="post_is_view" name="post_is_view" class="form-select shadow-none" required="required">
                                                <option value="0" <?= ($getPost['is_view'] == 0 ? 'selected' : ''); ?>>Hidden</option>
                                                <option value="1" <?= ($getPost['is_view'] == 1 ? 'selected' : ''); ?>>Show</option>
                                            </select>
                                            <div class="invalid-feedback post_is_view"></div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="post_viewers" class="form-label">Post Viewers</label>
                                            <input type="number" class="form-control shadow-none" id="post_viewers" name="post_viewers" value="<?= numberFormat($getPost['viewers']); ?>" pattern="[0-9]" required="required" />
                                            <div class="invalid-feedback post_viewers"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="post_author" class="form-label">*Author</label>
                                    <input type="text" class="form-control shadow-none" id="post_author" name="post_author" value="<?= $getPost['author']; ?>" maxlength="24" required="required" />
                                    <div class="invalid-feedback post_author"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="post_images" class="form-label">Post Images</label>
                                    <?php
                                    if (!empty($getPost['images'])) {
                                        $getPostImages = 'assets/images/post/' . $getPost['images'];

                                        if (file_exists($getPostImages)) {
                                            echo '<img style="display: block; margin-bottom: 10px;" id="review_image" src="' . base_url('/assets/images/post/' . $getPost['images']) . '" alt="' . $getPost['title'] . '" width="120" height="75" />';
                                        } else {
                                            echo '<img style="display: block; margin-bottom: 10px;" id="review_image" src="' . base_url('/assets/images/post/default.png') . '" alt="' . $getPost['title'] . '" width="120" height="75" />';
                                        }
                                    } else {
                                        echo '<img style="display: block; margin-bottom: 10px;" id="review_image" src="' . base_url('/assets/images/post/default.png') . '" alt="' . $getPost['title'] . '" width="120" height="75" />';
                                    } ?>
                                    <input type="file" class="form-control shadow-none" id="post_images" name="post_images" />
                                    <div class="invalid-feedback post_images"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="created_time" class="form-label">Time to Create Post</label>
                                    <input type="text" class="form-control shadow-none" value="<?= date('l, d F Y - h:i:s A', strtotime($getPost['created_time'])) . ' [' . getTimeAgo(date('d-m-Y H:i:s', strtotime($getPost['created_time'])), date('d-m-Y H:i:s')) . ']'; ?>" disabled="disabled" />
                                </div>
                                <?php if (!empty($getPost['updated_time']) || $getPost['updated_time'] != NULL || $getPost['updated_time'] != '') : ?>
                                    <div class="col-md-6 mb-3">
                                        <label for="updated_time" class="form-label">Last Post Updated</label>
                                        <input type="text" class="form-control shadow-none" value="<?= date('l, d F Y - h:i:s A', strtotime($getPost['updated_time'])) . ' [' . getTimeAgo(date('d-m-Y H:i:s', strtotime($getPost['updated_time'])), date('d-m-Y H:i:s')) . ']'; ?>" disabled="disabled" />
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="row">
                                <div class="col-xl-4 mt-2 mb-3">
                                    <input type="hidden" id="post_slug" name="post_slug" value="<?= $getPost['slug']; ?>" />
                                    <input type="hidden" id="csrfProtection" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                    <button type="submit" class="btn btn-success rounded-pill shadow-none mb-3" id="button_edit_post" style="width: 150px;">Save Changes</button>
                                    <a href="javascript:window.history.back();" class="btn btn-outline-secondary rounded-pill shadow-none mb-3" style="width: 150px;">Back</a>
                                </div>
                                <div class="col-xl-8">
                                    <div id="alert_validation_edit_post" class="alert text-center" role="alert" hidden>
                                        <span id="alert_message_edit_post"></span>
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

        // update
        $("#form_edit_post").on('submit', function(e) {
            e.preventDefault();
            const formEditPostData = new FormData(this);
            $.ajax({
                url: base_url + "admin/manage-post/update",
                method: "POST",
                dataType: "JSON",
                data: formEditPostData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#button_edit_post").attr("disabled", 'disabled').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
                    $(".form-control").removeClass('is-invalid');
                    $(".invalid-feedback").html('');
                },
                complete: function() {
                    $("#button_edit_post").removeAttr("disabled", 'disabled').text('Save Changes');
                },
                success: function(response) {
                    // CSRF callback update token
                    $("#csrfProtection").val(response.token);

                    if (response.status === true) {
                        $("#alert_message_edit_post").html(response.message);
                        $("#alert_validation_edit_post").fadeIn(500).removeClass('alert-danger').removeClass('alert-info').addClass('alert-success').removeAttr("hidden", 'hidden');
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
                            $("#alert_message_edit_post").html(response.message);
                            if (response.message == 'Nothing has been changed!') {
                                $("#alert_validation_edit_post").fadeIn(500).removeClass('alert-danger').addClass('alert-info').removeAttr("hidden", 'hidden').delay(2500).fadeOut(500);
                            } else {
                                $("#alert_validation_edit_post").fadeIn(500).removeClass('alert-info').addClass('alert-danger').removeAttr("hidden", 'hidden').delay(2500).fadeOut(500);
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