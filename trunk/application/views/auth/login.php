<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


	<head>
		<title><?php echo $title; ?></title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<!-- stylesheets -->
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/common/css/style.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/common/css/message.css" media="screen" />
		
	</head>
	<body>
		<div id="login">
			<!-- login -->
			<div class="title">
				<h5><?php echo $heading; ?></h5>
				<div class="corner tl"></div>
				<div class="corner tr"></div>
			</div>

			<div class="inner">
			<?php echo form_open("login");?>
				<div><?php echo form_error('data[login]','<div class="negative-top">','</div>'); ?></div>
				<div class="form">
					<!-- fields -->
					<div class="fields">
						<div class="field">
							<div class="label">
								<label for="username">Email:</label>
							</div>
							<div class="input">
								<input type="text" id="username" name="username" size="40" value="" />
								<div><?php echo form_error('data[username]',"<div class='negative'>",'</div>'); ?></div>
							</div>
						</div>
						<div class="field">
							<div class="label">
								<label for="password">Password:</label>
							</div>
							<div class="input">
								<input type="password" id="password" name="password" size="40" value="" />
								<div><?php echo form_error('data[password]','<div class="negative">','</div>'); ?></div>
							</div>
						</div>
						<div class="field">
							<div class="checkbox">
								<input type="checkbox" id="remember" name="remember" />
								<label for="remember">Hold the login status</label>
							</div>
						</div>
						<div class="buttons">
							<?php echo form_submit(array('id'=>'btnsignin','name'=>'login','value'=>'Log In'))?>
						</div>
					</div>
					<!-- end fields -->
					<?php echo form_close();?>
					<div> <?php echo $message; ?></div>
					<!-- links -->
					<div class="links">
						<div><a href="<?php base_url(); ?>register/newuser">Register new employee</a></div>
						<div><a href="<?php base_url(); ?>register/forgot_password">Forgot your password</a></div>
					</div>
					<!-- end links -->
				</div>
				<!-- end form -->
			</div>
			<!-- end inner -->
			
		</div>
	</body>
</html>