<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title><?php echo $title; ?></title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<!-- stylesheets -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/common/css/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/common/css/message.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/common/css/style_fixed_full.css" media="screen" />
	
	<!-- scripts (jquery) -->
	<script src="<?php echo base_url(); ?>/common/js/jquery-1.4.2.min.js" type="text/javascript"></script>
	<!--[if IE]><script language="javascript" type="text/javascript" src="resources/scripts/excanvas.min.js"></script><![endif]-->
	<script src="<?php echo base_url(); ?>/common/js/jquery-ui-1.8.custom.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>/common/js/jquery.ui.selectmenu.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>/common/js/jquery.flot.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>/common/js/tiny_mce/jquery.tinymce.js" type="text/javascript"></script>
	<!-- scripts (custom) -->
	<script type="text/javascript">
		var CI = {
				BASE_URL: "<?php echo base_url();?>"
			};
	</script>
	<script src="<?php echo base_url(); ?>/common/js/smooth.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>/common/js/smooth.menu.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>/common/js/smooth.table.js" type="text/javascript"></script>

	<!-- AN -->
	<script type="text/javascript" src="<?echo base_url();?>/common/js/script.js"></script>	
	<!-- END AN -->
	
	<!-- TOAN -->
	
	<script src="<?php echo base_url(); ?>common/js/dev/employee.js"></script>
	<!-- END TOAN -->
	
	<!-- TRIEU -->
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>/common/css/dialog.css" />

  <script src="<?php echo base_url(); ?>/common/js/jquery-1.8.3.js"></script>
	<script src="<?php echo base_url(); ?>/common/js/jquery-ui.js"></script>	
	<script type="text/javascript" src="<?php echo base_url(); ?>/common/js/dialog.js"></script>
	<!-- END TRIEU -->
	
	<!-- KEVIN -->
		<script type="text/javascript" src="<?echo base_url();?>/common/js/khuong.js"></script>
	<!-- END KEVIN -->
	
</head>

<body>
  <!-- header -->
  <div id="header">
  	<div id="header-outer">
  		<!-- logo -->
  		<div id="logo">
  			<h1><a href="<?php echo base_url();?>" title="Mulodo VN Employee Management">Mulodo VN Employee Management</a></h1>
  		</div>
  		<!-- end logo -->
  		<?php  
  		$login_name = $this->session->userdata('name'); 
  		$role = $this->session->userdata('role');
  		if($role == 2) {
  			$type = ' (Super Admin) ';
  		} elseif($role == 1) {
  			$type = ' (Admin) ';
  		} else  {
  			$type = ' (User)';
  		}
  		?>
  		
  		<!-- user -->
  		<ul id="user">
  			<li class="first"><a href="<?php echo base_url().'employee/employee_detail/'.$this->session->userdata('user_id');?>" >My profile</a></li>
  			<li><a href="<?php echo base_url();?>login/logout">Logout</a></li>
  			<li class="last highlight"><?php echo $login_name.$type; ?></li>
  		</ul>
  		<!-- end user -->
  		<div id="header-inner">
  			<!-- quick -->
  			<ul id="quick">
  				<li>
  					<a href="<?php echo site_url('employee/management_panel'); ?>" title="members"><span class="normal">Manage Members</span></a>
  				</li>
  				<li>
  					<a href="<?php echo site_url('/project/listProject'); ?>" title="projects"><span class="normal">Manage Projects</span></a>
  				</li>
  			</ul>
  			<!-- end quick -->
  			<div class="corner tl"></div>
  			<div class="corner tr"></div>
  		</div>
  	</div>
  </div>
	<!-- end header -->
	<!-- content -->
	<div id="content">
		<?php echo $this->template->message(); ?>
		<?php echo $this->template->yield(); ?>
	</div>
	<!-- end content -->
	<!-- footer -->
	<div id="footer">
		<p>Copyright &copy; 2012 Mulodo VN. All Rights Reserved.</p>
	</div>
	<!-- end footer -->
</body>
</html>	
	