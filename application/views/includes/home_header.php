<!DOCTYPE html>
<html>
<head>
  <title>One Health Global Issues Ltd</title>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url('assets/images/logo-img.jpeg') ?>">
  <link rel="icon" type="image/png" href="<?php echo base_url('assets/images/logo-img.jpeg') ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
<!--===============================================================================================-->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/vendor/animate/animate.css') ?>">
<!--===============================================================================================-->  
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/vendor/css-hamburgers/hamburgers.min.css'); ?>">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/vendor/select2/select2.min.css')?>">

<!--===============================================================================================-->
  <!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/login_css/util.css'); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/login_css/main.css'); ?>"> -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/swal-forms.css'); ?>">
  <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/main_page.css'); ?>">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script>
    function showLogInModal (elem,evt) {
      evt.preventDefault();
      $("#login-modal").modal("show");
    }

    function readMore (elem,evt) {
      evt.preventDefault();
      elem = $(elem);
      elem.parent().hide();
      elem.parent().next().show();
    }

    function readLess (elem,evt) {
      evt.preventDefault();
      elem = $(elem);
      elem.parent().hide();
      elem.parent().prev().show();
    }

    function showMore (elem,evt) {
      evt.preventDefault();
      elem = $(elem);
      elem.parent().parent().hide();
      elem.parent().parent().next().show();
    }

    function showLess (elem,evt) {
      evt.preventDefault();
      elem = $(elem);
      elem.parent().parent().hide();
      elem.parent().parent().prev().show();
    }

    function showMoreAboutUs (elem,evt) {
      evt.preventDefault();
      elem = $(elem);
      elem.parent().hide();
      elem.parent().next().show();
    }

    function showLessAboutUs (elem,evt) {
      evt.preventDefault();
      elem = $(elem);
      elem.parent().hide();
      elem.parent().prev().show();
    }

    function signIn (elem,evt) {
      document.location.assign("<?php echo site_url('onehealth/sign_in'); ?>");
    }
  </script>
</head>
<body class="animated fadeInLeft" style="background: #f5fbff;">
  <nav class="navbar navbar-expand-lg navbar-light static-top  shadow animated bounceInDown" style="background: #fff;">
    <div class="container">
      <a class="navbar-brand" href="<?php echo site_url('onehealth/') ?>"><img src="<?php echo base_url('assets/images/logo.jpeg'); ?>" style="width: 50px; height: 50px; border-radius: 50%;" alt=""><span id="company-name">OneHealth&reg;</span></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon " style=""></span>
          </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active">
            <a class="nav-link" style="" href="<?php echo site_url('onehealth') ?>">Home
                  <span class="sr-only">(current)</span>
                </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" style="" href="<?php echo site_url('onehealth/about_us') ?>">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" style="" href="<?php echo site_url('onehealth/our_services') ?>">Our Services</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" style="" href="<?php echo site_url('onehealth/contact_us') ?>">Contact Us</a>
          </li>
          
          <li class="nav-item">
            <a class="btn btn-primary" style="color: #fff; font-size: 13px;" href="#" onclick="showLogInModal(this,event)">Create Your Fee Account</a>
          </li>
          
        </ul>
      </div>
    </div>
  </nav>