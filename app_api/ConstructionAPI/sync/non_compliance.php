<?php
/**
 * @name         Sync functions for nonComplianceForm table
 * @version      1.0
 * @author       lin <lkandasamy@primetgrp.com>
 * @about        Developed for PrimeEng
 * @lastModified 2014.09.04
 */

// Controller function
function sync_non_compliance_form($item, $dbCon) {
	if ($stmt = $dbCon->prepare ( "SELECT * FROM `nonComplianceForm` WHERE `nonComplianceReportNo` = ? " )) {
		$val = $item ['nonComplianceReportNo'];
		$bind_val = &$val;
		
		if (! $stmt->bind_param ( "s", $bind_val )) {
			logDBError ( "error = " . $stmt->error, "sync_non_compliance_form_form:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_non_compliance_form_form:execute" );
			$stmt->close ();
			return false;
		}
		
		if (! $result = $stmt->get_result ()) {
			logDBError ( "error = " . $stmt->error, "sync_non_compliance_form:result" );
			$stmt->close ();
			return false;
		}
		
		$row_count = $result->num_rows;
		$stmt->close ();
		
		if ($row_count > 0) {
			// update existing
			return sync_update_non_compliance_form ( $item, $dbCon );
		} else {
			// add new
			return sync_add_non_compliance_form ( $item, $dbCon );
		}
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_non_compliance_form:prepare" );
	}
	
	return false;
}

// Update function
function sync_update_non_compliance_form($item, $dbCon) {
	$non_compliance_form_array = get_non_compliance_form_array ();
	
	$values = array ();
	$updateSETArr = array ();
	
	foreach ( $non_compliance_form_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			
			$values [] = &$item [$v [0]];
		}
		$updateSETArr [] = "`" . $k . "` = ?";
	}
	
	$val = $item ['nonComplianceReportNo'];
	$values [] = &$val;
	
	$query = "UPDATE `nonComplianceForm` SET " . implode ( $updateSETArr, ', ' ) . " WHERE `nonComplianceReportNo` = ?";
	if ($stmt = $dbCon->prepare ( $query )) {
		
		$types = array (
				str_repeat ( 's', count ( $values ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				'bind_param' 
		), $bind_values )) {
			logDBError ( "error = " . $stmt->error, "sync_update_non_compliance_form:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_update_non_compliance_form:execute" );
			$stmt->close ();
			return false;
		}
		
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_update_non_compliance_form" );
	}
	
	return false;
}

// Insert function
function sync_add_non_compliance_form($item, $dbCon) {
	$non_compliance_form_array = get_non_compliance_form_array ();
	
	$values = array ();
	$escaped_db_fields = array ();
	
	foreach ( $non_compliance_form_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			$values [] = &$item [$v [0]];
		}
		
		$escaped_db_fields [] = "`" . $k . "`";
	}
	
	$placeholders = array_fill ( 0, count ( $escaped_db_fields ), '?' );
	$query = "INSERT INTO `nonComplianceForm` (" . implode ( ', ', $escaped_db_fields ) . ") VALUES (" . implode ( ', ', $placeholders ) . ")";
	
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
			logDBError ( "error = " . $stmt->error, "sync_add_non_compliance_form:execute" );
			$stmt->close ();
			return false;
		}
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_add_non_compliance_form:prepare" );
	}
	return false;
}

// Returns an associate array containing JSON to DB fields mapping
function get_non_compliance_form_array() {
	$non_compliance_form_array = array (
			"ContractNo" => array (
					"contractNo",
					's' 
			),
			"ContractorResponsible" => array (
					"contractorResponsible",
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
			"DateCRTCB" => array (
					"dateCRTCB",
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
			"DescriptionOfNonCompliance" => array (
					"descriptionOfNonCompliance",
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
			"Non_ComHeader" => array (
					"non_ComHeader",
					's' 
			),
			"nonComplianceReportNo" => array (
					"nonComplianceReportNo",
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
			),
			"non_ComplianceNoticeNo" => array (
					"non_ComplianceNoticeNo",
					's' 
			)
	);
	
	return $non_compliance_form_array;
}
