<?php
	$query_str = 'CREATE TABLE ' .$table_name.' (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		facility_name VARCHAR(100) NOT NULL,
		initiation_code VARCHAR(100) NOT NULL,
		lab_id TEXT NOT NULL,
		test_id VARCHAR(1000) NOT NULL,
		patient_name VARCHAR(200) NOT NULL,
		test_name TEXT NOT NULL,
		price BIGINT(20) NOT NULL,
		ta_time BIGINT(20) NOT NULL,
		date VARCHAR(100) NOT NULL,
		time VARCHAR(100) NOT NULL,
		invalid INT NOT NULL DEFAULT 0,
		paid INT NOT NULL DEFAULT 0,
		date_paid VARCHAR(50) NOT NULL,
		time_paid VARCHAR(50) NOT NULL
	)';
	if($this->db->query($query_str)){
		$query = $this->db->insert($health_facility_test_result_table,$array);
		if($query){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}

?>