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
                echo form_open('settings/save_settings', $attributes);
                ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Your Picture <span class="text-danger">* Image size should be 256 X 256</span></label>
                            <input type="file" name="image" id="image" class="form-control">
                            <div class="text-danger" id="error_image"></div>
                        </div>

                        <div class="col-sm-3">
                            <label>Name *</label>
                            <input type="text" name="name" id="name" value="<?php echo isset($row) ? $row->name : ''; ?>" class="form-control">
                            <div class="text-danger" id="error_name"></div>
                        </div>

                        <div class="col-sm-3">
                            <label>Designation *</label>
                            <input type="text" name="designation" id="designation" value="<?php echo isset($row) ? $row->designation : ''; ?>" class="form-control">
                            <div class="text-danger" id="error_designation"></div>
                        </div>

                        <div class="col-sm-3">
                            <label>Contact Number *</label>
                            <input type="text" name="contact" id="contact" value="<?php echo isset($row) ? $row->contact : ''; ?>" class="form-control">
                            <div class="text-danger" id="error_contact"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Email *</label>
                            <input type="text" name="email" id="email" value="<?php echo isset($row) ? $row->email : ''; ?>" class="form-control">
                            <div class="text-danger" id="error_email"></div>
                        </div>

                        <div class="col-sm-3">
                            <label>Slogan *</label>
                            <input type="text" name="slogan" id="slogan" value="<?php echo isset($row) ? $row->slogan : ''; ?>" class="form-control">
                            <div class="text-danger" id="error_slogan"></div>
                        </div>

                        <div class="col-sm-6">
                            <label>Address *</label>
                            <input type="text" name="address" id="address" value="<?php echo isset($row) ? $row->address : ''; ?>" class="form-control">
                            <div class="text-danger" id="error_address"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-10">
                            <label>Disclaimer *</label>
                            <textarea name="disclaimer" id="disclaimer" class="form-control"><?php echo isset($row) ? $row->disclaimer : ''; ?></textarea>
                            <div class="text-danger" id="error_disclaimer"></div>
                        </div>
                        <div class="col-sm-2">
                            <img class="img-thumbnail" src="<?php echo base_url(PROFILE_PICTURE_PATH . $row->profile_image) ?>" onerror="this.src='<?php echo base_url('assets_admin/images/default_image.jpg') ?>'">
                        </div>
                    </div>
                </div>

                <h4 class="page-header text-success">Social Media Links</h4>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4">
                            <label>Linkedin *</label>
                            <input type="text" name="linkedin" id="linkedin" value="<?php echo isset($row) ? $row->linkedin : ''; ?>" class="form-control">
                            <div class="text-danger" id="error_linkedin"></div>
                        </div>

                        <div class="col-sm-4">
                            <label>Google *</label>
                            <input type="text" name="google" id="google" value="<?php echo isset($row) ? $row->google : ''; ?>" class="form-control">
                            <div class="text-danger" id="error_google"></div>
                        </div>

                        <div class="col-sm-4">
                            <label>Zillow *</label>
                            <input type="text" name="zillow" id="zillow" value="<?php echo isset($row) ? $row->zillow : ''; ?>" class="form-control">
                            <div class="text-danger" id="error_zillow"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4">
                            <label>Website *</label>
                            <input type="text" name="website" id="website" value="<?php echo isset($row) ? $row->website : ''; ?>" class="form-control">
                            <div class="text-danger" id="error_website"></div>
                        </div>

                        <div class="col-sm-4">
                            <label>Apply Link *</label>
                            <input type="text" name="apply_link" id="apply_link" value="<?php echo isset($row) ? $row->apply_link : ''; ?>" class="form-control">
                            <div class="text-danger" id="error_apply_link"></div>
                        </div>
                    </div>
                </div>

                <!--<div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Description *</label>
                            <textarea name="description" id="description" class="form-control"><?php echo isset($row) ? $row->name : ''; ?></textarea>
                            <div class="text-danger" id="error_description"></div>
                        </div>
                    </div>
                </div>-->

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="hidden" name="file_name" id="file_name" value="<?php echo isset($row) ? $row->profile_image : ''; ?>">
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
    $('#data_form').submit(function (e) {
        $('#submit_btn').attr('disabled', 'disabled').html('Update');
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('settings/save_settings') ?>',
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