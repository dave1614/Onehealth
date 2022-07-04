<!DOCTYPE html>
<html>
<head>
	<title>Jspdf Test</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
	<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script>
		$(document).ready(function () {
		
		})
	</script>
	
</head>
<body>

	<button id="button">Click Me!</button>
<?php  
	// echo number_format($this->onehealth_model->getTotalPriceForTestsByInitiationCodeTakingIntoAccountPartFee(1,"4fccc9127460-29-07"),2) . "<br>";      		
	// var_dump($this->onehealth_model->getLastRowTestResult(1));
?>

<?php
	// echo $this->onehealth_model->getTotalBalanceForTests(1,"e72a7ebd6d37-24-07");
	// echo 431.6762 - 431.6762 ."<br>";
	// echo round((300.49542 - 300.5),2);
	// echo round(300.49542,2);
	// if($this->onehealth_model->getDrugsBalance(1,"4d70a1c75191-23-04") == 0){
	// 	echo "string";
	// }
	// $temp = rand(10000, 99999);
	// $this->onehealth_model->set_barcode($temp);
	// $last_paid_date = "4 May 2019";
	// echo date("j M Y",strtotime($last_paid_date . ' + 2 days'));

	// $url = base_url('assets/images/result_upload.json');

	// // echo $url;
	// $response = $this->onehealth_model->custom_curl($url,TRUE,[]);
	// // echo $response;
	// if($this->onehealth_model->isJson($response)){
	// 	$response = json_decode($response);
	// 	// var_dump($response);
	// 	if(is_array($response)){

	// 		for($i = 0; $i < count($response); $i++){
	// 			// $lab_id = 1001 + $i;
	// 			// $lab_id = $lab_id . "-" . date("y");
	// 			// // echo $lab_id . "<br>";
	// 			// $response[$i]->lab_id = $lab_id;
	// 			$lab_id = $response[$i]->lab_id;
	// 			echo $lab_id. "<br>";
	// 			foreach($response[$i] as $key => $value){
	// 				if($key != "lab_id"){
	// 					echo $key . " : " . $value . "<br>";
	// 					if($this->onehealth_model->uploadResultPerTest('tpbm2stanthonys',$key,$value,$lab_id)){
	// 						echo "successfully uploaded";
	// 					}
	// 				}
	// 			}
	// 			echo "<br><br><br>";

	// 		}
	// 	}
	// }else{
	// 	echo $response;
	// }

	// $recepient_arr = array(
	// 	'ikechukwunwogo@gmail.com'
	// );

	// if($this->onehealth_model->sendEmail($recepient_arr,"Test Subject","Test Message")){
	// 	echo "sent";
	// }

	
	// print_r($banks_arr);
	// $ip_address = $this->input->ip_address();
	// if($ip_address == "::1"){
	// 	echo "string";
	// }
	// $ip_address = "197.211.60.81";
	// if($this->input->valid_ip($ip_address)){
	// 	echo $ip_address;
	// 	$location_info = $this->onehealth_model->custom_curl("http://api.ipstack.com/" . $ip_address . "?access_key=04b8933ab2331d1a31e653053f909b43",TRUE);
	// 	// echo $location_info;
	// 	$location_info = json_decode($location_info);
	// 	if(is_object($location_info)){
	// 		var_dump($location_info);
	// 		echo $location_info->region_code;
	// 		// echo ;
	// 	}

	// }

	 // $this->image_lib->clear();

	// $all_facilities = $this->onehealth_model->getAllHealthFaciitiesThatAreHospitals();
	// foreach($all_facilities as $row){
	// 	$health_facility_id = $row->id;
	// 	$health_facility_name = $row->name;
	// 	$table_name = $this->onehealth_model->createpatientBioDataTableString($health_facility_id,$health_facility_name);
	// 	if($this->onehealth_model->tableExists($table_name)){
	// 		// $tests = $this->onehealth_model->getAllTestResults1($table_name);
	// 		// if(is_array($tests)){
	// 		// 	foreach($tests as $row){
	// 		// 		$paid = $row->paid;
	// 		// 		$initiation_code = $row->initiation_code;
	// 		// 	}
	// 		// }

	// 		if($this->onehealth_model->modifyPatientBioDataTable($table_name)){
	// 			echo "successful<br>";
	// 		}

	// 	}
	// }

	// var_dump($this->onehealth_model->runSchemaRecordsCheck());

	// echo url_title("library & statistics");

	// $all_labs = $this->onehealth_model->getSubDeptById(60);
	// if(is_array($all_labs)){
	// 	foreach($all_labs as $row){
	// 		$sub_dept_id = $row->id;
	// 		$name = $row->name;

	// 		if($this->onehealth_model->addOfficerToClinics("records",$sub_dept_id)){
	// 			echo "successful";
	// 		}

	// 		if($this->onehealth_model->addOfficerToClinics("nurse",$sub_dept_id)){
	// 			echo "successful";
	// 		}

	// 		if($this->onehealth_model->addOfficerToClinics("doctor",$sub_dept_id)){
	// 			echo "successful";
	// 		}

	// 		if($this->onehealth_model->addOfficerToClinics("hospital teller",$sub_dept_id)){
	// 			echo "successful";
	// 		}

	// 		if($this->onehealth_model->addOfficerToClinics("Multitasking Records Officer",$sub_dept_id)){
	// 			echo "successful";
	// 		}

	// 		if($this->onehealth_model->addOfficerToClinics("Multitasking Nurse",$sub_dept_id)){
	// 			echo "successful";
	// 		}


	// 	}
	// }
	
	

	// $all_facilities = $this->onehealth_model->getAllHealthFaciities();
	// if(is_array($all_facilities)){
	// 	$arr = array();
	// 	foreach($all_facilities as $row){
	// 		$health_facility_id = $row->id;
	// 		$health_facility_name = $row->name;

	// 		$patient_bio_data_table = $this->onehealth_model->createTestResultTableHeaderString($health_facility_id,$health_facility_name);
	// 		if($this->onehealth_model->addClinicIdColumnToTestResultTable($patient_bio_data_table)){
	// 			echo "successful";
	// 		}
	// 	}
	// }
	// $i = 4;
	// $time = "4:06:20pm";
	// $initiation_code = "ad0d91d1358b-28-05";
	// $patients_full_name = "Mr" . " " . "Godswill" . " " . "Ogidi";
	// $from = "Luth Lab";
	// $to = array("07051942325");
	// $body = $patients_full_name . " This Is To Alert You That " . $i . " Test(s) Have Been Selected For You At " . $time . " With Initiation Code: ".$initiation_code.". Login To Your Account At " . site_url() . " To View Your Tests.";
	// // $body = "Test message";

	// $this->onehealth_model->sendFacilitySms(1,$from,$to,$body);

	// if($this->onehealth_model->sendSms($from,$to,$body)){
	// 	echo "message sent successfully";
	// }
 //    $dob =  "10/27/1984 5:03 PM";
	// echo $this->onehealth_model->getPatientAge($dob);

	// echo $this->onehealth_model->getTotalBalanceForTests(1,"ea0fbf39d831-31-07");
    
    // var_dump(explode(",", "1132211cd38a77d14b41e1e9ba1ba6c3.jpg,542dcd453d2828fb0dcd0da9ea40a485.jpg,efc52b610e7207a39335763326feaf84.jpg,00e3feb4f7eca11e328637130f1fe0dd.jpg,b1a678b34da031490fa093f2457b5266.jpg"));
// $curr_date = date("Y-m-d");
// echo $curr_date;

// var_dump($this->onehealth_model->checkIfVitalSignsChanged(1,8,20,20,20,40,50,20));
// $date_1 = strtotime("29 Nov 2019 09:12:43pm") . "<br>";
// $date_2 = strtotime("29 Nov 2019 09:12:44pm") . "<br>";
// $date_3 = strtotime("29 Nov 2019 09:12:45pm") . "<br>";

// $array = array(
// 	'1' => $date_1,
// 	'2' => $date_2,
// 	'3' => $date_3
// );

// $max = max($array);

// $index = array_search($max, $array);
// echo $index;

// echo $this->onehealth_model->generateWardIdString();

// $admission_duration = 4;
// $ward_admission_fee = 10000;
// $ward_admission_duration = 7;
// $ward_admission_grace_days = 2;

// $amount_for_a_day = $ward_admission_fee / $ward_admission_duration;

// $amount_to_be_paid_by_user = round(($amount_for_a_day * $admission_duration),2);
// echo $amount_to_be_paid_by_user;

// Display the array element 
// print_r($arr); 
    
	echo (INTEGER) true . "<br>";
	$q = 0;
	
	// $sub_tests = $this->onehealth_model->getTestsSubTests("t1luthlab",1);
	// foreach($sub_tests as $row){
	// 	$q++;

	// 	echo $q . "<br>";
	// }

	// echo $this->onehealth_model->getPartFeePayingPercentageDiscountForPatient(1,"test12");
	// echo mb_strlen("This is Just A Random String");
	// echo $this->onehealth_model->getAmountAccruedByNoneFeePayingWardPatient(2,1);
	$this->onehealth_model->genereateMortuaryFieldsString("clinic_payments");
	// $this->onehealth_model->genereateMortuaryFieldsRulesString("mortuary_autopsy");

	// $dosage = 3;
	// $frequency_num = 2;
	// $frequency_time = "weekly";
	// $duration_num = 3;
	// $duration_time = "days";
	// $price = 100;

	// $array =  $this->onehealth_model->calculatePrescription($dosage, $frequency_num,$frequency_time,$duration_num,$duration_time,$price);

	// $all_facilities = $this->onehealth_model->getAllHealthFaciities();
	// if(is_array($all_facilities)){
	// 	$arr = array();
	// 	foreach($all_facilities as $row){
	// 		$health_facility_id = $row->id;
	// 		$health_facility_name = $row->name;

	// 		$health_facility_main_test_result_table = $this->onehealth_model->createTestResultMainTableHeaderString($health_facility_id,$health_facility_name);
	// 		if($this->onehealth_model->addCommentColumnToResultTable($health_facility_main_test_result_table)){
	// 			echo "successful";
	// 		}
	// 	}
	// 	// print_r($arr);
	// }
	// $time = date("j M Y h:i:sa");
	
 //    $num = $this->onehealth_model->calculatePrescription2 (1, 8,"hourly",1,"days");
 //    for($i = 0; $i < $num; $i++){
 //    	if($i > 0){
	//     	$time = date("j M Y h:i:sa", strtotime('+8 hours',strtotime($time)));
	//     }
 //    	echo date("j M Y",strtotime($time))."<br>";
 //    }
	

?>
	

<?php
// $rand = mt_rand(1000,500000);
// $rand = $rand / 1000;

// $all_facilities = $this->onehealth_model->getAllHealthFaciities();
// foreach($all_facilities as $row){
// 	$health_facility_id = $row->id;
// 	$health_facility_name = $row->name;
// 	$test_result_table_name = $this->onehealth_model->createTestResultTableHeaderString($health_facility_id,$health_facility_name);
// 	if($this->onehealth_model->alterTable($test_result_table_name)){
// 		echo "successful";
// 	}
// }

//Add Sub Depts To Pharmacy
// $form_array = array('NHIS Pharmacy','In-patient Pharmacy','Out-patient Pharmacy');
// $form_array = array_values(array_unique($form_array));
// $this->onehealth_model->addSubDeptsToDept($form_array,6);

// Add Personnel To Personnel SubDepts
// $sub_depts = $this->onehealth_model->getSubDeptsByDeptId(3);
// if(is_array($sub_depts)){
// 	foreach($sub_depts as $row){
// 		$sub_dept_id = $row->id;
// 		$personnel_array = array('hospital teller');
// 		$this->onehealth_model->addPersonnelToSubDept($personnel_array,$sub_dept_id,3);
// 	}
// }

// echo $rand;
// Result Test Table For All Facilities
// $all_facilities = $this->onehealth_model->getAllHealthFaciities();
// foreach($all_facilities as $row){
// 	$health_facility_id = $row->id;
// 	$health_facility_name = $row->name;
// 	$test_table_name = $this->onehealth_model->createTestTableHeaderString($health_facility_id,$health_facility_name);
// 	if($this->onehealth_model->create_health_facility_test_table($test_table_name)){
// 		$default_tests = $this->onehealth_model->getDefaultTests();
// 		if(is_array($default_tests)){
// 			foreach($default_tests as $row){
// 				$id = $row->id;
// 				$main_test_id = $row->main_test_id;
// 				$facility_name = $row->facility_name;
// 				$sub_dept_id = $row->sub_dept_id;
// 				$test_id = $row->test_id;
// 				$name = $row->name;
// 				$under = $row->under;
// 				$sample_required = $row->sample_required;
// 				$indication = $row->indication;
// 				$cost = $row->cost;
// 				$t_a = $row->t_a;
// 				$pppc = $row->pppc;
// 				$section = $row->section;
// 				$active = $row->active;
// 				$no = $row->no;
// 				$tests = $row->tests;
// 				$unit = $row->unit;
// 				$range_lower = $row->range_lower;
// 				$range_higher = $row->range_higher;
// 				$range_enabled = $row->range_enabled;
// 				$range_type = $row->range_type;
// 				$desirable_value = $row->desirable_value;
// 				$unit_enabled = $row->unit_enabled;
// 				$control_enabled = $row->control_enabled;
				
// 				$form_array = array(
// 					'main_test_id' => $main_test_id,
// 					'facility_name' => $health_facility_name,
// 					'under' => $under,
// 					'sub_dept_id' => $sub_dept_id,
// 					'test_id' => $test_id,
// 					'name' => $name,
// 					'sample_required' => $sample_required,
// 					'indication' => $indication,
// 					'cost' => $cost,
// 					't_a' => $t_a,
// 					'pppc' => $pppc,
// 					'section' => $section,
// 					'active' => $active,
// 					'no' => $no,
// 					'tests' => $tests,
// 					'unit' => $unit,
// 					'range_lower' => $range_lower,
// 					'range_higher' => $range_higher,
// 					'range_enabled' => $range_enabled,
// 					'range_type' => $range_type,
// 					'desirable_value' => $desirable_value,
// 					'unit_enabled' => $unit_enabled,
// 					'control_enabled' => $control_enabled
// 				);
				
// 				$this->onehealth_model->add_tests($test_table_name,$form_array);
// 			}					
// 		}
// 	}
// }

// Result Test Table For All Facilities
// $all_facilities = $this->onehealth_model->getAllHealthFaciities();
// foreach($all_facilities as $row){
// 	$health_facility_id = $row->id;
// 	$health_facility_name = $row->name;
// 	$test_table_name = $this->onehealth_model->createTestTableHeaderString($health_facility_id,$health_facility_name);
// 	if($this->onehealth_model->updateRadiologyTestFieldsControlRangeUnit($test_table_name)){
		
// 	}
// }

// Use To Generate Results JSON
// $all_facilities = $this->onehealth_model->getHealthFacilityInfoBySlug('1-luth-lab');
// foreach($all_facilities as $row){
// 	$health_facility_id = $row->id;
// 	$health_facility_name = $row->name;
// 	$dept_id = $this->onehealth_model->getDeptIdBySlug('pathology-laboratory-services');
// 	$sub_dept_id = $this->onehealth_model->getSubDeptIdBySlugAndDeptId('clinical-pathology',$dept_id);
// 	$personnel_id = $this->onehealth_model->getPersonnelIdBySlugDeptIdAndSubDeptId('laboratory-officer-2',$dept_id,$sub_dept_id);
// 	$health_facility_test_table_name = $this->onehealth_model->createTestTableHeaderString($health_facility_id,$health_facility_name);

// 	$health_facility_test_result_table_name = $this->onehealth_model->createTestResultTableHeaderString($health_facility_id,$health_facility_name);

// 	$health_facility_patient_db_table = $this->onehealth_model->createTestPatientTableHeaderString($health_facility_id,$health_facility_name);

// 	$patient_tests = $this->onehealth_model->getAllPatientsTests($health_facility_test_result_table_name,$sub_dept_id);

// 	$health_facility_main_test_result_table = $this->onehealth_model->createTestResultMainTableHeaderString($health_facility_id,$health_facility_name);
// 	if(is_array($patient_tests)){
// 		$date = date("j M Y");
// 		$time = date("H:i:s");
// 		foreach($patient_tests as $row){
			
// 			$id = $row->id;
// 			$test_deleted = false;
// 			$main_test_id = $row->main_test_id;

// 			$test_id = $this->onehealth_model->getTestIdByMainTestId($health_facility_test_table_name,$main_test_id);
			
// 			if($test_id == ""){
// 				$test_id = $row->test_id;
// 			}
// 			$test_name = $this->onehealth_model->getTestNameById($health_facility_test_table_name,$main_test_id);
			
			
// 			if($test_name == ""){
// 				$test_name = $row->test_name;
// 			}
// 			$lab_id = $row->lab_id;
// 			$paid = $row->paid;
// 			if($paid == 1){
			
			
// 				$form_array = array(
// 					'test_id' => $test_id,
// 					'lab_id' => $lab_id,
// 					'test_name' => $test_name,
// 					'main_test_id' => $main_test_id,
// 					'main_test' => 1,
					
// 					'date' => $date,
					
// 					'time' => $time
// 				);
				
// 				if($this->onehealth_model->checkIfThisTestHasBeenAdded($health_facility_main_test_result_table,$main_test_id,$lab_id) == false){
// 					$this->onehealth_model->addTestMainResult($form_array,$health_facility_main_test_result_table);
// 				}else{
// 					// echo $test_name;
// 					$this->onehealth_model->updateTestMainResult($form_array,$health_facility_main_test_result_table,$main_test_id,$lab_id);
// 				}
				
				
// 				$main_test_info = $this->onehealth_model->getTestById($health_facility_test_table_name,$main_test_id);
// 				if(is_array($main_test_info)){
// 					foreach($main_test_info as $row){
// 						$control_enabled = $row->control_enabled;
// 						$range_enabled = $row->range_enabled;
						
// 						if($range_enabled == 1){
// 							$range_type = $row->range_type;
// 							if($range_type == "interval"){
// 								$range_lower = $row->range_lower;
// 								$range_higher = $row->range_higher;
// 							}else if($range_type == "desirable"){
// 								$desirable_value = $row->desirable_value;
// 							}
// 						}
// 						$unit_enabled = $row->unit_enabled;
// 						if($unit_enabled == 1){
// 							$unit = $row->unit;
// 						}
// 						$test_no = $this->onehealth_model->getNoOfSubTests($health_facility_test_table_name,$main_test_id);
						
						
// 					}
// 				}else{
// 					$test_deleted = true;
// 				}
				
// 				$array = array(
// 					'lab_id' => $lab_id,
// 					'test_id' => $test_id,
// 					'test_name' => $test_name,
// 					'main_test' => 1,
// 					'sub_test' => 0
// 				);	
// 				$test_result_id = $this->onehealth_model->getResultId($health_facility_main_test_result_table,$array);	
// 				$form_array2 = array(
// 					'super_test_id' => $test_result_id
// 				);
// 				if($test_deleted == false){
// 					if($test_no > 0){ 
// 						$g = 0;
// 						$sub_tests = $this->onehealth_model->getTestsSubTests($health_facility_test_table_name,$main_test_id);
// 						foreach($sub_tests as $row){
// 	                      $g++;
// 	                      $sub_test_id = $row->id;
// 	                      $test_id = $row->test_id;
// 	                      $test_name = $row->name;
// 	                      $sample_required = $row->sample_required;
// 	                      $indication = $row->indication;
// 	                      $test_cost = $row->cost;
// 	                      $ta_time = $row->t_a;
// 	                      $ta_active = $row->active;
// 	                      $control_enabled = $row->control_enabled;
// 	                      $under = $row->under;
	                      
// 							$range_enabled = $row->range_enabled;
							
// 							if($range_enabled == 1){
// 								$range_type = $row->range_type;
// 								if($range_type == "interval"){
// 									$range_lower = $row->range_lower;
// 									$range_higher = $row->range_higher;
// 								}else if($range_type == "desirable"){
// 									$desirable_value = $row->desirable_value;
// 								}
// 							}
// 	                      $unit_enabled = $row->unit_enabled;
// 	                      $unit = $row->unit;
// 	                      $super_main_test_id = $this->onehealth_model->getTestSuperTest($health_facility_test_table_name,$sub_test_id);
// 							$this_id = $this->onehealth_model->getMainTestResultIdByMainTestId($health_facility_main_test_result_table,$super_main_test_id,$lab_id);
// 							$form_array = array(
// 								'test_id' => $test_id,
// 								'lab_id' => $lab_id,
// 								'test_name' => $test_name,
// 								'main_test' => 0,
// 								'sub_test' => 1,
// 								'super_test_id' => $this_id,
// 								'main_test_id' => $sub_test_id,
// 								'date' => $date,
// 								'time' => $time
// 							);
// 							if($this->onehealth_model->checkIfThisTestHasBeenAdded2($health_facility_main_test_result_table,$sub_test_id,$lab_id,$this_id) == false){												
// 								$this->onehealth_model->addTestMainResult($form_array,$health_facility_main_test_result_table);
// 							}else{
// 								$this->onehealth_model->updateTestMainResult($form_array,$health_facility_main_test_result_table,$sub_test_id,$lab_id);
// 							}
// 						}
// 					}
// 				}		
// 			}
// 		}
// 		$form_array = array(
//           'sampled' => 1
//         );
//         $all_patients = $this->onehealth_model->getPatientsTests($health_facility_patient_db_table,$form_array,$sub_dept_id);
        
//         if(is_array($all_patients)){
//         	$final_arr = array();
//             // $all_patients = array_reverse($all_patients);
//             foreach($all_patients as $row){
                
//                 $lab_id = $row->lab_id;
//                 $first_name = $row->firstname;
//                 $last_name = $row->surname;

//                 $all_tests = $this->onehealth_model->getAllPatientsTestsByLabId($health_facility_main_test_result_table,$health_facility_test_table_name,$lab_id);
//                 $final_arr[] = $all_tests;
//             }    
//             echo json_encode($final_arr);
//         }    
// 	}
// }	

// $recepient_arr = array('','kk','@gmail.com','chikaawaraka@gmail.com');
// $subject = "Test Email Sending";
// $message = "This Is Just A Test Email";
// if($this->onehealth_model->sendEmail($recepient_arr,$subject,$message)){
// 	echo "<br>successful";
// }


// $desirable_value = ">5o0";
// if($this->onehealth_model->proper_desirable_input_format($desirable_value)){
// 	echo "done";
// }else{
// 	echo "Not Working o";
// }

// $fruits = array("d" => "lemon", "a" => "orange", "b" => "banana", "c" => "apple");
// $fruitArrayObject = new ArrayObject($fruits);
// $fruitArrayObject->asort();

// foreach ($fruitArrayObject as $key => $val) {
//     echo "$key = $val\n";
// }
// echo htmlspecialchars('<h2 class="fg-white">AboutUs</h2><pclass="fg-white">developing and supporting complex IT solutions.Touchingmillions of lives world wide by bringing in innovative technology </p>');

//View All Personnel Ids of Wards
// $this->onehealth_model->showAllWardsPersonnelIds()
?>
	<div id="editor" style="border: 0;">
	  <!-- <p>Hello World!</p>
	  <p>Some initial <strong>bold</strong> text</p>
	  <p><br></p> -->
	</div>

	<!-- <div id="map"></div> -->
	<!-- <img src="<?php echo base_url('assets/images/56cf7edd4216cffc33ea64f3bac5ce6d_thumb.png') ?>" alt=""> -->
	<!-- <img src="" alt=""> -->
	<!-- <img style="display:none;" id="e6b50e2dbbbd0f7ac2d47ebfdce962c9.jpeg" src="http://localhost/cloudhis/assets/images/f106c3526db74aa60e9124b80a3323bd.jpeg"> -->
	<!-- <form action="<?php echo site_url('onehealth/index/test-ajax') ?>" id="paystack-form">
		<div class="form-group">
			<label for="first_name">FirstName: </label>
			<input type="text" class="form-control" id="first_name" name="first_name">
		</div>
		<div class="form-group">
			<label for="last_name">LastName: </label>
			<input type="text" class="form-control" id="last_name" name="last_name">
		</div>
		<div class="form-group">
			<label for="email">Email: </label>
			<input type="email" name="email" id="email" class="form-control">
		</div>
		<input type="submit" class="btn btn-default">
	</form> -->
	<!-- <img src="<?php echo base_url('assets/images/logo.jpg') ?>" id="logo" alt=""> -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<!-- Initialize Quill editor -->
	<script>
	  var quill = new Quill('#editor', {
	    theme : 'snow',
	    readOnly : true,
	    modules : {
		      "toolbar": false
		  }
	  });

	  $("#button").click(function (evt) {
	  	var form_data = JSON.stringify(quill.getContents());
	  	var url = "<?php echo site_url('onehealth/test_json') ?>";
	  	$.ajax({
	        url : url,
	        type : "POST",
	        responseType : "json",
	        dataType : "json",
	        data : "",
	        success : function (response) {
	        	console.log(response.json);
	        	if(response.success){
	        		quill.setContents(JSON.parse(response.json));
	        	}
	        },error : function () {
	        	console.log('error');
	        }
	    });
	  })
		

      // Note: This example requires that you consent to location sharing when
      // prompted by your browser. If you see the error "The Geolocation service
      // failed.", it means you probably did not give permission for the browser to
      // locate you.
//       var getDataUrl = function (img) {
//   var canvas = document.createElement('canvas')
//   var ctx = canvas.getContext('2d')

//   canvas.width = img.width
//   canvas.height = img.height
//   ctx.drawImage(img, 0, 0)

//   // If the image is not png, the format
//   // must be specified here
//   return canvas.toDataURL()
// }
// // var logo_img = getDataUrl(document.getElementById("logo"));
        // console.log(logo_img)

    //   $(document).ready(function () {

    //   	$("#paystack-form").submit(function (evt) {
    //   		evt.preventDefault();
    //   		var form_data = $(this).serializeArray();
    //   		var url = $(this).attr("action");
    //   		$.ajax({
				// url : url,
				// type : "POST",
				// responseType : "json",
				// dataType : "json",
				// data : form_data,
				// success : function (response) {
				// 	console.log(response)
				// },
				// error : function () {

				// }
    //   		});
    //   	})
    //   })
    //   var map, infoWindow;
    //   function initMap() {
    //     map = new google.maps.Map(document.getElementById('map'), {
    //       center: {lat: -34.397, lng: 150.644},
    //       zoom: 6
    //     });
    //     infoWindow = new google.maps.InfoWindow;

    //     // Try HTML5 geolocation.
    //     if (navigator.geolocation) {
    //       navigator.geolocation.getCurrentPosition(function(position) {
    //         var pos = {
    //           lat: position.coords.latitude,
    //           lng: position.coords.longitude
    //         };

    //         infoWindow.setPosition(pos);
    //         infoWindow.setContent('Location found.');
    //         infoWindow.open(map);
    //         map.setCenter(pos);
    //       }, function() {
    //         handleLocationError(true, infoWindow, map.getCenter());
    //       });
    //     } else {
    //       // Browser doesn't support Geolocation
    //       handleLocationError(false, infoWindow, map.getCenter());
    //     }
    //   }

    //   function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    //     infoWindow.setPosition(pos);
    //     infoWindow.setContent(browserHasGeolocation ?
    //                           'Error: The Geolocation service failed.' :
    //                           'Error: Your browser doesn\'t support geolocation.');
    //     infoWindow.open(map);
    //   }
    </script>
<!--Add External Libraries - JQuery and jspdf 
check out url - https://scotch.io/@nagasaiaytha/generate-pdf-from-html-using-jquery-and-jspdf
-->
	<script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script> -->
	
	<!-- <script async defer type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqwCCwgdlL1PelauMcK8hCGXACDzEMwWg&libraries=places&callback=initMap"></script> -->
	<script>

	var obj = {first : "one",second : "two",third : "three",fourth : "four"};
	console.log(obj)
	obj.fivth = "five";
	console.log(obj)
// 		function randomColorProperty (obj) {
// 	        var keys = Object.keys(obj)
// 	        return obj[keys[ keys.length * Math.random() << 0]];
// 	    }
// 	    function getKeyByValue(object, value) {
// 		  return Object.keys(object).find(key => object[key] === value);
// 		}

// 		$(document).ready(function () {

// // This code is collected but useful, click below to jsfiddle link.
// 		var colours = {
//                    "1" : "#1abc9c", "2" : "#2ecc71", "3" :"#3498db", "4" :"#9b59b6", "5" :"#34495e", "6" :"#16a085","7" : "#27ae60","8" : "#2980b9","9" : "#8e44ad", "10" :"#2c3e50", 
//                     "11" :"#f1c40f", "12" :"#e67e22", "13" :"#e74c3c","14" : "#ecf0f1", "15" :"#95a5a6", "16" :"#f39c12","17" : "#d35400", "18" :"#c0392b", "19" :"#bdc3c7", "20" :"#7f8c8d"
//                 };
//         var random_colour = randomColorProperty(colours);  
// 		console.log(random_colour);
// 		console.log(getKeyByValue(colours,random_colour));

// 		})
	</script>
	<script>
		
	</script>
	<!-- <script src="<?php echo base_url('assets/js/index (2).js')?> "></script> -->
</body>
</html>