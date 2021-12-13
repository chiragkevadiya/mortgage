<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <!-- Twitter -->
        <meta name="twitter:site" content="<?php echo site_url() ?>">
        <meta name="twitter:creator" content="Mortgage4u">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php echo $news->title ?>">
        <meta name="twitter:description" content="<?php echo $news->title ?>">
        <meta name="twitter:image" content="<?php echo base_url(NEWS_IMAGE . $news->file_name) ?>">

        <!-- Facebook -->
        <meta property="og:title" content="<?php echo $news->title ?>">
        <meta property="og:type" content="article" />
        <meta property="og:url" content="<?php echo site_url() ?>">
        <meta property="og:image" content="<?php echo base_url(NEWS_IMAGE . $news->file_name) ?>">
        <meta property="og:description" content="<?php echo $news->title ?>">

        <!-- Meta -->
        <meta name="description" content="<?php echo $news->title ?>">
        <meta name="keywords" content="Mortgage, Mortgage4u, Mortgage Broker, Fort Funding Corp">
        <meta name="author" content="Mortgage4u">

        <title><?php echo $news->title ?></title>

        <!-- style's -->
        <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css') ?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/boxicons.min.css') ?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/magnific-popup.min.css') ?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/owl.carousel.min.css') ?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/owl.theme.default.min.css') ?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/nice-select.min.css') ?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css') ?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/responsive.css') ?>">
        <link rel="shortcut icon" href="<?php echo base_url('assets_admin/images/favicon/favicon.ico') ?>" type="image/x-icon">
        <link rel="icon" href="<?php echo base_url('assets_admin/images/favicon/favicon.ico') ?>" type="image/x-icon">
    </head>
    <body>
        <div class="one-counter-area pt-3 pb-3">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-lg-12">
                        <div class="counter-item">
                            <h3>Mortgage News</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="about" class="one-about-area">
            <div class="container">
                <div class="row m-0">
                    <div class="col-lg-12">
                        <div class="about-content">
                            <div class="one-section-title">
                                <h2><?php echo $news->title ?></h2>
                                <p><?php echo $news->description ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- required script -->
        <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-3.5.1.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/js/popper.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.magnific-popup.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/js/owl.carousel.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.nice-select.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.ajaxchimp.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/js/form-validator.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/js/contact-form-script.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.mixitup.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/js/custom.js') ?>"></script>
    </body>
</html>