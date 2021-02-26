<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$uri = $this->uri->segment(2);
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

        <link rel="shortcut icon" href="<?= base_url('assets_admin/images/favicon/favicon.ico') ?>" type="image/x-icon">
        <link rel="icon" href="<?= base_url('assets_admin/images/favicon/favicon.ico') ?>" type="image/x-icon">
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
        <script src="<?= base_url('assets_admin/js/jquery-1.11.0.min.js') ?>"></script>
    </head>
    <body class="page-body">
        <div class="page-container">
            <!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->
            <div class="sidebar-menu">
                <div class="sidebar-menu-inner">

                    <header class="logo-env">
                        <!-- logo -->
                        <div class="logo">
                            <a href="<?= site_url('admin/index') ?>">
                                <img src="<?= base_url('assets_admin/images/logo-text.png') ?>" width="120" alt="" />
                            </a>
                        </div>

                        <!-- logo collapse icon -->
                        <div class="sidebar-collapse">
                            <a href="#" class="sidebar-collapse-icon"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
                                <i class="entypo-menu"></i>
                            </a>
                        </div>


                        <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
                        <div class="sidebar-mobile-menu visible-xs">
                            <a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
                                <i class="entypo-menu"></i>
                            </a>
                        </div>
                    </header>

                    <ul id="main-menu" class="main-menu">
                        <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
                        <li class="<?php if ($uri == '' || $uri == 'index') { echo 'active'; } ?>">
                            <a href="<?= base_url('admin/index') ?>">
                                <i class="entypo-monitor"></i>
                                <span class="title">Dashboard</span>
                            </a>
                        </li>
                        <li class="<?php if ($uri == 'users') { echo 'active'; } ?>">
                            <a href="<?= base_url('admin/users') ?>">
                                <i class="entypo-users"></i>
                                <span class="title">Users</span>
                            </a>
                        </li>
                        <li class="<?php if ($uri == 'pre_request') { echo 'active'; } ?>">
                            <a href="#">
                                <i class="entypo-credit-card"></i>
                                <span class="title">Pre-Qual Request</span>
                            </a>
                        </li>
                        <li class="<?php if ($uri == 'refinance_request') { echo 'active'; } ?>">
                            <a href="#">
                                <i class="entypo-paypal"></i>
                                <span class="title">Refinance Request</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="main-content">
                <div class="row">
                    <!-- Profile Info and Notifications -->
                    <div class="col-md-6 col-sm-8 clearfix">
                        <ul class="user-info pull-left pull-none-xsm">
                            <!-- Profile Info -->
                            <li class="profile-info dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="<?= base_url('assets_admin/images/logo.png') ?>" alt="" class="img-circle" width="44" />
                                    Admin
                                </a>

                                <ul class="dropdown-menu">
                                    <!-- Reverse Caret -->
                                    <li class="caret"></li>
                                    <!-- Profile sub-links -->
                                    <li>
                                        <a href="<?= site_url('admin/change_password') ?>">
                                            <i class="entypo-eye"></i>
                                            Change Password
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= site_url('auth/logout') ?>">
                                            <i class="entypo-logout"></i>
                                            Logout
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <!-- Raw Links -->
                    <div class="col-md-6 col-sm-4 clearfix hidden-xs">
                        <ul class="list-inline links-list pull-right">
                            <li class="sep"></li>
                            <li>
                                <a href="<?= site_url('auth/logout') ?>">
                                    Log Out <i class="entypo-logout right"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <hr />

                <?php
                if (isset($content) && $content) {
                    $this->load->view($content);
                }
                ?>

                <br />

                <!-- Footer -->
                <footer class="main">
                    Copyright &copy; <?= date('Y') ?> <a class="" target="_blank" href="#">Mortgage4U.COM</a>. All Rights Reserved.
                </footer>
            </div>
        </div>

        <!-- Bottom scripts (common) -->
        <script src="<?= base_url('assets_admin/js/gsap/main-gsap.js') ?>"></script>
        <script src="<?= base_url('assets_admin/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js') ?>"></script>
        <script src="<?= base_url('assets_admin/js/bootstrap.js') ?>"></script>
        <script src="<?= base_url('assets_admin/js/joinable.js') ?>"></script>
        <script src="<?= base_url('assets_admin/js/resizeable.js') ?>"></script>
        <script src="<?= base_url('assets_admin/js/neon-api.js') ?>"></script>

        <!-- Imported scripts on this page -->
        <script src="<?= base_url('assets_admin/js/toastr.js') ?>"></script>

        <!-- Date Picket -->
        <script src="<?= base_url('assets_admin/js/bootstrap-datepicker.js') ?>"></script>
        <!-- typeahead -->
        <script src="<?= base_url('assets_admin/js/jquery.typeahead.min.js'); ?>"></script>
        <script src="<?= base_url('assets_admin/js/typeahead.js'); ?>"></script>

        <!-- wait me -->
        <script src="<?= base_url('assets_admin/js/waitMe.min.js'); ?>"></script>
        <script src="<?= base_url('assets_admin/js/waitMe.js'); ?>"></script>

        <!-- JavaScripts initializations and stuff -->
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
    </body>
</html>
