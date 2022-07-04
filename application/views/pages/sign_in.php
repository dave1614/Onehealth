<!DOCTYPE html>
<html>
<head>
	<title>Sign In</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="<?php echo base_url('assets/css/owl.carousel.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/owl.theme.default.css'); ?>">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>
	<script src="<?php echo base_url('assets/js/jquery-3.0.0.js') ?>"></script>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/animate.css') ?>">
	<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/sweetalert2.min.css') ?>">

	<link rel="stylesheet" href="<?php  echo base_url('assets/css/app.v1.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/login_styles.css'); ?>">
	<style>
		.error{
			color: red;
			font-style: italic;
			font-size: 15px;
		}
		#forgot-password-section{
			display: none;
		}
		#otp-validation-section{
			display: none;
		}
	</style>
	<script>
		
		function submitNewPassword(elem,evt) {
			evt.preventDefault();
			var new_password = elem.querySelector("#new_password").value;
			var url = "<?php echo site_url('onehealth/activate_new_password') ?>";
			var data = "new_password_enter=true&new_password="+new_password;
			$.ajax({
				type : "POST",
				dataType : "json",
				responseType : "json",
				url : url,
				data : data,
				success : function (response) {
					if(response.success == true){
						window.location.assign("<?php echo site_url('onehealth/cl_admin'); ?>");
					}else{
						swal({
						  type: 'error',
						  title: 'Oops.....',
						  text: 'Something Went Wrong Please Try Again!'
						});
					}
				},error : function () {
					swal({
					  type: 'error',
					  title: 'Oops.....',
					  text: 'Something Went Wrong Please Try Again!'
					});
				}
			});
		}

		function submitOtp(elem,evt){
			evt.preventDefault();
			var otp = elem.querySelector("#otp").value;
			var url = "<?php echo site_url('onehealth/verify_otp') ?>";
			var data = "verify_otp=true&otp="+otp;
			$.ajax({
				type : "POST",
				dataType : "json",
				responseType : "json",
				url : url,
				data : data,
				success : function (response) {
					if(response.success == true && response.messages !== ""){
						$("#otp-validation-section").hide();
						$(".container").append(response.messages);
					}else{
						swal({
						  type: 'error',
						  title: 'Oops.....',
						  text: 'OTP Mismatch'
						});
					}
				},error : function () {
					swal({
					  type: 'error',
					  title: 'Oops.....',
					  text: 'Something Went Wrong Please Try Again!'
					});
				}
			});	
		}

		function forgotPassword(elem,evt) {
			evt.preventDefault();
			$("#sign-in-form").hide();
			$("#forgot-password-section").show();
		}

		function signIn(elem,evt) {
			evt.preventDefault();
			$("#sign-in-form").show();
			$("#forgot-password-section").hide();
			$("#otp-validation-section").hide();
			$("#enter-new-password-section").hide();
		}

		function submitForgotPasswordForm (elem,evt) {
			evt.preventDefault();
			var url = "<?php echo site_url('onehealth/forgot-password') ?>";
			var email = elem.querySelector("#email").value;
			// $(".spinner-overlay").show();
			data = "email=" + email + "&forgot_password=true";
			$.ajax({
				type : "POST",
				dataType : "json",
				responseType : "json",
				url : url,
				data : data,
				success : function (response) {
					// $(".spinner-overlay").hide();
					$(".form-error").html("");
					
					if(response.success == true){
						$("#forgot-password-section").hide();
						$("#otp-validation-section").show();	
					}else if(response.invalid_email == true){
						swal({
						  type: 'error',
						  title: 'Oops.....',
						  text: 'Sorry, the email provided is not linked to any of our users. Please try again!'
						});
					}else{
						$.each(response.messages, function (key,value) {

			              var element = $('#'+key);
			              
			              element.closest('div.form-group')
			                      
			                      .find('.form-error').remove();
			              element.after(value);
			              
			             });

					}

				},error : function () {
					swal({
					  type: 'error',
					  title: 'Oops.....',
					  text: 'Something Went Wrong Please Try Again!'
					});
				}
			});
		}

	</script>
</head>
<body>
	<div class="spinner-overlay" style="display: none;">
	    <div class="spinner-well">
	      <img src="<?php echo base_url('assets/images/tests_loader.gif') ?>" alt="Loading...">
	    </div>
	  </div>
	<div class="section m-t-lg wrapper-md animated fadeInUp banner" >
		<main class="container aside-xl" style="padding-top: 0;">
			<section class="m-b-lg" id="sign-in-form">
					
					<h1 class="text-center">Login</h1>
					<?php echo form_open(); ?>
						<div class="list-group">
							<div class="form-group">
								<span id="username-error" class="error" style=""><?php echo form_error('user_name'); ?></span>
								<label for="username">Enter Username</label>
								<input type="text" id="user_name" name="user_name" class="form-control" value="<?php echo set_value('user_name'); ?>" required>
							</div>
							<div class="form-group">
								<span id="password-error" class="error"  style=""><?php echo form_error('password'); ?></span>
								<label for="pass" style="">Enter Password</label>
								<input type="password" id="pass" placeholder="" name="password" class="form-control" value="<?php echo set_value('password'); ?>" required>
							</div>
							<input type="submit" class="btn btn-lg btn-primary btn-block" value="Login" name="submit" style="margin-top: 50px;">
						
							<div class="text-center m-t m-b" style="color: white;">
								
								
								<a href="#" onclick="return forgotPassword(this,event)" style="border: 1px solid grey; background: #fff;">Forgot Password?</a>
							</div>
						</div>
					</form>
			</section>
			<section class="m-b-lg" id="forgot-password-section">
					
					<h1 class="text-center">Forgot Password</h1>
					<?php
						$attr = array('id' => 'forgot-password-form','onsubmit' => 'submitForgotPasswordForm(this,event)');
						echo form_open('',$attr); 
					?>
						<div class="list-group">
							<div class="form-group">
								<label for="email">Enter Email Address Associated With Your Account</label>
								<input type="email" id="email" name="email" class="form-control" required>
							</div>
							
							<input type="submit" class="btn btn-lg btn-primary btn-block" value="Submit" name="submit" style="margin-top: 50px;">
							<div class="text-center m-t m-b" style="color: white;">
								
								
								<a href="#" onclick="return signIn(this,event)" style="border: 1px solid grey; background: #fff;">Sign In</a>
							</div>
							
						</div>
					</form>
			</section>
			<section class="m-b-lg" id="otp-validation-section">
					
					<h1 class="text-center">Enter OTP</h1>
					<p>OTP Has Been Successfully Sent To Your Email Address.</p>
					<?php
						$attr = array('id' => 'otp-form','onsubmit' => 'submitOtp(this,event)');
						echo form_open('',$attr); 
					?>
						<div class="list-group">
							<div class="form-group">
								<label for="otp">Enter OTP</label>
								<input type="text" id="otp" name="otp" class="form-control" required>
							</div>
							
							<input type="submit" class="btn btn-lg btn-primary btn-block" value="Proceed" name="submit" style="margin-top: 50px;">
							<div class="text-center m-t m-b" style="color: white;">
								
								
								<a href="#" onclick="return signIn(this,event)" style="border: 1px solid grey; background: #fff;">Sign In</a>
							</div>
							
						</div>
					</form>
			</section>
			<input type="hidden" name="random_bytes" id="random_bytes" value='<?php echo bin2hex($this->encryption->create_key(16)); ?>' readonly>
		</main>
		<script>
			function validateForm(){
				var username = document.forms["myForm"]["username"].value;
				var password = document.forms["myForm"]["password"].value;
				if(username.length > 10){
					var username_error = document.getElementById("username-error");
					username_error.innerHTML = "username must be at most 10 characters";
					return false;
				} if(password.length > 10){
					var password_error = document.getElementById("password-error");
					password_error.innerHTML = "password must be at most 10 characters";
					return false;
				} 
			}	
			$(document).ready(function(){
				function perform(){
					var banner = $("#banner");
					var height = window.innerHeight
					|| document.documentElement.clientHeight
					|| document.body.clientHeight;
					banner.css("height",height);
					
				}
				setInterval(perform, 1000);
				<?php if($incorrect_password == true){ ?>
				swal({
				  type: 'error',
				  title: 'Oops.....',
				  text: 'Sorry, the user name password combination was incorrect. Please try again!'
				  // footer: '<a href>Why do I have this issue?</a>'
				})
				<?php } ?>

				<?php if($user_does_not_exist == true){ ?>
				swal({
				  type: 'error',
				  title: 'Oops.....',
				  text: 'Sorry, the user name provided does not exist. Please try again!'
				  // footer: '<a href>Why do I have this issue?</a>'
				})
				<?php } ?>

				<?php if($something_wrong == true){ ?>
				swal({
				  type: 'error',
				  title: 'Oops.....',
				  text: 'Sorry, went wrong when loading this page. You Will Be Redirected'
				  // footer: '<a href>Why do I have this issue?</a>'
				})
				<?php } ?>
			});
		</script>
	
</div>
</body>
<script src="<?php echo base_url('assets/js/jquery-3.0.0.js') ?>"></script>
<script src="<?php echo base_url('assets/js/owl.carousel.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/sweetalert2.all.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/sweetalert2.min.js') ?>"></script>
</html>