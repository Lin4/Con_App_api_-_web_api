<?php
/**
 * @name         Sync functions for dailyInspection_item table
 * @version      1.0
 * @author       lin <lkandasamy@primetgrp.com>
 * @about        Developed for PrimeEng
 * @lastModified 2014.09.04
 */

// Controller function
function sync_daily_inspection_item($item, $dbCon) {
	if ($stmt = $dbCon->prepare ( "SELECT * FROM `dailyInspection_item` WHERE `inspectionID` = ? " )) {
		$val = $item ['inspectionID'];
		$bind_val = &$val;
		
		if (! $stmt->bind_param ( "s", $bind_val )) {
			logDBError ( "error = " . $stmt->error, "sync_daily_inspection_item:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_daily_inspection_item:execute" );
			$stmt->close ();
			return false;
		}
		
		if (! $result = $stmt->get_result ()) {
			logDBError ( "error = " . $stmt->error, "sync_daily_inspection_item:result" );
			$stmt->close ();
			return false;
		}
		
		$row_count = $result->num_rows;
		$stmt->close ();
		
		if ($row_count > 0) {
			// update existing
			return sync_update_daily_inspection_item ( $item, $dbCon );
		} else {
			// add new
			return sync_add_daily_inspection_item ( $item, $dbCon );
		}
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_daily_inspection_item:prepare" );
	}
	
	return false;
}

// Update function
function sync_update_daily_inspection_item($item, $dbCon) {
	$dailyInspection_item_array = get_dailyInspection_item_array ();
	
	$values = array ();
	$updateSETArr = array ();
	
	foreach ( $dailyInspection_item_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			
			$values [] = &$item [$v [0]];
		}
		$updateSETArr [] = "`" . $k . "` = ?";
	}
	
	$val = $item ['inspectionID'];
	$values [] = &$val;
	
	$query = "UPDATE `dailyInspection_item` SET " . implode ( $updateSETArr, ', ' ) . " WHERE `inspectionID` = ?";
	if ($stmt = $dbCon->prepare ( $query )) {
		
		$types = array (
				str_repeat ( 's', count ( $values ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				'bind_param' 
		), $bind_values )) {
			logDBError ( "error = " . $stmt->error, "sync_update_daily_inspection_item:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_update_daily_inspection_item:execute" );
			$stmt->close ();
			return false;
		}
		
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_update_daily_inspection_item:prepare" );
	}
	
	return false;
}

// Insert function
function sync_add_daily_inspection_item($item, $dbCon) {
	$dailyInspection_item_array = get_dailyInspection_item_array ();
	
	$values = array ();
	$escaped_db_fields = array ();
	
	foreach ( $dailyInspection_item_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			$values [] = &$item [$v [0]];
		}
		
		$escaped_db_fields [] = "`" . $k . "`";
	}
	
	$placeholders = array_fill ( 0, count ( $escaped_db_fields ), '?' );
	$query = "INSERT INTO `dailyInspection_item` (" . implode ( ', ', $escaped_db_fields ) . ") VALUES (" . implode ( ', ', $placeholders ) . ")";
	
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
			logDBError ( "error = " . $stmt->error, "sync_add_daily_inspection_item:execute" );
			$stmt->close ();
			logDBError ( "error = " . $stmt->error, "sync_add_daily_inspection_item:bind" );
			return false;
		}
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_add_daily_inspection_item:prepare" );
	}
	return false;
}

// Returns an associate array containing DB fields to JSON mapping
function get_dailyInspection_item_array() {
	$dailyInspection_item_array = array (
			"inspectionID" => array (
					"inspectionID",
					's' 
			),
			"No" => array (
					"no",
					's' 
			),
			"Description" => array (
					"desc",
					's' 
			),
			"Qty" => array (
					"qty",
					's' 
			),
			"date" => array (
					"date",
					's' 
			) 
	);
	return $dailyInspection_item_array;
}
