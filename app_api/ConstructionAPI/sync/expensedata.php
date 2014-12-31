<?php
/**
 * @name         Sync functions for expensedata table
 * @version      1.0
 * @author       Akila Perera <ravihansa3000@gmail.com>
 * @about        Developed for PrimeEng
 * @lastModified 2014.09.04
 */

// Controller function
function sync_expensedata($item, $dbCon) {
	if ($stmt = $dbCon->prepare ( "SELECT * FROM `expensedata` WHERE `EXReportNo` = ? " )) {
		$val = $item ['eXReportNo'];
		$bind_val = &$val;
		
		if (! $stmt->bind_param ( "s", $bind_val )) {
			logDBError ( "error = " . $stmt->error, "sync_expensedata:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_expensedata:execute" );
			$stmt->close ();
			return false;
		}
		
		if (! $result = $stmt->get_result ()) {
			logDBError ( "error = " . $stmt->error, "sync_expensedata:result" );
			$stmt->close ();
			return false;
		}
		
		$row_count = $result->num_rows;
		$stmt->close ();
		
		if ($row_count > 0) {
			// update existing
			return sync_update_expensedata ( $item, $dbCon );
		} else {
			// add new
			return sync_add_expensedata ( $item, $dbCon );
		}
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_expensedata:prepare" );
	}
	
	return false;
}

// Update function
function sync_update_expensedata($item, $dbCon) {
	$expensedata_array = get_expensedata_array ();
	
	$values = array ();
	$updateSETArr = array ();
	
	foreach ( $expensedata_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			
			$values [] = &$item [$v [0]];
		}
		$updateSETArr [] = "`" . $k . "` = ?";
	}
	
	$val = $item ['eXReportNo'];
	$values [] = &$val;
	
	$query = "UPDATE `expensedata` SET " . implode ( $updateSETArr, ', ' ) . " WHERE `EXReportNo` = ?";
	if ($stmt = $dbCon->prepare ( $query )) {
		
		$types = array (
				str_repeat ( 's', count ( $values ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				'bind_param' 
		), $bind_values )) {
			logDBError ( "error = " . $stmt->error, "sync_update_expensedata:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_update_expensedata:execute" );
			$stmt->close ();
			return false;
		}
		
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_update_expensedata:prepare" );
	}
	
	return false;
}

// Insert function
function sync_add_expensedata($item, $dbCon) {
	$expensedata_array = get_expensedata_array ();
	
	$values = array ();
	$escaped_db_fields = array ();
	
	foreach ( $expensedata_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			$values [] = &$item [$v [0]];
		}
		
		$escaped_db_fields [] = "`" . $k . "`";
	}
	
	$placeholders = array_fill ( 0, count ( $escaped_db_fields ), '?' );
	$query = "INSERT INTO `expensedata` (" . implode ( ', ', $escaped_db_fields ) . ") VALUES (" . implode ( ', ', $placeholders ) . ")";
	
	if ($stmt = $dbCon->prepare ( $query )) {
		$types = array (
				str_repeat ( 's', count ( $escaped_db_fields ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				"bind_param" 
		), $bind_values )) {
			logDBError ( "error = " . $stmt->error, "sync_add_expensedata:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_add_expensedata:execute" );
			$stmt->close ();
			return false;
		}
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_add_expensedata:prepare" );
	}
	return false;
}

// Returns an associate array containing DB fields to JSON mapping
function get_expensedata_array() {
	$expensedata_array = array (
			"EXReportNo" => array (
					"eXReportNo",
					's' 
			),
			"ERDate1" => array (
					"eRDate1",
					's' 
			),
			"ERDescription1" => array (
					"eRDescription1",
					's' 
			),
			"ERJobNo1" => array (
					"eRJobNo1",
					's' 
			),
			"ERType1" => array (
					"eRType1",
					's' 
			),
			"ERPAMilage1" => array (
					"eRType1",
					's' 
			),
			"ERPARate1" => array (
					"eRPARate1",
					's' 
			),
			"ERTotal1" => array (
					"eRPARate1",
					's' 
			),
			"images_uploaded" => array (
					"images_uploaded",
					's' 
			) 
	);
	return $expensedata_array;
}
