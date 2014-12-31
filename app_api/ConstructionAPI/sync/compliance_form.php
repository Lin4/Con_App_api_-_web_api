<?php
/**
 * @name         Sync functions for complianceForm table
 * @version      1.0
 * @author       lin <lkandasamy@primetgrp.com>
 * @about        Developed for PrimeEng
 * @lastModified 2014.09.04
 */

// Controller function
function sync_compliance_form($item, $dbCon) {
	if ($stmt = $dbCon->prepare ( "SELECT * FROM `complianceForm` WHERE `complianceReportNo` = ? " )) {
		$val = $item ['complianceReportNo'];
		$bind_val = &$val;
		
		if (! $stmt->bind_param ( "s", $bind_val )) {
			logDBError ( "error = " . $stmt->error, "sync_compliance_form:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_compliance_form:execute" );
			$stmt->close ();
			return false;
		}
		
		if (! $result = $stmt->get_result ()) {
			logDBError ( "error = " . $stmt->error, "sync_compliance_form:result" );
			$stmt->close ();
			return false;
		}	

		$row_count = $result->num_rows;
		$stmt->close ();
		
		if ($row_count > 0) {
			// update existing
			return sync_update_compliance_form ( $item, $dbCon );
		} else {
			// add new
			return sync_add_compliance_form ( $item, $dbCon );
		}
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_compliance_form:prepare" );
	}
	
	return false;
}

// Update function
function sync_update_compliance_form($item, $dbCon) {
	$compliance_form_array = get_compliance_form_array ();
	
	$values = array ();
	$updateSETArr = array ();
	
	foreach ( $compliance_form_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			
			$values [] = &$item [$v [0]];
		}
		$updateSETArr [] = "`" . $k . "` = ?";
	}
	
	$val = $item ['complianceReportNo'];
	$values [] = &$val;
	
	$query = "UPDATE `complianceForm` SET " . implode ( $updateSETArr, ', ' ) . " WHERE `complianceReportNo` = ?";
	if ($stmt = $dbCon->prepare ( $query )) {
		
		$types = array (
				str_repeat ( 's', count ( $values ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				'bind_param' 
		), $bind_values )) {
			logDBError ( "error = " . $stmt->error, "sync_update_compliance_form:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_update_compliance_form:execute" );
			$stmt->close ();
			return false;
		}
		
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_update_compliance_form:prepare" );
	}
	
	return false;
}

// Insert function
function sync_add_compliance_form($item, $dbCon) {
	$compliance_form_array = get_compliance_form_array ();
	
	$values = array ();
	$escaped_db_fields = array ();
	
	foreach ( $compliance_form_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {			
			$values [] = &$item [$v[0]];
		}
		
		$escaped_db_fields [] = "`" . $k . "`";
	}
	
	$placeholders = array_fill ( 0, count ( $escaped_db_fields ), '?' );
	$query = "INSERT INTO `complianceForm` (" . implode ( ', ', $escaped_db_fields ) . ") VALUES (" . implode ( ', ', $placeholders ) . ")";
	
	if ($stmt = $dbCon->prepare ( $query )) {
		$types = array (
				str_repeat ( 's', count ( $escaped_db_fields ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				"bind_param" 
		), $bind_values )) {
			logDBError ( "error = " . $stmt->error, "sync_add_compliance_form:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_add_compliance_form:execute" );
			$stmt->close ();
			return false;
		}
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_add_compliance_form:prepare" );
	}
	return false;
}

// Returns an associate array containing DB fields to JSON mapping
function get_compliance_form_array() {
	$compliance_form_array = array (
			"comHeader" => array (
					"comHeader",
					's' 
			),
			"complianceReportNo" => array (
					"complianceReportNo",
					's' 
			),
			"complianceNoticeNo" => array (
					"complianceNoticeNo",
					's' 
			),
			"ContractNo" => array (
					"contractNo",
					's' 
			),
			"ContractorResponsible" => array (
					"contractorResponsible",
					's' 
			),
			"CorrectiveActionCompliance" => array (
					"correctiveActionCompliance",
					's' 
			),
			"Date" => array (
					"date",
					's' 
			),
			"DateContractorCompleted" => array (
					"dateContractorCompleted",
					's' 
			),
			"DateContractorStarted" => array (
					"dateContractorStarted",
					's' 
			),
			"DateIssued" => array (
					"dateIssued",
					's' 
			),
			"DateOfDWRReported" => array (
					"dateOfDWRReported",
					's' 
			),
			"images_1" => array (
					"images_1",
					's' 
			),
			"images_1_desc" => array (
					"images_1_desc",
					's' 
			),
			"images_2" => array (
					"images_2",
					's' 
			),
			"images_2_desc" => array (
					"images_2_desc",
					's' 
			),
			"images_3" => array (
					"images_3",
					's' 
			),
			"images_3_desc" => array (
					"images_3_desc",
					's' 
			),
			"images_uploaded" => array (
					"images_uploaded",
					's' 
			),
			"isApproved" => array (
				"isApproved",
					's' 
			),			
			"PrintedName" => array (
					"printedName",
					's' 
			),
			"project" => array (
					"project",
					's' 
			),			
			"Project_id" => array (
					"project_id",
					's' 
			),
			"projectDescription" => array (
					"projectDescription",
					's' 
			),
			"Signature" => array (
					"signature",
					's' 
			),
			"sketch_images" => array (
					"sketch_images",
					's' 
			),
			"Title" => array (
					"title",
					's' 
			),
			"To" => array (
					"to",
					's' 
			),
			"UserID" => array (
					"userID",
					's' 
			) 
	);
	
	return $compliance_form_array;
}
