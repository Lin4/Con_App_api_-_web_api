<?php
/**
 * @name         Sync functions for quantityEstimateForm table
 * @version      1.0
 * @author       lin <lkandasamy@primetgrp.com>
 * @about        Developed for PrimeEng
 * @lastModified 2014.09.21
 */

// Controller function
function sync_qty_estimate_form($item, $dbCon) {
	if ($stmt = $dbCon->prepare ( "SELECT * FROM `quantityEstimateForm` WHERE `qunatityReportNo` = ? " )) {
		$val = $item ['qunatityReportNo'];
		$bind_val = &$val;
		
		if (! $stmt->bind_param ( "s", $bind_val )) {
			logDBError ( "error = " . $stmt->error, "sync_qty_estimate_form:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_qty_estimate_form:execute" );
			$stmt->close ();
			return false;
		}
		
		if (! $result = $stmt->get_result ()) {
			logDBError ( "error = " . $stmt->error, "sync_qty_estimate_form:result" );
			$stmt->close ();
			return false;
		}
		
		$row_count = $result->num_rows;
		$stmt->close ();
		
		if ($row_count > 0) {
			// update existing
			return sync_update_qty_estimate_report ( $item, $dbCon );
		} else {
			// add new
			return sync_add_qty_estimate_report ( $item, $dbCon );
		}
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_qty_estimate_form:prepare" );
	}
	
	return false;
}

// Update function
function sync_update_qty_estimate_report($item, $dbCon) {
	$expense_report_array = get_expense_report_array ();
	
	$values = array ();
	$updateSETArr = array ();
	
	foreach ( $expense_report_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			
			$values [] = &$item [$v [0]];
		}
		$updateSETArr [] = "`" . $k . "` = ?";
	}
	
	$val = $item ['qunatityReportNo'];
	$values [] = &$val;
	
	$query = "UPDATE `quantityEstimateForm` SET " . implode ( $updateSETArr, ', ' ) . " WHERE `qunatityReportNo` = ?";
	if ($stmt = $dbCon->prepare ( $query )) {
		
		$types = array (
				str_repeat ( 's', count ( $values ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				'bind_param' 
		), $bind_values )) {
			logDBError ( "error = " . $stmt->error, "sync_update_qty_estimate_report:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_update_qty_estimate_report:execute" );
			$stmt->close ();
			return false;
		}
		
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_update_qty_estimate_report:prepare" );
	}
	
	return false;
}

// Insert function
function sync_add_qty_estimate_report($item, $dbCon) {
	$expense_report_array = get_qty_estimate_report_array ();
	
	$values = array ();
	$escaped_db_fields = array ();
	
	foreach ( $expense_report_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			$values [] = &$item [$v [0]];
		}
		
		$escaped_db_fields [] = "`" . $k . "`";
	}
	
	$placeholders = array_fill ( 0, count ( $escaped_db_fields ), '?' );
	$query = "INSERT INTO `quantityEstimateForm` (" . implode ( ', ', $escaped_db_fields ) . ") VALUES (" . implode ( ', ', $placeholders ) . ")";
	
	if ($stmt = $dbCon->prepare ( $query )) {
		$types = array (
				str_repeat ( 's', count ( $escaped_db_fields ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				"bind_param" 
		), $bind_values )) {
			logDBError ( "error = " . $stmt->error, "sync_add_qty_estimate_report:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_add_qty_estimate_report:execute" );
			$stmt->close ();
			return false;
		}
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_add_qty_estimate_report:prepare" );
	}
	return false;
}

// Returns an associate array containing DB fields to JSON mapping
function get_qty_estimate_report_array() {
	$qty_estimate_report_array = array (
			"date" => array (
					"date",
					's' 
			),
			"qunatityReportNo" => array (
					"qunatityReportNo",
					's' 
			),
			"est_Quantity" => array (
					"est_quantity",
					's' 
			),
			"item" => array (
					"item",
					's' 
			),
			"itemNo" => array (
					"itemNo",
					's' 
			),
			"Project_id" => array (
					"project_id",
					's' 
			),
			"projectName" => array (
					"projectName",
					's' 
			),
			"sheetNo" => array (
					"sheetNo",
					's' 
			),
			"unit" => array (
					"unit",
					's' 
			),
			"unitPrice" => array (
					"unit_price",
					's' 
			),
			"user" => array (
					"user",
					's' 
			),
			"UserID" => array (
					"userID",
					's' 
			),
			"isApproved" => array (
					"isApproved",
					's' 
			) 
	);
	return $qty_estimate_report_array;
}