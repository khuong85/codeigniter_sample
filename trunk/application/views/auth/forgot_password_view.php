<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


	<head>
		<title><?php echo $title;?></title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<!-- stylesheets -->
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/common/css/reset.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/common/css/style.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/common/css/message.css" media="screen" />
	</head>
	<body>
		<div id="login">
			<!-- login -->
			<div class="title">
				<h5><?php echo $heading;?></h5>
				<div class="corner tl"></div>
				<div class="corner tr"></div>
			</div>
			<?php echo form_open("register/forgot_password");?>
			<div class="inner">
				<?php echo form_error('username','<div class="negative-top">','</div>');
					if(isset($message)) echo "<div class='positive-top'>".$message."</div>";
					if(isset($error)) echo "<div class='negative-top'>".$error."</div>";
				?>
				<div class="form">
					<!-- fields -->
					<div class="fields">
						<div class="field">
							<div class="label">
								<label for="username">Email:</label>
							</div>
							<div class="input">
								<input type="text" id="username" name="username" size="40" value="" />
							</div>
						</div>
						<div class="buttons">
							<input type="submit" value="Send" />
							<?php echo form_submit(array('id'=>'btnback','name'=>'back','value'=>'Back'))?>
						</div>
					</div>
					<!-- end fields -->
				</div>
				
			</div>
			<!-- end login -->
			<?php echo form_close();?>
		</div>
	</body>
</html>