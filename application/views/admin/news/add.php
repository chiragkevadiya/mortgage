<ol class="breadcrumb bc-3" >
    <li>
        <a href="<?php echo base_url('admin/index') ?>"><i class="fa fa-home"></i>Home</a>
    </li>
    <li class="active">
        <strong><?php echo $title ?></strong>
    </li>
</ol>

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-default"><strong><?php echo $title ?></strong></div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php
                $attributes = array('id' => 'data_form', 'name' => 'data_form', 'class' => 'form-bordered');
                echo form_open('news/save_news', $attributes);
                ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>News Title *</label>
                            <input type="text" name="title" id="title" value="<?php echo isset($row) ? $row->title : ''; ?>" class="form-control">
                            <div class="text-danger" id="error_title"></div>
                        </div>

                        <div class="col-sm-6">
                            <label>News Image <span class="text-danger">* Image size should be 1920 X 1080</span></label>
                            <input type="file" name="image" id="image" class="form-control">
                            <div class="text-danger" id="error_image"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Description *</label>
                            <textarea name="description" id="description" class="form-control"><?php echo isset($row) ? $row->description : ''; ?></textarea>
                            <div class="text-danger" id="error_description"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button name="submit_btn" id="submit_btn" type="submit"  class="btn btn-success btn-xs">Save</button>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="newsid" id="newsid" value="<?php echo isset($row) ? $row->newsid : ''; ?>">
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    CKEDITOR.replace('description', {
        filebrowserUploadUrl: "<?php echo site_url('news/insert_file') ?>",
        height: 560
    });

    $('#data_form').submit(function (e) {
        $('#submit_btn').attr('disabled', 'disabled').html('Save');
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('news/save_news') ?>',
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                run_waitMe();
            },
            success: function (res)
            {
                $('#submit_btn').removeAttr('disabled').html('Save');
                if (res.status)
                {
                    show_toast(res.message, true);
                    setTimeout(function () {
                        window.location.href = res.redirect;
                    }, 500);
                } else
                {
                    var error = res.data.error;
                    $.each(error, function (i, val)
                    {
                        $('#error_' + i).html(val);
                    });

                    if (res.message !== '') {
                        alert(res.message);
                    }
                }
            },
            error: function (xhr) {
                alert(xhr.statusText + xhr.responseText);
            },
            complete: function () {
                hide_waitMe();
            }
        });
    });
</script>