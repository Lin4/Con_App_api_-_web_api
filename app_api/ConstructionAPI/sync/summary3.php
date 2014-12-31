<?php
/**
 * @name         Sync functions for summarySheet3 table
 * @version      1.0
 * @author       Akila Perera <ravihansa3000@gmail.com>
 * @about        Developed for PrimeEng
 * @lastModified 2014.09.04
 */

// Controller function
function sync_summary3($item, $dbCon) {
	if ($stmt = $dbCon->prepare ( "SELECT * FROM `summarySheet3` WHERE `SMSheetNo` = ? " )) {
		$val = $item ['sMSheetNo'];
		$bind_val = &$val;
		
		if (! $stmt->bind_param ( "s", $bind_val )) {
			logDBError ( "error = " . $stmt->error, "sync_summary3:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_summary3:execute" );
			$stmt->close ();
			return false;
		}
		
		if (! $result = $stmt->get_result ()) {
			logDBError ( "error = " . $stmt->error, "sync_summary3:result" );
			$stmt->close ();
			return false;
		}
		
		$row_count = $result->num_rows;
		$stmt->close ();
		
		if ($row_count > 0) {
			// update existing
			return sync_update_summary3 ( $item, $dbCon );
		} else {
			// add new
			return sync_add_summary3 ( $item, $dbCon );
		}
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_summary3:prepare" );
	}
	
	return false;
}

// Update function
function sync_update_summary3($item, $dbCon) {
	$summary3_array = get_summary3_array ();
	
	$values = array ();
	$updateSETArr = array ();
	
	foreach ( $summary3_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			
			$values [] = &$item [$v [0]];
		}
		$updateSETArr [] = "`" . $k . "` = ?";
	}
	
	$val = $item ['sMSheetNo'];
	$values [] = &$val;
	
	$query = "UPDATE `summarySheet3` SET " . implode ( $updateSETArr, ', ' ) . " WHERE `SMSheetNo` = ?";
	if ($stmt = $dbCon->prepare ( $query )) {
		
		$types = array (
				str_repeat ( 's', count ( $values ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				'bind_param' 
		), $bind_values )) {
			logDBError ( "error = " . $stmt->error, "sync_update_summary3:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_update_summary3:execute" );
			$stmt->close ();
			return false;
		}
		
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_update_summary3" );
	}
	
	return false;
}

// Insert function
function sync_add_summary3($item, $dbCon) {
	$summary3_array = get_summary3_array ();
	
	$values = array ();
	$escaped_db_fields = array ();
	
	foreach ( $summary3_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			$values [] = &$item [$v [0]];
		}
		
		$escaped_db_fields [] = "`" . $k . "`";
	}
	
	$placeholders = array_fill ( 0, count ( $escaped_db_fields ), '?' );
	$query = "INSERT INTO `summarySheet3` (" . implode ( ', ', $escaped_db_fields ) . ") VALUES (" . implode ( ', ', $placeholders ) . ")";
	
	if ($stmt = $dbCon->prepare ( $query )) {
		$types = array (
				str_repeat ( 's', count ( $escaped_db_fields ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				"bind_param" 
		), $bind_values )) {
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_add_summary3:execute" );
			$stmt->close ();
			return false;
		}
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_add_summary3:prepare" );
	}
	return false;
}

// Returns an associate array containing JSON to DB fields mapping
function get_summary3_array() {
	$summary3_array = array (
			"SMSheetNo" => array (
					"sMSheetNo",
					's' 
			),
			"Project_id" => array (
					"project_id",
					's' 
			),
			"EQSizeandClass1" => array (
					"eQSizeandClass1",
					's' 
			),
			"EQIdleActive1" => array (
					"eQIdleActive1",
					's' 
			),
			"EQNo1" => array (
					"eQNo1",
					's' 
			),
			"EQTotalHours1" => array (
					"eQTotalHours1",
					's' 
			),
			"EQRAte1" => array (
					"eQRAte1",
					's' 
			),
			"EQAmount1" => array (
					"eQAmount1",
					's' 
			),
			"EQSizeandClass2" => array (
					"eQSizeandClass2",
					's' 
			),
			"EQIdleActive2" => array (
					"eQIdleActive2",
					's' 
			),
			"EQNo2" => array (
					"eQNo2",
					's' 
			),
			"EQTotalHours2" => array (
					"eQTotalHours2",
					's' 
			),
			"EQRAte2" => array (
					"eQRAte2",
					's' 
			),
			"EQAmount2" => array (
					"eQAmount2",
					's' 
			),
			"EQSizeandClass3" => array (
					"eQSizeandClass3",
					's' 
			),
			"EQIdleActive3" => array (
					"eQIdleActive3",
					's' 
			),
			"EQNo3" => array (
					"eQNo3",
					's' 
			),
			"EQTotalHours3" => array (
					"eQTotalHours3",
					's' 
			),
			"EQRAte3" => array (
					"eQRAte3",
					's' 
			),
			"EQAmount3" => array (
					"eQAmount3",
					's' 
			),
			"EQSizeandClass4" => array (
					"eQSizeandClass4",
					's' 
			),
			"EQIdleActive4" => array (
					"eQIdleActive4",
					's' 
			),
			"EQNo4" => array (
					"eQNo4",
					's' 
			),
			"EQTotalHours4" => array (
					"eQTotalHours4",
					's' 
			),
			"EQRAte4" => array (
					"eQRAte4",
					's' 
			),
			"EQAmount4" => array (
					"eQAmount4",
					's' 
			),
			"EQSizeandClass5" => array (
					"eQSizeandClass5",
					's' 
			),
			"EQIdleActive5" => array (
					"eQIdleActive5",
					's' 
			),
			"EQNo5" => array (
					"eQNo5",
					's' 
			),
			"EQTotalHours5" => array (
					"eQTotalHours5",
					's' 
			),
			"EQRAte5" => array (
					"eQRAte5",
					's' 
			),
                        "EQAmount5" => array (
					"eQAmount5",
					's' 
			),
			"Inspector" => array (
					"inspector",
					's' 
			),
			"Signature1" => array (
					"signature1",
					's' 
			),
			"Date1" => array (
					"date1",
					's' 
			),
			"ContractorRepresentative" => array (
					"contractorRepresentative",
					's' 
			),
			"Signature2" => array (
					"signature2",
					's' 
			),
			"Date2" => array (
					"date2",
					's' 
			),
			"DailyTotal" => array (
					"dailyTotal",
					's' 
			),
			"total_to_date" => array (
					"total_to_date",
					's' 
			) 
	);
	
	return $summary3_array;
}
