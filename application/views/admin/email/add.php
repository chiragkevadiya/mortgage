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
                echo form_open('email/save_email', $attributes);
                ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Subject *</label>
                            <input type="text" name="subject" id="subject" value="<?php echo isset($row) ? $row->subject : ''; ?>" class="form-control">
                            <div class="text-danger" id="error_subject"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Email Text *</label>
                            <textarea name="message" id="message" class="form-control"><?php echo isset($row) ? $row->message : ''; ?></textarea>
                            <div class="text-danger" id="error_message"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="hidden" name="id" id="id" value="<?php echo isset($row) ? $row->id : ''; ?>">
                            <button name="submit_btn" id="submit_btn" type="submit"  class="btn btn-success btn-sm">Save</button>
                        </div>
                    </div>
                </div>
                
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    CKEDITOR.replace('message', {
        filebrowserUploadUrl: "<?php echo site_url('news/insert_file') ?>",
        height: 400
    });

    $('#data_form').submit(function (e) {
        CKEDITOR.instances['message'].updateElement();
        $('#submit_btn').attr('disabled', 'disabled').html('Save');
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('email/save_email') ?>',
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