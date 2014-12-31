<?php
/**
 * @name         Sync functions for quantity_summary_details table
 * @version      1.0
 * @author       Akila Perera <ravihansa3000@gmail.com>
 * @about        Developed for PrimeEng
 * @lastModified 2014.09.04
 */

// Controller function
function sync_quantity_summary_details($item, $dbCon) {
	if ($stmt = $dbCon->prepare ( "SELECT * FROM `quantity_summary_details` WHERE `id` = ? " )) {
		$val = $item ['id'];
		$bind_val = &$val;
		
		if (! $stmt->bind_param ( "s", $bind_val )) {
			logDBError ( "error = " . $stmt->error, "quantity_summary_details:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "quantity_summary_details:execute" );
			$stmt->close ();
			return false;
		}
		
		if (! $result = $stmt->get_result ()) {
			logDBError ( "error = " . $stmt->error, "quantity_summary_details:result" );
			$stmt->close ();
			return false;
		}
		
		$row_count = $result->num_rows;
		$stmt->close ();		
		
		if ($row_count > 0) {
			// update existing
			return sync_update_quantity_summary_details ( $item, $dbCon );
		} else {
			// add new
			return sync_add_quantity_summary_details ( $item, $dbCon );
		}
	} else {
		logDBError ( "error = " . $dbCon->error, "quantity_summary_details:prepare" );
	}
	
	return false;
}

// Update function
function sync_update_quantity_summary_details($item, $dbCon) {
	$quantity_summary_details_array = get_quantity_summary_details_array ();
	
	$values = array ();
	$updateSETArr = array ();
	
	foreach ( $quantity_summary_details_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			
			$values [] = &$item [$v [0]];
		}
		$updateSETArr [] = "`" . $k . "` = ?";
	}
	
	$val = $item ['id'];
	$values [] = &$val;
	
	$query = "UPDATE `quantity_summary_details` SET " . implode ( $updateSETArr, ', ' ) . " WHERE `id` = ?";
	if ($stmt = $dbCon->prepare ( $query )) {
		
		$types = array (
				str_repeat ( 's', count ( $values ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				'bind_param' 
		), $bind_values )) {
			logDBError ( "error = " . $stmt->error, "sync_update_quantity_summary_details:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_update_quantity_summary_details:execute" );
			$stmt->close ();
			return false;
		}
		
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_update_quantity_summary_details:prepare" );
	}
	
	return false;
}

// Insert function
function sync_add_quantity_summary_details($item, $dbCon) {
	$quantity_summary_details_array = get_quantity_summary_details_array ();
	
	$values = array ();
	$escaped_db_fields = array ();
	
	foreach ( $quantity_summary_details_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			$values [] = &$item [$v [0]];
		}
		
		$escaped_db_fields [] = "`" . $k . "`";
	}
	
	$placeholders = array_fill ( 0, count ( $escaped_db_fields ), '?' );
	$query = "INSERT INTO `quantity_summary_details` (" . implode ( ', ', $escaped_db_fields ) . ") VALUES (" . implode ( ', ', $placeholders ) . ")";
	
	if ($stmt = $dbCon->prepare ( $query )) {
		$types = array (
				str_repeat ( 's', count ( $escaped_db_fields ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				"bind_param" 
		), $bind_values )) {
			logDBError ( "error = " . $stmt->error, "sync_add_quantity_summary_details:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_add_quantity_summary_details:execute" );
			$stmt->close ();
			return false;
		}
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_add_quantity_summary_details:prepare" );
	}
	return false;
}

// Returns an associate array containing DB fields to JSON mapping
function get_quantity_summary_details_array() {
	$quantity_summary_details_array = array (
			"id" => array (
					"id",
					's' 
			),
			"project_id" => array (
					"project_id",
					's' 
			),
			"project" => array (
					"project",
					's' 
			),
			"item_no" => array (
					"item_no",
					's' 
			),
			"est_qty" => array (
					"est_qty",
					's' 
			),
			"unit" => array (
					"unit",
					's' 
			),
			"unit_price" => array (
					"unit_price",
					's' 
			),
			"user" => array (
					"user",
					's' 
			) 
	);
	return $quantity_summary_details_array;
}