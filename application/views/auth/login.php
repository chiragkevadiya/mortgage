<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Neon Admin Panel" />
        <meta name="author" content="" />
        <title><?= WEB_SITE . ' - ' . $title ?></title>

        <link rel="apple-touch-icon" sizes="57x57" href="<?= base_url('assets_admin/images/favicon/apple-icon-57x57.png') ?>">
        <link rel="apple-touch-icon" sizes="60x60" href="<?= base_url('assets_admin/images/favicon/apple-icon-60x60.png') ?>">
        <link rel="apple-touch-icon" sizes="72x72" href="<?= base_url('assets_admin/images/favicon/apple-icon-72x72.png') ?>">
        <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('assets_admin/images/favicon/apple-icon-76x76.png') ?>">
        <link rel="apple-touch-icon" sizes="114x114" href="<?= base_url('assets_admin/images/favicon/apple-icon-114x114.png') ?>">
        <link rel="apple-touch-icon" sizes="120x120" href="<?= base_url('assets_admin/images/favicon/apple-icon-120x120.png') ?>">
        <link rel="apple-touch-icon" sizes="144x144" href="<?= base_url('assets_admin/images/favicon/apple-icon-144x144.png') ?>">
        <link rel="apple-touch-icon" sizes="152x152" href="<?= base_url('assets_admin/images/favicon/apple-icon-152x152.png') ?>">
        <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets_admin/images/favicon/apple-icon-180x180.png') ?>">
        <link rel="icon" type="image/png" sizes="192x192"  href="<?= base_url('assets_admin/images/favicon/android-icon-192x192.png') ?>">
        <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets_admin/images/favicon/favicon-32x32.png') ?>">
        <link rel="icon" type="image/png" sizes="96x96" href="<?= base_url('assets_admin/images/favicon/favicon-96x96.png') ?>">
        <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets_admin/images/favicon/favicon-16x16.png') ?>">
        <link rel="manifest" href="<?= base_url('assets_admin/images/favicon/manifest.json') ?>">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="<?= base_url('assets_admin/images/favicon/ms-icon-144x144.png') ?>">
        <meta name="theme-color" content="#ffffff">

        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
        <link rel="stylesheet" href="<?= base_url('assets_admin/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets_admin/css/font-icons/entypo/css/entypo.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets_admin/css/font-icons/font-awesome/css/font-awesome.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets_admin/css/bootstrap.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets_admin/css/neon-core.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets_admin/css/neon-theme.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets_admin/css/neon-forms.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets_admin/css/custom.css') ?>">

        <!-- wait me -->
        <link rel="stylesheet" href="<?= base_url('assets_admin/css/waitMe.css'); ?>">

        <script type="text/javascript" src="<?= base_url('assets_admin/js/jquery-1.11.0.min.js') ?>"></script>
    </head>
    <body class="page-body login-page login-form-fall">
        <div class="login-container">
            <div class="login-header login-caret">
                <div class="login-content">
                    <a href="<?= base_url() ?>" class="logo">
                        <img src="<?= base_url('assets_admin/images/logo.png') ?>" width="" alt="" />
                    </a>
                </div>
            </div>

            <div class="login-form">
                <div class="login-content">
                    <?= form_open("auth/do_login", array('name' => 'login_form', 'id' => 'login_form')); ?>
                    <div class="form-group">

                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="entypo-user"></i>
                            </div>
                            <input type="text" class="form-control" name="identity" id="identity" placeholder="Username" autocomplete="off" />
                        </div>

                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="entypo-key"></i>
                            </div>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="off" />
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block btn-login">
                            <i class="entypo-login"></i>
                            Login In
                        </button>
                    </div>
                    <?= form_close() ?>


                    <footer class="main text-muted">
                        Copyright &copy; <?= date('Y') ?> <a class="" target="_blank" href="#">Mortgage4U.com</a> <br />All Rights Reserved.
                    </footer>

                </div>
            </div>
        </div>

        <!-- Bottom scripts (common) -->
        <script src="<?= base_url('assets_admin/js/gsap/main-gsap.js') ?>"></script>
        <script src="<?= base_url('assets_admin/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js') ?>"></script>
        <script src="<?= base_url('assets_admin/js/bootstrap.js') ?>"></script>
        <script src="<?= base_url('assets_admin/js/joinable.js') ?>"></script>
        <script src="<?= base_url('assets_admin/js/resizeable.js') ?>"></script>
        <script src="<?= base_url('assets_admin/js/neon-api.js') ?>"></script>
        <script src="<?= base_url('assets_admin/js/jquery.validate.min.js') ?>"></script>
        <script src="<?= base_url('assets_admin/js/neon-login.js') ?>"></script>

        <!-- Imported scripts on this page -->
        <script src="<?= base_url('assets_admin/js/toastr.js') ?>"></script>

        <!-- wait me -->
        <script src="<?= base_url('assets_admin/js/waitMe.min.js'); ?>"></script>
        <script src="<?= base_url('assets_admin/js/waitMe.js'); ?>"></script>

        <!-- initializations and stuff -->
        <script src="<?= base_url('assets_admin/js/neon-custom.js') ?>"></script>

        <!-- Demo Settings -->
        <script src="<?= base_url('assets_admin/js/neon-demo.js') ?>"></script>

        <script type="text/javascript">
            /** overlay */
            function run_waitMe() {
                $('body').waitMe({
                    effect: 'ios',
                    text: 'Please waiting...',
                    bg: 'rgba(255,255,255,0.7)',
                    color: '#000',
                    maxSize: '',
                    waitTime: -1,
                    source: '',
                    textPos: 'vertical',
                    fontSize: '',
                    onClose: function () {}
                });
            }

            /** hide overlay */
            function hide_waitMe() {
                $('body').waitMe('hide');
            }

            var options = {
                "closeButton": true,
                "debug": false,
                "positionClass": "toast-bottom-left",
                "onclick": null,
                "showDuration": "500",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            function show_toast(message, status) {
                if (status) {
                    toastr.success(message, "Success", options);
                } else {
                    toastr.error(message, "Something wen't wrong", options);
                }
            }
        </script>

        <script type="text/javascript">
            $('#login_form').submit(function (e) {
                e.preventDefault();
                $('#login_btn').attr('disabled', 'disabled').html('<i class="fa fa-spin fa-spinner"></i> SIGN IN');
                var url = '<?= site_url('auth/do_login') ?>';
                var data = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'POST',
                    beforeSend: function () {
                        run_waitMe()
                    },
                    data: data,
                    dataType: 'json',
                    success: function (res)
                    {
                        console.log(res);
                        $('#login_btn').removeAttr('disabled').html('SIGN IN');
                        if (res.status)
                        {
                            show_toast(res.message, true);
                            setTimeout(function () {
                                window.location.href = res.redirect;
                            }, 1000);
                        } else
                        {
                            show_toast(res.message, false);
                        }
                    },
                    error: function (xhr) {
                        show_toast(xhr.statusText + xhr.responseText, false);
                    },
                    complete: function () {
                        hide_waitMe()
                    }
                });
            });
        </script>
    </body>
</html>
