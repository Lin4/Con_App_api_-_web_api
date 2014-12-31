<?php
/**
 * @name         Sync functions for quantity_summary_items table
 * @version      1.0
 * @author       Akila Perera <ravihansa3000@gmail.com>
 * @about        Developed for PrimeEng
 * @lastModified 2014.09.04
 */

// Controller function
function sync_quantity_summary_items($item, $dbCon) {
	if ($stmt = $dbCon->prepare ( "SELECT * FROM `quantity_summary_items` WHERE `item_no` = ? AND `quantity_sum_details_no` = ?" )) {
		$val1 = $item ['item_no'];
		$val2 = $item ['quantity_sum_details_no'];
		$bind_val1 = &$val1;
		$bind_val2 = &$val2;
		
		if (! $stmt->bind_param ( "ss", $bind_val1, $bind_val2 )) {
			logDBError ( "error = " . $stmt->error, "sync_quantity_summary_items:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_quantity_summary_items:execute" );
			$stmt->close ();
			return false;
		}
		
		if (! $result = $stmt->get_result ()) {
			logDBError ( "error = " . $stmt->error, "sync_quantity_summary_items:result" );
			$stmt->close ();
			return false;
		}
		
		$row_count = $result->num_rows;
		$stmt->close ();
		
		if ($row_count > 0) {
			// update existing
			return sync_update_quantity_summary_items ( $item, $dbCon );
		} else {
			// add new
			return sync_add_quantity_summary_items ( $item, $dbCon );
		}
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_quantity_summary_items:prepare" );
	}
	
	return false;
}

// Update function
function sync_update_quantity_summary_items($item, $dbCon) {
	$quantity_summary_items_array = get_quantity_summary_items_array ();
	
	$values = array ();
	$updateSETArr = array ();
	
	foreach ( $quantity_summary_items_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			
			$values [] = &$item [$v [0]];
		}
		$updateSETArr [] = "`" . $k . "` = ?";
	}
	
	$val1 = $item ['item_no'];
	$val2 = $item ['quantity_sum_details_no'];
	$values [] = &$val1;
	$values [] = &$val2;
	
	$query = "UPDATE `quantity_summary_items` SET " . implode ( $updateSETArr, ', ' ) . " WHERE `item_no` = ? AND `quantity_sum_details_no` = ? ";
	if ($stmt = $dbCon->prepare ( $query )) {
		
		$types = array (
				str_repeat ( 's', count ( $values ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				'bind_param' 
		), $bind_values )) {
			logDBError ( "error = " . $stmt->error, "sync_update_quantity_summary_items:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_update_quantity_summary_items:execute" );
			$stmt->close ();
			return false;
		}
		
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_update_quantity_summary_items:prepare" );
	}
	
	return false;
}

// Insert function
function sync_add_quantity_summary_items($item, $dbCon) {
	$quantity_summary_items_array = get_quantity_summary_items_array ();
	
	$values = array ();
	$escaped_db_fields = array ();
	
	foreach ( $quantity_summary_items_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			$values [] = &$item [$v [0]];
		}
		
		$escaped_db_fields [] = "`" . $k . "`";
	}
	
	$placeholders = array_fill ( 0, count ( $escaped_db_fields ), '?' );
	$query = "INSERT INTO `quantity_summary_items` (" . implode ( ', ', $escaped_db_fields ) . ") VALUES (" . implode ( ', ', $placeholders ) . ")";
	
	if ($stmt = $dbCon->prepare ( $query )) {
		$types = array (
				str_repeat ( 's', count ( $escaped_db_fields ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				"bind_param" 
		), $bind_values )) {
			logDBError ( "error = " . $stmt->error, "sync_add_quantity_summary_items:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_add_quantity_summary_items:execute" );
			$stmt->close ();
			return false;
		}
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_add_quantity_summary_items:prepare" );
	}
	return false;
}

// Returns an associate array containing DB fields to JSON mapping
function get_quantity_summary_items_array() {
	$quantity_summary_items_array = array (
			"item_no" => array (
					"item_no",
					's' 
			),
			"quantity_sum_details_no" => array (
					"quantity_sum_details_no",
					's' 
			),
			"date" => array (
					"date",
					's' 
			),
			"location_station" => array (
					"location_station",
					's' 
			),
			"daily" => array (
					"daily",
					's' 
			),
			"accum" => array (
					"accum",
					's' 
			) 
	);
	return $quantity_summary_items_array;
}
