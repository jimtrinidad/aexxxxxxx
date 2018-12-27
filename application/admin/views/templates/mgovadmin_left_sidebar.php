<section class="sidebar">
  <!-- Sidebar user panel (optional) -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="<?php echo public_url('assets/profile/') . $accountInfo->Photo; ?>" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
      <p><?php echo user_full_name($accountInfo, false); ?></p>
      <!-- Status -->
      <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
  </div>
  <!-- /.search form -->
  <!-- Sidebar Menu -->
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">Main Menu</li>
    <!-- Optionally, you can add icons to the links -->
    <li><a href="#"><i class="fa fa-link"></i> <span>Dashboard</span></a></li>
    <li class="active treeview">
      <a href="#"><i class="fa fa-link"></i> <span>Settings</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="<?php echo base_url('accounts') ?>"><i class="fa fa-link"></i> Accounts</a></li>
        <li><a href="<?php echo base_url('department') ?>"><i class="fa fa-link"></i> Department</a></li>
        <li><a href="<?php echo base_url('department/officers') ?>"><i class="fa fa-link"></i> Dept Location & Officers</a></li>
        <li><a href="<?php echo base_url('services') ?>"><i class="fa fa-link"></i> Services</a></li>
        <li><a href="<?php echo base_url('documents') ?>"><i class="fa fa-link"></i> Documents</a></li>
        <li><a href="<?php echo base_url('zones') ?>"><i class="fa fa-link"></i> Zones</a></li>
      </ul>
    </li>
  </ul>
<!-- /.sidebar-menu -->
</section>