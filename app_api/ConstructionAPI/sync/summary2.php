<?php
/**
 * @name         Sync functions for summarySheet2 table
 * @version      1.0
 * @author       Akila Perera <ravihansa3000@gmail.com>
 * @about        Developed for PrimeEng
 * @lastModified 2014.09.04
 */

// Controller function
function sync_summary2($item, $dbCon) {
	if ($stmt = $dbCon->prepare ( "SELECT * FROM `summarySheet2` WHERE `SMSSheetNo` = ? " )) {
		$val = $item ['sMSSheetNo'];
		$bind_val = &$val;
		
		if (! $stmt->bind_param ( "s", $bind_val )) {
			logDBError ( "error = " . $stmt->error, "sync_summary2:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_summary2:execute" );
			$stmt->close ();
			return false;
		}
		
		if (! $result = $stmt->get_result ()) {
			logDBError ( "error = " . $stmt->error, "sync_summary2:result" );
			$stmt->close ();
			return false;
		}
		
		$row_count = $result->num_rows;
		$stmt->close ();
		
		if ($row_count > 0) {
			// update existing
			return sync_update_summary2_form ( $item, $dbCon );
		} else {
			// add new
			return sync_add_summary2_form ( $item, $dbCon );
		}
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_summary2:prepare" );
	}
	return false;
}

// Update function
function sync_update_summary2_form($item, $dbCon) {
	$summary2_array = get_summary2_array ();
	
	$values = array ();
	$updateSETArr = array ();
	
	foreach ( $summary2_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			
			$values [] = &$item [$v [0]];
		}
		$updateSETArr [] = "`" . $k . "` = ?";
	}
	
	$val = $item ['sMSSheetNo'];
	$values [] = &$val;
	
	$query = "UPDATE `summarySheet2` SET " . implode ( $updateSETArr, ', ' ) . " WHERE `SMSSheetNo` = ?";
	if ($stmt = $dbCon->prepare ( $query )) {
		
		$types = array (
				str_repeat ( 's', count ( $values ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				'bind_param' 
		), $bind_values )) {
			logDBError ( "error = " . $stmt->error, "sync_update_summary2:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_update_summary2:execute" );
			$stmt->close ();
			return false;
		}
		
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_update_summary2" );
	}
	
	return false;
}

// Insert function
function sync_add_summary2_form($item, $dbCon) {
	$summary2_array = get_summary2_array ();
	
	$values = array ();
	$escaped_db_fields = array ();
	
	foreach ( $summary2_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			$values [] = &$item [$v [0]];
		}
		
		$escaped_db_fields [] = "`" . $k . "`";
	}
	
	$placeholders = array_fill ( 0, count ( $escaped_db_fields ), '?' );
	$query = "INSERT INTO `summarySheet2` (" . implode ( ', ', $escaped_db_fields ) . ") VALUES (" . implode ( ', ', $placeholders ) . ")";
	
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
			logDBError ( "error = " . $stmt->error, "sync_add_summary2_form:execute" );
			$stmt->close ();
			return false;
		}
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_add_summary2_form:prepare" );
	}
	return false;
}

// Returns an associate array containing JSON to DB fields mapping
function get_summary2_array() {
	$summary2_array = array (
			"SMSSheetNo" => array (
					"sMSSheetNo",
					's' 
			),
			"Project_id" => array (
					"project_id",
					's' 
			),
			"MEDescription1" => array (
					"mEDescription1",
					's' 
			),
			"MEQuantity1" => array (
					"mEQuantity1",
					's' 
			),
			"MEUnitPrice1" => array (
					"mEUnitPrice1",
					's' 
			),
			"MEAmount1" => array (
					"mEAmount1",
					's' 
			),
			"MEDescription2" => array (
					"mEDescription2",
					's' 
			),
			"MEQuantity2" => array (
					"mEQuantity2",
					's' 
			),
			"MEUnitPrice2" => array (
					"mEUnitPrice2",
					's' 
			),
			"MEAmount2" => array (
					"mEAmount2",
					's' 
			),
			"MEDescription3" => array (
					"mEDescription3",
					's' 
			),
			"MEQuantity3" => array (
					"mEQuantity3",
					's' 
			),
			"MEUnitPrice3" => array (
					"mEUnitPrice3",
					's' 
			),
			"MEAmount3" => array (
					"mEAmount3",
					's' 
			),
			"MEDescription4" => array (
					"mEDescription4",
					's' 
			),
			"MEQuantity4" => array (
					"mEQuantity4",
					's' 
			),
			"MEUnitPrice4" => array (
					"mEUnitPrice4",
					's' 
			),
			"MEAmount4" => array (
					"mEAmount4",
					's' 
			),
			"MEDescription5" => array (
					"mEDescription5",
					's' 
			),
			"MEQuantity5" => array (
					"mEQuantity5",
					's' 
			),
			"MEUnitPrice5" => array (
					"mEUnitPrice5",
					's' 
			),
			"MEAmount5" => array (
					"mEAmount5",
					's' 
			),
			"Total1" => array (
					"total1",
					's' 
			),
			"LessDiscount" => array (
					"lessDiscount",
					's' 
			),
			"Total2" => array (
					"total2",
					's' 
			),
			"AdditionalDiscount" => array (
					"additionalDiscount",
					's' 
			),
			"Total3" => array (
					"total3",
					's' 
			) 
	);
	
	return $summary2_array;
}