<?php
if(isset($tests) && is_array($tests)){
	if($this->onehealth_model->checkIfTestsAreInTheRightFormatForEverightResultFile($tests)){
		$main_test_pos = $this->onehealth_model->getMainTestPositionForEverightResultFile($tests);
		$has_sub_test = $tests[$main_test_pos]['has_sub_test'];
		$sub_dept_id = $tests[$main_test_pos]['sub_dept_id'];
		$range_enabled = $this->onehealth_model->checkIfOneOfTheseTestsHaveRangeEnabledForEverightResultFile($tests);
		$methodogy_available = $this->onehealth_model->checkIfOneOfTheseTestsHaveMethodologyForEverightResultFile($tests);

		$range_enabled_sub_test = $this->onehealth_model->checkIfOneOfTheseTestsHaveRangeEnabledForEverightResultFileSubTest($tests);
		$methodogy_available_sub_test = $this->onehealth_model->checkIfOneOfTheseTestsHaveMethodologyForEverightResultFileSubTest($tests);
		if($has_sub_test){
			for($i = 0; $i < count($tests); $i++){
				$test = $tests[$i];
				if($test['last_sub_test'] == 1){
					$last_sub_test_pos = $i;
				}
			}
		}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Result File</title>
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/quill.snow.css'); ?>" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="<?php echo base_url('assets/js/quill.js') ?>"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
	<style>
		body{
			background: #E8E8E8;
		}
		.header{
			padding: 20px;
		}
		.result-container{
			background: #fff;
			border-radius: 5px;
			margin-top: 15px;
			margin-bottom: 15px;
			width: 850px;
			padding: 20px;
		}
		.header .header-title{
			text-transform: uppercase;
			font-weight: bold;
		}

		.header .header-param{
			text-transform: uppercase;
			font-weight: normal;
		}

		.test-section{
			margin-top: 20px;
		}

		.test-section .main-title{
			font-size: 24px;
			font-weight: bold;
			display: inline-block;
			border-bottom: 2px solid #000;
			text-transform: uppercase;
		}
		.test-section #test-table{
			margin-top: 20px;
		}

		.test-section #test-table td{
			font-weight: bold;
			font-size: 14px;
		}

		.test-section .comment-section{
			margin-top: 20px;
		}

		.test-section .comment-section .comment-title{
			font-size: 18px;
			text-transform: uppercase;
			font-weight: bold;
		}

		.test-section .comment-section .comment-title .comment-content{
			font-size: 16px;
			text-transform: none;
			font-weight: bold;
		}

		.test-section .personnel-section{
			margin-top: 50px;
		}

		.test-section .personnel-section .personnel-info .signature-img{
			width: 150px;
			height: 100px;
		}
		.test-section .personnel-section .personnel-info .personnel-name{
			text-transform: uppercase;
			font-size: 17px;
			font-weight: bold;
			margin-bottom: 0;
		}

		.test-section .personnel-section .personnel-info .personnel-portfolio{
			font-size: 15px;
			font-weight: bold;
			margin-bottom: 0;
		}

		.test-section .personnel-section .personnel-info .personnel-qualification{
			font-size: 17px;
			font-weight: bold;
			margin-bottom: 0;
		}

		.test-section .clinical-significance-section{
			margin-top: 30px;
		}

		.test-section .clinical-significance-section .clinical-significance-title{
			text-transform: uppercase;
			font-weight: bold;
			font-size: 18px;
			margin-bottom: 0;
		}

		.test-section .clinical-significance-section .clinical-significance-content{
			font-size: 15px;
		}

		.test-section .images-div{
			margin-top: 30px;
		}
	</style>
</head>
<body>

	<div class="container result-container">
		<div class="row header">
			<div class="col-sm-6">
				<p class="header-title">patient name: <span class="header-param"><?php echo $bio_data['patient_name']; ?></span></p>
				<p class="header-title">referred by: <span class="header-param"><?php echo $referring_dr; ?></span></p>
				<p class="header-title">reporting date: <span class="header-param"><?php echo $date; ?></span></p>
			</div>
			<div class="col-sm-6">
				<p class="header-title">age: <span class="header-param"><?php echo $bio_data['age']; ?></span></p>
				<p class="header-title">sex: <span class="header-param"><?php echo $bio_data['sex']; ?></span></p>
				<p class="header-title">lab no: <span class="header-param"><?php echo $lab_id; ?></span></p>
			</div>
		</div>

		<section class="test-section">
			<div class="text-center">
				<?php if($sub_dept_id != 6){ ?>
				<h3 class="main-title"><?php echo $tests[$main_test_pos]['testname']; ?> report</h3>
				<?php }else{ ?>
				<h3 class="main-title"><?php echo $tests[$main_test_pos]['testname']; ?></h3>
				<?php } ?>
			</div>

			<?php if($sub_dept_id != 6){ ?>
	
			<table id="test-table" class="table ">
				<thead>
					<?php if(!$has_sub_test){ ?>
					<tr>
						<th>TEST</th>
						<th>RESULT</th>
						<?php if($range_enabled){ ?>
						<th>REF. RANGE</th>
						<th>FLAG</th>
						<?php } ?>
						<?php if($methodogy_available){ ?>
						<th>METHODOLOGY</th>
						<?php } ?>
					</tr>
					<?php }else{ ?>
					<tr>
						<th>TEST</th>
						<th>RESULT</th>
						<?php if($range_enabled_sub_test){ ?>
						<th>REF. RANGE</th>
						<th>FLAG</th>
						<?php } ?>
						<?php if($methodogy_available_sub_test){ ?>
						<th>METHODOLOGY</th>
						<?php } ?>
					</tr>
					<?php } ?>
				</thead>
				<tbody>
					<?php if(!$has_sub_test){ ?>
					<tr>
						<td><?php echo $tests[$main_test_pos]['testname']; ?></td>
						<td><?php echo $tests[$main_test_pos]['testresult']; ?></td>
						<?php if($range_enabled){ ?>
						<td><?php echo $tests[$main_test_pos]['range']; ?></td>
						<td><?php echo $tests[$main_test_pos]['flag']; ?></td>
						<?php } ?>

						<?php if($methodogy_available){ ?>
						<td><?php echo $tests[$main_test_pos]['methodology']; ?></td>
						<?php } ?>
					</tr>
					<?php }else{ ?>
					<?php
						for($i = 0; $i < count($tests); $i++){
							$test = $tests[$i];
							if($i != $main_test_pos){
					?>
					<tr>
						<td><?php echo $tests[$i]['testname']; ?></td>
						<td><?php echo $tests[$i]['testresult']; ?></td>
						<?php if($range_enabled_sub_test){ ?>
						<td><?php echo $tests[$i]['range']; ?></td>
						<td><?php echo $tests[$i]['flag']; ?></td>
						<?php } ?>

						<?php if($methodogy_available_sub_test){ ?>
						<td><?php echo $tests[$i]['methodology']; ?></td>
						<?php } ?>
					</tr>
					<?php
							}
						}
					?>
					<?php }?>
				</tbody>
			</table>

			<?php }else{ ?>
			<h5 class="text-center">Findings</h5>
			<div id="editor-0" style="">

		    </div>

			<?php } ?>

	
			<?php if($sub_dept_id != 6){ ?>
			<div class="comment-section">
				<p class="comment-title">comment: <span class="comment-content"><?php echo $tests[$main_test_pos]['comments']; ?></span></p>
			</div>
			<?php } ?>

			<div class="personnel-section">
				<?php if($sub_dept_id == 6){ ?>
				<div class="row">

					<?php if($tests[$main_test_pos]['radiographer'] != 0 && $tests[$main_test_pos]['radiographer'] != ""){
						$radiographer = $tests[$main_test_pos]['radiographer'];
						if($radiographer != 0 && $radiographer != ""){
							$radiographer_full_name = $this->onehealth_model->getPersonnelFullName($radiographer);
							$radiographer_qualification = $this->onehealth_model->getPersonnelQualification($radiographer);
							$radiographer_signature = $this->onehealth_model->getPersonnelSignature($radiographer);
						}

					?>
					<div class="personnel-info col-6">
						<img src="<?php echo base_url('assets/images/'.$radiographer_signature) ?>" alt="" class="signature-img">
						<p class="personnel-name"><?php echo $radiographer_full_name; ?></p>
						<p class="personnel-portfolio">Radiographer</p>
						<p class="personnel-qualification"><?php echo $radiographer_qualification; ?></p>
					</div>
					<?php } ?>



					<?php if($tests[$main_test_pos]['sonologist'] != 0 && $tests[$main_test_pos]['sonologist'] != ""){
						$sonologist = $tests[$main_test_pos]['sonologist'];
						if($sonologist != 0 && $sonologist != ""){
							$sonologist_full_name = $this->onehealth_model->getPersonnelFullName($sonologist);
							$sonologist_qualification = $this->onehealth_model->getPersonnelQualification($sonologist);
							$sonologist_signature = $this->onehealth_model->getPersonnelSignature($sonologist);
						}

					?>
					<div class="personnel-info col-6">
						<img src="<?php echo base_url('assets/images/'.$sonologist_signature) ?>" alt="" class="signature-img">
						<p class="personnel-name"><?php echo $sonologist_full_name; ?></p>
						<p class="personnel-portfolio">Sonologist</p>
						<p class="personnel-qualification"><?php echo $sonologist_qualification; ?></p>
					</div>
					<?php } ?>


					<?php if($tests[$main_test_pos]['radiologist'] != 0 && $tests[$main_test_pos]['radiologist'] != ""){
						$radiologist = $tests[$main_test_pos]['radiologist'];
						if($radiologist != 0 && $radiologist != ""){
							$radiologist_full_name = $this->onehealth_model->getPersonnelFullName($radiologist);
							$radiologist_qualification = $this->onehealth_model->getPersonnelQualification($radiologist);
							$radiologist_signature = $this->onehealth_model->getPersonnelSignature($radiologist);
						}

					?>
					<div class="personnel-info col-6">
						<img src="<?php echo base_url('assets/images/'.$radiologist_signature) ?>" alt="" class="signature-img">
						<p class="personnel-name"><?php echo $radiologist_full_name; ?></p>
						<p class="personnel-portfolio">Radiologist</p>
						<p class="personnel-qualification"><?php echo $radiologist_qualification; ?></p>
					</div>
					<?php } ?>

					<?php if($tests[$main_test_pos]['cardiologist'] != 0 && $tests[$main_test_pos]['cardiologist'] != ""){
						$cardiologist = $tests[$main_test_pos]['cardiologist'];
						if($cardiologist != 0 && $cardiologist != ""){
							$cardiologist_full_name = $this->onehealth_model->getPersonnelFullName($cardiologist);
							$cardiologist_qualification = $this->onehealth_model->getPersonnelQualification($cardiologist);
							$cardiologist_signature = $this->onehealth_model->getPersonnelSignature($cardiologist);
						}

					?>
					<div class="personnel-info col-6">
						<img src="<?php echo base_url('assets/images/'.$cardiologist_signature) ?>" alt="" class="signature-img">
						<p class="personnel-name"><?php echo $cardiologist_full_name; ?></p>
						<p class="personnel-portfolio">Cardiologist</p>
						<p class="personnel-qualification"><?php echo $cardiologist_qualification; ?></p>
					</div>
					<?php } ?>

				</div>
				<?php }else{ ?>
				<div class="row">
					<?php if($tests[$main_test_pos]['lab_structure'] == "mini"){ ?>
					<?php if(!$has_sub_test){ ?>
					<?php if($tests[$main_test_pos]['laboratory_officer_2'] != 0 && $tests[$main_test_pos]['laboratory_officer_2'] != ""){
						$laboratory_officer_2 = $tests[$main_test_pos]['laboratory_officer_2'];
						if($laboratory_officer_2 != 0 && $laboratory_officer_2 != ""){
							$laboratory_officer_2_full_name = $this->onehealth_model->getPersonnelFullName($laboratory_officer_2);
							$laboratory_officer_2_qualification = $this->onehealth_model->getPersonnelQualification($laboratory_officer_2);
							$laboratory_officer_2_signature = $this->onehealth_model->getPersonnelSignature($laboratory_officer_2);
						}

					?>
					<div class="personnel-info col-6">
						<img src="<?php echo base_url('assets/images/'.$laboratory_officer_2_signature) ?>" alt="" class="signature-img">
						<p class="personnel-name"><?php echo $laboratory_officer_2_full_name; ?></p>
						<p class="personnel-portfolio">Laboratory Officer 2</p>
						<p class="personnel-qualification"><?php echo $laboratory_officer_2_qualification; ?></p>
					</div>
					<?php } ?>
					<?php }else{ ?>


					<?php if($tests[$last_sub_test_pos]['laboratory_officer_2'] != 0 && $tests[$last_sub_test_pos]['laboratory_officer_2'] != ""){
						$laboratory_officer_2 = $tests[$last_sub_test_pos]['laboratory_officer_2'];
						if($laboratory_officer_2 != 0 && $laboratory_officer_2 != ""){
							$laboratory_officer_2_full_name = $this->onehealth_model->getPersonnelFullName($laboratory_officer_2);
							$laboratory_officer_2_qualification = $this->onehealth_model->getPersonnelQualification($laboratory_officer_2);
							$laboratory_officer_2_signature = $this->onehealth_model->getPersonnelSignature($laboratory_officer_2);
						}

					?>
					<div class="personnel-info col-6">
						<img src="<?php echo base_url('assets/images/'.$laboratory_officer_2_signature) ?>" alt="" class="signature-img">
						<p class="personnel-name"><?php echo $laboratory_officer_2_full_name; ?></p>
						<p class="personnel-portfolio">Laboratory Officer 2</p>
						<p class="personnel-qualification"><?php echo $laboratory_officer_2_qualification; ?></p>
					</div>
					<?php } ?>
					<?php } ?>

					


					<?php }else if($tests[$main_test_pos]['lab_structure']  == "standard" || $tests[$main_test_pos]['lab_structure']  == "maximum"){ ?>

					<?php if($tests[$main_test_pos]['laboratory_supervisor'] != 0 && $tests[$main_test_pos]['laboratory_supervisor'] != ""){
						$laboratory_supervisor = $tests[$main_test_pos]['laboratory_supervisor'];
						if($laboratory_supervisor != 0 && $laboratory_supervisor != ""){
							$laboratory_supervisor_full_name = $this->onehealth_model->getPersonnelFullName($laboratory_supervisor);
							$laboratory_supervisor_qualification = $this->onehealth_model->getPersonnelQualification($laboratory_supervisor);
							$laboratory_supervisor_signature = $this->onehealth_model->getPersonnelSignature($laboratory_supervisor);
						}

					?>
					<div class="personnel-info col-6">
						<img src="<?php echo base_url('assets/images/'.$laboratory_supervisor_signature) ?>" alt="" class="signature-img">
						<p class="personnel-name"><?php echo $laboratory_supervisor_full_name; ?></p>
						<p class="personnel-portfolio">Medical Laboratory Scientist</p>
						<p class="personnel-qualification"><?php echo $laboratory_supervisor_qualification; ?></p>
					</div>
					<?php } ?>


					<?php if($tests[$main_test_pos]['pathologist'] != 0 && $tests[$main_test_pos]['pathologist'] != ""){
						$pathologist = $tests[$main_test_pos]['pathologist'];
						if($pathologist != 0 && $pathologist != ""){
							$pathologist_full_name = $this->onehealth_model->getPersonnelFullName($pathologist);
							$pathologist_qualification = $this->onehealth_model->getPersonnelQualification($pathologist);
							$pathologist_signature = $this->onehealth_model->getPersonnelSignature($pathologist);
						}

					?>
					<div class="personnel-info col-6">
						<img src="<?php echo base_url('assets/images/'.$pathologist_signature) ?>" alt="" class="signature-img">
						<p class="personnel-name"><?php echo $pathologist_full_name; ?></p>
						<p class="personnel-portfolio">Consultant Pathologist</p>
						<p class="personnel-qualification"><?php echo $pathologist_qualification; ?></p>
					</div>
					<?php } ?>


					<?php } ?>

				</div>
				<?php } ?>
			</div>
	
			<?php if($tests[$main_test_pos]['about_test'] != ""){ ?>
			<div class="clinical-significance-section">
				<p class="clinical-significance-title">Clinical Significance: </p>
				<p class="clinical-significance-content" id="about-us-editor-0" style="border: 0;"></p>
			</div>

			<?php } ?>
			

			<div class="images-div">
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
			</div>
		</section>
	</div>

</body>
<script src="<?php echo base_url('assets/js/letter_avatar.js') ?>"></script>

<script>
	$(document).ready(function () {
		<?php if($sub_dept_id == 6){ ?>
		var quill =  new Quill('#editor-0', {
		    theme : 'snow',
		    readOnly : true,
		    modules : {
			      "toolbar": false
			  }
		});

		quill.setContents(JSON.parse(<?php echo $tests[$main_test_pos]['radiology_comments']; ?>));
		<?php } ?>

		<?php if($tests[$main_test_pos]['about_test'] != ""){ ?>
			var about_quill =  new Quill('#about-us-editor-0', {
			    theme : 'snow',
			    readOnly : true,
			    modules : {
				      "toolbar": false
				  }
			});

			about_quill.setContents(JSON.parse(<?php echo $tests[$main_test_pos]['about_test']; ?>));
		<?php } ?>
	})
</script>

</html>
<?php
	}
}
?>