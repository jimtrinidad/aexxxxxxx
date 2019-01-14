<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo TITLE_PREFIX . $pageTitle ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="<?php echo public_url(); ?>resources/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo public_url(); ?>resources/images/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.4.1/css/ionicons.min.css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/css/AdminLTE.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/css/skins/skin-blue.min.css" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap-editable/css/bootstrap-editable.css" />

    <link rel="stylesheet" href="<?php echo public_url(); ?>resources/css/formwizard.css">
    <link rel="stylesheet" href="<?php echo public_url(); ?>resources/css/site.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <style type="text/css">

        .main-header {
          background: #e6e6e7;
        }

        .skin-blue .main-header .navbar .nav>li>a {
            color: #5f6060;
        }
        .skin-blue .main-header .navbar .sidebar-toggle {
            color: #5f6060;
        }

        .skin-blue .main-header .logo:hover {
            background-color: #e6e6e7;
        }

        .skin-blue .main-header .logo {
            background-color: #e6e6e7;
            color: #5f6060;
        }

        .skin-blue .main-header .navbar {
            background-color: #e6e6e7;
        }

        .skin-blue .main-header .navbar .sidebar-toggle:hover {
            background-color: #e6e6e7;
        }

        .skin-blue .main-header .navbar .sidebar-toggle:hover {
            color: #5f6060;
        }

        .main-sidebar .user-panel{
            background: #0d2f5d;
        }

        .skin-blue .wrapper, .skin-blue .main-sidebar, .skin-blue .left-side {
            background-color: #d9d9da;
        }

        .skin-blue .sidebar-menu>li.header {
            background: #8e211f;
            color: #8e211f;
        }

        .skin-blue .sidebar-menu>li.active>a {
            border-left-color: #e2a334;
        }

        .skin-blue .sidebar-menu>li:hover>a, .skin-blue .sidebar-menu>li.active>a, .skin-blue .sidebar-menu>li.menu-open>a {
            color: #fff;
            background: #fda43c;
        }

        .skin-blue .sidebar-menu>li>.treeview-menu {
            background: #d9d9da;
        }

        .skin-blue .sidebar a {
            color: #4d5055;
        }

        .skin-blue .sidebar-menu .treeview-menu>li>a {
            color: #4d5055;
        }

        .skin-blue .sidebar-menu .treeview-menu>li.active>a, .skin-blue .sidebar-menu .treeview-menu>li>a:hover {
            color: #eaa14b;
            font-weight: bold;
        }

        .skin-blue .main-header li.user-header {
            background-color: #cfd0d0;
        }

        .navbar-nav>.user-menu>.dropdown-menu>li.user-header>p {
            color: rgba(41, 40, 40, 0.8);
        }

        .main-footer {
            background: #8e211f;
            color: #fcfbfb;
            border-top: 0;
            font-size: 17px;
        }

        .content-wrapper {

          background: #101928 url('<?php echo public_url(); ?>resources/images/admin/dashboard_04.gif') no-repeat; background-size: cover;
        }
        .content-header>h1{
          color: white;
        }
        .content-header>.breadcrumb>li>a{
          color: white;
        }
    </style>

  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <!-- Main Header -->
      <header class="main-header">
        <?php view('templates/mgovadmin_header') ?>
      </header>

      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <?php view('templates/mgovadmin_left_sidebar') ?>
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        
        <!-- Content Header (Page header) -->
        <?php if (!isset($content_header) || (isset($content_header) && $content_header)): ?> 
        <section class="content-header">
          <h1>
          <?php echo $pageTitle; ?>
          <small><?php echo isset($pageDescription) ? $pageDescription : ''; ?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo ucfirst($this->uri->segment(1)); ?></a></li>
          </ol>
        </section>
        <?php endif; // content-header ?>
        
        <!-- Main content -->
        <section class="content container-fluid">
          <?php echo $templateContent;?>
        </section>

        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <!-- Main Footer -->
      <footer class="main-footer">
        <?php view('templates/mgovadmin_footer') ?>
      </footer>

    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/js/adminlte.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fastclick/1.0.6/fastclick.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-loading-overlay/2.1.6/loadingoverlay.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.2.1/typeahead.jquery.min.js"></script>

    <script src="<?php echo public_url(); ?>resources/js/highlighter.js"></script>

    <?php view('templates/js_constants'); ?>

    <?php
      if (isset($jsModules)) {
        foreach ($jsModules as $jsModule) {
          echo '<script src="'. public_url() .'resources/js/modules/'. $jsModule .'.js?'. time() .'"></script>';
        }
      }
    ?>

    <script type="text/javascript">
      $(document).ready(function(){
        FastClick.attach(document.body);
      });
    </script>
  </body>
</html>