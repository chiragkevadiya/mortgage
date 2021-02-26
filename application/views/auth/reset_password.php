<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Admin Panel</title>
        <!-- base:css -->
        <link rel="stylesheet" href="<?= base_url('assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/vendors/base/vendor.bundle.base.css') ?>">
        <!-- endinject -->
        <!-- plugin css for this page -->
        <!-- End plugin css for this page -->
        <!-- inject:css -->
        <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/css/toast.css') ?>">
        <!-- endinject -->
        <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.png') ?>" />
    </head>

    <body>
        <div id="overlay">
            <div class="cv-spinner">
                <span class="spinner"></span>
            </div>
        </div>

        <div class="container-scroller">
            <div class="container-fluid page-body-wrapper full-page-wrapper">
                <div class="main-panel">
                    <div class="content-wrapper d-flex align-items-center auth px-0">
                        <div class="row w-100 mx-0">
                            <div class="col-lg-4 mx-auto">
                                <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                                    <div class="brand-logo">
                                        <h2 class="card-title text-primary text-center">CHANGE PASSWORD</h2>
                                    </div>

                                    <?= form_open('auth/save_reset_password/' . $code, array('name' => 'login_form', 'id' => 'login_form', 'class' => 'pt-3')); ?>
                                    <div class="form-group">
                                        <input type="password" name="new" class="form-control" id="new" placeholder="New Password">
                                        <span class="text-danger text-small" id="error_new"></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="new_confirm" class="form-control" id="new_confirm" placeholder="Confirm Password">
                                        <span class="text-danger text-small" id="error_new_confirm"></span>
                                    </div>
                                    <div class="mt-3">
                                        <?php echo form_input($user_id); ?>
                                        <?php echo form_hidden($csrf); ?>
                                        <button type="submit" id="login_btn" class="btn btn-primary btn-block font-weight-medium auth-form-btn">SAVE</button>
                                    </div>
                                    <?= form_close(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <!-- container-scroller -->
        <!-- base:js -->
        <script src="<?= base_url('assets/vendors/base/vendor.bundle.base.js') ?>"></script>
        <script src="<?= base_url('assets/js/toast.js') ?>"></script>
        <!-- endinject -->

        <!-- inject:js -->
        <script src="<?= base_url('assets/js/template.js') ?>"></script>
        <!-- endinject -->

        <script type="text/javascript">
            $('#login_form').submit(function (e) {
                e.preventDefault();
                $('#login_btn').attr('disabled', 'disabled').html('<i class="mdi mdi-spin mdi-star"></i> SAVE');
                var url = '<?= site_url('auth/save_reset_password/' . $code) ?>';
                var data = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'POST',
                    beforeSend: function () {
                        $("#overlay").show();
                    },
                    data: data,
                    dataType: 'json',
                    success: function (res)
                    {
                        console.log(res);
                        $('#login_btn').removeAttr('disabled').html('SAVE');
                        if (res.status)
                        {
                            show_toast(res.message, true);
                            $("#login_form")[0].reset();
                            var error = res.data.error;
                            $.each(error, function (i, val)
                            {
                                $('#error_' + i).html(val);
                            });
                        } else
                        {
                            var error = res.data.error;
                            $.each(error, function (i, val)
                            {
                                $('#error_' + i).html(val);
                            });

                            if (res.data.message != '') {
                                show_toast(res.data.message, false);
                            }
                        }
                    },
                    error: function (xhr) {
                        show_toast(xhr.statusText + xhr.responseText, false);
                    },
                    complete: function () {
                        $("#overlay").hide();
                    }
                });
            });
        </script>
    </body>
</html>