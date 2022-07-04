<!DOCTYPE html>
<html>
<head>
	<title>Sign In</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="<?php echo base_url('assets/css/owl.carousel.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/owl.theme.default.css'); ?>">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>
	<script src="<?php echo base_url('assets/js/jquery-3.0.0.js') ?>"></script>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/animate.css') ?>">
	<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
	<link rel="stylesheet" href="<?php  echo base_url('assets/css/app.v1.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/login_styles.css'); ?>">
	<script>
		function toggleClass (element,name) {
  

		  if (element.classList) { 
		      element.classList.toggle(name);
		  } else {
		      // For IE9
		      var classes = element.className.split(" ");
		      var i = classes.indexOf(name);

		      if (i >= 0) 
		          classes.splice(i, 1);
		      else 
		          classes.push(name);
		          element.className = classes.join(" "); 
		  }
		}

		function toggleBtnDisabledStatus (elem,evt) {
			// evt.preventDefault();
			toggleClass(document.querySelector("#submit-btn"),"disabled");
		}

		function openTerms (elem,evt) {
			evt.preventDefault();
			$("#terms-modal").modal("show");
		}
	</script>
</head>
<body>
	<div class="section m-t-lg wrapper-md animated fadeInUp banner" >
		<main class="container aside-xl" style="padding-top: 0;">
			<section class="m-b-lg">
					
					<h1>Create Your Free Account</h1>
					<!-- <h2><?php echo validation_errors(); ?></h2> -->
					
					<h4 style="color: red; font-style: italic; display: none;" id="ajax-error">Error! Something Went Wrong Please Try Again Later</h4>
					<h4 style="color: red; font-style: italic;"><?php echo form_error('color'); ?></h4>
					<?php $attributes = array('id' => 'myForm') ?>
					<?php echo form_open('onehealth/sign_in_as_health_facility',$attributes); ?>
						<div class="list-group">

							<div class="form-group">
								<span id="name-error" style="color: red; font-style: italic;"><?php echo form_error('name'); ?></span>
								<label for="name">Enter Health Facility Name: </label>
								<input type="text" id="name" name="name" class="form-control" value="<?php echo set_value('name') ?>" required>
							</div>

							<div class="form-group">
								<span id="email-error" style="color: red; font-style: italic;"><?php echo form_error('email'); ?></span>
								<label for="email">Enter Email Address: </label>
								<input type="text" id="email" name="email" class="form-control" value="<?php echo set_value('email') ?>" required>
							</div>

							<div class="form-group">
								<span id="structure-error" style="color: red; font-style: italic;"></span>
								<label for="structure">Select Facility Structure: </label>
								<select name="structure" id="structure" class="form-control" required>
									<?php
		                              $facility_structures = $this->onehealth_model->getFacilityStructures();
		                              if(is_array($facility_structures)){
		                                foreach($facility_structures as $row){
		                                  $structure_name = $row->name;
		                             
		                            ?>
		                            <option value="<?php echo $structure_name ?>"><?php echo $structure_name ?></option>
		                            <?php
		                                } 
		                              }
		                            ?>
								</select>
							</div>
							<?php 
								$ip_address = $this->input->ip_address();
								if($ip_address == "::1"){
									$ip_address = "197.211.60.81";
								}
						
								
								if($this->input->valid_ip($ip_address)){
									$location_info = $this->onehealth_model->custom_curl("http://api.ipstack.com/" . $ip_address . "?access_key=04b8933ab2331d1a31e653053f909b43",TRUE);
									// echo $location_info;
									$location_info = json_decode($location_info);
									if(is_object($location_info)){
										// var_dump($location_info);
										// echo $location_info->region_code;
										// echo ;
										$cc = $location_info->country_code;
									}else{
										$cc = "";
									}
							?>
	
							<div class="form-group">
								<span id="country-error" style="color: red; font-style: italic;"></span>
								<label for="country">Select Country: </label>
								<select name="country" id="country" class="form-control" onchange="changeStateAndCity()" required>
									<?php
										if(is_array($all_countries)){ 
											
											$cc = strtolower($cc);
											foreach($all_countries as $country){
												$country_name = $country->name;
												$country_code = $country->code;
												$country_id = $country->id;
									?>
									<option style="text-transform: capitalize;" <?php if($country_code == $cc){ echo "selected"; } ?> value="<?php echo $country_id ?>"><?php echo $country_name; ?> (<?php echo $country_code; ?>)</option>
									<?php
											}	
											
										}	
									?>
								</select>
							</div>
							
							<div class="form-group">
								<span id="region-error" style="color: red; font-style: italic;"></span>
								<label for="region">Select State: </label>
								<select name="region" id="region" class="form-control" onchange="" required>
									<?php
										if(is_array($first_regions)){ 
											foreach($first_regions as $region){
												$region_name = $region->name;
												$country_id = $region->country_id;
												$region_id = $region->id;
									?>
									<option style="text-transform: capitalize;" value="<?php echo $region_id ?>"><?php echo $region_name; ?></option>
									<?php
											}
										}
									?>
								</select>
							</div>

							<?php } ?>
							
							<div class="form-group">
								<span id="address-error" style="color: red; font-style: italic;"><?php echo form_error('address'); ?></span>
								<label for="address">Enter address</label>
								<input type="text" id="address" name="address" class="form-control" value="<?php echo set_value('address') ?>" required>
							</div>
		
							<div class="form-group">
								<span id="username-error" style="color: red; font-style: italic;"><?php echo form_error('user_name'); ?></span>
								<label for="user_name">Enter Username</label>
								<input type="text" id="user_name" name="user_name" class="form-control" value="<?php echo set_value('user_name') ?>" required>
							</div>

							<div class="form-group">
								<span id="password-error" style="color: red; font-style: italic;"><?php echo form_error('password'); ?></span>
								<label for="pass" style="">Enter Password</label>
								<input type="password" id="pass" placeholder="" name="password" class="form-control" value="<?php echo set_value('password') ?>" required>
							</div>
							<input type="checkbox" onclick="toggleBtnDisabledStatus(this,event)"> <span style="font-size: 13px;">I Agree To <a href="#" onclick="openTerms(this,event)">Terms And Conditions</a></span>
							<input type="submit" id="submit-btn" class="btn btn-lg btn-primary disabled btn-block" value="Register" name="submit" style="margin-top: 50px;">
						
							<div class="text-center m-t m-b" style="color: white;">
								
								<p>Already Have An Account ?</p>
								<a href="<?php echo site_url('onehealth/sign_in') ?>" style="border: 1px solid grey; background: #fff;">Login Now</a>
							</div>
						</div>
						<input type="hidden" name="random_bytes" value='<?php echo bin2hex($this->encryption->create_key(16)); ?>' readonly>
						<input type="hidden" name="color" value="" readonly id="color">
					</form>
			</section>
			<!-- <input type="hidde"> -->
		</main>
		<div class="modal fade" style="color: #000;" data-backdrop="false" id="terms-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Terms And Conditions</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">
                OneHealth: Terms and policies

					Terms of Service

					Welcome to OneHealth!
					These Terms govern your use of OneHealth and the products, features, apps, services,
					technologies, and software we offer, except where we expressly state that separate terms (and
					not these) apply
					Our Services
					Our mission is to stimulate exponential growth towards excellence, in the total quality healthcare
					delivery, through active participation and innovative directions. To help advance this mission, we
					provide the following products and services:
					Provide access to quality healthcare for users
					Users of OneHealth are gravitated towards following best practices as healthcare providers and
					seekers. Expert inputs are emphasized, while we try to ensure elimination of quackery. Steps aimed
					at quality control of processes are initiated. Seekers of healthcare services will Know the
					qualifications of their providers.
					Connect you with people seeking and organizations offering services you need
					We help you find and connect with people, groups, businesses, organizations, and others that
					matter to you while using OneHealth. This way clients accessing health care in same facility can
					share experience and communicate with their providers through chats in real time. OneHealth also
					suggests registered facilities to you as you type in the type of medical help you seek
					All Healthcare professionals and their clients are beneficials
					All healthcare professionals and their clients are collectively served when signed in as a hospital or
					individually served by OneHealth when signed in as a respective professional facility.
					Quality diagnostic service
					Users of OneHealth will access and offer quality diagnostic services from diagnostic facilities based of
					following sequential Quality Management System defined steps in the preparation of test
					generation and reporting of test results.
					Stimulate Increase in Research Culture

					Uniting all users in OneHealth will generate data, elucidate the health challenges of the community,
					and stimulate interest into solving them, thereby yielding community centred research culture.
					E-reporting is enabled for our Users
					Healthcare providers using OneHealth can upload generated images from Ultrasound scans, CTscans,
					MRI, PETscan, Histology Micrographs, Staining Techniques, Electrophoregrams etc, which the
					Experts see and make real time inputs. This will be very valuable to the diagnostic world.

					Research ways to make our services better
					We engage in research and collaborate with others to improve OneHealth. One way we do this is by
					analyzing the data we have and understanding how people use our Products.
					Enable global access to our services
					OneHealth can be accessed all over the world!

					Your Commitments to OneHealth
					We provide these services to you and others to help advance our mission. In exchange, we need you
					to make the following commitments:
					Who can use OneHealth?
					 Use the same name that you use in everyday life.
					 Provide accurate information about yourself.
					 Create only a personal account and use your timeline for personal purposes.
					 Not share your password, give access to your OneHealth account to others, or transfer your
					account to anyone else (without our permission).
					We try to make OneHealth broadly available to everyone, but you cannot use OneHealth if:
					 We previously disabled your account for violations of our terms or policies.
					 You are prohibited from receiving our products, services, or software under applicable laws.
					2. What you can share and do on OneHealth?
					We want people to use Onehealth to advance their healthcare, but not at the expense of the safety
					and well-being of others or the integrity of our community. You therefore agree not to engage in the
					conduct described below (or to facilitate or support others in doing so):
					 You may not use our Products to do or share anything:
					 That violates these Terms
					 That is unlawful, misleading, discriminatory or fraudulent.
					 That infringes or violates someone else's rights.
					 You may not upload viruses or malicious code or do anything that could disable, overburden,
					or impair the proper working or appearance of our Products.

					 You may not access or collect data from our Products using automated means (without our
					prior permission) or attempt to access data you do not have permission to access.
					We can remove content you share in violation of these provisions and, if applicable, we may take
					action against your account, for the reasons described below. We may also disable your account if
					you repeatedly infringe other people's intellectual property rights.
					To help support our community, we encourage you to report content or conduct that you believe
					violates your rights (including intellectual property rights) or our terms and policies.
					The permissions you give us
					We need certain permissions from you to provide our services:
					 Permission to use Data generated by your facility or from your consult to a facility: You own
					the content you generate on OneHealth , and nothing in these Terms takes away the rights
					you have to your own content. You are free to share your content with anyone else,
					wherever you want. To provide our services, though, we need you to give us some legal
					permissions to use that content.
					Specifically, when you share, post, or upload content that is covered by intellectual property

					rights (like photos or videos) on or in connection with our Products, you grant us a non-
					exclusive, transferable, sub-licensable, royalty-free, and worldwide license to host, use,

					distribute, modify, run, copy, publicly perform or display, translate, and create derivative
					works of your content. This means, for example, if a test result is generated from your
					consult, you give us permission to store, copy, and share it with others.
					You can end this license any time by deleting your content or account. You should know
					that, for technical reasons, content you delete may persist for a limited period of time in
					backup copies (though it will not be visible to other users). In addition, content you delete
					may continue to appear if you have shared it with others and they have not deleted it.
					 Permission to use your name, profile picture, and clinical information: You give us
					permission to use your name and profile picture and information about actions you have
					taken on OneHealth next to or in connection with ads, offers, and other sponsored content
					that we display in our Product, without any compensation to you. For example, we may
					show your friends that you are interested in an advertised event or have liked a Page
					created by a brand that has paid us to display its ads on OneHealth. Ads like this can be seen
					only by all.
					 Permission to update software you use or download: If you download or use our software,
					you give us permission to download and install upgrades, updates, and additional features to
					improve, enhance, and further develop it.
					Limits on using our intellectual property
					If you use content covered by intellectual property rights that we have and make available in our
					Products, we retain all rights to that content (but not yours). You can only use our copyrights or
					trademarks (or any similar marks) as expressly permitted with our prior written permission. You
					must obtain our written permission (or permission under an open source license) to modify, create
					derivative works of, decompile, or otherwise attempt to extract source code from us.

					Updating our Terms
					We work constantly to improve our services and develop new features to make our Products better
					for you and our community. As a result, we may need to update these Terms from time to time to
					accurately reflect our services and practices. Unless otherwise required by law, we will notify you
					before we make changes to these Terms and give you an opportunity to review them before they go
					into effect. Once any updated Terms are in effect, you will be bound by them if you continue to use
					our Products.
					We hope that you will continue using our Products, but if you do not agree to our updated Terms
					and no longer want to be a part of the Facebook community, you can delete your account at any
					time.
					Account suspension or termination
					If we determine that you have violated our terms or policies, we may take action against your
					account to protect our community and services, including by suspending access to your account or
					disabling it. We may also suspend or disable your account if you create risk or legal exposure for us
					or when we are permitted or required to do so by law. Where appropriate, we will notify you about
					your account the next time you try to access it.
					If you delete or we disable your account, these Terms shall terminate as an agreement between you
					and us, but the following provisions will remain in place:
					Limits on liability
					We work hard to provide the best Products we can and to specify clear guidelines for everyone who
					uses them. Our Products, however, are provided "as is," and we make no guarantees that they
					always will be safe, secure, or error-free, or that they will function without disruptions, delays, or
					imperfections. To the extent permitted by law, we also DISCLAIM ALL WARRANTIES, WHETHER
					EXPRESS OR IMPLIED, INCLUDING THE IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
					PARTICULAR PURPOSE, TITLE, AND NON-INFRINGEMENT. We do not control or direct what people
					and others do or say, and we are not responsible for their actions or conduct (whether online or
					offline) or any content they share (including offensive, inappropriate, obscene, unlawful, and other
					objectionable content).
					We cannot predict when issues might arise with our Products. Accordingly, our liability shall be
					limited to the fullest extent permitted by applicable law, and under no circumstance will we be liable
					to you for any lost profits, revenues, information, or data, or consequential, special, indirect,
					exemplary, punitive, or incidental damages arising out of or related to these Terms or the use of
					OneHealth Product, even if we have been advised of the possibility of such damages.
					Disputes

					We try to provide clear rules so that we can limit or hopefully avoid disputes between you and us. If
					a dispute does arise, however, it's useful to know up front where it can be resolved and what laws
					will apply.
					If you are a consumer, the laws of the country in which you reside will apply to any claim, cause of
					action, or dispute you have against us that arises out of or relates to these Terms or the OneHealth
					Products ("claim"), and you may resolve your claim in any competent court in that country that has
					jurisdiction over the claim. In all other cases, you agree that the claim must be resolved exclusively in
					the Lagos. District Court for Lagos, that you submit to the personal jurisdiction of either of these
					courts for the purpose of litigating any such claim, and that the laws of Lagos State, Nigeria, will
					govern these Terms and any claim, without regard to conflict of law provisions.
					Other

					 These Terms (formerly known as the Statement of Rights and Responsibilities) make
					up the entire agreement between you and One Health Issues Global Ltd regarding
					your use of our Products. They supersede any prior agreements.
					 If any portion of these Terms are found to be unenforceable, the remaining portion
					will remain in full force and effect. If we fail to enforce any of these Terms, it will not
					be considered a waiver. Any amendment to or waiver of these Terms must be made
					in writing and signed by us.

					 You will not transfer any of your rights or obligations under these Terms to anyone else
					without our consent.
					 You may designate a person (called a legacy contact) to manage your account if it is
					memorialized. Only your legacy contact or a person who you have identified in a valid will or
					similar document expressing clear consent to disclose your content upon death or incapacity
					will be able to seek disclosure from your account after it is memorialized.
					 These Terms do not confer any third-party beneficiary rights. All of our rights and obligations
					under these Terms are freely assignable by us in connection with a merger, acquisition, or
					sale of assets, or by operation of law or otherwise.
					 You should know that we may need to change the username for your account in certain
					circumstances (for example, if someone else claims the username and it appears unrelated
					to the name you use in everyday life).
					 We always appreciate your feedback and other suggestions about our products and services.
					But you should know that we may use them without any restriction or obligation to
					compensate you, and we are under no obligation to keep them confidential.
					 We reserve all rights not expressly granted to you.
					Date of Last Revision: November 13th, 2018
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
		<script>
			$(document).ready(function(){
				var country_id = $('#country').val();
				$.when(
					$.post("<?php echo site_url('onehealth/get_regions') ?>","change_country_id=true&country_id=" + country_id,function(){
						// var states = states;
						
					}),

						
				).done(function (states,cities){
					$("#region").html(states);
					
				});
			});
			function changeStateAndCity(evt){
				var country_id = $('#country').val();
				$.when(
					$.post("<?php echo site_url('onehealth/get_regions') ?>","change_country_id=true&country_id=" + country_id,function(){
						// var states = states;
						
					}),

						
				).done(function (states,cities){
					$("#region").html(states);
					
				});
			}

			// function changeCity(){
			// 	var state_id = $('#region').val();
			// 	$.ajax({
			// 		url : "<?php echo site_url('onehealth/get_cities') ?>",
			// 		dataType : "text",
			// 		type : "POST",
			// 		data : "get_cities=true&state_id=" + state_id ,
			// 		responseType : "any",
			// 		success : function(response){
			// 			// console.log(response)
			// 			$("#city").html(response);
			// 		},
			// 		error:function(err){
			// 			// $("#ajax-error").html(err);
			// 			$("#ajax-error").show();
			// 		}
			// 	});

			// }
		</script>
	
</div>
</body>
<script src="<?php echo base_url('assets/js/jquery-3.0.0.js') ?>"></script>
<script src="<?php echo base_url('assets/js/owl.carousel.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/index.js') ?>"></script>
<script>
	$(document).ready(function(){
		var colours = {
                   "1" : "#1abc9c", "2" : "#2ecc71", "3" :"#3498db", "4" :"#9b59b6", "5" :"#34495e", "6" :"#16a085","7" : "#27ae60","8" : "#2980b9","9" : "#8e44ad", "10" :"#2c3e50", 
                    "11" :"#f1c40f", "12" :"#e67e22", "13" :"#e74c3c","14" : "#ecf0f1", "15" :"#95a5a6", "16" :"#f39c12","17" : "#d35400", "18" :"#c0392b", "19" :"#bdc3c7", "20" :"#7f8c8d"
                };
        var random_colour = randomColorProperty(colours);  
		// console.log(random_colour);
		// console.log(getKeyByValue(colours,random_colour));
		$("#color").val(getKeyByValue(colours,random_colour));
	})
</script>
</html>