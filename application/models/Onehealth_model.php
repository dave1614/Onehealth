<?php
	class onehealth_model extends CI_Model{
		public function __construct (){
			parent::__construct();
			$this->load->database();
			$this->load->library('encryption');
			$this->load->library('session');
			// $this->load->library('zend');
			// $this->zend->load('Zend/Barcode');
			$this->load->helper('cookie');
			$this->load->helper('url_helper');
		}

		public function getPreviousLisResults(){
			$ret = array();
			$this->db->select("lab_id");
			$this->db->from("everight_results");
			$this->db->order_by("lab_id","ASC");

			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$lab_id = $row->lab_id;
					$ret[] = $lab_id;
				}
			}

			return array_values(array_unique($ret));
		}

		public function getParentMainTestIdOfSubTest($health_facility_test_table_name,$main_test_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('id' => $main_test_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->under;
			}else{
				return false;
			}
		}

		public function checkIfThisTestHasSubTests($health_facility_test_table_name,$main_test_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('under' => $main_test_id));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function uploadResultForTestsMini($health_facility_id,$lab_id,$test_name,$value){
			$lab_structure = "mini";
			$user_id = $this->getUserIdWhenLoggedIn();
			$date = date("j M Y");
			$time = date("h:i:sa");
			if($this->onehealth_model->checkIfLabIdIsValid($health_facility_id,$lab_id)){
				$health_facility_name = $this->onehealth_model->getHealthFacilityNameById($health_facility_id);
				$health_facility_test_table_name = $this->onehealth_model->createTestTableHeaderString($health_facility_id,$health_facility_name);
				$query = $this->db->get_where($health_facility_test_table_name,array('name' => $test_name));
				if($query->num_rows() > 0){
					foreach($query->result() as $row){
						$main_test_id = $row->id;
						$sub_dept_id = $row->sub_dept_id;

						// echo $main_test_id . "<br>";
						$initiation_code = $this->onehealth_model->getInitiationCodeByLabId($health_facility_id,$lab_id);
						if(!$this->onehealth_model->checkIfThisTestHasSubTests($health_facility_test_table_name,$main_test_id)){
							//Main Test
					    	if(!$this->onehealth_model->checkIfTestIsASubTest($health_facility_test_table_name,$main_test_id)){
								if($this->onehealth_model->checkIfThisTestWasRequestedByThisPatient($health_facility_id,$lab_id,$main_test_id)){
									//None Radiology Test
									if($sub_dept_id != 6){

										if($this->onehealth_model->getLabFacilityParamByInitiationCodeAndHealthFacilityId("sampled",$initiation_code,$health_facility_id) == 1){

											$patients_user_id = $this->onehealth_model->getLabFacilityParamByInitiationCodeAndHealthFacilityId("user_id",$initiation_code,$health_facility_id);
						    				$patient_facility_id = $this->onehealth_model->getPatientFacilityParamByUserId("id",$health_facility_id,$patients_user_id);
						    				

			            					$control_enabled = $this->onehealth_model->getTestParamById("control_enabled",$health_facility_test_table_name,$main_test_id);

			            					$unit_enabled = $this->onehealth_model->getTestParamById("unit_enabled",$health_facility_test_table_name,$main_test_id);

			            					$range_enabled = $this->onehealth_model->getTestParamById("range_enabled",$health_facility_test_table_name,$main_test_id);

			            					$range_higher = $this->onehealth_model->getTestParamById("range_higher",$health_facility_test_table_name,$main_test_id);

			            					$range_lower = $this->onehealth_model->getTestParamById("range_lower",$health_facility_test_table_name,$main_test_id);
			            					$unit = $this->onehealth_model->getTestParamById("unit",$health_facility_test_table_name,$main_test_id);
			            					$range_type = $this->onehealth_model->getTestParamById("range_type",$health_facility_test_table_name,$main_test_id);
			            					$desirable_value = $this->onehealth_model->getTestParamById("desirable_value",$health_facility_test_table_name,$main_test_id);
			            					$success = false;

			            					if($unit_enabled == 0){
			            						$unit = "";
			            					}


			            					if($range_enabled){
			            						if(is_numeric($value)){
			            							$success = true;
			            						}
			            					}else{
			            						$success = true;
			            					}
			            					
			            					if($success){

			            						$action = "";
			            						$control_1 = "";
			            						$control_2 = "";
			            						$control_3 = "";
			            						$test_result = $value;
			            						$methodology = "";
			            						// $comments = "";
			            						$test_name = $this->onehealth_model->getTestParamById("name",$health_facility_test_table_name,$main_test_id);
			        							$ta_time = $this->onehealth_model->getLabFacilityParamByInitiationCodeAndHealthFacilityId("ta_time",$initiation_code,$health_facility_id);
			        							$test_id = $this->onehealth_model->getTestParamById("test_id",$health_facility_test_table_name,$main_test_id);
			        							$unit = $this->onehealth_model->getTestParamById("unit",$health_facility_test_table_name,$main_test_id);
			        							$range_lower = $this->onehealth_model->getTestParamById("range_lower",$health_facility_test_table_name,$main_test_id);
			        							$range_higher = $this->onehealth_model->getTestParamById("range_higher",$health_facility_test_table_name,$main_test_id);
			        							$range_enabled = $this->onehealth_model->getTestParamById("range_enabled",$health_facility_test_table_name,$main_test_id);
			        							$range_type = $this->onehealth_model->getTestParamById("range_type",$health_facility_test_table_name,$main_test_id);
			        							$desirable_value = $this->onehealth_model->getTestParamById("desirable_value",$health_facility_test_table_name,$main_test_id);
			        							$unit_enabled = $this->onehealth_model->getTestParamById("unit_enabled",$health_facility_test_table_name,$main_test_id);
			        							$control_enabled = $this->onehealth_model->getTestParamById("control_enabled",$health_facility_test_table_name,$main_test_id);
			            						if($control_enabled == 1){
			            							if($this->onehealth_model->checkIfResultsTableAlreadyHasThisTestInitBefore($health_facility_id,$lab_id,$initiation_code,$main_test_id)){

			            								$action = "update";
				            							$form_array = array(
				            								'test_result' => $test_result,
				            								'personnel_id' => $user_id,
				            								'date_entered' => $date,
				            								'time_entered' => $time
				            							);
				            						}else{
				            							echo "string";
				            							$action = "insert";
				            							$form_array = array(
				            								'health_facility_id' => $health_facility_id,
				            								'user_id' => $patients_user_id,
				            								'initiation_code' => $initiation_code,
				            								'lab_id' => $lab_id,
				            								'control_1' => $control_1,
				            								'control_2' => $control_2,
				            								'control_3' => $control_3,
				            								'methodology' => $methodology,
				            								'test_result' => $test_result,
				            								// 'comments' => $comments,
				            								'personnel_id' => $user_id,
				            								'date_entered' => $date,
				            								'time_entered' => $time
				            							);
				            						}
			            						}else{
			            							if($this->onehealth_model->checkIfResultsTableAlreadyHasThisTestInitBefore($health_facility_id,$lab_id,$initiation_code,$main_test_id)){
			            								$action = "update";
				            							$form_array = array(
				            								
				            								'test_result' => $test_result,
				            								
				            								'personnel_id' => $user_id,
				            								'date_entered' => $date,
				            								'time_entered' => $time
				            							);
				            						}else{
				            							$action = "insert";
				            							$form_array = array(
				            								'health_facility_id' => $health_facility_id,
				            								'user_id' => $patients_user_id,
				            								'initiation_code' => $initiation_code,
				            								'lab_id' => $lab_id,
				            								'methodology' => $methodology,
				            								'test_result' => $test_result,
				            								// 'comments' => $comments,
				            								'personnel_id' => $user_id,
				            								'date_entered' => $date,
				            								'time_entered' => $time
				            							);
				            						}
			            						}

			            						$form_array['test_id'] = $test_id;
				            					$form_array['test_name'] = $test_name;
				            					$form_array['ta_time'] = $ta_time;
				            					$form_array['unit'] = $unit;
				            					$form_array['range_lower'] = $range_lower;
				            					$form_array['range_higher'] = $range_higher;
				            					$form_array['range_enabled'] = $range_enabled;
				            					$form_array['range_type'] = $range_type;
				            					$form_array['desirable_value'] = $desirable_value;
				            					$form_array['unit_enabled'] = $unit_enabled;
				            					$form_array['control_enabled'] = $control_enabled;

			            						$form_array['main_test_id'] = $main_test_id;
			            						$form_array['main_test'] = 1;
			            						$form_array['has_sub_test'] = 0;

			            						$initiation_array = array(
			            							'lab_structure' => 'mini',
			            							'test_completed' => 1,
			            							'test_completed_time' => $date . " " .$time
			            						);


			            						if($this->onehealth_model->submitLabResultsMini($form_array,$health_facility_id,$initiation_code,$lab_id,$patients_user_id,$main_test_id,$action)){
			            							if($this->onehealth_model->updateTestInitiation1($health_facility_id,$initiation_code,$initiation_array,$main_test_id)){
			            								
			            							}
			            						}

			            					}
				            				
				            			}
			            			}else{// Radiology Test

			            			}
			        						        			
				        		}
				        	}else{// Sub Test
            					$sub_test_main_test_id = $this->onehealth_model->getParentMainTestIdOfSubTest($health_facility_test_table_name,$main_test_id);
            					
								if($this->onehealth_model->checkIfThisTestWasRequestedByThisPatient($health_facility_id,$lab_id,$sub_test_main_test_id)){
									
									$patients_user_id = $this->onehealth_model->getLabFacilityParamByInitiationCodeAndHealthFacilityId("user_id",$initiation_code,$health_facility_id);
		            				$patient_facility_id = $this->onehealth_model->getPatientFacilityParamByUserId("id",$health_facility_id,$patients_user_id);
		            				if($this->onehealth_model->checkIfTestNativeIdExists($sub_test_main_test_id,$health_facility_test_table_name)){

			            				if(!$this->onehealth_model->checkIfTestIsASubTest($health_facility_test_table_name,$sub_test_main_test_id)){
			            					
			            					$sub_tests = $this->onehealth_model->getTestsSubTests($health_facility_test_table_name,$sub_test_main_test_id);

		            						$q = 0;
		            						// $comments = "";

		            						$sub_tests = $this->onehealth_model->getTestsSubTests($health_facility_test_table_name,$sub_test_main_test_id);
			            					foreach($sub_tests as $row){
			            						$q++;
					            				$sub_test_id = $row->id;
												$test_id = $row->test_id;
												$name = $row->name;
												$sample_required = $row->sample_required;
												$indication = $row->indication;
												$cost = $row->cost;
												$t_a = $row->t_a;
												$pppc = $row->pppc;
												$section = $row->section;
												$active = $row->active;
												$no = $row->no;
												$tests = $row->tests;
												$unit = $row->unit;
												$range_lower = $row->range_lower;
												$range_higher = $row->range_higher;
												$range_enabled = $row->range_enabled;
												$range_type = $row->range_type;
												$desirable_value = $row->desirable_value;
												$unit_enabled = $row->unit_enabled;
												$control_enabled = $row->control_enabled;
												$success = false;

												if($sub_test_id == $main_test_id){
					            					if($range_enabled){
					            						if(is_numeric($value)){
					            							$success = true;
					            						}
					            					}else{
					            						$success = true;
					            					}
					            				}

												if($success){
													if($control_enabled == 1){
														$control_1 = "";

														$control_2 = "";	
														
														$control_3 = "";									
														$methodology = "";		
														$test_result = $value;		

														$form_array = array(
															'control_1' => $control_1,
															'control_2' => $control_2,
															'control_3' => $control_3,
															'methodology' => $methodology,
															'test_result' => $test_result
														);	

													}else{
														$methodology = "";		
														$test_result = $value;	

														$form_array = array(
															'methodology' => $methodology,
															'test_result' => $test_result
														);			
													}

													$form_array['user_id'] = $patients_user_id;

													$form_array['initiation_code'] = $initiation_code;
													$form_array['lab_id'] = $lab_id;

													$form_array['health_facility_id'] = $health_facility_id;
													$form_array['main_test_id'] = $sub_test_id;
													$form_array['has_sub_test'] = 0;
													$form_array['under'] = $sub_test_main_test_id;
													$form_array['test_id'] = $test_id;
													$form_array['test_name'] = $name;
													$form_array['ta_time'] = $t_a;
													$form_array['unit'] = $unit;
													$form_array['range_lower'] = $range_lower;
													$form_array['range_higher'] = $range_higher;
													$form_array['range_enabled'] = $range_enabled;
													$form_array['range_type'] = $range_type;
													$form_array['desirable_value'] = $desirable_value;
													$form_array['unit_enabled'] = $unit_enabled;
													$form_array['control_enabled'] = $control_enabled;
													$form_array['main_test'] = 0;
													
													$form_array['personnel_id'] = $user_id;
													$form_array['date_entered'] = $date;
													$form_array['time_entered'] = $time;

													if($this->onehealth_model->createTestResultRecordForMainTestWithSubTests($health_facility_id,$initiation_code,$lab_id,$sub_test_main_test_id)){
														if($this->onehealth_model->checkIfThisSubTestRecordIsAlreadyInDataBase($health_facility_id,$initiation_code,$lab_id,$sub_test_main_test_id,$sub_test_id)){
															unset($form_array['control_1']);
															unset($form_array['control_2']);
															unset($form_array['control_3']);
															unset($form_array['methodology']);
															//Update Already Existing Record
															if($this->onehealth_model->updateTestResults1($form_array,$health_facility_id,$initiation_code,$lab_id,$sub_test_main_test_id,$sub_test_id)){
																$update_array = array(
																	'test_entered' => 1,
																	'lab_structure' => 'mini'
																);

																if($this->onehealth_model->checkIfAllSubTestsHaveBeenEnteredSuccessfully($health_facility_test_table_name,$health_facility_id,$initiation_code,$lab_id,$sub_test_main_test_id)){
																	$update_array['test_completed'] = 1;

																	$update_array['test_completed_time'] = $date . " " .$time;

																}
																$this->onehealth_model->updateTestInitiation1($health_facility_id,$initiation_code,$update_array,$sub_test_main_test_id);
																if($q == count($sub_tests)){
																	$this->onehealth_model->checkIfAllSubTestsHaveBeenEnteredSuccessfullyAndMarkInitiationTableAsCompleted($health_facility_test_table_name,$health_facility_id,$initiation_code,$lab_id,$sub_test_main_test_id,$date,$time);
																	
																}
															}
														}else{
															//Insert New Record
															if($this->onehealth_model->addTestResultRow($form_array)){
																$update_array = array(
																	'test_entered' => 1,
																	'lab_structure' => $lab_structure
																);

																if($this->onehealth_model->checkIfAllSubTestsHaveBeenEnteredSuccessfully($health_facility_test_table_name,$health_facility_id,$initiation_code,$lab_id,$sub_test_main_test_id)){
																	$update_array['test_completed'] = 1;

																	$update_array['test_completed_time'] = $date . " " .$time;

																}
																$this->onehealth_model->updateTestInitiation1($health_facility_id,$initiation_code,$update_array,$sub_test_main_test_id);
																if($q == count($sub_tests)){
																	$this->onehealth_model->checkIfAllSubTestsHaveBeenEnteredSuccessfullyAndMarkInitiationTableAsCompleted($health_facility_test_table_name,$health_facility_id,$initiation_code,$lab_id,$sub_test_main_test_id,$date,$time);
																	
																}
															}
														}

													}											
												}		
											}

											// $this->onehealth_model->updateCommentsSubTests($health_facility_id,$initiation_code,$lab_id,$sub_test_main_test_id,$comments);
			            					
											
			            				}
			            			}
			            		}
            				}
			        	}
		        	}
	        	}
				
			}
		}

		public function getAllMortuaryPatientsPaymentsByMortuaryRecordId($health_facility_id,$mortuary_record_id){
			$this->db->select("*");
			$this->db->from("clinic_payments");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("mortuary_record_id",$mortuary_record_id);
			$this->db->where("type","mortuary_service");
			$this->db->order_by("id","DESC");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getlastPaymentTimeForMortuaryRecordId($mortuary_record_id,$health_facility_id){
			$this->db->select("date,time");
			$this->db->from("clinic_payments");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("mortuary_record_id",$mortuary_record_id);
			$this->db->where("type","mortuary_service");
			$this->db->order_by("id","DESC");
			$this->db->limit(1);
			$query = $this->db->get();

			if($query->num_rows() == 1){
				return $query->result()[0]->date . " " . $query->result()[0]->time;
			}
		}

		public function getTotalAmountPaidForMortuaryRecordId($mortuary_record_id,$health_facility_id){
			$sum = 0;
			$this->db->select("amount_paid");
			$this->db->from("clinic_payments");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("mortuary_record_id",$mortuary_record_id);
			$this->db->where("type","mortuary_service");
			$this->db->order_by("id","DESC");
			$query = $this->db->get();

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$sum += $row->amount_paid;
				}
			}
			
			return $sum;
		}

		public function getTotalNoOfPaymentsForMortuaryRecordId($mortuary_record_id,$health_facility_id){
			$this->db->select("mortuary_record_id");
			$this->db->from("clinic_payments");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("mortuary_record_id",$mortuary_record_id);
			$this->db->where("type","mortuary_service");
			$this->db->order_by("id","DESC");
			$query = $this->db->get();
			
			return $query->num_rows();
		}

		public function getAllMortuaryPatientsPayments($health_facility_id){
			$ret = array();
			$this->db->select("mortuary_record_id");
			$this->db->from("clinic_payments");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("mortuary_record_id !=",0);
			$this->db->where("type","mortuary_service");
			$this->db->order_by("id","DESC");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$mortuary_record_id = $row->mortuary_record_id;
					$ret[] = $mortuary_record_id;
				}
			}
			return array_values(array_unique($ret));
		}


		public function getAllMortuaryOutstandingPaymentsForPatient($health_facility_id,$mortuary_record_id){
			$this->db->select("*");
			$this->db->from("outstanding_payment");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("mortuary_record_id",$mortuary_record_id);
			$this->db->where("type","mortuary_service");
			$this->db->where("paid",0);
			$this->db->order_by("id","DESC");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function checkIfThisMortuaryRecordIdIsValid($health_facility_id,$mortuary_record_id){
			$query = $this->db->get_where("mortuary",array('health_facility_id' => $health_facility_id,'id' => $mortuary_record_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getLastDateAndTimeOutstandingMortuaryPayment($mortuary_record_id,$health_facility_id){
			$this->db->select("date,time");
			$this->db->from("outstanding_payment");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("mortuary_record_id",$mortuary_record_id);
			$this->db->where("type","mortuary_service");
			$this->db->where("paid",0);
			$this->db->order_by("id","DESC");
			$this->db->limit(1);
			$query = $this->db->get();
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$date = $row->date;
					$time = $row->time;

					return $date . " " . $time;
				}
			}else{
				return false;
			}
		}

		public function getTotalMortuaryOutstandingBillFormortuaryRecordId($mortuary_record_id,$health_facility_id){
			$sum = 0;
			$this->db->select("amount");
			$this->db->from("outstanding_payment");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("mortuary_record_id",$mortuary_record_id);
			$this->db->where("type","mortuary_service");
			$this->db->where("paid",0);
			$this->db->order_by("id","DESC");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$sum += $row->amount;
				}
			}
			return $sum;
		}

		public function getAllPatientsOwingFacilityMortuary($health_facility_id){
			$ret = array();
			$this->db->select("mortuary_record_id");
			$this->db->from("outstanding_payment");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("type","mortuary_service");
			$this->db->where("paid",0);
			$this->db->order_by("id","DESC");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$ret[] = $row->mortuary_record_id;
				}
			}else{
				return false;
			}
			return array_values(array_unique($ret));
		}

		public function getOutstandingBillsCollected($health_facility_id){
			$query = $this->db->get_where("clinic_payments",array('health_facility_id' => $health_facility_id,'type' => 'outstanding'));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function updateLabInitiation($health_facility_id,$initiation_code,$form_array){
			return $this->db->update('lab_facility_initiations',$form_array,array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
		}

		public function getSelectedDrugsByReferralIdOnly($referral_id){
			$query = $this->db->get_where("pharmacy_drugs_selected_referral_consult",array('referral_id' => $referral_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getSelectedReferralDrugsByReferralIdClinicDoctor($referral_id){
			$this->db->select("*");
			$this->db->from('pharmacy_drugs_selected_referral_consult');
			
			$this->db->where('referral_id',$referral_id);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getSelectedDrugsByReferralId($health_facility_id,$referral_id){
			$query = $this->db->get_where("pharmacy_drugs_selected_referral_consult",array('health_facility_id' => $health_facility_id,'referral_id' => $referral_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function savePharmacyDrugPrescriptionReferral($form_array){
			return $this->db->insert("pharmacy_drugs_selected_referral_consult",$form_array);
		}

		public function savePharmacyDrugPrescriptionWard($form_array){
			return $this->db->insert("pharmacy_drugs_selected_wards",$form_array);
		}

		public function getNumberOfDrugsByInitiationCodeAndHealthFacilityId($initiation_code,$health_facility_id){
			$query = $this->db->get_where("pharmacy_drugs_selected",array('initiation_code' => $initiation_code,'health_facility_id' => $health_facility_id));
			return $query->num_rows();
		}

		public function getRequestedDrugsByInitiationCode($health_facility_id,$initiation_code){
			$query  = $this->db->get_where("pharmacy_drugs_selected",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}


		public function getSelectedDrugsByConsultationIdDoctor($health_facility_id,$consultation_id){
			$query = $this->db->get_where("pharmacy_drugs_selected_clinics",array('health_facility_id' => $health_facility_id,'consultation_id' => $consultation_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}


		public function getClinicPatientsPharmacyPendingPayment($health_facility_id){
			$ret = array();
			$this->db->select("initiation_code");
			$this->db->from('pharmacy_drugs_selected');
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->where('clinic',1);
			$this->db->where('ward',0);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					if($this->getDrugsBalance($health_facility_id,$initiation_code) > 0){
						$ret[] = $initiation_code;
					}
				}

				$ret = array_reverse(array_values(array_unique($ret)));
			}

			return $ret;
		}


		public function getOTCPatientsPharmacyPendingPayment($health_facility_id){
			$ret = array();
			$this->db->select("initiation_code");
			$this->db->from('pharmacy_drugs_selected');
			$this->db->where('health_facility_id',$health_facility_id);
			// $this->db->where('clinic',0);
			// $this->db->where('ward',0);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;

					if($this->getDrugsBalance($health_facility_id,$initiation_code) > 0){
						$ret[] = $initiation_code;
					}
				}

				$ret = array_reverse(array_values(array_unique($ret)));
			}

			return $ret;
		}

		public function savePharmacyDrugPrescriptionClinic($form_array){
			return $this->db->insert("pharmacy_drugs_selected_clinics",$form_array);
		}


		public function getReferralOrConsultParamByIdAndHealthFacilityId($param,$health_facility_id,$referral_id){
			$query = $this->db->get_where("referrals_or_consults",array('id' => $referral_id,'referred_to_facility_id' => $health_facility_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$param_val = $row->$param;
				}
				return $param_val;
			}else{
				return false;
			}
		}

		public function getClinicConsultationParamByIdAndHealthFacilityId($param,$health_facility_id,$consultation_id){
			$query = $this->db->get_where("clinic_consultations",array('id' => $consultation_id,'health_facility_id' => $health_facility_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}else{
				return false;
			}
		}

		public function getPatientsFeesPharmacy($company_id,$health_facility_id,$user_type){
	    	//get all codes of this company id
	    	$ret = array();
	    	$codes = array();
	    	$query = $this->db->get_where("verification_codes_records",array('company_id' => $company_id,'health_facility_id' => $health_facility_id,'taken' => 1));
	    	if($query->num_rows() > 0){
	    		foreach($query->result() as $row){
	    			$code = $row->code;
	    			$codes[] = $code;
	    		}
	    	}


	    	if(count($codes) > 0){
	    		for($i = 0; $i < count($codes); $i++){
	    			$code = $codes[$i];

		    		$query = $this->db->get_where('pharmacy_drugs_selected',array('health_facility_id' => $health_facility_id,'insurance_code' => $code,'selection_type' => 'registered_patient','paid' => 1,'user_type' => $user_type));
		    		if($query->num_rows() > 0){
		    			$initiation_code_arr = array();
		    			foreach($query->result() as $row){
		    				$initiation_code = $row->initiation_code;
		    				$initiation_code_arr[] = $initiation_code;
		    			}
		    			$initiation_code_arr = array_values(array_unique($initiation_code_arr));
		    			$ret = array_merge($ret,$initiation_code_arr);
		    		}
		    	}
	    	}

	    	// var_dump($ret);

	    	return $ret;
	    }

		public function getNoOfPaymentsInPharmacyUnderACompanyId($company_id,$health_facility_id,$user_type){
	    	//get all codes of this company id
	    	$num = 0;
	    	$codes = array();
	    	$query = $this->db->get_where("verification_codes_records",array('company_id' => $company_id,'health_facility_id' => $health_facility_id,'taken' => 1));
	    	if($query->num_rows() > 0){
	    		foreach($query->result() as $row){
	    			$code = $row->code;
	    			$codes[] = $code;
	    		}
	    	}


	    	if(count($codes) > 0){
	    		for($i = 0; $i < count($codes); $i++){
	    			$code = $codes[$i];

		    		$query = $this->db->get_where('pharmacy_drugs_selected',array('health_facility_id' => $health_facility_id,'selection_type' => 'registered_patient','insurance_code' => $code,'paid' => 1,'user_type' => $user_type));
		    		if($query->num_rows() > 0){
		    			$initiation_code_arr = array();
		    			foreach($query->result() as $row){
		    				$initiation_code = $row->initiation_code;
		    				$initiation_code_arr[] = $initiation_code;
		    			}
		    			$initiation_code_arr = array_values(array_unique($initiation_code_arr));
		    			$num += count($initiation_code_arr);
		    		}
		    	}
	    	}

	    	return $num;
	    }

		 public function getCompaniesPharmacy($health_facility_id,$user_type){
	    	$ret = array();
			$this->db->select("*");
			$this->db->from("pharmacy_drugs_selected");
			$this->db->where("paid",1);
			$this->db->where("user_type",$user_type);
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("selection_type","registered_patient");

			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$insurance_code = $row->insurance_code;
					$company_id = $this->onehealth_model->getCompanyIdByInsuranceCode($insurance_code,$health_facility_id);

					$ret[] = $company_id;
				}
			}

			return array_values(array_unique($ret));

	    }

		public function getPatientsFeesPharmacyFullPaying($health_facility_id,$days_num){
			$ret = array();
			$this->db->select("*");
			$this->db->from("pharmacy_drugs_selected");
			$this->db->where("paid",1);
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("selection_type","registered_patient");
			$this->db->where("user_type","fp");

			$query = $this->db->get();
			
			if($query->num_rows() > 0){
				// var_dump($query->result());
				foreach($query->result() as $row){
					
					$initiation_code = $row->initiation_code;
					$user_type = $row->user_type;
					
					$ret[] = $initiation_code;
					
				}
			}else{
				return false;
			}
			return $ret;
		}

		public function getOverTheCounterPharmacyPayments($health_facility_id,$days_num){
			$ret = array();
			$this->db->select("*");
			$this->db->from("pharmacy_drugs_selected");
			$this->db->where("paid",1);
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("selection_type","over_the_counter");

			$query = $this->db->get();
			
			if($query->num_rows() > 0){
				// var_dump($query->result());
				foreach($query->result() as $row){
					
					$initiation_code = $row->initiation_code;
					$user_type = $row->user_type;
					// echo $patient_username;
					
					$ret[] = $initiation_code;
					
				}
			}else{
				return false;
			}
			return $ret;
		}

		public function addPharmacyTellerRecord($form_array){
	    	$query = $this->db->insert('teller_records_pharmacy',$form_array);
	    	return $query;
	    }


		public function updatePharmacyInitiation($health_facility_id,$initiation_code,$form_array){
			return $this->db->update("pharmacy_drugs_selected",$form_array,array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
		}

		public function getIfPharmacyInitiationCodeIsValid($health_facility_id,$initiation_code){
			$query = $this->db->get_where("pharmacy_drugs_selected",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getTellerRecordsForInitPharmacy($health_facility_id,$initiation_code){
			$query = $this->db->get_where('teller_records_pharmacy',array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getDrugsBalance($health_facility_id,$initiation_code){
			$amount = 0;
			$total_amount_due = $this->getTotalAmuntDueForDrugs($health_facility_id,$initiation_code);
			$selection_type = $this->getDrugSelectedParamByInitiationCodeAndHealthFacilityId("selection_type",$initiation_code,$health_facility_id);
			$user_type = $this->getDrugSelectedParamByInitiationCodeAndHealthFacilityId("user_type",$initiation_code,$health_facility_id);
			$insurance_code = $this->getDrugSelectedParamByInitiationCodeAndHealthFacilityId("insurance_code",$initiation_code,$health_facility_id);
			$amount_paid = $this->getDrugSelectedParamByInitiationCodeAndHealthFacilityId("amount_paid",$initiation_code,$health_facility_id);
			$part_payment_discount_percentage = $this->getDrugSelectedParamByInitiationCodeAndHealthFacilityId("part_payment_discount_percentage",$initiation_code,$health_facility_id);
			$discount = $this->getDrugSelectedParamByInitiationCodeAndHealthFacilityId("discount",$initiation_code,$health_facility_id);


			// echo $total_amount_due;
			if($selection_type == "over_the_counter"){
				$amount = ( $total_amount_due - (($discount / 100) * $total_amount_due));
				
			}else if($selection_type == "registered_patient"){
				if($user_type == "fp"){
					$amount = ( $total_amount_due - (($discount / 100) * $total_amount_due));
					
				}else if($user_type == "pfp"){
					$amount = ( $total_amount_due - (($part_payment_discount_percentage / 100) * $total_amount_due));
					$amount = ( $amount - (($discount / 100) * $amount));
				}else if($user_type == "nfp"){
					$amount = 0;
				}
			}
			$val = round(($amount - $amount_paid),2);

			return $val;
		}

		public function getDrugSelectedParamByInitiationCodeAndHealthFacilityId($param,$initiation_code,$health_facility_id){
			$query = $this->db->get_where("pharmacy_drugs_selected",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code),1);
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}else{
				return false;
			}
		}

		public function getTotalAmuntDueForDrugs($health_facility_id,$initiation_code){
			$sum = 0.00;
			$query = $this->db->get_where("pharmacy_drugs_selected",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$price = $row->price;
					$quantity = $row->quantity;
					
					$sum += $price * $quantity;
				}
			}
			return $sum;
		}

		public function checkIfOneOfTheseTestsHaveMethodologyForEverightResultFileSubTest($tests){
			if(is_array($tests)){
				for($i = 0; $i < count($tests); $i++){
					$test = $tests[$i];
					if(is_array($test)){
						if($test['sub_test'] == 1){
							if($test['methodology'] != ""){
								return true;
							}
						}
					}else{
						return false;
					}
				}
			}
		}

		public function checkIfOneOfTheseTestsHaveRangeEnabledForEverightResultFileSubTest($tests){
			if(is_array($tests)){
				for($i = 0; $i < count($tests); $i++){
					$test = $tests[$i];
					if(is_array($test)){
						if($test['sub_test'] == 1){
							if($test['range'] != ""){
								return true;
							}
						}
					}else{
						return false;
					}
				}
			}
		}

		public function checkIfOneOfTheseTestsHaveMethodologyForEverightResultFile($tests){
			if(is_array($tests)){
				for($i = 0; $i < count($tests); $i++){
					$test = $tests[$i];
					if(is_array($test)){
						if($test['methodology'] != ""){
							return true;
						}
					}else{
						return false;
					}
				}
			}
		}

		public function checkIfOneOfTheseTestsHaveRangeEnabledForEverightResultFile($tests){
			if(is_array($tests)){
				for($i = 0; $i < count($tests); $i++){
					$test = $tests[$i];
					if(is_array($test)){
						if($test['range'] != ""){
							return true;
						}
					}else{
						return false;
					}
				}
			}
		}

		public function getMainTestPositionForEverightResultFile($tests){
			if(is_array($tests)){
				for($i = 0; $i < count($tests); $i++){
					$test = $tests[$i];
					if(is_array($test)){
						if($test['main_test'] == 1){
							return $i;
						}
					}else{
						return false;
					}
				}
			}
		}

		public function checkIfTestsAreInTheRightFormatForEverightResultFile($tests){
			if(is_array($tests)){
				$main_test_num = 0;

				for($i = 0; $i < count($tests); $i++){
					$test = $tests[$i];
					if(is_array($test)){
						if(isset($test['main_test'])){
							if($test['main_test'] == 1){
								$main_test_num++;
							}
						}
					}else{
						return false;
					}
				}

				// echo $main_test_num;

				if($main_test_num == 1){
					return true;
				}
			}
		}

		public function getSelectedTestsByConsultationIdClinicDoctor1($consultation_id){
			$this->db->select("*");
			$this->db->from('patient_tests_selected_clinic');
			// $this->db->where('health_facility_id',$health_facility_id);
			$this->db->where('consultation_id',$consultation_id);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getTestsSelectedClinic($health_facility_id,$consultation_id){
			// echo $consultation_id;
			$this->db->select("*");
			$this->db->from('patient_tests_selected_clinic');
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->where('consultation_id',$consultation_id);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getSelectedReferralTestsByReferralIdClinicDoctor($referral_id){
			$this->db->select("*");
			$this->db->from('tests_selected_consult');
			
			$this->db->where('referral_id',$referral_id);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function createNewPatientTestRecordReferral($form_array){
	    	$query = $this->db->insert("tests_selected_consult",$form_array);
	    	if($query){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

		public function getTestRequestedByInitiationCode($health_facility_id,$initiation_code,$main_test_id){
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'main_test_id' => $main_test_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}


		public function addPatientTestsSelectedWardRecord($form_array1){
			return $this->db->insert("ward_tests_selected",$form_array1);
		}


		public function getWardTestsSelectedDuringAdmission($health_facility_id,$ward_record_id){
			$query = $this->db->get_where('ward_tests_selected',array('health_facility_id' => $health_facility_id,'ward_record_id' => $ward_record_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getNumberfOfTestsSelectedInInitiation($health_facility_id,$initiation_code){
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
			return $query->num_rows();
		}

		public function getLabInitiationParamByInitiationCodeAndHealthFacilityId($param,$health_facility_id,$initiation_code){
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code),1);
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}
		}

		public function getAllReferralInitiationsToThisLab($health_facility_id,$referring_facility_id){
			$ret = array();
			$this->db->select("initiation_code");
			$this->db->from("lab_facility_initiations");
			$this->db->where("referring_facility_id",$health_facility_id);
			$this->db->where("health_facility_id",$referring_facility_id);
			$this->db->order_by("id","DESC");

			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					$ret[] = $initiation_code;
				}
			}

			return array_values(array_unique($ret));
		}

		public function getLastDateOfReferralToACertainHealthFacility($health_facility_id,$referring_facility_id){
			
			$this->db->select("date,time");
			$this->db->from("lab_facility_initiations");
			$this->db->where("referring_facility_id",$health_facility_id);
			$this->db->where("health_facility_id",$referring_facility_id);
			$this->db->order_by("id","DESC");
			$this->db->limit(1);

			$query = $this->db->get();
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$date = $row->date;
					$time = $row->time;

					return $date . " " .$time;
				}
			}else{
				return false;
			}

			
		}

		public function getTotalNumberOfReferralsToACertainHealthFacility($health_facility_id,$referring_facility_id){
			$sum = 0;
			$initiation_code_arr = array();
			$this->db->select("initiation_code");
			$this->db->from("lab_facility_initiations");
			$this->db->where("referring_facility_id",$health_facility_id);
			$this->db->where("health_facility_id",$referring_facility_id);
			$this->db->order_by("id","DESC");

			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;

					$initiation_code_arr[] = $initiation_code;
				}
				$initiation_code_arr = array_values(array_unique($initiation_code_arr));
				$sum = count($initiation_code_arr);
			}

			return $sum;
		}

		public function getAllHealthFacilitiesReferredToLab($health_facility_id){
			$ret = array();
			$this->db->select("health_facility_id");
			$this->db->from("lab_facility_initiations");
			$this->db->where("referring_facility_id",$health_facility_id);
			$this->db->order_by("id","DESC");

			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$health_facility_id = $row->health_facility_id;
					$ret[] = $health_facility_id;
				}
			}

			return array_values(array_unique($ret));
		}

		public function getTestProgressStatusSubTest($health_facility_test_table_name,$health_facility_id,$main_test_id,$sub_test_id,$initiation_code){
	    	$ret = "";
	    	
	    	$query = $this->db->get_where('lab_facility_initiations',array('initiation_code' => $initiation_code,'main_test_id' => $main_test_id,'health_facility_id' => $health_facility_id));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$test_id = $row->test_id;
	    			$test_name = $row->test_name;
	    			$paid = $row->paid;
	    			$lab_structure = $row->lab_structure;
	    			$sub_dept_id = $row->sub_dept_id;
	    			$radiographer_complete = $row->radiographer_complete;
	    			$radiology_complete = $row->radiology_complete;
	    			$redirect_to = $row->redirect_to;
	    			$lab_structure = $row->lab_structure;
	    			$sampled = $row->sampled;
	    			$test_completed = $row->test_completed;
	    			$test_entered = $row->test_entered;
	    			$verified = $row->verified;
	    			$comments = $row->comments;
	    		}

	    		$balance = $this->onehealth_model->getTotalBalanceForTests($health_facility_id,$initiation_code);

	    		$test_result = "";

	    		$query = $this->db->get_where('lab_test_results',array('initiation_code' => $initiation_code,'health_facility_id' => $health_facility_id,'main_test_id' => $sub_test_id,'under' => $main_test_id,'main_test' => 0));
	    		if($query->num_rows() > 0){
	    			foreach($query->result() as $row){
	    				$test_result = $row->test_result;
	    			}
	    		}


	    		// var_dump($lab_structure);
	    		
	    		if($sub_dept_id != 6){
	    			if($lab_structure == ""){

	    				if($paid == 0){
		    				$ret = "initiated but awaiting payment";
		    			}else if($paid == 1 && $balance > 0){
							$ret = "awaiting completion of payment";
						}else if($balance == 0 && $sampled == 0){
		    				$ret = "awaiting sample collection";
		    			}else if($sampled == 1 && $test_result == ""){
		    				$ret = "awaiting input of result";
		    			}
	    			}else if($lab_structure == "mini"){

	    				if($paid == 0){
		    				$ret = "initiated but awaiting payment";
		    			}else if($paid == 1 && $balance > 0){
							$ret = "awaiting completion of payment";
						}else if($balance == 0 && $sampled == 0){
		    				$ret = "awaiting sample collection";
		    			}else if($sampled == 1 && $test_result == ""){
		    				$ret = "awaiting input of result";
		    			}else if($test_result != ""){
		    				$ret = "test ready";
		    			}
	    			}else if($lab_structure == "standard" || $lab_structure == "maximum"){

	    				if($paid == 0){
		    				$ret = "initiated but awaiting payment";
		    			}else if($paid == 1 && $balance > 0){
							$ret = "awaiting completion of payment";
						}else if($balance == 0 && $sampled == 0){
		    				$ret = "awaiting sample collection";
		    			}else if($sampled == 1 && $test_result == ""){
		    				$ret = "awaiting input of result";
		    			}else if($test_result != "" && $verified == 0){
		    				$ret = "awaiting verification by supervisor";
		    			}else if($verified == 1 && $comments == ""){
		    				$ret = "awaiting entering of comments";
		    			}else if($comments != ""){
		    				$ret = "test ready";
		    			}else{

		    			}
	    			}
	    		}

		    }
		    return $ret;
	    }


		public function getTestProgressStatusMainTest($health_facility_id,$main_test_id,$initiation_code){
	    	$ret = "";
	    	$query = $this->db->get_where('lab_facility_initiations',array('initiation_code' => $initiation_code,'main_test_id' => $main_test_id,'health_facility_id' => $health_facility_id));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$test_id = $row->test_id;
	    			$test_name = $row->test_name;
	    			$paid = $row->paid;
	    			$lab_structure = $row->lab_structure;
	    			$sub_dept_id = $row->sub_dept_id;
	    			$radiographer_complete = $row->radiographer_complete;
	    			$radiology_complete = $row->radiology_complete;
	    			$redirect_to = $row->redirect_to;
	    			$lab_structure = $row->lab_structure;
	    			$sampled = $row->sampled;
	    			$test_completed = $row->test_completed;
	    			$test_entered = $row->test_entered;
	    			$verified = $row->verified;
	    			$comments = $row->comments;
	    		}

	    		$balance = $this->onehealth_model->getTotalBalanceForTests($health_facility_id,$initiation_code);
	    		
	    		if($sub_dept_id == 6){
	    			if($paid == 0){
	    				$ret = "initiated but awaiting payment";
	    			}else if($paid == 1 && $balance > 0){
						$ret = "awaiting completion of payment";
					}else if($balance == 0 && $radiographer_complete == 0){
	    				$substr_name = substr($test_id,0,2);
						$substr_name = strtolower($substr_name);
						if($substr_name == "us"){
							$ret = "awaiting sonologist";
						}else{
							$ret = "awaiting radiographer";
						}	
	    			}else if($radiographer_complete == 1 && $radiology_complete == 0){
	    				$substr_name = substr($test_id,0,2);
						// echo $substr_name;
						if($redirect_to == "radiologist"){
							$ret = "awaiting radiologist";
						}elseif($redirect_to == "cardiologist"){
							$ret = "awaiting cardiologist";
						}	
	    			}else if($radiology_complete == 1){
	    				$ret = "test ready";
	    			}
	    		}else{
	    			if($lab_structure == ""){
	    				if($paid == 0){
		    				$ret = "initiated but awaiting payment";
		    			}else if($paid == 1 && $balance > 0){
							$ret = "awaiting completion of payment";
						}else if($balance == 0 && $sampled == 0){
		    				$ret = "awaiting sample collection";
		    			}else if($sampled == 1 && $test_completed == 0){
		    				$ret = "awaiting input of result";
		    			}
	    			}else if($lab_structure == "mini"){
	    				if($paid == 0){
		    				$ret = "initiated but awaiting payment";
		    			}else if($paid == 1 && $balance > 0){
							$ret = "awaiting completion of payment";
						}else if($balance == 0 && $sampled == 0){
		    				$ret = "awaiting sample collection";
		    			}else if($sampled == 1 && $test_completed == 0){
		    				$ret = "awaiting input of result";
		    			}else if($test_completed == 1){
		    				$ret = "test ready";
		    			}
	    			}else if($lab_structure == "standard" || $lab_structure == "maximum"){

	    				if($paid == 0){
		    				$ret = "initiated but awaiting payment";
		    			}else if($paid == 1 && $balance > 0){
							$ret = "awaiting completion of payment";
						}else if($balance == 0 && $sampled == 0){
		    				$ret = "awaiting sample collection";
		    			}else if($sampled == 1 && ($test_completed == 0)){
		    				$ret = "awaiting input of result";
		    			}else if(($test_completed == 1) && $verified == 0){
		    				$ret = "awaiting verification by supervisor";
		    			}else if($verified == 1 && $comments == ""){
		    				$ret = "awaiting entering of comments";
		    			}else if($comments != ""){
		    				$ret = "test ready";
		    			}
	    			}
	    		}

		    }
		    return $ret;
	    }

		public function getTestsSelectedByInitiationCodeAndHealthFacilityId($health_facility_id,$initiation_code){
			// echo $facility_slug;
			
			$query = $this->db->get_where("lab_facility_initiations",array('initiation_code' => $initiation_code,'health_facility_id' => $health_facility_id));
			// echo $this->db->last_query();
			// echo $facility_name;
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}


		public function getSelectedTestsByConsultationIdClinicDoctor($health_facility_id,$consultation_id){
			$this->db->select("*");
			$this->db->from('patient_tests_selected_clinic');
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->where('consultation_id',$consultation_id);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function addPatientTestsSelectedClinicRecord($form_array1){
			return $this->db->insert("patient_tests_selected_clinic",$form_array1);
		}

		public function removePendingClinicUsersReferralRecord($id,$health_facility_id){
			return $this->db->delete("pending_clinic_users_to_be_registered_referral",array('id' => $id,'health_facility_id' => $health_facility_id));
		}

		public function getPendingClinicUsersReferralParamByIdAndHealthFacilityId($param,$id,$health_facility_id){
			$query = $this->db->get_where("pending_clinic_users_to_be_registered_referral",array('id' => $id,'health_facility_id' => $health_facility_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}else{
				return false;
			}
		}

		public function checkIfPendingClinicUsersReferralIdIsValid($id,$health_facility_id){
			$query = $this->db->get_where("pending_clinic_users_to_be_registered_referral",array('id' => $id,'health_facility_id' => $health_facility_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getListOfReferralsByConsultationId($consultation_id){
			$query = $this->db->get_where("referrals_or_consults",array('main_health_facility_consultation_id' => $consultation_id,'viewable' => 1,'consultation_complete' => 1));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getReferralOrConsultParamById($param,$referral_id){
			$query = $this->db->get_where("referrals_or_consults",array('id' => $referral_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$param_val = $row->$param;
				}
				return $param_val;
			}else{
				return false;
			}
		}

		public function forwardPatientToNurseOffAppointment($form_array,$consultation_id){
			return $this->db->update("clinic_consultations",$form_array,array('id' => $consultation_id,'appointment_date !=' => '','records_registered' => 0));
		}

		public function getOnAppointmentsClinicRecords($health_facility_id,$sub_dept_id){
			$clinic_structure = $this->getHealthFacilityParamById("clinic_structure",$health_facility_id);

			$curr_date = date("Y-m-d");
			$this->db->select("*");
			$this->db->from("clinic_consultations");
			if($clinic_structure == "mini"){
				$this->db->where('sub_dept_id',55);
			}else{
				$this->db->where('sub_dept_id',$sub_dept_id);
			}
			$this->db->where('consultation_paid',1);
			$this->db->where('records_registered',0);
			$this->db->where('nurse_registered',0);
			$this->db->where('consultation_complete',0);
			$this->db->where('appointment_date',$curr_date);
			$this->db->order_by('id','DESC');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getPatientsFeesWardServicesNoneFeePaying($company_id,$health_facility_id){
	    	//get all codes of this company id
	    	$ret = array();
	    	$codes = array();
	    	$query = $this->db->get_where("verification_codes_records",array('company_id' => $company_id,'health_facility_id' => $health_facility_id,'taken' => 1));
	    	if($query->num_rows() > 0){
	    		foreach($query->result() as $row){
	    			$code = $row->code;
	    			$codes[] = $code;
	    		}
	    	}


	    	if(count($codes) > 0){
	    		for($i = 0; $i < count($codes); $i++){
	    			$code = $codes[$i];

		    		$query = $this->db->get_where('ward_services_requested',array('health_facility_id' => $health_facility_id,'insurance_code' => $code,'paid' => 1,'user_type' => 'nfp','receipt_file' => ""));
		    		if($query->num_rows() > 0){
		    			$ward_service_requested_ids = array();
		    			foreach($query->result() as $row){
		    				$id = $row->id;
		    				$ward_service_requested_ids[] = $id;
		    			}
		    			$ret = array_merge($ret,$ward_service_requested_ids);
		    		}
		    	}
	    	}

	    	// var_dump($ret);

	    	return $ret;
	    }

		public function getNoOfPaymentsInWardServicesnUnderACompanyIdNoneFeePaying($company_id,$health_facility_id){
	    	//get all codes of this company id
	    	$num = 0;
	    	$codes = array();
	    	$query = $this->db->get_where("verification_codes_records",array('company_id' => $company_id,'health_facility_id' => $health_facility_id,'taken' => 1));
	    	if($query->num_rows() > 0){
	    		foreach($query->result() as $row){
	    			$code = $row->code;
	    			$codes[] = $code;
	    		}
	    	}


	    	if(count($codes) > 0){
	    		for($i = 0; $i < count($codes); $i++){
	    			$code = $codes[$i];

		    		$query = $this->db->get_where('ward_services_requested',array('health_facility_id' => $health_facility_id,'insurance_code' => $code,'paid' => 1,'user_type' => 'nfp','receipt_file' => ""));
		    		$num += $query->num_rows();
		    	}
	    	}

	    	return $num;
	    }

		public function getCompaniesWardServicesNoneFeePaying($health_facility_id){
	    	$ret = array();
			$this->db->select("*");
			$this->db->from("ward_services_requested");
			$this->db->where("paid",1);
			$this->db->where("user_type","nfp");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("receipt_file","");
			
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$insurance_code = $row->insurance_code;
					$company_id = $this->onehealth_model->getCompanyIdByInsuranceCode($insurance_code,$health_facility_id);

					$ret[] = $company_id;
				}
			}

			return array_values(array_unique($ret));

	    }


		public function getWardServicesRequestedById($ward_service_requested_id,$health_facility_id){
			$query = $this->db->get_where('ward_services_requested',array('id' => $ward_service_requested_id,'health_facility_id' => $health_facility_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getPatientsFeesWardServicesPartFeePaying($company_id,$health_facility_id){
	    	//get all codes of this company id
	    	$ret = array();
	    	$codes = array();
	    	$query = $this->db->get_where("verification_codes_records",array('company_id' => $company_id,'health_facility_id' => $health_facility_id,'taken' => 1));
	    	if($query->num_rows() > 0){
	    		foreach($query->result() as $row){
	    			$code = $row->code;
	    			$codes[] = $code;
	    		}
	    	}


	    	if(count($codes) > 0){
	    		for($i = 0; $i < count($codes); $i++){
	    			$code = $codes[$i];

		    		$query = $this->db->get_where("ward_services_requested",array('insurance_code' => $code,'health_facility_id' => $health_facility_id,'user_type' => 'pfp','paid' => 1,'receipt_file !=' => ''));
		    		if($query->num_rows() > 0){
		    			$consultation_ids = array();
		    			foreach($query->result() as $row){
		    				$id = $row->id;
		    				$consultation_ids[] = $id;
		    			}
		    			$ret = array_merge($ret,$consultation_ids);
		    		}
		    	}
	    	}

	    	// var_dump($ret);

	    	return $ret;
	    }

		public function getNoOfPaymentsInWardServicesUnderACompanyIdPartFeePaying($company_id,$health_facility_id){
	    	//get all codes of this company id
	    	$num = 0;
	    	$codes = array();
	    	$query = $this->db->get_where("verification_codes_records",array('company_id' => $company_id,'health_facility_id' => $health_facility_id,'taken' => 1));
	    	if($query->num_rows() > 0){
	    		foreach($query->result() as $row){
	    			$code = $row->code;
	    			$codes[] = $code;
	    		}
	    	}


	    	if(count($codes) > 0){
	    		for($i = 0; $i < count($codes); $i++){
	    			$code = $codes[$i];

		    		$query = $this->db->get_where("ward_services_requested",array('insurance_code' => $code,'health_facility_id' => $health_facility_id,'user_type' => 'pfp','paid' => 1,'receipt_file !=' => ''));
		    		$num += $query->num_rows();
		    	}
	    	}

	    	return $num;
	    }

		public function getCompaniesWardServicesPartFeePaying($health_facility_id){
	    	$ret = array();
			$this->db->select("*");
			$this->db->from("ward_services_requested");
			$this->db->where("paid",1);
			$this->db->where("user_type","pfp");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("receipt_file !=","");
			
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$insurance_code = $row->insurance_code;
					$company_id = $this->onehealth_model->getCompanyIdByInsuranceCode($insurance_code,$health_facility_id);

					$ret[] = $company_id;
				}
			}

			return array_values(array_unique($ret));

	    }

		public function getPatientsWardServicesFullPaying($health_facility_id){
			$query = $this->db->get_where("ward_services_requested",array('health_facility_id' => $health_facility_id,'user_type' => 'fp','paid' => 1,'receipt_file !=' => ''));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function updateWardsServicesRequestedById($form_array,$id,$health_facility_id){
			return $this->db->update("ward_services_requested",$form_array,array('id' => $id,'health_facility_id' => $health_facility_id));
		}

		public function getWardServicesSelectedById($health_facility_id,$id){
			$query = $this->db->get_where("ward_services_requested",array('health_facility_id' => $health_facility_id,'id' => $id,'paid' => 0,'user_type !=' => 'nfp'));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function checkIfWardServiceRequestedIdIsValid($health_facility_id,$id){
			$query = $this->db->get_where("ward_services_requested",array('health_facility_id' => $health_facility_id,'id' => $id,'paid' => 0,'user_type !=' => 'nfp'));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getUnpaidWardServicesRecordsByPatientUserId($health_facility_id,$patient_user_id){
			$this->db->select("*");
			$this->db->from("ward_services_requested");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("user_id",$patient_user_id);
			$this->db->where("user_type !=",'nfp');
			$this->db->where("paid",0);
			$this->db->order_by("id","DESC");
			
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}
		}

		
		public function getLastdatTimeOfOutstandingWardServicesOfPatient($health_facility_id,$patient_user_id){
			$this->db->select("date,time");
			$this->db->from("ward_services_requested");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("user_id",$patient_user_id);
			$this->db->where("user_type !=",'nfp');
			$this->db->where("paid",0);
			$this->db->order_by("id","DESC");
			$this->db->limit(1);

			$query = $this->db->get();
			if($query->num_rows() == 1){
				return $query->result()[0]->date . " " . $query->result()[0]->time;
			}
		}

		public function getNumberOfPaymentsWithstandingForPatientWardsServices($health_facility_id,$patient_user_id){
			$num = 0;
			$query = $this->db->get_where("ward_services_requested",array('health_facility_id' => $health_facility_id,'user_id' => $patient_user_id,'paid' => 0,'user_type !=' => 'nfp'));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$num++;
				}
			}

			return $num;
		}

		public function getTotalAmountWithstandingForPatientWardsServices($health_facility_id,$patient_user_id){
			$sum = 0;
			$query = $this->db->get_where("ward_services_requested",array('health_facility_id' => $health_facility_id,'user_id' => $patient_user_id,'paid' => 0,'user_type !=' => 'nfp'));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$amount_due = $row->amount_due;

					$sum += $amount_due;
				}
			}

			return $sum;
		}

		public function getUserIdsOfPatientsAwaitingWardServicesPayment($health_facility_id){
			$ret_arr = array();

			$this->db->select("user_id");
			$this->db->from("ward_services_requested");
			$this->db->where("paid",0);
			$this->db->where("user_type !=",'nfp');

			$this->db->order_by("id","DESC");

			$query = $this->db->get();

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$user_id = $row->user_id;
					$ret_arr[] = $user_id;
				}
			}

			return array_values(array_unique($ret_arr));
		}

		public function getPatientRecordsByConsultationId($health_facility_id,$consultation_id){
			$query = $this->db->get_where("clinic_consultations",array('id' => $consultation_id,'health_facility_id' => $health_facility_id));
			return $query->result();
		}

		public function getDifferenceBetwwenTwoDates($date1,$date2){
			$val = "";
			

			$date1 = date("j M Y h:i:sa",strtotime($date1));
			$date1 = strtotime($date1);
			$date2 = date("j M Y h:i:sa",strtotime($date2));
			$date2 = strtotime($date2);
			$date_diff_secs = $date1 - $date2;
			
		        
		    $date_diff_seconds = $date_diff_secs;
		    
		    if($date_diff_seconds > 0){
		      $date_diff_minutes = floor($date_diff_seconds / 60);
		      $date_diff_hours = floor($date_diff_minutes / 60);
		      $date_diff_days = floor($date_diff_hours / 24);
		      $date_diff_weeks = floor($date_diff_days / 7);
		      $date_diff_months = floor($date_diff_weeks / 4.345);
		      $date_diff_years = floor($date_diff_months / 12);
		      
		      if($date_diff_minutes < 1){
		        $val = "";
		      }else if($date_diff_hours < 1){
		        $val = $date_diff_minutes . " minute(s)";
		      }else if($date_diff_days < 1){
		        $val = $date_diff_hours . " hour(s)";
		      }else if($date_diff_weeks < 1){
		        $val = $date_diff_days . " day(s)";
		      }else if($date_diff_months < 1){
		        $val = $date_diff_weeks . " week(s)";
		      }else if($date_diff_years < 1){
		        $val = $date_diff_months . " month(s)";
		      }else{
		        $val = $date_diff_years . " year(s)";
		      }
		    }else{
		      return false;
		    }

		    return $val;
		}

		public function getWardinfoById($ward_record_id,$health_facility_id){
			$query = $this->db->get_where('wards',array('health_facility_id' => $health_facility_id,'id' => $ward_record_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getAmountAccruedByNoneFeePayingWardPatient($ward_record_id,$health_facility_id){
			$query = $this->db->get_where('wards',array('health_facility_id' => $health_facility_id,'id' => $ward_record_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){

					$id = $row->id;
					$user_type = $row->user_type;
					$insurance_code = $row->insurance_code;
					$health_facility_id = $row->health_facility_id;
					$user_id = $row->user_id;
					$admission_fee = $row->admission_fee;
					$balance = $row->balance;
					$admission_no_of_days = $row->admission_no_of_days;
					$admission_grace = $row->admission_grace;
					$last_admission_payment_date = $row->last_admission_payment_date;
					$last_admission_payment_time = $row->last_admission_payment_time;
					$consultation_id = $row->consultation_id;
					$doctor_id = $row->doctor_id;
					$sub_dept_id = $row->sub_dept_id;
					$clinic_id = $row->clinic_id;
					$discharged = $row->discharged;
					$discharged_date = $row->discharged_date;
					$discharged_time = $row->discharged_time;
					$report = $row->report;
					$date = $row->date;
					$time = $row->time;
					$consults_list = $row->consults_list;

					if($discharged == 1){
						$admission_date = $date . " " . $time;;

						$admission_date = date("j M Y h:i:sa",strtotime($admission_date));
						$admission_date = strtotime($admission_date);
						$date_discharged = date("j M Y h:i:sa",strtotime($discharged_date . " " . $discharged_time));
						$date_discharged = strtotime($date_discharged);
						$date_diff_secs = $date_discharged - $admission_date;
						
					        
					    $date_diff_seconds = $date_diff_secs;
					    $amount_due_for_one_day = $admission_fee / $admission_no_of_days;
					    // echo $amount_due_for_one_day . "<br>";

					    $amount_due_for_one_second = $amount_due_for_one_day / 86400;
					    
					    $total_amount_due = round(($amount_due_for_one_second * $date_diff_seconds),2);
					    return $total_amount_due;

					}else{
						return false;
					}
				}
		    
		    }else{
		    	return false;
		    }

		    
		}

		public function getPatientsFeesWardAdmissionsNoneFeePaying($company_id,$health_facility_id){
	    	//get all codes of this company id
	    	$ret = array();
	    	$codes = array();
	    	$query = $this->db->get_where("verification_codes_records",array('company_id' => $company_id,'health_facility_id' => $health_facility_id,'taken' => 1));
	    	if($query->num_rows() > 0){
	    		foreach($query->result() as $row){
	    			$code = $row->code;
	    			$codes[] = $code;
	    		}
	    	}


	    	if(count($codes) > 0){
	    		for($i = 0; $i < count($codes); $i++){
	    			$code = $codes[$i];

		    		$query = $this->db->get_where('wards',array('health_facility_id' => $health_facility_id,'insurance_code' => $code,'discharged' => 1,'user_type' => 'nfp'));
		    		if($query->num_rows() > 0){
		    			$consultation_ids = array();
		    			foreach($query->result() as $row){
		    				$id = $row->id;
		    				$consultation_ids[] = $id;
		    			}
		    			$ret = array_merge($ret,$consultation_ids);
		    		}
		    	}
	    	}

	    	// var_dump($ret);

	    	return $ret;
	    }

		public function getNoOfPaymentsInWardAdmissionUnderACompanyIdNoneFeePaying($company_id,$health_facility_id){
	    	//get all codes of this company id
	    	$num = 0;
	    	$codes = array();
	    	$query = $this->db->get_where("verification_codes_records",array('company_id' => $company_id,'health_facility_id' => $health_facility_id,'taken' => 1));
	    	if($query->num_rows() > 0){
	    		foreach($query->result() as $row){
	    			$code = $row->code;
	    			$codes[] = $code;
	    		}
	    	}


	    	if(count($codes) > 0){
	    		for($i = 0; $i < count($codes); $i++){
	    			$code = $codes[$i];

		    		$query = $this->db->get_where('wards',array('health_facility_id' => $health_facility_id,'insurance_code' => $code,'discharged' => 1,'user_type' => "nfp"));
		    		$num += $query->num_rows();
		    	}
	    	}

	    	return $num;
	    }

		public function getNoOfPaymentsInWardAdmissionUnderACompanyId($company_id,$health_facility_id,$user_type){
	    	//get all codes of this company id
	    	$num = 0;
	    	$codes = array();
	    	$query = $this->db->get_where("verification_codes_records",array('company_id' => $company_id,'health_facility_id' => $health_facility_id,'taken' => 1));
	    	if($query->num_rows() > 0){
	    		foreach($query->result() as $row){
	    			$code = $row->code;
	    			$codes[] = $code;
	    		}
	    	}


	    	if(count($codes) > 0){
	    		for($i = 0; $i < count($codes); $i++){
	    			$code = $codes[$i];

		    		$query = $this->db->get_where("clinic_payments",array('health_facility_id' => $health_facility_id,'user_type' => $user_type,'insurance_code' => $code,'type' => 'ward_admission'));
		    		$num += $query->num_rows();
		    	}
	    	}

	    	return $num;
	    }

		public function getCompaniesWardAdmissionsNoneFeePaying($health_facility_id){
	    	$ret = array();
			$this->db->select("*");
			$this->db->from("wards");
			$this->db->where("discharged",1);
			$this->db->where("user_type","nfp");
			$this->db->where("health_facility_id",$health_facility_id);
			
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$insurance_code = $row->insurance_code;
					$company_id = $this->onehealth_model->getCompanyIdByInsuranceCode($insurance_code,$health_facility_id);

					$ret[] = $company_id;
				}
			}

			return array_values(array_unique($ret));

	    }

		public function getClinicPaymentParamById($param,$clinic_payment_id){
			$query = $this->db->get_where("clinic_payments",array('id' => $clinic_payment_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}else{
				return false;
			}
		}

		public function getPatientsFeesWardAdmissions($company_id,$health_facility_id,$user_type){
	    	//get all codes of this company id
	    	$ret = array();
	    	$codes = array();
	    	$query = $this->db->get_where("verification_codes_records",array('company_id' => $company_id,'health_facility_id' => $health_facility_id,'taken' => 1));
	    	if($query->num_rows() > 0){
	    		foreach($query->result() as $row){
	    			$code = $row->code;
	    			$codes[] = $code;
	    		}
	    	}


	    	if(count($codes) > 0){
	    		for($i = 0; $i < count($codes); $i++){
	    			$code = $codes[$i];

		    		$query = $this->db->get_where("clinic_payments",array('health_facility_id' => $health_facility_id,'user_type' => $user_type,'insurance_code' => $code,'type' => 'ward_admission'));
		    		if($query->num_rows() > 0){
		    			$consultation_ids = array();
		    			foreach($query->result() as $row){
		    				$id = $row->id;
		    				$consultation_ids[] = $id;
		    			}
		    			$ret = array_merge($ret,$consultation_ids);
		    		}
		    	}
	    	}

	    	// var_dump($ret);

	    	return $ret;
	    }

		public function getCompaniesWardAdmissions($health_facility_id,$user_type){
	    	$ret = array();
			$this->db->select("*");
			$this->db->from("clinic_payments");
			$this->db->where("type",'ward_admission');
			$this->db->where("user_type",$user_type);
			$this->db->where("health_facility_id",$health_facility_id);
			
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$insurance_code = $row->insurance_code;
					$company_id = $this->onehealth_model->getCompanyIdByInsuranceCode($insurance_code,$health_facility_id);

					$ret[] = $company_id;
				}
			}

			return array_values(array_unique($ret));

	    }

		public function getPatientswardAdmissionFullPaying($health_facility_id){
			$query = $this->db->get_where("clinic_payments",array('health_facility_id' => $health_facility_id,'user_type' => 'fp','type' => 'ward_admission'));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function processClinicPayment($form_array1){
			$query = $this->db->insert('clinic_payments',$form_array1);
			if($query){
				return true;

				// $health_facility_id = $form_array1['health_facility_id'];
				// $patient_user_id = $form_array1['patient_id'];
				// $receipt_file = $form_array1['receipt_file'];
				// $date = $form_array1['date'];
				// $time = $form_array1['time'];

				// $sender = $this->getHealthFacilityNameById($health_facility_id);
				// $receiver = $this->getUserParamById("user_name",$patient_user_id);
				// $title = "Wards Admisson Payment";

				// $message = "This Is To Alert You About The Successful Status Of Your Ward Admisson Payment. View Your Receipt <a target='_blank' href='".base_url('assets/images/'.$receipt_file)."'>Here</a>";

				// $notif_array = array(
				// 	'sender' => $sender,
				// 	'receiver' => $receiver,
				// 	'title' => $title,
				// 	'message' => $message,
				// 	'date_sent' => $date,
				// 	'time_sent' => $time
				// );

				// if($this->sendMessage($notif_array)){
				// 	return true;
				// }
			}
		}


		public function getWardParamByWardRecordIdAndHealthFacilityId($param,$health_facility_id,$ward_record_id){
			$query = $this->db->get_where("wards",array('health_facility_id' => $health_facility_id,'id' => $ward_record_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}else{
				return false;
			}
		}

		public function checkIfWardIdIsCorrect($ward_id){
			$query = $this->db->get_where("sub_dept",array('id' => $ward_id,'dept_id' => 5));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function generateWardIdString(){
			$ret_arr = array();
			$query = $this->db->get_where('sub_dept',array('dept_id' => 5));
    		if($query->num_rows() > 0){
    			foreach($query->result() as $row){
    				$sub_dept_id = $row->id;

    				$ret_arr[] = $sub_dept_id;
    			}
    		}
    		
    		if(is_array($ret_arr) && count($ret_arr) > 0){
    			return implode(",", $ret_arr);
    		}
		}

		public function displayAllWards($health_facility_id){
	    	if($this->checkIfFacilityIsHospital($health_facility_id)){
	    		$clinic_structure = $this->getHealthFacilityParamById("clinic_structure",$health_facility_id);
	    		$ward_ids = $this->getHealthFacilityParamById("ward_ids",$health_facility_id);
		    	if($ward_ids != ""){
		    		$ret_arr = array();
		    		$ward_id_arr = explode(",", $ward_ids);
			    	$query = $this->db->get_where('sub_dept',array('dept_id' => 5));
		    		if($query->num_rows() > 0){
		    			foreach($query->result() as $row){
		    				$sub_dept_id = $row->id;

		    				if(in_array($sub_dept_id, $ward_id_arr)){
		    					$ret_arr[] = $row;
		    				}
		    			}
		    		}
		    		return $ret_arr;
		    	}else{
		    		return false;
		    	}
	    	}else{
	    		return false;
	    	}
	    }


		public function submitClinicConsultation($health_facility_id,$form_array,$consultation_id){
			return $this->db->update("clinic_consultations",$form_array,array('health_facility_id' => $health_facility_id,'id' => $consultation_id,'consultation_complete' => 0));
		}

		public function getLastEnteredConsultationRecordsForPatientClinic($health_facility_id,$patient_user_id,$consultation_id){
			$all_clinic_consultation_ids_for_this_patient = $this->getAllClinicConsultationIdsForThisPatient($health_facility_id,$patient_user_id,$consultation_id);

			if(is_array($all_clinic_consultation_ids_for_this_patient) && count($all_clinic_consultation_ids_for_this_patient) > 0){
				$consultation_id_and_complete_date_time = array();

				for($i = 0; $i < count($all_clinic_consultation_ids_for_this_patient); $i++){
					$id = $all_clinic_consultation_ids_for_this_patient[$i];

					$consultation_complete = $this->getClinicConsultationParamById("consultation_complete",$id);
					$consultation_complete_date = $this->getClinicConsultationParamById("consultation_complete_date",$id);

					$consultation_complete_time = $this->getClinicConsultationParamById("consultation_complete_time",$id);
					$seconds_num = strtotime($consultation_complete_date . " " . $consultation_complete_time);
					if($consultation_complete == 1){

						$consultation_id_and_complete_date_time[$id] = $seconds_num;
					}
				}
				// var_dump($consultation_id_and_complete_date_time);
				if(is_array($consultation_id_and_complete_date_time) && count($consultation_id_and_complete_date_time) > 0){
					$max = max($consultation_id_and_complete_date_time);
					$index = array_search($max, $consultation_id_and_complete_date_time);

					$query = $this->db->get_where("clinic_consultations",array('health_facility_id' => $health_facility_id,'id' => $index,'user_id' => $patient_user_id));
					if($query->num_rows() > 0){
						return $query->result();
					}else{
						return false;
					}
				}
			}else{
				return false;
			}
		}

		public function getAllClinicConsultationIdsForThisPatient($health_facility_id,$patient_user_id,$consultation_id){
			$consultation_ids = array();

			$query = $this->db->get_where("clinic_consultations",array('health_facility_id' => $health_facility_id,'user_id' => $patient_user_id,'id !=' => $consultation_id));

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$consultation_ids[] = $row->id;
				}
			}

			return array_values(array_unique($consultation_ids));
		}

		public function getConsultationIdsOfOffAppointmentClinicDoctor($health_facility_id,$sub_dept_id){
			$consultation_ids = array();
			$clinic_structure = $this->onehealth_model->getHealthFacilityParamById("clinic_structure",$health_facility_id);

			if($clinic_structure == "mini"){
				$query = $this->db->get_where("clinic_consultations",array('health_facility_id' => $health_facility_id,'sub_dept_id' => 55,'consultation_paid' => 1,'nurse_registered' => 1,'consultation_complete' => 0));
			}else{
				$query = $this->db->get_where("clinic_consultations",array('health_facility_id' => $health_facility_id,'sub_dept_id' => $sub_dept_id,'consultation_paid' => 1,'nurse_registered' => 1,'consultation_complete' => 0));
			}
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$consultation_id = $row->id;
					$consultation_ids[] = $consultation_id;
				}
			}

			return array_values(array_unique($consultation_ids));
		}

		public function getOnAppointmentsClinicDoctor($health_facility_id,$sub_dept_id){
			$clinic_structure = $this->getHealthFacilityParamById("clinic_structure",$health_facility_id);

			$curr_date = date("Y-m-d");
			$this->db->select("*");
			$this->db->from("clinic_consultations");
			if($clinic_structure == "mini"){
				$this->db->where('sub_dept_id',55);
			}else{
				$this->db->where('sub_dept_id',$sub_dept_id);
			}
			$this->db->where('consultation_paid',1);
			$this->db->where('nurse_registered',1);
			$this->db->where('consultation_complete',0);
			$this->db->where('appointment_date',$curr_date);
			$this->db->order_by('id','DESC');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function checkIfVitalSignsChanged($health_facility_id,$consultation_id,$pr,$rr,$bp,$temperature,$waist_circumference,$hip_circumference){
			$consultation_pr = $this->getClinicConsultationParamById("pr",$consultation_id);
			$consultation_rr = $this->getClinicConsultationParamById("rr",$consultation_id);
			$consultation_bp = $this->getClinicConsultationParamById("bp",$consultation_id);
			$consultation_temperature = $this->getClinicConsultationParamById("temperature",$consultation_id);
			$consultation_waist_circumference = $this->getClinicConsultationParamById("waist_circumference",$consultation_id);
			$consultation_hip_circumference = $this->getClinicConsultationParamById("hip_circumference",$consultation_id);



			if($pr == $consultation_pr && $rr == $consultation_rr && $bp == $consultation_bp && $temperature == $consultation_temperature && $waist_circumference == $consultation_waist_circumference && $hip_circumference == $consultation_hip_circumference){
				return false;
			}else{
				return true;
			}
		}

		public function addVitalSignsToConsultationDoctorStandard($form_array,$health_facility_id,$consultation_id,$sub_dept_id){
			return $this->db->update("clinic_consultations",$form_array,array('health_facility_id' => $health_facility_id,'id' => $consultation_id,'sub_dept_id' => $sub_dept_id,'nurse_registered' => 1));
		}

		public function addVitalSignsToConsultationDoctorMini($form_array,$health_facility_id,$consultation_id){
			return $this->db->update("clinic_consultations",$form_array,array('health_facility_id' => $health_facility_id,'id' => $consultation_id,'sub_dept_id' => 55,'nurse_registered' => 1));
		}

		public function getOnAppointmentsClinicNurse($health_facility_id,$sub_dept_id){
			$clinic_structure = $this->getHealthFacilityParamById("clinic_structure",$health_facility_id);

			$curr_date = date("Y-m-d");
			$this->db->select("*");
			$this->db->from("clinic_consultations");
			if($clinic_structure == "mini"){
				$this->db->where('sub_dept_id',55);
			}else{
				$this->db->where('sub_dept_id',$sub_dept_id);
			}
			$this->db->where('consultation_paid',1);
			$this->db->where('records_registered',1);
			$this->db->where('nurse_registered',0);
			$this->db->where('consultation_complete',0);
			$this->db->where('appointment_date',$curr_date);
			$this->db->order_by('id','DESC');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function checkIfThisIsTheFirstConsultationOfThisPatient($consultation_id,$patient_user_id,$health_facility_id){
			$first_consultation_id = $this->onehealth_model->getPatientsFirstConsultationId($patient_user_id,$health_facility_id);
			// echo $first_consultation_id . "<br>";

			if($first_consultation_id == $consultation_id){
				return true;
			}else{
				return false;
			}
		}

		public function getConsultationIdsOfOffAppointmentClinicNurse($health_facility_id,$sub_dept_id){
			$consultation_ids = array();
			$clinic_structure = $this->onehealth_model->getHealthFacilityParamById("clinic_structure",$health_facility_id);

			if($clinic_structure == "mini"){
				$query = $this->db->get_where("clinic_consultations",array('health_facility_id' => $health_facility_id,'sub_dept_id' => 55,'consultation_paid' => 1,'nurse_registered' => 0));
			}else{
				$query = $this->db->get_where("clinic_consultations",array('health_facility_id' => $health_facility_id,'sub_dept_id' => $sub_dept_id,'consultation_paid' => 1,'nurse_registered' => 0));
			}
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$consultation_id = $row->id;
					$consultation_ids[] = $consultation_id;
				}
			}

			return array_values(array_unique($consultation_ids));
		}

		public function addVitalSignsToConsultationStandard($form_array,$health_facility_id,$consultation_id,$sub_dept_id){
			return $this->db->update("clinic_consultations",$form_array,array('health_facility_id' => $health_facility_id,'id' => $consultation_id,'sub_dept_id' => $sub_dept_id,'nurse_registered' => 0));
		}

		public function addVitalSignsToConsultationMini($form_array,$health_facility_id,$consultation_id){
			return $this->db->update("clinic_consultations",$form_array,array('health_facility_id' => $health_facility_id,'id' => $consultation_id,'sub_dept_id' => 55,'nurse_registered' => 0));
		}

		public function getConsultationDataById($health_facility_id,$consultation_id){
			$query = $this->db->get_where("clinic_consultations",array('id' => $consultation_id,'health_facility_id' => $health_facility_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getPatientsFirstConsultationId($patient_user_id,$health_facility_id){
			$this->db->select_min("id");
			$this->db->from("clinic_consultations");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("user_id",$patient_user_id);
			$this->db->where("consultation_paid",1);
			$this->db->limit(1);

			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result()[0]->id;
			}else{
				return false;
			}
		}

		public function checkIfPatientsFirstClinicConsultationIsPaid($patient_user_id,$health_facility_id){
			$this->db->select_min("id");
			$this->db->from("clinic_consultations");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("user_id",$patient_user_id);
			$this->db->where("consultation_paid",1);
			$this->db->limit(1);

			$query = $this->db->get();
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}


		public function getPatientsUserIdsClinicConsultation($health_facility_id,$sub_dept_id){
			$user_ids = array();
			$clinic_structure = $this->onehealth_model->getHealthFacilityParamById("clinic_structure",$health_facility_id);

			if($clinic_structure == "mini"){
				$query = $this->db->get_where("clinic_consultations",array('health_facility_id' => $health_facility_id,'sub_dept_id' => 55));
			}else{
				$query = $this->db->get_where("clinic_consultations",array('health_facility_id' => $health_facility_id,'sub_dept_id' => $sub_dept_id));
			}
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$user_id = $row->user_id;
					$user_ids[] = $user_id;
				}
			}

			return array_values(array_unique($user_ids));
		}

		public function getPatientsFeesClinicConsultation($company_id,$health_facility_id,$user_type){
	    	//get all codes of this company id
	    	$ret = array();
	    	$codes = array();
	    	$query = $this->db->get_where("verification_codes_records",array('company_id' => $company_id,'health_facility_id' => $health_facility_id,'taken' => 1));
	    	if($query->num_rows() > 0){
	    		foreach($query->result() as $row){
	    			$code = $row->code;
	    			$codes[] = $code;
	    		}
	    	}


	    	if(count($codes) > 0){
	    		for($i = 0; $i < count($codes); $i++){
	    			$code = $codes[$i];

		    		$query = $this->db->get_where('clinic_consultations',array('health_facility_id' => $health_facility_id,'insurance_code' => $code,'consultation_paid' => 1,'user_type' => $user_type,'payment_type' => "pay now"));
		    		if($query->num_rows() > 0){
		    			$consultation_ids = array();
		    			foreach($query->result() as $row){
		    				$id = $row->id;
		    				$consultation_ids[] = $id;
		    			}
		    			$ret = array_merge($ret,$consultation_ids);
		    		}
		    	}
	    	}

	    	// var_dump($ret);

	    	return $ret;
	    }

		public function getNoOfPaymentsInClinicConsultationUnderACompanyId($company_id,$health_facility_id,$user_type){
	    	//get all codes of this company id
	    	$num = 0;
	    	$codes = array();
	    	$query = $this->db->get_where("verification_codes_records",array('company_id' => $company_id,'health_facility_id' => $health_facility_id,'taken' => 1));
	    	if($query->num_rows() > 0){
	    		foreach($query->result() as $row){
	    			$code = $row->code;
	    			$codes[] = $code;
	    		}
	    	}


	    	if(count($codes) > 0){
	    		for($i = 0; $i < count($codes); $i++){
	    			$code = $codes[$i];

		    		$query = $this->db->get_where('clinic_consultations',array('health_facility_id' => $health_facility_id,'insurance_code' => $code,'consultation_paid' => 1,'user_type' => $user_type,'payment_type' => "pay now"));
		    		$num += $query->num_rows();
		    	}
	    	}

	    	return $num;
	    }

		public function getCompaniesClinicConsultations($health_facility_id,$user_type){
	    	$ret = array();
			$this->db->select("*");
			$this->db->from("clinic_consultations");
			$this->db->where("consultation_paid",1);
			$this->db->where("user_type",$user_type);
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("payment_type","pay now");
			
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$insurance_code = $row->insurance_code;
					$company_id = $this->onehealth_model->getCompanyIdByInsuranceCode($insurance_code,$health_facility_id);

					$ret[] = $company_id;
				}
			}

			return array_values(array_unique($ret));

	    }


		public function getPatientsConsultationFeesFullPaying($health_facility_id){
			$query = $this->db->get_where("clinic_consultations",array('health_facility_id' => $health_facility_id,'user_type' => 'fp','consultation_paid' => 1,'payment_type' => 'pay now'));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getPatientsFeesClinicRegistration($company_id,$health_facility_id,$user_type){
	    	//get all codes of this company id
	    	$ret = array();
	    	$codes = array();
	    	$query = $this->db->get_where("verification_codes_records",array('company_id' => $company_id,'health_facility_id' => $health_facility_id,'taken' => 1));
	    	if($query->num_rows() > 0){
	    		foreach($query->result() as $row){
	    			$code = $row->code;
	    			$codes[] = $code;
	    		}
	    	}


	    	if(count($codes) > 0){
	    		for($i = 0; $i < count($codes); $i++){
	    			$code = $codes[$i];

		    		$query = $this->db->get_where('patients_in_facility',array('health_facility_id' => $health_facility_id,'insurance_code_at_registration' => $code,'paid' => 1,'user_type_at_registration' => $user_type));
		    		if($query->num_rows() > 0){
		    			$patient_facility_ids = array();
		    			foreach($query->result() as $row){
		    				$id = $row->id;
		    				$patient_facility_ids[] = $id;
		    			}
		    			$ret = array_merge($ret,$patient_facility_ids);
		    		}
		    	}
	    	}

	    	// var_dump($ret);

	    	return $ret;
	    }

		public function getNoOfPaymentsInClinicRegistrationUnderACompanyId($company_id,$health_facility_id,$user_type){
	    	//get all codes of this company id
	    	$num = 0;
	    	$codes = array();
	    	$query = $this->db->get_where("verification_codes_records",array('company_id' => $company_id,'health_facility_id' => $health_facility_id,'taken' => 1));
	    	if($query->num_rows() > 0){
	    		foreach($query->result() as $row){
	    			$code = $row->code;
	    			$codes[] = $code;
	    		}
	    	}


	    	if(count($codes) > 0){
	    		for($i = 0; $i < count($codes); $i++){
	    			$code = $codes[$i];

		    		$query = $this->db->get_where('patients_in_facility',array('health_facility_id' => $health_facility_id,'insurance_code_at_registration' => $code,'paid' => 1,'user_type_at_registration' => $user_type));
		    		$num += $query->num_rows();
		    	}
	    	}

	    	return $num;
	    }

		public function getCompaniesClinicRegistrations($health_facility_id,$user_type){
	    	$ret = array();
			$this->db->select("*");
			$this->db->from("patients_in_facility");
			$this->db->where("paid",1);
			$this->db->where("user_type_at_registration",$user_type);
			$this->db->where("health_facility_id",$health_facility_id);
			
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$insurance_code = $row->insurance_code_at_registration;
					$company_id = $this->onehealth_model->getCompanyIdByInsuranceCode($insurance_code,$health_facility_id);

					$ret[] = $company_id;
				}
			}

			return array_values(array_unique($ret));

	    }

		public function getPatientsRegistrationFeesFullPaying($health_facility_id){
			$query = $this->db->get_where("patients_in_facility",array('health_facility_id' => $health_facility_id,'user_type_at_registration' => 'fp','paid' => 1));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function processClinicConsultationPayment($form_array,$consultation_id,$health_facility_id){
			return $this->db->update("clinic_consultations",$form_array,array('health_facility_id' => $health_facility_id,'id' => $consultation_id));
		}

		public function getPartFeePayingPercentageDiscountForPatient($health_facility_id,$insurance_code){
			$ret = 0;

			$insurance_code_char_num = mb_strlen($insurance_code);
			
			
			while ($insurance_code_char_num > 0) {
				$insurance_code_char_num--;
				
				$code_num = $insurance_code_char_num * -1;
				$substr_insurance_code = substr($insurance_code, $code_num);
				$query = $this->db->get_where("part_fee_paying_percentages",array('code' => $substr_insurance_code,'health_facility_id' => $health_facility_id));
				if($query->num_rows() == 1){
					$ret = $query->result()[0]->percentage_discount;
					break;
				}
			}

			return $ret;
		}

		public function getClinicConsultationParamById($param,$consultation_id){
			$query = $this->db->get_where("clinic_consultations",array('id' => $consultation_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}else{
				return false;
			}
		}

		public function updateClinicConsultation($form_array,$consultation_id,$health_facility_id){
			return $this->db->update("clinic_consultations",$form_array,array('id' => $consultation_id,'health_facility_id' => $health_facility_id));
		}

		public function checkIfThisConsultationIsPaidFor($consultation_id,$health_facility_id){
			$query = $this->db->get_where("clinic_consultations",array('id' => $consultation_id,'health_facility_id' => $health_facility_id,'consultation_paid' => 1));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfConsultationIdIsValid($consultation_id,$health_facility_id){
			$query = $this->db->get_where("clinic_consultations",array('health_facility_id' => $health_facility_id,'id' => $consultation_id));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function loadPatientsAwaitingConsultationPaymentClinic($health_facility_id){
			$query = $this->db->get_where("clinic_consultations",array('health_facility_id' => $health_facility_id,'consultation_paid' => 0));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function updatePartFeePayingPercentageDiscount($form_array,$id,$health_facility_id){
			return $this->db->update("part_fee_paying_percentages",$form_array,array('id' => $id,'health_facility_id' => $health_facility_id));
		}

		public function checkIfPartPaymentPercentageIdIsValid($id,$health_facility_id){
			$query = $this->db->get_where("part_fee_paying_percentages",array('id' => $id,'health_facility_id' => $health_facility_id));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function addNewPartFeePayingPercentageDiscount($form_array){
			return $this->db->insert("part_fee_paying_percentages",$form_array);
		}

		public function checkIfPartpaymentPercentageCodeIsValid($code,$health_facility_id){
			$num = 0;

			$code_char_num = mb_strlen($code);

			$code_char_num = $code_char_num * -1;

			$query = $this->db->get_where('verification_codes_records',array('health_facility_id' => $health_facility_id,'type' => 'pfp'));

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$verification_code = $row->code;

					$substr_verification_code = substr($verification_code,$code_char_num);

					if($substr_verification_code == $code){
						$num++;
					}
				}
			}else{
				return false;
			}

			if($num > 0){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfPartpaymentPercentageCodeHasBeenUsedBefore($code,$health_facility_id){
			$query = $this->db->get_where("part_fee_paying_percentages",array('code' => $code,'health_facility_id' => $health_facility_id));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function viewPercentagesForPartFeePaying($health_facility_id){
			$this->db->select("*");
			$this->db->from("part_fee_paying_percentages");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->order_by("id","DESC");

			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function initiateConsultationClinic($form_array){
			return $this->db->insert("clinic_consultations",$form_array);
		}

		public function getPatientFullNameByPatientfacilityId($patient_user_id){
	    	$title = $this->onehealth_model->getPatientParamByUserId("title",$patient_user_id);
			$first_name = $this->onehealth_model->getPatientParamByUserId("first_name",$patient_user_id);
			$last_name = $this->onehealth_model->getPatientParamByUserId("last_name",$patient_user_id);
			$patient_name = $title . " " .$first_name . " " .$last_name;
			return $patient_name;
	    }

		public function processClinicRegistrationPayment($form_array,$patient_facility_id,$health_facility_id){
			return $this->db->update("patients_in_facility",$form_array,array('id' => $patient_facility_id,'health_facility_id' => $health_facility_id));
		}

		public function checkIfThisPatientHasPaidClinicRegistrationFee($health_facility_id,$patient_facility_id){
			$query = $this->db->get_where("patients_in_facility",array('health_facility_id' => $health_facility_id,'id' => $patient_facility_id));
			if($query->num_rows() == 1){
				if($query->result()[0]->paid == 1){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		public function loadPatientsAwaitingRegistrationPaymentClinic($health_facility_id){
			$this->db->select("*");
			$this->db->from("patients_in_facility");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("paid",0);
			$this->db->order_by("id","DESC");

			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function loadPreviouslyRegisteredPatientsClinic($health_facility_id){
			$this->db->select("*");
			$this->db->from("patients_in_facility");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("paid",1);
			$this->db->order_by("id","DESC");

			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function updatePatientUserType($form_array,$patient_facility_id,$health_facility_id){
			return $this->db->update("patients_in_facility",$form_array,array('id' => $patient_facility_id,'health_facility_id' => $health_facility_id));
		}

		public function checkIfPatientFacilityIdIsValid($health_facility_id,$patient_facility_id){
			$query = $this->db->get_where('patients_in_facility',array('health_facility_id' => $health_facility_id,'id' => $patient_facility_id));

			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}
		public function uploadAboutTest($health_facility_test_table_name,$test_id,$about_test){
			return $this->db->update($health_facility_test_table_name,array('about_test' => $about_test),array('test_id' => $test_id));
		}

		public function getAboutTestByTestId($health_facility_test_table_name,$test_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('test_id' => $test_id),1);
			if($query->num_rows() == 1){
				return $query->result()[0]->about_test;
			}
		}

		public function getAllTestIdsWithAboutTestsNotEmpty($health_facility_test_table_name){
			$ret = array();
			$this->db->select("test_id");
			$this->db->from($health_facility_test_table_name);
			$this->db->where("about_test !=","");

			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$test_id = $row->test_id;

					$ret[] = $test_id;
				}
			}

			return array_values(array_unique($ret));
		}

		public function getNumberOfTestsRequestedByPatient($initiation_code,$health_facility_id){
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
			return $query->num_rows();
		}


		public function getInitiationCodesForReferralDrs($health_facility_id){
			$ret = array();
			$this->db->select("*");
			$this->db->from("lab_facility_initiations");
			$this->db->where("paid",1);
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->order_by("id","DESC");
			

			$query = $this->db->get();
			
			if($query->num_rows() > 0){
				// var_dump($query->result());
				foreach($query->result() as $row){
					
					$initiation_code = $row->initiation_code;
					$user_type = $row->user_type;
					$referring_doctor = $row->referring_doctor;
					$referring_doctor_email = $row->referring_doctor_email;
					$referring_doctor_phone = $row->referring_doctor_phone;
					if($this->getTotalBalanceForTests($health_facility_id,$initiation_code) == 0 && $user_type == "fp" ){
						if(($referring_doctor != "" || $referring_doctor_email != "" || $referring_doctor_phone != "")){
							$ret[] = $initiation_code;
						}
					}
					
				}
			}
			return array_values(array_unique($ret));
		}

		public function getPatientsFeesLaboratoryReferral($health_facility_id,$days_num){
			$ret = array();
			$this->db->select("*");
			$this->db->from("lab_facility_initiations");
			$this->db->where("paid",1);
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("referring_facility_id !=",0);

			$query = $this->db->get();
			
			if($query->num_rows() > 0){
				// var_dump($query->result());
				foreach($query->result() as $row){
					
					$initiation_code = $row->initiation_code;
					$user_type = $row->user_type;
					
					$ret[] = $initiation_code;
					
				}
			}else{
				return false;
			}
			return $ret;
		}

		public function getPatientsFeesLaboratoryNonePaying($health_facility_id,$days_num){
			$ret = array();
			$this->db->select("*");
			$this->db->from("lab_facility_initiations");
			$this->db->where("paid",1);
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("referring_facility_id",0);

			$query = $this->db->get();
			
			if($query->num_rows() > 0){
				// var_dump($query->result());
				foreach($query->result() as $row){
					
					$initiation_code = $row->initiation_code;
					$user_type = $row->user_type;
					// echo $patient_username;
					if($user_type == "nfp"){
						$ret[] = $initiation_code;
					}
				}
			}else{
				return false;
			}
			return $ret;
		}

		public function getPatientsFeesLaboratory($company_id,$health_facility_id,$user_type){
	    	//get all codes of this company id
	    	$ret = array();
	    	$codes = array();
	    	$query = $this->db->get_where("verification_codes_records",array('company_id' => $company_id,'health_facility_id' => $health_facility_id,'taken' => 1));
	    	if($query->num_rows() > 0){
	    		foreach($query->result() as $row){
	    			$code = $row->code;
	    			$codes[] = $code;
	    		}
	    	}


	    	if(count($codes) > 0){
	    		for($i = 0; $i < count($codes); $i++){
	    			$code = $codes[$i];

		    		$query = $this->db->get_where('lab_facility_initiations',array('health_facility_id' => $health_facility_id,'insurance_code' => $code,'paid' => 1,'user_type' => $user_type));
		    		if($query->num_rows() > 0){
		    			$initiation_code_arr = array();
		    			foreach($query->result() as $row){
		    				$initiation_code = $row->initiation_code;
		    				$initiation_code_arr[] = $initiation_code;
		    			}
		    			$initiation_code_arr = array_values(array_unique($initiation_code_arr));
		    			$ret = array_merge($ret,$initiation_code_arr);
		    		}
		    	}
	    	}

	    	// var_dump($ret);

	    	return $ret;
	    }

		public function getNoOfTestsUnderInitiationCode($health_facility_id,$initiation_code){
	    	$query = $this->db->get_where("lab_facility_initiations",array('initiation_code' => $initiation_code,'health_facility_id' => $health_facility_id));
	    	return $query->num_rows();
	    }

	    public function getCompaniesLab($health_facility_id,$user_type){
	    	$ret = array();
			$this->db->select("*");
			$this->db->from("lab_facility_initiations");
			$this->db->where("paid",1);
			$this->db->where("user_type",$user_type);
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("referring_facility_id",0);

			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$insurance_code = $row->insurance_code;
					$company_id = $this->onehealth_model->getCompanyIdByInsuranceCode($insurance_code,$health_facility_id);

					$ret[] = $company_id;
				}
			}

			return array_values(array_unique($ret));

	    }

	    public function getNoOfPaymentsInLabUnderACompanyId($company_id,$health_facility_id,$user_type){
	    	//get all codes of this company id
	    	$num = 0;
	    	$codes = array();
	    	$query = $this->db->get_where("verification_codes_records",array('company_id' => $company_id,'health_facility_id' => $health_facility_id,'taken' => 1));
	    	if($query->num_rows() > 0){
	    		foreach($query->result() as $row){
	    			$code = $row->code;
	    			$codes[] = $code;
	    		}
	    	}


	    	if(count($codes) > 0){
	    		for($i = 0; $i < count($codes); $i++){
	    			$code = $codes[$i];

		    		$query = $this->db->get_where('lab_facility_initiations',array('health_facility_id' => $health_facility_id,'insurance_code' => $code,'paid' => 1,'user_type' => $user_type));
		    		if($query->num_rows() > 0){
		    			$initiation_code_arr = array();
		    			foreach($query->result() as $row){
		    				$initiation_code = $row->initiation_code;
		    				$initiation_code_arr[] = $initiation_code;
		    			}
		    			$initiation_code_arr = array_values(array_unique($initiation_code_arr));
		    			$num += count($initiation_code_arr);
		    		}
		    	}
	    	}

	    	return $num;
	    }

	    public function getCompanyIdByInsuranceCode($insurance_code,$health_facility_id){
	    	$query = $this->db->get_where("verification_codes_records",array('health_facility_id' => $health_facility_id,'code' => $insurance_code));
	    	if($query->num_rows() == 1){
	    		return $query->result()[0]->company_id;
	    	}else{
	    		return false;
	    	}
	    }

		public function getPatientsFeesLaboratoryFullPaying($health_facility_id,$days_num){
			$ret = array();
			$this->db->select("*");
			$this->db->from("lab_facility_initiations");
			$this->db->where("paid",1);
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("referring_facility_id",0);

			$query = $this->db->get();
			
			if($query->num_rows() > 0){
				// var_dump($query->result());
				foreach($query->result() as $row){
					
					$initiation_code = $row->initiation_code;
					$user_type = $row->user_type;
					// echo $patient_username;
					if($user_type == "fp"){
						$ret[] = $initiation_code;
					}
				}
			}else{
				return false;
			}
			return $ret;
		}

		public function getImagesUploadedForTest($health_facility_id,$initiation_code,$main_test_id){
			$query = $this->db->get_where('lab_test_results',array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'main_test_id' => $main_test_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->images;
			}else{
				return false;
			}
		}

		public function getTestRequestedByInitiationCodeAndMainTestId($health_facility_id,$initiation_code,$main_test_id){
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'main_test_id' => $main_test_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function checkIfImagesWhereUploadedForThisTest($health_facility_id,$initiation_code,$main_test_id){
			$query = $this->db->get_where('lab_test_results',array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'main_test_id' => $main_test_id));
			if($query->num_rows() == 1){
				if($query->result()[0]->images != ""){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		

		public function getTestsRequestedByInitiationCode($health_facility_id,$initiation_code){
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function get_set_of_all_tests_by_initiation_code_lab_only($health_facility_id,$initiation_code){
			
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'sub_dept_id !=' => 6));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
			
		}


		public function checkIfThisTestHasAtLeastOneLabTest($health_facility_id,$initiation_code){
			//That Is At Least One Test Is Not Radiology
			$lab_test_num = 0;

			$query = $this->db->get_where('lab_facility_initiations',array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$sub_dept_id = $row->sub_dept_id;

					if($sub_dept_id != 6){
						$lab_test_num++;
					}
				}
			}

			if($lab_test_num > 0){
				return true;
			}
		}

		public function getUserIdsOfPhlebotomistOrAdmin($health_facility_id){
			// $lab_structure = $this->getFacilityParamById("lab_structure",$health_facility_id);

			$ret_arr = array();
			$query = $this->db->get_where('personnel_officers',array('health_facility_id' => $health_facility_id,'personnel_id' => 3,'type' => 'lab_first_officer'));

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$user_id = $row->user_id;

					$ret_arr[] = $user_id;
				}
			}else{
				$query = $this->db->get_where("users",array('admin_facility_id' => $health_facility_id),1);
				if($query->num_rows() == 1){
					$ret_arr[] = $query->result()[0]->id;
				}
			}

			return $ret_arr;
		}


		public function sendConcernedPersonnelNotifsOnTestPaymentCompletedReferral($referring_facility_id,$health_facility_id,$initiation_code,$receipt_file,$amount_paid,$balance,$date,$time){

			if($this->checkIfThisTestHasAtLeastOneLabTest($referring_facility_id,$initiation_code)){
				$health_facility_name = $this->getFacilityParamById("name",$health_facility_id);
				$sender = $health_facility_name;

				$patient_user_id = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("user_id",$initiation_code,$referring_facility_id);

				$patients_title = $this->getPatientParamByUserId("title",$patient_user_id);
				$patients_first_name = $this->getPatientParamByUserId("first_name",$patient_user_id);
				$patients_last_name = $this->getPatientParamByUserId("last_name",$patient_user_id);

				$patients_full_name = $patients_title . " " . $patients_first_name . " " .$patients_last_name;
				$lab_id = $this->getPatientLabIdByInitiationCode($referring_facility_id,$initiation_code);

				$tests = $this->onehealth_model->get_set_of_all_tests_by_initiation_code_lab_only($referring_facility_id,$initiation_code);
				$i = 0;
				$title = "Referral Notification";
        		$message = "This Is To Alert You That Our Patient <em class='text-primary'>".$patients_full_name . "</em> With Lab Id <em class='text-primary'>".$lab_id."</em> Has Been Referred To Your Lab. View More Details Below.";
       	
		       	$message .= '<div class="table-div material-datatables table-responsive" style="">';
	            $message .= '<table class="table table-test table-striped table-bordered nowrap hover display" id="example" cellspacing="0" width="100%" style="width:100%" data-ini-code="<?php echo $initiation_code; ?>">';
	            $message .= '<thead>';
	            $message .= '<tr>';
	            $message .= '<th>#</th>';
	            $message .= '<th>Test Name</th>';
	            $message .= '<th>Department</th>';
	            $message .= '<th>Cost(₦)</th>';
	                   
	            $message .= '</tr>';
	            $message .= '</thead>';
	            $message .= '<tbody>';
				$total_price = 0;
				foreach($tests as $test){
					$i++;
					$id = $test->id;
					$test_name = $test->test_name;
					$test_cost = $test->price;
					$sub_dept_id = $test->sub_dept_id;
					$sub_dept_name = $this->onehealth_model->getSubDeptNameById($sub_dept_id);
					$total_price += $test_cost;

	                $message .= '<tr>';

	                $message .= '<td>'.$i.'</td>';
	               
	                $message .= '<td class="test-name">'.$test_name.'</td>';
	                $message .= '<td class="sub-dept-name">'.$sub_dept_name.'</td>';
	                $message .= '<td class="test-cost">'.$test_cost.'</td>';
	                $message .= '</tr>';
				}
				
				$message .= '</tbody>';
          		$message .= '</table>';      
        		$message .= '</div>';

        		$message .= '<h4><a href="'.base_url('assets/images/'.$receipt_file).'">Receipt File</a></h4>';

				$personnel_user_ids = $this->getUserIdsOfPhlebotomistOrAdmin($referring_facility_id);

				if(is_array($personnel_user_ids) && count($personnel_user_ids) > 0){

					for($i = 0; $i < count($personnel_user_ids); $i++){
						$personnel_user_id = $personnel_user_ids[$i];
						$personnel_user_name = $this->getUserNameById($personnel_user_id);
						
						$receiver = $personnel_user_name;
						
			    		$notif_array = array(
							'sender' => $sender,
							'receiver' => $receiver,
							'title' => $title,
							'message' => $message,
							'date_sent' => $date,
							'time_sent' => $time
						);

						
						$this->sendMessage($notif_array);
					}
				}
				
			}
			
		}

		public function getLabFacilityParamByLabIdAndReferringFacilityId($param,$lab_id,$referring_facility_id){
			$query = $this->db->get_where("lab_facility_initiations",array('lab_id' => $lab_id,'referring_facility_id' => $referring_facility_id),1);
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}else{
				return false;
			}
		}

		public function checkIfThisLabIdIsAReferralFromThisFacilityAndIsPaid($health_facility_id,$lab_id){
			$query = $this->db->get_where("lab_facility_initiations",array('referring_facility_id' => $health_facility_id,'lab_id' => $lab_id));
			if($query->num_rows() > 0){
				$referring_facility_id = $query->result()[0]->health_facility_id;
				$initiation_code = $this->getInitiationCodeByLabId($referring_facility_id,$lab_id);
				$balance = $this->onehealth_model->getTotalBalanceForTests($referring_facility_id,$initiation_code);
				if($balance == 0){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		public function calculateTurnAroundTime($first_date,$second_date){

			if($first_date != "" && $second_date != ""){
				$first_date = date("j M Y",strtotime($first_date));
				$second_date = date("j M Y",strtotime($second_date));


				$first_date = strtotime($first_date);
				$second_date = strtotime($second_date);

				$diff = abs($second_date - $first_date);  
				$years = floor($diff / (365*60*60*24));  
				$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));  

				$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24)); 

				if($days >= 0){
					return $days;
				}

			}else{
				return "";
			}
		}

		public function checkIfThisInitiationCodeIsAReferralFromThisFacilityAndIsPaid($health_facility_id,$initiation_code){
			$query = $this->db->get_where("lab_facility_initiations",array('referring_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
			if($query->num_rows() > 0){
				$referring_facility_id = $query->result()[0]->health_facility_id;
				$balance = $this->onehealth_model->getTotalBalanceForTests($referring_facility_id,$initiation_code);
				if($balance == 0){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		public function updateLabToLabCode1($form_array,$code,$health_facility_id){
			return $this->db->update("lab_to_lab_referral_codes",$form_array,array('health_facility_id' => $health_facility_id,'code' => $code));
		}

		public function createTestRecordReferral($form_array,$last_iteration = false){
			if($this->db->insert('lab_facility_initiations',$form_array)){
				if($last_iteration){
					$health_facility_id = $form_array['health_facility_id'];
					$referring_facility_id = $form_array['referring_facility_id'];
					$referring_facility_name = $this->getFacilityParamById("name",$referring_facility_id);
					$sender = $referring_facility_name;
					$patient_user_id = $form_array['user_id'];
					$patient_user_name = $this->getUserNameById($patient_user_id);
					$patients_email = $this->getUserParamById("email",$patient_user_id);
					$receiver = $patient_user_name;
					$date = $form_array['date'];
					$time = $form_array['time'];
					$initiation_code = $form_array['initiation_code'];
					$patients_phone_number = $this->getUserFullPhoneNumberById($patient_user_id);
					$patients_title = $this->getPatientParamByUserId("title",$patient_user_id);
					$patients_first_name = $this->getPatientParamByUserId("first_name",$patient_user_id);
					$patients_last_name = $this->getPatientParamByUserId("last_name",$patient_user_id);
					$tests = $this->onehealth_model->get_set_of_all_tests_by_initiation_code($health_facility_id,$initiation_code);
					$i = 0;
					$title = "Tests Selected";
	        		$message = "This Is To Alert You That The Following Test(s) Where Selected For You In ".$referring_facility_name." On " . $date . " At " .$time . " With Initiation Code " . $initiation_code;
	       	
			       	$message .= '<div class="table-div material-datatables table-responsive" style="">';
		            $message .= '<table class="table table-test table-striped table-bordered nowrap hover display" id="example" cellspacing="0" width="100%" style="width:100%" data-ini-code="<?php echo $initiation_code; ?>">';
		            $message .= '<thead>';
		            $message .= '<tr>';
		            $message .= '<th>#</th>';
		            $message .= '<th>Test Name</th>';
		            $message .= '<th>Department</th>';
		            $message .= '<th>Cost(₦)</th>';
		                   
		            $message .= '</tr>';
		            $message .= '</thead>';
		            $message .= '<tbody>';
					$total_price = 0;
					foreach($tests as $test){
						$i++;
						$id = $test->id;
						$test_name = $test->test_name;
						$test_cost = $test->price;
						$sub_dept_id = $test->sub_dept_id;
						$sub_dept_name = $this->onehealth_model->getSubDeptNameById($sub_dept_id);
						$total_price += $test_cost;

		                $message .= '<tr>';

		                $message .= '<td>'.$i.'</td>';
		               
		                $message .= '<td class="test-name">'.$test_name.'</td>';
		                $message .= '<td class="sub-dept-name">'.$sub_dept_name.'</td>';
		                $message .= '<td class="test-cost">'.$test_cost.'</td>';
		                $message .= '</tr>';
					}
					
					$message .= '</tbody>';
	          		$message .= '</table>';      
	        		$message .= '</div>';

	        		$notif_array = array(
	    				'sender' => $sender,
	    				'receiver' => $receiver,
	    				'title' => $title,
	    				'message' => $message,
	    				'date_sent' => $date,
	    				'time_sent' => $time
	    			);

	    			if($patients_email != ""){
	    				$recepient_arr = array($patients_email);
	    				$this->sendEmail($recepient_arr,$title,$message);
	    			}
	    			$this->sendMessage($notif_array);
	    			$patients_full_name = $patients_title . " " . $patients_first_name . " " . $patients_last_name;
	    			$from = $referring_facility_name;
					$to = array($patients_phone_number);
					$body = $patients_full_name . " This Is To Alert You That " . $i . " Test(s) Have Been Selected For You At " . $time . " With Initiation Code: ".$initiation_code.". Login To Your Account At " . site_url() . " To View Your Tests.";
					// $body = "Test message";
					$this->sendFacilitySms($referring_facility_id,$from,$to,$body);
				}
												       
			}
		}

		public function sendPatientNotifsOnTestPaymentCompletedReferral($referring_facility_id,$health_facility_id,$initiation_code,$receipt_file,$amount_paid,$balance,$date,$time){

			$health_facility_name = $this->getFacilityParamById("name",$health_facility_id);
			$sender = $health_facility_name;
			$patient_user_id = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("user_id",$initiation_code,$referring_facility_id);
			$patient_user_name = $this->getUserNameById($patient_user_id);
			$patients_email = $this->getUserParamById("email",$patient_user_id);
			$receiver = $patient_user_name;
			
			
			$patients_phone_number = $this->getUserFullPhoneNumberById($patient_user_id);
			$patients_title = $this->getPatientParamByUserId("title",$patient_user_id);
			$patients_first_name = $this->getPatientParamByUserId("first_name",$patient_user_id);
			$patients_last_name = $this->getPatientParamByUserId("last_name",$patient_user_id);

			$tests_selected_date = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("date",$initiation_code,$referring_facility_id);
			$tests_selected_time = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("time",$initiation_code,$referring_facility_id);
			
			$i = 0;
			$title = "Payment For Tests Accepted";
    		$message = "This Is To Alert You That The Payment For Your Test(s) Selected In " . $health_facility_name ." On " .$tests_selected_date . " ". $tests_selected_time ." Has Been Processed. View Your Receipt <a target='_blank' href='".base_url('assets/images/'.$receipt_file)."'>Here</a> For More Information"; 	

    		$notif_array = array(
				'sender' => $sender,
				'receiver' => $receiver,
				'title' => $title,
				'message' => $message,
				'date_sent' => $date,
				'time_sent' => $time
			);

			if($patients_email != ""){
				$recepient_arr = array($patients_email);
				$this->sendEmail($recepient_arr,$title,$message);
			}
			$this->sendMessage($notif_array);
			$patients_full_name = $patients_title . " " . $patients_first_name . " " . $patients_last_name;
			$from = $health_facility_name;
			$to = array($patients_phone_number);
			$body = $patients_full_name . "This Is To Alert You That The Payment For Your Test(s) Selected On " .$tests_selected_date . " ". $tests_selected_time ." Has Been Processed. View Your Receipt <a target='_blank' href='".base_url('assets/images/'.$receipt_file)."'>Here</a>";
			// $body = "Test message";
			$this->sendFacilitySms($health_facility_id,$from,$to,$body);
			
		}

		public function checkIfLabToLabReferralCodeIsValid($referring_facility_id,$referral_code,$first_name,$last_name){
			$query = $this->db->get_where("lab_to_lab_referral_codes",array('taken' => 0,'code' => $referral_code,'health_facility_id' => $referring_facility_id),1);

			if($query->num_rows() == 1){

				$name_code_authentication = $query->result()[0]->name_code_authentication;
				$code_first_name = $query->result()[0]->firstname;
				$code_last_name = $query->result()[0]->lastname;

				if($name_code_authentication == 1){
					if(($first_name == $code_first_name) && ($last_name == $code_last_name)){
						return true;
					}else{
						return false;
					}
				}else{
					return true;
				}
			}else{
				return false;
			}
		}

		public function checkIfThisInitiationCodeIsAReferralFromThisFacilityAndIsUnpaid($health_facility_id,$initiation_code){
			$query = $this->db->get_where("lab_facility_initiations",array('referring_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
			if($query->num_rows() > 0){
				$referring_facility_id = $query->result()[0]->health_facility_id;
				$balance = $this->onehealth_model->getTotalBalanceForTests($referring_facility_id,$initiation_code);
				if($balance > 0){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		public function getPatientsTestsByInitiationCodeReferral($health_facility_id,$initiation_code){
			$query = $this->db->get_where("lab_facility_initiations",array('referring_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function updateLabToLabCode($form_array,$id){
			return $this->db->update("lab_to_lab_referral_codes",$form_array,array('id' => $id));
		}


		public function viewUnUsedLabToLabReferralCodesdById($health_facility_id,$id){
			$query = $this->db->get_where("lab_to_lab_referral_codes",array('health_facility_id' => $health_facility_id,'taken' => 0,'id' => $id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function viewUnUsedCodesLabToLabReferrrals($health_facility_id){
			$query = $this->db->get_where("lab_to_lab_referral_codes",array('health_facility_id' => $health_facility_id,'taken' => 0));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function viewUsedLabToLabCodes($health_facility_id){
			$query = $this->db->get_where("lab_to_lab_referral_codes",array('health_facility_id' => $health_facility_id,'taken' => 1));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function checkIfThisLabToLabCodeHasBeenUsedBefore($health_facility_id,$value){
			$query = $this->db->get_where("lab_to_lab_referral_codes",array('health_facility_id' => $health_facility_id,'code' => $value));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}


		public function saveLabToLabCode($form_array){
			return $this->db->insert("lab_to_lab_referral_codes",$form_array);
		}


		public function getPatientFullNameByInitiationCodeAndReferringFacilityId($referring_facility_id,$initiation_code){
	    	$query = $this->db->get_where("lab_facility_initiations",array('referring_facility_id' => $referring_facility_id,'initiation_code' => $initiation_code),1);
	    	if($query->num_rows() == 1){
	    		$patient_user_id = $query->result()[0]->user_id;
	    		$patients_title = $this->getPatientParamByUserId("title",$patient_user_id);
				$patients_first_name = $this->getPatientParamByUserId("first_name",$patient_user_id);
				$patients_last_name = $this->getPatientParamByUserId("last_name",$patient_user_id);

				$patients_full_name = $patients_title . " " . $patients_first_name . " " . $patients_last_name;
				return $patients_full_name;
	    	}else{
	    		return false;
	    	}
	    }


		public function getLabFacilityParamByInitiationCodeAndReferringFacilityId($param,$initiation_code,$referring_facility_id){
			$query = $this->db->get_where("lab_facility_initiations",array('initiation_code' => $initiation_code,'referring_facility_id' => $referring_facility_id),1);
			if($query->num_rows() == 1){
				
				return $query->result()[0]->$param;
				
			}else{
				return false;
			}
		}

		public function getInitiationCodesForAllUnPaidReferralTests($health_facility_id){
			$ret_arr = array();
			
			$this->db->select("initiation_code,health_facility_id");
			$this->db->from("lab_facility_initiations");
			$this->db->where("referring_facility_id",$health_facility_id);
			$this->db->order_by("id","DESC");

			$query = $this->db->get();


			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					$referring_facility_id = $row->health_facility_id;

					$balance = $this->getTotalBalanceForTests($referring_facility_id,$initiation_code);

					if($balance > 0){
						$ret_arr[] = $initiation_code;
					}
				}
			}

			return array_values(array_unique($ret_arr));
		}

		public function getPatientFacilityIdByUserIdAndHealthFacilityId($patient_user_id,$health_facility_id){
			$query = $this->db->get_where("patients_in_facility",array('user_id' => $patient_user_id,'health_facility_id' => $health_facility_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->id;
			}else{
				return false;
			}
		}

		public function registerPatientReferral($form_array){
			if($this->db->insert("patients_in_facility",$form_array)){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfFacilityIdIsValid($health_facility_id){
			$query = $this->db->get_where('health_facility',array('id' => $health_facility_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getAllHospitalsAndLabsNotThisOne($health_facility_id){
			$query_str = "SELECT * FROM health_facility WHERE id != " . $health_facility_id . " AND (facility_structure = 'hospital' OR facility_structure = 'laboratory')";
			$query = $this->db->query($query_str);
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getCurrentLabToLabDiscountForFacility($health_facility_id){
			$query = $this->db->get_where("health_facility",array('id' => $health_facility_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->lab_to_lab_discount;
			}
		}

		public function addReferenceCodeAsUsed($reference_code_arr){
			return $this->db->insert("reference_codes",$reference_code_arr);
		}

		public function generateReferenceCodeForOnlinePayment(){
			while (true) {
				$reference_code = substr(bin2hex($this->encryption->create_key(8)),4);
				if(!$this->onehealth_model->checkIfThisReferenceCodeHasBeenUsedBefore($reference_code)){
					return $reference_code;
				}
			}
		}

		public function checkIfThisReferenceCodeHasBeenUsedBefore($reference_code){
			$query = $this->db->get_where("reference_codes",array('reference_code' => $reference_code));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfThisInitiationCodeIsValid($health_facility_id,$initiation_code){
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfThisLabIdIsValid($health_facility_id,$lab_id){
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getAllInitiationCodesForPatientInFacility($health_facility_id,$user_id){
			$this->db->select("initiation_code");
			$this->db->from("lab_facility_initiations");
			$this->db->where('user_id',$user_id);
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->order_by('id','DESC');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function checkIfThisPatientIsRegisteredInThisFacility1($health_facility_id,$patient_user_id){
			$query = $this->db->get_where("patients_in_facility",array('health_facility_id' => $health_facility_id,'user_id' => $patient_user_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}


		public function checkIfUsernameIsValid($user_name){
			$query = $this->db->get_where("users",array('user_name' => $user_name));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getPatientFacilityIdByUserIdAndFacilityId($health_facility_id,$user_id){
			$query = $this->db->get_where("patients_in_facility",array('health_facility_id' => $health_facility_id,'user_id' => $user_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->id;
			}else{
				return false;
			}
		}

		public function getNumberOfRegisteredFacilityPatients($health_facility_id){
			$query = $this->db->get_where("patients_in_facility",array('health_facility_id' => $health_facility_id));
			
			return $query->num_rows();
		}

		public function checkIfPatientHasHisDetailsComplete($user_id){
			$is_patient = $this->getUserParamById("is_patient",$user_id);
			if($is_patient == 1){
				$title = $this->getPatientParamByUserId("title",$user_id);
				$first_name = $this->getPatientParamByUserId("first_name",$user_id);
				$last_name = $this->getPatientParamByUserId("last_name",$user_id);
				$dob = $this->getPatientParamByUserId("dob",$user_id);
				$sex = $this->getPatientParamByUserId("sex",$user_id);
				$race = $this->getPatientParamByUserId("race",$user_id);
				$address = $this->getPatientParamByUserId("address",$user_id);

				if($title != "" && $first_name != "" && $last_name != "" && $dob != "" && $sex != "" && $race != "" && $address != ""){
					return true;
				}else{
					return false;
				}
			}
		}

		public function updateVerificationCodeRecord($form_array,$health_facility_id,$user_type,$code){
			return $this->db->update("verification_codes_records",$form_array,array('health_facility_id' => $health_facility_id,'type' => $user_type,'code' => $code));
		}

		public function updatePatientFacilityDetailsByUserId($form_array,$health_facility_id,$user_id){
			return $this->db->update("patients_in_facility",$form_array,array('health_facility_id' => $health_facility_id,'user_id' => $user_id));
		}

		public function checkIfThisInsuranceCodeIsValid($health_facility_id,$user_type,$code,$user_id){
			$patients_first_name = $this->getPatientParamByUserId("first_name",$user_id);
			$patients_last_name = $this->getPatientParamByUserId("last_name",$user_id);
			$query = $this->db->get_where('verification_codes_records',array('type' => $user_type,'code' => $code,'health_facility_id' => $health_facility_id,'taken' => 0),1);
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$firstname = $row->firstname;
					$lastname = $row->lastname;
					$name_code_authentication = $row->name_code_authentication;

					if($name_code_authentication == 1){
						if(($firstname == $patients_first_name) && ($lastname == $patients_last_name)){
							return true;
						}else{
							return false;
						}
					}else{
						return true;
					}
				}
			}else{
				return false;
			}
		}

		public function registerPatient($form_array,$health_facility_id,$patient_user_id){
			if($this->db->insert("patients_in_facility",$form_array)){
				$health_facility_name = $this->onehealth_model->getFacilityParamById("name",$health_facility_id);
				$health_facility_slug = $this->onehealth_model->getFacilityParamById("slug",$health_facility_id);
				$sender = $health_facility_name;
				
				$patient_user_name = $this->getUserNameById($patient_user_id);
				$patients_email = $this->getUserParamById("email",$patient_user_id);
				$receiver = $patient_user_name;
				
				
				$patients_phone_number = $this->getUserFullPhoneNumberById($patient_user_id);
				$patients_title = $this->getPatientParamByUserId("title",$patient_user_id);
				$patients_first_name = $this->getPatientParamByUserId("first_name",$patient_user_id);
				$patients_last_name = $this->getPatientParamByUserId("last_name",$patient_user_id);

				$patients_full_name = $patients_title . " " . $patients_first_name . " " . $patients_last_name;

				$title = "Successful Registration"; 
	    		$message = $patients_full_name . " Welcome To " . $health_facility_name . ". You Can Access This Facility's Services At The Facility Page <a href='".site_url('onehealth/'.$health_facility_slug)."'>Here</a>.";
	   			
	   
	    		$date =	date("j M Y");
				$time = date("H:i:s");

				$notif_array = array(
					'sender' => $sender,
					'receiver' => $receiver,
					'title' => $title,
					'message' => $message,
					'date_sent' => $date,
					'time_sent' => $time
				);

				if($patients_email != ""){
					$recepient_arr = array($patients_email);
					$this->sendEmail($recepient_arr,$title,$message);
				}
				$this->sendMessage($notif_array);
				

				$from = $health_facility_name;
				$to = array($patients_phone_number);
				$body = $patients_full_name . " Welcome To " . $health_facility_name . ". You Can Access This Facility's Services At The Facility Page " . site_url('onehealth/'.$health_facility_slug) . ".";
				$this->onehealth_model->sendFacilitySms($health_facility_id,$from,$to,$body);

				return true;
			}
		}


		public function checkIfUserIsRegisteredOnThisFacility($health_facility_id,$user_id){
			$query = $this->db->get_where("patients_in_facility",array('health_facility_id' => $health_facility_id,'user_id' => $user_id));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}


		public function updatePatientInformationByUserId($form_array,$user_id){
			return $this->db->update("patients",$form_array,array('user_id' => $user_id));
		}

		public function getFacilitiesThisPatientIsAffiliatedWith($user_id){
			$is_patient = $this->getUserParamById("is_patient",$user_id);
			if($is_patient == 1){
				// $query = $this->db->get_where("patients_in_facility",array('user_id' => $user_id));

				$this->db->select("*");
				$this->db->from("patients_in_facility");
				$this->db->where("user_id",$user_id);
				$this->db->order_by("id","DESC");

				$query = $this->db->get();
				if($query->num_rows() > 0){
					return $query->result();
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		public function getPatientBioDataParamByUserId($param,$patients_user_id){
			$query = $this->db->get_where("patients",array('user_id' => $patients_user_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}
		}

		public function getImagesUploadedForThisTestResult($health_facility_id,$lab_id,$main_test_id){
			$query = $this->db->get_where('lab_test_results',array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id,'main_test_id' => $main_test_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->images;
			}
		}

		
		public function getLabTestResultParam($param,$health_facility_id,$lab_id,$main_test_id){
			$query = $this->db->get_where('lab_test_results',array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id,'main_test_id' => $main_test_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}
		}
		

		public function getAllTestsToPrintByLabId($health_facility_test_table_name,$health_facility_id,$lab_id,$selected){
			$ready_main_test_ids = $this->getAllReadyTestsMainTestIds($health_facility_id,$lab_id);
			if(is_array($ready_main_test_ids) && count($ready_main_test_ids) > 0){
				if(is_array($selected)){
					$ready_main_test_ids = array_intersect($ready_main_test_ids,$selected);
				}

				$ready_main_test_ids = array_values($ready_main_test_ids);
				$tests_arr = array();
				$j = 0;
				for($i = 0; $i < count($ready_main_test_ids); $i++){
					
					$main_test_id = $ready_main_test_ids[$i];

					$query = $this->db->get_where('lab_facility_initiations',array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id,'main_test_id' => $main_test_id));
			
					if($query->num_rows() > 0){
						
						foreach($query->result() as $row){
							$main_test_id = $row->main_test_id;
							$sub_dept_id = $row->sub_dept_id;
							$lab_structure = $row->lab_structure;
							$radiology_complete = $row->radiology_complete;
							$test_completed = $row->test_completed;
							$comments = $row->comments;
							$test_name = $row->test_name;
							$radiology_comments = $row->radiology_comments;
							$radiographer = $row->radiographer;
							$radiologist = $row->radiologist;
							$cardiologist = $row->cardiologist;
							$radiographer_complete = $row->radiographer_complete;
							$phlebotomist = $row->phlebotomists_id;
							$lab_two = $row->lab_two;
							$supervisor = $row->supervisor;
							$pathologist = $row->pathologist;
							$test_id = $row->test_id;

							$methodology = $this->onehealth_model->getLabTestResultParam("methodology",$health_facility_id,$lab_id,$main_test_id);

							$about_test = $this->getTestParamById("about_test",$health_facility_test_table_name,$main_test_id);

							if($about_test != ""){
								$about_test = json_encode($about_test);
							}

							if($sub_dept_id == 6){
								$j++;
								$images = $this->onehealth_model->getImagesUploadedForThisTestResult($health_facility_id,$lab_id,$main_test_id);
								$test_id = strtolower($test_id);
								// echo $test_id . "<br>";

								$substr_name = substr($test_id,0,2);
								// echo $substr_name;
								if($substr_name == "us"){
									$sonologist = $radiographer;
									$radiographer = 0;
								}else{
									$sonologist = 0;
									
								}

								
								$array = array(
									"i" => $j,
									'methodology' => '',
									'about_test' => $about_test,
									"testname" => $test_name,
									"testresult" => "",
									"range" => "",
									"flag" => "",
									"images" => $images,
									'has_sub_test' => 0,
									'last_sub_test' => 0,
				    				'sub_test' => 0,
				    				'main_test' => 1,
				    				'comments' => '',
				    				'lab_structure' => $lab_structure,
				    				'sub_dept_id' => $sub_dept_id,
				    				'radiology_comments' => json_encode($radiology_comments),
				    				'radiographer' => $radiographer,
				    				'sonologist' => $sonologist,
				    				'radiologist' => $radiologist,
				    				'cardiologist' => $cardiologist
								);

								$tests_arr[] = $array;
								
							}else{
								if($this->checkIfThisTestHasSubTestResultEntered($health_facility_id,$lab_id,$main_test_id)){
									if($lab_structure == "mini"){
										$j++;
										$array = array(
											"i" => $j,
											'methodology' => $methodology,
											'about_test' => $about_test,
											"testname" => $test_name,
											"testresult" => "",
											"range" => "",
											"flag" => "H",
											"images" =>"",
											'has_sub_test' => 1,
											'last_sub_test' => 0,
						    				'sub_test' => 0,
						    				'main_test' => 1,
						    				'comments' => '',
						    				'lab_structure' => $lab_structure,
						    				'sub_dept_id' => $sub_dept_id,
						    				'phlebotomist' => $phlebotomist
										);

										$tests_arr[] = $array;

										$sub_test_ids = $this->getWhichIdsAreSubTestIdsOfThisTest($health_facility_id,$lab_id,$main_test_id,$ready_main_test_ids);
										if(is_array($sub_test_ids) && count($sub_test_ids) > 0){

											$sub_tests_results = $this->onehealth_model->getResultsOfSubTestsResults($health_facility_id,$lab_id,$main_test_id);
											// var_dump($sub_tests_results);

											$last_result_value = max($sub_test_ids);
											// echo $last_result_value;

											if(is_array($sub_tests_results)){
												foreach($sub_tests_results as $row){
													$sub_test_main_test_id = $row->main_test_id;
													$sub_test_name = $row->test_name;
													$test_result = $row->test_result;
													$unit_enabled = $row->unit_enabled;
													$range_enabled = $row->range_enabled;
													$desirable_value = $row->desirable_value;
													$range_higher = $row->range_higher;
													$range_lower = $row->range_lower;
													$range_type = $row->range_type;
													$unit = $row->unit;
													$images = $row->images;
													$comments = $row->comments;
													$personnel_id = $row->personnel_id;

													$methodology_sub_test = $this->onehealth_model->getLabTestResultParam("methodology",$health_facility_id,$lab_id,$sub_test_main_test_id);


													$flag = "";
													if($range_enabled == 1){
														if($range_type == "interval"){
															$flag = $this->getResultFlag($range_higher,$range_lower,$test_result);
														}else{
															$flag = $this->getResultFlag1($desirable_value,$test_result);
														}
													}

													if($unit_enabled == 1 && $range_enabled == 1){
														
														$test_result = $test_result . " " . $unit;
													}

													if($range_enabled == 1){
														if($range_type == "interval"){
															$range_str = $range_lower . " - " . $range_higher;
														}else{
															$range_str = $desirable_value;
														}

														if($unit_enabled == 1){
															$range_str .= " " . $unit;
														}
													}else{
														$range_str = "";
													}



													if(in_array($sub_test_main_test_id,$ready_main_test_ids)){

														$sub_test_arr = array(
															"i" => '',
															'methodology' => $methodology_sub_test,
															'super_main_test_id' => $main_test_id,
															'about_test' => $about_test,
															"testname" => $sub_test_name,
															"testresult" => $test_result,
															"range" => $range_str,
															"flag" => $flag,
															"images" => $images,
															'comments' => $comments,
															'has_sub_test' => 0,
															'last_sub_test' => 0,
										    				'sub_test' => 1,
										    				'main_test' => 0,
										    				'lab_structure' => $lab_structure,
										    				'sub_dept_id' => $sub_dept_id,
										    				'phlebotomist' => $phlebotomist,
										    				'laboratory_officer_2' => $personnel_id
														);

														if($sub_test_main_test_id == $last_result_value){
															$sub_test_arr['last_sub_test'] = 1;
														}

														$tests_arr[] = $sub_test_arr;
													}
												}
											}	
										}
										
									}else if($lab_structure == "maximum" || $lab_structure == "standard"){
										$j++;
										$array = array(
											"i" => $j,
											'methodology' => $methodology,
											'about_test' => $about_test,
											"testname" => $test_name,
											"testresult" => "",
											"range" => "",
											"flag" => "",
											"images" =>"",
											'has_sub_test' => 1,
											'last_sub_test' => 0,
						    				'sub_test' => 0,
						    				'main_test' => 1,
						    				'lab_structure' => $lab_structure,
						    				'sub_dept_id' => $sub_dept_id,
						    				'comments' => $comments,
						    				'phlebotomist' => $phlebotomist,
						    				'laboratory_officer_2' => $lab_two,
						    				'laboratory_supervisor' => $supervisor,
						    				'pathologist' => $pathologist
										);

										$tests_arr[] = $array;

										$sub_test_ids = $this->getWhichIdsAreSubTestIdsOfThisTest($health_facility_id,$lab_id,$main_test_id,$ready_main_test_ids);
										if(is_array($sub_test_ids) && count($sub_test_ids) > 0){

											$sub_tests_results = $this->onehealth_model->getResultsOfSubTestsResults($health_facility_id,$lab_id,$main_test_id);

											$last_result_value = max($sub_test_ids);

											if(is_array($sub_tests_results)){
												foreach($sub_tests_results as $row){

													$sub_test_main_test_id = $row->main_test_id;
													$sub_test_name = $row->test_name;
													$test_result = $row->test_result;
													$unit_enabled = $row->unit_enabled;
													$range_enabled = $row->range_enabled;
													$desirable_value = $row->desirable_value;
													$range_higher = $row->range_higher;
													$range_lower = $row->range_lower;
													$range_type = $row->range_type;
													$unit = $row->unit;
													$images = $row->images;
													// $comments = $row->comments;
													$personnel_id = $row->personnel_id;

													$methodology_sub_test = $this->onehealth_model->getLabTestResultParam("methodology",$health_facility_id,$lab_id,$sub_test_main_test_id);

													$flag = "";
													if($range_enabled == 1){
														if($range_type == "interval"){
															$flag = $this->getResultFlag($range_higher,$range_lower,$test_result);
														}else{
															$flag = $this->getResultFlag1($desirable_value,$test_result);
														}
													}

													if($unit_enabled == 1 && $range_enabled == 1){
														
														$test_result = $test_result . " " . $unit;
													}

													if($range_enabled == 1){
														if($range_type == "interval"){
															$range_str = $range_lower . " - " . $range_higher;
														}else{
															$range_str = $desirable_value;
														}

														if($unit_enabled == 1){
															$range_str .= " " . $unit;
														}
													}else{
														$range_str = "";
													}



													if(in_array($sub_test_main_test_id,$ready_main_test_ids)){

														$sub_test_arr = array(
															"i" => '',
															'methodology' => $methodology_sub_test,
															'super_main_test_id' => $main_test_id,
															'about_test' => $about_test,
															"testname" => $sub_test_name,
															"testresult" => $test_result,
															"range" => $range_str,
															"flag" => $flag,
															"images" => $images,
															'comments' => $comments,
															'has_sub_test' => 0,
															'last_sub_test' => 0,
										    				'sub_test' => 1,
										    				'main_test' => 0,
										    				'lab_structure' => $lab_structure,
										    				'sub_dept_id' => $sub_dept_id,
										    				'phlebotomist' => $phlebotomist,
										    				'laboratory_officer_2' => $personnel_id,
										    				'laboratory_supervisor' => $supervisor,
							    							'pathologist' => $pathologist
														);

														if($sub_test_main_test_id == $last_result_value){
															$sub_test_arr['last_sub_test'] = 1;
														}

														$tests_arr[] = $sub_test_arr;
													}
												}
											}	
										}
									}
								}else{
									$images = $this->onehealth_model->getImagesUploadedForThisTestResult($health_facility_id,$lab_id,$main_test_id);
									$test_result = $this->onehealth_model->getLabTestResultParam("test_result",$health_facility_id,$lab_id,$main_test_id);
									$range_enabled = $this->onehealth_model->getLabTestResultParam("range_enabled",$health_facility_id,$lab_id,$main_test_id);
									$range_type = $this->onehealth_model->getLabTestResultParam("range_type",$health_facility_id,$lab_id,$main_test_id);
									$range_higher = $this->onehealth_model->getLabTestResultParam("range_higher",$health_facility_id,$lab_id,$main_test_id);

									$range_lower = $this->onehealth_model->getLabTestResultParam("range_lower",$health_facility_id,$lab_id,$main_test_id);

									$desirable_value = $this->onehealth_model->getLabTestResultParam("desirable_value",$health_facility_id,$lab_id,$main_test_id);

									$unit_enabled = $this->onehealth_model->getLabTestResultParam("unit_enabled",$health_facility_id,$lab_id,$main_test_id);
									$unit = $this->onehealth_model->getLabTestResultParam("unit",$health_facility_id,$lab_id,$main_test_id);

									$mini_comments = $this->onehealth_model->getLabTestResultParam("comments",$health_facility_id,$lab_id,$main_test_id);

									$personnel_id = $this->onehealth_model->getLabTestResultParam("personnel_id",$health_facility_id,$lab_id,$main_test_id);

									$flag = "";
									if($range_enabled == 1){
										if($range_type == "interval"){
											$flag = $this->getResultFlag($range_higher,$range_lower,$test_result);
										}else{
											$flag = $this->getResultFlag1($desirable_value,$test_result);
										}
									}

									if($unit_enabled == 1 && $range_enabled == 1){
										
										$test_result = $test_result . " " . $unit;
									}

									if($range_enabled == 1){
										if($range_type == "interval"){
											$range_str = $range_lower . " - " . $range_higher;
										}else{
											$range_str = $desirable_value;
										}

										if($unit_enabled == 1){
											$range_str .= " " . $unit;
										}
									}else{
										$range_str = "";
									}


									if($lab_structure == "mini"){
										$j++;
										$array = array(
											"i" => $j,
											'methodology' => $methodology,
											'about_test' => $about_test,
											"testname" => $test_name,
											"testresult" => $test_result,
											"range" => $range_str,
											"flag" => $flag,
											"images" => $images,
											'has_sub_test' => 0,
											'last_sub_test' => 0,
						    				'sub_test' => 0,
						    				'main_test' => 1,
						    				'lab_structure' => $lab_structure,
						    				'sub_dept_id' => $sub_dept_id,
						    				'comments' => $mini_comments,
						    				'phlebotomist' => $phlebotomist,
						    				'laboratory_officer_2' => $personnel_id
										);

										$tests_arr[] = $array;
									}elseif($lab_structure == "standard" || $lab_structure == "maximum"){
										
										$j++;
										$array = array(
											"i" => $j,
											'methodology' => $methodology,
											'about_test' => $about_test,
											"testname" => $test_name,
											"testresult" => $test_result,
											"range" => $range_str,
											"flag" => $flag,
											"images" => $images,
											'has_sub_test' => 0,
											'last_sub_test' => 0,
						    				'sub_test' => 0,
						    				'main_test' => 1,
						    				'lab_structure' => $lab_structure,
						    				'sub_dept_id' => $sub_dept_id,
						    				'comments' => $comments,
						    				'phlebotomist' => $phlebotomist,
						    				'laboratory_officer_2' => $personnel_id,
						    				'laboratory_supervisor' => $supervisor,
						    				'pathologist' => $pathologist
										);
										$tests_arr[] = $array;

										// var_dump($tests_arr);
									}
								}
							}
						}
					}	

				}
				// var_dump($tests_arr);
				return $tests_arr;
			}
		}

		public function getWhichIdsAreSubTestIdsOfThisTest($health_facility_id,$lab_id,$main_test_id,$ready_main_test_ids){
			$ret_arr = array();
			$sub_tests = $this->getSubTestsIdsOfThisResultMainTestCommaSeperated($health_facility_id,$lab_id,$main_test_id);
			if($sub_tests != false){
				$sub_test_arr = explode(",",$sub_tests);

				if(is_array($ready_main_test_ids)){
					for($i = 0; $i < count($ready_main_test_ids); $i++){
						$sub_test_id = $ready_main_test_ids[$i];
						if(in_array($sub_test_id, $sub_test_arr)){
							$ret_arr[] = $sub_test_id;
						}
					}
				}
			}
			return array_values(array_unique($ret_arr));
		}

		public function getAllReadyTestsMainTestIds($health_facility_id,$lab_id){
			$main_test_ids = array();
			$health_facility_name  = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			// $tests_num = $this->getTotalNumberOfTestsDoneWithThisLabId($health_facility_id,$lab_id);

			$query = $this->db->get_where('lab_facility_initiations',array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id));
			
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$main_test_id = $row->main_test_id;
					$sub_dept_id = $row->sub_dept_id;
					$lab_structure = $row->lab_structure;
					$radiology_complete = $row->radiology_complete;
					$test_completed = $row->test_completed;
					$comments = $row->comments;

					
					if($sub_dept_id == 6){
						if($radiology_complete == 1){
							$main_test_ids[] = $main_test_id;
						}
					}else{
						if($this->checkIfThisTestHasSubTestResultEntered($health_facility_id,$lab_id,$main_test_id)){
							if($lab_structure == "mini"){
								if($test_completed == 1){
									$main_test_ids[] = $main_test_id;
									$sub_tests = $this->getSubTestsIdsOfThisResultMainTestCommaSeperated($health_facility_id,$lab_id,$main_test_id);
									if($sub_tests != false){
										$sub_tests = explode(",",$sub_tests);
										if(is_array($sub_tests) && count($sub_tests) > 0){
											for($j = 0; $j < count($sub_tests); $j++){
												$sub_test_main_test_id = $sub_tests[$j];
												$main_test_ids[] = $sub_test_main_test_id;
											}
										}
									}		
								}
							}else if($lab_structure == "standard" || $lab_structure == "maximum"){
								if($comments != ""){
									$main_test_ids[] = $main_test_id;
									$sub_tests = $this->getSubTestsIdsOfThisResultMainTestCommaSeperated($health_facility_id,$lab_id,$main_test_id);
									if($sub_tests != false){
										$sub_tests = explode(",",$sub_tests);
										if(is_array($sub_tests) && count($sub_tests) > 0){
											for($j = 0; $j < count($sub_tests); $j++){
												$sub_test_main_test_id = $sub_tests[$j];
												$main_test_ids[] = $sub_test_main_test_id;
											}
										}
									}	
								}
							}
						}else{
							if($lab_structure == "mini"){
								if($test_completed == 1){
									$main_test_ids[] = $main_test_id;
								}
							}else if($lab_structure == "standard" || $lab_structure == "maximum"){
								if($comments != ""){
									$main_test_ids[] = $main_test_id;
								}
							}
						}
					}
				}
			}

			return $main_test_ids;

		}

		public function getPersonnelSignature($user_id){
			if($this->getUserParamById("is_patient",$user_id) == 0){
				$signature = $this->getUserParamById("signature",$user_id);
				return $signature;
			}else{
				return "";
			}
		}

		public function getPersonnelQualification($user_id){
			if($this->getUserParamById("is_patient",$user_id) == 0){
				$qualification = $this->getUserParamById("qualification",$user_id);
				
				return $qualification;
			}else{
				return "";
			}
		}

		public function getPersonnelFullName($user_id){
			if($this->getUserParamById("is_patient",$user_id) == 0){
				$title = $this->getUserParamById("title",$user_id);
				$full_name = $this->getUserParamById("full_name",$user_id);

				return $title . " " . $full_name;
			}else{
				return "";
			}
		}


		public function getSubTestsIdsOfThisResultMainTestCommaSeperated($health_facility_id,$lab_id,$main_test_id){
			$ret_arr = array();

			$query = $this->db->get_where('lab_test_results',array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id,'under' => $main_test_id,'main_test' => 0,'test_result !=' => ''));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$id = $row->id;
					$main_test_id = $row->main_test_id;
					$ret_arr[] = $main_test_id;
				}
			}else{
				return false;
			}

			return implode(",", $ret_arr);
		}

		public function getLabIdsWithAtLeastOneResultReady($health_facility_id){
			$ret_arr = array();

			$all_lab_ids = $this->getAllLabIdsForFacilityResults($health_facility_id);
			if(is_array($all_lab_ids) && count($all_lab_ids) > 0){
				for($i = 0; $i < count($all_lab_ids); $i++){
					
					$lab_id = $all_lab_ids[$i]['lab_id'];
					$main_health_facility_id = $all_lab_ids[$i]['health_facility_id'];
					$referring_facility_id = $all_lab_ids[$i]['referring_facility_id'];

					if($this->checkIfAllTestsWithAtLeastOneResultReady($main_health_facility_id,$lab_id)){
						$ret_arr[] = $all_lab_ids[$i];
					}
				}
			}

			return $ret_arr;
		}

		public function checkIfAllTestsWithAtLeastOneResultReady($health_facility_id,$lab_id){
			$health_facility_name  = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			// $tests_num = $this->getTotalNumberOfTestsDoneWithThisLabId($health_facility_id,$lab_id);

			$query = $this->db->get_where('lab_facility_initiations',array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id));
			$completed_num = 0;
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$main_test_id = $row->main_test_id;
					$sub_dept_id = $row->sub_dept_id;
					$lab_structure = $row->lab_structure;
					$radiology_complete = $row->radiology_complete;
					$test_completed = $row->test_completed;
					$comments = $row->comments;

					$proceed = false;

					
					if($sub_dept_id == 6){
						if($radiology_complete == 1){
							$proceed = true;
						}
					}else{
						if($this->checkIfThisTestHasSubTestResultEntered($health_facility_id,$lab_id,$main_test_id)){
							if($lab_structure == "mini"){
								if($test_completed == 1){
									$proceed = true;
								}
							}else if($lab_structure == "standard" || $lab_structure == "maximum"){
								if($comments != ""){
									$proceed = true;
								}
							}
						}else{
							if($lab_structure == "mini"){
								if($test_completed == 1){
									$proceed = true;
								}
							}else if($lab_structure == "standard" || $lab_structure == "maximum"){
								if($comments != ""){
									$proceed = true;
								}
							}
						}
					}



					if($proceed){
						$completed_num++;
					}
					
				}
			}

			if($completed_num > 0){
				return true;
			}else{
				return false;
			}
		}


		public function getResultsOfSubTestsResults($health_facility_id,$lab_id,$main_test_id){
			// $query = $this->db->get_where('lab_test_results',array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id,'under' => $main_test_id,'main_test' => 0));

			$this->db->select("*");
			$this->db->from("lab_test_results");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("lab_id",$lab_id);
			$this->db->where("under",$main_test_id);
			$this->db->where("main_test",0);
			$this->db->order_by("id","ASC");

			$query = $this->db->get();

			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function checkIfThisTestHasSubTestResultEntered($health_facility_id,$lab_id,$main_test_id){
			$query = $this->db->get_where('lab_test_results',array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id,'under' => $main_test_id,'main_test' => 0));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getRequestedTestsDoneLabId($health_facility_id,$lab_id){
			$query = $this->db->get_where('lab_facility_initiations',array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getLabIdsWithCompletedResults($health_facility_id){
			$ret_arr = array();

			$all_lab_ids = $this->getAllLabIdsForFacilityResults($health_facility_id);
			if(is_array($all_lab_ids) && count($all_lab_ids) > 0){
				for($i = 0; $i < count($all_lab_ids); $i++){
					$lab_id = $all_lab_ids[$i]['lab_id'];
					$main_health_facility_id = $all_lab_ids[$i]['health_facility_id'];
					$referring_facility_id = $all_lab_ids[$i]['referring_facility_id'];

					if($this->checkIfAllTestsDoneWithThisLabIdIsComplete($main_health_facility_id,$lab_id)){
						$ret_arr[] = $all_lab_ids[$i];
					}
				}
			}

			return $ret_arr;
		}

		public function checkIfAllTestsDoneWithThisLabIdIsComplete($health_facility_id,$lab_id){
			$health_facility_name  = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			$tests_num = $this->getTotalNumberOfTestsDoneWithThisLabId($health_facility_id,$lab_id);

			$query = $this->db->get_where('lab_facility_initiations',array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id));
			$completed_num = 0;
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$main_test_id = $row->main_test_id;
					$sub_dept_id = $row->sub_dept_id;
					$lab_structure = $row->lab_structure;
					$radiology_complete = $row->radiology_complete;
					$test_completed = $row->test_completed;
					$comments = $row->comments;

					$proceed = false;

					
					if($sub_dept_id == 6){
						if($radiology_complete == 1){
							$proceed = true;
						}
					}else{
						if($this->checkIfThisTestHasSubTestResultEntered($health_facility_id,$lab_id,$main_test_id)){
							if($lab_structure == "mini"){
								if($test_completed == 1){
									$proceed = true;
								}
							}else if($lab_structure == "standard" || $lab_structure == "maximum"){
								if($comments != ""){
									$proceed = true;
								}
							}
						}else{
							if($lab_structure == "mini"){
								if($test_completed == 1){
									$proceed = true;
								}
							}else if($lab_structure == "standard" || $lab_structure == "maximum"){
								if($comments != ""){
									$proceed = true;
								}
							}
						}
					}



					if($proceed){
						$completed_num++;
					}
					
				}
			}

			if($tests_num == $completed_num){
				return true;
			}else{
				return false;
			}
		}

		public function getTotalNumberOfTestsDoneWithThisLabId($health_facility_id,$lab_id){
			$query = $this->db->get_where('lab_facility_initiations',array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id));
			return $query->num_rows();
		}

		public function getAllLabIdsForFacilityResults($health_facility_id){
			$ret_arr = array();
			
			$query_str = "SELECT * FROM `lab_facility_initiations` WHERE (health_facility_id = " . $health_facility_id . " OR referring_facility_id = ".$health_facility_id.") ORDER BY `lab_id` ASC";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$lab_id = $row->lab_id;
					$health_facility_id = $row->health_facility_id;
					$referring_facility_id = $row->referring_facility_id;


					$ret_arr[] = array(
						'lab_id' => $lab_id,
						'health_facility_id' => $health_facility_id,
						'referring_facility_id' => $referring_facility_id
					);
				}
			}


			return array_values(array_unique($ret_arr,SORT_REGULAR));
		}

		public function getMainTestIdsOfSelectedTestsOfPatientRadiologistEdit($health_facility_id,$initiation_code,$sub_dept_id){
			
			$ret_arr = array();

			$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			
			$query_str = "SELECT main_test_id FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND radiographer_complete = 1 AND redirect_to = 'radiologist' AND radiology_complete = 1 AND initiation_code = '".$initiation_code."' AND `sub_dept_id` = 6 AND paid = 1 ORDER BY `lab_id` ASC";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$main_test_id = $row->main_test_id;
					$ret_arr[] = $main_test_id;
					
				}
				
			}

			return array_values(array_unique($ret_arr));
		}


		public function checkIfTheseSetOfTestsAreValidToBeWorkedOnByRadiologistEdit($health_facility_id,$initiation_code,$sub_dept_id){
			if($this->getTestsNumForPatientAwaitingRadiologistEdit($health_facility_id,$initiation_code,$sub_dept_id) > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getTestsNumForPatientAwaitingRadiologistEdit($health_facility_id,$initiation_code,$sub_dept_id){
			
			$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			
			$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND radiographer_complete = 1 AND redirect_to = 'radiologist' AND radiology_complete = 1 AND initiation_code = '".$initiation_code."' AND `sub_dept_id` = 6 AND paid = 1 ";

			$query = $this->db->query($query_str);

			return $query->num_rows();
		}

		public function getInitiationCodesAwaitingRadiologistEdit($health_facility_id,$sub_dept_id){
			
			$ret_arr = array();
			
			$query_str = "SELECT initiation_code FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id."  AND radiographer_complete = 1 AND redirect_to = 'radiologist' AND radiology_complete = 1 AND `sub_dept_id` = 6 AND paid = 1 ORDER BY `lab_id` ASC";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					
					$ret_arr[] = $initiation_code;
					
				}
				$ret_arr = array_values(array_unique($ret_arr));
			}else{
				return false;
			}

			return $ret_arr;
		}

		public function getMainTestIdsOfSelectedTestsOfPatientRadiologist($health_facility_id,$initiation_code,$sub_dept_id){
			
			$ret_arr = array();

			$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			
			$query_str = "SELECT main_test_id FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND radiographer_complete = 1 AND redirect_to = 'radiologist' AND radiology_complete = 0 AND initiation_code = '".$initiation_code."' AND `sub_dept_id` = 6 AND paid = 1 ";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$main_test_id = $row->main_test_id;
					$ret_arr[] = $main_test_id;
					
				}
				
			}

			return array_values(array_unique($ret_arr));
		}

		public function updateCommentRadiologist($comments,$health_facility_id,$main_test_id,$lab_id,$date,$time){
			
				
			$form_array = array(
				'radiology_comments' => $comments,
				'radiologist' => $this->getUserIdWhenLoggedIn(),
				'radiographer_complete_time' => $date . " " .$time
			);
			
			$form_array['radiology_complete'] = 1;
			$form_array['radiology_complete_time'] = $date . " " .$time;
			

			return $this->db->update("lab_facility_initiations",$form_array,array('health_facility_id' => $health_facility_id,'main_test_id' => $main_test_id,'lab_id' => $lab_id));
			
		}


		public function checkIfTheseSetOfTestsAreValidToBeWorkedOnByRadiologist($health_facility_id,$initiation_code,$sub_dept_id){
			if($this->getTestsNumForPatientAwaitingRadiologist($health_facility_id,$initiation_code,$sub_dept_id) > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getTestsNumForPatientAwaitingRadiologist($health_facility_id,$initiation_code,$sub_dept_id){
			
			$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			
			$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND radiographer_complete = 1 AND redirect_to = 'radiologist' AND radiology_complete = 0 AND initiation_code = '".$initiation_code."' AND `sub_dept_id` = 6 AND paid = 1 ";

			$query = $this->db->query($query_str);

			return $query->num_rows();
		}

		public function getInitiationCodesAwaitingRadiologist($health_facility_id,$sub_dept_id){
			
			$ret_arr = array();
			
			$query_str = "SELECT initiation_code FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id."  AND radiographer_complete = 1 AND redirect_to = 'radiologist' AND radiology_complete = 0 AND `sub_dept_id` = 6 AND paid = 1 ORDER BY `lab_id` ASC";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					
					$ret_arr[] = $initiation_code;
					
				}
				$ret_arr = array_values(array_unique($ret_arr));
			}else{
				return false;
			}

			return $ret_arr;
		}

		public function getMainTestIdsOfSelectedTestsOfPatientCardiologistEdit($health_facility_id,$initiation_code,$sub_dept_id){
			
			$ret_arr = array();

			$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			
			$query_str = "SELECT main_test_id FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND radiographer_complete = 1 AND redirect_to = 'cardiologist' AND radiology_complete = 1 AND initiation_code = '".$initiation_code."' AND `sub_dept_id` = 6 AND paid = 1 ORDER BY `lab_id` ASC";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$main_test_id = $row->main_test_id;
					$ret_arr[] = $main_test_id;
					
				}
				
			}

			return array_values(array_unique($ret_arr));
		}

		public function checkIfTheseSetOfTestsAreValidToBeWorkedOnByCardiologistEdit($health_facility_id,$initiation_code,$sub_dept_id){
			if($this->getTestsNumForPatientAwaitingCardiologistEdit($health_facility_id,$initiation_code,$sub_dept_id) > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getTestsNumForPatientAwaitingCardiologistEdit($health_facility_id,$initiation_code,$sub_dept_id){
			
			$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			
			$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND radiographer_complete = 1 AND redirect_to = 'cardiologist' AND radiology_complete = 1 AND initiation_code = '".$initiation_code."' AND `sub_dept_id` = 6 AND paid = 1 ";

			$query = $this->db->query($query_str);

			return $query->num_rows();
		}


		public function getInitiationCodesAwaitingCardiologistEdit($health_facility_id,$sub_dept_id){
			
			$ret_arr = array();
			
			$query_str = "SELECT initiation_code FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id."  AND radiographer_complete = 1 AND redirect_to = 'cardiologist' AND radiology_complete = 1 AND `sub_dept_id` = 6 AND paid = 1 ORDER BY `lab_id` ASC";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					
					$ret_arr[] = $initiation_code;
					
				}
				$ret_arr = array_values(array_unique($ret_arr));
			}else{
				return false;
			}

			return $ret_arr;
		}

		public function updateCommentCardiologist($comments,$health_facility_id,$main_test_id,$lab_id,$date,$time){
			
				
			$form_array = array(
				'radiology_comments' => $comments,
				'cardiologist' => $this->getUserIdWhenLoggedIn(),
				'radiographer_complete_time' => $date . " " .$time
			);
			
			$form_array['radiology_complete'] = 1;
			$form_array['radiology_complete_time'] = $date . " " .$time;
			

			return $this->db->update("lab_facility_initiations",$form_array,array('health_facility_id' => $health_facility_id,'main_test_id' => $main_test_id,'lab_id' => $lab_id));
			
		}

		public function updateCommentRadiolographerEdit($type,$comments,$health_facility_id,$main_test_id,$lab_id,$date,$time){
			$form_array = array(
				'radiology_comments' => $comments,
				'radiographer' => $this->getUserIdWhenLoggedIn(),
				'radiographer_complete' => 1,
				'radiographer_complete_time' => $date . " " .$time
			);
			
			$form_array['radiology_complete'] = 1;
			$form_array['radiology_complete_time'] = $date . " " .$time;
			

			return $this->db->update("lab_facility_initiations",$form_array,array('health_facility_id' => $health_facility_id,'main_test_id' => $main_test_id,'lab_id' => $lab_id));
			
		}

		public function getMainTestIdsOfSelectedTestsOfPatientCardiologist($health_facility_id,$initiation_code,$sub_dept_id){
			
			$ret_arr = array();

			$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			
			$query_str = "SELECT main_test_id FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND radiographer_complete = 1 AND redirect_to = 'cardiologist' AND radiology_complete = 0 AND initiation_code = '".$initiation_code."' AND `sub_dept_id` = 6 AND paid = 1 ";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$main_test_id = $row->main_test_id;
					$ret_arr[] = $main_test_id;
					
				}
				
			}

			return array_values(array_unique($ret_arr));
		}


		public function checkIfTheseSetOfTestsAreValidToBeWorkedOnByCardiologist($health_facility_id,$initiation_code,$sub_dept_id){
			if($this->getTestsNumForPatientAwaitingCardiologist($health_facility_id,$initiation_code,$sub_dept_id) > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getTestsNumForPatientAwaitingCardiologist($health_facility_id,$initiation_code,$sub_dept_id){
			
			$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			
			$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND radiographer_complete = 1 AND redirect_to = 'cardiologist' AND radiology_complete = 0 AND initiation_code = '".$initiation_code."' AND `sub_dept_id` = 6 AND paid = 1 ";

			$query = $this->db->query($query_str);

			return $query->num_rows();
		}

		public function getInitiationCodesAwaitingCardiologist($health_facility_id,$sub_dept_id){
			
			$ret_arr = array();
			
			$query_str = "SELECT initiation_code FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id."  AND radiographer_complete = 1 AND redirect_to = 'cardiologist' AND radiology_complete = 0 AND `sub_dept_id` = 6 AND paid = 1 ORDER BY `lab_id` ASC";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					
					$ret_arr[] = $initiation_code;
					
				}
				$ret_arr = array_values(array_unique($ret_arr));
			}else{
				return false;
			}

			return $ret_arr;
		}

		public function getMainTestIdsOfSelectedTestsOfPatientSonologistEdit($health_facility_id,$initiation_code,$sub_dept_id){
			
			$ret_arr = array();

			$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			
			$query_str = "SELECT main_test_id FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND radiographer_complete = 1 AND redirect_to = '' AND radiology_complete = 1  AND initiation_code = '".$initiation_code."' AND `sub_dept_id` = 6 AND paid = 1 ORDER BY `lab_id` ASC";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					
					$main_test_id = $row->main_test_id;
					$test_id = $this->getTestParamById("test_id",$health_facility_test_table_name,$main_test_id);
					// echo $test_id;
					$test_id = strtolower($test_id);

					// echo $main_test_id ."<br>";
					$substr_name = substr($test_id,0,2);
					// echo $substr_name;
					if($substr_name == "us"){
						$ret_arr[] = $main_test_id;
					}
				}
				
			}

			return array_values(array_unique($ret_arr));
		}

		public function checkIfTheseSetOfTestsAreValidToBeWorkedOnBySonologistEdit($health_facility_id,$initiation_code,$sub_dept_id){
			if($this->getTestsNumForPatientAwaitingSonologistEdit($health_facility_id,$initiation_code,$sub_dept_id) > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getTestsNumForPatientAwaitingSonologistEdit($health_facility_id,$initiation_code,$sub_dept_id){
			
			$num = 0;

			$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			
			$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND radiographer_complete = 1 AND redirect_to = '' AND radiology_complete = 1 AND initiation_code = '".$initiation_code."' AND `sub_dept_id` = 6 AND paid = 1 ";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					
					$main_test_id = $row->main_test_id;
					$test_id = $this->getTestParamById("test_id",$health_facility_test_table_name,$main_test_id);
					// echo $test_id;
					$test_id = strtolower($test_id);

					// echo $main_test_id ."<br>";
					$substr_name = substr($test_id,0,2);
					// echo $substr_name;
					if($substr_name == "us"){
						$num++;
					}
				}
				
			}

			return $num;
		}


		public function checkIfThisInitiationCodeHasSonologistTestEdit($health_facility_id,$initiation_code){
			$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'sub_dept_id' => 6, 'radiographer_complete' => 1 , 'redirect_to' => '', 'radiology_complete' => 1));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$main_test_id = $row->main_test_id;
					$test_id = $this->getTestParamById("test_id",$health_facility_test_table_name,$main_test_id);
					// echo $test_id;
					$test_id = strtolower($test_id);

					// echo $main_test_id ."<br>";
					$substr_name = substr($test_id,0,2);
					// echo $substr_name;
					if($substr_name == "us"){
						return true;
					}
				}
			}
		}

		public function getInitiationCodesAwaitingSonologistEdit($health_facility_id,$sub_dept_id){
			
			$ret_arr = array();
			
			$query_str = "SELECT initiation_code FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND radiographer_complete = 1 AND redirect_to = '' AND radiology_complete = 1  AND `sub_dept_id` = 6 AND paid = 1 ORDER BY `lab_id` ASC";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					if($this->checkIfThisInitiationCodeHasSonologistTestEdit($health_facility_id,$initiation_code)){
						$ret_arr[] = $initiation_code;
					}
				}
				$ret_arr = array_values(array_unique($ret_arr));
			}else{
				return false;
			}

			return $ret_arr;
		}

		public function getMainTestIdsOfSelectedTestsOfPatientRadiographerEdit($health_facility_id,$initiation_code,$sub_dept_id){
			
			$ret_arr = array();

			$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			
			$query_str = "SELECT main_test_id FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND radiographer_complete = 1 AND redirect_to = '' AND radiology_complete = 1 AND initiation_code = '".$initiation_code."' AND `sub_dept_id` = 6 AND paid = 1 ORDER BY `lab_id` ASC";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					
					$main_test_id = $row->main_test_id;
					$test_id = $this->getTestParamById("test_id",$health_facility_test_table_name,$main_test_id);
					// echo $test_id;
					$test_id = strtolower($test_id);

					// echo $main_test_id ."<br>";
					$substr_name = substr($test_id,0,2);
					// echo $substr_name;
					if($substr_name != "us"){
						$ret_arr[] = $main_test_id;
					}
				}
				
			}

			return array_values(array_unique($ret_arr));
		}

		public function checkIfTheseSetOfTestsAreValidToBeWorkedOnByRadiographerEdit($health_facility_id,$initiation_code,$sub_dept_id){
			if($this->getTestsNumForPatientAwaitingRadiographerEdit($health_facility_id,$initiation_code,$sub_dept_id) > 0){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfThisInitiationCodeHasRadiographerTestEdit($health_facility_id,$initiation_code){
			$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'sub_dept_id' => 6, 'radiographer_complete' => 1 , 'redirect_to' => '', 'radiology_complete' => 1));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$main_test_id = $row->main_test_id;
					$test_id = $this->getTestParamById("test_id",$health_facility_test_table_name,$main_test_id);
					// echo $test_id;
					$test_id = strtolower($test_id);

					// echo $main_test_id ."<br>";
					$substr_name = substr($test_id,0,2);
					// echo $substr_name;
					if($substr_name != "us"){
						return true;
					}
				}
			}
		}

		public function getTestsNumForPatientAwaitingRadiographerEdit($health_facility_id,$initiation_code,$sub_dept_id){
			
			$num = 0;

			$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			
			$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND radiographer_complete = 1 AND redirect_to = '' AND radiology_complete = 1 AND initiation_code = '".$initiation_code."' AND `sub_dept_id` = 6 AND paid = 1 ";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					
					$main_test_id = $row->main_test_id;
					$test_id = $this->getTestParamById("test_id",$health_facility_test_table_name,$main_test_id);
					// echo $test_id;
					$test_id = strtolower($test_id);

					// echo $main_test_id ."<br>";
					$substr_name = substr($test_id,0,2);
					// echo $substr_name;
					if($substr_name != "us"){
						$num++;
					}
				}
				
			}

			return $num;
		}

		public function getInitiationCodesAwaitingRadiographerEdit($health_facility_id,$sub_dept_id){
			
			$ret_arr = array();
			
			$query_str = "SELECT initiation_code FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND radiographer_complete = 1 AND redirect_to = '' AND radiology_complete = 1 AND `sub_dept_id` = 6 AND paid = 1 ORDER BY `lab_id` ASC";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					if($this->checkIfThisInitiationCodeHasRadiographerTestEdit($health_facility_id,$initiation_code)){
						$ret_arr[] = $initiation_code;
					}
				}
				$ret_arr = array_values(array_unique($ret_arr));
			}else{
				return false;
			}

			return $ret_arr;
		}

		public function getMainTestIdsOfSelectedTestsOfPatientSonologist($health_facility_id,$initiation_code,$sub_dept_id){
			
			$ret_arr = array();

			$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			
			$query_str = "SELECT main_test_id FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND radiographer_complete = 0 AND initiation_code = '".$initiation_code."' AND `sub_dept_id` = 6 AND paid = 1 ";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					
					$main_test_id = $row->main_test_id;
					$test_id = $this->getTestParamById("test_id",$health_facility_test_table_name,$main_test_id);
					// echo $test_id;
					$test_id = strtolower($test_id);

					// echo $main_test_id ."<br>";
					$substr_name = substr($test_id,0,2);
					// echo $substr_name;
					if($substr_name == "us"){
						$ret_arr[] = $main_test_id;
					}
				}
				
			}

			return array_values(array_unique($ret_arr));
		}

		public function checkIfTheseSetOfTestsAreValidToBeWorkedOnBySonologist($health_facility_id,$initiation_code,$sub_dept_id){
			if($this->getTestsNumForPatientAwaitingSonologist($health_facility_id,$initiation_code,$sub_dept_id) > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getTestsNumForPatientAwaitingSonologist($health_facility_id,$initiation_code,$sub_dept_id){
			
			$num = 0;

			$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			
			$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND radiographer_complete = 0 AND initiation_code = '".$initiation_code."' AND `sub_dept_id` = 6 AND paid = 1 ";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					
					$main_test_id = $row->main_test_id;
					$test_id = $this->getTestParamById("test_id",$health_facility_test_table_name,$main_test_id);
					// echo $test_id;
					$test_id = strtolower($test_id);

					// echo $main_test_id ."<br>";
					$substr_name = substr($test_id,0,2);
					// echo $substr_name;
					if($substr_name == "us"){
						$num++;
					}
				}
				
			}

			return $num;
		}

		public function checkIfThisInitiationCodeHasSonologistTest($health_facility_id,$initiation_code){
			$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'sub_dept_id' => 6,'radiographer_complete' => 0));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$main_test_id = $row->main_test_id;
					$test_id = $this->getTestParamById("test_id",$health_facility_test_table_name,$main_test_id);
					// echo $test_id;
					$test_id = strtolower($test_id);

					// echo $main_test_id ."<br>";
					$substr_name = substr($test_id,0,2);
					// echo $substr_name;
					if($substr_name == "us"){
						return true;
					}
				}
			}
		}

		public function getInitiationCodesAwaitingSonologist($health_facility_id,$sub_dept_id){
			
			$ret_arr = array();
			
			$query_str = "SELECT initiation_code FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND radiographer_complete = 0 AND `sub_dept_id` = 6 AND paid = 1 ORDER BY `lab_id` ASC";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					if($this->checkIfThisInitiationCodeHasSonologistTest($health_facility_id,$initiation_code)){
						$ret_arr[] = $initiation_code;
					}
				}
				$ret_arr = array_values(array_unique($ret_arr));
			}else{
				return false;
			}

			return $ret_arr;
		}

		public function updateCommentRadiolographer($type,$comments,$health_facility_id,$main_test_id,$lab_id,$date,$time){
			$this->db->select_max("radiology_interval");
			$this->db->from("lab_facility_initiations");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("main_test_id",$main_test_id);
			$this->db->limit(1);
			$query = $this->db->get();

			
			if($query->num_rows() == 1){
				$radiology_interval = $query->result()[0]->radiology_interval;
				$new_radiology_interval = $radiology_interval + 1;
				$form_array = array(
					'radiology_interval' => $new_radiology_interval,
					'radiology_comments' => $comments,
					'radiographer' => $this->getUserIdWhenLoggedIn(),
					'radiographer_complete' => 1,
					'radiographer_complete_time' => $date . " " .$time
				);
				if($type == "cardiologist" || $type == "radiologist"){
					$form_array['redirect_to'] = $type;
				}else{
					$form_array['radiology_complete'] = 1;
					$form_array['radiology_complete_time'] = $date . " " .$time;
				}

				return $this->db->update("lab_facility_initiations",$form_array,array('health_facility_id' => $health_facility_id,'main_test_id' => $main_test_id,'lab_id' => $lab_id));
			}
		}

		public function getLastRadiologyCommentEnteredForThisTestInThisFacility($health_facility_id,$main_test_id){
			$this->db->select_max("radiology_interval");
			$this->db->from("lab_facility_initiations");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("main_test_id",$main_test_id);
			$this->db->limit(1);

			$query = $this->db->get();

			
			if($query->num_rows() == 1){
				$radiology_interval = $query->result()[0]->radiology_interval;
				$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'main_test_id' => $main_test_id,'radiology_interval' => $radiology_interval),1);
				// echo $this->db->last_query();
				// var_dump($query->result());
				return $query->result()[0]->radiology_comments;
			}else{
				return "";
			}
		}

		public function getNoOfSubTestsRadiographer($health_facility_test_table_name,$health_facility_id,$initiation_code,$lab_id,$main_test_id){
			$sub_tests_num = $this->getNoOfSubTests($health_facility_test_table_name,$main_test_id);
			return $sub_tests_num;
		}

		public function getMainTestIdsOfSelectedTestsOfPatientRadiographer($health_facility_id,$initiation_code,$sub_dept_id){
			
			$ret_arr = array();

			$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			
			$query_str = "SELECT main_test_id FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND radiographer_complete = 0 AND initiation_code = '".$initiation_code."' AND `sub_dept_id` = 6 AND paid = 1 ";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					
					$main_test_id = $row->main_test_id;
					$test_id = $this->getTestParamById("test_id",$health_facility_test_table_name,$main_test_id);
					// echo $test_id;
					$test_id = strtolower($test_id);

					// echo $main_test_id ."<br>";
					$substr_name = substr($test_id,0,2);
					// echo $substr_name;
					if($substr_name != "us"){
						$ret_arr[] = $main_test_id;
					}
				}
				
			}

			return array_values(array_unique($ret_arr));
		}

		public function checkIfTheseSetOfTestsAreValidToBeWorkedOnByRadiographer($health_facility_id,$initiation_code,$sub_dept_id){
			if($this->getTestsNumForPatientAwaitingRadiographer($health_facility_id,$initiation_code,$sub_dept_id) > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getTestsNumForPatientAwaitingRadiographer($health_facility_id,$initiation_code,$sub_dept_id){
			
			$num = 0;

			$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			
			$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND radiographer_complete = 0 AND initiation_code = '".$initiation_code."' AND `sub_dept_id` = 6 AND paid = 1 ";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					
					$main_test_id = $row->main_test_id;
					$test_id = $this->getTestParamById("test_id",$health_facility_test_table_name,$main_test_id);
					// echo $test_id;
					$test_id = strtolower($test_id);

					// echo $main_test_id ."<br>";
					$substr_name = substr($test_id,0,2);
					// echo $substr_name;
					if($substr_name != "us"){
						$num++;
					}
				}
				
			}

			return $num;
		}

		public function getInitiationCodesAwaitingRadiographer($health_facility_id,$sub_dept_id){
			
			$ret_arr = array();
			
			$query_str = "SELECT initiation_code FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND radiographer_complete = 0 AND `sub_dept_id` = 6 AND paid = 1 ORDER BY `lab_id` ASC";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					if($this->checkIfThisInitiationCodeHasRadiographerTest($health_facility_id,$initiation_code)){
						$ret_arr[] = $initiation_code;
					}
				}
				$ret_arr = array_values(array_unique($ret_arr));
			}else{
				return false;
			}

			return $ret_arr;
		}

		public function checkIfThisInitiationCodeHasRadiographerTest($health_facility_id,$initiation_code){
			$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
			$health_facility_test_table_name = $this->createTestTableHeaderString($health_facility_id,$health_facility_name);
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'sub_dept_id' => 6,'radiographer_complete' => 0));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$main_test_id = $row->main_test_id;
					$test_id = $this->getTestParamById("test_id",$health_facility_test_table_name,$main_test_id);
					// echo $test_id;
					$test_id = strtolower($test_id);

					// echo $main_test_id ."<br>";
					$substr_name = substr($test_id,0,2);
					// echo $substr_name;
					if($substr_name != "us"){
						return true;
					}
				}
			}
		}

		public function getJsonText(){
			$query = $this->db->get_where("lab_facility_initiations",array('id' => 34));
			if($query->num_rows() == 1){
				return $query->result()[0]->radiology_comments;
			}
		}

		public function getMainTestIdsOfSelectedTestsOfPatientPathologistEdit($health_facility_id,$initiation_code,$sub_dept_id){
			$ret_arr = array();
			$lab_structure = $this->getFacilityParamById("lab_structure",$health_facility_id);
			if($lab_structure == "maximum"){
				$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `initiation_code` = '".$initiation_code."' AND `sub_dept_id` = ".$sub_dept_id." AND verified =1 AND comments != ''  AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum') ORDER BY `lab_id` ASC";
			}else if($lab_structure == "standard"){
				$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `initiation_code` = '".$initiation_code."' AND `sub_dept_id` != 6  AND verified =1 AND comments != ''  AND  (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum') ORDER BY `lab_id` ASC";
			}
			$query = $this->db->query($query_str);
			// echo $this->db->last_query();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$main_test_id = $row->main_test_id;
					$ret_arr[] = $main_test_id;
				}
				$ret_arr = array_values(array_unique($ret_arr));
			}else{
				return false;
			}

			return $ret_arr;
		}

		public function checkIfTheseSetOfTestsAreValidToBeWorkedOnByPathologistEdit($health_facility_id,$lab_id,$sub_dept_id){
			$lab_structure = $this->getFacilityParamById("lab_structure",$health_facility_id);
			if($lab_structure == "maximum"){
				$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `sub_dept_id` = ".$sub_dept_id." AND verified = 1 AND comments != ''  AND lab_id = ".$lab_id." AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum')";
			}else if($lab_structure == "standard"){
				$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND sub_dept_id != 6 AND verified = 1 AND comments != ''  AND lab_id = ".$lab_id." AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum')";
			}
			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}


		public function getTestsNumForPatientAwaitingPathologistsCommentEdit($health_facility_id,$initiation_code,$sub_dept_id){
			$lab_structure = $this->getFacilityParamById("lab_structure",$health_facility_id);
			if($lab_structure == "maximum"){
				$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `sub_dept_id` = ".$sub_dept_id." AND verified = 1 AND comments != ''  AND  `initiation_code` = '".$initiation_code."' AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum')";
			}else if($lab_structure == "standard"){
				$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND verified = 1 AND comments != ''  AND `initiation_code` = '".$initiation_code."' AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum')";
			}

			$query = $this->db->query($query_str);
			

			return $query->num_rows();
		}

		public function getInitiationCodesAwaitingPathologistsCommentEdit($health_facility_id,$sub_dept_id){
			$lab_structure = $this->getFacilityParamById("lab_structure",$health_facility_id);
			$ret_arr = array();
			
			if($lab_structure == "maximum"){
				$query_str = "SELECT initiation_code FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `sub_dept_id` = ".$sub_dept_id." AND verified = 1 AND comments != '' AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum') ORDER BY `lab_id` ASC";
			}else if($lab_structure == "standard"){
				$query_str = "SELECT initiation_code FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND sub_dept_id != 6 AND verified = 1 AND comments != '' AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum') ORDER BY `lab_id` ASC";
			}

			// echo $query_str;

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					$ret_arr[] = $initiation_code;
				}
				$ret_arr = array_values(array_unique($ret_arr));
			}else{
				return false;
			}

			return $ret_arr;
		}

		public function enterTestsComments($health_facility_id,$initiation_code,$form_array,$main_test_id){
			return $this->db->update("lab_facility_initiations",$form_array,array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'main_test_id' => $main_test_id,'verified' => 1));
		}

		public function getMainTestIdsOfSelectedTestsOfPatientPathologist($health_facility_id,$initiation_code,$sub_dept_id){
			$ret_arr = array();
			$lab_structure = $this->getFacilityParamById("lab_structure",$health_facility_id);
			if($lab_structure == "maximum"){
				$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `initiation_code` = '".$initiation_code."' AND `sub_dept_id` = ".$sub_dept_id." AND verified =1 AND comments = ''  AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum') ORDER BY `lab_id` ASC";
			}else if($lab_structure == "standard"){
				$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `initiation_code` = '".$initiation_code."' AND `sub_dept_id` != 6  AND verified =1 AND comments = ''  AND  (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum') ORDER BY `lab_id` ASC";
			}
			$query = $this->db->query($query_str);
			// echo $this->db->last_query();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$main_test_id = $row->main_test_id;
					$ret_arr[] = $main_test_id;
				}
				$ret_arr = array_values(array_unique($ret_arr));
			}else{
				return false;
			}

			return $ret_arr;
		}

		public function checkIfTheseSetOfTestsAreValidToBeWorkedOnByPathologist($health_facility_id,$lab_id,$sub_dept_id){
			$lab_structure = $this->getFacilityParamById("lab_structure",$health_facility_id);
			if($lab_structure == "maximum"){
				$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `sub_dept_id` = ".$sub_dept_id." AND verified = 1 AND comments = ''  AND lab_id = ".$lab_id." AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum')";
			}else if($lab_structure == "standard"){
				$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND sub_dept_id != 6 AND verified = 1 AND comments = ''  AND lab_id = ".$lab_id." AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum')";
			}
			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getTestsNumForPatientAwaitingPathologistsComment($health_facility_id,$initiation_code,$sub_dept_id){
			$lab_structure = $this->getFacilityParamById("lab_structure",$health_facility_id);
			if($lab_structure == "maximum"){
				$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `sub_dept_id` = ".$sub_dept_id." AND verified = 1 AND comments = ''  AND  `initiation_code` = '".$initiation_code."' AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum')";
			}else if($lab_structure == "standard"){
				$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND verified = 1 AND comments = ''  AND `initiation_code` = '".$initiation_code."' AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum')";
			}

			$query = $this->db->query($query_str);
			

			return $query->num_rows();
		}

		public function getInitiationCodesAwaitingPathologistsComment($health_facility_id,$sub_dept_id){
			$lab_structure = $this->getFacilityParamById("lab_structure",$health_facility_id);
			$ret_arr = array();
			
			if($lab_structure == "maximum"){
				$query_str = "SELECT initiation_code FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `sub_dept_id` = ".$sub_dept_id." AND verified = 1 AND comments = '' AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum') ORDER BY `lab_id` ASC";
			}else if($lab_structure == "standard"){
				$query_str = "SELECT initiation_code FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND sub_dept_id != 6 AND verified = 1 AND comments = '' AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum') ORDER BY `lab_id` ASC";
			}

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					$ret_arr[] = $initiation_code;
				}
				$ret_arr = array_values(array_unique($ret_arr));
			}else{
				return false;
			}

			return $ret_arr;
		}

		public function submitLabResultsSupervisor($form_array,$health_facility_id,$initiation_code,$lab_id,$patients_user_id,$main_test_id){
			
			return $this->db->update("lab_test_results",$form_array,array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'lab_id' => $lab_id,'main_test_id' => $main_test_id,'user_id' => $patients_user_id));
			
		}

		public function getTestsSubTestsOnlyAwaitingResults($health_facility_id,$initiation_code,$lab_id,$health_facility_test_table_name,$main_test_id){
			$ret_arr = array();
			$sub_tests = $this->getTestsSubTests($health_facility_test_table_name,$main_test_id);
			
			foreach($sub_tests as $row){
				$sub_test_id = $row->id;
				$control_enabled = $row->control_enabled;
				if(!$this->onehealth_model->checkIfThisSubTestResultHasBeenEnteredSuccessfully($health_facility_test_table_name,$health_facility_id,$initiation_code,$lab_id,$main_test_id,$sub_test_id)){
					$ret_arr[] = $row;
				}
			}	
			
			return $ret_arr;
		}

		public function verifyTest($health_facility_id,$initiation_code,$form_array,$main_test_id){
			return $this->db->update("lab_facility_initiations",$form_array,array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'main_test_id' => $main_test_id,'verified' => 0));
		}

		public function getNoOfSubTestsAwaitingVerification($health_facility_test_table_name,$health_facility_id,$initiation_code,$lab_id,$main_test_id){
			$sub_tests_num = $this->getNoOfSubTests($health_facility_test_table_name,$main_test_id);
			return $sub_tests_num;
		}

		public function getMainTestIdsOfSelectedTestsOfPatientSupervisor($health_facility_id,$initiation_code,$sub_dept_id){
			$ret_arr = array();

			$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `initiation_code` = '".$initiation_code."' AND `sub_dept_id` = ".$sub_dept_id." AND (`test_completed` =1 OR `test_entered` = 1) AND verified =0 AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum') ORDER BY `lab_id` ASC";

			$query = $this->db->query($query_str);
			// echo $this->db->last_query();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$main_test_id = $row->main_test_id;
					$ret_arr[] = $main_test_id;
				}
				$ret_arr = array_values(array_unique($ret_arr));
			}else{
				return false;
			}

			return $ret_arr;
		}

		public function checkIfTheseSetOfTestsAreValidToBeWorkedOnBySupervisor($health_facility_id,$lab_id,$sub_dept_id){

			$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `sub_dept_id` = ".$sub_dept_id." AND (`test_completed` =1 OR `test_entered` = 1) AND verified = 0 AND lab_id = ".$lab_id." AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum')";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getLabFacilityParamByInitiationCodeAndHealthFacilityIdAndSubDeptId($param,$initiation_code,$health_facility_id,$sub_dept_id){
			$query = $this->db->get_where("lab_facility_initiations",array('initiation_code' => $initiation_code,'health_facility_id' => $health_facility_id,'sub_dept_id' => $sub_dept_id,$param .'!=' => ""));
			if($query->num_rows() > 0){
				return $query->result()[0]->$param;
			}else{
				return false;
			}
		}

		public function getTestsNumForPatientAwaitingVerification($health_facility_id,$initiation_code,$sub_dept_id){

			$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `sub_dept_id` = ".$sub_dept_id." AND (`test_completed` =1 OR `test_entered` = 1) AND verified = 0 AND `initiation_code` = '".$initiation_code."' AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum')";

			$query = $this->db->query($query_str);
			

			return $query->num_rows();
		}

		public function getInitiationCodesAwaitingVerification($health_facility_id,$sub_dept_id){
			$ret_arr = array();
			

			$query_str = "SELECT initiation_code FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `sub_dept_id` = ".$sub_dept_id." AND (`test_completed` =1 OR `test_entered` = 1)  AND verified = 0 AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum') ORDER BY `lab_id` ASC";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					$ret_arr[] = $initiation_code;
				}
				$ret_arr = array_values(array_unique($ret_arr));
			}else{
				return false;
			}

			return $ret_arr;
		}


		public function checkIfAllSubTestsHaveBeenEnteredSuccessfullyAndMarkInitiationTableAsCompletedStandardOrMaximum($health_facility_test_table_name,$health_facility_id,$initiation_code,$lab_id,$main_test_id,$date,$time){
			$lab_structure = $this->getFacilityParamById("lab_structure",$health_facility_id);
			$sub_tests_num = $this->getNoOfSubTests($health_facility_test_table_name,$main_test_id);

			$query = $this->db->get_where("lab_test_results",array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id,'initiation_code' => $initiation_code,'under' => $main_test_id,'main_test' => 0));
			if($sub_tests_num == $query->num_rows()){
				$num = 0;
				foreach($query->result() as $row){
					$test_result = $row->test_result;
					if($test_result != ""){
						$num++;
					}
				}

				// echo $num . "<br>";
				// echo $sub_tests_num . "<br>";

				if($num == $sub_tests_num){
					$test_completed_time = $date . " " . $time;
					if($this->db->update("lab_facility_initiations",array('test_completed' => 1,'test_completed_time' => $test_completed_time,'lab_structure' => $lab_structure),array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'lab_id' => $lab_id,'main_test_id' => $main_test_id))){
						// echo "string";
						return true;
						// $balance = $this->getTotalBalanceForTests($health_facility_id,$initiation_code);
						// if($balance <= 0){
						// 	$health_facility_name = $this->getFacilityParamById("name",$health_facility_id);


						// 	$health_facility_test_table_name = $this->onehealth_model->createTestTableHeaderString($health_facility_id,$health_facility_name);
						// 	$test_name = $this->getTestParamById("name",$health_facility_test_table_name,$main_test_id);
						// 	$sender = $health_facility_name;
						// 	$patient_user_id = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("user_id",$initiation_code,$health_facility_id);
						// 	$patient_user_name = $this->getUserNameById($patient_user_id);
						// 	$patients_email = $this->getUserParamById("email",$patient_user_id);
						// 	$receiver = $patient_user_name;
							
							
						// 	$patients_phone_number = $this->getUserFullPhoneNumberById($patient_user_id);
						// 	$patients_title = $this->getPatientParamByUserId("title",$patient_user_id);
						// 	$patients_first_name = $this->getPatientParamByUserId("first_name",$patient_user_id);
						// 	$patients_last_name = $this->getPatientParamByUserId("last_name",$patient_user_id);

						// 	$initiation_date = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("date",$initiation_code,$health_facility_id);

						// 	$initiation_time = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("time",$initiation_code,$health_facility_id);

						// 	$title = "Test(s) Result Ready"; 
				  //   		$message = "This Is To Inform You That Your ".$test_name." Test Requested At " .$initiation_date . " " . $initiation_time . " Is Ready. You Can View It In Your Patient Panel.";
				   			
				   
				  //   		$date =	date("j M Y");
						// 	$time = date("H:i:s");

						// 	$notif_array = array(
						// 		'sender' => $sender,
						// 		'receiver' => $receiver,
						// 		'title' => $title,
						// 		'message' => $message,
						// 		'date_sent' => $date,
						// 		'time_sent' => $time
						// 	);

						// 	if($patients_email != ""){
						// 		$recepient_arr = array($patients_email);
						// 		$this->sendEmail($recepient_arr,$title,$message);
						// 	}
						// 	$this->sendMessage($notif_array);
						// 	$patients_full_name = $patients_title . " " . $patients_first_name . " " . $patients_last_name;
						// 	$from = $health_facility_name;
						// 	$to = array($patients_phone_number);
						// 	$body = $patients_full_name . " This Is To Inform You That Your ".$test_name." Requested At ". $initiation_date . " " . $initiation_time . "  Test Is Ready. You Can View It In Your Patient Panel.";
						// 	// $body = "Test message";
						// 	$this->sendFacilitySms($health_facility_id,$from,$to,$body);
						// 	return true;
						// }else{
						// 	return true;
						// }
					}
				}
			}
		}

		public function getTestsNumForPatientValidForInputingOfResultsStandardOrMaximum($health_facility_id,$initiation_code,$sub_dept_id){

			// $this->db->select("initiation_code");
			// $this->db->from("lab_facility_initiations");
			// $this->db->where("health_facility_id",$health_facility_id);
			// $this->db->where("sub_dept_id",$sub_dept_id);
			// $this->db->where("sampled",1);
			// $this->db->where("test_completed",0);
			// $this->db->where("initiation_code",$initiation_code);
			// $this->db->where("lab_structure","");
			// $this->db->or_where("lab_structure","standard");
			// $this->db->or_where("lab_structure","maximum");
			$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `sub_dept_id` = ".$sub_dept_id." AND `sampled` = 1 AND `test_completed` =0 AND `initiation_code` = '".$initiation_code."' AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum')";

			$query = $this->db->query($query_str);
			

			return $query->num_rows();
		}

		public function getMainTestIdsOfSelectedTestsOfPatientLabOfficer2StandardOrMaximum($health_facility_id,$initiation_code,$sub_dept_id){
			$ret_arr = array();

			$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `initiation_code` = '".$initiation_code."' AND `sub_dept_id` = ".$sub_dept_id." AND `sampled` = 1 AND `test_completed` =0 AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum') ORDER BY `lab_id` ASC";

			$query = $this->db->query($query_str);
			// echo $this->db->last_query();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$main_test_id = $row->main_test_id;
					$ret_arr[] = $main_test_id;
				}
				$ret_arr = array_values(array_unique($ret_arr));
			}else{
				return false;
			}

			return $ret_arr;
		}

		public function checkIfTheseSetOfTestsAreValidToBeWorkedOnByOfficer2StandardOrMaximum($health_facility_id,$lab_id,$sub_dept_id){

			$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `sub_dept_id` = ".$sub_dept_id." AND `sampled` = 1 AND `test_completed` =0 AND  lab_id = ".$lab_id." AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum')";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getInitiationCodesValidForInputingOfResultsStandardOrMaximum($health_facility_id,$sub_dept_id){
			$ret_arr = array();
			

			$query_str = "SELECT initiation_code FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `sub_dept_id` = ".$sub_dept_id." AND `sampled` = 1 AND `test_completed` =0 AND (`lab_structure` = '' OR `lab_structure` = 'standard' OR `lab_structure` = 'maximum') ORDER BY `lab_id` ASC";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					$ret_arr[] = $initiation_code;
				}
				$ret_arr = array_values(array_unique($ret_arr));
			}else{
				return false;
			}

			return $ret_arr;
		}

		public function getMainTestResultParam($param,$health_facility_id,$initiation_code,$lab_id,$main_test_id){
			$query = $this->db->get_where("lab_test_results",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'lab_id' => $lab_id,'main_test_id' => $main_test_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}
		}

		public function getLabFacilityParamByInitiationCodeAndHealthFacilityIdAndMainTestId($param,$initiation_code,$health_facility_id,$main_test_id){
			$query = $this->db->get_where("lab_facility_initiations",array('initiation_code' => $initiation_code,'health_facility_id' => $health_facility_id,'main_test_id' => $main_test_id),1);
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}else{
				return false;
			}
		}


		public function getTestsSubTestsOnlyInputedResults($health_facility_id,$initiation_code,$lab_id,$health_facility_test_table_name,$main_test_id){
			$ret_arr = array();
			$sub_tests = $this->getTestsSubTests($health_facility_test_table_name,$main_test_id);
			
			foreach($sub_tests as $row){
				$sub_test_id = $row->id;
				$control_enabled = $row->control_enabled;
				if($this->onehealth_model->checkIfThisSubTestResultHasBeenEnteredSuccessfully($health_facility_test_table_name,$health_facility_id,$initiation_code,$lab_id,$main_test_id,$sub_test_id)){
					$ret_arr[] = $row;
				}
			}	
			
			return $ret_arr;
		}

		public function checkIfValueWasEnteredForSubTestsOfThisTestEdit($health_facility_id,$initiation_code,$lab_id,$health_facility_test_table_name,$main_test_id,$post_array){
			if(is_array($post_array) && count($post_array) > 0){
				$sub_tests = $this->onehealth_model->getTestsSubTests($health_facility_test_table_name,$main_test_id);
				
				foreach($sub_tests as $row){
    				$sub_test_id = $row->id;
    				$control_enabled = $row->control_enabled;

    				$control_1_val = 'control_1_'.$sub_test_id;
    				$control_2_val = 'control_2_'.$sub_test_id;
    				$control_3_val = 'control_3_'.$sub_test_id;

    				$test_result_val = 'test_result_'.$sub_test_id;

    				if($this->onehealth_model->checkIfThisSubTestResultHasBeenEnteredSuccessfully($health_facility_test_table_name,$health_facility_id,$initiation_code,$lab_id,$main_test_id,$sub_test_id)){

	    				if($control_enabled == 1){
		    				if(isset($post_array[$control_1_val])){
		    					if($post_array[$control_1_val] != ""){
		    						return true;
		    					}
		    				}

		    				if(isset($post_array[$control_2_val])){
		    					if($post_array[$control_2_val] != ""){
		    						return true;
		    					}
		    				}

		    				if(isset($post_array[$control_3_val])){
		    					if($post_array[$control_3_val] != ""){
		    						return true;
		    					}
		    				}
		    			}

	    				if(isset($post_array[$test_result_val])){
	    					if($post_array[$test_result_val] != ""){
	    						return true;
	    					}
	    				}
	    			}
    			}	

			}else{
				return true;
			}
		}

		public function getSubTestResultParam($param,$health_facility_id,$initiation_code,$lab_id,$main_test_id,$sub_test_id){
			$query = $this->db->get_where("lab_test_results",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'lab_id' => $lab_id,'main_test_id' => $sub_test_id,'under' => $main_test_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}
		}

		public function checkIfAtleastOneSubTestBeenEnteredSuccessfully($health_facility_test_table_name,$health_facility_id,$initiation_code,$lab_id,$main_test_id){
			$sub_tests_num = $this->getNoOfSubTests($health_facility_test_table_name,$main_test_id);

			$query = $this->db->get_where("lab_test_results",array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id,'initiation_code' => $initiation_code,'under' => $main_test_id,'main_test' => 0));
			if($query->num_rows() > 0){
				
				$num = 0;
				foreach($query->result() as $row){
					$test_result = $row->test_result;
					if($test_result != ""){
						$num++;
					}
				}

				if($num >= 1){
					return true;
				}else{
					return false;
				}
				
			}else{
				return false;
			}
		}

		public function getMainTestIdsOfSelectedTestsOfPatientLabOfficer2MiniEdit($health_facility_id,$initiation_code){
			$ret_arr = array();
			// $this->db->select("main_test_id");
			// $this->db->from("lab_facility_initiations");
			// $this->db->where("health_facility_id",$health_facility_id);
			// $this->db->where("initiation_code",$initiation_code);
			// $this->db->where("sub_dept_id !=",6);
			// $this->db->where("test_completed",1);
			// $this->db->or_where("test_entered",1);
			// $this->db->order_by("test_completed_time","DESC");

			$query_str = "SELECT main_test_id FROM lab_facility_initiations WHERE health_facility_id = ".$health_facility_id . " AND initiation_code = '" . $initiation_code . "' AND sub_dept_id != 6 AND (test_completed = 1 OR test_entered = 1) ORDER BY test_completed_time DESC";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$main_test_id = $row->main_test_id;
					$ret_arr[] = $main_test_id;
				}
				$ret_arr = array_values(array_unique($ret_arr));
			}else{
				return false;
			}

			return $ret_arr;
		}

		public function getNoOfSubTestsWithResultEntered($health_facility_test_table_name,$health_facility_id,$initiation_code,$lab_id,$main_test_id){
			$sub_tests_num = $this->getNoOfSubTests($health_facility_test_table_name,$main_test_id);

			$query = $this->db->get_where("lab_test_results",array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id,'initiation_code' => $initiation_code,'under' => $main_test_id,'main_test' => 0));
			if($query->num_rows() > 0){
				
				$num = 0;
				foreach($query->result() as $row){
					$test_result = $row->test_result;
					if($test_result != ""){
						$num++;
					}
				}

				return $num;

			}else{
				return 0;
			}
		}

		public function checkIfTheseSetOfTestsAreValidToBeWorkedOnByOfficer2MiniEdit($health_facility_id,$lab_id){
			// $this->db->select("time");
			// $this->db->from("lab_facility_initiations");
			// $this->db->where('health_facility_id',$health_facility_id);
			// $this->db->where("sub_dept_id !=",6);
			
			// $this->db->where("test_completed",1);
			// $this->db->or_where("test_entered",1);
			// $this->db->where("lab_id",$lab_id);
			
			$query_str = "SELECT time FROM lab_facility_initiations WHERE health_facility_id = " . $health_facility_id . " AND sub_dept_id != 6 AND (test_completed = 1 OR test_entered = 1) AND lab_id = ". $lab_id;

			$query = $this->db->query($query_str);
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function updateCommentsSubTests($health_facility_id,$initiation_code,$lab_id,$main_test_id,$comments){
			return $this->db->update("lab_test_results",array('comments' => $comments),array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'lab_id' => $lab_id,'under' => $main_test_id,'main_test' => 0,'has_sub_test' => 0));
		}

		public function checkIfResultsTableAlreadyHasThisSubTestInitBefore($health_facility_id,$lab_id,$initiation_code,$main_test_id,$sub_test_id){
			$query = $this->db->get_where("lab_test_results",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'lab_id' => $lab_id,'main_test_id' => $sub_test_id,'main_test' => 0,'under' => $main_test_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function loadCommentsPreviouslyEnteredSubTests($health_facility_id,$initiation_code,$lab_id,$main_test_id){
			$query = $this->db->get_where("lab_test_results",array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id,'initiation_code' => $initiation_code,'under' => $main_test_id,'main_test' => 0),1);


			if($query->num_rows() == 1){
				// echo $query->result()[0]->comments;
				return $query->result()[0]->comments;				
			}
		}

		public function checkIfThisSubTestResultHasBeenEnteredSuccessfully($health_facility_test_table_name,$health_facility_id,$initiation_code,$lab_id,$main_test_id,$sub_test_id){
			

			$query = $this->db->get_where("lab_test_results",array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id,'initiation_code' => $initiation_code,'under' => $main_test_id,'main_test_id' => $sub_test_id,'main_test' => 0));
			if($query->num_rows() == 1){
				$num = 0;
				foreach($query->result() as $row){
					$test_result = $row->test_result;
					if($test_result != ""){
						return true;
					}else{
						return false;
					}
				}
			}else{
				return false;
			}
		}	
		


		public function getNoOfSubTestsWithResultAwaiting($health_facility_test_table_name,$health_facility_id,$initiation_code,$lab_id,$main_test_id){
			$sub_tests_num = $this->getNoOfSubTests($health_facility_test_table_name,$main_test_id);

			$query = $this->db->get_where("lab_test_results",array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id,'initiation_code' => $initiation_code,'under' => $main_test_id,'main_test' => 0));
			if($query->num_rows() > 0){
				
				$num = 0;
				foreach($query->result() as $row){
					$test_result = $row->test_result;
					if($test_result != ""){
						$num++;
					}
				}

				return $sub_tests_num - $num;

			}else{
				return $sub_tests_num;
			}
		}

		public function checkIfAllSubTestsHaveBeenEnteredSuccessfully($health_facility_test_table_name,$health_facility_id,$initiation_code,$lab_id,$main_test_id){
			$sub_tests_num = $this->getNoOfSubTests($health_facility_test_table_name,$main_test_id);

			$query = $this->db->get_where("lab_test_results",array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id,'initiation_code' => $initiation_code,'under' => $main_test_id,'main_test' => 0));
			if($query->num_rows() > 0){
				
				$num = 0;
				foreach($query->result() as $row){
					$test_result = $row->test_result;
					if($test_result != ""){
						$num++;
					}
				}

				if($num == $sub_tests_num){
					return true;
				}else{
					return false;
				}
				
			}else{
				return false;
			}
		}

		public function checkIfAllSubTestsHaveBeenEnteredSuccessfullyAndMarkInitiationTableAsCompleted($health_facility_test_table_name,$health_facility_id,$initiation_code,$lab_id,$main_test_id,$date,$time){
			$lab_structure = $this->getFacilityParamById("lab_structure",$health_facility_id);
			$sub_tests_num = $this->getNoOfSubTests($health_facility_test_table_name,$main_test_id);

			$query = $this->db->get_where("lab_test_results",array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id,'initiation_code' => $initiation_code,'under' => $main_test_id,'main_test' => 0));
			if($sub_tests_num == $query->num_rows()){
				$num = 0;
				foreach($query->result() as $row){
					$test_result = $row->test_result;
					if($test_result != ""){
						$num++;
					}
				}

				if($num == $sub_tests_num){
					$test_completed_time = $date . " " . $time;
					if($this->db->update("lab_facility_initiations",array('test_completed' => 1,'test_completed_time' => $test_completed_time),array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'lab_id' => $lab_id,'main_test_id' => $main_test_id,'lab_structure' => $lab_structure))){
						$balance = $this->getTotalBalanceForTests($health_facility_id,$initiation_code);
						if($balance <= 0){
							$health_facility_name = $this->getFacilityParamById("name",$health_facility_id);


							$health_facility_test_table_name = $this->onehealth_model->createTestTableHeaderString($health_facility_id,$health_facility_name);
							$test_name = $this->getTestParamById("name",$health_facility_test_table_name,$main_test_id);
							$sender = $health_facility_name;
							$patient_user_id = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("user_id",$initiation_code,$health_facility_id);
							$patient_user_name = $this->getUserNameById($patient_user_id);
							$patients_email = $this->getUserParamById("email",$patient_user_id);
							$receiver = $patient_user_name;
							
							
							$patients_phone_number = $this->getUserFullPhoneNumberById($patient_user_id);
							$patients_title = $this->getPatientParamByUserId("title",$patient_user_id);
							$patients_first_name = $this->getPatientParamByUserId("first_name",$patient_user_id);
							$patients_last_name = $this->getPatientParamByUserId("last_name",$patient_user_id);

							$initiation_date = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("date",$initiation_code,$health_facility_id);

							$initiation_time = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("time",$initiation_code,$health_facility_id);

							$title = "Test(s) Result Ready"; 
				    		$message = "This Is To Inform You That Your ".$test_name." Test Requested At " .$initiation_date . " " . $initiation_time . " Is Ready. You Can View It In Your Patient Panel.";
				   			
				   
				    		$date =	date("j M Y");
							$time = date("H:i:s");

							$notif_array = array(
								'sender' => $sender,
								'receiver' => $receiver,
								'title' => $title,
								'message' => $message,
								'date_sent' => $date,
								'time_sent' => $time
							);

							if($patients_email != ""){
								$recepient_arr = array($patients_email);
								$this->sendEmail($recepient_arr,$title,$message);
							}
							$this->sendMessage($notif_array);
							$patients_full_name = $patients_title . " " . $patients_first_name . " " . $patients_last_name;
							$from = $health_facility_name;
							$to = array($patients_phone_number);
							$body = $patients_full_name . " This Is To Inform You That Your ".$test_name." Requested At ". $initiation_date . " " . $initiation_time . "  Test Is Ready. You Can View It In Your Patient Panel.";
							// $body = "Test message";
							$this->sendFacilitySms($health_facility_id,$from,$to,$body);
							return true;
						}else{
							return true;
						}
					}
				}
			}
		}

		public function updateTestResults1($form_array,$health_facility_id,$initiation_code,$lab_id,$main_test_id,$sub_test_id){
			return $this->db->update("lab_test_results",$form_array,array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'lab_id' => $lab_id,'main_test_id' => $sub_test_id,'under' => $main_test_id,'main_test' => 0,'has_sub_test' => 0));
		}

		public function checkIfThisSubTestRecordIsAlreadyInDataBase($health_facility_id,$initiation_code,$lab_id,$main_test_id,$sub_test_id){
			$query = $this->db->get_where("lab_test_results",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'lab_id' => $lab_id,'main_test_id' => $sub_test_id,'under' => $main_test_id,'main_test' => 0,'has_sub_test' => 0));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}	
		}

		public function createTestResultRecordForMainTestWithSubTests($health_facility_id,$initiation_code,$lab_id,$main_test_id){
			$query = $this->db->get_where("lab_test_results",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'lab_id' => $lab_id,'main_test_id' => $main_test_id,'main_test' => 1,'has_sub_test' => 1));
			if($query->num_rows() > 0){
				return true;
			}else{
				return $this->db->insert("lab_test_results",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'lab_id' => $lab_id,'main_test_id' => $main_test_id,'main_test' => 1,'has_sub_test' => 1));
			}
		}

		public function checkIfValueWasEnteredForThisSubTest($health_facility_test_table_name,$sub_test_id,$post_array){
			if(is_array($post_array) && count($post_array) > 0){
				$sub_test = $this->getTestById($health_facility_test_table_name,$sub_test_id);
				foreach($sub_test as $row){
    				$sub_test_id = $row->id;
    				$control_enabled = $row->control_enabled;

    				$control_1_val = 'control_1_'.$sub_test_id;
    				$control_2_val = 'control_2_'.$sub_test_id;
    				$control_3_val = 'control_3_'.$sub_test_id;

    				$test_result_val = 'test_result_'.$sub_test_id;

    				if($control_enabled == 1){
	    				if(isset($post_array[$control_1_val])){
	    					if($post_array[$control_1_val] != ""){
	    						return true;
	    					}
	    				}

	    				if(isset($post_array[$control_2_val])){
	    					if($post_array[$control_2_val] != ""){
	    						return true;
	    					}
	    				}

	    				if(isset($post_array[$control_3_val])){
	    					if($post_array[$control_3_val] != ""){
	    						return true;
	    					}
	    				}
	    			}

    				if(isset($post_array[$test_result_val])){
    					if($post_array[$test_result_val] != ""){
    						return true;
    					}
    				}
    			}	

			}else{
				return true;
			}
		}


		public function checkIfValueWasEnteredForSubTestsOfThisTest($health_facility_test_table_name,$main_test_id,$post_array){
			if(is_array($post_array) && count($post_array) > 0){
				$sub_tests = $this->onehealth_model->getTestsSubTests($health_facility_test_table_name,$main_test_id);
				if(isset($post_array['comments'])){
					if($post_array['comments'] != ""){
						return true;
					}
				}
				foreach($sub_tests as $row){
    				$sub_test_id = $row->id;
    				$control_enabled = $row->control_enabled;

    				$control_1_val = 'control_1_'.$sub_test_id;
    				$control_2_val = 'control_2_'.$sub_test_id;
    				$control_3_val = 'control_3_'.$sub_test_id;

    				$test_result_val = 'test_result_'.$sub_test_id;

    				if($control_enabled == 1){
	    				if(isset($post_array[$control_1_val])){
	    					if($post_array[$control_1_val] != ""){
	    						return true;
	    					}
	    				}

	    				if(isset($post_array[$control_2_val])){
	    					if($post_array[$control_2_val] != ""){
	    						return true;
	    					}
	    				}

	    				if(isset($post_array[$control_3_val])){
	    					if($post_array[$control_3_val] != ""){
	    						return true;
	    					}
	    				}
	    			}

    				if(isset($post_array[$test_result_val])){
    					if($post_array[$test_result_val] != ""){
    						return true;
    					}
    				}
    			}	

			}else{
				return true;
			}
		}

		public function getTestsNumForPatientValidForEditingOfResultsMini($health_facility_id,$initiation_code){

			// $this->db->select("initiation_code");
			// $this->db->from("lab_facility_initiations");
			// $this->db->where("health_facility_id",$health_facility_id);
			// $this->db->where("sub_dept_id !=",6);
			// $this->db->where("test_completed",1);
			// $this->db->or_where("test_entered",1);
			// $this->db->where("initiation_code",$initiation_code);

			$query_str = "SELECT initiation_code FROM lab_facility_initiations WHERE health_facility_id = " . $health_facility_id . " AND sub_dept_id != 6 AND (test_completed = 1 OR test_entered = 1) AND initiation_code = '" . $initiation_code ."'";

			$query = $this->db->query($query_str);

			return $query->num_rows();
		}

		public function getInitiationCodesValidForEditingOfResultsMini($health_facility_id){
			$ret_arr = array();
			

			$query_str = "SELECT initiation_code FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `sub_dept_id` != 6 AND (`test_completed` =1 OR `test_entered` = 1) AND (`lab_structure` = '' OR `lab_structure` = 'mini') ORDER BY `lab_id` ASC";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					$ret_arr[] = $initiation_code;
				}
				$ret_arr = array_values(array_unique($ret_arr));
			}else{
				return false;
			}

			return $ret_arr;
		}

		public function addTestResultRow($form_array){
			return $this->db->insert("lab_test_results",$form_array);
		}

		public function updateTestResultTableByLabIdFacilityIdAndMainTestId($form_array,$lab_id,$health_facility_id,$main_test_id){
			return $this->db->update('lab_test_results',$form_array,array('lab_id' => $lab_id,'health_facility_id' => $health_facility_id,'main_test_id' => $main_test_id));
		}

		public function getTestResultParamByLabIdFacilityIdAndMainTestId($param,$lab_id,$health_facility_id,$main_test_id){
			$query = $this->db->get_where("lab_test_results",array('lab_id' => $lab_id,'health_facility_id' => $health_facility_id,'main_test_id' => $main_test_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}
		}

		public function updateTestInitiation1($health_facility_id,$initiation_code,$form_array,$main_test_id){
			return $this->db->update("lab_facility_initiations",$form_array,array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'main_test_id' => $main_test_id));
		}

		public function getTotalBalanceForTests($health_facility_id,$initiation_code){
			$total_price_for_tests = $this->getTotalPriceForTestsByInitiationCode($health_facility_id,$initiation_code);
			
			$initiation_user_type = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("user_type",$initiation_code,$health_facility_id);
			$referring_facility_id = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("referring_facility_id",$initiation_code,$health_facility_id);
			$amount_paid = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("amount_paid",$initiation_code,$health_facility_id);
			
			if($referring_facility_id == 0){
				if($initiation_user_type == "fp"){
					$balance = $total_price_for_tests;

					$discount = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("discount",$initiation_code,$health_facility_id);
					if($discount != 0){
						$balance = ($total_price_for_tests - (($discount / 100) * $total_price_for_tests));
					}

				}else if($initiation_user_type == "nfp"){
					$balance = 0;
				}else{
					$balance = $total_price_for_tests;
					$part_payment_percent = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("part_payment_percent",$initiation_code,$health_facility_id);
					
					if($part_payment_percent != 0){
						$balance = ($total_price_for_tests - (($part_payment_percent / 100) * $total_price_for_tests));
					}

					$discount = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("discount",$initiation_code,$health_facility_id);

					if($discount != 0){
					
						$balance = ($balance - (($discount / 100) * $balance));
					}
					
				}
			}else{
				$lab_to_lab_discount = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("lab_to_lab_discount",$initiation_code,$health_facility_id);

				$balance = ($total_price_for_tests - (($lab_to_lab_discount / 100) * $total_price_for_tests));
			}

			$balance = round(($balance - $amount_paid),2);

			return $balance;
		}

		// public function setPharmacyBalanceToZero($health_facility_id,$initiation_code){}

		public function submitLabResultsMini($form_array,$health_facility_id,$initiation_code,$lab_id,$patients_user_id,$main_test_id,$action){
			$proceed = false;

			if($action == "insert"){
				$proceed = $this->db->insert("lab_test_results",$form_array);
			}else if($action == "update"){
				$proceed = $this->db->update("lab_test_results",$form_array,array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'lab_id' => $lab_id,'main_test_id' => $main_test_id,'user_id' => $patients_user_id));
			}else{
				return false;
			}

			if($proceed){
				$balance = $this->getTotalBalanceForTests($health_facility_id,$initiation_code);
				if($balance <= 0){
					$health_facility_name = $this->getFacilityParamById("name",$health_facility_id);


					$health_facility_test_table_name = $this->onehealth_model->createTestTableHeaderString($health_facility_id,$health_facility_name);
					$test_name = $this->getTestParamById("name",$health_facility_test_table_name,$main_test_id);
					$sender = $health_facility_name;
					$patient_user_id = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("user_id",$initiation_code,$health_facility_id);
					$patient_user_name = $this->getUserNameById($patient_user_id);
					$patients_email = $this->getUserParamById("email",$patient_user_id);
					$receiver = $patient_user_name;
					
					
					$patients_phone_number = $this->getUserFullPhoneNumberById($patient_user_id);
					$patients_title = $this->getPatientParamByUserId("title",$patient_user_id);
					$patients_first_name = $this->getPatientParamByUserId("first_name",$patient_user_id);
					$patients_last_name = $this->getPatientParamByUserId("last_name",$patient_user_id);

					$title = "Test(s) Result Ready"; 
		    		$message = "This Is To Inform You That Your ".$test_name." Test Is Ready. You Can View It In Your Patient Panel.";
		   			
		   
		    		$date =	date("j M Y");
					$time = date("H:i:s");

					$notif_array = array(
						'sender' => $sender,
						'receiver' => $receiver,
						'title' => $title,
						'message' => $message,
						'date_sent' => $date,
						'time_sent' => $time
					);

					if($patients_email != ""){
						$recepient_arr = array($patients_email);
						$this->sendEmail($recepient_arr,$title,$message);
					}
					$this->sendMessage($notif_array);
					$patients_full_name = $patients_title . " " . $patients_first_name . " " . $patients_last_name;
					$from = $health_facility_name;
					$to = array($patients_phone_number);
					$body = $patients_full_name . " This Is To Inform You That Your ".$test_name." Test Is Ready. You Can View It In Your Patient Panel.";
					// $body = "Test message";
					$this->sendFacilitySms($health_facility_id,$from,$to,$body);
					return true;
				}else{
					return true;
				}	
			}
		}

		public function checkIfResultsTableAlreadyHasThisTestInitBefore($health_facility_id,$lab_id,$initiation_code,$main_test_id){
			$query = $this->db->get_where("lab_test_results",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'lab_id' => $lab_id,'main_test_id' => $main_test_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfThisTestWasRequestedByThisPatient($health_facility_id,$lab_id,$main_test_id){
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id,'main_test_id' => $main_test_id));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfTestIsASubTest($health_facility_test_table_name,$main_test_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('id' => $main_test_id,'under' => 0));
			if($query->num_rows() == 1){
				return false;
			}else{
				return true;
			}
		}


		public function getTestParamById($param,$health_facility_test_table_name,$id){
			$query = $this->db->get_where($health_facility_test_table_name,array('id' => $id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}
		}

		public function getMainTestIdsOfSelectedTestsOfPatientLabOfficer2Mini($health_facility_id,$initiation_code){
			$ret_arr = array();
			// $this->db->select("main_test_id");
			// $this->db->from("lab_facility_initiations");
			// $this->db->where("health_facility_id",$health_facility_id);
			// $this->db->where("initiation_code",$initiation_code);
			// $this->db->where("sub_dept_id !=",6);
			// $this->db->where("sampled",1);
			// $this->db->where("test_completed",0);
			// $this->db->where("lab_structure","");
			// $this->db->or_where("lab_structure","mini");
			// $this->db->order_by("seperation_time","DESC");

			$query_str = "SELECT main_test_id FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `initiation_code` = '".$initiation_code."' AND `sub_dept_id` != 6 AND `sampled` = 1 AND `test_completed` = 0  AND (`lab_structure` = '' OR `lab_structure` = 'mini') ORDER BY `lab_id` ASC";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$main_test_id = $row->main_test_id;
					$ret_arr[] = $main_test_id;
				}
				$ret_arr = array_values(array_unique($ret_arr));
			}else{
				return false;
			}

			return $ret_arr;
		}

		public function checkIfTheseSetOfTestsAreValidToBeWorkedOnByOfficer2Mini($health_facility_id,$lab_id){
			// $this->db->select("time");
			// $this->db->from("lab_facility_initiations");
			// $this->db->where('health_facility_id',$health_facility_id);
			// $this->db->where("sub_dept_id !=",6);
			// $this->db->where("sampled",1);
			// $this->db->where("test_completed",0);
			// $this->db->where("lab_id",$lab_id);
			// $this->db->where('lab_structure',"");
			// $this->db->or_where('lab_structure',"mini");

			$query_str = "SELECT * FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `sub_dept_id` != 6 AND `sampled` = 1 AND `test_completed` = 0 AND `lab_id` = ".$lab_id."  AND (`lab_structure` = '' OR `lab_structure` = 'mini')";

			$query = $this->db->query($query_str);
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getTestsNumForPatientValidForInputingOfResultsMini($health_facility_id,$initiation_code){

			// $this->db->select("initiation_code");
			// $this->db->from("lab_facility_initiations");
			// $this->db->where("health_facility_id",$health_facility_id);
			// $this->db->where("sub_dept_id !=",6);
			// $this->db->where("sampled",1);
			// $this->db->where("test_completed",0);
			// $this->db->where("initiation_code",$initiation_code);
			// $this->db->where("lab_structure","");
			// $this->db->or_where("lab_structure","mini");


			$query_str = "SELECT initiation_code FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id." AND `initiation_code` = '".$initiation_code."' AND `sub_dept_id` != 6 AND `sampled` = 1 AND `test_completed` = 0  AND (`lab_structure` = '' OR `lab_structure` = 'mini')";
			$query = $this->db->query($query_str);

			return $query->num_rows();
		}

		public function getInitiationCodesValidForInputingOfResultsMini($health_facility_id){
			$ret_arr = array();
			// $this->db->select("initiation_code");
			// $this->db->from("lab_facility_initiations");
			// $this->db->where("health_facility_id",$health_facility_id);
			// $this->db->where("sub_dept_id !=",6);
			// $this->db->where("sampled",1);
			// $this->db->where("test_completed",0);
			// $this->db->where("lab_structure","");
			// $this->db->or_where("lab_structure","mini");
			// $this->db->order_by("seperation_time","DESC");

			$query_str = "SELECT initiation_code FROM `lab_facility_initiations` WHERE `health_facility_id` = ".$health_facility_id."  AND `sub_dept_id` != 6 AND `sampled` = 1 AND `test_completed` = 0  AND (`lab_structure` = '' OR `lab_structure` = 'mini') ORDER BY `lab_id` ASC";

			$query = $this->db->query($query_str);

			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					$ret_arr[] = $initiation_code;
				}
				$ret_arr = array_values(array_unique($ret_arr));
			}else{
				return false;
			}

			return $ret_arr;
		}

		public function checkIfPersonnelInfoIsComplete(){
			$user_id = $this->getUserIdWhenLoggedIn();
			$full_name = $this->getUserParamById("full_name",$user_id);
			$title = $this->getUserParamById("title",$user_id);
			$qualification = $this->getUserParamById("qualification",$user_id);
			$signature = $this->getUserParamById("signature",$user_id);
			if($full_name == "" || $title == "" || $qualification == "" || $signature == ""){
				return false;
			}else{
				return true;
			}
		}

		public function checkForPersonnel($health_facility_id,$personnel_slug,$sub_dept_slug,$dept_slug){
			$dept_id = $this->getDeptIdBySlug($dept_slug);
			$sub_dept_id = $this->getSubDeptIdBySlugAndDeptId($sub_dept_slug,$dept_id);
			$personnel_id = $this->getPersonnelIdBySlugDeptIdAndSubDeptId($personnel_slug,$dept_id,$sub_dept_id);
			
			$query = $this->db->get_where("personnel_officers",array('health_facility_id' => $health_facility_id,'personnel_id' => $personnel_id,'type' => ''));

			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getUserPositionAtPersonnel($health_facility_id,$user_id,$personnel_slug,$sub_dept_slug,$dept_slug){
			if($this->checkIfUserIsAdminOfFacility1($health_facility_id,$user_id)){
				return "admin";
			}else{
				if($this->checkIfUserIsSubAdminOfSubDeptInFacility($health_facility_id,$user_id,$sub_dept_slug,$dept_slug)){
					return "sub_admin";
				}else{
					if($this->checkIfUserIsPersonnelOfPersonnelInFacility($health_facility_id,$user_id,$personnel_slug,$sub_dept_slug,$dept_slug)){
						return "personnel";
					}else{
						return false;
					}
				}
			}
		}

		public function checkIfUserIsSubAdminOfSubDeptInFacility($health_facility_id,$user_id,$sub_dept_slug,$dept_slug){

			$dept_id = $this->getDeptIdBySlug($dept_slug);
			$sub_dept_id = $this->getSubDeptIdBySlugAndDeptId($sub_dept_slug,$dept_id);
			// echo $sub_dept_id;
			$query = $this->db->get_where("sub_admin_officers",array('health_facility_id' => $health_facility_id,'user_id' => $user_id,'personnel_id' => $sub_dept_id));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfUserIsPersonnelOfPersonnelInFacility($health_facility_id,$user_id,$personnel_slug,$sub_dept_slug,$dept_slug){

			$dept_id = $this->getDeptIdBySlug($dept_slug);
			$sub_dept_id = $this->getSubDeptIdBySlugAndDeptId($sub_dept_slug,$dept_id);
			$personnel_id = $this->getPersonnelIdBySlugDeptIdAndSubDeptId($personnel_slug,$dept_id,$sub_dept_id);
			
			$query = $this->db->get_where("personnel_officers",array('health_facility_id' => $health_facility_id,'user_id' => $user_id,'personnel_id' => $personnel_id,'type' => ''));
			
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfUserIsAdminOrSubAdminOrPersonnel($health_facility_id,$user_id,$personnel_slug,$sub_dept_slug,$dept_slug){
			if($this->checkIfUserIsAdminOfFacility1($health_facility_id,$user_id)){
				return true;
			}else{
				if($this->checkIfUserIsSubAdminOfSubDeptInFacility($health_facility_id,$user_id,$sub_dept_slug,$dept_slug)){
					return true;
				}else{
					if($this->checkIfUserIsPersonnelOfPersonnelInFacility($health_facility_id,$user_id,$personnel_slug,$sub_dept_slug,$dept_slug)){
						return true;
					}else{
						return false;
					}
				}
			}
		}

		public function getCurrentLabStructureForFacility($health_facility_id){
			$query = $this->db->get_where("health_facility",array('id' => $health_facility_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->lab_structure;
			}
		}

		public function getCurrentClinicStructureForFacility($health_facility_id){
			$query = $this->db->get_where("health_facility",array('id' => $health_facility_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->clinic_structure;
			}
		}

		public function getCurrentPharmacyStructureForFacility($health_facility_id){
			$query = $this->db->get_where("health_facility",array('id' => $health_facility_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->pharmacy_structure;
			}
		}
		public function sendNotifsToPatientAboutSampleRejected($health_facility_id,$initiation_code,$date,$time,$lab_id){
			$health_facility_name = $this->getFacilityParamById("name",$health_facility_id);
			$sender = $health_facility_name;
			$patient_user_id = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("user_id",$initiation_code,$health_facility_id);
			$patient_user_name = $this->getUserNameById($patient_user_id);
			$patients_email = $this->getUserParamById("email",$patient_user_id);
			$receiver = $patient_user_name;
			
			
			$patients_phone_number = $this->getUserFullPhoneNumberById($patient_user_id);
			$patients_title = $this->getPatientParamByUserId("title",$patient_user_id);
			$patients_first_name = $this->getPatientParamByUserId("first_name",$patient_user_id);
			$patients_last_name = $this->getPatientParamByUserId("last_name",$patient_user_id);

			$title = "Tests Sample(s) Rejected";
    		$message = "This Is To Inform You That The Sample(s) For Test(s) With Initiation Code ".$initiation_code.". And Lab Id: ".$lab_id." Has Been Rejected. Please Send New Sample(s) For This Test(s).";
   			$message .= "<h3>Requested Tests</h3>";
	       	$message .= '<div class="table-div material-datatables table-responsive" style="">';
            $message .= '<table class="table table-test table-striped table-bordered nowrap hover display" id="example" cellspacing="0" width="100%" style="width:100%">';
            $message .= '<thead>';
            $message .= '<tr>';
            $message .= '<th>#</th>';
            $message .= '<th>Test Id</th>';
            $message .= '<th>Test Name</th>';
            $message .= '<th>Cost(₦)</th>';
                   
            $message .= '</tr>';
            $message .= '</thead>';
            $message .= '<tbody>';
			$total_price = 0;
			$tests = $this->getTestsSelectedByInitiationCodeAndHealthFacilityIdPhlebotomist($health_facility_id,$initiation_code);
										
			if(is_array($tests)){
				$i = 0;
				foreach($tests as $test){
					$i++;
					$id = $test->id;
					
					$test_id = $test->test_id;
					$test_name = $test->test_name;
					$test_cost = $test->price;
					$ta_time = $test->ta_time;
					$total_price += $test_cost;

	                $message .= '<tr>';

	                $message .= '<td>'.$i.'</td>';
	               
	                $message .= '<td class="test-name">'.$test_id.'</td>';
	                $message .= '<td class="patient-name">'.$test_name.'</td>';
	                $message .= '<td class="test-cost">'.$test_cost.'</td>';
	                $message .= '</tr>';
	 
				}
			}
			
			$message .= '</tbody>';
      		$message .= '</table>';      
    		$message .= '</div>';
   
    		$date =	date("j M Y");
			$time = date("H:i:s");

			$notif_array = array(
				'sender' => $sender,
				'receiver' => $receiver,
				'title' => $title,
				'message' => $message,
				'date_sent' => $date,
				'time_sent' => $time
			);

			if($patients_email != ""){
				$recepient_arr = array($patients_email);
				$this->sendEmail($recepient_arr,$title,$message);
			}
			$this->sendMessage($notif_array);
			$patients_full_name = $patients_title . " " . $patients_first_name . " " . $patients_last_name;
			$from = $health_facility_name;
			$to = array($patients_phone_number);
			$body = $patients_full_name . " This Is To Inform You That The Sample(s) For Test(s) With Initiation Code ".$initiation_code.". And Lab Id: ".$lab_id." Has Been Rejected. Please Send New Sample(s) For This Test(s). Visit " . site_url(). " To Find Out More.";
			// $body = "Test message";
			$this->sendFacilitySms($health_facility_id,$from,$to,$body);

		}

		public function getTestsSelectedByInitiationCodeAndHealthFacilityIdPhlebotomist($health_facility_id,$initiation_code){
			$this->db->select("*");
			$this->db->from("lab_facility_initiations");
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->where('initiation_code',$initiation_code);
			$this->db->where('sampled',0);
			$this->db->where('sub_dept_id !=',6);
			$this->db->where('paid',1);
			// $this->db->where('patient_username',$user_name);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getAllPatientsBioData($user_id){
			$query = $this->db->get_where("patients",array('user_id' => $user_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{

			}
		}

		public function getInitiationCodeByLabId($health_facility_id,$lab_id){
			$query = $this->db->get_where("lab_facility_initiations",array('lab_id' => $lab_id,'health_facility_id' => $health_facility_id),1);
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
				}
				return $initiation_code;
			}
		}

		public function checkIfTheseSetOfTestsAreValidToBeWorkedOnByPhlebotomist($health_facility_id,$lab_id){
			$this->db->select("time");
			$this->db->from("lab_facility_initiations");
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->where('lab_id',$lab_id);
			$this->db->where('sampled',0);
			$this->db->where('sub_dept_id !=',6);
			$this->db->where('paid',1);
			// $this->db->where('patient_username',$user_name);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getLastPaymentDateAndTimePhlebotomist($health_facility_id,$initiation_code){
			// $query = $this->db->get_where("teller_records",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));

			$this->db->select("date,time");
			$this->db->from("teller_records");
			$this->db->where("initiation_code",$initiation_code);
			
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->order_by("id","DESC");
			$this->db->limit(1);

			$query = $this->db->get();

			if($query->num_rows() > 0){
				return $query->result()[0]->date . " " . $query->result()[0]->time;
			}
		}

		public function getTestNumByInitiationCodePhlebotomist($health_facility_id,$initiation_code){
			$this->db->select("time");
			$this->db->from("lab_facility_initiations");
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->where('initiation_code',$initiation_code);
			$this->db->where('sub_dept_id !=',6);
			$this->db->where('paid',1);
			// $this->db->where('patient_username',$user_name);
			$query = $this->db->get();
			return $query->num_rows();
		}

		public function getAllInitiationCodesForFacilityPhlebotomist($health_facility_id){
			$this->db->select("initiation_code");
			$this->db->from("lab_facility_initiations");
			
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->where('sampled',0);
			$this->db->where('sub_dept_id !=',6);
			$this->db->where('paid',1);
			$this->db->order_by('id','DESC');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getTestNumByInitiationCode1($health_facility_id,$initiation_code){
			$this->db->select("time");
			$this->db->from("lab_facility_initiations");
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->where('initiation_code',$initiation_code);
			// $this->db->where('patient_username',$user_name);
			$query = $this->db->get();
			return $query->num_rows();
		}

		public function getAllInitiationCodesForFacility($health_facility_id){
			$this->db->select("initiation_code");
			$this->db->from("lab_facility_initiations");
			
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->order_by('id','DESC');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getInitiatedPatientByLabId($health_facility_id,$lab_id){
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id),1);
			// echo $this->db->last_query();
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getPdfTestsRows($health_facility_id,$lab_id){
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id,'paid' => 1));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function checkIfLabIdIsValid($health_facility_id,$lab_id){
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'lab_id' => $lab_id));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function sendPatientNotifsOnTestPaymentCompleted($health_facility_id,$initiation_code,$receipt_file,$amount_paid,$balance,$date,$time){

			$health_facility_name = $this->getFacilityParamById("name",$health_facility_id);
			$sender = $health_facility_name;
			$patient_user_id = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("user_id",$initiation_code,$health_facility_id);
			$patient_user_name = $this->getUserNameById($patient_user_id);
			$patients_email = $this->getUserParamById("email",$patient_user_id);
			$receiver = $patient_user_name;
			
			
			$patients_phone_number = $this->getUserFullPhoneNumberById($patient_user_id);
			$patients_title = $this->getPatientParamByUserId("title",$patient_user_id);
			$patients_first_name = $this->getPatientParamByUserId("first_name",$patient_user_id);
			$patients_last_name = $this->getPatientParamByUserId("last_name",$patient_user_id);

			$tests_selected_date = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("date",$initiation_code,$health_facility_id);
			$tests_selected_time = $this->getLabFacilityParamByInitiationCodeAndHealthFacilityId("time",$initiation_code,$health_facility_id);
			
			$i = 0;
			$title = "Payment For Tests Accepted";
    		$message = "This Is To Alert You That The Payment For Your Test(s) Selected In " . $health_facility_name ." On " .$tests_selected_date . " ". $tests_selected_time ." Has Been Processed. View Your Receipt <a target='_blank' href='".base_url('assets/images/'.$receipt_file)."'>Here</a> For More Information"; 	

    		$notif_array = array(
				'sender' => $sender,
				'receiver' => $receiver,
				'title' => $title,
				'message' => $message,
				'date_sent' => $date,
				'time_sent' => $time
			);

			if($patients_email != ""){
				$recepient_arr = array($patients_email);
				$this->sendEmail($recepient_arr,$title,$message);
			}
			$this->sendMessage($notif_array);
			$patients_full_name = $patients_title . " " . $patients_first_name . " " . $patients_last_name;
			$from = $health_facility_name;
			$to = array($patients_phone_number);
			$body = $patients_full_name . "This Is To Alert You That The Payment For Your Test(s) Selected On " .$tests_selected_date . " ". $tests_selected_time ." Has Been Processed. View Your Receipt <a target='_blank' href='".base_url('assets/images/'.$receipt_file)."'>Here</a>";
			// $body = "Test message";
			$this->sendFacilitySms($health_facility_id,$from,$to,$body);
			
		}

		public function getLastRowTestResult($health_facility_id){
			$this->db->select_max('lab_id');
			$this->db->from("lab_facility_initiations");
			$this->db->where("health_facility_id",$health_facility_id);
			$query = $this->db->get();
			if($query->num_rows() == 1){
				
				return $query->result();
			}else{
				return false;
			}
		}

		public function getPatientLabIdByInitiationCode($health_facility_id,$initiation_code){
	    	$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code),1);
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$lab_id = $row->lab_id;
	    		}
	    		return $lab_id;
	    	}else{
	    		return false;
	    	}
	    }


		public function getTotalPriceForTestsByInitiationCodeTakingIntoAccountPartFee($health_facility_id,$initiation_code){
			$total_price = 0;
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$id = $row->id;
					$price = $row->price;
					$total_price += $price;
				}
			}

			$patients_user_id = $this->onehealth_model->getLabFacilityParamByInitiationCodeAndHealthFacilityId("user_id",$initiation_code,$health_facility_id);
			$patient_facility_id = $this->onehealth_model->getPatientFacilityParamByUserId("id",$health_facility_id,$patients_user_id);
			$initiation_user_type = $this->onehealth_model->getLabFacilityParamByInitiationCodeAndHealthFacilityId("user_type",$initiation_code,$health_facility_id);
			$initiation_insurance_code = $this->onehealth_model->getLabFacilityParamByInitiationCodeAndHealthFacilityId("insurance_code",$initiation_code,$health_facility_id);
			$user_type = $this->onehealth_model->getPatientFacilityParamById("user_type",$patient_facility_id);
			$insurance_code = $this->onehealth_model->getPatientFacilityParamById("insurance_code",$patient_facility_id);
			$part_payment_percent = $this->onehealth_model->getLabFacilityParamByInitiationCodeAndHealthFacilityId("part_payment_percent",$initiation_code,$health_facility_id);
			$discount = $this->onehealth_model->getLabFacilityParamByInitiationCodeAndHealthFacilityId("discount",$initiation_code,$health_facility_id);

			if($initiation_user_type == "pfp"){
				$balance = $total_price - (($part_payment_percent / 100) * $total_price);
				return $balance - (($discount / 100) * $balance);
			}else{
				if($initiation_user_type == ""){
					$form_array = array(
						'user_type' => "fp"
					);
					$this->updateTestInitiation($health_facility_id,$initiation_code,$form_array);
				}
				return $total_price - (($discount / 100) * $total_price);
			}
		}

		public function updateTestInitiation($health_facility_id,$initiation_code,$form_array){
			return $this->db->update("lab_facility_initiations",$form_array,array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
		}

		public function getIfInitiationCodeIsValid($health_facility_id,$initiation_code){
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getPatientFacilityParamByUserId($param,$health_facility_id,$patient_user_id){
			$query = $this->db->get_where("patients_in_facility",array('health_facility_id' => $health_facility_id,'user_id' => $patient_user_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}
		}

		public function getTotalPriceForTestsByInitiationCode($health_facility_id,$initiation_code){
			$query = $this->db->get_where("lab_facility_initiations",array('initiation_code' => $initiation_code,'health_facility_id' => $health_facility_id));
			// echo $initiation_code;
			if($query->num_rows() > 0){
				$total_cost = 0;
				foreach($query->result() as $row){
					$cost = $row->price;
					$total_cost += $cost;
				}
				return $total_cost;
			}else{
				return false;
			}
		}


		public function getLabFacilityParamByInitiationCodeAndHealthFacilityId($param,$initiation_code,$health_facility_id){
			$query = $this->db->get_where("lab_facility_initiations",array('initiation_code' => $initiation_code,'health_facility_id' => $health_facility_id),1);
			if($query->num_rows() == 1){
				
					return $query->result()[0]->$param;
				
			}else{
				return false;
			}
		}

		public function getPatientFullNameByInitiationCode($health_facility_id,$initiation_code){
	    	$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code),1);
	    	if($query->num_rows() == 1){
	    		$patient_user_id = $query->result()[0]->user_id;
	    		$patients_title = $this->getPatientParamByUserId("title",$patient_user_id);
				$patients_first_name = $this->getPatientParamByUserId("first_name",$patient_user_id);
				$patients_last_name = $this->getPatientParamByUserId("last_name",$patient_user_id);

				$patients_full_name = $patients_title . " " . $patients_first_name . " " . $patients_last_name;
				return $patients_full_name;
	    	}else{
	    		return false;
	    	}
	    }


		public function getPatientsTestsByInitiationCode($health_facility_id,$initiation_code){
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'referring_facility_id' => 0));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function generateInitiationCodeLab($health_facility_id,$user_id){
			$code_date = date("j");
			$code_time = date("h");
			
			while (true) {
				$initiation_code = substr(bin2hex($this->encryption->create_key(8)),4). '-' . $code_date .'-' . $code_time;
				if(!$this->checkIfInitiationCodeHasBeenTakenBeforeInThisFacility($health_facility_id,$initiation_code)){
					return $initiation_code;
				}else{
					continue;
				}
			}
			
		}

		public function checkIfInitiationCodeHasBeenTakenBeforeInThisFacility($health_facility_id,$initiation_code){
			$query = $this->db->get_where("lab_facility_initiations",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function get_set_of_all_tests_by_initiation_code($health_facility_id,$initiation_code){
			
			$query = $this->db->get_where("lab_facility_initiations",array('initiation_code' => $initiation_code,'health_facility_id' => $health_facility_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
			
		}


		public function createTestRecord($form_array,$last_iteration = false){
			if($this->db->insert('lab_facility_initiations',$form_array)){
				if($last_iteration){
					$health_facility_id = $form_array['health_facility_id'];
					$health_facility_name = $this->getFacilityParamById("name",$health_facility_id);
					$sender = $health_facility_name;
					$patient_user_id = $form_array['user_id'];
					$patient_user_name = $this->getUserNameById($patient_user_id);
					$patients_email = $this->getUserParamById("email",$patient_user_id);
					$receiver = $patient_user_name;
					$date = $form_array['date'];
					$time = $form_array['time'];
					$initiation_code = $form_array['initiation_code'];
					$patients_phone_number = $this->getUserFullPhoneNumberById($patient_user_id);
					$patients_title = $this->getPatientParamByUserId("title",$patient_user_id);
					$patients_first_name = $this->getPatientParamByUserId("first_name",$patient_user_id);
					$patients_last_name = $this->getPatientParamByUserId("last_name",$patient_user_id);
					$tests = $this->onehealth_model->get_set_of_all_tests_by_initiation_code($health_facility_id,$initiation_code);
					$i = 0;
					$title = "Tests Selected";
	        		$message = "This Is To Notify You That The Following Test(s) Where Selected For You In ".$health_facility_name." On " . $date . " At " .$time . " With Initiation Code " . $initiation_code;
	       	
			       	$message .= '<div class="table-div material-datatables table-responsive" style="">';
		            $message .= '<table class="table table-test table-striped table-bordered nowrap hover display" id="example" cellspacing="0" width="100%" style="width:100%" data-ini-code="<?php echo $initiation_code; ?>">';
		            $message .= '<thead>';
		            $message .= '<tr>';
		            $message .= '<th>#</th>';
		            $message .= '<th>Test Name</th>';
		            $message .= '<th>Department</th>';
		            $message .= '<th>Cost(₦)</th>';
		                   
		            $message .= '</tr>';
		            $message .= '</thead>';
		            $message .= '<tbody>';
					$total_price = 0;
					foreach($tests as $test){
						$i++;
						$id = $test->id;
						$test_name = $test->test_name;
						$test_cost = $test->price;
						$sub_dept_id = $test->sub_dept_id;
						$sub_dept_name = $this->onehealth_model->getSubDeptNameById($sub_dept_id);
						$total_price += $test_cost;

		                $message .= '<tr>';

		                $message .= '<td>'.$i.'</td>';
		               
		                $message .= '<td class="test-name">'.$test_name.'</td>';
		                $message .= '<td class="sub-dept-name">'.$sub_dept_name.'</td>';
		                $message .= '<td class="test-cost">'.$test_cost.'</td>';
		                $message .= '</tr>';
					}
					
					$message .= '</tbody>';
	          		$message .= '</table>';      
	        		$message .= '</div>';

	        		$notif_array = array(
	    				'sender' => $sender,
	    				'receiver' => $receiver,
	    				'title' => $title,
	    				'message' => $message,
	    				'date_sent' => $date,
	    				'time_sent' => $time
	    			);

	    			if($patients_email != ""){
	    				$recepient_arr = array($patients_email);
	    				$this->sendEmail($recepient_arr,$title,$message);
	    			}
	    			$this->sendMessage($notif_array);
	    			$patients_full_name = $patients_title . " " . $patients_first_name . " " . $patients_last_name;
	    			$from = $health_facility_name;
					$to = array($patients_phone_number);
					$body = $patients_full_name . " This Is To Alert You That " . $i . " Test(s) Have Been Selected For You At " . $time . " With Initiation Code: ".$initiation_code.". Login To Your Account At " . site_url() . " To View Your Tests.";
					// $body = "Test message";
					$this->sendFacilitySms($health_facility_id,$from,$to,$body);
				}
												       
			}
		}

		public function getPatientFacilityParamById($param,$patient_facility_id){
			$query = $this->db->get_where("patients_in_facility",array('id' => $patient_facility_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}else{
				return false;
			}
		}

		public function checkIfThisPatientIsRegisteredInThisFacility($health_facility_id,$patient_facility_id){
			$query = $this->db->get_where("patients_in_facility",array('health_facility_id' => $health_facility_id,'id' => $patient_facility_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}



		public function getPatientAge($dob){
			$val = "";
			$orig_date = $dob;

			$orig_date = date("j M Y h:i:sa",strtotime($orig_date));
			$orig_date = strtotime($orig_date);
			$curr_date = strtotime(date("j M Y h:i:sa"));
			$date_diff_secs = $curr_date - $orig_date;
			

			$seconds = $curr_date;
		        
		    $date_diff_seconds = $date_diff_secs;
		    
		    if($date_diff_seconds > 0){
		      $date_diff_minutes = floor($date_diff_seconds / 60);
		      $date_diff_hours = floor($date_diff_minutes / 60);
		      $date_diff_days = floor($date_diff_hours / 24);
		      $date_diff_weeks = floor($date_diff_days / 7);
		      $date_diff_months = floor($date_diff_weeks / 4.345);
		      $date_diff_years = floor($date_diff_months / 12);
		      
		      if($date_diff_minutes < 1){
		        $val = "";
		      }else if($date_diff_hours < 1){
		        $val = $date_diff_minutes . " minute(s)";
		      }else if($date_diff_days < 1){
		        $val = $date_diff_hours . " hour(s)";
		      }else if($date_diff_weeks < 1){
		        $val = $date_diff_days . " day(s)";
		      }else if($date_diff_months < 1){
		        $val = $date_diff_weeks . " week(s)";
		      }else if($date_diff_years < 1){
		        $val = $date_diff_months . " month(s)";
		      }else{
		        $val = $date_diff_years . " year(s)";
		      }
		    }else{
		      return false;
		    }

		    return $val;
		}

		public function getPatientAgeAndUnit($dob){
			$val = "";
			$orig_date = $dob;

			$orig_date = date("j M Y h:i:sa",strtotime($orig_date));
			$orig_date = strtotime($orig_date);
			$curr_date = strtotime(date("j M Y h:i:sa"));
			$date_diff_secs = $curr_date - $orig_date;
			

			$seconds = $curr_date;
		        
		    $date_diff_seconds = $date_diff_secs;
		    
		    if($date_diff_seconds > 0){
		      $date_diff_minutes = floor($date_diff_seconds / 60);
		      $date_diff_hours = floor($date_diff_minutes / 60);
		      $date_diff_days = floor($date_diff_hours / 24);
		      $date_diff_weeks = floor($date_diff_days / 7);
		      $date_diff_months = floor($date_diff_weeks / 4.345);
		      $date_diff_years = floor($date_diff_months / 12);
		      
		      if($date_diff_minutes < 1){
		        return false;
		      }else if($date_diff_hours < 1){
		        $ret_arr = array(
		        	'age' => $date_diff_minutes,
		        	'age_unit' => "minutes"
		        );
		      }else if($date_diff_days < 1){
		        $ret_arr = array(
		        	'age' => $date_diff_hours,
		        	'age_unit' => "hours"
		        );
		      }else if($date_diff_weeks < 1){
		        $ret_arr = array(
		        	'age' => $date_diff_days,
		        	'age_unit' => "days"
		        );
		      }else if($date_diff_months < 1){
		        $ret_arr = array(
		        	'age' => $date_diff_weeks,
		        	'age_unit' => "weeks"
		        );
		      }else if($date_diff_years < 1){
		        $ret_arr = array(
		        	'age' => $date_diff_months,
		        	'age_unit' => "months"
		        );
		      }else{
		        $ret_arr = array(
		        	'age' => $date_diff_years,
		        	'age_unit' => "years"
		        );
		      }
		    }else{
		      return false;
		    }

		    return $ret_arr;
		}


		public function loadPreviouslyRegisteredPatients($health_facility_id){
			$this->db->select("*");
			$this->db->from("patients_in_facility");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->order_by("id","DESC");

			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getPatientParamByUserId($param,$patient_user_id){
			$query = $this->db->get_where("patients",array('user_id' => $patient_user_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}
		}

		public function sendFacilitySms($health_facility_id,$from,$to,$body){
			if($this->getFacilityParamById("sms_enabled",$health_facility_id) == 1){
				if(is_array($to)){
					$to = implode(",", $to);
					$use_post = true;
					$api_token = "RbmFJ0QkMM7Lkf6ig8ET39aRyIYSyrklR7rvJQo0Q4dXJPBVcnNOUikLUh4M";
					$post_data = [
						"api_token" => $api_token,
						"from" => $from,
						"body" => $body,
						"to" => $to
					];
					



					$url = "https://www.bulksmsnigeria.com/api/v1/sms/create";
					// $url = site_url("onehealth/testing123");
					// $url .= "api_token=". $api_token."&from=".$from."&body=".$body."&to=".$to;
					
					// echo $url;
					$response = $this->curl($url, $use_post, $post_data);
					// var_dump($response);
					if($this->isJson($response)){
						return true;
					}
				}
			}
		}

		public function getUserFullPhoneNumberById($user_id){
			$phone_code = $this->getUserParamById("phone_code",$user_id);
			$phone = $this->getUserParamById("phone",$user_id);
			if(substr($phone,0,1) == 0){
				$phone = substr($phone,1);
			}
			return "+" . $phone_code . "" . $phone;
		}


		public function createPatientFacilityAccount($patient_facility_array){
			$query = $this->db->insert('patients_in_facility',$patient_facility_array);
			if($query){
				$patients_user_id = $patient_facility_array['user_id'];
				$health_facility_id = $patient_facility_array['health_facility_id'];
				$date = $patient_facility_array['date_created'];
				$time = $patient_facility_array['time_created'];
				$health_facility_name = $this->getHealthFacilityParamById("name",$health_facility_id);
				$patients_phone_number = $this->getUserFullPhoneNumberById($patients_user_id);
				$patients_email = $this->getUserParamById("email",$patients_user_id);
				$patients_user_name = $this->getUserNameById($patients_user_id);

				$sender = $health_facility_name;
    			$receiver = $patients_user_name;
    			$title = "Welcome To Our Facility";
    			$message = "Welcome To " . $health_facility_name . ". Explore Our Offers.";
    			
    			$date_sent = $date;
    			$time_sent = $time;
    			$notif_array = array(
    				'sender' => $sender,
    				'receiver' => $receiver,
    				'title' => $title,
    				'message' => $message,
    				'date_sent' => $date_sent,
    				'time_sent' => $time_sent
    			);

    			if($patients_email != ""){
    				$recepient_arr = array($patients_email);
    				$this->sendEmail($recepient_arr,$title,$message);
    			}
    			$this->sendMessage($notif_array);

				return true;
			}else{
				return false;
			}
		}


		public function createPatientAccount($patient_array){
			$query = $this->db->insert('patients',$patient_array);
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function generateNewFacilityRegistrationNumber($health_facility_id){
			while (true) {
				$digits = 5;
				$rand = rand(pow(10, $digits-1), pow(10, $digits)-1);;
				$query = $this->db->get_where("patients_in_facility",array('health_facility_id' => $health_facility_id,'registration_num' => $rand));
				if($query->num_rows() == 1){
					continue;
				}else{
					return $rand;
				}
			}
		}

		public function sendSms($from,$to,$body){
			if(is_array($to)){
				$to = implode(",", $to);
				$use_post = true;
				$api_token = "RbmFJ0QkMM7Lkf6ig8ET39aRyIYSyrklR7rvJQo0Q4dXJPBVcnNOUikLUh4M";
				$post_data = [
					"api_token" => $api_token,
					"from" => $from,
					"body" => $body,
					"to" => $to
				];
				



				$url = "https://www.bulksmsnigeria.com/api/v1/sms/create";
				// $url = site_url("onehealth/testing123");
				// $url .= "api_token=". $api_token."&from=".$from."&body=".$body."&to=".$to;
				
				// echo $url;
				$response = $this->curl($url, $use_post, $post_data);
				// var_dump($response);
				if($this->isJson($response)){
					$response = json_decode($response);
					if($response->data->status == "success"){
						return true;
					}
				}
			}
		}

		public function getPersonnelNumFirstLabOfficers($health_facility_id,$first_officer_id){
			$query = $this->db->get_where("personnel_officers",array('health_facility_id' => $health_facility_id,'personnel_id' => $first_officer_id,'type' => 'lab_first_officers'));
			return $query->num_rows();
		}

		public function getFirstLabOfficerParamById($param,$first_officer_id){
			$query = $this->db->get_where("lab_first_officers",array('id' => $first_officer_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}
		}

		public function getUserPositionFirstLabOfficers($health_facility_id,$user_id,$first_officer_id){
			if($this->checkIfUserIsAdminOfFacility1($health_facility_id,$user_id)){
				return "admin";
			}else{
				if($this->checkIfUserIsAPersonnelFirstLabOfficer($health_facility_id,$user_id,$first_officer_id)){
					return "personnel";
				}else{
					return false;
				}
			}
		}

		public function checkIfUserIsAPersonnelFirstLabOfficer($health_facility_id,$user_id,$first_officer_id){
			$query = $this->db->get_where("personnel_officers",array('health_facility_id' => $health_facility_id,'user_id' => $user_id,'type' => 'lab_first_officer','personnel_id' => $first_officer_id));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfUserIsValidToEnterFirstLabOfficerPersonnelPage($health_facility_id,$user_id,$first_officer_id){
			if($this->checkIfUserIsAdminOfFacility1($health_facility_id,$user_id)){
				return true;
			}else{
				if($this->onehealth_model->checkIfUserIsAPersonnelFirstLabOfficer($health_facility_id,$user_id,$first_officer_id)){
					return true;
				}else{
					return false;
				}
			}
		}

		public function getFirstLabOfficersParamById($param,$lab_officer_id){
			$query = $this->db->get_where("lab_first_officers",array('id' => $lab_officer_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}
		}


		public function getHealthFacilityParamById($param,$health_facility_id){
			$query = $this->db->get_where("health_facility",array('id' => $health_facility_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}
		}

		public function getSubDeptIdByPersonnelId($personnel_id){
			$query = $this->db->get_where("personnel",array('id' => $personnel_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->sub_dept_id;
			}
		}


		public function getUserRoles(){
			$user_id = $this->getUserIdWhenLoggedIn();
			$is_patient = $this->getUserParamById("is_patient",$user_id);
			$is_admin = $this->getUserParamById("is_admin",$user_id);
			if($is_patient == 0){
				$admin_roles = array();
				$sub_admin_roles = array();
				$personnel_roles = array();
				if($is_admin == 1){
					$admin_facility_id = $this->getUserParamById("admin_facility_id",$user_id);
					$admin_facility_slug = $this->getHealthFacilityParamById("slug",$admin_facility_id);
					$admin_roles = array(
						'facility_id' => $admin_facility_id,
						'facility_slug' => $admin_facility_slug
					);
				}
				$query = $this->db->get_where("sub_admin_officers",array('user_id' => $user_id));
				if($query->num_rows() > 0){
					for($i = 0; $i < count($query->result()); $i++){
						$query->result()[$i]->role = "sub_admin";
					}
					$sub_admin_roles = $query->result();
				}

				$query = $this->db->get_where("personnel_officers",array('user_id' => $user_id));
				if($query->num_rows() > 0){
					for($i = 0; $i < count($query->result()); $i++){
						$query->result()[$i]->role = "personnel";
					}
					$personnel_roles = $query->result();
				}
				return array(
					'admin_roles' => $admin_roles,
					'sub_admin_roles' => $sub_admin_roles,
					'personnel_roles' => $personnel_roles
				);
			}
		}


		public function deletePersonnel($personnel_id,$personnels_id,$health_facility_id){
			return $this->db->delete("personnel_officers",array('health_facility_id' => $health_facility_id,'personnel_id' => $personnel_id,'id' => $personnels_id));
		}

		public function checkIfUserIsTrulyAPersonnelHere($personnel_id,$personnels_id,$health_facility_id){
	    	$query = $this->db->get_where("personnel_officers",array('health_facility_id' => $health_facility_id,'id' => $personnels_id,'personnel_id' => $personnel_id));
	    	if($query->num_rows() == 1){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

		public function getPersonnelForSubDept($health_facility_id,$personnel_id){
			$query = $this->db->get_where("personnel_officers",array('type' => '','health_facility_id' => $health_facility_id,'personnel_id' => $personnel_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function addPersonnelFunctionalityToUser($health_facility_id,$user_id,$personnel_id,$sub_dept_id,$dept_id,$date,$time){
			$health_facility_name = $this->getHealthFacilityNameById($health_facility_id);
			$dept_name = $this->getDeptNameById($dept_id);
			$sub_dept_name = $this->getSubDeptNameById($sub_dept_id);
			$personnel_name = $this->getPersonnelParamById("name",$personnel_id);
			$form_array = array(
				'health_facility_id' => $health_facility_id,
				'personnel_id' => $personnel_id,
				'user_id' => $user_id,
				'type' => '',
				'date' => $date,
				'time' => $time
			);
			if($this->db->insert("personnel_officers",$form_array)){
				$sender = $health_facility_name;
    			$receiver = $this->getUserNameById($user_id);
    			$title = "Appointment Notification";
    			$message = "This Is To Inform You That You Have Just Been Appointed As <em class='text-primary'>".$personnel_name."</em> In <em class='text-primary'>". $sub_dept_name ."</em> Dept. " . $health_facility_name . ". You Can Access Your New Functionalities Via Your Affiliated Facilities Page Or Click <a href='".site_url('onehealth/index/cl_admin/employer-health-facilities')."'>Here</a>";
    			
    			$date_sent = $date;
    			$time_sent = $time;
    			$notif_array = array(
    				'sender' => $sender,
    				'receiver' => $receiver,
    				'title' => $title,
    				'message' => $message,
    				'date_sent' => $date_sent,
    				'time_sent' => $time_sent
    			);
    			if($this->sendMessage($notif_array)){
    				return true;
    			}
			}
		}

		public function checkIfUserAlreadyHasThisPersonnelFunctionality($health_facility_id,$user_id,$personnel_id){
			$query = $this->db->get_where("personnel_officers",array('health_facility_id' => $health_facility_id,'user_id' => $user_id,'type' => '','personnel_id' => $personnel_id));
			if($query->num_rows() > 0){
				return false;
			}else{
				return true;
			}
		}
		
		public function getFirstPersonnelUserid($health_facility_id,$personnel_id){
			$query = $this->db->get_where("personnel_officers",array('health_facility_id' => $health_facility_id,'personnel_id' => $personnel_id),1);
			return $query->result()[0]->user_id;
		}


		public function getPersonnelNum($health_facility_id,$personnel_id){
			$query = $this->db->get_where("personnel_officers",array('health_facility_id' => $health_facility_id,'personnel_id' => $personnel_id,'type' => ''));
			return $query->num_rows();
		}

		public function deleteSubAdmin($sub_dept_id,$sub_admin_id,$health_facility_id){
			return $this->db->delete("sub_admin_officers",array('health_facility_id' => $health_facility_id,'personnel_id' => $sub_dept_id,'id' => $sub_admin_id));
		}

		public function checkIfUserIsASubAdminInFacility($sub_dept_id,$sub_admin_id,$health_facility_id){
	    	$query = $this->db->get_where("sub_admin_officers",array('health_facility_id' => $health_facility_id,'id' => $sub_admin_id,'personnel_id' => $sub_dept_id));
	    	if($query->num_rows() == 1){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

		public function addSubAdminFunctionalityToUser($health_facility_id,$user_id,$dept_id,$sub_dept_id,$sub_dept_name,$date,$time){
			$dept_name = $this->getDeptNameById($dept_id);
			$health_facility_name = $this->getHealthFacilityNameById($health_facility_id);
			$form_array = array(
				'health_facility_id' => $health_facility_id,
				'personnel_id' => $sub_dept_id,
				'user_id' => $user_id,
				'type' => '',
				'date' => $date,
				'time' => $time
			);
			if($this->db->insert("sub_admin_officers",$form_array)){
				$sender = $health_facility_name;
    			$receiver = $this->getUserNameById($user_id);
    			$title = "Appointment Notification";
    			$message = "This Is To Inform You That You Have Just Been Appointed As Sub Admin In <em class='text-primary'>".$sub_dept_name."</em> <em class='text-primary'>".$dept_name."</em> Dept. " . $health_facility_name . ". You Can Access Your New Functionalities Via Your Affiliated Facilities Page Or Click <a href='".site_url('onehealth/index/cl_admin/employer-health-facilities')."'>Here</a>";
    			
    			$date_sent = $date;
    			$time_sent = $time;
    			$notif_array = array(
    				'sender' => $sender,
    				'receiver' => $receiver,
    				'title' => $title,
    				'message' => $message,
    				'date_sent' => $date_sent,
    				'time_sent' => $time_sent
    			);
    			if($this->sendMessage($notif_array)){
    				return true;
    			}
			}
		}

		public function checkIfUserAlreadyHasThisSubAdminFunctionality($health_facility_id,$user_id,$sub_dept_id){
			$query = $this->db->get_where("sub_admin_officers",array('health_facility_id' => $health_facility_id,'user_id' => $user_id,'personnel_id' => $sub_dept_id));
			if($query->num_rows() > 0){
				return false;
			}else{
				return true;
			}
		}

		public function addSubAdmin1($form_array){
			return $this->db->insert("sub_admin_officers",$form_array);
		}


		public function getFirstSubAdminUserid($health_facility_id,$sub_dept_id){
			$query = $this->db->get_where("sub_admin_officers",array('health_facility_id' => $health_facility_id,'personnel_id' => $sub_dept_id),1);
			return $query->result()[0]->user_id;
		}


		public function getSubAdminsNum($health_facility_id,$sub_dept_id){
			$query = $this->db->get_where("sub_admin_officers",array('health_facility_id' => $health_facility_id,'personnel_id' => $sub_dept_id));
			return $query->num_rows();
		}


		public function getNumberOfSubAdmins($health_facility_id,$lab_officer_id){
			$query = $this->db->get_where("personnel_officers",array('health_facility_id' => $health_facility_id,'personnel_id' => $lab_officer_id,'type' => 'lab_first_officer'));
			return $query->num_rows();
		}


		public function checkForSubAdmin($health_facility_id,$dept_id,$sub_dept_id){
			$query = $this->db->get_where("sub_admin_officers",array('health_facility_id' => $health_facility_id,'personnel_id' => $sub_dept_id));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}


		public function getPersonnelByDeptIdAndSubDeptId($dept_id,$sub_dept_id){
			$query = $this->db->get_where('personnel',array('dept_id' => $dept_id,'sub_dept_id' => $sub_dept_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}


		public function addPersonnelFunctionalityToUserFirstOfficers($health_facility_id,$user_id,$first_officer_id,$first_officer_name,$date,$time){
			$health_facility_name = $this->getHealthFacilityNameById($health_facility_id);
			$form_array = array(
				'health_facility_id' => $health_facility_id,
				'personnel_id' => $first_officer_id,
				'user_id' => $user_id,
				'type' => 'lab_first_officer',
				'date' => $date,
				'time' => $time
			);
			if($this->db->insert("personnel_officers",$form_array)){
				$sender = $health_facility_name;
    			$receiver = $this->getUserNameById($user_id);
    			$title = "Appointment Notification";
    			$message = "This Is To Inform You That You Have Just Been Appointed As <em class='text-primary'>".$first_officer_name."</em> In " . $health_facility_name . ". You Can Access Your New Functionalities Via Your Affiliated Facilities Page Or Click <a href='".site_url('onehealth/index/cl_admin/employer-health-facilities')."'>Here</a>";
    			
    			$date_sent = $date;
    			$time_sent = $time;
    			$notif_array = array(
    				'sender' => $sender,
    				'receiver' => $receiver,
    				'title' => $title,
    				'message' => $message,
    				'date_sent' => $date_sent,
    				'time_sent' => $time_sent
    			);
    			if($this->sendMessage($notif_array)){
    				return true;
    			}
			}
		}


		public function checkIfUserIsAdminOrSubAdmin($health_facility_id,$user_id,$sub_dept_slug,$dept_slug){
			if($this->checkIfUserIsAdminOfFacility1($health_facility_id,$user_id)){
				return true;
			}else{
				if($this->checkIfUserIsSubAdminOfSubDeptInFacility($health_facility_id,$user_id,$sub_dept_slug,$dept_slug)){
					return true;
				}else{
					return false;
				}
			}
		}

		public function getUserPositionAtSubDept($health_facility_id,$user_id,$sub_dept_slug,$dept_slug){
			if($this->checkIfUserIsAdminOfFacility1($health_facility_id,$user_id)){
				return "admin";
			}else{
				if($this->checkIfUserIsSubAdminOfSubDeptInFacility($health_facility_id,$user_id,$sub_dept_slug,$dept_slug)){
					return "sub_admin";
				}else{
					return false;
				}
			}
		}


		


		public function checkIfUserIsAdminOfFacility1($health_facility_id,$user_id){
			$is_admin = $this->getUserParamById("is_admin",$user_id);
			$admin_facility_id = $this->getUserParamById("admin_facility_id",$user_id);
			if($is_admin == 1){
				if($admin_facility_id == $health_facility_id){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}	
		}


		public function checkIfUserAlreadyHasThisPersonnelFunctionalityFirstLabOfficers($health_facility_id,$user_id,$first_officer_id){
			$query = $this->db->get_where("personnel_officers",array('health_facility_id' => $health_facility_id,'user_id' => $user_id,'type' => 'lab_first_officer','personnel_id' => $first_officer_id));
			if($query->num_rows() > 0){
				return false;
			}else{
				return true;
			}
		}


		public function getAllHealthFaciitiesThatAreHospitals(){
			$query = $this->db->get_where("health_facility",array('facility_structure' => 'hospital'));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getNumberOfLabFirstOfficersPersonnel($health_facility_id,$lab_officer_id){
			$query = $this->db->get_where("personnel_officers",array('health_facility_id' => $health_facility_id,'personnel_id' => $lab_officer_id,'type' => 'lab_first_officer'));
			return $query->num_rows();
		}

		public function getLabFirstOfficersPersonnelUserid($health_facility_id,$lab_officer_id){
			$query = $this->db->get_where("personnel_officers",array('health_facility_id' => $health_facility_id,'personnel_id' => $lab_officer_id,'type' => 'lab_first_officer'),1);
			return $query->result()[0]->user_id;
		}

		public function getFirstOfficersBooleanBySlug($slug){

			$query = $this->db->get_where('lab_first_officers',array('slug' => $slug));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getFirstLabOfficerIdBySlug($slug){
			$query = $this->db->get_where("lab_first_officers",array('slug' => $slug));
			if($query->num_rows() == 1){
				return $query->result()[0]->id;
			}
		}

		public function getFirstOfficerParamById($param,$first_officer_id){
			$query = $this->db->get_where("lab_first_officers",array('id' => $first_officer_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}
		}

		public function getAllCountryCodes(){
			$ret = array();
			$query = $this->db->get("country_codes");
			if($query->num_rows() > 0){
				return $query->result();
			}
			return $ret;
		}

		public function checkIfThisPhoneNumberHasBeenUsedBefore($phone_number,$phone_code){
			if(substr($phone_number, 0,1) == 0){
    			$phone_number = substr($phone_number, 1);
    		}
    		$query = $this->db->get_where("users",array('phone_code' => $phone_code,'phone' => $phone_number));
    		if($query->num_rows() > 0){
    			return true;
    		}else{
    			return false;
    		}
		}

		public function addPersonnel($form_array){
			return $this->db->insert("personnel_officers",$form_array);
		}

		public function checkIfUserIsAnAdminOfFacility1($health_facility_slug){
			$user_id = $this->getUserIdWhenLoggedIn();
			$health_facility_id = $this->getHealthFacilityIdBySlug($health_facility_slug);
			if($this->getUserParamById("is_admin",$user_id) == 1){
				$query = $this->db->get_where("health_facility",array('admin_id' => $user_id,'id' => $health_facility_id));
				if($query->num_rows() == 1){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		public function getLabFirstOfficersPersonnelForFacility($health_facility_id,$first_officer_id){
			$query = $this->db->get_where("personnel_officers",array('type' => 'lab_first_officer','health_facility_id' => $health_facility_id,'personnel_id' => $first_officer_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function checkIfUserIsAPersonnelInFacility($personnel_officers_id,$health_facility_id){
	    	$query = $this->db->get_where("personnel_officers",array('health_facility_id' => $health_facility_id,'id' => $personnel_officers_id));
	    	if($query->num_rows() == 1){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }


		public function getLabFirstOfficers(){
			$query = $this->db->get_where("lab_first_officers");
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function modifyPatientBioDataTable($table_name){
			$query_str = "ALTER TABLE ".$table_name." ADD `nationality` VARCHAR(200) NOT NULL AFTER `sex`, ADD `state_of_origin` VARCHAR(200) NOT NULL AFTER `nationality`, ADD `religion` VARCHAR(200) NOT NULL AFTER `state_of_origin`, ADD `occupation` VARCHAR(500) NOT NULL AFTER `religion`;";
			$query = $this->db->query($query_str);
			return $query;
		}

		public function runSchemaRecordsCheck(){
			$query_str = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'mortuary'";
			$query = $this->db->query($query_str);
			return $query->result();
		}
		

		public function get_client_ip() {
		    $ipaddress = '';
		    if (getenv('HTTP_CLIENT_IP'))
		        $ipaddress = getenv('HTTP_CLIENT_IP');
		    else if(getenv('HTTP_X_FORWARDED_FOR'))
		        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		    else if(getenv('HTTP_X_FORWARDED'))
		        $ipaddress = getenv('HTTP_X_FORWARDED');
		    else if(getenv('HTTP_FORWARDED_FOR'))
		        $ipaddress = getenv('HTTP_FORWARDED_FOR');
		    else if(getenv('HTTP_FORWARDED'))
		       $ipaddress = getenv('HTTP_FORWARDED');
		    else if(getenv('REMOTE_ADDR'))
		        $ipaddress = getenv('REMOTE_ADDR');
		    else
		        $ipaddress = 'UNKNOWN';
		    return $ipaddress;
		}

		public function testCreateTable(){
			$query_str = "CREATE TABLE test (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				user_name VARCHAR(40) NOT NULL,
				hashed VARCHAR(50) NOT NULL,
				position VARCHAR(50) NOT NULL,
				token TEXT NOT NULL,
				dept VARCHAR(100) NOT NULL,
				sub_dept VARCHAR(100) NOT NULL,
				personnel VARCHAR(100) NOT NULL,
				email VARCHAR(50) NOT NULL,
				phone BIGINT NOT NULL,
				country INT(11) NOT NULL,
				state INT(11) NOT NULL,
				address TEXT NOT NULL,
				slug VARCHAR(50) NOT NULL,
				register INT(11) NOT NULL,
				date VARCHAR(20) NOT NULL,
				time VARCHAR(20) NOT NULL
			)";
			if($this->db->query($query_str)){
				return true;
			}else{
				return false;
			}
		}

		//get departments
		public function getDepts(){
			$query = $this->db->get('dept');
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}


		public function getHealthFacilityInfoByUserId($user_id){
			$query = $this->db->get_where('health_facility',array('user_id' => $user_id),1);
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getHealthFacilityInfoId($id){
			$query = $this->db->get_where('health_facility',array('id' => $id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getHealthFacilityInfoBySlug($slug){
			$query = $this->db->get_where('health_facility',array('slug' => $slug));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function showAllWardsPersonnelIds(){
			$ret = "";
			$query = $this->db->get_where('personnel',array('dept_id' => 5));
			if($query->num_rows() > 0){
				$ret = "elseif(";
				foreach($query->result() as $row){
					$id = $row->id;
					echo $id."<br>";
					// $ret .= $id . "$personnel_id == ||";
				}
			}
			echo $ret;
		}

		public function getSubDeptsByDeptId($dept_id){
			$query = $this->db->get_where('sub_dept',array('dept_id' => $dept_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}



		public function getSubDeptById($sub_dept_id){
			$query = $this->db->get_where('sub_dept',array('id' => $sub_dept_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function addPersonnelToSubDept($personnel_arr,$sub_dept_id,$dept_id){
			if(is_array($personnel_arr)){
				for($i = 0; $i < count($personnel_arr); $i++){
					$name = $personnel_arr[$i];
					$slug = strtolower(url_title($name));
					$form_array = array(
						'name' => $name,
						'slug' => $slug,
						'dept_id' => $dept_id,
						'sub_dept_id' => $sub_dept_id
					);
					$this->db->insert('personnel',$form_array);
				}
			}
		}

		public function checkIfNotifExistsById($id){
			$query = $this->db->get_where('notif',array('id' => $id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfUserOwnsThisMessage($id,$user_name){
			$query = $this->db->get_where('notif',array('id' => $id,'receiver' => $user_name));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getNotifById($id){
			$query = $this->db->get_where('notif',array('id' => $id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getPersonnelBySubDeptIdAndDeptId($dept_id,$sub_dept_id){
			$query = $this->db->get_where('personnel',array('sub_dept_id' => $sub_dept_id,'dept_id' => $dept_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getUsersAndHealthFacilities($search_val){
			$this->db->select("a.name,b.user_name");
			$this->db->from('health_facility as a');
			$this->db->from('users as b');
			$this->db->like('a.name',$search_val,'after');
			$this->db->or_like('b.user_name',$search_val,'after');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}

		}

		public function getUserBySlug($slug){
			$query = $this->db->get_where('users',array('slug' => $slug));
			if($query->num_rows() == 1){
				return $query->num_rows();
			}else{
				return false;
			}
		}

		public function getUserById($slug){
			$query = $this->db->get_where('users',array('id' => $slug));
			if($query->num_rows() == 1){
				return $query->num_rows();
			}else{
				return false;
			}
		}

		public function getHealthFacilities($search_val){
			$this->db->select("*");
			$this->db->from("health_facility");
			$this->db->like("health_facility.name",$search_val,"after");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getFirstHealthFacilities($search_val){
			$this->db->select("*");
			$this->db->from("health_facility");
			$this->db->like("name",$search_val,"after");
			$this->db->limit(10);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getPaginationHealthFacilities($search_val){
			$this->db->select("*");
			$this->db->from("health_facility");
			$this->db->like("name",$search_val,"after");
			
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getFirstUsers($search_val){
			$this->db->select("*");
			$this->db->from("users");
			$this->db->like("user_name",$search_val,"after");
			
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getFirstPatients($search_val){
			$this->db->select("*");
			$this->db->from("users");
			$this->db->where('is_patient',1);
			$this->db->where('is_admin',0);
			$this->db->like("user_name",$search_val,"after");			
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getRealAffiliatedFacilities($affiliated_facilities){			
            if($affiliated_facilities != ""){
            	$affiliated_facilities_arr = explode(",", $affiliated_facilities);
            	$new_str = "";
            	for($i = 0; $i < count($affiliated_facilities_arr); $i++){
            		$health_facility_table_name = $affiliated_facilities_arr[$i];
            		if($this->db->table_exists($health_facility_table_name)){
	            		$query = $this->db->get($health_facility_table_name,1);
	            		if($query->num_rows() == 1){
	            			foreach($query->result() as $row){
	            				$health_facility_name = $row->facility_name;
	            				$new_str .= $health_facility_name .',';
	            			}
	            		}
	            	}
            		
            	}
            	return $new_str;
            }else{
            	return "";
            }
		}



		public function getHealthFacilityTableByDeptAndPosition($health_facility_table_name,$dept,$sub_dept){
			$query = $this->db->get_where($health_facility_table_name,array('dept' => $dept,'sub_dept' => $sub_dept,"personnel" => "",'position' => 'sub_admin' , 'is_admin' => 1));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function verifyDailyMaintenanceMortuary($form_array){
			return $this->db->insert("mortuary_daily_maintenance",$form_array);
		}

		public function getSubAdmins($health_facility_id,$sub_dept_id){
			$query = $this->db->get_where("sub_admin_officers",array('health_facility_id' => $health_facility_id,'personnel_id' => $sub_dept_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		

		public function getSubDeptSlugById($sub_dept_id,$dept_id){
			$query = $this->db->get_where('sub_dept',array('id' => $sub_dept_id,'dept_id' => $dept_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$sub_dept_slug = $row->slug;
				}
				return $sub_dept_slug;
			}else{
				return "";
			}
		}

		public function checkIfTestIdsAreExclusivelyScan($test_ids_arr){
			if(is_array($test_ids_arr)){
				$original_count = count($test_ids_arr);
				$new_count = 0;
				for($i = 0; $i < $original_count; $i++){
					$test_id = $test_ids_arr[$i];
					$test_id = strtolower($test_id);
					$first_two_char = substr($test_id, 0,2);
					if($first_two_char == "us"){
						$new_count++;
					}
				}

				
				if($original_count == $new_count){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		public function checkIfTestIdsAreNotExclusivelyScan($test_ids_arr){
			if(is_array($test_ids_arr)){
				$original_count = count($test_ids_arr);
				$new_count = 0;
				for($i = 0; $i < $original_count; $i++){
					$test_id = $test_ids_arr[$i];
					$test_id = strtolower($test_id);
					$first_two_char = substr($test_id, 0,2);
					if($first_two_char != "us"){
						$new_count++;
					}
				}
				
				if($original_count == $new_count){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		public function checkIfPharmacyInitiationCodeIsValidUnPaid($health_facility_id,$initiation_code){
			$query = $this->db->get_where('pharmacy_drugs_selected',array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'paid' => 0));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function markPharmacyPatientsDrugsAsDispensed($health_facility_id,$initiation_code,$date,$time,$id){
			$date = $date . " " . $time;
			return $this->db->update('pharmacy_drugs_selected',array('dispensed' => 1,'dispensed_time' => $date),array('health_facility_id' => $health_facility_id, 'initiation_code' => $initiation_code,'id' => $id));
		}

		public function getDrugIdByInitiationCodePharmacy($health_facility_id,$initiation_code){
			$query = $this->db->get_where('pharmacy_drugs_selected',array('initiation_code' => $initiation_code),1);
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$drug_id = $row->drug_id;
				}
				return $drug_id;
			}
		}

		public function getSelectedDrugIdByInitiationCodePharmacy($health_facility_id,$initiation_code){
			$query = $this->db->get_where('pharmacy_drugs_selected',array('initiation_code' => $initiation_code),1);
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$id = $row->id;
				}
				return $id;
			}
		}

		public function getDrugPoisonStatusBySelectedDrugId($health_facility_id,$selected_drug_id){
			$query = $this->db->get_where('pharmacy_drugs_selected',array('initiation_code' => $initiation_code),1);
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$is_poison = $row->is_poison;
				}
				return $is_poison;
			}
		}

		public function updateRecordsCode($array1,$health_facility_id,$code){
			return $this->db->update('verification_codes_records',$array1,array('health_facility_id' => $health_facility_id,'code' => $code));
		}

		public function viewUsedCodesRecords($health_facility_id){
			$query = $this->db->get_where("verification_codes_records",array('health_facility_id' => $health_facility_id,'taken' => 1));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function viewUnUsedCodesRecords($health_facility_id){
			$query = $this->db->get_where("verification_codes_records",array('health_facility_id' => $health_facility_id,'taken' => 0));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function viewUnUsedCodesRecordById($health_facility_id,$id){
			$query = $this->db->get_where("verification_codes_records",array('health_facility_id' => $health_facility_id,'taken' => 0,'id' => $id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}


		public function subtractQuantityFromStore($health_facility_id,$quantity,$drug_id){
			$query = $this->db->get_where('drugs',array('id' => $drug_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$dispensary_quantity = $row->dispensary_quantity;
				}
				$new_quantity = $dispensary_quantity - $quantity;
				$this->db->update('drugs',array('dispensary_quantity' => $new_quantity),array('id' => $drug_id));
			}
		}

		public function getDrugInfo($health_facility_id,$drug_id){
			$query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id,'id' => $drug_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getAntibioticsPattern($health_facility_id){
			$query = $this->db->get_where("pharmacy_antibiotics_pattern",array("health_facility_id" => $health_facility_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getIfDrugWasSelectedInTheWard($id){
			$query = $this->db->get_where("pharmacy_drugs_selected",array('id' => $id,'ward' => 1));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getWardRecordIdByInitiationCode($health_facility_id,$initiation_code){
			$query = $this->db->get_where("pharmacy_drugs_selected_wards",array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$ward_record_id = $row->ward_record_id;
				}
				return $ward_record_id;
			}else{
				return false;
			}
		}

		public function getParamForSelectedDrug($drugs_selected_id,$param){
			$ret = "";
			$query = $this->db->get_where("pharmacy_drugs_selected",array('id' => $drugs_selected_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$ret = $row->$param;
				}
			}
			return $ret;
		}

		public function calculatePrescription2 ($dosage, $frequency_num,$frequency_time,$duration_num,$duration_time) {
    
		    if($dosage != "" && $frequency_num != "" && $frequency_time != "" && $duration_num != "" && $duration_time != ""){
		      // console.log(i)
		      $quantity = 0;
		      if($frequency_time == "nocte" || $frequency_time == "stat"){
		        $frequency_time = "daily";
		      }

		      if($frequency_time == "yearly" && $duration_time == "years"){
		        
		      }else if($frequency_time == "monthly" && $duration_time == "years"){
		        $duration_num = 12 * $duration_num;
		      }else if($frequency_time == "weekly" && $duration_time == "years"){
		        $duration_num = 12 * 4 * $duration_num;
		      }else if($frequency_time == "daily" && $duration_time == "years"){
		        $duration_num = 12 * 28 * $duration_num;
		      }else if($frequency_time == "hourly" && $duration_time == "years"){
		        $duration_num = 12 * 28 * 24 * $duration_num;
		      }else if($frequency_time == "minutely" && $duration_time == "years"){
		        $duration_num = 12 * 28 * 24 * 60 * $duration_num;
		      }else if($frequency_time == "monthly" && $duration_time == "months"){
		        
		      }else if($frequency_time == "weekly" && $duration_time == "months"){
		        $duration_num = 4 * $duration_num;
		      }else if($frequency_time == "daily" && $duration_time == "months"){
		        $duration_num = 28 * $duration_num;
		      }else if($frequency_time == "hourly" && $duration_time == "months"){
		        $duration_num = 28 * 24 * $duration_num;
		      }else if($frequency_time == "minutely" && $duration_time == "months"){
		        $duration_num = 28 * 24 * 60 * $duration_num;
		      }else if($frequency_time == "weekly" && $duration_time == "weeks"){
		        
		      }else if($frequency_time == "daily" && $duration_time == "weeks"){
		        $duration_num = 7 * $duration_num;
		      }else if($frequency_time == "hourly" && $duration_time == "weeks"){
		        $duration_num = 7 * 24 * $duration_num;
		      }else if($frequency_time == "minutely" && $duration_time == "weeks"){
		        $duration_num = 7 * 24 * 60 * $duration_num;
		      }else if($frequency_time == "daily" && $duration_time == "days"){
		        
		      }else if($frequency_time == "hourly" && $duration_time == "days"){
		        $duration_num =  24 * $duration_num;
		      }else if($frequency_time == "minutely" && $duration_time == "days"){
		        $duration_num =  24 * 60 * $duration_num;
		      }else if($frequency_time == "hourly" && $duration_time == "hours"){
		        
		      }else if($frequency_time == "minutely" && $duration_time == "hours"){
		        $duration_num =  60 * $duration_num;
		      }else{
		        $duration_num = 0;
		        $frequency_num = 0;
		      }


		      
		      if($duration_num > 0 || $frequency_num > 0){
			      $quantity = ($duration_num / $frequency_num);
			      return $quantity;
			  }
		    }
		}

		public function getMedicationChart($health_facility_id,$ward_record_id,$initiation_code,$drug_selected_id){
			$query = $this->db->get_where("drug_taken_times",array('ward_record_id' => $ward_record_id,'drug_selected_id' => $drug_selected_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}


		public function markPharmacyPatientsDrugsAsDispatched($health_facility_id,$initiation_code,$date,$time,$id,$drug_id,$quantity_requested){
			$date = $date . " " . $time;
			$query = $this->db->update('pharmacy_drugs_selected',array('dispatched' => 1,'dispatched_time' => $date),array('health_facility_id' => $health_facility_id, 'initiation_code' => $initiation_code,'id' => $id));
			if($query){
				if($this->getIfDrugWasSelectedInTheWard($id)){
					
					$drugs_selected_id = $id;
					$ward_record_id = $this->getParamForSelectedDrug($drugs_selected_id,"ward_record_id");
					$dosage = $this->getParamForSelectedDrug($drugs_selected_id,"dosage");
					$frequency_num = $this->getParamForSelectedDrug($drugs_selected_id,"frequency_num");
					$frequency_time = $this->getParamForSelectedDrug($drugs_selected_id,"frequency_time");
					$duration_num = $this->getParamForSelectedDrug($drugs_selected_id,"duration_num");
					$duration_time = $this->getParamForSelectedDrug($drugs_selected_id,"duration_time");

					$num = $this->onehealth_model->calculatePrescription2 ($dosage, $frequency_num,$frequency_time,$duration_num,$duration_time);
					$taken_time = date("j M Y h:i:sa");
					if($frequency_time == "minutely"){
						$str = "minutes";
					}else if($frequency_time == "hourly"){
						$str = "hours";
					}else if($frequency_time == "daily" || $frequency_time == "nocte" || $frequency_time == "stat"){
						$str = "days";
					}else if($frequency_time == "weekly"){
						$str = "weeks";
					}else if($frequency_time == "monthly"){
						$str = "months";
					}else if($frequency_time == "yearly"){
						$str = "years";
					}
				    for($i = 0; $i < $num; $i++){
				    	if($i > 0){
					    	$taken_time = date("j M Y h:i:sa", strtotime('+'.$frequency_num.' '.$str,strtotime($taken_time)));
					    }
				    	$date1 = date("j M Y",strtotime($taken_time));
				    	$time1 = date("h:i:sa",strtotime($taken_time));
				    	$array = array(
				    		'health_facility_id' => $health_facility_id,
				    		'ward_record_id' => $ward_record_id,
				    		'drug_selected_id' => $drugs_selected_id,
				    		'given' => 0,
				    		'date_to_be_given' => $date1,
				    		'time_to_be_given' => $time1
				    	);
				    	$this->db->insert("drug_taken_times",$array);
				    }
				}
				if($this->resetDrugsStoreAfterPurchase($health_facility_id,$drug_id,$quantity_requested)){
					
					$drug_info = $this->getDrugInfo($health_facility_id,$drug_id);
					if(is_array($drug_info)){
						foreach($drug_info as $row){
		            		$brand_name = $row->brand_name;
		            		$generic_name = $row->generic_name;
		            		$formulation = $row->formulation;
		            		$class_name = $row->class_name;
		            		$strength = $row->strength;
		            		$strength_unit = $row->strength_unit;
		            		$expiry_date = $row->expiry_date;
							$unit = $row->unit;
							$is_poison = $row->is_poison;
							
							$main_store_quantity = $row->main_store_quantity;
							$dispensary_quantity = $row->dispensary_quantity;
						
							$common_adult_dosage = $row->common_adult_dosage;
							$common_adult_dose_frequency_num = $row->common_adult_dose_frequency_num;
							$common_adult_dose_frequency_time = $row->common_adult_dose_frequency_time;
							$common_adult_dose_duration_num = $row->common_adult_dose_duration_num;
							$common_adult_dose_duration_time = $row->common_adult_dose_duration_time;
							$common_pediatric_dosage = $row->common_pediatric_dosage;
							$common_pediatric_dose_frequency_num = $row->common_pediatric_dose_frequency_num;
							$common_pediatric_dose_frequency_time = $row->common_pediatric_dose_frequency_time;
							$common_pediatric_dose_duration_num = $row->common_pediatric_dose_duration_num;
							$common_pediatric_dose_duration_time = $row->common_pediatric_dose_duration_time;
							$price = $row->price;

							$prescribed_by = $this->getDrugsCliniciansName($health_facility_id,$id);
							$dispensed_by = $this->getUserIdWhenLoggedIn();
							$class_name = strtolower($class_name);
						}	

						if($class_name == "antibiotics"){
							$form_array1 = array(
								'brand_name' => $brand_name,
								'generic_name' => $generic_name,
								'formulation' => $formulation,
								'class_name' => $class_name,
								'unit' => $unit,
								'strength' => $strength,
								'strength_unit' => $strength_unit,
								'quantity' => $quantity_requested,
								'health_facility_id' => $health_facility_id,
								'drugs_selected_id' => $id,
								'date' => date("j M Y"),
								'time' => date("h:i:sa")
							);
							$this->db->insert("pharmacy_antibiotics_pattern",$form_array1);
						}
						if($this->checkIfDrugIsPoison($health_facility_id,$drug_id)){
							$form_array = array(
								'brand_name' => $brand_name,
								'generic_name' => $generic_name,
								'formulation' => $formulation,
								'class_name' => $class_name,
								'unit' => $unit,
								'strength' => $strength,
								'strength_unit' => $strength_unit,
								'quantity_requested' => $quantity_requested,
								'health_facility_id' => $health_facility_id,
								'prescribed_by' => $prescribed_by,
								'dispensed_by' => $dispensed_by,
								'date' => $date
							);
							
							return $this->db->insert('drugs_poison_register',$form_array);
						}else{
							return true;
						}
					}	
				}	
			}else{
				return false;
			}
		}

		public function getListOfPharmacyOtherRegistersForFacility($health_facility_id){
			$query = $this->db->get_where('other_pharmacy_registers',array("health_facility_id" => $health_facility_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getListOfFacilitiesApartFromYours($health_facility_id){
			
			$this->db->select("*");
			$this->db->from("health_facility");
			$this->db->where("facility_structure","hospital");
			$this->db->where("id !=",$health_facility_id);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function referPatient($form_array){
			return $this->db->insert("referrals_or_consults",$form_array);
		}

		public function getListOfClinics($health_facility_id,$sub_dept_id){
			// $query = $this->db->get_where("sub_dept",array('dept_id' => 3));
			$clinic_structure = $this->getHealthFacilityParamById("clinic_structure",$health_facility_id);
			$this->db->select("*");
			$this->db->from("sub_dept");
			$this->db->where("dept_id",3);
			if($clinic_structure == "mini"){
				$this->db->where("id",55);
			}
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}


		public function checkIfPatientMatchesUserType($patient_bio_data_table,$type,$patient_id){
			$query = $this->db->get_where($patient_bio_data_table,array('user_id' => $patient_id,'user_type' => $type));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}


		public function checkIfPatientMatchesUserTypeUsername($patient_bio_data_table,$type,$patient_username){
			$query = $this->db->get_where($patient_bio_data_table,array('user_name' => $patient_username,'user_type' => $type));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}


		public function checkIfPatientMatchesUserTypeUsernameFp($patient_bio_data_table,$patient_username){
			$query = $this->db->get_where($patient_bio_data_table,array('user_name' => $patient_username,'user_type' => "fp"));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfPatientMatchesUserTypeUsernamePfp($patient_bio_data_table,$patient_username){
			$query = $this->db->get_where($patient_bio_data_table,array('user_name' => $patient_username,'user_type' => "pfp"));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfPatientMatchesUserTypeUsernameNfp($patient_bio_data_table,$patient_username){
			$query = $this->db->get_where($patient_bio_data_table,array('user_name' => $patient_username,'user_type' => "nfp"));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}
		




		public function getPatientsFees($patient_bio_data_table,$health_facility_id,$days_num,$type,$payment_type){
			$ret = array();
			$query_str = "SELECT * FROM clinic_payments WHERE health_facility_id = ".$health_facility_id." AND type= '".$payment_type."'";
			$query = $this->db->query($query_str);
			// echo $this->db->last_query();
			if($query->num_rows() > 0){
				$i = -1;
				// var_dump($query->result());
				foreach($query->result() as $row){
					$i++;
					
					$patient_id = $row->patient_id;
					if($this->checkIfPatientMatchesUserType($patient_bio_data_table,$type,$patient_id)){
						$ret[] = $query->result()[$i];
					}
				}
			}else{
				return false;
			}
			return $ret;
		}
	

		public function getListOfReferralsClinicsRecord($sub_dept_id,$health_facility_id){
			// $query = $this->db->get_where('referrals_or_consults')
			$this->db->select("*");
			$this->db->from("referrals_or_consults");
			$this->db->where("referred_to_facility_id",$health_facility_id);
			$this->db->where("referred_to_sub_dept_id",$sub_dept_id);
			$this->db->where("records_registered",0);
			$query = $this->db->get();
			// echo $this->db->last_query();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}

		}

		public function getListOfReferralsClinicsRecordNurse($sub_dept_id,$health_facility_id){
			// $query = $this->db->get_where('referrals_or_consults')
			$this->db->select("*");
			$this->db->from("referrals_or_consults");
			$this->db->where("referred_to_facility_id",$health_facility_id);
			$this->db->where("referred_to_sub_dept_id",$sub_dept_id);
			$this->db->where("nurse_registered",0);
			$this->db->where("records_registered",1);
			$query = $this->db->get();
			// echo $this->db->last_query();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}

		}

		public function getRecordIdByRefferalId($referral_id,$health_facility_id,$sub_dept_id){
			$query = $this->db->get_where("referrals_or_consults",array('referred_to_facility_id' => $health_facility_id,'referred_to_sub_dept_id' => $sub_dept_id,'id' => $referral_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$record_id = $row->main_health_facility_record_id;
				}
				return $record_id;
			}else{
				return false;
			}
		}

		

		public function getListOfReferralsClinicsRecordDoctor($sub_dept_id,$health_facility_id){
			// $query = $this->db->get_where('referrals_or_consults')

			$this->db->select("*");
			$this->db->from("referrals_or_consults");
			$this->db->where("referred_to_facility_id",$health_facility_id);
			$this->db->where("referred_to_sub_dept_id",$sub_dept_id);
			$this->db->where("nurse_registered",1);
			$this->db->where("records_registered",1);
			$this->db->where("consultation_complete",0);
			$query = $this->db->get();
			// echo $this->db->last_query();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}

		}

		public function updateConsultTable($health_facility_id,$sub_dept_id,$form_array,$referral_id){
			return $this->db->update("referrals_or_consults",$form_array,array('referred_to_facility_id' => $health_facility_id,'referred_to_sub_dept_id' => $sub_dept_id,'id' => $referral_id));
		}

		public function getReferralInfo($sub_dept_id,$health_facility_id,$referral_id){
			// $query = $this->db->get_where('referrals_or_consults')
			$this->db->select("*");
			$this->db->from("referrals_or_consults");
			$this->db->where("referred_to_facility_id",$health_facility_id);
			$this->db->where("referred_to_sub_dept_id",$sub_dept_id);
			$this->db->where("id",$referral_id);
			$query = $this->db->get();
			// echo $this->db->last_query();
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}

		}

		public function getReferralInfoPreviousReferralsById($referral_id){
			// $query = $this->db->get_where('referrals_or_consults')
			$this->db->select("*");
			$this->db->from("referrals_or_consults");
			$this->db->where("id",$referral_id);
			$this->db->where("viewable",1);
			$query = $this->db->get();
			// echo $this->db->last_query();
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}

		}

		public function markDrugAsTaken($id,$form_array){
			return $this->db->update("drug_taken_times",$form_array,array('id' => $id));
		}

		public function submitPharmacyNewRecordToRegisterForm($form_array){
			return $this->db->insert("other_pharmacy_registers_values",$form_array);
		}

		public function getRegisterInfoForFacilityPharmacy($health_facility_id,$id){
			$query = $this->db->get_where('other_pharmacy_registers',array('health_facility_id' => $health_facility_id,'id' => $id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getRecordsForOtherRegisterPharmacyDates($health_facility_id,$id){
			// $query = $this->db->get_where("other_pharmacy_registers_values",array('health_facility_id' => $health_facility_id,'register_id' => $id));
			$ret = array();
			$this->db->select("date");
			$this->db->from("other_pharmacy_registers_values");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("register_id",$id);
			$this->db->order_by("id","DESC");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$date = $row->date;
					$ret[] = $date;
				}

			}else{
				return false;
			}

			$ret = array_values(array_unique($ret));
			return $ret;
		}

		public function getNumberOfRecordsInDayOtherPharmacyRegisters($health_facility_id,$id,$date){
			$query = $this->db->get_where("other_pharmacy_registers_values",array('health_facility_id' => $health_facility_id,'date' => $date,'register_id' => $id));
			return $query->num_rows();
		}

		public function getRecordsForOtherRegisterPharmacyParticularDay($health_facility_id,$id,$date){
			$this->db->select("*");
			$this->db->from("other_pharmacy_registers_values");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("register_id",$id);
			$this->db->where("date",$date);
			$this->db->order_by("id","DESC");
			
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function checkIfUserIsValidToEditOtherRegisterPharmacyRecord($health_facility_id,$register_id,$register_value_id){
			$user_id = $this->getUserIdWhenLoggedIn();
			$query = $this->db->get_where('other_pharmacy_registers_values',array('health_facility_id' => $health_facility_id,'register_id' => $register_id,'id' => $register_value_id,'personnel_id' => $user_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function submitPharmacyEditRecordToRegisterForm($form_array,$health_facility_id,$register_value_id){
			return $this->db->update("other_pharmacy_registers_values",$form_array,array('health_facility_id' => $health_facility_id,'id' => $register_value_id));
		}

		public function getParameterValOtherRegisterPharmacy($health_facility_id,$register_id,$register_value_id,$parameter){
			$query = $this->db->get_where('other_pharmacy_registers_values',array('health_facility_id' => $health_facility_id,'register_id' => $register_id,'id' => $register_value_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$parameter = $row->$parameter;
				}
				return $parameter;
			}else{
				return false;
			}
		}

		public function getLastEntryTimeInDayOtherPharmacyRegisters($health_facility_id,$id,$date){
			$this->db->select("time");
			$this->db->from("other_pharmacy_registers_values");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("register_id",$id);
			$this->db->where("date",$date);
			$this->db->order_by("id","DESC");
			$this->db->limit(1);
			$query = $this->db->get();
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$time = $row->time;
				}
				return $time;
			}else{
				return false;
			}
		}

		public function submitPharmacyNewRegisterForm($form_array){
			return $this->db->insert("other_pharmacy_registers",$form_array);
		}

		public function getPharmacyInitiationCodeById($health_facility_id,$id){
			$query =  $this->db->get_where('pharmacy_drugs_selected',array("health_facility_id" => $health_facility_id,'id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
				}
				return $initiation_code;
			}
		}

		public function getPoisonRegisterForHealthFacility($health_facility_id){
			$query = $this->db->get_where('drugs_poison_register',array('health_facility_id' => $health_facility_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function checkIfPharmacyIsInStock($health_facility_id,$drug_id,$quantity_requested){
			$dispensary_quantity = $this->getDispensaryQuantity($health_facility_id,$drug_id);
			if($quantity_requested <= $dispensary_quantity){
				return true;
			}else{
				return false;
			}
		}

		public function getDrugsCliniciansName($health_facility_id,$id){
			$query = $this->db->get_where('pharmacy_drugs_selected',array('health_facility_id' => $health_facility_id,'id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$clinician = $row->clinician;
				}
				return $clinician;
			}else{
				return false;
			}
		}

		public function resetDrugsStoreAfterPurchase($health_facility_id,$drug_id,$quantity_requested){
			$query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id,'id' => $drug_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$main_store_quantity = $row->main_store_quantity;
					$dispensary_quantity = $row->dispensary_quantity;

					$new_dispensary_quantity = $dispensary_quantity - $quantity_requested;
				}
				$query = $this->db->update('drugs',array('dispensary_quantity' => $new_dispensary_quantity),array('health_facility_id' => $health_facility_id,'id' => $drug_id));
				return $query;
			}
		}

		public function getQuantityOfDrugsRequested($health_facility_id,$initiation_code,$id){
			$query = $this->db->get_where('pharmacy_drugs_selected',array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$quantity = $row->quantity;
				}
				return $quantity;
			}else{
				return false;
			}
		}

		public function checkIfDrugIsPoison($health_facility_id,$drug_id){
			$query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id,'id' => $drug_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$is_poison = $row->is_poison;
					if($is_poison == 1){
						return true;
					}else{
						return false;
					}
				}
			}
		}

		public function getDrugIdByDrugsSelectedId($health_facility_id,$id){
			$query = $this->db->get_where('pharmacy_drugs_selected',array('health_facility_id' => $health_facility_id,'id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$drug_id = $row->drug_id;
				}
				return $drug_id;
			}else{
				return false;
			}
		}

		public function getTotalAvailableDrugQuantity($health_facility_id,$drug_id){
			$query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id,'id' => $drug_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$main_store_quantity = $row->main_store_quantity;
					$dispensary_quantity = $row->dispensary_quantity;

					return $main_store_quantity + $dispensary_quantity;
				}
			}
		}

		public function getMainStoreQuantityForDrug($health_facility_id,$id){
			$query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id,'id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$main_store_quantity = $row->main_store_quantity;
					return $main_store_quantity;
				}
			}
		}

		public function getDispensaryQuantityForDrug($health_facility_id,$id){
			$query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id,'id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$dispensary_quantity = $row->dispensary_quantity;
					return $dispensary_quantity;
				}
			}
		}

		public function updateDispensaryQuantity($new_main_store_quantity,$dispensary_quantity,$id,$health_facility_id){
			$query = $this->db->update('drugs',array('main_store_quantity' => $new_main_store_quantity,'dispensary_quantity' => $dispensary_quantity),array('id' => $id,'health_facility_id' => $health_facility_id));
			return $query;
		}

		public function getDispensaryQuantity($health_facility_id,$drug_id){
			$query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id,'id' => $drug_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$dispensary_quantity = $row->dispensary_quantity;
					return  $dispensary_quantity;
				}
			}
		}

		public function checkIfPharmacyInitiationCodeHasBeenDispensed($health_facility_id,$initiation_code,$id){
			$query = $this->db->get_where('pharmacy_drugs_selected',array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'paid' => 1,'dispensed' => 1,'id' => $id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfPharmacyInitiationCodeHasBeenDispatched($health_facility_id,$initiation_code,$id){
			$query = $this->db->get_where('pharmacy_drugs_selected',array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'paid' => 1,'dispatched' => 1,'id' => $id));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfPharmacyInitiationCodeIsValidPaid($health_facility_id,$initiation_code){
			$query = $this->db->get_where('pharmacy_drugs_selected',array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'paid' => 1));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getPersonnel($health_facility_table_name,$dept,$sub_dept,$personnel){
			$query = $this->db->get_where($health_facility_table_name,array('dept' => $dept,'sub_dept' => $sub_dept,"personnel" => $personnel,'position' => 'personnel' , 'is_admin' => 1));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getSubAdmin($health_facility_table_name,$dept,$sub_dept){
			$query = $this->db->get_where($health_facility_table_name,array('dept' => $dept,'sub_dept' => $sub_dept,'position' => 'sub_admin' , 'is_admin' => 1));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		

		public function getUserPosition($health_facility_table_name,$user_id){
			$query = $this->db->get_where($health_facility_table_name,array('user_id' => $user_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$position = $row->position;
				}
				return $position;
			}else{
				return false;
			}
		}


		public function getPersonnelPositionByUserId($health_facility_table_name,$user_id){
			$query = $this->db->get_where($health_facility_table_name,array('user_id' => $user_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$position = $row->personnel;
				}
				return $position;
			}
		}

		public function getHealthFacilityTableByDeptAndPositionPersonnel($health_facility_table_name,$dept,$sub_dept,$personnel){
			$query = $this->db->get_where($health_facility_table_name,array('dept' => $dept,'sub_dept' => $sub_dept,"personnel" => $personnel,'position' => 'personnel'));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function checkIfUserIsAdminOfFacility($hospital_table_name,$user_id){
			$query = $this->db->get_where($hospital_table_name,array('user_id' => $user_id,'position' => 'admin'));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfUserIsAnAdmin3($user_id){
			$query = $this->db->get_where("users",array('id' => $user_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$is_admin = $row->is_admin;
				}
				if($is_admin == 1){
					return true;
				}else{
					return false;
				}
			}
		}

		public function getHealthFacilityTableBySubDeptDeptAndPosition($health_facility_table_name,$dept,$sub_dept,$personnel){
			$query = $this->db->get_where($health_facility_table_name,array('dept' => $dept,'sub_dept' => $sub_dept,'personnel' => $personnel,'position' => 'personnel'));
			// echo $query->num_rows();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getHealthFacilityTableBySubDeptAndPosition($health_facility_table_name,$sub_dept,$user_position){
			$query = $this->db->get_where($health_facility_table_name,array('sub_dept' => $sub_dept,'position' => 'personnel',));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getHealthFacilityTableByDeptSubDeptAndPosition($health_facility_table_name,$dept,$sub_dept,$personnel){
			// echo $dept . ' : ' . $sub_dept .' : ' .$personnel; 
			$query = $this->db->get_where($health_facility_table_name,array('dept' => $dept,'position' => 'personnel','sub_dept' => $sub_dept,'personnel' => $personnel));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function slugifyDept($form_array,$dept_id){
			$query = $this->db->update('personnel',$form_array,array('id' => $dept_id));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function getDeptBooleanBySlug($slug){

			$query = $this->db->get_where('dept',array('slug' => $slug));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getSubDeptBooleanBySlug($slug){
			$query = $this->db->get_where('sub_dept',array('slug' => $slug));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getPersonnelBooleanBySlug($slug){
			$query = $this->db->get_where('personnel',array('slug' => $slug));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getDeptById($dept_id){
			$query = $this->db->get_where('dept',array('id' => $dept_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getSubDeptBySlugAndDeptId($dept_id,$slug){
			$query = $this->db->get_where('sub_dept',array('slug' => $slug,'dept_id' => $dept_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}


		public function getPersonnelBySlugDeptIdAndSubDeptId($slug,$dept_id,$sub_dept_id){
			$query = $this->db->get_where('personnel',array('slug' => $slug,'dept_id' => $dept_id,'sub_dept_id' => $sub_dept_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getDeptBySlug($slug){
			$query = $this->db->get_where('dept',array('slug' => $slug));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getAllFacilityOfficers($health_facility_table_name){
			$this->db->select("*");
			$this->db->from($health_facility_table_name);
			$this->db->where('is_admin',1);
			$this->db->where('position !=','admin');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getFacilityOfficer($health_facility_table_name,$user_id){
			$query = $this->db->get_where($health_facility_table_name,array('user_id' => $user_id),1);
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function checkIfUserDoesNotHaveThisPost($health_facility_table_name,$user_id,$user_name,$dept_slug,$sub_dept_slug){

			$query = $this->db->get_where($health_facility_table_name,array('user_id' => $user_id,'dept' => $dept_slug,'sub_dept' => $sub_dept_slug, 'personnel' => '','position' => 'sub_admin'));
			if($query->num_rows() > 0){
				if($this->checkIfUserIsATopAdmin($health_facility_table_name,$user_name)){
					return false;
				}
			}else{
				return true;
			}
		}

		public function getOtherSubDepts3(){
			$this->db->select("*");
			$this->db->from("sub_dept");
			$this->db->where('id !=',1);
			$this->db->where('id !=',2);
			$this->db->where('id !=',3);
			$this->db->where('id !=',6);
			$this->db->where('id !=',7);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}
		}

		public function insertPersonnel($form_array){
			$query = $this->db->insert('personnel',$form_array);
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfUserDoesNotHaveThisPost2($health_facility_table_name,$user_id,$dept_slug,$sub_dept_slug){
			$this->db->select("*");
			$this->db->from($health_facility_table_name);
			$this->db->where('dept',$dept_slug);
			$this->db->where('user_id',$user_id);
			$this->db->where('sub_dept !=', $sub_dept_slug);
			$this->db->where('personnel','');
			$this->db->where('position','sub_admin');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				echo $sub_dept_slug;
				return false;
			}else{
				return true;
			}
		}


		public function checkIfUserDoesNotHaveThisPostPersonnel($health_facility_table_name,$user_id,$dept_slug,$sub_dept_slug,$personnel_slug){
			$query = $this->db->get_where($health_facility_table_name,array('user_id' => $user_id,'dept' => $dept_slug,'sub_dept' => $sub_dept_slug, 'personnel' => $personnel_slug,'position' => 'personnel'));
			if($query->num_rows() > 0){
				return false;
				
			}else{
				return true;
			}
		}


		public function getDeptIdBySlug($slug){
			$query = $this->db->get_where('dept',array('slug' => $slug));
			if($query->num_rows() == 1){
				foreach($query->result() as $row ){
					$dept_id = $row->id;
				}
				return $dept_id;
			}else{
				return false;
			}
		}

		public function getIfWardSubDeptIsValid($ward_id){
			$query = $this->db->get_where('sub_dept',array('id' => $ward_id,'dept_id' => 5));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getWardsPatients($health_facility_id,$sub_dept_id,$ward_id){
			$query = $this->db->get_where('wards',array('health_facility_id' => $health_facility_id,'clinic_id' => $sub_dept_id,'sub_dept_id' => $ward_id,'discharged' => 0));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getWardsPatients2($health_facility_id,$sub_dept_id,$ward_id){
			$ret_arr = array();

			$query = $this->db->get_where('wards',array('health_facility_id' => $health_facility_id,'sub_dept_id' => $ward_id,'discharged' => 0));
			if($query->num_rows() > 0){
				// echo "string";
				foreach($query->result() as $row){
					$consults_list = $row->consults_list;
					if($consults_list != ""){
						$consults_list_arr = explode(",",$consults_list);
						if(in_array($sub_dept_id,$consults_list_arr)){
							$ret_arr[] = $row;
						}
					}
				}
			}else{
				return false;
			}
			return $ret_arr;
		}

		public function getWardsPatients1($health_facility_id,$ward_id){
			$query = $this->db->get_where('wards',array('health_facility_id' => $health_facility_id,'sub_dept_id' => $ward_id,'discharged' => 0));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}



		public function getPatientNameByRecordId($clinic_activities_table_name,$record_id){
			$query = $this->db->get_where($clinic_activities_table_name,array('id' => $record_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$patient_name = $row->patient_name;
				}
				return $patient_name;
			}else{
				return "";
			}
		}

		public function getWardsClinicHasPatientsIn($health_facility_id,$sub_dept_id){
			$query = $this->db->get_where('wards',array('health_facility_id' => $health_facility_id,'clinic_id' => $sub_dept_id,'discharged' => 0));
			if($query->num_rows() > 0){
				$wards_list = array();
				foreach($query->result() as $row){
					$ward_id = $row->sub_dept_id;
					if($this->getIfWardSubDeptIsValid($ward_id)){
						$wards_list[] = $ward_id;
					}
				}
				if(!empty($wards_list)){
					$wards_list = array_values(array_unique($wards_list));

					return $wards_list;
				}
			}
		}

		public function getWardsClinicHasPatientsIn1($health_facility_id,$sub_dept_id){
			$query = $this->db->get_where('wards',array('health_facility_id' => $health_facility_id,'discharged' => 0));
			if($query->num_rows() > 0){
				$wards_list = array();
				foreach($query->result() as $row){
					$ward_id = $row->sub_dept_id;
					$consults_list = $row->consults_list;
					$consults_list_arr = explode(",",$consults_list);
					if(in_array($sub_dept_id, $consults_list_arr)){
						if($this->getIfWardSubDeptIsValid($ward_id)){
							$wards_list[] = $ward_id;
						}
					}
				}
				if(!empty($wards_list)){
					$wards_list = array_values(array_unique($wards_list));

					return $wards_list;
				}
			}
		}


		public function getNumberOfPatientsInWard($health_facility_id,$sub_dept_id,$ward_id){
			$query = $this->db->get_where('wards',array('health_facility_id' => $health_facility_id,'clinic_id' => $sub_dept_id,'sub_dept_id' => $ward_id,'discharged' => 0));
			return $query->num_rows();
		}

		public function getNumberOfPatientsInWard1($health_facility_id,$sub_dept_id,$ward_id){
			$ret = 0;
			$query = $this->db->get_where('wards',array('health_facility_id' => $health_facility_id,'sub_dept_id' => $ward_id,'discharged' => 0));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$consults_list = $row->consults_list;
					if($consults_list != ""){
						$consults_list_arr = explode(",",$consults_list);
						if(in_array($sub_dept_id, $consults_list_arr)){
							$ret++;
						}
					}
				}
			}
			return $ret;
		}


		public function checkIfCinicHasPatientsInWard($health_facility_id,$sub_dept_id){
			$query = $this->db->get_where('wards',array('health_facility_id' => $health_facility_id,'clinic_id' => $sub_dept_id,'discharged' => 0));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfCinicHasPatientsInWard1($health_facility_id,$sub_dept_id){
			$query = $this->db->get_where('wards',array('health_facility_id' => $health_facility_id,'discharged' => 0));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$consults_list = $row->consults_list;
					if($consults_list != ""){
						$consults_list_arr = explode(",",$consults_list);
						if(in_array($sub_dept_id, $consults_list_arr)){
							return true;
						}
					}
				}
			}else{
				return false;
			}
		}

		public function getSubDeptIdBySlugAndDeptId($slug,$dept_id){
			$query = $this->db->get_where('sub_dept',array('slug' => $slug,'dept_id' => $dept_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row ){
					$sub_dept_id = $row->id;
				}
				return $sub_dept_id;
			}else{
				return false;
			}
		}

		public function getPersonnelIdBySlugDeptIdAndSubDeptId($slug,$dept_id,$sub_dept_id){
			$query = $this->db->get_where('personnel',array('slug' => $slug,'dept_id' => $dept_id,'sub_dept_id' => $sub_dept_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row ){
					$personnel_id = $row->id;
				}
				return $personnel_id;
			}else{
				return false;
			}
		}


		public function getPersonnelBySlug($slug){
			$query = $this->db->get_where('personnel',array('slug' => $slug));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getSubDeptPostBySlug($sub_dept_slug,$dept_id){
			$query = $this->db->get_where('sub_dept',array('slug' => $sub_dept,'dept_id' => $dept_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $sub_dept){
					$post = $sub_dept->name; 
					$id = $sub_dept->id;
					return $id;
				}

			}else{
				return false;
			}
		}

		public function getPersonnelPostBySlug($personnel_slug,$dept_id,$sub_dept_id){
			$query = $this->db->get_where('personnel',array('slug' => $personnel_slug,'dept_id' => $dept_id,'sub_dept_id' => $sub_dept_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $personnel){
					$post = $personnel->name; 
					$id = $personnel->id;
					return $id;
				}

			}else{
				return false;
			}
		}

		public function getPersonnelsByDeptIdAndSubDeptId($dept_id,$sub_dept_id){
			$query = $this->db->get_where('personnel',array('dept_id' => $dept_id,'sub_dept_id' => $sub_dept_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getPersonnelsBySubDeptId($sub_dept_id){
			$query = $this->db->get_where('personnel',array('sub_dept_id' => $sub_dept_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		//Add SubAdmin
		public function addSubAdmin($table_name,$health_facility_table_array){
			$query = $this->db->insert($table_name,$health_facility_table_array);
			if($query){
				return true;
			}else{
				return false;
			}
		}
		public function getCountries(){
			$query = $this->db->query('SELECT * FROM countries ORDER BY name ASC');

			if($query->num_rows() > 0){
				return $query->result();
			}

		}

		public function getCountryById($id){
			$query = $this->db->get_where('countries',array('id' => $id),1);

			if(is_array($query->result())){
				foreach($query->result() as $row){
					return $row->name;
				}
			}
		}

		public function getCountryAcrById($id){
			$query = $this->db->get_where('countries',array('id' => $id),1);

			if(is_array($query->result())){
				foreach($query->result() as $row){
					return $row->code;
				}
			}
		}

		public function getStateById($id){
			$query = $this->db->query('SELECT * FROM regions WHERE id = '.$id.' ORDER BY id ASC');
			if(is_array($query->result())){
				foreach($query->result() as $row){
					return $row->name;
				}
			}
		}

		public function updateHealthFacility($form_array,$health_facility_id){
			$query = $this->db->update('health_facility',$form_array,array('id' => $health_facility_id));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function getHealthFacilityLogo($health_facility_id){
			$query = $this->db->get_where('health_facility',array('id' => $health_facility_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$logo = $row->logo;
					return $logo;
				}
			}
		}
		
		public function getHealthFacilityIdByTableName($affiliated_facility_table){
			$query = $this->db->get_where('health_facility',array('table_name' => $affiliated_facility_table));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$id = $row->id;
				}
				return $id;
			}
		}

		public function getHealthFacilitySlugByTableName($affiliated_facility_table){
			$query = $this->db->get_where('health_facility',array('table_name' => $affiliated_facility_table));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$slug = $row->slug;
				}
				return $slug;
			}
		}

		public function getFirstRegionsByCountryId(){
			$query = $this->db->query('SELECT * FROM regions WHERE country_id=1 ORDER BY name ASC');
			if($query->num_rows() > 0){
				return $query->result();
			}
		}

		public function getRegionsByCountryId($country_id){
			$query = $this->db->get_where('regions',array('country_id' => $country_id));
			if($query->num_rows() > 0){
				return $query->result();
			}
		}

		public function getFirstCitiesByRegionAndCountryId(){
			$query = $this->db->get_where('cities',array('country_id' => 1,'region_id' => 2));
			if($query->num_rows() > 0){
				return $query->result();
			}
		}

		public function getCitiesByFirstStateId($id){
			$query = $this->db->get_where('cities',array('region_id' => $id));
			if($query->num_rows() > 0){
				return $query->result();
			}
		}

		public function getFirstStateByCountryId($id){
			// $query = $this->db->get_where('cities',array('country_id' => $id) ,1);
			$query_str = "SELECT * FROM regions WHERE country_id= $id LIMIT 1";
			$query = $this->db->query($query_str);
			if($query->num_rows() == 1){
				return $query->result();
			}
		}

		public function getCitiesByStateId($state_id){
			$query = $this->db->get_where('cities',array('region_id' => $state_id));
			if($query->num_rows() > 0){
				return $query->result();
			}
		}




		//Create Health Facility Account
		public function createUser($user_array){
			$query = $this->db->insert('users',$user_array);
			if($query){
				return true;
			}
			else{
				return false;
			}
		}

		public function updateUserTable($user_array,$id){
			$query = $this->db->update('users',$user_array,array('id' => $id));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function updateUserLastActivity($user_id){
			$query_str = "UPDATE users SET last_activity = NOW() WHERE id=".$user_id;
			if($this->db->query($query_str)){
				return true;
			}else{
				return false;
			}
		}

		public function create_health_facility_account_table($health_facility_table_array,$table_name){
			$query_str = 'CREATE TABLE ' .$table_name.' (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				facility_name VARCHAR(100) NOT NULL,
				user_name VARCHAR(40) NOT NULL,
				user_id INT NOT NULL,
				signature VARCHAR(1000) NOT NULL,
				full_name VARCHAR(200) NOT NULL,
				qualification VARCHAR(200) NOT NULL,
				title VARCHAR(50) NOT NULL,
				hashed VARCHAR(50) NOT NULL,
				dept VARCHAR(100) NOT NULL,
				sub_dept VARCHAR(100) NOT NULL,
				personnel VARCHAR(100) NOT NULL,
				position VARCHAR(50) NOT NULL,
				is_admin INT NOT NULL,
				date VARCHAR(20) NOT NULL,
				time VARCHAR(20) NOT NULL
			)';
			if($this->db->query($query_str)){
				$query = $this->db->insert($table_name,$health_facility_table_array);
				if($query == true){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		public function getAllHealthFaciities(){
			$query = $this->db->get('health_facility');
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function updateRadiologyTestFieldsControlRangeUnit($test_table_name){
			$query_str = "UPDATE ".$test_table_name." SET control_enabled = 0,range_enabled = 0,unit_enabled = 0 WHERE sub_dept_id = 6";
			if($this->db->query($query_str)){
				return true;
			}else{
				return false;
			}
		}

		public function create_health_facility_test_table($table_name){
			if($this->db->table_exists($table_name)){
				$query_str = "DROP TABLE " . $table_name;
				if($this->db->query($query_str)){

				}
			}
			$query_str = 'CREATE TABLE ' .$table_name.' (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				about_test TEXT NOT NULL,
				main_test_id INT NOT NULL DEFAULT 0,
				facility_name VARCHAR(100) NULL,
				under INT NOT NULL,
				sub_dept_id VARCHAR(100) NOT NULL,
				test_id VARCHAR(1000) NOT NULL,
				name TEXT NOT NULL,
				sample_required TEXT NOT NULL,
				indication TEXT NOT NULL,
				cost BIGINT NOT NULL,
				t_a BIGINT NOT NULL,
				pppc TEXT NOT NULL,
				section VARCHAR(50) NOT NULL,
				active INT DEFAULT 1,
				no INT DEFAULT 1 NOT NULL,
				tests TEXT NULL,
				unit VARCHAR(100) NOT NULL,
				range_lower DECIMAL(20,3) NOT NULL DEFAULT 0,
				range_higher DECIMAL(20,3) NOT NULL DEFAULT 0,
				range_enabled INT DEFAULT 1 NOT NULL,
				range_type VARCHAR(100) DEFAULT "interval" NOT NULL,
				desirable_value VARCHAR(1000) DEFAULT ">2" NOT NULL,
				unit_enabled INT DEFAULT 1 NOT NULL,
				control_enabled INT DEFAULT 1 NOT NULL			
			)';
			if($this->db->query($query_str)){
				return true;
			}else{
				return false;
			}
		}

		public function proper_desirable_input_format($desirable_value){
			$desirable_first_char = substr($desirable_value,0,1);
			$desirable_last_chars1 = substr($desirable_value,1);
			
			if($desirable_first_char == ">" || $desirable_first_char == "<"){    
				if(is_numeric($desirable_last_chars1)){
		        	return true;		                   
		        }              
		    }else{
		    	return false;
		    }

		  	
		}

		public function getDefaultTests(){
			$query = $this->db->get('tests');
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getUserAffiliatedFacilities($user_id){
			$query = $this->db->get_where('users',array('id' => $user_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$affiliated_facilities = $row->affiliated_facilities;
				}
				return $affiliated_facilities;
			}else{
				return false;
			}
		}

		public function add_tests($test_table_name,$form_array){
			$query = $this->db->insert($test_table_name,$form_array);
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function create_health_facility_account($health_facility_array){
			$query = $this->db->insert('health_facility',$health_facility_array);
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function getUserRow($user_name){
			$query = $this->db->get_where('users',array('user_name' => $user_name));
			if($query->num_rows() == 1){
				return $query->result();
			}
		}

		public function getHealthFacilityRow($name){
			$query = $this->db->get_where('health_facility',array('name' => $name));
			if($query->num_rows() == 1){
				return $query->result();
			}
		}

		public function getUserIdByEmailAddress($email){
			$query = $this->db->get_where('users',array('email' => $email));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$id = $row->id;
				}
				return $id;
			}else{
				return "";
			}
		}

		public function changeUserPassword($user_id,$token,$new_password){
			$hashed = sha1($new_password);
			$query = $this->db->update('users',array('hashed' => $hashed,'token' => $token),array('id' => $user_id));
			if($query){
				return true;
			}else{
				return false;
			}	
		}

		public function onRegister($user_id,$token){
			$user_id = strtolower($user_id);
			$token = strtolower($token);
			$cookie = $user_id . ':' .$token;
			$mac = $this->encryption->encrypt($cookie);
			$cookie .= ':' .$mac;
			if(setcookie('onehealthlogged',$cookie,time() + 31536000,'/')) {
				return true;
			}
		}

		public function getUserInfoByTableName($table_name,$user_id){
			$query = $this->db->get_where($table_name,array('user_id' => $user_id),1);
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getUserInfoByUserTableName($health_facility_table_name,$user_name){
			$query = $this->db->get_where($health_facility_table_name,array('user_name' => $user_name),1);
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}



		public function getUserInfoByUserName($user_name){
			$query = $this->db->get_where('users',array('user_name' => $user_name));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getUserInfoByUserId($user_id){
			$query = $this->db->get_where('users',array('id' => $user_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getUserInfoBySlug($slug){
			$query = $this->db->get_where('users',array('slug' => $slug));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getUserInfoById($slug){
			$query = $this->db->get_where('users',array('id' => $slug));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getIfUserIdIsValid($partner_id){
			$query = $this->db->get_where('users',array('id' => $partner_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getFirstPostLikes($post_id){
			$user_id = $this->getUserIdWhenLoggedIn();
			$this->db->select("*");
			$this->db->from("posts");
			$this->db->where("id",$post_id);
			$query  = $this->db->get();
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$likes = $row->likes;
				}
				if($likes !== ""){
					$likes_arr = explode(",", $likes);
					$likes_arr = array_reverse($likes_arr);
					// $likes_arr = array_slice($likes_arr,0, 2);
					return $likes_arr;
				}
			}
		}

		public function getIfPostIdIsValid($post_id){
			$query = $this->db->get_where('posts',array('id' => $post_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getConversationsNum($user_id){
			// $query = $this->db->get_where('messages',array('receiver' => $user_id,'received' => 0));
			$this->db->select("*");
			$this->db->from("messages");
			$this->db->where('sender',$user_id);
			$this->db->or_where('receiver',$user_id);
			$this->db->order_by("id","DESC");
			// $this->db->where('received',0);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				// $ret_arr = array('sender' => )
				$rows = array();
				$new_rows = array();
				foreach($query->result() as $row){
					$sender = $row->sender;
					$id = $row->id;
					$date = $row->date;
					$time = $row->time;
					$received = $row->received;
					$date_time = $date . " " . $time;
					$message = $row->message;
					$rows[] = array(
						'sender' => $sender,
						'id' => $id,
						'date_time' => $date_time,
						'received' => $received,
						'message' => $message
					);
				}
				
				// $rows = array_unique($rows,SORT_REGULAR);
				$rows1 = array_unique(array_column($rows, 'sender'));
				// print_r(array_intersect_key($array, $tempArr));
				$rows = array_intersect_key($rows,$rows1);
				$rows = array_values($rows);
				// $rows = array_slice($rows, 0,20);
				// var_dump($rows);	
				$rows = count($rows);			
			}else{
				$rows = 0;
			}
			return $rows;
		}

		public function getConversations($user_id){
			
			$this->db->select("*");
			$this->db->from("messages");
			$this->db->where('sender',$user_id);
			$this->db->or_where('receiver',$user_id);
			$this->db->order_by("id","DESC");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				// $ret_arr = array('sender' => )
				$rows = array();
				$new_rows = array();
				foreach($query->result() as $row){
					$sender = $row->sender;
					$id = $row->id;
					$date = $row->date;
					$time = $row->time;
					$received = $row->received;
					$date_time = $date . " " . $time;
					$message = $row->message;
					$receiver = $row->receiver;
					$rows[] = array(
						'sender' => $sender,
						'id' => $id,
						'date_time' => $date_time,
						'received' => $received,
						'message' => $message,
						'receiver' => $receiver
					);
				}
				
				// $rows = array_unique($rows,SORT_REGULAR);
				$rows1 = array_unique($this->array_column_manual($rows, 'sender'));
				// print_r(array_intersect_key($array, $tempArr));
				$rows = array_intersect_key($rows,$rows1);
				$rows = array_values($rows);
				$rows = array_slice($rows, 0,20);
				// var_dump($rows);				
			}else{
				$rows = false;
			}
			return $rows;
		}

		public function getConversationsRem2($user_id,$offset){
			$this->db->select("*");
			$this->db->from("messages");
			$this->db->where('sender',$user_id);
			$this->db->or_where('receiver',$user_id);
			$this->db->order_by("id","DESC");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				// $ret_arr = array('sender' => )
				// echo $this->db->last_query();
				$rows = array();
				$new_rows = array();
				foreach($query->result() as $row){
					$sender = $row->sender;
					$id = $row->id;
					$date = $row->date;
					$time = $row->time;
					$received = $row->received;
					$date_time = $date . " " . $time;
					$message = $row->message;
					$receiver = $row->receiver;
					$rows[] = array(
						'sender' => $sender,
						'id' => $id,
						'date_time' => $date_time,
						'received' => $received,
						'message' => $message,
						'receiver' => $receiver
					);
				}
				
				// $rows = array_unique($rows,SORT_REGULAR);
				$rows1 = array_unique($this->array_column_manual($rows, 'sender'));
				// var_dump($rows1);
				// print_r(array_intersect_key($array, $tempArr));
				$rows = array_intersect_key($rows,$rows1);
				$rows = array_values($rows);
				// $rows = array_reverse($rows);
				$slice = $offset * 10;

				$rows = array_slice($rows, $slice,10);
				// var_dump($rows);
				
			}else{
				$rows = false;
			}
			return $rows;
		}

		public function array_column_manual($array, $column)
		{
		    $newarr = array();
		    foreach ($array as $row) $newarr[] = $row[$column];
		    return $newarr;
		}

		public function getFirstChatMessages($user_id,$partner_id){
			$this->db->select("*");
			$this->db->from("messages");
			$this->db->where('sender',$user_id);
			$this->db->where('receiver',$partner_id);
			$this->db->or_where('sender',$partner_id);
			$this->db->where('receiver',$user_id);
			$this->db->limit(10);
			$this->db->order_by('id','DESC');
			

			$query = $this->db->get();
			if($query->num_rows() > 0){
				// echo $this->db->last_query();
				return $query->result();
			}else{
				return false;
			}
		}

		public function getSubsequentChatMessages($user_id,$partner_id,$offset){
			$this->db->select("*");
			$this->db->from("messages");
			$this->db->where('sender',$partner_id);
			$this->db->where('receiver',$user_id);
			$this->db->where('received',0);
			$this->db->where('id >',$offset);
			// $this->db->limit(10);
			$this->db->order_by('id','DESC');
			

			$query = $this->db->get();
			if($query->num_rows() > 0){
				// echo $this->db->last_query();
				return $query->result();
			}else{
				// echo $this->db->last_query();
				return false;
			}
		}

		public function getSubsequentChatOlderMessages($user_id,$partner_id,$offset){
			$this->db->select("*");
			$this->db->from("messages");

			$this->db->where('sender',$user_id);
			$this->db->where('receiver',$partner_id);
			$this->db->where('id <',$offset);
			$this->db->or_where('sender',$partner_id);
			$this->db->where('receiver',$user_id);
			$this->db->where('id <',$offset);
			
			$this->db->limit(10);
			$this->db->order_by('id','DESC');
			

			$query = $this->db->get();
			if($query->num_rows() > 0){
				// echo $this->db->last_query();
				return $query->result();
			}else{
				// echo $this->db->last_query();
				return false;
			}
		}

		public function getUserOnlineStatus($partner_id){
			// echo $partner_id;
			$query_str = "SELECT * FROM users WHERE id = ".$partner_id." AND last_activity < SUBTIME(NOW(),'0:0:06')";
			$query = $this->db->query($query_str);
			if($query->num_rows() == 1){
				// echo $this->db->last_query();
				return false;
			}else{
				return true;
			}
		}

		public function updatePostLikes($likes_arr,$post_id){
			if(is_array($likes_arr)){
				$likes = implode(",", $likes_arr);
				if($this->db->update('posts',array('likes' => $likes),array('id' => $post_id))){
					return true;
				}else{
					return false;
				}
			}
		}

		public function commentOnPost($user_id,$post_id,$content,$date,$time){
			$query = $this->db->insert('comments',array('sender' => $user_id,'post_id' => $post_id,'content' => $content,'date' => $date,'time' => $time));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function getNoOfCommentsInPost($post_id){
			$query = $this->db->get_where('comments',array('post_id' => $post_id));
			if($query->num_rows() > 0){
				return $query->num_rows();
			}else{
				return false;
			}
		}


		public function checkIfCommentIdIsValid($comment_id){
			$query = $this->db->get_where('comments',array('id' => $comment_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfPostIdIsValid($post_id){
			$query = $this->db->get_where('posts',array('id' => $post_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}
		
		public function getPostIdByCommentId($comment_id){
			$query = $this->db->get_where('comments',array('id' => $comment_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$post_id = $row->post_id;
				}
				return $post_id;
			}else{
				return false;
			}
		}

		public function checkIfImageIsPartOfPost($post_id,$image_name){
			$query = $this->db->get_where('posts',array('id' => $post_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$images = $row->images;
				}
				if($images !== ""){
					$images_arr = explode(",", $images);
					if(in_array($image_name,$images_arr)){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}
		}

		public function deletePostImage($post_id,$image_name){
			$query = $this->db->get_where('posts',array('id' => $post_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$images = $row->images;
				}
				if($images !== ""){
					$images_arr = explode(",", $images);
					if(in_array($image_name,$images_arr)){
						$index = array_search($image_name, $images_arr);
						unset($images_arr[$index]);
						$images = implode(",", $images_arr);
						$query = $this->db->update('posts',array('images' => $images),array('id' => $post_id));
						if($query){
							unlink('./assets/images/'.$image_name);
							return true;
						}else{
							return false;
						}
					}else{
						return false;
					}
				}else{
					return false;
				}
			}
		}

		public function deletePost($post_id){
			$query = $this->db->get_where('posts',array('id' => $post_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$images = $row->images;
				}
				if($images !== ""){
					$images_arr = explode(",", $images);
					for($i = 0; $i < count($images_arr); $i++){
						$image_name = $images_arr[$i];
						unlink('./assets/images/'.$image_name);
					}
				}	
				$query = $this->db->delete('posts',array('id' => $post_id));
				if($query){
					return true;
				}else{
					return false;
				}		
				
			}else{
				return false;
			}	
		}

		public function deleteCommentsUnderPost($post_id){
			$query = $this->db->delete('comments',array('post_id' => $post_id));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function updatePostContent($post_id,$content){
			$query = $this->db->update('posts',array('content' => $content),array('id' => $post_id));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function getIfUserOwnsThisPost($post_id,$user_id){
			$query = $this->db->get_where('posts',array('id' => $post_id,'sender' => $user_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getIfThereIsStillSpaceForPostUpload($post_id){
			$query = $this->db->get_where('posts',array('id' => $post_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$images = $row->images;
				}
				if($images !== ""){
					$images_arr = explode(",", $images);
					if(count($images_arr) < 5){
						return true;
					}else{
						return false;
					}
				}else{
					return true;
				}
			}else{
				return false;
			}	
		}

		public function getPostById($post_id){
			$query = $this->db->get_where('posts',array('id' => $post_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function checkIfCommentsBatchIsLast($post_id,$offset){
			$this->db->select("*");
			$this->db->from("comments");

			$this->db->where('post_id',$post_id);
			
			$this->db->where('id <',$offset);
			
			$this->db->limit(10);
			$this->db->order_by('id','DESC');
			

			$query = $this->db->get();
			if($query->num_rows() == 10){
				// echo $this->db->last_query();
				return false;
			}else{
				// echo $this->db->last_query();
				return true;
			}
		}



		public function getSubsequentCommentsOnPost($post_id,$offset){
			$this->db->select("*");
			$this->db->from("comments");

			$this->db->where('post_id',$post_id);
			
			$this->db->where('id <',$offset);
			
			$this->db->limit(10);
			$this->db->order_by('id','DESC');
			

			$query = $this->db->get();
			if($query->num_rows() > 0){
				// echo $this->db->last_query();
				return $query->result();
			}else{
				// echo $this->db->last_query();
				return false;
			}
		}

		public function getNotifsPerPage($user_name,$page){
			$offset = $page * 10;
			$this->db->select("*");
			$this->db->from('notif');
			$this->db->where('receiver',$user_name);
			$this->db->order_by('id','DESC');
			$this->db->limit(10,$offset);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getAllNotifsCount($user_name){
			$this->db->select("*");
			$this->db->from("notif");
			$this->db->where("receiver",$user_name);
			$query = $this->db->get();
			return $query->num_rows();
		}


		public function getSubsequentPostsUserProfile($user_id,$offset){
			$this->db->select("*");
			$this->db->from("posts");
			$this->db->where('sender',$user_id);
			$this->db->where('id <',$offset);
			
			$this->db->limit(2);
			$this->db->order_by('id','DESC');
			

			$query = $this->db->get();
			if($query->num_rows() > 0){
				// echo $this->db->last_query();
				return $query->result();
			}else{
				// echo $this->db->last_query();
				return false;
			}
		}

		public function getUserLogoById($sender){
			$query = $this->db->get_where('users',array('id' => $sender));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$logo = $row->logo;
				}
				if(is_null($logo)){
					$logo = base_url('assets/images/avatar.jpg');
				}else{
					$logo = base_url('assets/images/'.$logo);
				}
				return $logo;
			}else{
				return false;
			}
		}

		public function getMainUserPostById($post_id){
			$query = $this->db->get_where('posts',array('id' => $post_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function updateCommentLikes($likes_arr,$comment_id){
			if(is_array($likes_arr)){
				$likes = implode(",", $likes_arr);
				if($this->db->update('comments',array('likes' => $likes),array('id' => $comment_id))){
					return true;
				}else{
					return false;
				}
			}
		}

		public function getUserTotalPostsNum($user_id){
			$query = $this->db->get_where('posts',array('sender' => $user_id));
			return $query->num_rows();
		}

		public function getUserTotalFollowersNum($user_id){
			$query = $this->db->get_where('users',array('id' => $user_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$followers = $row->followers;
				}
				if($followers !== ""){
					$followers_arr = explode(",",$followers);
					$followers_arr = array_unique($followers_arr);
					$followers = implode(",", $followers_arr);
					if($this->db->update('users',array('followers' => $followers),array('id' => $user_id))){

					}else{
						return false;
					}
					// print_r($followers_arr);
					if(!empty($followers_arr)){
						$foller_num = count($followers_arr);
					}else{
						$foller_num = 0;
					}
				}else{
					$foller_num = 0;
				}
				return $foller_num;
			}else{
				return false;
			}
		}

		public function getUserTotalFollowers($user_id){
			$query = $this->db->get_where('users',array('id' => $user_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$followers = $row->followers;
				}
				if($followers !== ""){
					$followers_arr = explode(",",$followers);
					$followers_arr = array_unique($followers_arr);
					$followers = implode(",", $followers_arr);
					if($this->db->update('users',array('followers' => $followers),array('id' => $user_id))){

					}else{
						return false;
					}
					// print_r($followers_arr);
					if(!empty($followers_arr)){
						$foller_num = count($followers_arr);
						return $followers_arr;
					}else{
						$foller_num = 0;
					}
				}else{
					$foller_num = 0;
				}
				return false;
			}else{
				return false;
			}
		}

		public function makePost($form_array){
			$query = $this->db->insert('posts',$form_array);
			if($query){
				set_cookie('send_post_id',$this->db->insert_id(),36000);
				return true;

			}else{
				return false;
			}
		}

		public function convertToWebp($file,$ext,$file_no_ext){
			// $file='hnbrnocz.jpg';
			if($ext == "jpg" || $ext == "jpeg"){
				$image=  imagecreatefromjpeg($file);
				
				
			}elseif($ext == "gif"){
				$image=  imagecreatefromgif($file);
				
				
			}elseif($ext == "png"){
				$image=  imagecreatefrompng($file);
				
				
			}
			
			
			imagewebp($image,$file_no_ext.'.webp',100);
			

		}

		public function getAllPostsByUser($user_id){
			$this->db->select("*");
			$this->db->from("posts");
			$this->db->where("sender",$user_id);
			$this->db->order_by("id","DESC");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getFirstTenPostsByUser($user_id){
			$this->db->select("*");
			$this->db->from("posts");
			$this->db->where("sender",$user_id);
			$this->db->limit(2);
			$this->db->order_by("id","DESC");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getFirstFiveCommentsOnPost($post_id){
			// $query = $this->db->get_where('comments',array('post_id' => $post_id),5);
			$this->db->select("*");
			$this->db->from("comments");
			$this->db->where("post_id",$post_id);
			$this->db->limit(5);
			$this->db->order_by("id","DESC");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}


		public function updatePostImages($file_name,$post_id,$user_id){
			$query = $this->db->get_where('posts',array('id' => $post_id,'sender' => $user_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$images = $row->images;
				}
				if($images == ""){
					$new_images = $file_name;
				}else{
					$images_arr = explode(",",$images);
					$new_images = $images . "," . $file_name;
				}
				if($this->db->update('posts',array('images' => $new_images),array('id' => $post_id))){
					return true;
				}else{
					return false;
				}

			}
		}

		public function getUserTotalFollowingNum($user_id){
			$query = $this->db->get_where('users',array('id' => $user_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$followers = $row->following;
				}
				if($followers !== ""){
					$followers_arr = explode(",",$followers);
					$followers_arr = array_unique($followers_arr);
					$following = implode(",", $followers_arr);					
					if($this->db->update('users',array('following' => $following),array('id' => $user_id))){
						
					}else{
						return false;
					}
					if(!empty($followers_arr)){
						$foller_num = count($followers_arr);
					}else{
						$foller_num = 0;
					}
				}else{
					$foller_num = 0;
				}
				return $foller_num;
			}else{
				return false;
			}
		}

		public function getUserTotalFollowing($user_id){
			$query = $this->db->get_where('users',array('id' => $user_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$followers = $row->following;
				}
				if($followers !== ""){
					$followers_arr = explode(",",$followers);
					$followers_arr = array_unique($followers_arr);
					$following = implode(",", $followers_arr);					
					if($this->db->update('users',array('following' => $following),array('id' => $user_id))){
						
					}else{
						return false;
					}
					if(!empty($followers_arr)){
						$foller_num = count($followers_arr);
						return $followers_arr;
					}else{
						$foller_num = 0;
					}
				}else{
					$foller_num = 0;
				}
				return $foller_num;
			}else{
				return false;
			}
		}


		public function checkIfUserIsAlreadyFollowedByUser($user_id,$partner_id){
			$query = $this->db->get_where('users',array('id' => $partner_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$followers = $row->followers;
				}
				$followers_arr = explode(",", $followers);
				if(in_array($user_id, $followers_arr)){
					return false;
				}else{
					return true;
				}
			}	
		}

		public function checkIfUserIsFollowingUser($user_id,$partner_id){
			$query = $this->db->get_where('users',array('id' => $user_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$following = $row->following;
				}
				$following_arr = explode(",", $following);
				// print_r($following_arr);
				if(in_array($partner_id, $following_arr)){
					return false;
				}else{
					return true;
				}
			}	
		}

		public function checkIfUserHasAlreadyLikedPost($user_id,$post_id){
			$query = $this->db->get_where('posts',array('id' => $post_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$likes = $row->likes;
				}
				$likes_arr = explode(",", $likes);
				if(in_array($user_id, $likes_arr)){
					return false;
				}else{
					return true;
				}
			}	
		}

		public function followUser($user_id,$partner_id){
			$query = $this->db->get_where('users',array('id' => $partner_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$followers = $row->followers;
				}
				if($followers == ""){
					$followers = $user_id;
				}else{
					$followers = $followers . ",".$user_id;
				}
				$query = $this->db->get_where('users',array('id' => $user_id));
				if($query->num_rows() == 1){
					foreach($query->result() as $row){
						$following = $row->following;
					}
					if($following == ""){
						$following = $partner_id;
					}else{
						$following = $following . ",".$partner_id;
					}

					if($this->db->update('users',array('followers' => $followers),array('id' => $partner_id))){
						if($this->db->update('users',array('following' => $following),array('id' => $user_id))){
							return true;
						}else{
							return false;
						}
					}else{
						return false;
					}
				}else{
					return false;
				}	
			}else{
				return false;
			}
		}

		public function likePost($user_id,$post_id){
			$query = $this->db->get_where('posts',array('id' => $post_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$likes = $row->likes;
				}
				if($likes == ""){
					$likes = $user_id;
				}else{
					$likes = $likes . ",".$user_id;
				}
				
				if($this->db->update('posts',array('likes' => $likes),array('id' => $post_id))){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		public function unfollowUser($user_id,$partner_id){
			$query = $this->db->get_where('users',array('id' => $partner_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$followers = $row->followers;
				}
				if($followers == ""){
					return false;
				}else{
					$followers_arr = explode(",", $followers);
					if(in_array($user_id, $followers_arr)){
						$index = array_search($user_id, $followers_arr);
						unset($followers_arr[$index]);
						// print_r($followers_arr)
						if(empty($followers_arr)){
							$followers = "";
						}else{
							$followers = implode(",", $followers_arr);
						}
					}
				}
				$query = $this->db->get_where('users',array('id' => $user_id));
				if($query->num_rows() == 1){
					foreach($query->result() as $row){
						$following = $row->following;
					}
					if($following == ""){
						return false;
					}else{
						$following_arr = explode(",", $following);
						if(in_array($partner_id, $following_arr)){
							$index = array_search($partner_id, $followers_arr);
							unset($following_arr[$index]);
							if(empty($following_arr)){
								$following = "";
							}else{
								$following = implode(",", $following_arr);
							}
						}
					}
					
					if($this->db->update('users',array('followers' => $followers),array('id' => $partner_id))){
						if($this->db->update('users',array('following' => $following),array('id' => $user_id))){
							return true;
						}else{
							return false;
						}
					}else{
						return false;
					}
				}else{
					return false;
				}	
			}else{
				return false;
			}
		}

		public function unlikePost($user_id,$post_id){
			$query = $this->db->get_where('posts',array('id' => $post_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$likes = $row->likes;
				}
				if($likes == ""){
					return false;
				}else{
					$likes_arr = explode(",", $likes);
					if(in_array($user_id, $likes_arr)){
						$index = array_search($user_id, $likes_arr);
						unset($likes_arr[$index]);
						// print_r($followers_arr)
						if(empty($likes_arr)){
							$likes = "";
						}else{
							$likes = implode(",", $likes_arr);
						}
					}
				}
				
				if($this->db->update('posts',array('likes' => $likes),array('id' => $post_id))){
					return true;
				}else{
					return false;
				}
					
			}else{
				return false;
			}
		}
		public function sendChatMessage($form_array){
			if($this->db->insert('messages',$form_array)){
				return $this->db->insert_id();
			}else{
				return false;
			}
		}

		public function socialMediaFormatNum($num){
			if($num >= 1000 && $num < 1000000){
				$num = round($num / 1000,2);
				$num = $num . 'K';
			}elseif($num >= 1000000 && $num < 1000000000){
				$num = round($num / 1000000,2);
				$num = $num . 'M';
			}elseif($num >= 1000000000 && $num < 1000000000000){
				$num = round($num / 1000000000,2);
				$num = $num . 'B';
			}elseif($num >= 1000000000000){
				$num = round($num / 1000000000000,2);
				$num = $num . 'T';
			}
			return $num;
		}

		public function updateMessageAsRead($id){
			$query = $this->db->update('messages',array('received' => 1),array('id' => $id));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function getAllHealthFacilities(){
			$query = $this->db->get("health_facility");
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getFacilityStructures(){
			$query = $this->db->get("health_facility_structure");
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}	
		}



		public function checkIfUserIsAnAdmin($health_facility_table_name,$user_id){
			// echo $user_id;
			// echo $health_facility_table_name;
			// echo $user_id;
			$query = $this->db->get_where($health_facility_table_name,array('user_id' => $user_id,'is_admin' => 1));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}



		public function getUserInfoHealthFacilityById($affiliated_facility_table,$user_id){
			if($this->db->table_exists($affiliated_facility_table)){
				$query = $this->db->get_where($affiliated_facility_table,array('user_id' => $user_id));
				if($query->num_rows() > 0){
					return $query->result();
				}else{
					return false;
				}
			}else{
				$query = $this->db->get_where('users',array('id' => $user_id));
				if($query->num_rows() == 1){
					foreach($query->result() as $row){
						$affiliated_facilities = $row->affiliated_facilities;
						$affiliated_facilities_arr = explode(",", $affiliated_facilities);
						$index = array_search($affiliated_facility_table,$affiliated_facilities_arr);
						unset($affiliated_facilities_arr[$index]);
						$affiliated_facilities = implode(",", $affiliated_facilities_arr);
						$user_array = array(
							'affiliated_facilities' => $affiliated_facilities
						);
						$this->updateUserTable($user_array,$user_id);
					}
				}else{
					return false;
				}
			}
		}

		public function checkIfBankDetailsAreSet($health_facility_name,$health_facility_id){
			$query = $this->db->get_where('health_facility',array('id' => $health_facility_id,'name' => $health_facility_name,'bank_name' => 0,'account_number' => NULL));
			// print_r($query->result());
			if($query->num_rows() == 1){
				return false;
			}else{
				return true;
			}
		}

		public function getSubDeptIdByLabId($health_facility_test_result_table,$lab_id){
			$query = $this->db->get_where($health_facility_test_result_table,array('lab_id' => $lab_id),1);
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$sub_dept_id = $row->sub_dept_id;
				}
				return $sub_dept_id;
			}else{
				return "";
			}
		}

		public function getSubDeptNameBySlug($slug,$dept_id){
			$query = $this->db->get_where('sub_dept',array('slug' => $slug,'dept_id' => $dept_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$name = $row->name;
				}
				return $name;
			}else{
				return "";
			}
		}

		public function insertInMortuary($form_array){
			return $this->db->insert("mortuary",$form_array);
		}

		public function getSubDeptNameById($id){
			$query = $this->db->get_where('sub_dept',array('id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$name = $row->name;
				}
				return $name;
			}else{
				return "";
			}
		}

		public function getDeptNameById($id){
			$query = $this->db->get_where('dept',array('id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$name = $row->name;
				}
				return $name;
			}else{
				return "";
			}
		}


		public function getPersonnelNameBySlug($slug,$dept_id,$sub_dept_id){
			$query = $this->db->get_where('personnel',array('slug' => $slug,'dept_id' => $dept_id,'sub_dept_id' => $sub_dept_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$name = $row->name;
				}
				return $name;
			}else{
				return "";
			}
		}

		public function getDeptNameBySlug($slug){
			$query = $this->db->get_where('dept',array('slug' => $slug));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$name = $row->name;
				}
				return $name;
			}else{
				return "";
			}
		}

		public function checkIfUserIsAPatient($health_facility_table_name,$user_id){
			$query = $this->db->get_where($health_facility_table_name,array('user_id' => $user_id,'position' => 'patient'));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function confirmHealthFacilityIdAndSlug($health_facility_id,$health_facility_slug){
			// echo $health_facility_id . ' ' .$health_facility_slug;
			$query = $this->db->get_where('health_facility',array('id' => $health_facility_id,'slug' => $health_facility_slug));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfUserIsAnAdminBool($health_facility_table_name,$user_name){
			$query = $this->db->get_where($health_facility_table_name,array('user_name' => $user_name,'is_admin' => 1));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfEmailIsInId($email,$user_id){
			$query = $this->db->get_where('users',array('id' => $user_id,'email' => $email));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfUserIsAnAdminOrSubAdminBool($health_facility_table_name,$user_name){
			$this->db->select("*");
			$this->db->from($health_facility_table_name);
			$this->db->where('user_name = "'.$user_name.'" AND position = "admin" OR position = "sub_admin"');
			$query = $this->db->get();
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfUserIsMainAdminBool($health_facility_table_name,$user_name){
			$query = $this->db->get_where($health_facility_table_name,array('user_name' => $user_name,'is_admin' => 1,'position' => 'admin'));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getAllPaidTests($health_facility_table_name,$sub_dept_id){
			// $query = $this->db->get_where($health_facility_table_name,array('paid' => 1));
			if($this->db->table_exists($health_facility_table_name)){
				$this->db->select("*");
				$this->db->from($health_facility_table_name);
				$this->db->where('paid',1);
				$this->db->where('sub_dept_id',$sub_dept_id);
				$this->db->order_by('id','DESC');
				$query = $this->db->get();
				if($query->num_rows() > 0){
					return $query->result();
				}else{
					return false;
				}
			}else{
				$query_str = 'CREATE TABLE ' .$health_facility_test_result_table.' (
					id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					main_test_id INT NOT NULL,
					facility_name VARCHAR(100) NOT NULL,
					referring_facility_id INT NULL,
					record_id INT NULL,
					ward_id INT NULL,
					referral_id INT NULL,
					initiation_code VARCHAR(100) NOT NULL,
					lab_id TEXT NULL,
					sub_dept_id INT NOT NULL,
					test_id VARCHAR(1000) NOT NULL,
					receptionist INT NULL,
					teller INT NULL,
					receipt_file TEXT NULL,
					patient_name VARCHAR(200) NOT NULL,
					test_name TEXT NOT NULL,
					patient_username VARCHAR(100) NULL,
					patient_email VARCHAR(50) NULL,
					price BIGINT(20) NOT NULL,
					amount_paid BIGINT(20) NOT NULL,
					ta_time BIGINT(20) NOT NULL,
					date VARCHAR(100) NOT NULL,
					time VARCHAR(100) NOT NULL,
					invalid INT NOT NULL DEFAULT 0,
					paid INT NOT NULL DEFAULT 0,
					date_paid VARCHAR(50) NOT NULL,
					time_paid VARCHAR(50) NOT NULL,
					refund_requested INT NOT NULL DEFAULT 0,
					refund_request_code TEXT NULL,
					payment_initiated INT DEFAULT 0 NOT NULL,
					patient_locked INT DEFAULT 0 NOT NULL,
					registered INT DEFAULT 0

				)';
				if($this->db->query($query_str)){
					$this->db->select("*");
					$this->db->from($health_facility_table_name);
					$this->db->where('paid',1);
					$this->db->where('sub_dept_id',$sub_dept_id);
					$this->db->order_by('id','DESC');
					$query = $this->db->get();
					if($query->num_rows() > 0){
						return $query->result();
					}else{
						return false;
					}
				}else{
					return false;
				}
			}
		}

		

		public function getPdfTestsRowsResult($health_facility_main_test_result_table,$lab_id){
			$query = $this->db->get_where($health_facility_main_test_result_table,array('lab_id' => $lab_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function checkIfPatientIsAUser($health_facility_patient_db_table,$lab_id){
			$query = $this->db->get_where($health_facility_patient_db_table,array('lab_id' => $lab_id,'patient_username' => NULL),1);
			if($query->num_rows() == 1){
				// echo "string";
				return false;
			}else{
				return true;
			}
		}

		public function getPaidPatients($health_facility_patient_db_table,$form_array,$sub_dept_id){
			$form_array = array_merge($form_array,array('sub_dept_id' => $sub_dept_id));
			$query = $this->db->get_where($health_facility_patient_db_table,$form_array);
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getPatientsTests($health_facility_patient_db_table,$form_array,$sub_dept_id){
			$form_array = array_merge($form_array,array('sub_dept_id' => $sub_dept_id));
			$query = $this->db->get_where($health_facility_patient_db_table,$form_array);
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getPatientsTestRecordsWherePathologistHasEnteredComment($health_facility_patient_db_table,$sub_dept_id){		
			$this->db->select("*");
			$this->db->from($health_facility_patient_db_table);
			$this->db->where("sub_dept_id" ,$sub_dept_id);
			$this->db->where("verified",1);
			$this->db->where("pathologists_comment !=","");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function keysToLower($obj){
		    if(is_object($obj))
		    {
		        $newobj = (object) array();
		        foreach ($obj as $key =>$val)
		            $newobj->{strtolower($key)} = $this->keysToLower($val);
		        $obj=$newobj;
		    }
		    else if(is_array($obj))
		        foreach($obj as $value)
		            keysToLower($value);
		    return $obj;
		}

		public function uploadResultPerTest($health_facility_main_test_result_table,$test_name,$result_value,$lab_id,$health_facility_id,$health_facility_name,$fourth_addition,$health_facility_table_name,$user_id){
			$date = date("j M Y");
			$time = date("h:i:sa");
			if($this->db->table_exists($health_facility_main_test_result_table)){
				$query = $this->db->get_where($health_facility_main_test_result_table,array('test_name' => $test_name,'lab_id' => $lab_id));
				// echo $this->db->last_query();
				if($query->num_rows() > 0){

					$query = $this->db->update($health_facility_main_test_result_table,array('test_result' => $result_value,'submitted' => 1),array('test_name' => $test_name,'lab_id' => $lab_id));
					if($query){
						$form_array1 = array(
							'test_ready' => 1,
							'result_entered' => 1,
							'verification_date' => $date,
							'verification_time' => $time 
						);
						
						$health_facility_patient_db_table = $this->onehealth_model->createTestPatientTableHeaderString($health_facility_id,$health_facility_name);
						
						if($this->onehealth_model->updatePatientTestFields($form_array1,$health_facility_patient_db_table,$lab_id)){
							if(is_null($this->onehealth_model->getLabTwoPersonnelForPatient($health_facility_patient_db_table,$lab_id)) && $fourth_addition == "laboratory-officer-2"){
								if(!$this->onehealth_model->checkIfUserIsAdminOrSubAdminUserId($health_facility_table_name,$user_id)){
									$lab_two = $user_id;
								}else{
									$lab_two = NULL;
								}
								$form_array = array(
									'lab_two' => $lab_two
								);
								
								$this->onehealth_model->updatePatientBioDataTable($form_array,$health_facility_patient_db_table,$lab_id);
							}
						}
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}
		}

		public function getAllPatientsTestsByLabId($health_facility_main_test_result_table,$health_facility_test_table_name,$lab_id){
			$ret_arr = array();
			$ret_arr['lab_id'] = $lab_id;
			$query = $this->db->get_where($health_facility_main_test_result_table,array('lab_id' => $lab_id));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$id = $row->id;
					$main_test_id = $row->main_test_id;
					if($this->onehealth_model->checkIfTestNativeIdExists($main_test_id,$health_facility_test_table_name)){
						$test_name = $row->test_name;
						$rand = mt_rand(1000,500000);
						$rand = $rand / 1000;
						$rand = (String) $rand;
						if(!$this->checkIfTestResultTestHasSubTests($health_facility_main_test_result_table,$id)){
							
						}else{
							$ret_arr[$test_name] = $rand;
						}
					}
				}
			}
			
			return $ret_arr;
		}

		public function getAllPatientsTests($health_facility_patient_db_table,$sub_dept_id){
			
			$query = $this->db->get_where($health_facility_patient_db_table,array('sub_dept_id' => $sub_dept_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getIfPatientResultIsVerified($health_facility_patient_db_table,$lab_id){
			$query = $this->db->get_where($health_facility_patient_db_table,array('lab_id' => $lab_id,'verified' => 1));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getDateOfVerification($health_facility_patient_db_table,$lab_id){
			$query = $this->db->get_where($health_facility_patient_db_table,array('lab_id' => $lab_id,'verified' => 1));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$date_of_verification = $row->date_of_verification;
				}
				if(is_null($date_of_verification)){
					return "";
				}
				return $date_of_verification;
			}else{
				return false;
			}
		}

		public function getLabTwoPersonnelForPatient($health_facility_patient_db_table,$lab_id){
			$query = $this->db->get_where($health_facility_patient_db_table,array('lab_id' => $lab_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$lab_two = $row->lab_two;
				}
				return $lab_two;
			}else{
				return NULL;
			}
		}

		public function getClericalPersonnelForPatient($health_facility_patient_db_table,$lab_id){
			$query = $this->db->get_where($health_facility_patient_db_table,array('lab_id' => $lab_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$lab_two = $row->clerk;
				}
				return $lab_two;
			}else{
				return NULL;
			}
		}

		public function getPatientUserNameByLabId($health_facility_patient_db_table,$lab_id){
			$query = $this->db->get_where($health_facility_patient_db_table,array('lab_id' => $lab_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$patient_username = $row->patient_username;
				}
				return $patient_username;
			}else{
				return NULL;
			}
		}

		// public function getUserNameByInitiationCode($health_facility_test_result_table,$initiation_code){
		// 	$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code),1);
		// 	if($query->num_rows() == 1){
		// 		foreach($query->result() as $row){
		// 			$patient_username = $row->patient_username;
		// 		}
		// 		ret 
		// 	}
		// }

		public function getPathologistPersonnelForPatient($health_facility_patient_db_table,$lab_id){
			$query = $this->db->get_where($health_facility_patient_db_table,array('lab_id' => $lab_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$lab_two = $row->pathologist_id;
				}
				return $lab_two;
			}else{
				return NULL;
			}
		}

		public function getSupervisorPersonnelForPatient($health_facility_patient_db_table,$lab_id){
			$query = $this->db->get_where($health_facility_patient_db_table,array('lab_id' => $lab_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$lab_two = $row->supervisor;
				}
				return $lab_two;
			}else{
				return NULL;
			}
		}

		public function updateHealthFacilityPatientBioData($form_array,$lab_id,$health_facility_patient_db_table){
			$query = $this->db->get_where($health_facility_patient_db_table,$form_array,array('lab_id' => $lab_id));
			if($query){
				return true;
			}else{
				return false;
			}
		}


		public function updatePatientRecord($health_facility_table_name,$form_array,$initiation_code,$sub_dept_id){
			
				$query = $this->db->update($health_facility_table_name,$form_array,array('initiation_code' => $initiation_code,'sub_dept_id' => $sub_dept_id));
				if($query){
					return true;
				}else{
					return false;
				}
			
		}

		public function addPatientRecord($health_facility_table_name,$form_array){
			if($this->db->table_exists($health_facility_table_name)){
				$query = $this->db->insert($health_facility_table_name,$form_array);
				if($query){
					return true;
				}else{
					return false;
				}
			}else{
				$query_str = 'CREATE TABLE ' .$health_facility_table_name.' (
					id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					sub_dept_id INT NOT NULL,
					initiation_code VARCHAR(50) NOT NULL,
					lab_id TEXT NOT NULL,
					result_file TEXT NOT NULL,
					receptionist INT NULL,
					teller INT NULL,
					clerk INT NUll,
					lab_three INT NULL,
					lab_two INT NULL,
					supervisor INT NULL,
					pathologist_id INT NULL,
					patient_username VARCHAR(50) NULL,
					patient_name VARCHAR(200) NOT NULL,
					firstname VARCHAR(50) NOT NULL,
					surname VARCHAR(50) NOT NULL,
					dob VARCHAR(50) NOT NULL,
					age INT NOT NULL,
					age_unit VARCHAR(50) NOT NULL,
					sex VARCHAR(50) NOT NULL,
					race VARCHAR(50) NOT NULL,
					mobile_no BIGINT NOT NULL,
					email VARCHAR(50) NOT NULL,
					height VARCHAR(50) NOT NULL,
					weight INT NOT NULL,
					present_medications TEXT NOT NULL,
					fasting INT NOT NULL,
					sample VARCHAR(300) NOT NULL,
					sample_other VARCHAR(200) NOT NULL,
					date_of_request VARCHAR(50) NOT NULL,
					referring_dr VARCHAR(50) NOT NULL,
					consultant VARCHAR(70) NOT NULL,
					consultant_email VARCHAR(50) NOT NULL,
					consultant_mobile BIGINT NOT NULL,
					pathologist VARCHAR(100) NOT NULL,
					pathologist_email VARCHAR(60) NOT NULL,
					pathologist_mobile BIGINT NOT NULL,
					address TEXT NOT NULL,
					created INT NOT NULL,
					date_created VARCHAR(50) NOT NULL,
					time_created VARCHAR(50) NOT NULL,
					clinical_summary TEXT NOT NULL,
					lmp VARCHAR(50) NOT NULL,
					sampling_time VARCHAR(50) NOT NULL,
					separation_time VARCHAR(50) NOT NULL,
					observation TEXT NOT NULL,
					sampled INT DEFAULT 0 NOT NULL,
					controls TEXT NULL,
					result_entered INT DEFAULT 0 NOT NULL,
					sample_rejected INT DEFAULT 0 NOT NULL,
					sample_rejected_date VARCHAR(50) NOT NULL,
					sample_rejected_time VARCHAR(50) NOT NULL,
					sample_replaced INT DEFAULT 0 NOT NULL,
					sample_replaced_date VARCHAR(50) NOT NULL,
					sample_replaced_time VARCHAR(50) NOT NULL,
					verified INT DEFAULT 0 NOT NULL,
					verification_date VARCHAR(50) NULL,
					verification_time VARCHAR(50) NULL,
					date_of_verification VARCHAR(50) NULL,
					test_ready INT DEFAULT 0 NOT NULL,
					pathologists_comment TEXT NOT NULL,
					printed INT DEFAULT 0 NOT NULL,
					zipped INT DEFAULT 0 NOT NULL
				)';
				if($this->db->query($query_str)){
					$query = $this->db->insert($health_facility_table_name,$form_array);
					if($query){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				} 
			}
		}


		public function getReceptionistIdFromInitiatedTest($health_facility_test_result_table,$initiation_code){
			$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code),1);
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$receptionist = $row->receptionist;
				}
				if(is_null($receptionist)){
					$receptionist = NULL;
				}
				return $receptionist;
			}else{
				return NULL;
			}
		}


		public function getPatientInfo($health_facility_patient_db_table,$sub_dept_id){
			$query = $this->db->get_where($health_facility_patient_db_table,array('created' => 1,'sub_dept_id' => $sub_dept_id,'sampled' => 0));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getIfPathologistCommentIsAdded($health_facility_patient_db_table,$lab_id){
			$query = $this->db->get_where($health_facility_patient_db_table,array('lab_id' => $lab_id,'pathologists_comment' => ''));
			if($query->num_rows() == 1){
				return false;
			}else{
				return true;
			}
		}

		public function getIfResultIsZipped($health_facility_patient_db_table,$lab_id){
			$query = $this->db->get_where($health_facility_patient_db_table,array('zipped' => 0,'lab_id' => $lab_id));
				if($query->num_rows() == 1){
					return false;
				}else{
					return true;
				}
		}

		public function getIfUserIsPathologist($health_facility_table_name,$user_id,$dept,$sub_dept){
			$query = $this->db->get_where($health_facility_table_name,array('user_id' => $user_id,'position' => 'personnel', 'personnel' => 'pathologist','dept' => $dept,'sub_dept' => $sub_dept));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function updateSupervisorSignature($health_facility_table_name,$user_id,$dept,$sub_dept,$target_file_name){
			$query = $this->db->update($health_facility_table_name,array('signature' => $target_file_name),array('user_id' => $user_id,'personnel' => 'laboratory-supervisor','dept' => $dept,'sub_dept' => $sub_dept));
			if($query){
				return true;
			}else{
				return false;
			}
		}


		public function updatePersonnelSignature($health_facility_table_name,$user_id,$target_file_name){
			$query = $this->db->update($health_facility_table_name,array('signature' => $target_file_name),array('user_id' => $user_id));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function getFacilitySlugById($facility_id){
			$query = $this->db->get_where('health_facility',array('id' => $facility_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$slug = $row->slug;
				}
				return $slug;
			}else{
				return false;
			}
		}

		public function updatePathologistSignature($health_facility_table_name,$user_id,$dept,$sub_dept,$target_file_name){
			$query = $this->db->update($health_facility_table_name,array('signature' => $target_file_name),array('user_id' => $user_id,'personnel' => 'pathologist','dept' => $dept,'sub_dept' => $sub_dept));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfFacilityHasLetterHeadingEnabled($facility_id){
			if($facility_id == 0){
				return true;
			}
			$query = $this->db->get_where('health_facility',array('id' => $facility_id,'letter_heading' => 1));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		

		public function getSupervisorSignature($health_facility_table_name,$user_id,$dept,$sub_dept){
			$query = $this->db->get_where($health_facility_table_name,array('user_id' => $user_id,'personnel' => 'laboratory-supervisor','dept' => $dept,'sub_dept' => $sub_dept));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$signature = $row->signature;
					// echo $signature;
				}
				return $signature;
			}else{
				return false;
			}
		}

		public function getPathologistSignature($health_facility_table_name,$user_id,$dept,$sub_dept){
			$query = $this->db->get_where($health_facility_table_name,array('user_id' => $user_id,'personnel' => 'pathologist','dept' => $dept,'sub_dept' => $sub_dept));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$signature = $row->signature;
					// echo $signature;
				}
				return $signature;
			}else{
				return false;
			}
		}

		public function getIfUserIsSupervisor($health_facility_table_name,$user_id,$dept,$sub_dept){
			$query = $this->db->get_where($health_facility_table_name,array('user_id' => $user_id,'position' => 'personnel', 'personnel' => 'laboratory-supervisor','dept' => $dept,'sub_dept' => $sub_dept));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getTotalAmountPaidInFacilityPerDay($health_facility_id,$date){
			$total_amount = 0;
			$query = $this->db->get_where('clinic_payments',array('health_facility_id' => $health_facility_id,'date' => $date));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$amount_paid = $row->amount_paid;
					$total_amount += $amount_paid;
				}
			}else{
				return false;
			}
			return $total_amount;
		}

		public function getNumberOfPaymentsInFacilityPerDay($health_facility_id,$date){
			
			$query = $this->db->get_where('clinic_payments',array('health_facility_id' => $health_facility_id,'date' => $date));
			return $query->num_rows();
		}

		public function getLastTimeOfPaymentsInFacilityPerDay($health_facility_id,$date){
			$this->db->select("time");
			$this->db->from("clinic_payments");
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->where('date',$date);
			$this->db->order_by("id","DESC");
			$this->db->limit(1);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$time = $row->time;
					return $time;
				}
			}else{
				return false;
			}
		}

		public function getClinicPaymentHistoryForFacilityDaily($health_facility_id){
			// $query  = $this->db->get_where('clinic_payments',array('health_facility_id' => $health_facility_id));
			$ret = array();
			$this->db->select("date");
			$this->db->from("clinic_payments");
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->order_by("id","DESC");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$date = $row->date;
					$ret[] = $date;
				}
				$ret = array_values(array_unique($ret));
			}else{
				return $ret;
			}
			return $ret;
		}

		public function getClinicPaymentHistoryForFacilityByDate($health_facility_id,$date){
			$this->db->select("*");
			$this->db->from("clinic_payments");
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->where('date',$date);
			$this->db->order_by("id","DESC");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function disablePaymentHistory($health_facility_id,$id){
			$query = $this->db->update('payment_logs',array('disabled' => 1),array('health_facility_id' => $health_facility_id,'id' => $id));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function disableAllPaymentHistory($health_facility_id){
			$query = $this->db->update('payment_logs',array('disabled' => 1),array('health_facility_id' => $health_facility_id,'disabled' => 0));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function getFacilitiesGrandTotalEarnings($health_facility_id){
			$total = 0;
			$query = $this->db->get_where("payment_logs",array('health_facility_id' => $health_facility_id));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$amount = $row->amount;
					$total += $amount;
				}
			}
			return $total;
		}

		public function getFacilityParamById($param,$health_facility_id){
			$query = $this->db->get_where("health_facility",array('id' => $health_facility_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}
		}

		public function getFacilityPaymentHistoryForFacility($health_facility_id){
			// $query = $this->db->get_where('payment_logs',array('health_facility_id' => $health_facility_id,'disabled' => 0));
			$this->db->select("*");
			$this->db->from("payment_logs");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("disabled",0);
			$this->db->order_by("id","DESC");
			$query =  $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getIfUserIsMainAdmin($health_facility_table_name,$user_id){
			$query = $this->db->get_where($health_facility_table_name,array('user_id' => $user_id,'position' => 'admin' , 'is_admin' => 1));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function saveFacilitySetting($health_facility_id,$key,$value){
			
			if($value == "true"){
				$value = 1;
			}else{
				$value = 0;
			}
			
			$query = $this->db->update('health_facility',array($key => $value),array('id' => $health_facility_id));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function getIfUserIsSubAdmin($health_facility_table_name,$user_id,$dept,$sub_dept){
			$query = $this->db->get_where($health_facility_table_name,array('user_id' => $user_id,'position' => 'sub_admin','dept' => $dept,'sub_dept' => $sub_dept));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function zipResult($health_facility_patient_db_table,$lab_id){
			$query = $this->db->update($health_facility_patient_db_table,array('zipped' => 1),array('lab_id' => $lab_id));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function unzipResult($health_facility_patient_db_table,$lab_id){
			$query = $this->db->update($health_facility_patient_db_table,array('zipped' => 0),array('lab_id' => $lab_id));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfTestResultsAreReady($health_facility_patient_db_table,$lab_id){
			$query = $this->db->get_where($health_facility_patient_db_table,array('lab_id' => $lab_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$test_ready = $row->test_ready;
					if($test_ready == 1){
						return true;
					}else{
						return false;
					}
				}
			}
		}

		public function checkIfTestResultIsReady($health_facility_main_test_result_table,$main_test_id,$initiation_code,$lab_id){
			$query = $this->db->get_where($health_facility_main_test_result_table,array('main_test_id' => $main_test_id,'lab_id' => $lab_id,'submitted' => 1));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfTestHasSubTests($health_facility_test_table_name,$main_test_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('under' => $main_test_id));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfAllResultsAreReady($health_facility_main_test_result_table,$lab_id,$tests_num){
			$query = $this->db->get_where($health_facility_main_test_result_table,array('lab_id' => $lab_id,'submitted' => $submitted));
			if($query->num_rows() == $tests_num){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfTestIsSubmitted($health_facility_main_test_result_table,$test_result_id){
			$query = $this->db->get_where($health_facility_main_test_result_table,array('id' => $test_result_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$submitted = $row->submitted;
					if($submitted == 1){
						return false;
					}else{
						return true;
					}
				}
			}
		}

		public function checkIfTestImageResultsHaveBeenUploaded($health_facility_main_test_result_table,$id){
			$query = $this->db->get_where($health_facility_main_test_result_table,array('id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$images = $row->images;
					if($images == ""){
						return false;
					}else{
						return true;
					}
				}
			}
		}

		public function getPathologistsComment($health_facility_patient_db_table,$lab_id){
			$query = $this->db->get_where($health_facility_patient_db_table,array('lab_id' => $lab_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$pathologists_comment = $row->pathologists_comment;
				}
				return $pathologists_comment;
			}
		}

		public function getAllPaidTestsRegisteredZero($health_facility_table_name){
			$query = $this->db->get_where($health_facility_table_name,array('registered' => 0,'paid' => 1));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function checkIfRangeIsValid($range){
			if($range == "1-day" || $range == "1-week" || $range == "1-month" || $range == "1-year" || $range == "1-decade"){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfResultIsVerified($health_facility_patient_db_table,$lab_id){
			$query = $this->db->get_where($health_facility_patient_db_table,array('lab_id' => $lab_id,'verified' => 1));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function deleteAdmin($health_facility_table_name,$user_name,$affiliation_id){
			$query = $this->db->delete($health_facility_table_name,array('id' => $affiliation_id,'user_name' => $user_name));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfUserIsATopAdmin($health_facility_table_name,$user_name){
			$query = $this->db->get_where($health_facility_table_name,array('user_name' => $user_name,'is_admin' => 1,'position' => 'admin'));

			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfUserIsATopAdmin2($health_facility_table_name,$user_id){
			$query = $this->db->get_where($health_facility_table_name,array('user_id' => $user_id,'is_admin' => 1,'position' => 'admin'));
			
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfUserIsAnAdmin1($health_facility_table_name,$user_name){
			$query = $this->db->get_where($health_facility_table_name,array('user_name' => $user_name,'is_admin' => 1));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getNewNotifsCount(){
			$user_id = $this->getUserIdWhenLoggedIn();
			$user_name = $this->getUserNameById($user_id);
			$this->db->select("*");
			$this->db->from("notif");
			$this->db->where("receiver",$user_name);
			$this->db->where("received",0);
			$query = $this->db->get();
			$num_rows = $query->num_rows();
			if($num_rows > 0){
				return "(" . $num_rows . ")";
			}else{
				return "";
			}
		}


		public function getTestSections($sub_dept){
			$this->db->select("*");
			$this->db->from('test_sections');
			if($sub_dept == 1){
				$this->db->where('label = "a" OR label = "b" OR label = "c" OR label = "e" OR label = "f" OR label = "g" OR label = "h" OR label = "i" OR label = "j" OR label = "k" OR label = "l" OR label = "m" OR label = "n" OR label = "o"');
			}elseif($sub_dept == 2){
				$this->db->where('label = "a" OR label = "b" OR label = "m" OR label = "n" OR label = "o"');
			}elseif($sub_dept == 3){
				$this->db->where('label = "a" OR label = "b" OR label = "d" OR label = "e" OR label = "n" OR label = "o"');
			}elseif($sub_dept == 6){
				$this->db->where('label = "p" OR label = "q" OR label = "r"');
			}elseif($sub_dept == 7){
				$this->db->where('label = "a" OR label = "c" OR label = "o"');
			}else{
				return false;
			}
			$this->db->order_by('label','ASC');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getAllTestSections($sub_dept){
			$this->db->select("*");
			$this->db->from('test_sections');
			if($sub_dept == 1){
				$this->db->where('label = "a" OR label = "b" OR label = "c" OR label = "e" OR label = "f" OR label = "g" OR label = "h" OR label = "i" OR label = "j" OR label = "k" OR label = "l" OR label = "m" OR label = "n" OR label = "o"');
			}elseif($sub_dept == 2){
				$this->db->where('label = "a" OR label = "b" OR label = "m" OR label = "n" OR label = "o"');
			}elseif($sub_dept == 3){
				$this->db->where('label = "a" OR label = "b" OR label = "d" OR label = "e" OR label = "n" OR label = "o"');
			}elseif($sub_dept == 6){
				$this->db->where('label = "p" OR label = "q" OR label = "r"');
			}elseif($sub_dept == 7){
				$this->db->where('label = "a" OR label = "c" OR label = "o"');
			}else{
				return false;
			}
			$this->db->order_by('label','ASC');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		

		public function getClinicalPathologyTestSectionByLabel($label){
			if($label == 'a' || $label == 'b' || $label == 'f' || $label == 'g' || $label == 'h' || $label == 'i' || $label == 'j' || $label == 'k' || $label == 'l'){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfTestIdIsValid($test_id,$health_facility_test_table_name,$id){
			$query = $this->db->get_where($health_facility_test_table_name,array('id' => $id,'test_id' => $test_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfTestIdIsValid1($test_id,$health_facility_test_table_name){
			$query = $this->db->get_where($health_facility_test_table_name,array('test_id' => $test_id));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function checkNoOfTestsWithTestId($test_id,$health_facility_test_table_name,$sub_dept_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('test_id' => $test_id,'sub_dept_id' => $sub_dept_id));
			return $query->num_rows();
				
		}

		public function test_id_unique_1($table_name,$sub_dept_id,$testid){
			$query = $this->db->get_where($table_name,array('sub_dept_id' => $sub_dept_id,'test_id' => $testid));
			if($query->num_rows() > 0){
				return false;
			}else{
				return true;
			}
		}

		public function getTestById($health_facility_test_table_name,$main_test_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('id' => $main_test_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getResultValue($health_facility_main_test_result_table,$test_result_id,$field){
			if($field == "control_1" || $field == "control_2" || $field == "control_3" || $field == "test_result" || $field == "date" || $field == "time" || $field == "comment"){
				$query = $this->db->get_where($health_facility_main_test_result_table,array('id' => $test_result_id));
				if($query->num_rows() == 1){
					foreach($query->result() as $row){
						$rVal = $row->$field;
					}
					return $rVal;
				}else{
					return "";
				}
			}else{
				return "";
			}
		}

		public function getTestResultsMain1($health_facility_main_test_result_table,$lab_id){
			$this->db->select("*");
			$this->db->from($health_facility_main_test_result_table);
			$this->db->where('lab_id',$lab_id);
			$this->db->order_by('id','ASC');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getTestResultsMain5($health_facility_main_test_result_table,$lab_id){
			$this->db->select("*");
			$this->db->from($health_facility_main_test_result_table);
			$this->db->where('lab_id' ,$lab_id);
			
			$this->db->order_by('id','ASC');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getTestResultsMain2($health_facility_main_test_result_table,$form_array){
			$query = $this->db->get_where($health_facility_main_test_result_table,$form_array);
			// echo $this->db->last_query();
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getMortuaryAutopsyParamByMortuaryRecordId($health_facility_id,$mortuary_record_id,$param){
			$query = $this->db->get_where("mortuary_autopsy",array('health_facility_id' => $health_facility_id,'mortuary_record_id' => $mortuary_record_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$param_val = $row->$param;
				}
				return $param_val;
			}
		}

		public function updateMortuaryAutopsyRecord($form_array,$mortuary_record_id){
			return $this->db->update("mortuary_autopsy",$form_array,array('mortuary_record_id' => $mortuary_record_id));
		}

		public function getTestSuperTest($health_facility_test_table_name,$sub_test_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('id' => $sub_test_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$under = $row->under;
				}
				return $under;
			}
		}

		public function getMainTestResultIdByMainTestId($health_facility_main_test_result_table,$super_main_test_id,$lab_id){
			$query = $this->db->get_where($health_facility_main_test_result_table,array('main_test_id' => $super_main_test_id,'lab_id' => $lab_id),1);
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$id = $row->id;
				}
				return $id;
			}
		}

		public function checkIfTestResultTestHasSubTests($health_facility_main_test_result_table,$id){
			$query = $this->db->get_where($health_facility_main_test_result_table,array('super_test_id' => $id));
			if($query->num_rows() > 0){
				return false;
			}else{
				return true;
			}
		}

		public function getFirstSubOfMainTestResult($health_facility_main_test_result_table,$id){
			$query = $this->db->get_where($health_facility_main_test_result_table,array('super_test_id' => $id),1);
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$id = $row->id;
				}
				return $id;
			}else{
				return false;
			}
		}

		public function checkIfTestResultTestHasBeenEntered($health_facility_main_test_result_table,$id){
			$this->db->select("*");
			$this->db->from($health_facility_main_test_result_table);
			$this->db->where('id', $id);
			$this->db->where('test_result', NULL);

			$this->db->or_where('images' ,"");
			$query = $this->db->get();
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getTestSectionNameByLabel($section){
			$query = $this->db->get_where('test_sections',array('label' => $section));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					return $row->name;
				}
			}
		}

		public function getTestNameById($health_facility_test_table_name,$test_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('id' => $test_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$name = $row->name;
				}
				return $name;
			}else{
				return "";
			}
		}

		public function getTestUnit($health_facility_test_table_name,$test_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('id' => $test_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$unit = $row->unit;
				}
				return $unit;
			}else{
				return "";
			}
		}

		public function checkIfRangeIsEnabled($health_facility_test_table_name,$test_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('id' => $test_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$range_enabled = $row->range_enabled;
				}
				if($range_enabled == 1){
					return true;
				}else{
					return false;
				}
			}else{
				return "";
			}
		}

		public function getPreciseRangeValue($health_facility_test_table_name,$test_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('id' => $test_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$unit = $row->unit;
					$unit_enabled = $row->unit_enabled;
					$range_lower = $row->range_lower;
					$range_higher = $row->range_higher;
					$range_enabled = $row->range_enabled;
					$range_type = $row->range_type;
					$desirable_value = $row->desirable_value;
				}
				if($range_enabled == 1){
					$range = "";
					if($range_type == "interval"){
						$range = $range_lower . " - " . $range_higher;
						if($unit_enabled == 1){
							$range .= " " .$unit;
						}
					}else if($range_type == "desirable"){
						$range = $desirable_value;
						if($unit_enabled == 1){
							$range .= " " .$unit;
						}
					}
					return $range;
				}else{
					return false;
				}
			}else{
				return "";
			}
		}

		public function getPreciseResultFlag($health_facility_test_table_name,$test_id,$test_result){
			$query = $this->db->get_where($health_facility_test_table_name,array('id' => $test_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$unit = $row->unit;
					$unit_enabled = $row->unit_enabled;
					$range_lower = $row->range_lower;
					$range_higher = $row->range_higher;
					$range_enabled = $row->range_enabled;
					$range_type = $row->range_type;
					$desirable_value = $row->desirable_value;
				}
				if($range_enabled == 1){
					if($range_type == "interval"){
						return $this->getResultFlag($range_higher,$range_lower,$test_result);
					}else if($range_type == "desirable"){
						return $this->getResultFlag1($desirable_value,$test_result);
					}
				}
			}	
		}

		public function checkIfImagesWasUploaded($health_facility_main_test_result_table,$lab_id,$main_test_id){
			$query = $this->db->get_where($health_facility_main_test_result_table,array('lab_id' => $lab_id,'main_test_id' => $main_test_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$images = $row->images;
				}
				if($images != ""){
					return true;
				}else{
					return false;
				}
			}
		}

		public function getTestIdById($health_facility_test_table_name,$test_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('id' => $test_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$test_id = $row->test_id;
				}
				return $test_id;
			}
		}


		public function getTestTaTimeById($health_facility_test_table_name,$test_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('id' => $test_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$t_a = $row->t_a;
				}
				return $t_a;
			}
		}

		public function getTestIdByMainTestId($health_facility_test_table_name,$test_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('id' => $test_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$test_id = $row->test_id;
				}
				return $test_id;
			}else{
				return "";
			}
		}

		public function getTestInfoById($health_facility_test_table_name,$super_test_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('id' => $super_test_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getTestSectionNameByTestId($health_facility_test_table_name,$test_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('id' => $test_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$section = $row->section;
				}
				return $this->getTestSectionNameByLabel($section);
			}
		}

		public function getTestSectionLabelByTestId($health_facility_test_table_name,$test_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('id' => $test_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					return $row->section;
				}
				
			}
		}

		public function getNoOfSubTests($health_facility_test_table_name,$id){
			$query = $this->db->get_where($health_facility_test_table_name,array('under' => $id));
			return $query->num_rows();
		}

		public function getTestsSubTests($health_facility_test_table_name,$test_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('under' => $test_id));
			if($query->num_rows() > 0){
				return $query->result();
			}
		}

		public function getTestsBySection($test_section_label,$test_table_name,$sub_dept_id){
			

			if($this->db->table_exists($test_table_name))
			{
			   //DO SOMETHING! IT EXISTS!
				$query = $this->db->get_where($test_table_name,array('section' => $test_section_label,'sub_dept_id' => $sub_dept_id,'under' => 0));
				if($query->num_rows() > 0){
					return $query->result();
				}
			}
			else
			{
			    //I can't find it...
			    $query = $this->db->get_where('tests',array('section' => $test_section_label));
				if($query->num_rows() > 0){
					return $query->result();
				}
			}
			
		}

		public function getDeptParamById($param,$id){
			$query = $this->db->get_where("dept",array('id' => $id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}
		}

		public function getSubDeptParamById($param,$id){
			$query = $this->db->get_where("sub_dept",array('id' => $id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}
		}

		public function addOfficerToClinics($name,$sub_dept_id){
			$slug = strtolower(url_title($name));
			$query = $this->db->insert("personnel",array('name' => $name,'slug' => $slug,'dept_id' => 3,'sub_dept_id' => $sub_dept_id));
			return $query;
		}

		public function getPersonnelParamById($param,$id){
			$query = $this->db->get_where("personnel",array('id' => $id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}
		}

		public function getIfClinicIdIsValid($clinic_id){
			$query = $this->db->get_where("sub_dept",array('id' => $clinic_id,'dept_id' => 3));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function addConsultForWard($ward_record_id,$clinic_id){
			$consults_list = $this->getWardParamByWardRecordId("consults_list",$ward_record_id);
			if($consults_list == ""){
				return $this->db->update("wards",array('consults_list' => $clinic_id),array('id' => $ward_record_id));
			}else{
				$consults_list_arr = explode(",",$consults_list);
				if(in_array($clinic_id, $consults_list_arr)){
					return true;
				}else{
					$consults_list_arr[] = $clinic_id;
					$consults_list = implode(",", $consults_list_arr);
					return $this->db->update("wards",array('consults_list' => $consults_list),array('id' => $ward_record_id));
				}
			}
		}


		public function removeConsultForWard($ward_record_id,$clinic_id){
			$consults_list = $this->getWardParamByWardRecordId("consults_list",$ward_record_id);
			if($consults_list == ""){
				return true;
			}else{
				$consults_list_arr = explode(",",$consults_list);
				if(in_array($clinic_id, $consults_list_arr)){
					
					$clinic_id_arr = array($clinic_id);
					$consults_list_arr = array_diff($consults_list_arr,$clinic_id_arr);
					$consults_list = implode(",", $consults_list_arr);
					return $this->db->update("wards",array('consults_list' => $consults_list),array('id' => $ward_record_id));
				}else{
					return true;
				}
			}
		}

		public function getWardParamByWardRecordId($param,$ward_record_id){
			$query = $this->db->get_where("wards",array('id' => $ward_record_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}
		}

		public function getAllClinics(){
			$query = $this->db->get_where("sub_dept",array('dept_id' => 3));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function trim_text($input, $length, $ellipses = true, $strip_html = true) {
		    //strip tags, if desired
		    if ($strip_html) {
		        $input = strip_tags($input);
		    }
		  
		    //no need to trim, already shorter than trim length
		    if (strlen($input) <= $length) {
		        return $input;
		    }
		  
		    //find last space within length
		    $last_space = strrpos(substr($input, 0, $length), ' ');
		    $trimmed_text = substr($input, 0, $last_space);
		  
		    //add ellipses (...)
		    if ($ellipses) {
		        $trimmed_text .= '...';
		    }
		  
		    return $trimmed_text;
		}

		public function addNewWardServiceForFacility($form_array){
			return $this->db->insert('ward_services',$form_array);
		}

		public function addNewMortuaryServiceForFacility($form_array){
			return $this->db->insert('mortuary_services',$form_array);
		}

		public function editWardServiceForFacility($form_array,$health_facility_id,$sub_dept_id,$id){
			$query = $this->db->update('ward_services',$form_array,array('health_facility_id' => $health_facility_id,'ward_id' => $sub_dept_id,'id' => $id));
			return $query;
		}

		public function editMortuaryServiceForFacility($form_array,$health_facility_id,$id){
			$query = $this->db->update('mortuary_services',$form_array,array('health_facility_id' => $health_facility_id,'id' => $id));
			return $query;
		}

		public function getOwingWardPatientsForFacility($health_facility_id){
			$query = $this->db->get_where("wards",array('health_facility_id' => $health_facility_id,'discharged' => 0,'user_type !=' => "nfp"));
			if($query->num_rows() > 0){
				
				return $query->result();
			}else{
				return false;
			}
		}

		public function getWardAdmissionInfo($health_facility_id,$sub_dept_id){
			$query = $this->db->get_where('ward_admission_info',array('health_facility_id' => $health_facility_id,'ward_id' => $sub_dept_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getWardServiceTypeById($service_id,$ward_id){
			$query = $this->db->get_where('ward_services',array('id' => $service_id,'ward_id' => $ward_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$type = $row->type;
				}
				return $type;
			}
		}

		public function getMortuaryServiceTypeById($service_id){
			$query = $this->db->get_where('mortuary_services',array('id' => $service_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$type = $row->type;
				}
				return $type;
			}
		}

		public function getMortuaryServiceParamById($service_id,$param){
			$query = $this->db->get_where('mortuary_services',array('id' => $service_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$param_val = $row->$param;
				}
				return $param_val;
			}
		}

		public function getWardServiceQuantityById($service_id,$ward_id){
			$query = $this->db->get_where('ward_services',array('id' => $service_id,'ward_id' => $ward_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$quantity = $row->quantity;
				}
				return $quantity;
			}
		}

		public function getWardServicePriceById($service_id,$ward_id){
			$query = $this->db->get_where('ward_services',array('id' => $service_id,'ward_id' => $ward_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$price = $row->price;
				}
				return $price;
			}
		}

		public function getIfWardServiceIsValid($service_id,$ward_id,$health_facility_id){
			$query = $this->db->get_where('ward_services',array('id' => $service_id,'ward_id' => $ward_id,'health_facility_id' => $health_facility_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getWardServicesRequestedInWard($service_id,$health_facility_id,$ward_record_id){
			$query = $this->db->get_where('ward_services_requested',array('ward_service_id' => $service_id,'health_facility_id' => $health_facility_id,'ward_record_id' => $ward_record_id,'paid' => 1));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getWardServicePpqById($service_id,$ward_id){
			$query = $this->db->get_where('ward_services',array('id' => $service_id,'ward_id' => $ward_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$ppq = $row->ppq;
				}
				return $ppq;
			}
		}

		public function getAllWardServicesForFacility($sub_dept_id,$health_facility_id){
			$query = $this->db->get_where('ward_services',array('ward_id' => $sub_dept_id,'health_facility_id' => $health_facility_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getMortuaryParamById($mortuary_record_id,$param){
			$query = $this->db->get_where("mortuary",array('id' => $mortuary_record_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$param_val = $row->$param;
				}
				return $param_val;
			}else{
				return false;
			}
		}

		public function getAllMortuaryServicesForFacility($health_facility_id){
			$query = $this->db->get_where('mortuary_services',array('health_facility_id' => $health_facility_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function requestNewWardService($form_array){
			return $this->db->insert('ward_services_requested',$form_array);
		}

		public function requestNewMortuaryService($form_array){
			return $this->db->insert('mortuary_services_requested',$form_array);
		}

		public function checkIfUserIsValidToEditWardService($id,$sub_dept_id,$health_facility_id){
			$query = $this->db->get_where('ward_services',array('ward_id' => $sub_dept_id,'health_facility_id' => $health_facility_id, 'id' => $id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfUserIsValidToEditMortuaryService($id,$health_facility_id){
			$query = $this->db->get_where('mortuary_services',array('health_facility_id' => $health_facility_id, 'id' => $id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getWardServiceByIdForFacility($sub_dept_id,$health_facility_id,$id){
			$query = $this->db->get_where('ward_services',array('ward_id' => $sub_dept_id,'health_facility_id' => $health_facility_id, 'id' => $id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getMortuaryServiceByIdForFacility($health_facility_id,$id){
			$query = $this->db->get_where('mortuary_services',array('health_facility_id' => $health_facility_id, 'id' => $id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function insertWardAdmissionInfoForFacility($form_array){
			return $this->db->insert('ward_admission_info',$form_array);
		}

		public function updateWardAdmissionInfoForFacility($form_array,$sub_dept_id){
			$query = $this->db->update('ward_admission_info',$form_array,array('ward_id' => $sub_dept_id));
			return $query;
		}

		public function checkIfWardAdmissionInfoExistsForFacility($health_facility_id,$sub_dept_id){
			$query = $this->db->get_where('ward_admission_info',array('health_facility_id' => $health_facility_id,'ward_id' => $sub_dept_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		// public function checkIfUserIsAdminOrSubAdmin($health_facility_table_name,$user_name){
		// 	// $query = $this->db->query('SELECT * FROM "'.$health_facility_table_name.'" WHERE username = "'.$user_name.'" AND ( position="admin" OR position="sub_admin") ');
		// 	$this->db->select('*');
		// 	$this->db->from($health_facility_table_name);
		// 	$this->db->where('user_name = "'.$user_name.'" AND ( position="admin" OR position="sub_admin")');
		// 	$query = $this->db->get();
		// 	if($query->num_rows() > 0){
		// 		return true;
		// 	}else{
		// 		return false;
		// 	}
		// }

		public function checkIfUserIsAdminOrSubAdminUserId($health_facility_table_name,$user_id){
			// $query = $this->db->query('SELECT * FROM "'.$health_facility_table_name.'" WHERE username = "'.$user_name.'" AND ( position="admin" OR position="sub_admin") ');
			$this->db->select('*');
			$this->db->from($health_facility_table_name);
			$this->db->where('user_id = "'.$user_id.'" AND ( position="admin" OR position="sub_admin")');
			$query = $this->db->get();
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfUserIdIsValid($user_id){
			$query = $this->db->get_where('users',array('id' => $user_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		
		public function deleteWorkerPermanently($personnel_officers_id){
			return $this->db->delete("personnel_officers",array('id' => $personnel_officers_id));
		}

		public function checkIfUserNameExists($user_name){
			$query = $this->db->get_where("users",array('user_name' => $user_name));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfUserNameExistsOfficerOnly($user_name){
			$query = $this->db->get_where("users",array('user_name' => $user_name,'is_patient' => 0));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}



		public function deleteWorkerPermanently1($health_facility_table_name,$personnel_id,$dept,$sub_dept){
			$query = $this->db->delete($health_facility_table_name,array('user_id' => $personnel_id,'dept' => $dept,'sub_dept' => $sub_dept,'position' => 'sub_admin'));
			if($query){
				$query = $this->db->delete('users',array('id' => $personnel_id));
				if($query){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}



		public function checkIfUserIsAPersonnel($health_facility_table_name,$user_name){
			// $query = $this->db->query('SELECT * FROM "'.$health_facility_table_name.'" WHERE username = "'.$user_name.'" AND ( position="admin" OR position="sub_admin") ');
			$this->db->select('*');
			$this->db->from($health_facility_table_name);
			$this->db->where('user_name = "'.$user_name.'" AND (position="personnel")');
			$query = $this->db->get();
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getIfMainTestHasSubTests($health_facility_main_test_result_table,$form_array2){
			$query = $this->db->get_where($health_facility_main_test_result_table,$form_array2);
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		
		public function checkIfUserIsPatient($user_name){
			$query = $this->db->get_where('users',array('user_name' => $user_name,'is_patient' => 1));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getFirstTenHospitals(){
			$query = $this->db->get_where('health_facility',array('facility_structure' => 'hospital'),10);
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		
		

		public function confirmUserIsAClinicAdmin($health_facility_slug){
			// $user_id = $this->getUserIdWhenLoggedIn();
			// $query = $this->db->get_where('health_facility',array('slug' => $health_facility_slug));
			// if($query->num_rows() == 1){
			// 	foreach($query->result() as $row){
			// 		$table_name = $row->table_name;
			// 	}
			// 	$query = $this->db->get_where($table_name,array('user_id' => $user_id,'is_admin' => 1,'dept' => 'clinic-services'));
			// 	if($query->num_rows() > 0){
			// 		return true;
			// 	}else{
			// 		if($this->checkIfUserIsAdminOfFacility($table_name,$user_id)){
			// 			return true;
			// 		}else{
			// 			return false;
			// 		}
					
			// 	}
			// }else{
			// 	return false;
			// }
			return true;
		}

		public function confirmUserIsAWardAdmin($health_facility_slug){
			return true;
			$user_id = $this->getUserIdWhenLoggedIn();
			$query = $this->db->get_where('health_facility',array('slug' => $health_facility_slug));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$table_name = $row->table_name;
				}
				$query = $this->db->get_where($table_name,array('user_id' => $user_id,'is_admin' => 1,'dept' => 'wards'));
				if($query->num_rows() > 0){
					return true;
				}else{
					if($this->checkIfUserIsAdminOfFacility($table_name,$user_id)){
						return true;
					}else{
						return false;
					}
					
				}
			}else{
				return false;
			}
		}

		public function checkIfUserIsValidToEditDrsWardConsultation($consultation_id,$ward_record_id,$user_id){
			$query = $this->db->get_where('doctors_ward_consultations',array('id' => $consultation_id,'ward_record_id' => $ward_record_id,'doctor_id' => $user_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfUserIsValidToEditWardClinicalNote($consultation_id,$ward_record_id,$user_id){
			$query = $this->db->get_where('ward_clinical_notes',array('id' => $consultation_id,'ward_record_id' => $ward_record_id,'doctor_id' => $user_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfUserIsValidToEditWardReport($report_id,$ward_record_id,$user_id){
			$query = $this->db->get_where('ward_reports',array('id' => $report_id,'ward_record_id' => $ward_record_id,'doctor_id' => $user_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getDrsWardConsultationInfo($consultation_id,$ward_record_id,$user_id){
			$query = $this->db->get_where('doctors_ward_consultations',array('id' => $consultation_id,'ward_record_id' => $ward_record_id,'doctor_id' => $user_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getWardClinicalNoteInfo($consultation_id,$ward_record_id,$user_id){
			$query = $this->db->get_where('ward_clinical_notes',array('id' => $consultation_id,'ward_record_id' => $ward_record_id,'doctor_id' => $user_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}


		public function getDrsConsultationByIdAndWardRecordId($ward_record_id,$id){
			$query = $this->db->get_where('doctors_ward_consultations',array('id' => $id,'ward_record_id' => $ward_record_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getClinicalNoteByIdAndWardRecordId($ward_record_id,$id){
			$query = $this->db->get_where('ward_clinical_notes',array('id' => $id,'ward_record_id' => $ward_record_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getVitalSignsNumByDate($ward_record_id,$date){
			$query = $this->db->get_where('ward_vital_signs',array('date' => $date,'ward_record_id' => $ward_record_id));
			return $query->num_rows();
		}

		public function addNurseWardsInputOutput($form_array){
			$query = $this->db->insert('ward_input_output',$form_array);
			return $query;
		}

		public function getInputOutputNumByDate($ward_record_id,$date){
			$query = $this->db->get_where('ward_input_output',array('date' => $date,'ward_record_id' => $ward_record_id));
			return $query->num_rows();
		}

		public function getOtherChartInfoNumByDate($other_charts_id,$ward_record_id,$date){
			$query = $this->db->get_where('other_charts_values',array('date' => $date,'ward_record_id' => $ward_record_id,'other_charts_id' => $other_charts_id));
			return $query->num_rows();
		}

		public function getVitalSignsForDay($ward_record_id,$date){
			$query = $this->db->get_where('ward_vital_signs',array('ward_record_id' => $ward_record_id,'date' => $date));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getAllOtherChartsForFacility($health_facility_id){
			$query = $this->db->get_where('other_charts',array('health_facility_id' => $health_facility_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getExternalClinicsRecordsMortuaryRecordsPage($health_facility_id){
			// $this->db->select("*");
			// $this->db->from("mortuary");
			// $this->db->where("incoming_facility_id !=",$health_facility_id);
			// $this->db->where("consultation_id !=",null);
			// $this->db->where("health_facility_id",$health_facility_id);
			// $this->db->where("records_registered",0);
			// $this->db->where("discharged",0);


			$query_str = "SELECT * FROM mortuary WHERE incoming_facility_id != " .$health_facility_id . " AND (consultation_id != 'null' OR ward_record_id != 'null') AND health_facility_id = " . $health_facility_id . " AND records_registered = 0 AND discharged = 0";
			// echo $query_str;
			$query = $this->db->query($query_str);
			if($query->num_rows() > 0){
				return($query->result());
			}else{
				return false;
			}
		}

		public function getInternalClinicsRecordsMortuaryRecordsPage($health_facility_id){
			// $this->db->select("*");
			// $this->db->from("mortuary");
			// $this->db->where("incoming_facility_id",$health_facility_id);
			// $this->db->where("consultation_id !=",null);
			// $this->db->where("health_facility_id",$health_facility_id);
			// $this->db->where("records_registered",0);
			// $this->db->where("discharged",0);

			$query_str = "SELECT * FROM mortuary WHERE incoming_facility_id = " .$health_facility_id . " AND (consultation_id != 'null' OR ward_record_id != 'null') AND health_facility_id = " . $health_facility_id . " AND records_registered = 0 AND discharged = 0";

			$query = $this->db->query($query_str);
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getInternalClinicsRecordsMortuaryByIdRecordsPage($health_facility_id,$id){
			// $this->db->select("*");
			// $this->db->from("mortuary");
			// $this->db->where("incoming_facility_id",$health_facility_id);
			// $this->db->where("consultation_id !=",null);
			// $this->db->where("health_facility_id",$health_facility_id);
			// $this->db->where("id",$id);
			// $this->db->where("records_registered",0);
			// $this->db->where("discharged",0);

			$query_str = "SELECT * FROM mortuary WHERE incoming_facility_id = " .$health_facility_id . " AND (consultation_id != 'null' OR ward_record_id != 'null') AND health_facility_id = " . $health_facility_id . " AND id = ".$id." AND records_registered = 0 AND discharged = 0";


			$query = $this->db->query($query_str);
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}


		public function getMortuaryRecordById($health_facility_id,$id){
			$this->db->select("*");
			$this->db->from("mortuary");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("id",$id);
			$this->db->where("records_registered",1);
			

			$query = $this->db->get();
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}


		public function getMortuaryAutopsyRecordById($health_facility_id,$id){
			$this->db->select("*");
			$this->db->from("mortuary_autopsy");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("mortuary_record_id",$id);
			
			$query = $this->db->get();
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}



		public function getExternalLinkedClinicsRecordsMortuaryByIdRecordsPage($health_facility_id,$id){
			// $this->db->select("*");
			// $this->db->from("mortuary");
			// $this->db->where("incoming_facility_id !=",$health_facility_id);
			// $this->db->where("consultation_id !=",null);
			// $this->db->where("health_facility_id",$health_facility_id);
			// $this->db->where("id",$id);
			// $this->db->where("records_registered",0);
			// $this->db->where("discharged",0);

			$query_str = "SELECT * FROM mortuary WHERE incoming_facility_id != " .$health_facility_id . " AND (consultation_id != 'null' OR ward_record_id != 'null') AND health_facility_id = " . $health_facility_id . " AND id = ".$id." AND records_registered = 0 AND discharged = 0";

			$query = $this->db->query($query_str);
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		
		public function updateFacilityTable($health_facility_table_name,$form_array,$user_id){
			return $this->db->update($health_facility_table_name,$form_array,array('user_id' => $user_id));
		}

		public function getPersonnelParamByUserId($health_facility_table_name,$user_id,$param){
			$query = $this->db->get_where($health_facility_table_name,array('user_id' => $user_id),1);
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$param_val = $row->$param;
				}
				return $param_val;
			}
		}

		public function debitFacility($health_facility_id,$amount){
			$withdrawn = $this->getFacilityParamById("withdrawn",$health_facility_id);

			$new_withdrawn = $withdrawn + $amount;
			return $this->db->update("health_facility",array('withdrawn' => $withdrawn),array('id' => $health_facility_id));
		}

		public function updateFacility($form_array,$health_facility_id){
			return $this->db->update("health_facility",$form_array,array('id' => $health_facility_id));
		}


		public function updateMortuaryRecord($form_array,$id){
			return $this->db->update("mortuary",$form_array,array('id' => $id));
		}


		public function getRecordIdByWardRecordId($ward_record_id){
			$query = $this->db->get_where('wards',array('id' => $ward_record_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$record_id = $row->record_id;
				}
				return $record_id;
			}else{
				return false;
			}
		}

		public function getInputOutputForDay($ward_record_id,$date){
			$query = $this->db->get_where('ward_input_output',array('ward_record_id' => $ward_record_id,'date' => $date));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getParameterOneByIdOtherCharts($id){
			$query = $this->db->get_where('other_charts_values',array('id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$parameter_1 = $row->parameter_1;
				}
				return $parameter_1;
			}else{
				return false;
			}
		}

		public function getParameterTwoByIdOtherCharts($id){
			$query = $this->db->get_where('other_charts_values',array('id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$parameter_2 = $row->parameter_2;
				}
				return $parameter_2;
			}else{
				return false;
			}
		}

		
		public function getParameterThreeByIdOtherCharts($id){
			$query = $this->db->get_where('other_charts_values',array('id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$parameter_3 = $row->parameter_3;
				}
				return $parameter_3;
			}else{
				return false;
			}
		}

		public function getParameterFourByIdOtherCharts($id){
			$query = $this->db->get_where('other_charts_values',array('id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$parameter_4 = $row->parameter_4;
				}
				return $parameter_4;
			}else{
				return false;
			}
		}

		public function getOtherChartInfoForDay($ward_record_id,$date,$other_charts_id){
			$query = $this->db->get_where('other_charts_values',array('ward_record_id' => $ward_record_id,'other_charts_id' => $other_charts_id,'date' => $date));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getAllPatientsVitalSignsWard($ward_record_id){
			
			$this->db->select("date");
			$this->db->from("ward_vital_signs");
			$this->db->where("ward_record_id",$ward_record_id);
			$this->db->order_by("id","DESC");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				$ret = array();
				foreach($query->result() as $row){
					$date = $row->date;
					$ret[] = $date;
				}
				$ret = array_values(array_unique($ret));
				return $ret;
			}else{
				return false;
			}
		}

		public function getAllPatientsInputOutputWard($ward_record_id){
			
			$this->db->select("date");
			$this->db->from("ward_input_output");
			$this->db->where("ward_record_id",$ward_record_id);
			$this->db->order_by("id","DESC");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				$ret = array();
				foreach($query->result() as $row){
					$date = $row->date;
					$ret[] = $date;
				}
				$ret = array_values(array_unique($ret));
				return $ret;
			}else{
				return false;
			}
		}

		public function getOtherChartInfo1($other_charts_id){
			$query = $this->db->get_where('other_charts',array('id' => $other_charts_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function addOtherChartData($form_array){
			$query = $this->db->insert('other_charts_values',$form_array);
			return $query;
		}

		public function getOtherChartInfo($other_charts_id,$ward_record_id){
			
			$this->db->select("date");
			$this->db->from("other_charts_values");
			$this->db->where("ward_record_id",$ward_record_id);
			$this->db->where("other_charts_id",$other_charts_id);
			$this->db->order_by("id","DESC");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				$ret = array();
				foreach($query->result() as $row){
					$date = $row->date;
					$ret[] = $date;
				}
				$ret = array_values(array_unique($ret));
				return $ret;
			}else{
				return false;
			}
		}

		public function getIfOtherChartIdIsValid($other_chart_id,$health_facility_id){
			$query = $this->db->get_where('other_charts',array('id' => $other_chart_id,'health_facility_id' => $health_facility_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getOtherChartNameById($other_chart_id){
			$query = $this->db->get_where('other_charts',array('id' => $other_chart_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$name = $row->name;
				}
				return $name;
			}
		}

		public function addNurseWardsConsultation($form_array){
			$query = $this->db->insert('ward_vital_signs',$form_array);
			return $query;
		}

		public function addOtherChart($form_array){
			$query = $this->db->insert('other_charts',$form_array);
			return $query;
		}

		public function addDoctorsWardsConsultation($form_array){
			$query = $this->db->insert('doctors_ward_consultations',$form_array);
			return $query;
		}

		public function addWardClinicalNote($form_array){
			$query = $this->db->insert('ward_clinical_notes',$form_array);
			return $query;
		}

		public function updateDoctorsWardsConsultation($form_array,$id){
			$query = $this->db->update('doctors_ward_consultations',$form_array,array('id' => $id));
			return $query;
		}


		public function updateWardReport($form_array,$id){
			$query = $this->db->update('ward_reports',$form_array,array('id' => $id));
			return $query;
		}

		public function updateWardClinicalNote($form_array,$id){
			$query = $this->db->update('ward_clinical_notes',$form_array,array('id' => $id));
			return $query;
		}

		public function getPreviousWardReports($ward_record_id,$health_facility_id){
			$query = $this->db->get_where('ward_reports',array('ward_record_id' => $ward_record_id,'health_facility_id' => $health_facility_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getWardPatientReportIndividuallly($ward_record_id,$health_facility_id,$type){
			$ret = "";
			// $query = $this->db->get_where('ward_reports',array('ward_record_id' => $ward_record_id,'health_facility_id' => $health_facility_id));
			$this->db->select("*");
			$this->db->from("ward_reports");
			$this->db->where("ward_record_id",$ward_record_id);
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->order_by("id","DESC");
			$this->db->limit(1);
			$query = $this->db->get();
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$ret = $row->$type;
				}

			}	
			return $ret;
		}

		public function getPatientWardReportInfoById($health_facility_id,$ward_record_id,$report_id){
			$query = $this->db->get_where('ward_reports',array('health_facility_id' => $health_facility_id,'ward_record_id' => $ward_record_id,'id' => $report_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function addNewWardReport($form_array){
			$query = $this->db->insert('ward_reports',$form_array);
			return $query;
		}

		public function checkIfWardRecordExists($ward_record_id,$health_facility_id){
			$query = $this->db->get_where('wards',array('id' => $ward_record_id,'health_facility_id' => $health_facility_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfMortuaryRecordExists($mortuary_record_id,$health_facility_id){
			$query = $this->db->get_where('mortuary',array('id' => $mortuary_record_id,'health_facility_id' => $health_facility_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getWardPatient($ward_record_id,$health_facility_id){
			$query = $this->db->get_where('wards',array('id' => $ward_record_id,'health_facility_id' => $health_facility_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function replaceUnderscoreWithSpace($str){
			return ucwords(str_replace("_", " ", $str));
		}

		

		public function getDrsWardRecords($ward_record_id){
			$query = $this->db->get_where('doctors_ward_consultations',array('ward_record_id' => $ward_record_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return "";
			}
		}

		public function getWardClinicalNotes($ward_record_id){
			$query = $this->db->get_where('ward_clinical_notes',array('ward_record_id' => $ward_record_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return "";
			}
		}

		public function getFirstSubDept($dept_id){
			$query = $this->db->get_where('sub_dept',array('dept_id' => $dept_id),1);
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getSubDepts($dept_id){
			$query = $this->db->get_where('sub_dept',array('dept_id' => $dept_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}


		public function getSubDeptsOther($dept_id){
			$this->db->select("*");
			$this->db->from("sub_dept");
			$this->db->where("dept_id",$dept_id);
			$this->db->limit(9,1);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}


		

		public function getFirstRegisteredPatients($hospital_table_name){
			$query = $this->db->get_where($hospital_table_name,array('position' => 'patient'),7);
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getRemainingRegisteredPatients($hospital_table_name,$offset){
			$query = $this->db->get_where($hospital_table_name,array('position' => 'patient'),7,$offset);
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getUserDpById($user_id){
			$query = $this->db->get_where('users',array('id' => $user_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$dp = $row->logo;
				}
				return $dp;
			}else{
				return false;
			}
		}

		public function getNotifsNum($user_id){
			$this->db->select("*");
			$this->db->from('notif');
			$this->db->where('receiver',$user_id);			
			$query = $this->db->get();
			return $query->num_rows();
		}

		public function getUserNameBySlug($slug){
			$query = $this->db->get_where('users',array('slug' => $slug));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$user_name = $row->user_name;
				}
				// echo $user_name;
				return $user_name;
			}else{
				return false;
			}
		}

		public function getSocialMediaTime($post_date,$post_time){
			$social_formated_time = "";
			if($post_date !== "" && $post_time !== ""){
				$post_date = strtotime($post_date);
				$post_date = date("j M Y",$post_date);
				$post_time = strtotime($post_time);
				$post_time = date("H:i:s",$post_time);

				$post_date1 = $post_date;
				$post_time1 = $post_time;

				$curr_date = date("j M Y");
				$curr_time = date("h:i:sa");
				$curr_date = date("j M Y",strtotime($curr_date));
				$curr_time = date("H:i:s",strtotime($curr_time));
				
				$curr_date = $curr_date . " " . $curr_time;
				// echo $curr_date;
				$curr_date = new DateTime($curr_date);
				$post_date = $post_date . " " .$post_time;
				$post_date = new DateTime($post_date);

				$time_diff = $curr_date->getTimestamp() - $post_date->getTimestamp();
				// echo $time_diff;
				if($time_diff >= 0){
					//First Check If Time Is Greater Equal
					if($time_diff == 0){
						$social_formated_time = "Just Now";
					}else if($time_diff <= 60){
						$social_formated_time = $time_diff . " secs ago";
					}else if(($time_diff > 60) && ($time_diff < 3600)){
						$social_formated_time = floor($time_diff / 60);
					 	$social_formated_time = $social_formated_time . " mins ago";
					}else if(($time_diff >= 3600) && ($time_diff < 86400)){
					 	$social_formated_time = floor($time_diff / 3600);
					 	if($social_formated_time == 1){
						 	$social_formated_time = $social_formated_time . " hour ago";
						}else{
							$social_formated_time = $social_formated_time . " hours ago";
						}
					}else if(($time_diff >= 86400) && ($time_diff < 2628000)){
					 	$social_formated_time = floor($time_diff / 86400);
					 	if($social_formated_time == 1){
					 		$social_formated_time = $social_formated_time . " day ago";
					 	}else{
					 		$social_formated_time = $social_formated_time . " days ago";
					 	}
					}else if(($time_diff >= 2628000) && (date("Y") == date("Y",strtotime($post_date1)))){
					 	$social_formated_time = date("j M",strtotime($post_date1));
					}else if ((date("Y") !== date("Y",strtotime($post_date1)))) {
					 	$social_formated_time = date("j M Y",strtotime($post_date1));
					}
				}
			}
			return $social_formated_time;
		}

		public function getUserNameById($slug){
			$query = $this->db->get_where('users',array('id' => $slug));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$user_name = $row->user_name;
				}
				// echo $user_name;
				return $user_name;
			}else{
				return false;
			}
		}

		public function getUserBioById($slug){
			$query = $this->db->get_where('users',array('id' => $slug));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$bio = $row->bio;
				}
				// echo $user_name;
				return $bio;
			}else{
				return false;
			}
		}

		public function getUserLogoById1($slug){
			$query = $this->db->get_where('users',array('id' => $slug));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$logo = $row->logo;
				}
				// echo $user_name;
				if(is_null($logo)){
					$logo = "avatar.jpg";
				}else{

				}
				return $logo;
			}else{
				return false;
			}
		}

		public function getFacilityPatients($hospital_table_name,$facility_name){
			$query = $this->db->get_where($hospital_table_name,array('position' => 'patient','facility_name' => $facility_name,'is_admin' => 0));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getNotifCount($user_id){

			$query = $this->db->get_where('notif',array('receiver' => $user_id,'received' => 0));
			return $query->num_rows();
		}

		public function getNewMessagesCount($user_id){
			// $query = $this->db->get_where('messages',array('receiver' => $user_id,'received' => 0));
			$this->db->select('sender');
			$this->db->from('messages');
			$this->db->where('receiver',$user_id);
			$this->db->where('received',0);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				$rows = array();
				foreach($query->result() as $row){
					$sender = $row->sender;
					$rows[] .= $sender;
				}
				
				$rows = array_unique($rows);
				$rows = count($rows);
			}else{
				$rows = 0;
			}
			return $rows;
		}

		public function sortMessagesArrayRows($new_rows,$user_id){
			if(is_array($new_rows)){
				$return_rows = array();
				for($i = 0; $i < count($new_rows); $i++){
					if($this->getIfUserIdIsValid($new_rows[$i])){
						$last_message_by_user = $this->getLastMessageByThisUser($user_id);
					}
				}
			}
		}


		public function getNumberOfNewMessagesFromSender($user_id,$sender){
			$query = $this->db->get_where('messages',array('sender' => $sender,'receiver' => $user_id,'received' => 0));
			if($query->num_rows() > 0 ){
				return "(". $query->num_rows() .")";
			}else{
				return "";
			}
		}



		public function getConversationsRem($user_id,$offset){
			// $query = $this->db->get_where('messages',array('receiver' => $user_id,'received' => 0));
			$this->db->select('*');
			$this->db->from('messages');
			$this->db->where('receiver',$user_id);
			// $this->db->where('received',0);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				// $ret_arr = array('sender' => )
				$rows = array();
				$new_rows = array();
				foreach($query->result() as $row){
					$sender = $row->sender;
					$id = $row->id;
					$date = $row->date;
					$time = $row->time;
					$received = $row->received;
					$date_time = $date . " " . $time;
					$message = $row->message;
					$rows[] = array(
						'sender' => $sender,
						'id' => $id,
						'date_time' => $date_time,
						'received' => $received,
						'message' => $message
					);
				}
				
				// $rows = array_unique($rows,SORT_REGULAR);
				$rows1 = array_unique(array_column($rows, 'sender'));
				// print_r(array_intersect_key($array, $tempArr));
				$rows = array_intersect_key($rows,$rows1);
				$rows = array_values($rows);
				$slice = $offset * 10;

				$rows = array_slice($rows, $slice,10);
				// var_dump($rows);				
			}else{
				$rows = false;
			}
			return $rows;
		}

		

		public function getNotifs($user_name){
			$this->db->select("*");
			$this->db->from('notif');
			$this->db->where('receiver',$user_name);
			$this->db->order_by('id','DESC');
			$this->db->limit(15);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getUserHashedById($user_id){
			$query = $this->db->get_where('users',array('id' => $user_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$hashed = $row->hashed;
				}
				return $hashed;
			}else{
				return "";
			}
		}

		public function deleteMessage($id){
			$query = $this->db->delete('notif',array('id' => $id));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function updateNotif($form_array,$id){
			$query = $this->db->update('notif',$form_array,array('id' => $id));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function submitPathologistComment($health_facility_patient_db_table,$form_array,$lab_id){
			$query = $this->db->update($health_facility_patient_db_table,$form_array,array('lab_id' => $lab_id));
			if($query){
				return true;
			}else{
				return false;
			}
		}



		public function getPatientNameByUserName($patient_user_name){
			$query = $this->db->get_where('patients',array('user_name' => $patient_user_name));
			if($query->num_rows() == 1){
				if(is_array($query->result())){
					foreach($query->result() as $row){
						$full_name = $row->firstname . ' ' . $row->lastname;
					}
					return $full_name;
				}
			}else{
				return false;
			}
		}

		public function getPatientEmailByUserId($user_id){
			$query = $this->db->get_where('patients',array('user_id' => $user_id));
			if($query->num_rows() == 1){
				if(is_array($query->result())){
					foreach($query->result() as $row){
						$email = $row->email;
					}
					return $email;
				}
			}else{
				return false;
			}
		}

		public function checkIfTableExists($table_name){
			if($this->db->table_exists($table_name)){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfUserExists($user_name,$user_id){
			$query = $this->db->get_where('users',array('user_name' => $user_name,'id' => $user_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}


		
		public function confirmLoggedIn(){
			if(get_cookie('onehealthlogged',true)){
				$cookie = get_cookie('onehealthlogged',true);
				list($user_id,$token,$mac) = explode(':', $cookie);
				if(!isset($user_id) || !isset($token) || !isset($mac) || is_null($user_id) || is_null($mac) || is_null($token) || $user_id == "" || $token == "" || $mac == ""){
					return false;
				}
				$cookie0 = $user_id . ':' .$token;
				
				$decrypt_mac = $this->encryption->decrypt($mac);
				if($decrypt_mac == false){
					return false;
				}
				
				if(!hash_equals($cookie0,$decrypt_mac)){
					return false;
				}
				$usertoken_arr = $this->db->get_where('users',array('id' => $user_id),1);
				$usertoken_arr = $usertoken_arr->result();
				if(is_array($usertoken_arr)){
					foreach($usertoken_arr as $user_token){
						$user_token = $user_token->token;
						// $user_name1 = $user_token->user_name;
					}
					
					if(hash_equals($user_token,$token)){
						$query1 = $this->db->get_where('users',array('id' => $user_id));
						if($query1->num_rows() == 1){
							foreach($query1->result() as $row){
								$affiliated_facilities = $row->affiliated_facilities;
							}
							if($affiliated_facilities !== ""){
								$affiliated_facilities_arr = explode(",", $affiliated_facilities);
								$affiliated_facilities_arr = array_unique($affiliated_facilities_arr);
								$affiliated_facilities = implode(",",$affiliated_facilities_arr);
								$user_array = array(
									'affiliated_facilities' => $affiliated_facilities
								);
								$this->updateUserTable($user_array,$user_id);
							}
							return true;
						}
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{
				return false;
			}
		}



		public function getUserIdWhenLoggedIn(){
			if(get_cookie('onehealthlogged',true)){
				$cookie = get_cookie('onehealthlogged',true);
				list($user_id,$token,$mac) = explode(':', $cookie);
				return $user_id;
			}else{
				return false;
			}	
		}

		public function timingSafeCompare($safe, $user) {
		    if (function_exists('hash_equals')) {
		        return hash_equals($safe, $user); // PHP 5.6
		    }
		    // Prevent issues if string length is 0
		    $safe .= chr(0);
		    $user .= chr(0);

		    // mbstring.func_overload can make strlen() return invalid numbers
		    // when operating on raw binary strings; force an 8bit charset here:
		    if (function_exists('mb_strlen')) {
		        $safeLen = mb_strlen($safe, '8bit');
		        $userLen = mb_strlen($user, '8bit');
		    } else {
		        $safeLen = strlen($safe);
		        $userLen = strlen($user);
		    }

		    // Set the result to the difference between the lengths
		    $result = $safeLen - $userLen;

		    // Note that we ALWAYS iterate over the user-supplied length
		    // This is to prevent leaking length information
		    for ($i = 0; $i < $userLen; $i++) {
		        // Using % here is a trick to prevent notices
		        // It's safe, since if the lengths are different
		        // $result is already non-0
		        $result |= (ord($safe[$i % $safeLen]) ^ ord($user[$i]));
		    }

		    // They are only identical strings if $result is exactly 0...
		    return $result === 0;
		}

		//Get User Info
		

		public function getSubDeptBySlug($slug){
			$query = $this->db->get_where('sub_dept',array('slug' => $slug));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}


		
		public function getFacilityBySlug($slug){
			$query = $this->db->get_where('health_facility',array('slug' => $slug));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		

		public function deletePendingClinicsUsersToBeRegisteredReferralRecord($val){
			if(is_array($val)){
				return $this->db->delete("pending_clinic_users_to_be_registered_referral",$val);
			}else{
				return false;
			}
		}

		public function getResultFlag($range_higher,$range_lower,$value){
		    
		    if($value !== ""){
		      if($value > $range_higher){
		        $ret = "H";
		      }else if($value < $range_lower){
		        $ret = "L";
		      }else{
		        $ret = "";
		      }
		    }else{
		       $ret = "";
		    }   
		    return $ret;
	  	}

	  	public function getResultFlag1($desirable_value,$value){
		    $ret = "";
    		if($value != ""){
	    		$desirable_first_char = substr($desirable_value,0,1);
				$desirable_last_chars1 = substr($desirable_value,1);
				
				if($desirable_first_char == ">" || $desirable_first_char == "<"){    
					if(is_numeric($desirable_last_chars1)){
						if($desirable_first_char == ">"){
							if($value <= $desirable_last_chars1){
								$ret = "L";
							}
						}else if($desirable_first_char == "<"){
							if($value >= $desirable_last_chars1){
								$ret = "H";
							}
						}
					}
				}	
			}
			return $ret;   
	  	}

	  	public function checkWhichRangeForTestIsEnabled($health_facility_test_table_name,$main_test_id){
	  		$query = $this->db->get_where($health_facility_test_table_name,array('id' => $main_test_id));
	  		if($query->num_rows() == 1){
	  			foreach($query->result() as $row){
	  				$range_type = $row->range_type;
	  			}
	  			return $range_type;
	  		}
	  	}

		public function createMainTestResultTable($health_facility_test_result_table){
			if($this->db->table_exists($health_facility_test_result_table)){
				return false;
			}else{
				$query_str = 'CREATE TABLE ' .$health_facility_test_result_table.' (
					id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					lab_id TEXT NOT NULL,
					test_id TEXT NOT NULL,
					main_test_id INT NOT NULL,
					test_name VARCHAR(500) NOT NULL,
					control_1 VARCHAR(300) NOT NULL,
					control_2 VARCHAR(300) NOT NULL,
					control_3 VARCHAR(300) NOT NULL,
					test_result TEXT NOT NULL,
					comment TEXT  NULL,
					images TEXT NOT NULL,
					main_test INT DEFAULT 0 NOT NULL,
					sub_test INT DEFAULT 0 NOT NULL,
					super_test_id INT NOT NULL,
					date VARCHAR(50) NOT NULL,
					time VARCHAR(50) NOT NULL,
					submitted INT DEFAULT 0 NOT NULL
				)';
				if($this->db->query($query_str)){
					return true;	
				}
			}
			
		}

		public function addCommentColumnToResultTable($health_facility_main_test_result_table){
			$query_str = 'ALTER TABLE '. $health_facility_main_test_result_table .' ADD COLUMN comment TEXT NULL AFTER test_result;';
			if($this->db->query($query_str)){
				return true;
			}else{
				return false;
			}
		}

		public function addClinicIdColumnToTestResultTable($test_result_table_name){
			if($this->db->table_exists($test_result_table_name)){
				$query_str = 'ALTER TABLE '.$test_result_table_name.' ADD `record_id` INT NULL AFTER `referring_facility_id`, ADD `ward_id` INT NULL AFTER `record_id`, ADD `referral_id` INT NULL AFTER `ward_id`;';
				if($this->db->query($query_str)){
					return true;
				}else{
					return false;
				}
			}
		}

		public function getIfFacilityIdIsValid($referring_facility_id){
			$query = $this->db->get_where("health_facility",array('id' => $health_facility_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getMainResultTableReady($patient_tests,$health_facility_main_test_result_table,$health_facility_test_table_name,$health_facility_patient_db_table,$health_facility_test_result_table_name){
			if(is_array($patient_tests)){
				$date = date("j M Y");
				$time = date("H:i:s");
				foreach($patient_tests as $row){
					
					$id = $row->id;
					$test_deleted = false;
					$main_test_id = $row->main_test_id;

					$test_id = $this->onehealth_model->getTestIdByMainTestId($health_facility_test_table_name,$main_test_id);
					
					if($test_id == ""){
						$test_id = $row->test_id;
					}
					$test_name = $this->onehealth_model->getTestNameById($health_facility_test_table_name,$main_test_id);
					
					
					if($test_name == ""){
						$test_name = $row->test_name;
					}
					$lab_id = $row->lab_id;
					$paid = $row->paid;
					if(!is_null($lab_id)){
					
					
						$form_array = array(
							'test_id' => $test_id,
							'lab_id' => $lab_id,
							'test_name' => $test_name,
							'main_test_id' => $main_test_id,
							'main_test' => 1,
							
							'date' => $date,
							
							'time' => $time
						);
						
						if($this->onehealth_model->checkIfThisTestHasBeenAdded($health_facility_main_test_result_table,$main_test_id,$lab_id) == false){
							$this->onehealth_model->addTestMainResult($form_array,$health_facility_main_test_result_table);
						}else{
							// echo $test_name;
							$this->onehealth_model->updateTestMainResult($form_array,$health_facility_main_test_result_table,$main_test_id,$lab_id);
						}
						
						
						$main_test_info = $this->onehealth_model->getTestById($health_facility_test_table_name,$main_test_id);
						if(is_array($main_test_info)){
							foreach($main_test_info as $row){
								$control_enabled = $row->control_enabled;
								$range_enabled = $row->range_enabled;
								
								if($range_enabled == 1){
									$range_type = $row->range_type;
									if($range_type == "interval"){
										$range_lower = $row->range_lower;
										$range_higher = $row->range_higher;
									}else if($range_type == "desirable"){
										$desirable_value = $row->desirable_value;
									}
								}
								$unit_enabled = $row->unit_enabled;
								if($unit_enabled == 1){
									$unit = $row->unit;
								}
								$test_no = $this->onehealth_model->getNoOfSubTests($health_facility_test_table_name,$main_test_id);
								
								
							}
						}else{
							$test_deleted = true;
						}
						
						$array = array(
							'lab_id' => $lab_id,
							'test_id' => $test_id,
							'test_name' => $test_name,
							'main_test' => 1,
							'sub_test' => 0
						);	
						$test_result_id = $this->onehealth_model->getResultId($health_facility_main_test_result_table,$array);	
						$form_array2 = array(
							'super_test_id' => $test_result_id
						);
						if($test_deleted == false){
							if($test_no > 0){ 
								$g = 0;
								$sub_tests = $this->onehealth_model->getTestsSubTests($health_facility_test_table_name,$main_test_id);
								foreach($sub_tests as $row){
			                      $g++;
			                      $sub_test_id = $row->id;
			                      $test_id = $row->test_id;
			                      $test_name = $row->name;
			                      $sample_required = $row->sample_required;
			                      $indication = $row->indication;
			                      $test_cost = $row->cost;
			                      $ta_time = $row->t_a;
			                      $ta_active = $row->active;
			                      $control_enabled = $row->control_enabled;
			                      $under = $row->under;
			                      
									$range_enabled = $row->range_enabled;
									
									if($range_enabled == 1){
										$range_type = $row->range_type;
										if($range_type == "interval"){
											$range_lower = $row->range_lower;
											$range_higher = $row->range_higher;
										}else if($range_type == "desirable"){
											$desirable_value = $row->desirable_value;
										}
									}
			                      $unit_enabled = $row->unit_enabled;
			                      $unit = $row->unit;
			                      $super_main_test_id = $this->onehealth_model->getTestSuperTest($health_facility_test_table_name,$sub_test_id);
									$this_id = $this->onehealth_model->getMainTestResultIdByMainTestId($health_facility_main_test_result_table,$super_main_test_id,$lab_id);
									$form_array = array(
										'test_id' => $test_id,
										'lab_id' => $lab_id,
										'test_name' => $test_name,
										'main_test' => 0,
										'sub_test' => 1,
										'super_test_id' => $this_id,
										'main_test_id' => $sub_test_id,
										'date' => $date,
										'time' => $time
									);
									if($this->onehealth_model->checkIfThisTestHasBeenAdded2($health_facility_main_test_result_table,$sub_test_id,$lab_id,$this_id) == false){												
										$this->onehealth_model->addTestMainResult($form_array,$health_facility_main_test_result_table);
									}else{
										$this->onehealth_model->updateTestMainResult($form_array,$health_facility_main_test_result_table,$sub_test_id,$lab_id);
									}
								}
							}
						}	
					}	

				}
			}
		}

		public function fixIfDrugParamHasBeenEnteredBefore($name,$type){
			$query = $this->db->get_where('drugs_parameters',array('name' => $name,'type' => $type));
			if($query->num_rows() == 0){
				$this->db->insert('drugs_parameters',array('name' => $name,'type' => $type));
			}
		}

		public function addNewDrug($form_array){
			return $this->db->insert('drugs',$form_array);
		}

		public function getDrugsParamsFilteredResults($type,$term){
			$ret = array();
			$this->db->select("*");
			$this->db->from('drugs_parameters');
			$this->db->where('type',$type);
			$this->db->like('name',$term,'after');
			$this->db->limit(30);

			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result()  as $row){
					$name = $row->name;
					$ret[] = $name;
				}
			}
			return $ret;
		}

		public function getMainStoreDrugsForFacility($health_facility_id){
			// $query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id),'');
			$this->db->select("*");
			$this->db->from('drugs');
			$this->db->where('health_facility_id',$health_facility_id);
			// $this->db->where('main_store_quantity !=',0);
			$this->db->order_by('id','DESC');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getDrugGenericNameById($health_facility_id,$id){
			$query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id,'id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$generic_name = $row->generic_name;
				}
				return $generic_name;
			}else{
				return false;
			}
		}

		public function getDrugBrandNameById($health_facility_id,$id){
			$query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id,'id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$brand_name = $row->brand_name;
				}
				return $brand_name;
			}else{
				return false;
			}
		}

		public function getDrugStrengthById($health_facility_id,$id){
			$query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id,'id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$strength = $row->strength;
				}
				return $strength;
			}else{
				return false;
			}
		}

		public function getDrugStrengthUnitById($health_facility_id,$id){
			$query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id,'id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$strength_unit = $row->strength_unit;
				}
				return $strength_unit;
			}else{
				return false;
			}
		}

		public function getDrugFormulationById($health_facility_id,$id){
			$query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id,'id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$formulation = $row->formulation;
				}
				return $formulation;
			}else{
				return false;
			}
		}

		public function getDrugUnitById($health_facility_id,$id){
			$query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id,'id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$unit = $row->unit;
				}
				return $unit;
			}else{
				return false;
			}
		}

		public function checkIfDrugExists($health_facility_id,$id){
			if($id == 0){
				return true;
			}
			$query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id,'id' => $id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getMainStoreLogsForFacility($health_facility_id){
			$query = $this->db->get_where('main_store_activity_log',array('health_facility_id' => $health_facility_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function editDrug($form_array,$id){
			return $this->db->update('drugs',$form_array,array('id' => $id));
		}

		public function getMainStoreQuantityById($id,$health_facility_id){
			$query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id,'id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$main_store_quantity = $row->main_store_quantity;
				}
				return $main_store_quantity;
			}
		}

		public function checkIfQuantityIsChangedAndPerformAction($quantity,$health_facility_id,$id,$type){
			$personnel_id = $this->getUserIdWhenLoggedIn();
			$date = date("j M Y");
			$time = date("h:i:sa");
			$query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id,'id' => $id,'main_store_quantity' => $quantity));
			if($query->num_rows() == 0){
				$main_store_quantity = $this->getMainStoreQuantityById($id,$health_facility_id);
				if($type == "edit"){
					$generic_name = $this->getDrugGenericNameById($health_facility_id,$id);
					$brand_name = $this->getDrugBrandNameById($health_facility_id,$id);	
					$formulation = $this->getDrugFormulationById($health_facility_id,$id);

					$reason = "Quantity Of Drug With Generic Name: <em class='text-primary'>" . $generic_name . "</em> ,Brand Name:  <em class='text-primary'>" . $brand_name . "</em> Of <em class='text-primary'>" . $formulation . "</em> Formulation Was Changed From <em class='text-primary'>" . number_format($main_store_quantity,2) . " Units</em> To <em class='text-primary'>" . number_format($quantity,2)." Units</em>";
					$this->db->insert('main_store_activity_log',array('health_facility_id' => $health_facility_id,'date' => $date,'time' => $time,'personnel_id' => $personnel_id,'summary' => $reason));
				}
			}
		}

		public function deleteDrug($id,$health_facility_id){
			return $this->db->delete('drugs',array('health_facility_id' => $health_facility_id,'id' => $id));
		}

		public function performActionOnDrugIfDeleted($health_facility_id,$id){
			$personnel_id = $this->getUserIdWhenLoggedIn();
			$date = date("j M Y");
			$time = date("h:i:sa");

			$generic_name = $this->getDrugGenericNameById($health_facility_id,$id);
			$brand_name = $this->getDrugBrandNameById($health_facility_id,$id);	
			$formulation = $this->getDrugFormulationById($health_facility_id,$id);	

			$reason = "Drug With Generic Name: <em class='text-primary'>" . $generic_name . "</em> ,Brand Name:  <em class='text-primary'>" . $brand_name . "</em> Of <em class='text-primary'>" . $formulation . "</em> Formulation Was Deleted From The Main Store.</em>";

			$this->db->insert('main_store_activity_log',array('health_facility_id' => $health_facility_id,'date' => $date,'time' => $time,'personnel_id' => $personnel_id,'summary' => $reason));
		}

		public function getDrugForFacilityById($health_facility_id,$id){
			// $query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id),'');
			$this->db->select("*");
			$this->db->from('drugs');
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->where('id',$id);
			$query = $this->db->get();
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function isJson($string) {
		 json_decode($string);
		 return (json_last_error() == JSON_ERROR_NONE);
		}

		public function curl($url, $use_post, $post_data=[]){
	        $curl = curl_init();
	        
	        curl_setopt($curl, CURLOPT_URL, $url.'?'.http_build_query($post_data));
	        
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	        
	        if($use_post){
	            curl_setopt($curl, CURLOPT_POST, TRUE);
	            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data));
	        }
	        //Modify this two lines to suit your needs
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);//curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
	        $response = curl_exec($curl);
	        curl_close($curl);
	        
	        return $response;
	    }

	    public function updateRadiologyTests($test_table_name,$form_array){
	    	$query = $this->db->update($test_table_name,$form_array,array('sub_dept_id' => 6));
	    	if($query){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function updateHistoTests($test_table_name,$form_array){
	    	$query = $this->db->update($test_table_name,$form_array,array('sub_dept_id' => 7));
	    	if($query){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function updateMicroTests($test_table_name,$form_array){
	    	$query = $this->db->update($test_table_name,$form_array,array('sub_dept_id' => 2));
	    	if($query){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function custom_curl($url,$use_post,$post_data=[]){
	    	$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL,$url);
			if($use_post){
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($post_data));
			}
			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$response = curl_exec($ch);

			curl_close ($ch);
			return $response;
	    }


		public function getInitiationCodeById($health_facility_test_result_table,$id){
			$query = $this->db->get_where($health_facility_test_result_table,array('id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
				}
				return $initiation_code;
			}else{
				return false;
			}
		}

		public function markPaidTests1($id,$form_array,$health_facility_name,$health_facility_test_result_table,$sub_dept_id){
			if($this->db->table_exists($health_facility_test_result_table)){
				$query = $this->db->update($health_facility_test_result_table,$form_array,array('id' => $id,'facility_name' => $health_facility_name,'sub_dept_id' => $sub_dept_id));
				if($query){
					return true;
				}else{
					return false;
				}
			}else{
				$query_str = 'CREATE TABLE ' .$health_facility_test_result_table.' (
					id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					main_test_id INT NOT NULL,
					facility_name VARCHAR(100) NOT NULL,
					referring_facility_id INT NULL,
					record_id INT NULL,
					ward_id INT NULL,
					referral_id INT NULL,
					initiation_code VARCHAR(100) NOT NULL,
					lab_id TEXT NULL,
					sub_dept_id INT NOT NULL,
					test_id VARCHAR(1000) NOT NULL,
					receptionist INT NULL,
					teller INT NULL,
					receipt_file TEXT NULL,
					patient_name VARCHAR(200) NOT NULL,
					test_name TEXT NOT NULL,
					patient_username VARCHAR(100) NULL,
					patient_email VARCHAR(50) NULL,
					price BIGINT(20) NOT NULL,
					amount_paid BIGINT(20) NOT NULL,
					ta_time BIGINT(20) NOT NULL,
					date VARCHAR(100) NOT NULL,
					time VARCHAR(100) NOT NULL,
					invalid INT NOT NULL DEFAULT 0,
					paid INT NOT NULL DEFAULT 0,
					date_paid VARCHAR(50) NOT NULL,
					time_paid VARCHAR(50) NOT NULL,
					refund_requested INT NOT NULL DEFAULT 0,
					refund_request_code TEXT NULL,
					payment_initiated INT DEFAULT 0 NOT NULL,
					patient_locked INT DEFAULT 0 NOT NULL,
					registered INT DEFAULT 0

				)';
				if($this->db->query($query_str)){
					$query = $this->db->udpate($health_facility_test_result_table,$form_array,array('id' => $id,'facility_name' => $health_facility_name));
					if($query){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}
		}

		public function markPaidTests2($initiation_code,$form_array,$health_facility_name,$health_facility_test_result_table,$sub_dept_id){
			$query = $this->db->update($health_facility_test_result_table,$form_array,array('initiation_code' => $initiation_code,'facility_name' => $health_facility_name,'sub_dept_id' => $sub_dept_id));
			if($query){
				return true;
			}else{
				return false;
			}
			
		}

		public function markPaidTests($id,$form_array,$health_facility_name,$health_facility_test_result_table){
			if($this->db->table_exists($health_facility_test_result_table)){
				$query = $this->db->update($health_facility_test_result_table,$form_array,array('id' => $id,'facility_name' => $health_facility_name));
				if($query){
					return true;
				}else{
					return false;
				}
			}else{
				$query_str = 'CREATE TABLE ' .$health_facility_test_result_table.' (
					id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					main_test_id INT NOT NULL,
					facility_name VARCHAR(100) NOT NULL,
					referring_facility_id INT NULL,
					record_id INT NULL,
					ward_id INT NULL,
					referral_id INT NULL,
					initiation_code VARCHAR(100) NOT NULL,
					lab_id TEXT NULL,
					sub_dept_id INT NOT NULL,
					test_id VARCHAR(1000) NOT NULL,
					receptionist INT NULL,
					teller INT NULL,
					receipt_file TEXT NULL,
					patient_name VARCHAR(200) NOT NULL,
					test_name TEXT NOT NULL,
					patient_username VARCHAR(100) NULL,
					patient_email VARCHAR(50) NULL,
					price BIGINT(20) NOT NULL,
					amount_paid BIGINT(20) NOT NULL,
					ta_time BIGINT(20) NOT NULL,
					date VARCHAR(100) NOT NULL,
					time VARCHAR(100) NOT NULL,
					invalid INT NOT NULL DEFAULT 0,
					paid INT NOT NULL DEFAULT 0,
					date_paid VARCHAR(50) NOT NULL,
					time_paid VARCHAR(50) NOT NULL,
					refund_requested INT NOT NULL DEFAULT 0,
					refund_request_code TEXT NULL,
					payment_initiated INT DEFAULT 0 NOT NULL,
					patient_locked INT DEFAULT 0 NOT NULL,
					registered INT DEFAULT 0

				)';
				if($this->db->query($query_str)){
					$query = $this->db->udpate($health_facility_test_result_table,$form_array,array('id' => $id,'facility_name' => $health_facility_name));
					if($query){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}
		}

		

		public function getLastRowPatientBio($health_facility_test_result_table,$health_facility_name){
			// $this->db->get_where('hospital_number');
			// $query = $this->db->get_where($health_facility_test_result_table);
			$numbers_array = array();
			$this->db->select("hospital_number");
			$this->db->from($health_facility_test_result_table);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$hospital_number = $row->hospital_number;
					if(!is_null($hospital_number)){
						$numbers_array[] = (Integer) substr($hospital_number, 0,-3);
					}
				}
			}

			if(is_array($numbers_array) && count($numbers_array) > 0){
				return max($numbers_array);
			}else{
				return 0;
			}
		}


		public function getLastRowMortuary($health_facility_id){
			$this->db->select_max('mortuary_number');
			$query = $this->db->get_where("mortuary",array('health_facility_id' => $health_facility_id,'records_registered' => 1));
			
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getExternalUnlinkedRecordsMortuary($health_facility_id){
			$query = $this->db->get_where("mortuary",array('health_facility_id' => $health_facility_id,'records_registered' => 1,'user_id' => 0,'discharged' => 0));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function genereateMortuaryFieldsString($table_name){
			$query_str = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$table_name."'";
			$query = $this->db->query($query_str);
			$ret = "";
			foreach($query->result() as $row){
				$column_name = $row->COLUMN_NAME;
				$ret .= "$".$column_name . " = " . "$" . "row" . "->" . $column_name . ";<br>";
			}
			echo $ret;
		}


		public function genereateMortuaryFieldsString1($table_name){
			$query_str = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$table_name."'";
			$query = $this->db->query($query_str);
			$ret = "";
			foreach($query->result() as $row){
				$column_name = $row->COLUMN_NAME;
				$form_name = $this->replaceUnderscoreWithSpace($column_name);


				$ret .= '<div class="form-group col-sm-6">';
                $ret .= '<label for="'.$column_name.'" class="label-control"> '.$form_name.': </label>';
                $ret .= '<input type="text" class="form-control" id="'.$column_name.'" name="'.$column_name.'">';
                $ret .= '<span class="form-error"></span>';
                $ret .= '</div>';
			}
			echo $ret;
		}

		public function genereateMortuaryFieldsRulesString($table_name){
			$query_str = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$table_name."'";
			$query = $this->db->query($query_str);
			$ret = "";
			foreach($query->result() as $row){
				$column_name = $row->COLUMN_NAME;
				$form_name = $this->replaceUnderscoreWithSpace($column_name);

				
				$ret .= "'" . "" . $column_name . "' => " . "$" .$column_name.",<br>";
			}
			echo $ret;
		}

		public function createBodyAutopsyRecords($mortuary_record_id,$health_facility_id){
			$query = $this->db->get_where("mortuary_autopsy",array('mortuary_record_id' => $mortuary_record_id,'health_facility_id' => $health_facility_id));
			if($query->num_rows() == 0){
				$form_array = array(
					'health_facility_id' => $health_facility_id,
					'mortuary_record_id' => $mortuary_record_id
				);
				return $this->db->insert("mortuary_autopsy",$form_array);
			}else{
				return true;
			}
		}


		public function getAllRegisteredRecordsMortuary($health_facility_id){
			$query = $this->db->get_where("mortuary",array('health_facility_id' => $health_facility_id,'records_registered' => 1));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}


		public function getAllRegisteredRecordsMortuaryMortician($health_facility_id){
			$query = $this->db->get_where("mortuary",array('health_facility_id' => $health_facility_id,'records_registered' => 1,'discharged' => 0));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getMortuaryDailyMaintenance($health_facility_id,$mortuary_record_id){
			$query = $this->db->get_where("mortuary_daily_maintenance",array('health_facility_id' => $health_facility_id,'mortuary_record_id' => $mortuary_record_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getCurrentRecordsMortuary($health_facility_id){
			$query = $this->db->get_where("mortuary",array('health_facility_id' => $health_facility_id,'records_registered' => 1,'discharged' => 0));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getReadyCertificatesRecordsMortuary($health_facility_id){
			// $query = $this->db->get_where("",array('health_facility_id' => $health_facility_id,'records_registered' => 1,'death_certificate' => ''));
			$this->db->select("*");
			$this->db->from("mortuary");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("death_certificate !=","");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}




		public function getExternalUnlinkedRecordMortuaryById($health_facility_id,$id){
			$query = $this->db->get_where("mortuary",array('health_facility_id' => $health_facility_id,'records_registered' => 1,'user_id' => 0,'id' => $id,'discharged' => 0));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getRegisteredPatientsRecords($patient_bio_data_table){
			$query = $this->db->get_where($patient_bio_data_table,array('registered' => 1));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		

		public function getOffAppointmentsClinicNurse($clinic_activities_table_name,$dept_id,$sub_dept_id){
			// $query = $this->db->get_where($clinic_activities_table_name,array('dept_id' => $dept_id,'sub_dept_id' => $sub_dept_id));
			$this->db->select("*");
			$this->db->from($clinic_activities_table_name);
			$this->db->where('dept_id',$dept_id);
			$this->db->where('sub_dept_id',$sub_dept_id);
			$this->db->where('records_registered',1);
			$this->db->where('nurse_registered',0);
			$this->db->where('consultation_complete',0);
			$this->db->where('consultation_paid',1);
			$this->db->where('appointment_date','');
			$this->db->order_by('id','DESC');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		

		public function getPreviousClinicConsultationsForPatient($clinic_activities_table_name,$hospital_number,$sub_dept_id){
			$query = $this->db->get_where($clinic_activities_table_name,array('hospital_number' => $hospital_number,'sub_dept_id' => $sub_dept_id,'consultation_complete' => 1));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getAllWardRecordIdsForThisPatientForPreviousConsultations($hospital_number,$clinic_id,$health_facility_id){
			$query = $this->db->get_where("wards",array('hospital_number' => $hospital_number,'clinic_id' => $clinic_id,'discharged' => 1,'health_facility_id' => $health_facility_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}

		}

		public function getDoctorsWardConsultationInfo($id){
			$query = $this->db->get_where("doctors_ward_consultations",array('id' => $id));
			
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getPreviousWardConsultations($ward_record_id){
			$query = $this->db->get_where("doctors_ward_consultations",array('ward_record_id' => $ward_record_id));
			
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getNumberOfWardConsultations($ward_record_id){
			$query = $this->db->get_where("doctors_ward_consultations",array('ward_record_id' => $ward_record_id));
			return $query->num_rows();
		}

		public function getUserParamById($param,$user_id){
			$query = $this->db->get_where('users',array('id' => $user_id));
			if($query->num_rows() == 1){
				return $query->result()[0]->$param;
			}else{
				return false;
			}
		}

		

		public function checkIfEmailExists($email){
			$query = $this->db->get_where('users',array('email' => $email));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		
		public function getTestsSelectedByInitiationCodeAndFacilitySlug1($facility_slug,$initiation_code){
			$facility_name = $this->getFacilityNameBySlug($facility_slug);
			$facility_id = $this->getFacilityIdBySlug($facility_slug);
			$health_facility_test_result_table = $this->createTestResultTableHeaderString($facility_id,$facility_name);
			$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code,'paid' => 0));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		

		public function getSelectedTestsByReferralIdClinicDoctor($referral_id){
			$this->db->select("*");
			$this->db->from("tests_selected_consult");
			$this->db->where('referral_id',$referral_id);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getConsultParamByReferralId($referral_id,$param){
			$this->db->select("*");
			$this->db->from("referrals_or_consults");
			$this->db->where('id',$referral_id);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$param_val = $row->$param;
				}
				return $param_val;
			}else{
				return false;
			}
		}



		public function getOffAppointmentsClinicDoctor($clinic_activities_table_name,$dept_id,$sub_dept_id){
			// $query = $this->db->get_where($clinic_activities_table_name,array('dept_id' => $dept_id,'sub_dept_id' => $sub_dept_id));
			$this->db->select("*");
			$this->db->from($clinic_activities_table_name);
			$this->db->where('dept_id',$dept_id);
			$this->db->where('sub_dept_id',$sub_dept_id);
			$this->db->where('records_registered',1);
			$this->db->where('nurse_registered',1);
			$this->db->where('appointment_date','');
			$this->db->where('consultation_complete',0);
			$this->db->where('new_patient',0);
			$this->db->order_by('referred_date','DESC');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getPatientUserIdByHospitalNumber($patient_bio_data_table,$hospital_number){
			$query = $this->db->get_where($patient_bio_data_table,array('hospital_number' => $hospital_number));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$user_id = $row->user_id;
				}
				return $user_id;
			}
		}

		public function getServiceNameById($health_facility_id,$sub_dept_id,$service_id){
			$query = $this->db->get_where('ward_services',array('health_facility_id' => $health_facility_id,'ward_id' => $sub_dept_id,'id' => $service_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$name = $row->name;
				}
				return $name;
			}else{
				return false;
			}
		}


		public function getMortuaryServiceNameById($health_facility_id,$service_id){
			$query = $this->db->get_where('mortuary_services',array('health_facility_id' => $health_facility_id,'id' => $service_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$name = $row->name;
				}
				return $name;
			}else{
				return false;
			}
		}

		public function getRequestedMortuaryServicesByMortuaryRecordId($health_facility_id,$mortuary_record_id){
			$query = $this->db->get_where("mortuary_services_requested",array('health_facility_id' => $health_facility_id,'mortuary_record_id' => $mortuary_record_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function addToOutstandingPayment1($form_array){
			return $this->db->insert('outstanding_payment',$form_array);
		}	

		public function addToOutstandingPayment($form_array,$health_facility_name,$receiver,$amount){
			$query = $this->db->insert('outstanding_payment',$form_array);
			if($query){
				$date = date("j M Y");
				$time = date("h:i:sa");
				$insert_id = $this->db->insert_id();
				$sender = $health_facility_name;
    			$receiver = $this->getUserNameById($receiver);
    			$title = "Outstanding Payment";
    			$message = "This Is To Alert You That You Have An Outstanding Payment Of " .number_format($amount,2) . " At " .$health_facility_name. ". Click Below To Complete Payment";
    			$btn_1_url = site_url('onehealth/index/user_clinic_outstaning_payment/'.$insert_id);
    			$btn_1 = "<a href='".$btn_1_url."' class='btn btn-primary'>Proceed To Payment</a>";
    			$date_sent = $date;
    			$time_sent = $time;
    			$notif_array = array(
    				'sender' => $sender,
    				'receiver' => $receiver,
    				'title' => $title,
    				'message' => $message,
    				'btn_1' => $btn_1,
    				'date_sent' => $date_sent,
    				'time_sent' => $time_sent
    			);
    			if($this->sendMessage($notif_array)){
    				return true;
    			}
			}
		}

		public function getPatientBioDataParamById($patient_bio_data_table,$id,$param){
			$query = $this->db->get_where($patient_bio_data_table,array('id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$param_val = $row->$param;
				}
				return $param_val;
			}else{
				return false;
			}
		}

		public function getNewPatientsClinicDoctor($clinic_activities_table_name,$dept_id,$sub_dept_id){
			// $query = $this->db->get_where($clinic_activities_table_name,array('dept_id' => $dept_id,'sub_dept_id' => $sub_dept_id));
			$this->db->select("*");
			$this->db->from($clinic_activities_table_name);
			$this->db->where('dept_id',$dept_id);
			$this->db->where('sub_dept_id',$sub_dept_id);
			$this->db->where('records_registered',1);
			$this->db->where('nurse_registered',1);
			$this->db->where('new_patient',1);
			$this->db->where('appointment_date','');
			$this->db->where('consultation_complete',0);
			// $this->db->where('new_patient',1);
			$this->db->order_by('referred_date','DESC');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getPatientSexByHospitalNumber($patient_bio_data_table,$hospital_number){
			$query = $this->db->get_where($patient_bio_data_table,array('hospital_number' => $hospital_number));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$sex = $row->sex;
				}
				return $sex;
			}else{
				return "";
			}
		}

		public function getPatientBioIdByHospitalNumber($patient_bio_data_table,$hospital_number){
			$query = $this->db->get_where($patient_bio_data_table,array('hospital_number' => $hospital_number));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$id = $row->id;
				}
				return $id;
			}else{
				return "";
			}
		}

		public function getPatientFulNameByHospitalNumber($patient_bio_data_table,$hospital_number){
			$query = $this->db->get_where($patient_bio_data_table,array('hospital_number' => $hospital_number));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$firstname = $row->firstname;
					$lastname = $row->lastname;
				}
				return $firstname . " " .$lastname;
			}else{
				return "";
			}
		}

		public function getPatientFirstNameByHospitalNumber($patient_bio_data_table,$hospital_number){
			$query = $this->db->get_where($patient_bio_data_table,array('hospital_number' => $hospital_number));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$firstname = $row->firstname;
				}
				return $firstname;
			}else{
				return "";
			}
		}

		public function getPatientLastNameByHospitalNumber($patient_bio_data_table,$hospital_number){
			$query = $this->db->get_where($patient_bio_data_table,array('hospital_number' => $hospital_number));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					
					$lastname = $row->lastname;
				}
				return $lastname;
			}else{
				return "";
			}
		}

		public function getPatientEmailByHospitalNumber($patient_bio_data_table,$hospital_number){
			$query = $this->db->get_where($patient_bio_data_table,array('hospital_number' => $hospital_number));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					
					$email = $row->email;
				}
				return $email;
			}else{
				return "";
			}
		}

		public function getPatientHospitalNumberByWardRecordId($health_facility_id,$ward_record_id){
			$query = $this->db->get_where('wards',array('health_facility_id' => $health_facility_id,'id' => $ward_record_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$hospital_number = $row->hospital_number;
				}
				return $hospital_number;
			}else{
				return false;
			}
		}

		

		public function getHealthFacilitySlugByFacilityId($health_facility_id){
			$query = $this->db->get_where('health_facility',array('id' => $health_facility_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$slug = $row->slug;
				}
				return $slug;
			}
		}

		public function getWardAdmissionFee($ward_id,$health_facility_id){
			$query = $this->db->get_where('ward_admission_info',array('ward_id' => $ward_id,'health_facility_id' => $health_facility_id),1);
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$fee = $row->fee;

				}
				return $fee;
			}else{
				return 10000;
			}
		}

		public function getHospitalNumberByWardRecordId($ward_record_id){
			$query = $this->db->get_where('wards',array('id' => $ward_record_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$hospital_number = $row->hospital_number;
				}
				return $hospital_number;
			}else{
				return 0;
			}	
		}

		public function updateWardTable($form_array,$ward_record_id){
			return $this->db->update("wards",$form_array,array('id' => $ward_record_id));
		}

		public function getPatientUserIdByPharmacyInitiationCode($initiation_code){
			$query = $this->db->get_where("pharmacy_drugs_selected",array('initiation_code' => $initiation_code),1);
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$user_id = $row->user_id;
				}
				return $user_id;
			}
		}

		public function getWardIdByWardRecordId($health_facility_id,$ward_record_id){
			$query = $this->db->get_where('wards',array('health_facility_id' => $health_facility_id,'id' => $ward_record_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$ward_id = $row->sub_dept_id;
				}
				return $ward_id;
			}else{
				return 0;
			}
		}

		public function getAllFacilityOutstandingBills($health_facility_id){
			$query = $this->db->get_where('outstanding_payment',array('health_facility_id' => $health_facility_id,'paid' => 0));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getPatientLastOweDate($patient_id,$health_facility_id){
			$this->db->select("*");
			$this->db->from("outstanding_payment");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("patient_id",$patient_id);
			$this->db->where("paid",0);
			$this->db->order_by("id","DESC");
			$this->db->limit(1);
			$query = $this->db->get();
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$date = $row->date;
					$time = $row->time;

					return array(
						'date' => $date,
						'time' => $time
					);
				}
			}
		}

		public function getPatientTotalOustandingPaymentSum($patient_id,$health_facility_id){
			$total_amount = 0;
			$query = $this->db->get_where('outstanding_payment',array('health_facility_id' => $health_facility_id,'paid' => 0,'patient_id' => $patient_id));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$amount = $row->amount;
					$total_amount += $amount;
				}
			}
			return $total_amount;
		}

		public function checkIfOutstandingBillIdIsValid($id){
			$user_id = $this->getUserIdWhenLoggedIn();
			$query = $this->db->get_where('outstanding_payment',array('patient_id' => $user_id,'id' => $id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getHealthFacilityIdByOutstandingBillId($id){
			$query = $this->db->get_where('outstanding_payment',array('id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$health_facility_id = $row->health_facility_id;
				}
				return $health_facility_id;
			}else{
				return false;
			}
		}

		public function getHealthFacilitySlugById($health_facility_id){
			$query = $this->db->get_where('health_facility',array('id' => $health_facility_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$slug = $row->slug;
				}
				return $slug;
			}
		}

		public function getHealthFacilitySlugByOutstandingBillId($id){
			$health_facility_id = $this->getHealthFacilityIdByOutstandingBillId($id);
			return $this->getHealthFacilitySlugById($health_facility_id);
		}

		public function filterOutstandingBillsRecordsIdForFacility($health_facility_id,$ids){
			$ret = array();
			if(is_array($ids)){
				$id1 = $ids[0];
				$patient_id = $this->getPatientUserIdByClinicOutstandingBillId($health_facility_id,$id1);
				for($i = 0; $i < count($ids); $i++){

					$id = $ids[$i];
					$query = $this->db->get_where('outstanding_payment',array('health_facility_id' => $health_facility_id,'id' => $id,'paid' => 0,'patient_id' => $patient_id));
					if($query->num_rows() == 1){
						$ret[] = $id;
					}
				}
			}
			return $ret;
		}

		public function getPatientOutstandingBillsInfo($health_facility_id,$patient_id){
			
			$query = $this->db->get_where('outstanding_payment',array('health_facility_id' => $health_facility_id,'paid' => 0,'patient_id' => $patient_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getPatientOutstandingBillInfo($health_facility_id,$patient_id,$id){
			
			$query = $this->db->get_where('outstanding_payment',array('health_facility_id' => $health_facility_id,'patient_id' => $patient_id,'id' => $id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function checkIfOustandingBillIsPaid($health_facility_id,$patient_id,$id){
			$user_id = $this->getUserIdWhenLoggedIn();
			$query = $this->db->get_where('outstanding_payment',array('patient_id' => $user_id,'health_facility_id' => $health_facility_id,'patient_id' => $patient_id,'id' => $id,'paid' => 0));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}


		public function getPatientUserIdByClinicOutstandingBillId($health_facility_id,$id){
			$query = $this->db->get_where('outstanding_payment',array('health_facility_id' => $health_facility_id,'id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$patient_id = $row->patient_id;
				}
				return $patient_id;
			}else{
				return false;
			}
		}

		public function getPatientReasonByClinicOutstandingBillId($health_facility_id,$id){
			$query = $this->db->get_where('outstanding_payment',array('health_facility_id' => $health_facility_id,'id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$reason = $row->reason;
				}
				return $reason;
			}else{
				return false;
			}
		}

		public function getPatientAmountByClinicOutstandingBillId($health_facility_id,$id){
			$query = $this->db->get_where('outstanding_payment',array('health_facility_id' => $health_facility_id,'id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$amount = $row->amount;
				}
				return $amount;
			}else{
				return false;
			}
		}

		public function markClinicOutstandingBillRecordAsPaid($health_facility_id,$id,$personnel_id,$date,$time){
			return $this->db->update('outstanding_payment',array('paid' => 1,'cleared_by' => $personnel_id,'paid_date' => $date,'paid_time' => $time),array('health_facility_id' => $health_facility_id,'id' => $id));
		}

		public function getOutstandingBillsParamById($id,$param){
			$query = $this->db->get_where("outstanding_payment",array('id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$param_val = $row->$param;
				}
				return $param_val;
			}else{
				return false;
			}
		}

		

		public function getAllPatientsOwingFacility($health_facility_id){
			$ret = array();
			$this->db->select("patient_id");
			$this->db->from("outstanding_payment");
			$this->db->where("health_facility_id",$health_facility_id);
			// $this->db->where("type !=","mortuary_service");
			$this->db->where("paid",0);
			$this->db->order_by("id","DESC");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$patient_id = $row->patient_id;
					$ret[] = $patient_id;
				}
			}else{
				return false;
			}
			$ret = array_values(array_unique($ret));
			return $ret;
		}

		public function processWardAdmissionPayment($form_array,$form_array1,$ward_record_id,$health_facility_id){
			$query = $this->db->update('wards',$form_array,array('id' => $ward_record_id,'health_facility_id' => $health_facility_id));
			if($query){
				$query = $this->db->insert('clinic_payments',$form_array1);
				return $query;
			}else{
				return false;
			}
		}


		public function saveCodeRecordsOfficer($form_array){
			return $this->db->insert("verification_codes_records",$form_array);
		}

		public function updateCodeRecordsOfficer($form_array,$id){
			return $this->db->update("verification_codes_records",$form_array,array('id' => $id));
		}

		public function checkIfThisCodeHasBeenUsedBeforeRecords($health_facility_id,$value){
			$query = $this->db->get_where("verification_codes_records",array('health_facility_id' => $health_facility_id,'code' => $value));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getVerificationCodesRecords($form_array){
			$query = $this->db->get_where("verification_codes_records",$form_array);
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}


		public function getIfWardPatientAdmissionHasExpired($health_facility_id,$ward_record_id){
			$query = $this->db->get_where('wards',array('health_facility_id' => $health_facility_id,'id' => $ward_record_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					
					$id = $row->id;
					$admission_fee = $row->admission_fee;
					$admission_no_of_days = $row->admission_no_of_days;
					$admission_grace = $row->admission_grace;
					$last_admission_payment_date = $row->last_admission_payment_date;
					$last_admission_payment_time = $row->last_admission_payment_time;
					$total_days = $admission_no_of_days + $admission_grace;

					
					$doctor_id = $row->doctor_id;
					$ward_id = $row->sub_dept_id;
					$clinic_id = $row->clinic_id;
					$clinic_name = $this->getSubDeptNameById($clinic_id);
					$ward_name = $this->getSubDeptNameById($ward_id);
					$date = $row->date;
					$time = $row->time;
					$balance = $row->balance;

					$user_type = $row->user_type;


					$curr_date = strtotime(date("j M Y"));
					$curr_time = date("h:i:sa");

					if($user_type == "nfp"){
						return false;
					}

					$expired = true;
					if($admission_no_of_days != 0){
						$expiry_date = strtotime(date("j M Y",strtotime($last_admission_payment_date .' + '.$total_days.' days')));
						// var_dump($expiry_date > $curr_date);
						if($expiry_date > $curr_date){
							$expired = false;
						}
					}

					//Check If Patient Is Owing
					if($admission_no_of_days == 0 || $expired){
						return true;
					}else{
						return false;
					}	
				}	
			}
		}

		public function getIfWardPatientAdmissionHasExpiredFirstStage($health_facility_id,$ward_record_id){
			$query = $this->db->get_where('wards',array('health_facility_id' => $health_facility_id,'id' => $ward_record_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					
					$id = $row->id;
					$admission_fee = $row->admission_fee;
					$admission_no_of_days = $row->admission_no_of_days;
					$admission_grace = $row->admission_grace;
					$last_admission_payment_date = $row->last_admission_payment_date;
					$last_admission_payment_time = $row->last_admission_payment_time;
					$total_days = $admission_no_of_days;

					
					$doctor_id = $row->doctor_id;
					$ward_id = $row->sub_dept_id;
					$clinic_id = $row->clinic_id;
					$clinic_name = $this->getSubDeptNameById($clinic_id);
					$ward_name = $this->getSubDeptNameById($ward_id);
					$date = $row->date;
					$time = $row->time;
					$balance = $row->balance;

					$user_type = $row->user_type;
					
					if($user_type == "nfp"){
						return false;
					}

					$curr_date = strtotime(date("j M Y"));
					$curr_time = date("h:i:sa");
					$expired = true;
					if($admission_no_of_days != 0){
						$expiry_date = strtotime(date("j M Y",strtotime($last_admission_payment_date .' + '.$total_days.' days')));
						if($expiry_date > $curr_date){
							$expired = false;
						}
					}

					//Check If Patient Is Owing
					if($admission_no_of_days == 0 || $expired){
						return true;
					}else{
						return false;
					}	
				}	
			}
		}

		public function getDrugsForSelectionInPharmacy($health_facility_id){
			$query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function calculatePrescription ($dosage, $frequency_num,$frequency_time,$duration_num,$duration_time,$price) {
			// echo $dosage . " " .$frequency_num . " " .$frequency_time . " " .$duration_num . " " . $duration_time . " " .$price;
			// echo $price;
    
		    if($dosage != "" && $frequency_num != "" && $frequency_time != "" && $duration_num != "" && $duration_time != ""){
		      // console.log(i)
		      $quantity = 0;
		      if($frequency_time == "nocte" || $frequency_time == "stat"){
		        $frequency_time = "daily";
		      }

		      if($frequency_time == "yearly" && $duration_time == "years"){
		        
		      }else if($frequency_time == "monthly" && $duration_time == "years"){
		        $duration_num = 12 * $duration_num;
		      }else if($frequency_time == "weekly" && $duration_time == "years"){
		        $duration_num = 12 * 4 * $duration_num;
		      }else if($frequency_time == "daily" && $duration_time == "years"){
		        $duration_num = 12 * 28 * $duration_num;
		      }else if($frequency_time == "hourly" && $duration_time == "years"){
		        $duration_num = 12 * 28 * 24 * $duration_num;
		      }else if($frequency_time == "minutely" && $duration_time == "years"){
		        $duration_num = 12 * 28 * 24 * 60 * $duration_num;
		      }else if($frequency_time == "monthly" && $duration_time == "months"){
		        
		      }else if($frequency_time == "weekly" && $duration_time == "months"){
		        $duration_num = 4 * $duration_num;
		      }else if($frequency_time == "daily" && $duration_time == "months"){
		        $duration_num = 28 * $duration_num;
		      }else if($frequency_time == "hourly" && $duration_time == "months"){
		        $duration_num = 28 * 24 * $duration_num;
		      }else if($frequency_time == "minutely" && $duration_time == "months"){
		        $duration_num = 28 * 24 * 60 * $duration_num;
		      }else if($frequency_time == "weekly" && $duration_time == "weeks"){
		        
		      }else if($frequency_time == "daily" && $duration_time == "weeks"){
		        $duration_num = 7 * $duration_num;
		      }else if($frequency_time == "hourly" && $duration_time == "weeks"){
		        $duration_num = 7 * 24 * $duration_num;
		      }else if($frequency_time == "minutely" && $duration_time == "weeks"){
		        $duration_num = 7 * 24 * 60 * $duration_num;
		      }else if($frequency_time == "daily" && $duration_time == "days"){
		        
		      }else if($frequency_time == "hourly" && $duration_time == "days"){
		        $duration_num =  24 * $duration_num;
		      }else if($frequency_time == "minutely" && $duration_time == "days"){
		        $duration_num =  24 * 60 * $duration_num;
		      }else if($frequency_time == "hourly" && $duration_time == "hours"){
		        
		      }else if($frequency_time == "minutely" && $duration_time == "hours"){
		        $duration_num =  60 * $duration_num;
		      }else{
		        $duration_num = 0;
		        $frequency_num = 0;
		      }


		      
		      if($duration_num > 0 || $frequency_num > 0){
			      $quantity = ($duration_num / $frequency_num);
			      $quantity = $dosage * $quantity;
			      $quantity = round($quantity,2);


			      if(!is_null($quantity)){
			      	$price = round($price,2);
			        if($price != null){
			          $total_price = $price * $quantity;

			          return array(
			          	'quantity' => $quantity,
			          	'total_price' => $total_price
			          );
			        }
			     }
			  }
		    }
		  }

		public function getDrugPriceById($health_facility_id,$drug_id){
			$query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id,'id' => $drug_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$price = $row->price;
				}
				return $price;
			}else{
				return false;
			}
		}


		public function getDrugPoisonStatusById($health_facility_id,$drug_id){
			$query = $this->db->get_where('drugs',array('health_facility_id' => $health_facility_id,'id' => $drug_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$is_poison = $row->is_poison;
				}
				return $is_poison;
			}else{
				return false;
			}
		}  


		public function getPharmacyErrorRegister($health_facility_id){
			$query = $this->db->get_where('pharmacy_error_register',array('health_facility_id' => $health_facility_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getPharmacyErrorRegisterRecordById($health_facility_id,$id){
			$query = $this->db->get_where('pharmacy_error_register',array('health_facility_id' => $health_facility_id,'id' => $id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function savePharmacyDrugPrescription($user_array){

			return $this->db->insert('pharmacy_drugs_selected',$user_array);
		}




		public function getInitiationCodesOfPharmacyClinic($health_facility_id,$record_id){
			// $query = $this->db->get_where("pharmacy_drugs_selected_clinics",array('health_facility_id' => $health_facility_id));
			$ret = array();
			$this->db->select("initiation_code");
			$this->db->from("pharmacy_drugs_selected_clinics");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("record_id",$record_id);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					$ret[] = $initiation_code;
				}
			}else{
				return false;
			}

			return $ret;

		}


		public function getInitiationCodesOfPharmacyClinicReferral($health_facility_id,$referral_id){
			// $query = $this->db->get_where("pharmacy_drugs_selected_clinics",array('health_facility_id' => $health_facility_id));
			$ret = array();
			$this->db->select("initiation_code");
			$this->db->from("pharmacy_drugs_selected_referral_consult");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("referral_id",$referral_id);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					$ret[] = $initiation_code;
				}
			}else{
				return false;
			}

			return $ret;

		}


		public function getPharmacyNameByInitiationCode($initiation_code){
			$query = $this->db->get_where("pharmacy_drugs_selected",array('initiation_code' => $initiation_code),1);
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$health_facility_id = $row->health_facility_id;
					$health_facility_name= $this->getHealthFacilityNameById($health_facility_id);
				}
				return $health_facility_name;
			}else{
				return false;
			}
		}

		public function getNumberOfDrugsByInitiationCode($initiation_code){
			$query = $this->db->get_where("pharmacy_drugs_selected",array('initiation_code' => $initiation_code));
			return $query->num_rows();
		}

		public function getPharmacySelectedDrugsByInitiationCode($health_facility_id,$initiation_code){
			$query = $this->db->get_where("pharmacy_drugs_selected",array('initiation_code' => $initiation_code));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}


		public function getInitiationCodesOfPharmacyWard($health_facility_id,$ward_record_id){
			// $query = $this->db->get_where("pharmacy_drugs_selected_clinics",array('health_facility_id' => $health_facility_id));
			$ret = array();
			$this->db->select("*");
			$this->db->from("pharmacy_drugs_selected_wards");
			$this->db->where("health_facility_id",$health_facility_id);
			$this->db->where("ward_record_id",$ward_record_id);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
			return $ret;
		}

		public function getPharmacyDrugsSelectedDatesByInitiationCodes($initiation_codes){
			$ret = array();
			$this->db->select("date");
			$this->db->from("pharmacy_drugs_selected");
			// $this->db->where("health_facility_id",$health_facility_id);
			for($i = 0; $i < count($initiation_codes); $i++){
				if($i == 0){
					$this->db->where("initiation_code",$initiation_codes[$i]);
				}else{
					$this->db->or_where("initiation_code",$initiation_codes[$i]);
				}
			}
			$this->db->order_by("id","DESC");
			$query = $this->db->get();
			// echo $this->db->last_query();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$date = $row->date;
					$ret[] = $date;
				}

			}else{
				return false;
			}
			$ret = array_values(array_unique($ret));
			return $ret;
		}

		public function getRecordsForParticularDayPharmacy($date,$patient_id){
			$query = $this->db->get_where("pharmacy_drugs_selected",array('date' => $date,'user_id' => $patient_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getPharmacySelectedDrugStatusById($id){
			$query = $this->db->get_where("pharmacy_drugs_selected",array("id" => $id));
			if($query->num_rows() == 1){
				$ret = "";
				foreach($query->result() as $row){
					$paid = $row->paid;
					$dispensed = $row->dispensed;
					$dispensed_time = $row->dispensed_time;
					$dispatched = $row->dispatched;
					$dispatched_time = $row->dispatched_time;

					if($paid == 0){
						$ret = "Awaiting Payment";
					}else if($paid == 1 && $dispensed == 0){
						$ret = "Paid Awaiting Dispensing";
					}else if($paid == 1 && $dispensed == 1 && $dispatched == 0){
						$ret = "Dispensed At ".$dispensed_time . ". Awaiting Dispatching";
					}else if($paid == 1 && $dispatched == 1 && $dispensed == 1){
						$ret = "Dispatched At " .$dispatched_time;
					}
				}

				return $ret;
			}
		}

		public function getWardPatientLastAdmissionPaymentDate($health_facility_id,$ward_record_id){
			$query = $this->db->get_where('wards',array('health_facility_id' => $health_facility_id,'id' => $ward_record_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$last_admission_payment_date = $row->last_admission_payment_date;
				}
				return $last_admission_payment_date;
			}	
		}

		public function addNewDataToErrorRegister($form_data){
			return $this->db->insert('pharmacy_error_register',$form_data);
		}

		public function editDataErrorRegister($form_array,$id){
			return $this->db->update('pharmacy_error_register',$form_array,array('id' => $id));
		}

		public function getOTCPatientsPharmacyPendingPaymentByCode($health_facility_id,$initiation_code){
			$query = $this->db->get_where('pharmacy_drugs_selected',array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code),1);
			if($query->num_rows() == 1 && $this->getDrugsBalance($health_facility_id,$initiation_code) > 0){
				return $query->result();
			}
		}

		public function getPharmacyPatientInfoByInitiationCode($health_facility_id,$initiation_code,$type){
			$query = $this->db->get_where('pharmacy_drugs_selected',array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$ret = $row->$type;
					return $ret;
				}
			}else{
				return false;
			}
		}	

		public function getPharmacyPatientNoOfDrugsByInitiationCode($health_facility_id,$initiation_code){
			$query = $this->db->get_where('pharmacy_drugs_selected',array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
			
			return $query->num_rows();
		}	

		public function getPatientsDrugsYetToBeDispensedByInitiationCode($health_facility_id,$initiation_code){
			

			$query_str = "SELECT * FROM `pharmacy_drugs_selected` WHERE `health_facility_id` = ".$health_facility_id." AND initiation_code = '".$initiation_code."' AND `paid` = 1 AND (`dispensed` =1 OR `dispensed` = 0) AND ('dispatched' = 1 OR `dispatched` =0)";

			$query = $this->db->query($query_str);
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getOTCPatientPharmacyTotalPendingPaymentByCode($health_facility_id,$initiation_code){
			$ret = 0;
			$query = $this->db->get_where('pharmacy_drugs_selected',array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code,'paid' => 0));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$drug_id = $row->drug_id;
					if($this->checkIfDrugExists($health_facility_id,$drug_id)){
						$quantity = $row->quantity;
						$price = $row->price;

						$total_price = $quantity * $price;
						$ret += $total_price;
					}
				}
			}
			return $ret;
		}

		

		public function getDrugsSelectedByPatient($health_facility_id,$initiation_code){
			$query = $this->db->get_where('pharmacy_drugs_selected',array('initiation_code' => $initiation_code,'health_facility_id' => $health_facility_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}


		public function getOustandingPaymentsPharmacyByInitiationCode($health_facility_id,$initiation_code){
			$query = $this->db->get_where('pharmacy_drugs_selected',array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getWardPatientsPharmacyPendingPayment($health_facility_id){
			$ret = array();
			$this->db->select("initiation_code");
			$this->db->from('pharmacy_drugs_selected');
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->where('paid',0);
			$this->db->where('clinic',0);
			$this->db->where('ward',1);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					$ret[] = $initiation_code;
				}

				$ret = array_reverse(array_values(array_unique($ret)));
			}

			return $ret;
		}

		
		public function getOTCPatientsPharmacyPaidPayment($health_facility_id){
			$ret = array();
			$this->db->select("initiation_code");
			$this->db->from('pharmacy_drugs_selected');
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->where('paid',1);
			$this->db->where('ward',0);
			$this->db->where('clinic',0);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					$ret[] = $initiation_code;
				}

				$ret = array_reverse(array_values(array_unique($ret)));
			}

			return $ret;
		}

		public function getClinicPatientsPharmacyPaidPayment($health_facility_id){
			$ret = array();
			$this->db->select("initiation_code");
			$this->db->from('pharmacy_drugs_selected');
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->where('paid',1);
			$this->db->where('ward',0);
			$this->db->where('clinic',1);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					$ret[] = $initiation_code;
				}

				$ret = array_reverse(array_values(array_unique($ret)));
			}

			return $ret;
		}

				public function getWardPatientsPharmacyPaidPayment($health_facility_id){
			$ret = array();
			$this->db->select("initiation_code");
			$this->db->from('pharmacy_drugs_selected');
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->where('paid',1);
			$this->db->where('ward',1);
			$this->db->where('clinic',0);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					$ret[] = $initiation_code;
				}

				$ret = array_reverse(array_values(array_unique($ret)));
			}

			return $ret;
		}


		public function getOTCPatientsPharmacyPaidPaymentByCode($health_facility_id,$initiation_code){
			$ret = array();
			$this->db->select("*");
			$this->db->from('pharmacy_drugs_selected');
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->where('initiation_code',$initiation_code);
			$this->db->where('paid',1);
			$this->db->where('clinic',0);
			$this->db->where('ward',0);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getClinicPatientsPharmacyPaidPaymentByCode($health_facility_id,$initiation_code){
			$ret = array();
			$this->db->select("*");
			$this->db->from('pharmacy_drugs_selected');
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->where('initiation_code',$initiation_code);
			$this->db->where('paid',1);
			$this->db->where('clinic',1);
			$this->db->where('ward',0);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getWardPatientsPharmacyPaidPaymentByCode($health_facility_id,$initiation_code){
			$ret = array();
			$this->db->select("*");
			$this->db->from('pharmacy_drugs_selected');
			$this->db->where('health_facility_id',$health_facility_id);
			$this->db->where('initiation_code',$initiation_code);
			$this->db->where('paid',1);
			$this->db->where('clinic',0);
			$this->db->where('ward',1);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getOTCPatientsPharmacyAwaitingDispensingOrDispatching($health_facility_id){
			$ret = array();
			$query_str = "SELECT `initiation_code` FROM `pharmacy_drugs_selected` WHERE `health_facility_id` = ".$health_facility_id." AND `paid` = 1 AND (`dispensed` =1 OR `dispensed` = 0) AND ('dispatched' = 1 OR `dispatched` =0)";
			$query = $this->db->query($query_str);
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					$ret[] = $initiation_code;
				}

				$ret = array_reverse(array_values(array_unique($ret)));
			}

			return $ret;
		}


		public function getClinicPatientsPharmacyAwaitingDispensingOrDispatching($health_facility_id){
			$ret = array();
			$query_str = "SELECT `initiation_code` FROM `pharmacy_drugs_selected` WHERE `health_facility_id` = ".$health_facility_id." AND clinic = 1 AND ward = 0 AND `paid` = 1 AND (`dispensed` =1 OR `dispensed` = 0) AND ('dispatched' = 1 OR `dispatched` =0)";
			$query = $this->db->query($query_str);
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					$ret[] = $initiation_code;
				}

				$ret = array_reverse(array_values(array_unique($ret)));
			}

			return $ret;
		}

		public function getWardPatientsPharmacyAwaitingDispensingOrDispatching($health_facility_id){
			$ret = array();
			$query_str = "SELECT `initiation_code` FROM `pharmacy_drugs_selected` WHERE `health_facility_id` = ".$health_facility_id." AND clinic = 0 AND ward = 1 AND `paid` = 1 AND (`dispensed` =1 OR `dispensed` = 0) AND ('dispatched' = 1 OR `dispatched` =0)";
			$query = $this->db->query($query_str);
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$initiation_code = $row->initiation_code;
					$ret[] = $initiation_code;
				}

				$ret = array_reverse(array_values(array_unique($ret)));
			}

			return $ret;
		}

		public function getPharmacyReportsForFacility($health_facility_id){
			$query = $this->db->get_where('pharmacy_report',array('health_facility_id' => $health_facility_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}
		public function submitPharmacyReportForm($form_array){
			return $this->db->insert('pharmacy_report',$form_array);
		}

		public function submitPharmacyReportFormEdit($form_array,$health_facility_id,$id){
			return $this->db->update('pharmacy_report',$form_array,array("health_facility_id" => $health_facility_id,'id' => $id));
		}

		public function checkIfPersonnelIsValidToEditPharmacyReport($health_facility_id,$id){
			$user_id = $this->getUserIdWhenLoggedIn();
			$query = $this->db->get_where('pharmacy_report',array("health_facility_id" => $health_facility_id,"id" => $id,"personnel_id" => $user_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getWardPatientNoOfDays($health_facility_id,$ward_record_id){
			$query = $this->db->get_where('wards',array('health_facility_id' => $health_facility_id,'id' => $ward_record_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$no_of_days = $row->admission_no_of_days;
				}
				return $no_of_days;
			}	
		}


		public function getWardPatientGraceDays($health_facility_id,$ward_record_id){
			$query = $this->db->get_where('wards',array('health_facility_id' => $health_facility_id,'id' => $ward_record_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$grace_days = $row->admission_grace;
				}
				return $grace_days;
			}	
		}


		public function processWardAdmissionPayment1($form_array,$ward_record_id,$health_facility_id){
			return $this->db->update('wards',$form_array,array('id' => $ward_record_id,'health_facility_id' => $health_facility_id));
		}
			
		public function getWardNoOfDays($ward_id,$health_facility_id){
			$query = $this->db->get_where('ward_admission_info',array('ward_id' => $ward_id,'health_facility_id' => $health_facility_id),1);
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$no_of_days = $row->no_of_days;
				}
				return $no_of_days;
			}else{
				return 14;
			}
		}

		public function getWardGraceDays($ward_id,$health_facility_id){
			$query = $this->db->get_where('ward_admission_info',array('ward_id' => $ward_id,'health_facility_id' => $health_facility_id),1);
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$grace_days = $row->grace_days;
				}
				return $grace_days;
			}else{
				return 2;
			}
		}

		public function getPatientAgeByHospitalNumber($patient_bio_data_table,$hospital_number){
			$query = $this->db->get_where($patient_bio_data_table,array('hospital_number' => $hospital_number));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$age = $row->age;
					$age_unit = $row->age_unit;
				}
				return $age . " " . $age_unit;
			}else{
				return "";
			}
		}

		public function getRegisteredPatientsRecordsDoctor($patient_bio_data_table){
			$this->db->select("*");
			$this->db->from($patient_bio_data_table);
			$this->db->where('registered',1);
			$this->db->where('paid',1);
			$this->db->where('nurse_registered',1);
			$this->db->where('dr_registered',0);
			$this->db->order_by('nurse_time','DESC');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getRegisteredPatientsRecordsUnpaid($patient_bio_data_table){
			$query = $this->db->get_where($patient_bio_data_table,array('registered' => 1,'paid' => 0));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getRegisteredPatientsRecordById($patient_bio_data_table,$id){
			$query = $this->db->get_where($patient_bio_data_table,array('registered' => 1,'id' => $id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getSubmittedConsultationRecord($clinic_activities_table_name,$id){
			$query = $this->db->get_where($clinic_activities_table_name,array('consultation_complete' => 1,'id' => $id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getPatientActivityRecordById($clinic_activities_table_name,$id){
			$query = $this->db->get_where($clinic_activities_table_name,array('nurse_registered' => 1,'id' => $id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function checkIfAutopsyWasDoneOnBody($facility_id,$mortuary_record_id){
			$query = $this->db->get_where("mortuary_autopsy",array('mortuary_record_id' => $mortuary_record_id));
			if($query->num_rows() > 0 ){
				if($this->getMortuaryAutopsyParamByMortuaryRecordId($facility_id,$mortuary_record_id,"date") !== ""){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		public function getHospitalNumberByRecordId($clinic_activities_table_name,$record_id){

			$query = $this->db->get_where($clinic_activities_table_name,array('id' => $record_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$hospital_number = $row->hospital_number;
				}
				return $hospital_number;
			}else{
				return "";
			}
		}

		public function proceedReferralToNurse($health_facility_id,$sub_dept_id,$referral_id){
			return $this->db->update("referrals_or_consults",array('records_registered' => 1),array('referred_to_facility_id' => $health_facility_id,'referred_to_sub_dept_id' => $sub_dept_id,'id' => $referral_id));
		}

		public function getPatientBioDataParamByHospitalNumber($health_facility_patient_bio_data_table,$hospital_number,$param){
			$query = $this->db->get_where($health_facility_patient_bio_data_table,array('hospital_number' => $hospital_number));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$param_val = $row->$param;
				}
				return $param_val;
			}else{
				return false;
			}
		}


		public function getReferralOrConsultParamByHospitalNumber($referral_id,$param){
			$query = $this->db->get_where("referrals_or_consults",array('id' => $referral_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$param_val = $row->$param;
				}
				return $param_val;
			}else{
				return false;
			}
		}

		public function getPatientInfoParamByHospitalNumber($patient_bio_data_table,$hospital_number,$param){
			$query = $this->db->get_where($patient_bio_data_table,array('hospital_number' => $hospital_number),1);
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$param_val = $row->$param;
				}
				return $param_val;
			}else{
				return "";
			}
		}

		public function addPatientToWard($form_array){
			$query = $this->db->insert('wards',$form_array);
			return $query;
		}

		public function getLastRowTestResultRefundId($health_facility_test_result_table,$health_facility_name){
			$this->db->select_max('refund_request_code');
			$query = $this->db->get_where($health_facility_test_result_table,array('facility_name' => $health_facility_name));
			if($query->num_rows() == 1){
				
				return $query->result();
			}else{
				return false;
			}
		}

		public function getTestResultsByLabId($health_facility_main_test_result_table,$lab_id){
			$query = $this->db->get_where($health_facility_main_test_result_table,array('lab_id' => $lab_id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getResultId($health_facility_main_test_result_table,$array){
			$query = $this->db->get_where($health_facility_main_test_result_table,$array);
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$id = $row->id;
					return $id;
				}
			}else{
				return false;
			}
		}

		public function getPatientEmailByLabId($health_facility_patient_db_table,$lab_id){
			$query = $this->db->get_where($health_facility_patient_db_table,array('lab_id' => $lab_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$email = $row->email;
				}
				return $email;
			}else{
				return false;
			}
		}

		public function sendEmail($recepient_arr,$subject,$message){
			if($_SERVER['SERVER_NAME'] != "localhost"){
				if(count($recepient_arr) > 0){
					if($message !== ""){
						$message = "<h3>" . $message . "</h3>";
						$year = date("Y");
						$message .= "<h5><a href='https://onehealthissues.com'>One Health Issues Global Limited</a> &copy; " . $year . ". All Rights Reserved</h5>";
					}
					
						if(is_array($recepient_arr)){

							$mail = $this->phpmailer_library->load();				 
						    //Server settings
						    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
						    $mail->isSMTP();                                      // Set mailer to use SMTP
						    $mail->Host = 'localhost';  // Specify main and backup SMTP servers
						    $mail->SMTPAuth = true;                               // Enable SMTP authentication
						    $mail->Username = 'support@onehealthissues.com';                 // SMTP username
						    $mail->Password = 'ikechukwunwogo@gmail.com';                           // SMTP password
						    $mail->SMTPSecure = 'pop3';                            // Enable TLS encryption, `ssl` also accepted
						    $mail->Port = 25;                                    // TCP port to connect to

						    //Recipients
						    $mail->setFrom('support@onehealthissues.com', 'One Health Issues Global Limited');
						    for($i = 0; $i < count($recepient_arr); $i++){
						    	$to_email = $recepient_arr[$i];
						    	// if($this->checkIfEmailHasNotifEnabled($to_email)  && $this->checkIfEmailNotifIsEnabled()){
								    $mail->addAddress($to_email);     // Add a recipient
								// }
							}
						    // //Attachments
						    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
						    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

						    //Content
						    $mail->isHTML(true);                                  // Set email format to HTML
						    $mail->Subject = $subject;
						    $mail->Body    = $message;
						    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

						    if($mail->send()){
							    return true;
							}else{
								return false;
							}
						}
					
				}else{
					return true;
				}
			}else{
				return true;
			}			
		}

		
		public function getAllReadyTestsComments($health_facility_main_test_result_table,$lab_id,$health_facility_test_table_name){
			$ret = array();
			$this->db->select("*");
			$this->db->from($health_facility_main_test_result_table);
			$this->db->where("lab_id",$lab_id);
			$this->db->where("main_test",1);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$comment = $row->comment;
					$main_test_id = $row->main_test_id;
					if($this->checkIfTestNativeIdExists($main_test_id,$health_facility_test_table_name)){
						$test_id = $this->getTestIdById($health_facility_test_table_name,$main_test_id);
						$ret[] = array(
							'comment' => $comment,
							'test_id' => $test_id
						);
					}
				}
			}
			return $ret;
		}

		public function getCommentOfSuperTest($health_facility_main_test_result_table,$super_test_id){
			$query = $this->db->get_where($health_facility_main_test_result_table,array('id' => $super_test_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$comment = $row->comment;
					if(is_null($comment)){
						$comment = "";
					}
					// echo $comment;
					return $comment;
				}
			}else{
				return false;
			}
		}

		public function checkIfSubTestIsTheLastOne($health_facility_main_test_result_table,$id,$lab_id,$super_test_id){
			// $ret = array();
			$this->db->select_max("main_test_id");
			$this->db->from($health_facility_main_test_result_table);
			$this->db->where("lab_id",$lab_id);
			$this->db->where("super_test_id",$super_test_id);
			$this->db->where("sub_test",1);
			// $this->db->where("submitted",1);
			// $this->db->order_by("id","DESC");
			$query = $this->db->get();
			if($query->num_rows() == 1){
				// print_r($query->result());
				foreach($query->result() as $row){
					$main_test_id = $row->main_test_id;
					if($main_test_id == $id){
						// echo "string";
						return true;
					}else{
						return false;
					}
				}
			}else{
				return false;
			}
		}

		public function checkIfSubTestIsTheLastOneSelected($health_facility_main_test_result_table,$id,$lab_id,$super_test_id,$selected_arr){
			// var_dump($selected_arr);
			// echo $super_test_id . "<br>";
			$ret = array();
			for($i = 0; $i < count($selected_arr); $i++){
				$this->db->select("id");
				$this->db->from($health_facility_main_test_result_table);
				$this->db->where("lab_id",$lab_id);
				$this->db->where("super_test_id",$super_test_id);
				$this->db->where("sub_test",1);
				// $this->db->where("id",$selected_arr[$i]);
				// $this->db->where("submitted",1);
				// $this->db->order_by("id","DESC");

				$query = $this->db->get();
				// echo $this->db->last_query();
				// echo $query->num_rows();
				if($query->num_rows() > 0){
					foreach($query->result() as $row){
						$sub_id = $row->id;
						if($sub_id == $selected_arr[$i]){
							$ret[] = $selected_arr[$i];
						}
					}
				}else{
					return false;
				}
			}

			if(count($ret) > 0){
				$max = max($ret);
				if($max == $id){
					return true;
				}
			}
			// var_dump($ret);
		}

		public function updateTestResults($form_array,$id,$health_facility_test_result_table){
			$query = $this->db->update($health_facility_test_result_table,$form_array,array('id' => $id));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function updateTestRecordsFieldsRefundId($form_array,$refund_id,$health_facility_test_result_table){
			$query = $this->db->update($health_facility_test_result_table,$form_array,array('refund_request_code' => $refund_id));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function custom_echo($x, $length)
		{
		  if(strlen($x)<=$length)
		  {
		    return $x;
		  }
		  else
		  {
		    $y=substr($x,0,$length) . '...';
		    return $y;
		  }
		}

		//Get Tests
		public function get_first_set_of_tests_clinical_pathology(){
			$query = $this->db->query('SELECT * FROM tests WHERE section = "a" OR section = "b" OR section = "f" OR section = "g" OR section = "h" OR section = "i" OR section = "j" OR section = "k" OR section = "l"');
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function deletePatientTest($health_facility_patient_db_table,$user_name,$id){
			$query = $this->db->delete($health_facility_patient_db_table,array('id' => $id,'patient_username' => $user_name));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function getAllInitiationCodesByPatient($health_facility_test_result_table,$patient_username){
			$this->db->select("initiation_code");
			$this->db->from($health_facility_test_result_table);
			$this->db->where('patient_username',$patient_username);
			$this->db->where('paid',0);
			$this->db->where('registered',1);
			$this->db->order_by('id','DESC');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getTellerRecordsForInit($health_facility_id,$initiation_code){
			$query = $this->db->get_where('teller_records',array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		

		public function getInitiationCodeInfoForFacility($health_facility_test_result_table,$sub_dept_id,$initiation_code){
			$this->db->select("*");
			$this->db->from($health_facility_test_result_table);
			$this->db->where("initiation_code",$initiation_code);
			// $this->db->where('paid',0);
			// $this->db->where('registered',0);
			$this->db->where('sub_dept_id',$sub_dept_id);
			$this->db->order_by('id','DESC');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getInitiationCodeInfoForFacility1($health_facility_test_result_table,$sub_dept_id,$initiation_code){
			$this->db->select("*");
			$this->db->from($health_facility_test_result_table);
			$this->db->where("initiation_code",$initiation_code);
			// $this->db->where('paid',0);
			// $this->db->where('registered',0);
			$this->db->where('sub_dept_id',$sub_dept_id);
			$this->db->order_by('id','DESC');
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}


		public function getDateByInitiationCode($health_facility_test_result_table,$initiation_code,$user_name){
			$this->db->select("date");
			$this->db->from($health_facility_test_result_table);
			$this->db->where('initiation_code',$initiation_code);
			$this->db->where('patient_username',$user_name);
			$this->db->limit(1);
			$query = $this->db->get();
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$date = $row->date;
				}
				return $date;
			}else{
				return false;
			}
		}

		public function getDateByInitiationCode1($health_facility_test_result_table,$initiation_code){
			$this->db->select("date");
			$this->db->from($health_facility_test_result_table);
			$this->db->where('initiation_code',$initiation_code);
			// $this->db->where('patient_username',$user_name);
			$this->db->limit(1);
			$query = $this->db->get();
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$date = $row->date;
				}
				return $date;
			}else{
				return false;
			}
		}

		public function getFullNameByInitiationCode1($health_facility_test_result_table,$initiation_code){
			$this->db->select("*");
			$this->db->from($health_facility_test_result_table);
			$this->db->where('initiation_code',$initiation_code);
			// $this->db->where('patient_username',$user_name);
			$this->db->limit(1);
			$query = $this->db->get();
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$patient_name = $row->patient_name;
				}
				return $patient_name;
			}else{
				return false;
			}
		}

		public function getUserSlugByUserName($patient_username){
			$query = $this->db->get_where('users',array('user_name' => $patient_username));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$slug = $row->slug;
				}
				return $slug;
			}else{
				return false;
			}
		}

		public function getPatientUserNameByInitiationCode1($health_facility_test_result_table,$initiation_code){
			$this->db->select("*");
			$this->db->from($health_facility_test_result_table);
			$this->db->where('initiation_code',$initiation_code);
			// $this->db->where('patient_username',$user_name);
			$this->db->limit(1);
			$query = $this->db->get();
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$patient_username = $row->patient_username;
					if(!is_null($patient_username)){
						$patient_slug = $this->getUserSlugByUserName($patient_username);
						$patient_username = "<a href='" . site_url("onehealth/".$patient_slug) ."'>" .$patient_username ."</a>";
					}else{
						$patient_username = "New Patient";
					}
				}
				return $patient_username;
			}else{
				return false;
			}
		}


		public function getTestNumByInitiationCode($health_facility_test_result_table,$initiation_code,$user_name){
			$this->db->select("time");
			$this->db->from($health_facility_test_result_table);
			$this->db->where('initiation_code',$initiation_code);
			$this->db->where('patient_username',$user_name);
			$this->db->where('paid',0);
			$query = $this->db->get();
			return $query->num_rows();
		}

		public function getTestNumByInitiationCode2($health_facility_test_result_table,$initiation_code,$user_name){
			$this->db->select("time");
			$this->db->from($health_facility_test_result_table);
			$this->db->where('initiation_code',$initiation_code);
			$this->db->where('patient_username',$user_name);
			$this->db->where('paid',0);
			$query = $this->db->get();
			return $query->num_rows();
		}

		

		

		public function getTotalAmountPaidAlreadyForTest1($health_facility_test_result_table,$initiation_code){
			$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code),1);
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$amount_paid = $row->amount_paid;
				}
				return $amount_paid;
			}else{
				return false;
			}
		}



		public function checkIfInitiationCodeIsValid($health_facility_test_result_table,$code,$user_name){
			$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $code,'patient_username' => $user_name));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		

		public function getPatientsTotalCostByInitiationCode($health_facility_test_result_table,$code,$user_name){
			$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $code,'patient_username' => $user_name,'paid' => 0));
			if($query->num_rows() > 0){
				$total_cost = 0;
				foreach($query->result() as $row){
					$cost = $row->price;
					$total_cost += $cost;
				}
				return $total_cost;
			}else{
				return false;
			}
		}

		public function getPatientsTotalCostByInitiationCode1($health_facility_test_result_table,$code,$user_name){
			$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $code,'patient_username' => $user_name,'paid' => 0));
			if($query->num_rows() > 0){
				$total_cost = 0;
				foreach($query->result() as $row){
					$cost = $row->price;
					$total_cost += $cost;
				}
				return $total_cost;
			}else{
				return false;
			}
		}

		public function deletePatientBioDataHealthFacility($patient_bio_data_table,$user_id){
			$query = $this->db->delete($patient_bio_data_table,array('user_id' => $user_id));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function getPaidTestsIdsByInitiationCode($health_facility_test_result_table,$initiation_code,$user_name){
			$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code,'patient_username' => $user_name));
			if($query->num_rows() > 0){
				$ret_arr = array();
				foreach($query->result() as $row){
					$id = $row->id;
					$ret_arr[] .= $id;
				}
				return $ret_arr;
			}else{
				return false;
			}
		}


		public function getPatientEmail($health_facility_patient_db_table,$user_name,$user_id){
			$query = $this->db->get_where($health_facility_patient_db_table,array('user_name' => $user_name,'user_id' => $user_id));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$email = $row->email;
				}
				return $email;
			}else{
				return false;
			}
		}

		public function getPatientFirstName($health_facility_patient_db_table,$user_name,$user_id){
			$query = $this->db->get_where($health_facility_patient_db_table,array('user_name' => $user_name,'user_id' => $user_id));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$firstname = $row->firstname;
				}
				return $firstname;
			}else{
				return false;
			}
		}

		public function getPatientLastName($health_facility_patient_db_table,$user_name,$user_id){
			$query = $this->db->get_where($health_facility_patient_db_table,array('user_name' => $user_name,'user_id' => $user_id));
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$lastname = $row->lastname;
				}
				return $lastname;
			}else{
				return false;
			}
		}

		public function getPatientAccount($user_id){
			$query = $this->db->get_where('users',array('id' => $user_id,'is_patient' => 1));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function getFacilityTotalAmount($health_facility_id){
			$query = $this->db->get_where('health_facility',array('id' => $health_facility_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$total_income = $row->total_income;
				}
				return $total_income;
			}
		}

		public function getHealthFacilityTableNameById($health_facility_id){
			$query = $this->db->get_where('health_facility',array('id' => $health_facility_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$table_name = $row->table_name;
				}
				return $table_name;
			}
		}

		public function creditFacilityAccount($form_array,$message){
			$query = $this->db->insert("payment_logs",$form_array);
			if($query){
				$health_facility_id = $form_array['health_facility_id'];
				$health_facility_total_income = $this->getFacilityTotalAmount($health_facility_id);
				$amount = $form_array['amount'];
				$new_amount = $amount + $health_facility_total_income;
				$query = $this->db->update('health_facility',array('total_income' => $new_amount),array('id' => $health_facility_id));
				if($query){
					$sender = "OneHealth";
					$receiver = $this->getAdminsUsername($health_facility_id);
					$title = "Credit Alert";

					$notif_array = array(
						'sender' => $sender,
						'receiver' => $receiver,
						'title' => $title,
						'message' => $message,
						'date_sent' => $form_array['date'],
						'time_sent' => $form_array['time']
					);
					if($this->sendMessage($notif_array)){
						return true;
					}
				}
			}
		}

		public function getTotalPatientTestAmount($health_facility_test_result_table,$code,$user_name){
			$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $code,'patient_username' => $user_name,'paid' => 0));
			if($query->num_rows() > 0){
				$total_amount = 0;
				foreach($query->result() as $row){
					$amount = $row->price;
					$total_amount += $amount;
				}
				return $total_amount;
			}else{
				return 0;
			}
		}

		public function getTotalPatientTestAmount1($health_facility_test_result_table,$code,$user_name,$sub_dept_id){
			$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $code,'patient_username' => $user_name,'sub_dept_id' => $sub_dept_id));
			if($query->num_rows() > 0){
				$total_amount = 0;
				foreach($query->result() as $row){
					$amount = $row->price;
					$total_amount += $amount;
				}
				return $total_amount;
			}else{
				return 0;
			}
		}

		public function getTimeByInitiationCode($health_facility_test_result_table,$initiation_code,$user_name){
			$this->db->select("time");
			$this->db->from($health_facility_test_result_table);
			$this->db->where('initiation_code',$initiation_code);
			$this->db->where('patient_username',$user_name);
			$this->db->limit(1);
			$query = $this->db->get();
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$time = $row->time;
				}
				return $time;
			}else{
				return false;
			}
		}

		public function getTimeByInitiationCode1($health_facility_test_result_table,$initiation_code){
			$this->db->select("time");
			$this->db->from($health_facility_test_result_table);
			$this->db->where('initiation_code',$initiation_code);
			// $this->db->where('patient_username',$user_name);
			$this->db->limit(1);
			$query = $this->db->get();
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$time = $row->time;
				}
				return $time;
			}else{
				return false;
			}
		}

		public function get_set_of_tests_clinical_pathology_by_initiation_code2($health_facility_test_result_table,$user_name,$health_facility_name){
			if($this->db->table_exists($health_facility_test_result_table)){
				
				$this->db->select("initiation_code,date,time");
				$this->db->from($health_facility_test_result_table);
				$this->db->where('patient_locked',1);
				$this->db->where('patient_username',$user_name);
				$this->db->where('facility_name',$health_facility_name);
				$query = $this->db->get();
				if($query->num_rows() > 0){
					return $query->result();
				}else{
					return false;
				}
			}else{
				$query_str = 'CREATE TABLE ' .$health_facility_test_result_table.' (
					id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					main_test_id INT NOT NULL,
					facility_name VARCHAR(100) NOT NULL,
					referring_facility_id INT NULL,
					record_id INT NULL,
					ward_id INT NULL,
					referral_id INT NULL,
					initiation_code VARCHAR(100) NOT NULL,
					lab_id TEXT NULL,
					sub_dept_id INT NOT NULL,
					test_id VARCHAR(1000) NOT NULL,
					receptionist INT NULL,
					teller INT NULL,
					receipt_file TEXT NULL,
					patient_name VARCHAR(200) NOT NULL,
					test_name TEXT NOT NULL,
					patient_username VARCHAR(100) NULL,
					patient_email VARCHAR(50) NULL,
					price BIGINT(20) NOT NULL,
					amount_paid BIGINT(20) NOT NULL,
					ta_time BIGINT(20) NOT NULL,
					date VARCHAR(100) NOT NULL,
					time VARCHAR(100) NOT NULL,
					invalid INT NOT NULL DEFAULT 0,
					paid INT NOT NULL DEFAULT 0,
					date_paid VARCHAR(50) NOT NULL,
					time_paid VARCHAR(50) NOT NULL,
					refund_requested INT NOT NULL DEFAULT 0,
					refund_request_code TEXT NULL,
					payment_initiated INT DEFAULT 0 NOT NULL,
					patient_locked INT DEFAULT 0 NOT NULL,
					registered INT DEFAULT 0

				)';
				if($this->db->query($query_str)){
					$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code));
					if($query->num_rows() > 0){
						return $query->result();
					}else{
						return false;
					}
				}else{
					return false;
				}
			}
		}

		public function get_set_of_tests_clinical_pathology_by_initiation_code1($initiation_code,$health_facility_test_result_table,$user_name,$health_facility_name){
			if($this->db->table_exists($health_facility_test_result_table)){
				$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code,'patient_username' => $user_name,'facility_name' => $health_facility_name,'registered' => 1,'paid' => 0));
				if($query->num_rows() > 0){
					return $query->result();
				}else{
					return false;
				}
			}else{
				$query_str = 'CREATE TABLE ' .$health_facility_test_result_table.' (
					id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					main_test_id INT NOT NULL,
					facility_name VARCHAR(100) NOT NULL,
					referring_facility_id INT NULL,
					record_id INT NULL,
					ward_id INT NULL,
					referral_id INT NULL,
					initiation_code VARCHAR(100) NOT NULL,
					lab_id TEXT NULL,
					sub_dept_id INT NOT NULL,
					test_id VARCHAR(1000) NOT NULL,
					receptionist INT NULL,
					teller INT NULL,
					receipt_file TEXT NULL,
					patient_name VARCHAR(200) NOT NULL,
					test_name TEXT NOT NULL,
					patient_username VARCHAR(100) NULL,
					patient_email VARCHAR(50) NULL,
					price BIGINT(20) NOT NULL,
					amount_paid BIGINT(20) NOT NULL,
					ta_time BIGINT(20) NOT NULL,
					date VARCHAR(100) NOT NULL,
					time VARCHAR(100) NOT NULL,
					invalid INT NOT NULL DEFAULT 0,
					paid INT NOT NULL DEFAULT 0,
					date_paid VARCHAR(50) NOT NULL,
					time_paid VARCHAR(50) NOT NULL,
					refund_requested INT NOT NULL DEFAULT 0,
					refund_request_code TEXT NULL,
					payment_initiated INT DEFAULT 0 NOT NULL,
					patient_locked INT DEFAULT 0 NOT NULL,
					registered INT DEFAULT 0

				)';
				if($this->db->query($query_str)){
					$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code));
					if($query->num_rows() > 0){
						return $query->result();
					}else{
						return false;
					}
				}else{
					return false;
				}
			}
		}

		

		public function getPathologistsCommentByInitiationCode($health_facility_patient_db_table,$lab_id){
			$query = $this->db->get_where($health_facility_patient_db_table,array('lab_id' => $lab_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$pathologists_comment = $row->pathologists_comment;
				}
				return $pathologists_comment;
			}
		}


		public function get_set_of_all_tests_by_initiation_code1($initiation_code,$health_facility_test_result_table){
			if($this->db->table_exists($health_facility_test_result_table)){
				$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code,'patient_locked' => 0,'paid' => 0));
				if($query->num_rows() > 0){
					return $query->result();
				}else{
					return false;
				}
			}
		}

		public function getHealthFacilityIdBySlug($facility_slug){
			$query = $this->db->get_where('health_facility',array('slug' => $facility_slug));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$id = $row->id;
				}
				return $id;
			}
		}

		public function getHealthFacilityNameBySlug($facility_slug){
			$query = $this->db->get_where('health_facility',array('slug' => $facility_slug));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$name = $row->name;
				}
				return $name;
			}
		}

		public function getHealthFacilityNameById($id){
			$query = $this->db->get_where('health_facility',array('id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$name = $row->name;
				}
				return $name;
			}
		}

		public function get_set_of_all_tests_by_initiation_code_paid($initiation_code,$health_facility_test_result_table){
			if($this->db->table_exists($health_facility_test_result_table)){
				$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code,'patient_locked' => 0,'paid' => 0));
				if($query->num_rows() > 0){
					return $query->result();
				}else{
					return false;
				}
			}
		}	

		public function get_set_of_tests_clinical_pathology_by_initiation_code($initiation_code,$health_facility_test_result_table,$sub_dept_id){
			if($this->db->table_exists($health_facility_test_result_table)){
				$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code,'patient_locked' => 0,'sub_dept_id' => $sub_dept_id));
				if($query->num_rows() > 0){
					return $query->result();
				}else{
					return false;
				}
			}else{
				$query_str = 'CREATE TABLE ' .$health_facility_test_result_table.' (
					id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					main_test_id INT NOT NULL,
					facility_name VARCHAR(100) NOT NULL,
					referring_facility_id INT NULL,
					record_id INT NULL,
					ward_id INT NULL,
					referral_id INT NULL,
					initiation_code VARCHAR(100) NOT NULL,
					lab_id TEXT NULL,
					sub_dept_id INT NOT NULL,
					test_id VARCHAR(1000) NOT NULL,
					receptionist INT NULL,
					teller INT NULL,
					receipt_file TEXT NULL,
					patient_name VARCHAR(200) NOT NULL,
					test_name TEXT NOT NULL,
					patient_username VARCHAR(100) NULL,
					patient_email VARCHAR(50) NULL,
					price BIGINT(20) NOT NULL,
					amount_paid BIGINT(20) NOT NULL,
					ta_time BIGINT(20) NOT NULL,
					date VARCHAR(100) NOT NULL,
					time VARCHAR(100) NOT NULL,
					invalid INT NOT NULL DEFAULT 0,
					paid INT NOT NULL DEFAULT 0,
					date_paid VARCHAR(50) NOT NULL,
					time_paid VARCHAR(50) NOT NULL,
					refund_requested INT NOT NULL DEFAULT 0,
					refund_request_code TEXT NULL,
					payment_initiated INT DEFAULT 0 NOT NULL,
					patient_locked INT DEFAULT 0 NOT NULL,
					registered INT DEFAULT 0

				)';
				if($this->db->query($query_str)){
					$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code));
					if($query->num_rows() > 0){
						return $query->result();
					}else{
						return false;
					}
				}else{
					return false;
				}
			}
		}

		public function get_set_of_tests_clinical_pathology_by_initiation_code_teller($initiation_code,$health_facility_test_result_table,$sub_dept_id){
			if($this->db->table_exists($health_facility_test_result_table)){
				$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code,'paid' => 0,'sub_dept_id' => $sub_dept_id));
				if($query->num_rows() > 0){
					return $query->result();
				}else{
					return false;
				}
			}else{
				$query_str = 'CREATE TABLE ' .$health_facility_test_result_table.' (
					id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					main_test_id INT NOT NULL,
					facility_name VARCHAR(100) NOT NULL,
					referring_facility_id INT NULL,
					record_id INT NULL,
					ward_id INT NULL,
					referral_id INT NULL,
					initiation_code VARCHAR(100) NOT NULL,
					lab_id TEXT NULL,
					sub_dept_id INT NOT NULL,
					test_id VARCHAR(1000) NOT NULL,
					receptionist INT NULL,
					teller INT NULL,
					receipt_file TEXT NULL,
					patient_name VARCHAR(200) NOT NULL,
					test_name TEXT NOT NULL,
					patient_username VARCHAR(100) NULL,
					patient_email VARCHAR(50) NULL,
					price BIGINT(20) NOT NULL,
					amount_paid BIGINT(20) NOT NULL,
					ta_time BIGINT(20) NOT NULL,
					date VARCHAR(100) NOT NULL,
					time VARCHAR(100) NOT NULL,
					invalid INT NOT NULL DEFAULT 0,
					paid INT NOT NULL DEFAULT 0,
					date_paid VARCHAR(50) NOT NULL,
					time_paid VARCHAR(50) NOT NULL,
					refund_requested INT NOT NULL DEFAULT 0,
					refund_request_code TEXT NULL,
					payment_initiated INT DEFAULT 0 NOT NULL,
					patient_locked INT DEFAULT 0 NOT NULL,
					registered INT DEFAULT 0

				)';
				if($this->db->query($query_str)){
					$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code,'paid' => 0));
					if($query->num_rows() > 0){
						return $query->result();
					}else{
						return false;
					}
				}else{
					return false;
				}
			}
		}

		public function updateTestRecordsFields($form_array,$id,$health_facility_test_result_table){
			if($this->db->table_exists($health_facility_test_result_table)){
				$query = $this->db->update($health_facility_test_result_table,$form_array,array('id' => $id));
				if($query){
					return true;
				}else{
					return false;
				}
			}else{
				$query_str = 'CREATE TABLE ' .$health_facility_test_result_table.' (
					id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					main_test_id INT NOT NULL,
					facility_name VARCHAR(100) NOT NULL,
					referring_facility_id INT NULL,
					record_id INT NULL,
					ward_id INT NULL,
					referral_id INT NULL,
					initiation_code VARCHAR(100) NOT NULL,
					lab_id TEXT NULL,
					sub_dept_id INT NOT NULL,
					test_id VARCHAR(1000) NOT NULL,
					receptionist INT NULL,
					teller INT NULL,
					receipt_file TEXT NULL,
					patient_name VARCHAR(200) NOT NULL,
					test_name TEXT NOT NULL,
					patient_username VARCHAR(100) NULL,
					patient_email VARCHAR(50) NULL,
					price BIGINT(20) NOT NULL,
					amount_paid BIGINT(20) NOT NULL,
					ta_time BIGINT(20) NOT NULL,
					date VARCHAR(100) NOT NULL,
					time VARCHAR(100) NOT NULL,
					invalid INT NOT NULL DEFAULT 0,
					paid INT NOT NULL DEFAULT 0,
					date_paid VARCHAR(50) NOT NULL,
					time_paid VARCHAR(50) NOT NULL,
					refund_requested INT NOT NULL DEFAULT 0,
					refund_request_code TEXT NULL,
					payment_initiated INT DEFAULT 0 NOT NULL,
					patient_locked INT DEFAULT 0 NOT NULL,
					registered INT DEFAULT 0

				)';
				if($this->db->query($query_str)){
					$query = $this->db->update($health_facility_test_result_table,$form_array,array('id' => $id));
					if($query){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}	
		}

		public function getPatientEmail1($health_facility_test_result_table,$initiation_code,$lab_id){
			$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code, 'lab_id' => $lab_id),1);
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$patient_email = $row->patient_email;
				}
				return $patient_email;
			}else{
				return "";
			}
		}

		public function updateTestRecordsFieldsDemo($form_array,$id,$health_facility_test_result_table){
			if($this->db->table_exists($health_facility_test_result_table)){
				$query = $this->db->update($health_facility_test_result_table,$form_array,array('lab_id' => $id));
				if($query){
					return true;
				}else{
					return false;
				}
			}else{
				$query_str = 'CREATE TABLE ' .$health_facility_test_result_table.' (
					id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					main_test_id INT NOT NULL,
					facility_name VARCHAR(100) NOT NULL,
					referring_facility_id INT NULL,
					record_id INT NULL,
					ward_id INT NULL,
					referral_id INT NULL,
					initiation_code VARCHAR(100) NOT NULL,
					lab_id TEXT NULL,
					sub_dept_id INT NOT NULL,
					test_id VARCHAR(1000) NOT NULL,
					receptionist INT NULL,
					teller INT NULL,
					receipt_file TEXT NULL,
					patient_name VARCHAR(200) NOT NULL,
					test_name TEXT NOT NULL,
					patient_username VARCHAR(100) NULL,
					patient_email VARCHAR(50) NULL,
					price BIGINT(20) NOT NULL,
					amount_paid BIGINT(20) NOT NULL,
					ta_time BIGINT(20) NOT NULL,
					date VARCHAR(100) NOT NULL,
					time VARCHAR(100) NOT NULL,
					invalid INT NOT NULL DEFAULT 0,
					paid INT NOT NULL DEFAULT 0,
					date_paid VARCHAR(50) NOT NULL,
					time_paid VARCHAR(50) NOT NULL,
					refund_requested INT NOT NULL DEFAULT 0,
					refund_request_code TEXT NULL,
					payment_initiated INT DEFAULT 0 NOT NULL,
					patient_locked INT DEFAULT 0 NOT NULL,
					registered INT DEFAULT 0

				)';
				if($this->db->query($query_str)){
					$query = $this->db->update($health_facility_test_result_table,$form_array,array('lab_id' => $id));
					if($query){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}	
		}

		public function testInsert($form_array){
			$query = $this->db->insert('test',$form_array);
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function updateFacilityTests($form_array,$id,$test_table_name){
			if($this->db->table_exists($test_table_name)){
				$query = $this->db->update($test_table_name,$form_array,array('id' => $id));
				if($query){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}



		public function checkIfTestNativeIdExists($id,$health_facility_test_table_name){
			$query = $this->db->get_where($health_facility_test_table_name,array('id' => $id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;			
			}
		}

		public function deleteTest($id,$health_facility_test_table_name){
			$query = $this->db->delete($health_facility_test_table_name,array('id' => $id));
			if($query){
				if($this->getNoOfSubTests($health_facility_test_table_name,$id)){
					$query = $this->db->delete($health_facility_test_table_name,array('under' => $id));
				}
				return true;
			}else{
				return false;
			}
		}

		public function addClinicalPathologyTests($form_array,$test_table_name){
			if($this->db->table_exists($test_table_name)){
				$query = $this->db->insert($test_table_name,$form_array);
				if($query){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		public function updatePatientTestFields($form_array,$test_table_name,$lab_id){			
			$query = $this->db->update($test_table_name,$form_array,array('lab_id' => $lab_id));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function getLabId($health_facility_main_test_result_table,$form_array){
			$query = $this->db->get_where($health_facility_main_test_result_table,$form_array);
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$lab_id = $row->lab_id;
				}
				return $lab_id;
			}
		}

		public function changeMainTable($table_name){
			$query_str = "ALTER TABLE ".$table_name." ADD `signature` VARCHAR(1000) NULL AFTER `user_id`;";
			if($this->db->query($query_str)){
				return true;
			}
		}

		public function getSubDeptIdByLabId1($health_facility_patient_table_name,$lab_id){
			$query = $this->db->get_where($health_facility_patient_table_name,array('lab_id' => $lab_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$sub_dept_id = $row->sub_dept_id;
				}
				return $sub_dept_id;
			}
		}

		public function getSupervisorIdByLabId($health_facility_patient_table_name,$lab_id){
			$query = $this->db->get_where($health_facility_patient_table_name,array('lab_id' => $lab_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$supervisor = $row->supervisor;
				}
				return $supervisor;
			}
		}

		public function getPathologistIdByLabId($health_facility_patient_table_name,$lab_id){
			$query = $this->db->get_where($health_facility_patient_table_name,array('lab_id' => $lab_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$pathologist_id = $row->pathologist_id;
				}
				return $pathologist_id;
			}
		}

		public function getDeptIdBySubDeptId($sub_dept_id){
			$query = $this->db->get_where('sub_dept',array('id' => $sub_dept_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$dept_id = $row->dept_id;
				}
				return $dept_id;
			}else{
				return false;
			}
		}

		public function getDeptSlugById($dept_id){
			$query = $this->db->get_where('dept',array('id' => $dept_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$slug = $row->slug;
				}
				return $slug;
			}else{
				return false;
			}
		}

		public function getReadyResults($health_facility_main_test_result_table,$lab_id){
			$this->db->select('*');
			$this->db->from($health_facility_main_test_result_table);
			$this->db->where('lab_id',$lab_id);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getReadyResults1($health_facility_main_test_result_table,$lab_id){
			$this->db->select('*');
			$this->db->from($health_facility_main_test_result_table);
			$this->db->where('lab_id',$lab_id);
			$this->db->where('main_test',1);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getTestResultSubTests($health_facility_main_test_result_table,$lab_id,$id){
			$query = $this->db->get_where($health_facility_main_test_result_table,array('lab_id' => $lab_id,'super_test_id' => $id));
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getIfTestIsMainTest($health_facility_test_table_name,$main_test_id){
			if($this->getIfTestExists($health_facility_test_table_name,$main_test_id)){
				$query = $this->db->get_where($health_facility_test_table_name,array('id' => $main_test_id,'under' => 0));
				if($query->num_rows() == 1){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		public function checkIfPatientHasPaidFinish($health_facility_test_table_name,$health_facility_test_result_table,$initiation_code,$lab_id,$main_test_id){
			
			$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code,'lab_id' => $lab_id));
			if($query->num_rows() > 0){
				$total_cost = 0;
				foreach($query->result() as $row){
					$price = $row->price;
					$amount_paid = $row->amount_paid;
					$total_cost += $price;
				}
				if($total_cost == $amount_paid){
					return true;
				}else{
					return false;
				}
			}
			
		}

		public function getIfTestExists($health_facility_test_table_name,$main_test_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('id' => $main_test_id));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfThisTestHasSubTestsHere($health_facility_main_test_result_table,$id){
			$query = $this->db->get_where($health_facility_main_test_result_table,array('super_test_id' => $id));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		

		public function getMainTestResultInfo($health_facility_main_test_result_table,$selected_id,$lab_id){
			$query = $this->db->get_where($health_facility_main_test_result_table,array('id' => $selected_id,'lab_id' => $lab_id));
			if($query->num_rows() == 1){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getTestsSubTestsMainResultTableAsString($health_facility_main_test_result_table,$id){
			$query = $this->db->get_where($health_facility_main_test_result_table,array('super_test_id' => $id));
			if($query->num_rows() > 0){
				$ret = array();
				foreach($query->result() as $row){
					$id = $row->id;
					$ret[] = $id;
				}
				return implode(",", $ret);
			}else{
				return "";
			}
		}

		public function getTestCostById($health_facility_test_table_name,$main_test_id){
			$query = $this->db->get_where($health_facility_test_table_name,array('id' => $main_test_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$cost = $row->cost;
				}
				return $cost;
			}else{
				return false;
			}
		}

		
		public function alterTable($test_result_table_name){
			$query = $this->db->query("ALTER TABLE ".$test_result_table_name." ADD `amount_paid` BIGINT(20) NOT NULL AFTER `price`");
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function tableExists($table_name){
			if($this->db->table_exists($table_name)){
				return true;
			}else{
				return false;
			}
		}

		public function getAllTestResults1($table_name){
			$query = $this->db->get($table_name);
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function getTestDateRequested($health_facility_test_result_table,$initiation_code,$main_test_id,$sub_dept_id){
			$query = $this->db->get_where($health_facility_test_result_table,array('main_test_id' => $main_test_id,'initiation_code' => $initiation_code));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					
					$date_requested = $row->date . ' ' . $row->time;
				}
				return $date_requested;
			}else{
				return "";
			}
		}

		public function createTableHeaderString($id,$string){
			return substr(strtolower(preg_replace('/[^A-Za-z0-9\-s+]/', '', $id.'_'.$string)),0,10);
		}

		public function createTestTableHeaderString($id,$string){
			return substr(strtolower(preg_replace('/[^A-Za-z0-9\-s+]/', '', 't'.$id.'_'.$string)),0,20);
		}

		public function createTestResultTableHeaderString($id,$string){
			return substr(strtolower(preg_replace('/[^A-Za-z0-9\-s+]/', '', 'tr'.$id.'_'.$string)),0,20);
		}

		public function createTestPatientTableHeaderString($id,$string){
			return substr(strtolower(preg_replace('/[^A-Za-z0-9\-s+]/', '', 'tpb'.$id.'_'.$string)),0,20);
		}

		public function createTestResultMainTableHeaderString($id,$string){
			return substr(strtolower(preg_replace('/[^A-Za-z0-9\-s+]/', '', 'tpbm'.$id.'_'.$string)),0,20);
		}

		public function createpatientBioDataTableString($id,$string){
			return substr(strtolower(preg_replace('/[^A-Za-z0-9\-s+]/', '', 'pbd'.$id.'_'.$string)),0,20);
		}

		public function createpatientClinicActivitiesTableString($id,$string){
			return substr(strtolower(preg_replace('/[^A-Za-z0-9\-s+]/', '', 'ca'.$id.'_'.$string)),0,20);
		}

		public function createpatientTestsSelectedTableString($id,$string){
			return substr(strtolower(preg_replace('/[^A-Za-z0-9\-s+]/', '', 'pts'.$id.'_'.$string)),0,20);
		}

		public function createAssignedFacilitiesString($affiliated_facilities,$table_name){
			return $affiliated_facilities .',' . $table_name;;
		}

		public function getPatientUserNameByHospitalNumber($patient_bio_data_table,$hospital_number){
			$query = $this->db->get_where($patient_bio_data_table,array('hospital_number' => $hospital_number));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$user_name = $row->user_name;
				}
				return $user_name;
			}else{
				return false;
			}
		}

		public function getNewAffiliatedFacilitiesString($table_name,$affiliated_facilities_arr){
			$index = array_search($table_name,$affiliated_facilities_arr);
			$affiliated_facilities = "";
			unset($affiliated_facilities_arr[$index]);
	      	if(!empty($affiliated_facilities_arr)){
	      		$affiliated_facilities = implode(",", $affiliated_facilities_arr);
	      	}
	     	return $affiliated_facilities;
	    } 

	    public function checkIfBioDataHasBeenEnteredByPatientFacilityTable($patient_bio_data_table,$user_name,$user_id){
	    	if($this->db->table_exists($patient_bio_data_table)){
	    		$query = $this->db->get_where($patient_bio_data_table,array('user_name' => $user_name,'user_id' => $user_id));
	    		if($query->num_rows() == 1){
	    			return true;
	    		}else{
	    			return false;
	    		}
	    	}else{
	    		return false;
	    	}
	    }

	    public function checkIfTestsHaveBeenMarkedAsPaid($health_facility_test_result_table,$initiation_code,$sub_dept_id,$user_name){
	    	$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code,'sub_dept_id' => $sub_dept_id,'patient_username' => $user_name,'paid' => 1));
	    	if($query->num_rows() > 0){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function checkIfInitiationCodeHasBeenPaidFor($health_facility_test_result_table,$initiation_code,$sub_dept_id){
	    	$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code,'sub_dept_id' => $sub_dept_id,'paid' => 1));
    		if($query->num_rows() > 0){
    			return true;
    		}else{
    			return false;
    		}	    	
	    }

	    public function addSubDeptsToDept($form_array,$dept_id){
	    	if(is_array($form_array)){
	    		for($i = 0; $i < count($form_array); $i++){
	    			$sub_dept_name = $form_array[$i];
	    			$sub_dept_slug = strtolower(url_title($sub_dept_name));
	    			$form_array1 = array(
	    				'name' => $sub_dept_name,
	    				'slug' => $sub_dept_slug,
	    				'dept_id' => $dept_id
	    			);
	    			$query = $this->db->insert('sub_dept',$form_array1);
	    		}
	    	}
	    }

	   

	    public function checkIfCommentIsEntered($health_facility_patient_db_table,$health_facility_test_result_table,$initiation_code,$sub_dept_id){
	    	if($this->checkIfInitiationCodeHasBeenPaidFor($health_facility_test_result_table,$initiation_code,$sub_dept_id)){
		    	$query = $this->db->get_where($health_facility_patient_db_table,array('pathologists_comment' => '','initiation_code' => $initiation_code,'sub_dept_id' => $sub_dept_id));
		    	if($query->num_rows() == 1){
		    		return false;
		    	}else{
		    		return true;
		    	}
		    }else{
		    	return false;
		    }
	    }

	    
	    public function addTellerRecord($form_array){
	    	$query = $this->db->insert('teller_records',$form_array);
	    	return $query;
	    }

	    public function checkIfBioDataHasBeenEnteredByPatient($user_name,$user_id){	    	
    		$query = $this->db->get_where('patients',array('user_name' => $user_name,'user_id' => $user_id,'data_entered' => 1));
    		if($query->num_rows() == 1){
    			return true;
    		}else{
    			return false;
    		}
	    }



	    public function getPatientNameByLabId($health_facility_patient_db_table,$lab_id){
	    	$query = $this->db->get_where($health_facility_patient_db_table,array('lab_id' => $lab_id));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$firstname = $row->firstname;
	    			$lastname = $row->surname;
	    			$patient_name = $firstname . ' ' .$lastname;
	    		}
	    		return $patient_name;
	    	}else{
	    		return "";
	    	}
	    }	

	    public function getPatientInfoByHospitalNumber($patient_bio_data_table,$hospital_number){
	    	$query = $this->db->get_where($patient_bio_data_table,array('hospital_number' => $hospital_number ));
	    	if($query->num_rows() == 1){
	    		return $query->result();
	    	}else{
	    		return false;
	    	}
	    }

	    public function getUserNameByHospitalNumber($patient_bio_data_table,$hospital_number){
	    	$query = $this->db->get_where($patient_bio_data_table,array('hospital_number' => $hospital_number ));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$user_name = $row->user_name;
	    		}
	    		return $user_name;
	    	}else{
	    		return false;
	    	}
	    }

	    public function createClinicActivityRecord($clinic_activities_table_name,$form_array){
	    	$query = $this->db->insert($clinic_activities_table_name,$form_array);
	    	if($query){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function getPatientUserNameInTestResultTemp($health_facility_test_result_table,$initiation_code){
	    	$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code),1);
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$patient_username = $row->patient_username;
	    		}
	    		return $patient_username;
	    	}else{
	    		return "";
	    	}
	    }

	    public function getPatientEmailPatientTable($user_id){
	    	$query = $this->db->get_where('patients',array('user_id' => $user_id));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$email = $row->email;
	    		}
	    		return $email;
	    	}else{
	    		return "";
	    	}
	    }

	    

	    public function getSubDeptNameByInitiationCode($health_facility_test_result_table,$initiation_code){
	    	$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code),1);
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$sub_dept_id = $row->sub_dept_id;
	    		}
	    		return $this->getSubDeptNameById($sub_dept_id);
	    	}else{
	    		return "";
	    	}
	    }

	    public function getBalanceOfTestPaid($health_facility_test_result_table,$initiation_code){
	    	$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code));
	    	if($query->num_rows() > 0){
	    		$total_cost = 0;
	    		foreach($query->result() as $row){
	    			$amount_paid = $row->amount_paid;
	    			$total_cost += $row->price;
	    		}
	    		$balance = $total_cost - $amount_paid;
	    		return $balance;
	    	}else{
	    		return false;
	    	}
	    }


	    public function getDatePaidOfTestPaid($health_facility_test_result_table,$initiation_code){
	    	$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code),1);
	    	if($query->num_rows() > 0){
	    		foreach($query->result() as $row){
	    			$date_paid = $row->date_paid;
	    			
	    		}
	    		return $date_paid; 
	    	}else{
	    		return false;
	    	}
	    }

	    public function getTimePaidOfTestPaid($health_facility_test_result_table,$initiation_code){
	    	$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code),1);
	    	if($query->num_rows() > 0){
	    		foreach($query->result() as $row){
	    			$time_paid = $row->time_paid;
	    		}
	    		return $time_paid; 
	    	}else{
	    		return false;
	    	}
	    }

	    public function getHospitalNumberByTestInitiationCode($health_facility_test_result_table,$patient_bio_data_table,$initiation_code){
	    	$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code),1);
	    	if($query->num_rows() > 0){
	    		foreach($query->result() as $row){
	    			$patient_username = $row->patient_username;
	    			// echo $patient_username;
	    			$hospital_number = $this->getHospitalNumberByPatientUsername($patient_bio_data_table,$patient_username);
	    			// echo $hospital_number;
	    		}
	    		return $hospital_number; 
	    	}else{
	    		return false;
	    	}
	    }

	    public function getHospitalNumberByPatientUsername($patient_bio_data_table,$patient_username){
	    	$query = $this->db->get_where($patient_bio_data_table,array('user_name' => $patient_username));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$hospital_number = $row->hospital_number;
	    		}
	    		return $hospital_number;
	    	}else{
	    		return false;
	    	}
	    }

	    public function getPatientFullNamePatientTable($user_id){
	    	$query = $this->db->get_where('patients',array('user_id' => $user_id));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$firstname = $row->firstname;
	    			$lastname = $row->lastname;
	    		}
	    		return $firstname . ' ' .$lastname;
	    	}else{
	    		return "";
	    	}
	    }

	    public function checkIfInitiationCodeIsValid1($health_facility_test_result_table,$code){
	    	$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $code),1);
	    	if($query->num_rows() == 1){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    

	    public function checkIfAllTestsInInitiationCodeHaveBeenPaidFor($health_facility_test_result_table,$initiation_code){
	    	$tests_count = $this->getNoOfTestsUnderInitiationCode($health_facility_test_result_table,$initiation_code);
	    	$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code,'paid' => 1));
	    	if($query->num_rows() == $tests_count){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function checkIfTestHasBeenPaidFor($health_facility_test_result_table,$code){
	    	$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $code,'paid' => 0),1);
	    	if($query->num_rows() == 1){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function makeSureUserIsAdminOrSubAdminForAccess($health_facility_table_name,$dept_slug,$sub_dept_slug,$user_name){
	    	if($this->checkIfUserIsATopAdmin($health_facility_table_name,$user_name)){
	    		return true;
	    	}else{
	    		$query = $this->db->get_where($health_facility_table_name,array('dept' => $dept_slug,'sub_dept' => $sub_dept_slug,'position' => 'sub_admin','user_name' => $user_name));
	    		if($query->num_rows() == 1){
	    			return true;
	    		}else{
	    			return false;
	    		}
	    	}
	    }

	    public function checkIfUserIsSubAdmin($health_facility_table_name,$user_name){
	    	$query = $this->db->get_where($health_facility_table_name,array('user_name' => $user_name,'position' => 'sub_admin'));
	    	if($query->num_rows() == 1){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function makeSureUserIsAdminOrSubAdminOrPersonnelIsRightForAccess($health_facility_table_name,$dept_slug,$sub_dept_slug,$personnel_slug,$user_name){
	    	if($this->checkIfUserIsATopAdmin($health_facility_table_name,$user_name)){
	    		return true;
	    	}else if($this->checkIfUserIsSubAdmin($health_facility_table_name,$user_name)){
	    		$query = $this->db->get_where($health_facility_table_name,array('dept' => $dept_slug,'sub_dept' => $sub_dept_slug,'position' => 'sub_admin','user_name' => $user_name));
	    		if($query->num_rows() == 1){
	    			return true;
	    		}else{
	    			return false;
	    		}
	    	}else if($this->checkIfUserIsAPersonnel($health_facility_table_name,$user_name)){
	    		$query = $this->db->get_where($health_facility_table_name,array('dept' => $dept_slug,'sub_dept' => $sub_dept_slug,'personnel' => $personnel_slug,'position' => 'personnel','user_name' => $user_name));
	    		if($query->num_rows() == 1){
	    			return true;
	    		}else{
	    			return false;
	    		}
	    	}else{
	    		return false;
	    	}
	    }

	    public function getUserFullNameByUserName($patient_username){
	    	$query = $this->db->get_where('users',array('user_name' => $patient_username));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$full_name = $row->full_name;
	    		}
	    		return $full_name;
	    	}
	    }

	    public function createFacilityPatientTestsSelectedTable($table_name){
	    	if($this->db->table_exists($table_name)){
	    		return true;
	    	}else{
		    	$query_str = 'CREATE TABLE ' .$table_name.' (
					id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					facility_id INT NOT NULL,					
					initiation_code VARCHAR(60) NOT NULL,
					facility_slug VARCHAR(100) NOT NULL,
					record_id INT NOT NULL					
				)';
				if($this->db->query($query_str)){
					return true;	
				}else{
					return false;
				}
			}	
	    }

	    public function createNewPatientTestRecord($patient_tests_selected_table,$form_array){
	    	$query = $this->db->insert($patient_tests_selected_table,$form_array);
	    	if($query){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    

	    public function createNewPatientTestRecordWard($form_array){
	    	return $this->db->insert('ward_tests_selected',$form_array);
	    }

	    public function getUserIdByName($user_name){
	    	$query = $this->db->get_where('users',array('user_name' => $user_name));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$user_id = $row->id;
	    		}
	    		return $user_id;
	    	}else{
	    		return "";
	    	}
	    }

	    public function getFacilityNameBySlug($facility_slug){
	    	$query = $this->db->get_where('health_facility',array('slug' => $facility_slug));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$facility_name = $row->name;
	    		}
	    		return $facility_name;
	    	}else{
	    		return "";
	    	}
	    }

	    public function getFacilityAddressBySlug($facility_slug){
	    	$query = $this->db->get_where('health_facility',array('slug' => $facility_slug));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$facility_address = $row->address;
	    		}
	    		return $facility_address;
	    	}else{
	    		return "";
	    	}
	    }

	    public function getFacilityIdBySlug($facility_slug){
	    	$query = $this->db->get_where('health_facility',array('slug' => $facility_slug));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$facility_id = $row->id;
	    		}
	    		return $facility_id;
	    	}else{
	    		return "";
	    	}
	    }

	    public function createClinicActivitiesTable($table_name){
	    	if($this->db->table_exists($table_name)){
	    		return true;
	    	}else{
		    	$query_str = 'CREATE TABLE ' .$table_name.' (
					id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					user_id INT NOT NULL,
					patient_name VARCHAR(150) NOT NULL,
					hospital_number VARCHAR(100) NULL,
					complaints TEXT NOT NULL,
					examination_findings TEXT NOT NULL,
					diagnosis TEXT NOT NULL,
					tests_selected TEXT NOT NULL,
					initiation_codes TEXT NOT NULL,
					advice_given TEXT NOT NULL,
					drugs TEXT NOT NULL,
					appointment_date VARCHAR(50) NOT NULL,
					consultation_complete INT NOT NULL,
					consultation_complete_date VARCHAR(50) NOT NULL,
					consultation_complete_time VARCHAR (50) NOT NULL,
					doctor_id INT NOT NULL,
					pr INT NOT NULL,
					bp VARCHAR(50) NOT NULL,
					rr INT NOT NULL,
					temperature DECIMAL(20,2) NOT NULL,
					waist_circumference INT NOT NULL,
					hip_circumference INT NOT NULL,	
					history TEXT NOT NULL,
					dept_id INT NOT NULL,
					sub_dept_id INT NOT NULL,
					last_data_entry_date INT NOT NULL,
					referred_date VARCHAR(50) NOT NULL,
					new_patient INT NOT NULL DEFAULT 0,
					records_registered INT NOT NULL,
					nurse_registered INT NOT NULL,
					consultation_amount BIGINT NOT NULL,
					consultation_amount_paid BIGINT NOT NULL,
					consultation_payment_date VARCHAR(100) NOT NULL,
					consultation_paid INT DEFAULT 0 NOT NULL,
					consultation_receipt_file TEXT NOT NULL
				)';
				if($this->db->query($query_str)){
					return true;	
				}else{
					return false;
				}
			}	
	    }

	    public function checkIfPatientHasBeenRegisteredAsNewPatientBefore($clinic_activities_table_name,$hospital_number){
	    	$query = $this->db->get_where($clinic_activities_table_name,array('hospital_number' => $hospital_number,'new_patient' => 1));
	    	if($query->num_rows() == 1){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function updateNewClinicActivityRecord($clinic_activities_table_name,$form_array1){
	    	$hospital_number = $form_array1['hospital_number'];

	    	$query = $this->db->update($clinic_activities_table_name,$form_array1,array('hospital_number' => $hospital_number));
	    	// set_cookie('query_str',$this->db->last_query());
	    	if($query){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function getLabsAndHospitals($health_facility_id){
	    	$query_str = "SELECT * FROM health_facility WHERE id != " . $health_facility_id . " AND (facility_structure = 'hospital' OR facility_structure = 'laboratory')";
	    	$query = $this->db->query($query_str);
	    	if($query->num_rows() > 0){
	    		return $query->result();
	    	}else{
	    		return false;
	    	}
	    }


	    public function getMortuariesAndHospitals(){
	    	$this->db->select("*");
	    	$this->db->from("health_facility");
	    	$this->db->where("facility_structure","hospital");
	    	$this->db->or_where("facility_structure","mortuary");
	    	$query = $this->db->get();
	    	if($query->num_rows() > 0){
	    		return $query->result();
	    	}else{
	    		return false;
	    	}
	    }


	    public function getPharmaciesAndHospitals(){
	    	$this->db->select("*");
	    	$this->db->from("health_facility");
	    	$this->db->where("facility_structure","hospital");
	    	$this->db->or_where("facility_structure","pharmacy");
	    	$query = $this->db->get();
	    	if($query->num_rows() > 0){
	    		return $query->result();
	    	}else{
	    		return false;
	    	}
	    }

	    public function getIfRecordIdIsValid($health_facility_clinic_activities_table,$record_id){
	    	$query = $this->db->get_where($health_facility_clinic_activities_table,array('id' => $record_id));
	    	if($query->num_rows() == 1){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function createPatientBioDataTable($table_name){
	    	if($this->db->table_exists($table_name)){
	    		return true;
	    	}else{
		    	$query_str = 'CREATE TABLE ' .$table_name.' (
					id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					user_id INT NOT NULL,
					user_name VARCHAR(50) NOT NULL,
					user_type VARCHAR(100) NOT NULL,
					code TEXT NOT NULL,
					hospital_number VARCHAR(100) NULL,
					pr INT NOT NULL,
					bp VARCHAR(50) NOT NULL,
					rr INT NOT NULL,
					temperature DECIMAL(20,2) NOT NULL,
					waist_circumference INT NOT NULL,
					hip_circumference INT NOT NULL,	
					firstname VARCHAR(50) NOT NULL,
					lastname VARCHAR(50) NOT NULL,
					dob VARCHAR(50) NOT NULL,
					age INT NOT NULL,
					age_unit VARCHAR(50) NOT NULL,
					sex VARCHAR(50) NOT NULL,
					fasting INT NOT NULL,
					race VARCHAR(50) NOT NULL,
					mobile_no BIGINT NOT NULL,
					email VARCHAR(50) NOT NULL,
					present_medications TEXT NOT NULL,
					height VARCHAR(50) NOT NULL,
					weight INT NOT NULL,
					address TEXT NOT NULL,
					registered INT NOT NULL,
					nurse_registered INT NOT NULL,
					dr_registered INT NOT NULL,
					paid INT NOT NULL,
					registration_amount BIGINT NOT NULL,
					registration_amount_paid BIGINT NOT NULL,
					consultation_amount BIGINT NOT NULL,
					consultation_amount_paid BIGINT NOT NULL,
					registration_payment_date VARCHAR(100) NOT NULL,
					consultation_payment_date VARCHAR(100) NOT NULL,
					consultation_paid INT DEFAULT 0 NOT NULL,
					consultation_receipt_file TEXT NOT NULL,
					receipt_file TEXT NOT NULL,
					nurse_time VARCHAR(50) NOT NULL,
					date VARCHAR(50) NOT NULL,
					time VARCHAR(50) NOT NULL,
					name_of_next_of_kin VARCHAR(200) NOT NULL,
					address_of_next_of_kin TEXT NOT NULL,
					mobile_no_of_next_of_kin BIGINT NOT NULL,
					username_of_next_of_kin VARCHAR(200) NOT NULL,
					relationship_of_next_of_kin VARCHAR(500) NOT NULL,
					dead INT DEFAULT 0 NOT NULL
				)';
				if($this->db->query($query_str)){
					return true;	
				}else{
					return false;
				}
			}	
	    }

	    public function checkIfPatientIsNurseRegistered($patient_bio_data,$hospital_number){
	    	$query = $this->db->get_where($patient_bio_data,array('hospital_number' => $hospital_number,'nurse_registered' => 1));
	    	if($query->num_rows() == 1){
	    		return false;
	    	}else{
	    		return true;
	    	}
	    }

	    public function getPatientData($user_id,$user_name){	    	
    		$query = $this->db->get_where('patients',array('user_id' => $user_id,'user_name' => $user_name));
    		if($query->num_rows() == 1){
    			return $query->result();
    		}else{
    			return false;
    		}	    		
	    }

	    public function getPendingClinicsUsersToBeRegisteredReferralParamById($id,$param){
	    	$query = $this->db->get_where("pending_clinic_users_to_be_registered_referral",array('id' => $id));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$param_val = $row->$param;
	    		}
	    		return $param_val;
	    	}else{
	    		return false;
	    	}
	    }

	    public function createPatientBioData($form_array,$patient_bio_data_table){
	    	$query = $this->db->insert($patient_bio_data_table,$form_array);
	    	if($query){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function updatePatientBioDataPatientTable($user_id,$user_name,$form_array2){
	    	$query = $this->db->update('patients',$form_array2,array('user_name' => $user_name,'user_id' => $user_id));
	    	if($query){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function getPatientUserNameById($patient_bio_data_table,$id){
	    	$query = $this->db->get_where($patient_bio_data_table,array('id' => $id));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$user_name = $row->user_name;
	    		}
	    		return $user_name;
	    	}
	    }

	    public function getPatientHospitalNumberByUserId($patient_bio_data_table,$user_id){
	    	$query = $this->db->get_where($patient_bio_data_table,array('user_id' => $user_id));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$hospital_number = $row->hospital_number;
	    		}
	    		return $hospital_number;
	    	}
	    }

	    public function getPatientHospitalNumberById($patient_bio_data_table,$id){
	    	$query = $this->db->get_where($patient_bio_data_table,array('id' => $id));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$hospital_number = $row->hospital_number;
	    		}
	    		return $hospital_number;
	    	}
	    }

	    public function getPatientFullNameById($patient_bio_data_table,$id){
	    	$query = $this->db->get_where($patient_bio_data_table,array('id' => $id));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$firstname = $row->firstname;
	    			$lastname = $row->lastname;
	    		}
	    		return $firstname . " " .$lastname;
	    	}
	    }

	    public function getPatientBioDataIdByUserId($patient_bio_data_table,$user_id){
	    	$query = $this->db->get_where($patient_bio_data_table,array('user_id' => $user_id));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$id = $row->id;
	    		}
	    		return $id;
	    	}
	    }

	    

	    public function updatePatientBioData2($patient_bio_data_table,$form_array,$id){
	    	$query = $this->db->update($patient_bio_data_table,$form_array,array('id' => $id));
	    	if($query){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	   
	    public function receiptNameForClinicRegistration($health_facility_slug,$hospital_number,$receipt_number){
	    	return $health_facility_slug . '_' .$hospital_number.'_' . $receipt_number.'.html';
	    }

	    public function receiptNameForPharmacyPayment($health_facility_slug,$receipt_number){
	    	return $health_facility_slug .'_' . $receipt_number.'.html';
	    }

	    public function getPharmacyFullNameByInitiationCode($health_facility_id,$initiation_code){
	    	$query = $this->db->get_where('pharmacy_drugs_selected',array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code),1);
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$full_name = $row->full_name;
	    		}
	    		return $full_name;
	    	}else{
	    		return "";
	    	}
	    }

	    public function getPharmacySexByInitiationCode($health_facility_id,$initiation_code){
	    	$query = $this->db->get_where('pharmacy_drugs_selected',array('health_facility_id' => $health_facility_id,'initiation_code' => $initiation_code),1);
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$sex = $row->sex;
	    		}
	    		return $sex;
	    	}else{
	    		return "";
	    	}
	    }

	    public function getHospitalNumberById($patient_bio_data_table,$id){
	    	$query = $this->db->get_where($patient_bio_data_table,array('id' => $id));
	    	if($query->num_rows() == 1){
	    		foreach($query->result as $row){
	    			$hospital_number = $row->hospital_number;
	    		}
	    		return $hospital_number;
	    	}else{
	    		return false;
	    	}
	    }

	    public function updatePatientBioData3($patient_bio_data_table,$form_array,$id){
	    	$query = $this->db->update($patient_bio_data_table,$form_array,array('hospital_number' => $id));
	    	if($query){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function updateReferralOrConsultRecord($form_array,$referral_id){
	    	return $this->db->update('referrals_or_consults',$form_array,array('id' => $referral_id));
	    }

	    public function checkIfPatientExistsInCinicDataBase($patient_bio_data_table,$patient_id){
	    	$query = $this->db->get_where($patient_bio_data_table,array('user_id' => $patient_id));
	    	if($query->num_rows() > 0){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function checkIfUserIsInPendingRegistrationDatabaseReferral($health_facility_id,$user_id){
	    	$query = $this->db->get_where("pending_clinic_users_to_be_registered_referral",array('user_id' => $user_id,'health_facility_id' => $health_facility_id));
	    	if($query->num_rows() > 0){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function getListOfReferralsAwaitingRegistration($health_facility_id){
	    	$query = $this->db->get_where("pending_clinic_users_to_be_registered_referral",array('health_facility_id' => $health_facility_id));
	    	if($query->num_rows() > 0){
	    		return $query->result();
	    	}else{
	    		return false;
	    	}
	    }

	    public function getReferralAwaitingRegistration($health_facility_id,$id){
	    	$query = $this->db->get_where("pending_clinic_users_to_be_registered_referral",array('health_facility_id' => $health_facility_id,'id' => $id));
	    	if($query->num_rows() == 1){
	    		return $query->result();
	    	}else{
	    		return false;
	    	}
	    }

	    public function enterPendingClinicUsersRegisteredReferralsTable($form_array){
	    	return $this->db->insert("pending_clinic_users_to_be_registered_referral",$form_array);
	    }

	    public function updateClinicActivities($clinic_activities_table_name,$form_array,$id){
	    	// echo $clinic_activities_table_name;
	    	$query = $this->db->update($clinic_activities_table_name,$form_array,array('id' => $id));
	    	// echo $this->db->last_query();
	    	if($query){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function checkIfFacilityIsHospital($health_facility_id){
	    	$query = $this->db->get_where('health_facility',array('id' => $health_facility_id,'facility_structure' => 'hospital'));
	    	if($query->num_rows() == 1){
	    		// echo "hospital";
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    
	    public function updatePatientBioData($form_array,$patient_bio_data_table,$user_id){
	    	$query = $this->db->update($patient_bio_data_table,$form_array,array('user_id' => $user_id));
	    	if($query){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function updatePatientBioData22($form_array,$patient_bio_data_table,$id){
	    	$query = $this->db->update($patient_bio_data_table,$form_array,array('id' => $id));
	    	if($query){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	     public function updatePatientBioDataTable($form_array,$patient_bio_data_table,$lab_id){
	    	$query = $this->db->update($patient_bio_data_table,$form_array,array('lab_id' => $lab_id));
	    	if($query){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function getPatientName($patient_bio_data_table,$user_id,$user_name){
	    	$query = $this->db->get_where($patient_bio_data_table,array('user_id' => $user_id,'user_name' => $user_name));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$firstname = $row->firstname;
	    			$lastname = $row->lastname;
	    			$full_name = $firstname . ' ' . $lastname;
	    		}
	    		return $full_name;
	    	}else{
	    		return false;
	    	}
	    }

	    public function getPatientBioDataTable($patient_bio_data_table,$user_id,$user_name){
	    	$query = $this->db->get_where($patient_bio_data_table,array('user_id' => $user_id,'user_name' => $user_name));
	    	if($query->num_rows() == 1){
	    		return $query->result();
	    	}else{
	    		return false;
	    	}
	    }

	    public function getPatientBioData($health_facility_patient_db_table,$form_array){
	    	$query = $this->db->get_where($health_facility_patient_db_table,$form_array);
	    	if($query->num_rows() == 1){
	    		return $query->result();
	    	}else{
	    		return false;
	    	}
	    }

	    public function getPersonnelDisplayDetailsByUserId($health_facility_table_name,$user_id){
	    	$query = $this->db->get_where($health_facility_table_name,array('user_id' => $user_id));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$title = $row->title;
	    			$full_name = $row->full_name;
	    			$qualification = $row->qualification;
	    		}
	    		$ret_val = $title . ' ' .$full_name . ' ' .$qualification;
	    		return $ret_val;
	    	}else{
	    		return "";
	    	}

	    }

	    public function getPersonnelDisplayDetailsByUserIdSr($health_facility_table_name,$user_id){
	    	$query = $this->db->get_where($health_facility_table_name,array('user_id' => $user_id));
	    	if($query->num_rows() == 1){
	    		foreach($query->result() as $row){
	    			$title = $row->title;
	    			$full_name = $row->full_name;
	    			$qualification = $row->qualification;
	    		}
	    		$ret_val = $title . ' ' .$full_name . ' Senior Registrar';
	    		return $ret_val;
	    	}else{
	    		return "";
	    	}

	    }

	    public function cutomizeInterimPathologistText($default_text,$health_facility_table_name){
			
    		return $default_text . '(For Consultant Pathologist)';
			
		}

		public function convertImage($originalImage, $outputImage, $quality)
		{
		    // jpg, png, gif or bmp?
		    $exploded = explode('.',$originalImage);
		    $ext = $exploded[count($exploded) - 1]; 

		    if (preg_match('/jpg|jpeg/i',$ext))
		        $imageTmp=imagecreatefromjpeg($originalImage);
		    else if (preg_match('/png/i',$ext))
		        $imageTmp=imagecreatefrompng($originalImage);
		    else if (preg_match('/gif/i',$ext))
		        $imageTmp=imagecreatefromgif($originalImage);
		    else if (preg_match('/bmp/i',$ext))
		        $imageTmp=imagecreatefrombmp($originalImage);
		    else
		        return false;

		    // quality is a value from 0 (worst) to 100 (best)
		    imagejpeg($imageTmp, $outputImage, $quality);
		    imagedestroy($imageTmp);

		    return true;
		}

		public function renameFacilityTable($health_facility_table_name,$new_facility_table_name){
			$query = $this->db->query("RENAME TABLE `" . $health_facility_table_name . "` TO `" . $new_facility_table_name . "`");
			if($query){
				return true;
			}else{
				return true;
			}
		}

		public function getDataURI($image, $mime = '') {
			return 'data:'.(function_exists('mime_content_type') ? mime_content_type($image) : $mime).';base64,'.base64_encode(file_get_contents($image));
		}

		public function addTestMainResult($form_array,$health_facility_main_test_result_table){
			$query = $this->db->insert($health_facility_main_test_result_table,$form_array);
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function updateTestMainResult($form_array,$health_facility_main_test_result_table,$main_test_id,$lab_id){
			$query = $this->db->update($health_facility_main_test_result_table,$form_array,array('main_test_id' => $main_test_id,'lab_id' => $lab_id));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfThisTestHasBeenAdded($health_facility_main_test_result_table,$test_id,$lab_id){
			$query = $this->db->get_where($health_facility_main_test_result_table,array('main_test_id' => $test_id,'lab_id' => $lab_id));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function checkIfImagesUploadedBeforeExceedsAmount($health_facility_main_test_result_table,$id){
			$query = $this->db->get_where($health_facility_main_test_result_table,array('id' => $id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$images = $row->images;
				}
				if($images !== ""){

					$images_arr = explode(",", $images);
					// echo count($images_arr);
					if(count($images_arr) <= 2){
						return true;
					}else{
						return false;
					}
				}else{
					return true;
				}
			}
		}

		public function checkIfThisTestHasBeenAdded2($health_facility_main_test_result_table,$main_test_id,$lab_id,$this_id){
			$query = $this->db->get_where($health_facility_main_test_result_table,array('main_test_id' => $main_test_id,'lab_id' => $lab_id,'super_test_id' => $this_id));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

		public function getTestResultId($health_facility_main_test_result_table,$test_name,$lab_id){
			$query = $this->db->get_where($health_facility_main_test_result_table,array('test_name' => $test_name,'lab_id' => $lab_id));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$id = $row->id;
					return $id;
				}
			}else{
				return false;
			}
		}

		

		public function update_table_name_health_facility($health_facility_id,$table_name){
			$query = $this->db->update('health_facility',array('table_name' => $table_name),array('id' => $health_facility_id));
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function updateAllHealthFacilityTableRows($form_array,$table_name){
			$query = $this->db->update($table_name,$form_array);
			if($query){
				return true;
			}else{
				return false;
			}
		}

		//Check If User Exists
		public function userExists($user_name){
			$query = $this->db->get_where('users',array('user_name' => $user_name));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		//Verify Password
		public function password_verify($user_name,$hashed){
			$query = $this->db->get_where('users',array('user_name' => $user_name,'hashed' => $hashed));
			if($query->num_rows() == 1){
				return true;
			}else{
				return false;
			}
		}

		//Check If Sub Admin Exists
		public function checkIfSubAdminExists($health_facility_table_name,$second_addition){
			$query = $this->db->get_where($health_facility_table_name,array('dept' => $second_addition,'position' => 'sub_admin' , 'is_admin' => 1));
			if($query->num_rows() == 1){
				print_r($query->result());
				return true;
			}else{
				return false;
			}
		}

		public function sendMessage($form_array){
			$query = $this->db->insert('notif',$form_array);
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function getPatientUserName($health_facility_test_result_table,$initiation_code){
			$query = $this->db->get_where($health_facility_test_result_table,array('initiation_code' => $initiation_code),1);
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$patient_username = $row->patient_username;
				}
				return $patient_username;
			}
		}

		public function getPatientInfo2($user_name){
			$query = $this->db->get_where('patients',array('user_name' => $user_name),1);
			if($query->num_rows() > 0){
				return $query->result();
			}else{
				return false;
			}
		}

		public function sendMessageCustom($form_array){
			$query = $this->db->insert('notif',$form_array);
			if($query){
				return $this->db->insert_id();
			}else{
				return false;
			}
		}

		public function getAdminsHealthFacilitySlug($user_id){
			$query = $this->db->get_where('users',array('id' => $user_id, 'is_admin' => 1));
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$affiliated_facilities = $row->affiliated_facilities;
				}
				if($affiliated_facilities != ""){
					$affiliated_facilities_arr = explode(",", $affiliated_facilities);
					if(count($affiliated_facilities_arr) == 1){
						if($this->db->table_exists($affiliated_facilities)){
							$query = $this->db->get_where($affiliated_facilities,array('user_id' => $user_id,'position' => 'admin'));
							if($query->num_rows() == 1){
								foreach($query->result() as $row){
									$facility_name = $row->facility_name;
									$query = $this->db->get_where('health_facility',array('table_name' => $affiliated_facilities),1);
									if($query->num_rows() == 1){
										foreach($query->result() as $row){
											$facility_slug = $row->slug;
										}
										return $facility_slug;
									}
									
								}
								
							}
						}
					}
				}
			}
		}


		public function getAdminsUsername($health_facility_id){
			$query = $this->db->get_where('users',array('admin_facility_id' => $health_facility_id),1);
			if($query->num_rows() == 1){
				foreach($query->result() as $row){
					$user_name = $row->user_name;
				}
				return $user_name;
			}
		}

		// public function getSubAdminsUsername($health_facility_table_name,$dept,$sub_dept_slug){
		// 	$query = $this->db->get_where($health_facility_table_name,array('position' => 'admin'));
		// 	if($query->num_rows() == 1){
		// 		foreach($query->result() as $row){
		// 			$admin_username = $row->user_name;
		// 		}
		// 		return $admin_username;
		// 	}
		// }
	}
?>