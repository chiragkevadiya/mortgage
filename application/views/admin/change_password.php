<ol class="breadcrumb bc-3" >
    <li>
        <a href="<?= base_url('admin/index') ?>"><i class="fa fa-home"></i>Home</a>
    </li>
    <li class="active">
        <strong><?= $title ?></strong>
    </li>
</ol>

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-default"><strong><?= $title ?></strong></div>
    </div>
</div>

<br />

<?= form_open('admin/change_password', array('id' => 'dataform', 'class' => 'form-horizontal form-groups-bordered', 'name' => 'dataform', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-body">

                <div class="row">
                    <div class="col-md-12">
                        <?php if (isset($message) && !empty($message)) { ?>
                            <div class="alert alert-danger">
                                <?= $message; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label">Old Password <span class="text-danger">*</span></label>
                    <div class="col-sm-7">
                        <input type="password" name="old" class="form-control" placeholder="Enter Old Password">
                        <span class="text-danger" id="error_old"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label">New Password <span class="text-danger">*</span></label>
                    <div class="col-sm-7">
                        <input type="password" name="new" class="form-control" placeholder="New Password" pattern="^.{<?= $this->data['min_password_length'] ?>}.*$">
                        <span class="text-danger" id="error_new"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label">Confirm Password <span class="text-danger">*</span></label>
                    <div class="col-sm-7">
                        <input type="password" name="new_confirm" class="form-control" placeholder="Confirm Password" pattern="^.{<?= $this->data['min_password_length'] ?>}.*$">
                        <span class="text-danger" id="error_new_confirm"></span>
                    </div>
                </div>

                <?= form_input($user_id); ?>

                <div class="form-group">
                    <label class="col-md-4 control-label"></label>
                    <div class="col-sm-7">
                        <hr />
                        <button type="submit" id="submit_btn" class="btn btn-primary btn-sm">Change Password</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?= form_close(); ?>

<script type="text/javascript">
    $('#dataform').submit(function (e) {
        e.preventDefault();
        $('#submit_btn').attr('disabled', 'disabled').html('<i class="fa fa-spin fa-spinner"></i> Changing');
        var url = '<?= site_url('admin/save_password') ?>';
        var data = $(this).serialize();
        $.ajax({
            url: url,
            type: 'POST',
            beforeSend: function () {
                run_waitMe();
            },
            data: data,
            dataType: 'json',
            success: function (res)
            {
                console.log(res);
                $('#submit_btn').removeAttr('disabled').html('Change Passowrd');
                if (res.status)
                {
                    show_toast(res.message, true);
                    setTimeout(function () {
                        window.location.href = res.redirect;
                    }, 1000);
                } else
                {
                    var error = res.data.error;
                    $.each(error, function (i, val)
                    {
                        $('#error_' + i).html(val);
                    });

                    if (res.message !== '') {
                        show_toast(res.message, false);
                    }
                }
            },
            error: function (xhr) {
                show_toast(xhr.statusText + xhr.responseText, false);
            },
            complete: function () {
                hide_waitMe();
            }
        });
    });
</script>