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
                echo form_open('terms/save_terms', $attributes);
                ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <textarea name="terms_and_conditions" id="terms_and_conditions" class="form-control"><?php echo isset($row) ? $row->terms_and_conditions : ''; ?></textarea>
                            <div class="text-danger" id="error_terms_and_conditions"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="hidden" name="id" id="id" value="<?php echo isset($row) ? $row->id : ''; ?>">
                            <button name="submit_btn" id="submit_btn" type="submit"  class="btn btn-success btn-sm">Update</button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    CKEDITOR.replace('terms_and_conditions', {
        filebrowserUploadUrl: "<?php echo site_url('news/insert_file') ?>",
        height: 560
    });

    $('#data_form').submit(function (e) {
        CKEDITOR.instances['terms_and_conditions'].updateElement();
        $('#submit_btn').attr('disabled', 'disabled').html('Update');
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('terms/save_terms') ?>',
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
                $('#submit_btn').removeAttr('disabled').html('Update');
                if (res.status)
                {
                    show_toast(res.message, true);
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