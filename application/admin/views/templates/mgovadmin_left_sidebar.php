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
    <li class="<?php echo (is_current_url('dashboard', 'index') ? 'active' : ''); ?>"><a href="<?php echo site_url() ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
    <li class="treeview <?php echo (is_setting_page() ? 'active' : ''); ?>">
      <a href="#"><i class="fa fa-cogs"></i> <span>Settings</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li class="<?php echo (is_current_url('accounts') ? 'active' : ''); ?>"><a href="<?php echo site_url('accounts') ?>"><i class="fa fa-users"></i> Accounts</a></li>
        <li class="<?php echo (is_current_url('department', 'index') ? 'active' : ''); ?>"><a href="<?php echo site_url('department') ?>"><i class="fa fa-university"></i> Department</a></li>
        <li class="<?php echo (is_current_url('department', 'officers') ? 'active' : ''); ?>"><a href="<?php echo site_url('department/officers') ?>"><i class="fa fa-sitemap"></i> Activation of mGov Locations</a></li>
        <li class="<?php echo (is_current_url('services') ? 'active' : ''); ?>"><a href="<?php echo site_url('services') ?>"><i class="fa fa-exchange"></i> Services</a></li>
        <li class="<?php echo (is_current_url('documents') ? 'active' : ''); ?>"><a href="<?php echo site_url('documents') ?>"><i class="fa fa-file-text"></i> Digital Documents</a></li>
        <li class="<?php echo (is_current_url('zones') ? 'active' : ''); ?>"><a href="<?php echo site_url('zones') ?>"><i class="fa fa-globe"></i> Zones</a></li>
      </ul>
    </li>
  </ul>
<!-- /.sidebar-menu -->
</section>