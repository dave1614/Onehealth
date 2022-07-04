<!DOCTYPE html>
<html>
<head>
	<title>Reciept For <?php echo $patient_name; ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<style>
		body{
			font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
			background: #E8E8E8;
		}
		.container{
			padding: 30px;
			padding-top: 50px;
			background: #fff;
			border-radius: 5px;
			width: 900px;
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
			margin-top: -22px;
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
			text-transform: capitalize;
			font-weight: lighter;
			margin-top: 0;
		}

		#patient-name{
			font-size: 13px;
			font-weight: bold;
		}

		/*table, th, td {
		  border: 1px solid black;
		}*/

		#tests-table {
			margin-top: 30px;
		  margin-bottom: 30px;
		  border-collapse: collapse;
		  width: 100%;
		  font-size: 13px;
		}

		#tests-table td, #tests-table th {
		  border: 1px solid #ddd;
		  padding: 8px;
		}

		#tests-table tr:nth-child(even){background-color: #f2f2f2;}

		#tests-table tr:hover {background-color: #ddd;}

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
		}


		.our-email{
			font-size: 10px;
			color: #0277BD;
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
		}

		#patient-name-placeholder{
			font-size: 15px;
			display: inline;
		}
		#patient-name{
			font-size: 13px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}

		#patient-age-placeholder{
			font-size: 15px;
			display: inline;
		}
		#patient-age{
			font-size: 13px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}
		#patient-sex-placeholder{
			font-size: 15px;
			display: inline;
		}
		#patient-sex{
			font-size: 13px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}
		#patient-address-placeholder{
			font-size: 15px;
			display: inline;
		}
		#patient-address{
			font-size: 13px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}
		#patient-referring-dr-placeholder{
			font-size: 15px;
			display: inline;
		}
		#patient-referring-dr{
			font-size: 13px;
			font-weight: lighter;
			font-style: italic;
			display: inline;
		}

	</style>
</head>
<body>
	<div class="container">
		<div class="top-row-cont">
			<?php
		
			 	// if($this->onehealth_model->checkIfFacilityHasLetterHeadingEnabled($facility_id)){
			?>
			<?php if($logo != ""){ ?>
			<img src="<?php echo $logo['src'];  ?>" id="facility-logo-img">
			<?php }else{ ?>
			<img avatar="<?php echo $facility_name; ?>" col="<?php echo $color; ?>" id="facility-logo-img">
			<?php } ?>
			<?php  ?>	
			<div id="company-name-div" align="right">
				<img src="<?php echo base_url('assets/images/logo_small.jpg'); ?>" id="company-logo" alt="">
				<h2 id="company-name">OneHealth Issues Global Limited</h2>
				<h4>Certificate No. <em><?php echo $certificate_number; ?></em></h4>
			</div>
		</div>
		<?php
		
		 	// if($this->onehealth_model->checkIfFacilityHasLetterHeadingEnabled($facility_id)){
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
			// }
		?>


		<h3 id="receipt-title">Death Certificate</h3>
		<h3 id="receipt-subtitle">This Is To Certify <b><?php echo $patient_name; ?></b> Of <b><?php echo $address; ?></b> Aged <b><?php echo $age . " " .  $age_unit; ?></b> Whose Death Occured On <b><?php echo date("j M Y h:i a",strtotime($time_of_death)); ?></b>. 
		<?php
		if($sex == "male"){
			echo "He";
		}else{
			echo "She";
		}
		?>
		  Was Until Death Managed By <b><?php echo $referring_facility;  ?></b>. The Body Was Received On <b><?php echo date("j M Y",strtotime($date_received)); ?></b> With <b><?php echo $autopsy_done_str; ?></b>. <b><?php echo $cause_of_death; ?></b>. </h3>

		<h3 id="receipt-subtitle">Witnessed my hand this day <b><?php echo $date; ?></b></h3>

		

		
		
		<div class="footer-right-div" align="left">
			
			<h3 class="sub-total-placeholder"><?php echo $pathologist_name; ?></h3>
			<h3 class="sub-total-name"><?php echo $pathologist_qualification; ?></h3>
			<div class="" style="height: 5px"></div>
			<img src="<?php echo $signature_url; ?>" style="width: 100px; height: 100px;" id="signature" alt="">
			<div class="" style="height: 5px"></div>
			
			
			
		</div>
		


		<div class="footer-center" align="center" style="margin-top: 30px">
			<h3 class="credit">For <?php echo $facility_name; ?></h3>
			<h3 class="credit-us">OneHealth Issues Global Limited</h3>
			<p class="our-email">support@onehealthissues.com</p>
		</div>



	</div>
</body>
<script src="<?php echo base_url('assets/js/letter_avatar.js') ?>"></script>
</html>