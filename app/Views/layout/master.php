<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Poultry Farm</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <!-- <link rel="stylsheet" href="/plugins/fontawesome-free/css/all.min.css"> -->
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <link rel="stylesheet" href="<?php echo base_url()?>/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- <link rel="stylesheet" href="/plugins/jqvmap/jqvmap.min.css"> -->
  <link rel="stylesheet" href="<?php echo base_url()?>/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
 
  <!-- <link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css"> -->
  <!-- <link rel="stylesheet" href="/plugins/summernote/summernote-bs4.min.css"> -->
  <?= $this->renderSection("style"); ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed text-sm">
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <!-- <img class="animation__shake" src="/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60"> -->
    </div>

    <?= view('layout/sidebar'); ?>
    <?= view('layout/navbar'); ?>

  

    
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->

      <!-- /.content-header -->
     
      <!-- Main content -->
      <section class="content mt-2">
        <div class="container-fluid">
          <?= $this->renderSection("content"); ?>
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="<?php echo base_url()?>/plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <!-- <script src="/plugins/jquery-ui/jquery-ui.min.js"></script> -->
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <!-- <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script> -->
  <!-- Bootstrap 4 -->
  <script src="<?php echo base_url()?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- ChartJS -->
  <!-- <script src="/plugins/chart.js/Chart.min.js"></script> -->
  <!-- Sparkline -->
  <!-- <script src="/plugins/sparklines/sparkline.js"></script> -->
  <!-- JQVMap -->
  <!-- <script src="/plugins/jqvmap/jquery.vmap.min.js"></script> -->
  <!-- <script src="/plugins/jqvmap/maps/jquery.vmap.usa.js"></script> -->
  <!-- jQuery Knob Chart -->
  <!-- <script src="/plugins/jquery-knob/jquery.knob.min.js"></script> -->
  <!-- daterangepicker -->
  <!-- <script src="/plugins/moment/moment.min.js"></script> -->
  <!-- Tempusdominus Bootstrap 4 -->
  <!-- <script src="/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script> -->
  <!-- Summernote -->
  <!-- <script src="/plugins/summernote/summernote-bs4.min.js"></script> -->
  <!-- overlayScrollbars -->
  <script src="<?php echo base_url()?>/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo base_url()?>/dist/js/adminlte.js"></script>
  <script src="<?php echo base_url()?>/dist/js/demo.js"></script>
  <script src="<?php echo base_url()?>/vue/bundle.js"></script>
  <?= $this->renderSection("script"); ?>
</body>

</html>