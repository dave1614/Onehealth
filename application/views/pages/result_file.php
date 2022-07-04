<!DOCTYPE html>
<html>
<head>
	<title>Result For <?php echo $patient_name; ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/quill.snow.css'); ?>" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="<?php echo base_url('assets/js/quill.js') ?>"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
	<style>
		body{
			font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
			background: #E8E8E8;
		}
		.container{
			padding: 30px;
			padding-top: 20px;
			background: #fff;
			border-radius: 5px;
			width: 800px;
			margin: 10px auto;
			
		}
		#facility-logo-img{
			height: 50px;
			width: 10%;
			display: inline-block;
		}
		
		#company-name-div{
			width: 60%;
			display: inline-block;
			float: right
		}

		#company-name-div #company-logo{
			width: 25px;
			/*float: right;*/
		}

		#company-name-div #company-name{
			font-weight: bold;
		    display: block;
		    text-align: right;
		    font-size: 10px;
		    /* width: 50%; */
		    /* float: right; */
		    margin: 0;
		    /* margin-right: 10px; */
		}
		#top-row-cont{
			width: 100%;
		}
		.facility-info-div{
			margin-top: 5px;
		}
		.facility-name{
			font-weight: bold;
		    font-size: 20px;
		    margin-bottom: 10px;
		    text-transform: capitalize;
		}
		.state-placeholder{
			font-weight: bold;
			font-size: 15px;
			display: inline;
		}
		.state-name{
			font-size: 14px;
			display: inline;
			font-style: italic;
			font-weight: lighter;
		}
	
		.date-placeholder{
			font-weight: bold;
			font-size: 15px;
			display: inline;
		}

		.date-value{
			font-size: 14px;
			display: inline;
			font-style: italic;
			font-weight: lighter;
		}

		.address-placeholder{
			font-weight: bold;
			font-size: 15px;
			display: inline;
		}

		.address-value{
			font-size: 14px;
			display: inline;
			font-style: italic;
			font-weight: lighter;
		}

		#receipt-title{
			font-weight: bold;
			text-align: center;
			font-size: 20px;
			margin-bottom: 10px;
		}

		#receipt-subtitle{
			font-style: italic;
			/*text-align: center;*/
			font-size: 14px;
			font-weight: lighter;
			margin-top: 0;
		}

		#patient-name{
			font-size: 14px;
			font-weight: bold;
		}

		/*table, th, td {
		  border: 1px solid black;
		}*/

		#tests-table {
			margin-top: 30px;
		  margin-bottom: 10px;
		  border-collapse: collapse;
		  width: 100%;
		  font-size: 13px;
		}

		#tests-table td, #tests-table th {
		  border: 1px solid #ddd;
		  padding: 2px;
		}

		/*#tests-table tr:nth-child(even){background-color: #f2f2f2;}*/

		/*#tests-table tr:hover {background-color: #ddd;}*/

		#tests-table th {
		  padding-top: 12px;
		  padding-bottom: 12px;
		  text-align: left;
		  /*background-color: #4CAF50;
		  color: white;*/
		}

		.pathology-placeholder{
			font-weight: bold;
			font-size: 15px;
			display: inline; 
		}

		.pathology-number{
			font-size: 13px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}

		.sub-total-placeholder{
			font-weight: bold;
			font-size: 15px;
			display: inline; 
		}

		.sub-total-name{
			font-size: 13px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}

		.initiation-code-placeholder{
			font-weight: bold;
			font-size: 15px;
			display: inline; 
		}

		.initiation-code-name{
			font-size: 13px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}

		.mode-of-payment-placeholder{
			font-weight: bold;
			font-size: 15px;
			display: inline; 
		}

		.mode-of-payment-name{
			font-size: 13px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}
		
		.receipt-number-placeholder{
			font-weight: bold;
			font-size: 15px;
			display: inline; 
		}

		.receipt-number-name{
			font-size: 13px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}

		.credit-us{
			font-weight: bold;
			font-size: 14px;
			
		}
		
		.credit{
			font-weight: bold;
			font-size: 15px;
			margin: 0;
		}


		.our-email{
			font-size: 10px;
			color: #0277BD;
			margin: 0;
		}

		#test-image-result-heading{
			text-align: center;
			margin-top: 70px;
			margin-bottom: 40px;
			text-decoration: underline;
		}

		.test-image{
			max-width: 400px;
			height: 300px;
			margin-bottom: 40px;
		}

		#contributors-heading{
			text-align: center;
			font-size: 18px;
		}

		
		.receptionist-placeholder{
			font-weight: bold;
			font-size: 18px;
			display: inline; 
		}

		.receptionist-name{
			font-size: 16px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}

		.teller-placeholder{
			font-weight: bold;
			font-size: 18px;
			display: inline; 
		}

		.teller-name{
			font-size: 16px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}

		.clerk-placeholder{
			font-weight: bold;
			font-size: 18px;
			display: inline; 
		}

		.clerk-name{
			font-size: 16px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}

		.laboratory-officer-3-placeholder{
			font-weight: bold;
			font-size: 16px;
			display: inline; 
		}

		.laboratory-officer-3-name{
			font-size: 13px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}
		
		.laboratory-officer-2-placeholder{
			font-weight: bold;
			font-size: 16px;
			display: inline; 
		}

		.laboratory-officer-2-name{
			font-size: 13px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}

		.consultation-pathologist-placeholder{
			font-weight: bold;
			font-size: 16px;
			display: inline; 
		}

		.consultation-pathologist-name{
			font-size: 13px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}

		.pathologists-comment-cont p{
			/*width: 50%;*/
			font-size: 13px;
		}

		#pathologists-comment-heading{
			text-align: left;
			font-size: 18px;
			margin: 0;
		}

		#patient-name-placeholder{
			font-size: 15px;
			display: inline;
		}
		#patient-name{
			font-size: 14px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}

		#patient-age-placeholder{
			font-size: 15px;
			display: inline;
		}
		#patient-age{
			font-size: 14px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}
		#patient-sex-placeholder{
			font-size: 15px;
			display: inline;
		}
		#patient-sex{
			font-size: 14px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}
		#patient-address-placeholder{
			font-size: 15px;
			display: inline;
		}
		#patient-address{
			font-size: 14px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}
		#patient-referring-dr-placeholder{
			font-size: 15px;
			display: inline;
		}
		#patient-referring-dr{
			font-size: 14px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}
			
		.bold{
			font-weight: bold;
		}
	</style>
</head>
<body>
	<?php
		$radiology_only = false;
		if(is_array($tests)){
			if(count($tests) == 1){
				if(isset($tests[0]['sub_dept_id'])){
					if($tests[0]['sub_dept_id'] == 6){
						$radiology_only = true;
					}
				}
			}
		}
		// var_dump($radiology_only);
	?>
	<div class="container">
		<div class="top-row-cont">
			<?php
		
			 	if($this->onehealth_model->checkIfFacilityHasLetterHeadingEnabled($facility_id)){
			?>
			<?php if($logo != ""){ ?>
			<img src="<?php echo $logo['src'];  ?>" id="facility-logo-img">
			<?php }else{ ?>
			<img avatar="<?php echo $facility_name; ?>" col="<?php echo $color; ?>" id="facility-logo-img">
			<?php } ?>
			<?php } ?>	

			<?php if($facility_name != "Everight Diagnostics"){ ?>
			<div id="company-name-div" align="right">
				<img src="<?php echo base_url('assets/images/logo_small.jpg'); ?>" id="company-logo" alt="">
				<h2 id="company-name">OneHealth Issues Global Limited</h2>
			</div>
			<?php } ?>
		</div>
		<?php
		
		 	if($this->onehealth_model->checkIfFacilityHasLetterHeadingEnabled($facility_id)){
		?>
		<div class="facility-info-div">
			<h3 class="facility-name"><?php echo $facility_name;?></h3>
			<h3 class="state-placeholder">State : </h3>
			<h3 class="state-name"><?php echo $facility_state . ", " .$facility_country; ?></h3>
			<div class="" style="height: 5px"></div>
			<h3 class="date-placeholder">Date :</h3>
			<h3 class="date-value"> <?php echo $date; ?></h3>
			<div class="" style="height: 5px"></div>
			<h3 class="address-placeholder">Address :</h3>
			<h3 class="address-value"><?php echo $facility_address; ?></h3>

		</div>
		<?php
			}
		?>

		<?php  if(!$radiology_only){ ?>
		<h3 id="receipt-title">LABORATORY TEST RESULTS</h3>
		<?php } ?>
		<!-- <h3 id="receipt-subtitle">Results For: </h3> -->
		<div class="patient-bio-data">
			<h3 id="patient-name-placeholder" class="bold">Patient Name: </h3>
			<h3 id="patient-name"><?php echo $patient_name; ?></h3>
			<!-- <h3 id="patient-age-placeholder">Age: </h3> -->
			<!-- <h3 id="patient-age"><?php echo $bio_data['age']; ?></h3> -->
			<h3 id="patient-sex-placeholder"class="bold" class="bold">Sex: </h3>
			<h3 id="patient-sex"><?php echo $bio_data['sex']; ?></h3>
			<?php if($facility_name != "Everight Diagnostics"){ ?>
			<h3 id="patient-address-placeholder" class="bold">Address: </h3>
			<h3 id="patient-address"><?php echo $bio_data['address']; ?></h3>
			<?php } ?>
			<!-- <h3 id="patient-referring-dr-placeholder">Referring Dr: </h3>
			<h3 id="patient-referring-dr"><?php echo $bio_data['referring_dr']; ?></h3> -->
			<h3 id="patient-referring-dr-placeholder" class="bold">Date: </h3>
			<h3 id="patient-referring-dr"><?php echo $date; ?></h3>
		</div>

		<?php 
			if($radiology_only){ 
				$radiology_comments = $tests[0]['radiology_comments'];
				$radiographer = $tests[0]['radiographer'];
				$sonologist = $tests[0]['sonologist'];
				$radiologist = $tests[0]['radiologist'];
				$cardiologist = $tests[0]['cardiologist'];
				$about_test  = $tests[0]['about_test'];

				if($radiographer != 0 && $radiographer != ""){
					$radiographer_full_name = $this->onehealth_model->getPersonnelFullName($radiographer);
					$radiographer_qualification = $this->onehealth_model->getPersonnelQualification($radiographer);
					$radiographer_signature = $this->onehealth_model->getPersonnelSignature($radiographer);
				}


				if($sonologist != 0 && $sonologist != ""){
					$sonologist_full_name = $this->onehealth_model->getPersonnelFullName($sonologist);
					$sonologist_qualification = $this->onehealth_model->getPersonnelQualification($sonologist);
					$sonologist_signature = $this->onehealth_model->getPersonnelSignature($sonologist);
				}

				
				if($radiologist != 0 && $radiologist != ""){
					$radiologist_full_name = $this->onehealth_model->getPersonnelFullName($radiologist);
					$radiologist_qualification = $this->onehealth_model->getPersonnelQualification($radiologist);
					$radiologist_signature = $this->onehealth_model->getPersonnelSignature($radiologist);
				}

				if($cardiologist != 0 && $cardiologist != ""){
					$cardiologist_full_name = $this->onehealth_model->getPersonnelFullName($cardiologist);
					$cardiologist_qualification = $this->onehealth_model->getPersonnelQualification($cardiologist);
					$cardiologist_signature = $this->onehealth_model->getPersonnelSignature($cardiologist);
				}
		?>
		<h3 class="text-center"><?php echo $tests[0]['testname']; ?></h3>

		<div class="" style="margin-top: 20px;">
			
			<?php  if($about_test != ""){ ?>
			
										
			<div id="about-us-editor-0" style="border:0;">;

			</div>
				
			<?php  } ?>

			<h5 class="text-center">Findings</h5>

			<div id="editor-0" style="">

		    </div>

		    <div class="" style="margin-top: 20px;">
		    	<h5 class="text-center" style="margin-bottom: 20px;">Contributors </h5>
				<div class="row">
				<?php if($radiographer != 0 && $radiographer != ""){ ?>
					<div class="col-6">
						<h6 class=''>Radiographer: </h6>
						<p class='bold' style='text-transform:capitalize;'><?php echo $radiographer_full_name ?></p>

						<p class='bold' style='text-transform:capitalize;'><?php echo $radiographer_qualification ?></p>

						<img src="<?php echo base_url('assets/images/'.$radiographer_signature) ?>" style="height:150px; width: 150px;">
					</div>
				<?php } ?>


				<?php if($sonologist != 0 && $sonologist != ""){ ?>
					<div class="col-6">
						<h6 class=''>Sonologist: </h6>
						<p class='bold' style='text-transform:capitalize;'><?php echo $sonologist_full_name; ?> </p>

						<p class='bold' style='text-transform:capitalize;'><?php echo $sonologist_qualification; ?></p>

						<img src="<?php echo base_url('assets/images/'.$sonologist_signature); ?> " style="height:150px; width: 150px;">
					</div>
				<?php } ?>

							
				<?php if($radiologist != 0 && $radiologist != ""){ ?>
					<div class="col-6">
						<h6 class=''>Radiologist: </h6>
						<p class='bold' style='text-transform:capitalize;'><?php echo $radiologist_full_name; ?></p>

						<p class='bold' style='text-transform:capitalize;'><?php echo $radiologist_qualification; ?></p>

						<img src="<?php echo base_url('assets/images/'.$radiologist_signature); ?>" style="height:150px; width: 150px;">
					</div>
				<?php } ?>

				<?php if($cardiologist != 0 && $cardiologist != ""){ ?>
					<div class="col-6">
						<h6 class=''>Cardiologist: </h6>
						<p class='bold' style='text-transform:capitalize;'><?php echo $cardiologist_full_name; ?></p>

						<p class='bold' style='text-transform:capitalize;'><?php echo $cardiologist_qualification; ?></p>

						<img src="<?php echo base_url('assets/images/'.$cardiologist_signature); ?>" style="height:150px; width: 150px;">
					</div>
				<?php }  ?>
				</div>
		    </div>
		</div>
		<?php } ?>
		

		<?php if(!$radiology_only){ ?>
		<table id="tests-table">
			<thead>
				<tr>
					<th>#</th>
					
					<th>TEST NAME</th>
					<th>TEST RESULT</th>
					<th>RANGE</th>
					<th>FLAG</th>
					<th>METHODOLOGY</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$response = "";

					for($i = 0; $i < count($tests); $i++){
						$sub_test_count = 0;
						$index = $tests[$i]['i'];
						
						$testname = $tests[$i]['testname'];
						$testresult = $tests[$i]['testresult'];
						$range = $tests[$i]['range'];
						$flag = $tests[$i]['flag'];
						$about_test = $tests[$i]['about_test'];
						$last_sub_test = (Boolean) $tests[$i]['last_sub_test'];
						$has_sub_test = (Boolean) $tests[$i]['has_sub_test'];
						$main_test = (Boolean) $tests[$i]['main_test'];
						$sub_test = (Boolean) $tests[$i]['sub_test'];
						$lab_structure = $tests[$i]['lab_structure'];
						$sub_dept_id = $tests[$i]['sub_dept_id'];
						$methodology = $tests[$i]['methodology'];

						if($sub_dept_id == 6){
							
							$radiology_comments = $tests[$i]['radiology_comments'];
							$radiographer = $tests[$i]['radiographer'];
							$sonologist = $tests[$i]['sonologist'];
							$radiologist = $tests[$i]['radiologist'];
							$cardiologist = $tests[$i]['cardiologist'];

							if($radiographer != 0 && $radiographer != ""){
								$radiographer_full_name = $this->onehealth_model->getPersonnelFullName($radiographer);
								$radiographer_qualification = $this->onehealth_model->getPersonnelQualification($radiographer);
								$radiographer_signature = $this->onehealth_model->getPersonnelSignature($radiographer);
							}


							if($sonologist != 0 && $sonologist != ""){
								$sonologist_full_name = $this->onehealth_model->getPersonnelFullName($sonologist);
								$sonologist_qualification = $this->onehealth_model->getPersonnelQualification($sonologist);
								$sonologist_signature = $this->onehealth_model->getPersonnelSignature($sonologist);
							}

							
							if($radiologist != 0 && $radiologist != ""){
								$radiologist_full_name = $this->onehealth_model->getPersonnelFullName($radiologist);
								$radiologist_qualification = $this->onehealth_model->getPersonnelQualification($radiologist);
								$radiologist_signature = $this->onehealth_model->getPersonnelSignature($radiologist);
							}

							if($cardiologist != 0 && $cardiologist != ""){
								$cardiologist_full_name = $this->onehealth_model->getPersonnelFullName($cardiologist);
								$cardiologist_qualification = $this->onehealth_model->getPersonnelQualification($cardiologist);
								$cardiologist_signature = $this->onehealth_model->getPersonnelSignature($cardiologist);
							}

							$response .= "<tr>";
							$response .= "<td style='font-weight:bolder;'>".$index."</td>";
							$response .= "<td style='font-weight:bolder;'>".$testname."</td>";
							$response .= "<td></td>";
							$response .= "<td></td>";
							$response .= "<td></td>";
							$response .= "<td></td>";
							$response .= "</tr>";

							if($about_test != ""){
								$response .= "<tr>";
								$response .= "<td colspan='100%'>";
								// $response .= "<h5>Findings: </h5>";
								$response .= '<div id="about-us-editor-'.$index.'" style="border:0;">';

			            		$response .= '</div>';
			            		$response .= "</td>";
								$response .= "</tr>";
							}

							$response .= "<tr>";
							$response .= "<td colspan='100%'>";
							$response .= "<h5>Findings: </h5>";
							$response .= '<div id="editor-'.$index.'" style="border:0;">';

		            		$response .= '</div>';
		            		$response .= "</td>";
							$response .= "</tr>";

							

							$response .= "<tr class='container'>";

							$response .= "<td colspan='100%' style='padding: 20px;'>";
							$response .= "<h5>Contributors: </h5>";
							// $response .= '<div class="row">';
							if($radiographer != 0 && $radiographer != ""){
								$response .= "<p style='margin-bottom: 0;'><span class='bold'>Radiographer: </span><span style='text-transform:capitalize;'>".$radiographer_full_name."</span> <span>".$radiographer_qualification."</span></p>";

								$response .= "<img src='".base_url('assets/images/'.$radiographer_signature)."' style='height:30px; width: 117px;'>";	
							}


							if($sonologist != 0 && $sonologist != ""){
								$response .= "<p style='margin-bottom: 0;'><span class='bold'>Sonologist: </span><span style='text-transform:capitalize;'>".$sonologist_full_name."</span> <span>".$sonologist_qualification."</span></p>";

								$response .= "<img src='".base_url('assets/images/'.$sonologist_signature)."' style='height:30px; width: 117px;'>";	
							}

							
							if($radiologist != 0 && $radiologist != ""){
								$response .= "<p style='margin-bottom: 0;'><span class='bold'>Radiologist: </span><span style='text-transform:capitalize;'>".$radiologist_full_name."</span> <span>".$radiologist_qualification."</span></p>";

								$response .= "<img src='".base_url('assets/images/'.$radiologist_signature)."' style='height:30px; width: 117px;'>";	
							}

							if($cardiologist != 0 && $cardiologist != ""){
								$response .= "<p style='margin-bottom: 0;'><span class='bold'>Cardiologist: </span><span style='text-transform:capitalize;'>".$cardiologist_full_name."</span> <span>".$cardiologist_qualification."</span></p>";

								$response .= "<img src='".base_url('assets/images/'.$cardiologist_signature)."' style='height:30px; width: 117px;'>";	
							}
							
							$response .= "</td>";

							$response .= "</tr>";
						}else{
							if($main_test && $has_sub_test){
								
								if($lab_structure == "mini"){
									$response .= '<tr>';
									$response .= "<td style='font-weight:bolder;'>".$index."</td>";
									$response .= "<td style='font-weight:bolder;'>".$testname."</td>";
									$response .= '</tr>';

								}else if($lab_structure == "standard" || $lab_structure == "maximum"){
									$response .= '<tr>';
									$response .= "<td style='font-weight:bolder;'>".$index."</td>";
									$response .= "<td style='font-weight:bolder;'>".$testname."</td>";
									$response .= '</tr>';
								}
							}else if(!$main_test && $sub_test){
								$super_test_id = $tests[$i]['super_main_test_id'];
								if($lab_structure == "mini"){
									
									$laboratory_officer_2 = $tests[$i]['laboratory_officer_2'];
									$phlebotomist = $tests[$i]['phlebotomist'];
									$comments = $tests[$i]['comments'];

									if($phlebotomist != 0 && $phlebotomist != ""){
										$phlebotomist_full_name = $this->onehealth_model->getPersonnelFullName($phlebotomist);
										$phlebotomist_qualification = $this->onehealth_model->getPersonnelQualification($phlebotomist);
										$phlebotomist_signature = $this->onehealth_model->getPersonnelSignature($phlebotomist);
									}
									

									if($laboratory_officer_2 != 0 && $laboratory_officer_2 != ""){
										$laboratory_officer_2_full_name = $this->onehealth_model->getPersonnelFullName($laboratory_officer_2);
										$laboratory_officer_2_qualification = $this->onehealth_model->getPersonnelQualification($laboratory_officer_2);
										$laboratory_officer_2_signature = $this->onehealth_model->getPersonnelSignature($laboratory_officer_2);
									}

									$response .= '<tr>';
									$response .= '<td></td>';
									$response .= '<td>'.$testname.'</td>';
									$response .= '<td>'.$testresult.'</td>';
									$response .= '<td>'.$range.'</td>';
									$response .= '<td>'.$flag.'</td>';
									$response .= '<td>'.$methodology.'</td>';
									$response .= '</tr>';

									if($last_sub_test == 1){

										if($about_test != ""){
											$response .= "<tr>";
											$response .= "<td colspan='100%'>";
											// $response .= "<h5>Findings: </h5>";
											$response .= '<div id="about-us-editor-'.$super_test_id.'" style="border:0;">';

						            		$response .= '</div>';
						            		$response .= "</td>";
											$response .= "</tr>";
										}

										if($comments != ""){
											$response .= "<tr>";
											$response .= "<td colspan='100%'>";
											$response .= "<h5>Comments: </h5>";
											$response .= '<p>'.$comments.'</p>';

						            		$response .= '</div>';
						            		$response .= "</td>";
											$response .= "</tr>";
										}

										

										$response .= "<tr class='container'>";

										$response .= "<td colspan='100%' style='padding: 20px;'>";
										$response .= "<h5>Contributors: </h5>";
										

										if($laboratory_officer_2 != 0 && $laboratory_officer_2 != ""){
											$response .= "<p style='margin-bottom: 0;'><span class='bold'>Laboratory Officer 2: </span><span style='text-transform:capitalize;'>".$laboratory_officer_2_full_name."</span> <span>".$laboratory_officer_2_qualification."</span></p>";

											$response .= "<img src='".base_url('assets/images/'.$laboratory_officer_2_signature)."' style='height:30px; width: 117px;'>";
										}
										
									}
							
								}else if($lab_structure == "standard" || $lab_structure == "maximum"){
									$laboratory_officer_2 = $tests[$i]['laboratory_officer_2'];
									$phlebotomist = $tests[$i]['phlebotomist'];
									$laboratory_supervisor = $tests[$i]['laboratory_supervisor'];
									$pathologist = $tests[$i]['pathologist'];
									$comments = $tests[$i]['comments'];


									if($phlebotomist != 0 && $phlebotomist != ""){
										$phlebotomist_full_name = $this->onehealth_model->getPersonnelFullName($phlebotomist);
										$phlebotomist_qualification = $this->onehealth_model->getPersonnelQualification($phlebotomist);
										$phlebotomist_signature = $this->onehealth_model->getPersonnelSignature($phlebotomist);
									}
									

									if($laboratory_officer_2 != 0 && $laboratory_officer_2 != ""){
										$laboratory_officer_2_full_name = $this->onehealth_model->getPersonnelFullName($laboratory_officer_2);
										$laboratory_officer_2_qualification = $this->onehealth_model->getPersonnelQualification($laboratory_officer_2);
										$laboratory_officer_2_signature = $this->onehealth_model->getPersonnelSignature($laboratory_officer_2);
									}

									if($laboratory_supervisor != 0 && $laboratory_supervisor != ""){
										$laboratory_supervisor_full_name = $this->onehealth_model->getPersonnelFullName($laboratory_supervisor);
										$laboratory_supervisor_qualification = $this->onehealth_model->getPersonnelQualification($laboratory_supervisor);
										$laboratory_supervisor_signature = $this->onehealth_model->getPersonnelSignature($laboratory_supervisor);
									}

									if($pathologist != 0 && $pathologist != ""){
										$pathologist_full_name = $this->onehealth_model->getPersonnelFullName($pathologist);
										$pathologist_qualification = $this->onehealth_model->getPersonnelQualification($pathologist);
										$pathologist_signature = $this->onehealth_model->getPersonnelSignature($pathologist);
									}

									$response .= '<tr>';
									$response .= '<td></td>';
									$response .= '<td>'.$testname.'</td>';
									$response .= '<td>'.$testresult.'</td>';
									$response .= '<td>'.$range.'</td>';
									$response .= '<td>'.$flag.'</td>';
									$response .= '<td>'.$methodology.'</td>';
									$response .= '</tr>';

									if($last_sub_test == 1){

										if($about_test != ""){
											$response .= "<tr>";
											$response .= "<td colspan='100%'>";
											// $response .= "<h5>Findings: </h5>";
											$response .= '<div id="about-us-editor-'.$super_test_id.'" style="border:0;">';

						            		$response .= '</div>';
						            		$response .= "</td>";
											$response .= "</tr>";
										}

										if($comments != ""){
											$response .= "<tr>";
											$response .= "<td colspan='100%'>";
											$response .= "<h5>Pathologist's Comments: </h5>";
											$response .= '<p>'.$comments.'</p>';

						            		$response .= '</div>';
						            		$response .= "</td>";
											$response .= "</tr>";
										}


										

										$response .= "<tr class='container'>";

										$response .= "<td colspan='100%' style='padding: 20px;'>";
										$response .= "<h5>Contributors: </h5>";
										

										
										
										if($laboratory_officer_2 != 0 && $laboratory_officer_2 != ""){
											$response .= "<p style='margin-bottom: 0;'><span class='bold'>Laboratory Officer 2: </span><span style='text-transform:capitalize;'>".$laboratory_officer_2_full_name."</span> <span>".$laboratory_officer_2_qualification."</span></p>";

										}

										if($laboratory_supervisor != 0 && $laboratory_supervisor != ""){
											$response .= "<p style='margin-bottom: 0;'><span class='bold'>Laboratory Supervisor: </span><span style='text-transform:capitalize;'>".$laboratory_supervisor_full_name."</span> <span>".$laboratory_supervisor_qualification."</span></p>";

											$response .= "<img src='".base_url('assets/images/'.$laboratory_supervisor_signature)."' style='height:30px; width: 117px;'>";
										}

										if($pathologist != 0 && $pathologist != ""){
											$response .= "<p style='margin-bottom: 0;'><span class='bold'>Pathologist: </span><span style='text-transform:capitalize;'>".$pathologist_full_name."</span> <span>".$pathologist_qualification."</span></p>";

											$response .= "<img src='".base_url('assets/images/'.$pathologist_signature)."' style='height:30px; width: 117px;'>";										
										}
										
									}
								}
							}else if($main_test && !$has_sub_test){
								if($lab_structure == "mini"){
									
									$laboratory_officer_2 = $tests[$i]['laboratory_officer_2'];
									$phlebotomist = $tests[$i]['phlebotomist'];
									$comments = $tests[$i]['comments'];

									if($phlebotomist != 0 && $phlebotomist != ""){
										$phlebotomist_full_name = $this->onehealth_model->getPersonnelFullName($phlebotomist);
										$phlebotomist_qualification = $this->onehealth_model->getPersonnelQualification($phlebotomist);
										$phlebotomist_signature = $this->onehealth_model->getPersonnelSignature($phlebotomist);
									}
									

									if($laboratory_officer_2 != 0 && $laboratory_officer_2 != ""){
										$laboratory_officer_2_full_name = $this->onehealth_model->getPersonnelFullName($laboratory_officer_2);
										$laboratory_officer_2_qualification = $this->onehealth_model->getPersonnelQualification($laboratory_officer_2);
										$laboratory_officer_2_signature = $this->onehealth_model->getPersonnelSignature($laboratory_officer_2);
									}

									$response .= '<tr>';
									$response .= "<td style='font-weight:bolder;'>".$index."</td>";
									$response .= "<td style='font-weight:bolder;'>".$testname."</td>";
									$response .= '<td>'.$testresult.'</td>';
									$response .= '<td>'.$range.'</td>';
									$response .= '<td>'.$flag.'</td>';
									$response .= '<td>'.$methodology.'</td>';
									$response .= '</tr>';

									if($about_test != ""){
										$response .= "<tr>";
										$response .= "<td colspan='100%'>";
										// $response .= "<h5>Findings: </h5>";
										$response .= '<div id="about-us-editor-'.$index.'" style="border:0;">';

					            		$response .= '</div>';
					            		$response .= "</td>";
										$response .= "</tr>";
									}
									

									if($comments != ""){
										$response .= "<tr>";
										$response .= "<td colspan='100%'>";
										$response .= "<h5>Comments: </h5>";
										$response .= '<p>'.$comments.'</p>';

					            		$response .= '</div>';
					            		$response .= "</td>";
										$response .= "</tr>";
									}

									

									$response .= "<tr class='container'>";

									$response .= "<td colspan='100%' style='padding: 20px;'>";
									$response .= "<h5>Contributors: </h5>";
									
									
									if($laboratory_officer_2 != 0 && $laboratory_officer_2 != ""){
										$response .= "<p style='margin-bottom: 0;'><span class='bold'>Laboratory Officer 2: </span><span style='text-transform:capitalize;'>".$laboratory_officer_2_full_name."</span> <span>".$laboratory_officer_2_qualification."</span></p>";

										$response .= "<img src='".base_url('assets/images/'.$laboratory_officer_2_signature)."' style='height:30px; width: 117px;'>";
									}
									$response .= '</div>';
								}else if($lab_structure == "standard" || $lab_structure == "maximum"){
									$laboratory_officer_2 = $tests[$i]['laboratory_officer_2'];
									$phlebotomist = $tests[$i]['phlebotomist'];
									$laboratory_supervisor = $tests[$i]['laboratory_supervisor'];
									$pathologist = $tests[$i]['pathologist'];
									$comments = $tests[$i]['comments'];

									if($phlebotomist != 0 && $phlebotomist != ""){
										$phlebotomist_full_name = $this->onehealth_model->getPersonnelFullName($phlebotomist);
										$phlebotomist_qualification = $this->onehealth_model->getPersonnelQualification($phlebotomist);
										$phlebotomist_signature = $this->onehealth_model->getPersonnelSignature($phlebotomist);
									}
									

									if($laboratory_officer_2 != 0 && $laboratory_officer_2 != ""){
										$laboratory_officer_2_full_name = $this->onehealth_model->getPersonnelFullName($laboratory_officer_2);
										$laboratory_officer_2_qualification = $this->onehealth_model->getPersonnelQualification($laboratory_officer_2);
										$laboratory_officer_2_signature = $this->onehealth_model->getPersonnelSignature($laboratory_officer_2);
									}

									if($laboratory_supervisor != 0 && $laboratory_supervisor != ""){
										$laboratory_supervisor_full_name = $this->onehealth_model->getPersonnelFullName($laboratory_supervisor);
										$laboratory_supervisor_qualification = $this->onehealth_model->getPersonnelQualification($laboratory_supervisor);
										$laboratory_supervisor_signature = $this->onehealth_model->getPersonnelSignature($laboratory_supervisor);
									}

									if($pathologist != 0 && $pathologist != ""){
										$pathologist_full_name = $this->onehealth_model->getPersonnelFullName($pathologist);
										$pathologist_qualification = $this->onehealth_model->getPersonnelQualification($pathologist);
										$pathologist_signature = $this->onehealth_model->getPersonnelSignature($pathologist);
									}

									$response .= '<tr>';
									$response .= "<td style='font-weight:bolder;'>".$index."</td>";
									$response .= "<td style='font-weight:bolder;'>".$testname."</td>";
									$response .= '<td>'.$testresult.'</td>';
									$response .= '<td>'.$range.'</td>';
									$response .= '<td>'.$flag.'</td>';
									$response .= '<td>'.$methodology.'</td>';
									$response .= '</tr>';

									if($about_test != ""){
										$response .= "<tr>";
										$response .= "<td colspan='100%'>";
										// $response .= "<h5>Findings: </h5>";
										$response .= '<div id="about-us-editor-'.$index.'" style="border:0;">';

					            		$response .= '</div>';
					            		$response .= "</td>";
										$response .= "</tr>";
									}


									if($comments != ""){
										$response .= "<tr>";
										$response .= "<td colspan='100%'>";
										$response .= "<h5>Pathologist's Comments: </h5>";
										$response .= '<p>'.$comments.'</p>';

					            		$response .= '</div>';
					            		$response .= "</td>";
										$response .= "</tr>";
									}

									
									$response .= "<tr class='container'>";

									$response .= "<td colspan='100%' style='padding: 20px;'>";
									$response .= "<h5>Contributors: </h5>";
									

									
									
									if($laboratory_officer_2 != 0 && $laboratory_officer_2 != ""){
										$response .= "<p style='margin-bottom: 0;'><span class='bold'>Laboratory Officer 2: </span><span style='text-transform:capitalize;'>".$laboratory_officer_2_full_name."</span> <span>".$laboratory_officer_2_qualification."</span></p>";

									}

									if($laboratory_supervisor != 0 && $laboratory_supervisor != ""){
										$response .= "<p style='margin-bottom: 0;'><span class='bold'>Laboratory Supervisor: </span><span style='text-transform:capitalize;'>".$laboratory_supervisor_full_name."</span> <span>".$laboratory_supervisor_qualification."</span></p>";

										$response .= "<img src='".base_url('assets/images/'.$laboratory_supervisor_signature)."' style='height:30px; width: 117px;'>";	
									}

									if($pathologist != 0 && $pathologist != ""){
										$response .= "<p style='margin-bottom: 0;'><span class='bold'>Pathologist: </span><span style='text-transform:capitalize;'>".$pathologist_full_name."</span> <span>".$pathologist_qualification."</span></p>";

										$response .= "<img src='".base_url('assets/images/'.$pathologist_signature)."' style='height:30px; width: 117px;'>";	
									}
									
									
								}
							}
						}
						
					}
					echo $response;
				?>
			</tbody>
			
		</table>
		<?php } ?>

		<?php
		for($j = 0; $j < count($tests); $j++){
			$testname = $tests[$j]['testname'];
			$images = $tests[$j]['images'];
			if($images != ""){
				$image_arr = explode(",", $images);
				$images_count = count($image_arr);
		?>

		<h3 id="test-image-result-heading"><?php echo $testname; ?>'s Result Images: </h3>
		<div class="row">
		<?php for($i = 0; $i < $images_count; $i++){ ?>
			
				<a target="_blank" href="<?php echo base_url('assets/images/'.$image_arr[$i]); ?>" class="col-6">
					<img align="center"  src="<?php echo base_url('assets/images/'.$image_arr[$i]); ?>" alt="" class="col-12" style="margin-bottom: 20px;">
				</a>
			
		<?php 
				}
		?>
		</div>
		<?php
			}	 
		}	

		?>

		
		<div class="footer-right-div" align="right">
			<h3 class="pathology-placeholder">Lab Id: </h3>
			<h3 class="pathology-number"><?php echo $lab_id; ?></h3>
			<div class="" style="height: 2px"></div>
			<h3 class="initiation-code-placeholder">Initiation Code: </h3>
			<h3 class="initiation-code-name"><?php echo $initiation_code; ?></h3>
		</div>

		<?php if($facility_name != "Everight Diagnostics"){ ?>
		<div class="footer-center" align="center">
			<h3 class="credit"><?php echo $facility_name; ?></h3>
			<!-- <hy3 class="credit-us">OneHealth Issues Global Limited</h3> -->
			<p class="our-email">support@onehealthpoints.com</p>
		</div>
		<?php } ?>



	</div>
</body>
<script>
	$(document).ready(function () {
		<?php  
		if(!$radiology_only){
		for($i = 0; $i < count($tests); $i++){
			$sub_dept_id = $tests[$i]['sub_dept_id'];

			if($sub_dept_id == 6){
				$index = $tests[$i]['i'];
				$radiology_comments = $tests[$i]['radiology_comments'];

		?>
				var quill_<?php echo $index; ?> =  new Quill('#editor-<?php echo $index; ?>', {
				    theme : 'snow',
				    readOnly : true,
				    modules : {
					      "toolbar": false
					  }
				});

				quill_<?php echo $index; ?>.setContents(JSON.parse(<?php echo $radiology_comments; ?>));
		<?php

			}
		}
		}else{
			$index = 0;
		?>

		var quill_<?php echo $index; ?> =  new Quill('#editor-<?php echo $index; ?>', {
		    theme : 'snow',
		    readOnly : true,
		    modules : {
			      "toolbar": false
			  }
		});

		quill_<?php echo $index; ?>.setContents(JSON.parse(<?php echo $radiology_comments; ?>));
		<?php
		}
		?>


		<?php
		if(!$radiology_only){
		for($i = 0; $i < count($tests); $i++){
			$sub_dept_id = $tests[$i]['sub_dept_id'];

			
			$index = $tests[$i]['i'];
			$about_test = $tests[$i]['about_test'];
			$main_test = $tests[$i]['main_test'];
			if($about_test != "" && $main_test == 1){

		?>
				var about_quill_<?php echo $index; ?> =  new Quill('#about-us-editor-<?php echo $index; ?>', {
				    theme : 'snow',
				    readOnly : true,
				    modules : {
					      "toolbar": false
					  }
				});

				about_quill_<?php echo $index; ?>.setContents(JSON.parse(<?php echo $about_test; ?>));
		<?php
			}
		}
			
			
		}else{
			$index = 0;
				if($about_test != ""){ 
		?>

		var about_quill_<?php echo $index; ?> =  new Quill('#about-us-editor-<?php echo $index; ?>', {
		    theme : 'snow',
		    readOnly : true,
		    modules : {
			      "toolbar": false
			}
		});

		about_quill_<?php echo $index; ?>.setContents(JSON.parse(<?php echo $about_test; ?>));
		<?php
			}
		}
		?>
	})
	
</script>

<script src="<?php echo base_url('assets/js/letter_avatar.js') ?>"></script>
<script>

</script>
</html>