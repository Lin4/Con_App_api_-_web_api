<?php
/**
 * @name         Sync functions for expenseReport table
 * @version      1.0
 * @author       lin <lkandasamy@primetgrp.com>
 * @about        Developed for PrimeEng
 * @lastModified 2014.09.21
 */

// Controller function
function sync_expense_report($item, $dbCon) {
	if ($stmt = $dbCon->prepare ( "SELECT * FROM `expenseReportModel` WHERE `eXReportNo` = ? " )) {
		$val = $item ['eXReportNo'];
		$bind_val = &$val;
		
		if (! $stmt->bind_param ( "s", $bind_val )) {
			logDBError ( "error = " . $stmt->error, "sync_expense_report:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_expense_report:execute" );
			$stmt->close ();
			return false;
		}
		
		if (! $result = $stmt->get_result ()) {
			logDBError ( "error = " . $stmt->error, "sync_expense_report:result" );
			$stmt->close ();
			return false;
		}
		
		$row_count = $result->num_rows;
		$stmt->close ();
		
		if ($row_count > 0) {
			// update existing
			return sync_update_expense_report ( $item, $dbCon );
		} else {
			// add new
			return sync_add_expense_report ( $item, $dbCon );
		}
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_expense_report:prepare" );
	}
	
	return false;
}

// Update function
function sync_update_expense_report($item, $dbCon) {
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
	
	$val = $item ['eXReportNo'];
	$values [] = &$val;
	
	$query = "UPDATE `expenseReportModel` SET " . implode ( $updateSETArr, ', ' ) . " WHERE `eXReportNo` = ?";
	if ($stmt = $dbCon->prepare ( $query )) {
		
		$types = array (
				str_repeat ( 's', count ( $values ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				'bind_param' 
		), $bind_values )) {
			logDBError ( "error = " . $stmt->error, "sync_update_expense_report:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_update_expense_report:execute" );
			$stmt->close ();
			return false;
		}
		
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_update_expense_report:prepare" );
	}
	
	return false;
}

// Insert function
function sync_add_expense_report($item, $dbCon) {
	$expense_report_array = get_expense_report_array ();
	
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
	$query = "INSERT INTO `expenseReportModel` (" . implode ( ', ', $escaped_db_fields ) . ") VALUES (" . implode ( ', ', $placeholders ) . ")";
	
	if ($stmt = $dbCon->prepare ( $query )) {
		$types = array (
				str_repeat ( 's', count ( $escaped_db_fields ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				"bind_param" 
		), $bind_values )) {
			logDBError ( "error = " . $stmt->error, "sync_add_expense_report:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_add_expense_report:execute" );
			$stmt->close ();
			return false;
		}
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_add_expense_report:prepare" );
	}
	return false;
}

// Returns an associate array containing DB fields to JSON mapping
function get_expense_report_array() {
	$expense_report_array = array (
			"eXReportNo" => array (
					"eXReportNo",
					's' 
			),
			"Auto_1" => array (
					"auto_1",
					's' 
			),
			"Auto_2" => array (
					"auto_2",
					's' 
			),
			"Auto_3" => array (
					"auto_3",
					's' 
			),
			"Auto_4" => array (
					"auto_4",
					's' 
			),
			"Auto_5" => array (
					"auto_5",
					's' 
			),
			"Auto_6" => array (
					"auto_6",
					's' 
			),
			"Auto_7" => array (
					"auto_7",
					's' 
			),
			"Auto_8" => array (
					"auto_8",
					's' 
			),
			"CheckNo" => array (
					"checkNo",
					's' 
			),
			"Date" => array (
					"date",
					's' 
			),
			"ERDate1" => array (
					"date_1",
					's' 
			),
			"ERDate2" => array (
					"date_2",
					's' 
			),
			"ERDate3" => array (
					"date_3",
					's' 
			),
			"ERDate4" => array (
					"date_4",
					's' 
			),
			"ERDate5" => array (
					"date_5",
					's' 
			),
			"ERDate6" => array (
					"date_6",
					's' 
			),
			"ERDate7" => array (
					"date_7",
					's' 
			),
			"ERDate8" => array (
					"date_8",
					's' 
			),
			"ERDescription1" => array (
					"description_1",
					's' 
			),
			"ERDescription2" => array (
					"description_2",
					's' 
			),
			"ERDescription3" => array (
					"description_3",
					's' 
			),
			"ERDescription4" => array (
					"description_4",
					's' 
			),
			"ERDescription5" => array (
					"description_5",
					's' 
			),
			"ERDescription6" => array (
					"description_6",
					's' 
			),
			"ERDescription7" => array (
					"description_7",
					's' 
			),
			"ERDescription8" => array (
					"description_8",
					's' 
			),
			"empl_1" => array (
					"empl_1",
					's' 
			), 
			"empl_2" => array (
					"empl_2",
					's' 
			),
			"empl_3" => array (
					"empl_3",
					's' 
			),
			"empl_4" => array (
					"empl_4",
					's' 
			),
			"empl_5" => array (
					"empl_5",
					's' 
			),
			"empl_6" => array (
					"empl_6",
					's' 
			),
			"empl_7" => array (
					"empl_7",
					's' 
			),
			"empl_8" => array (
					"empl_8",
					's' 
			),
			"EMPName" => array (
					"employee_Name",
					's' 
			),
			"employee_Number" => array (
					"employee_Number",
					's' 
			),
			"ent_1" => array (
					"ent_1",
					's' 
			),
			"ent_2" => array (
					"ent_2",
					's' 
			),
			"ent_3" => array (
					"ent_3",
					's' 
			),
			"ent_4" => array (
					"ent_4",
					's' 
			),
			"ent_5" => array (
					"ent_5",
					's' 
			),
			"ent_6" => array (
					"ent_6",
					's' 
			),
			"ent_7" => array (
					"ent_7",
					's' 
			),
			"ent_8" => array (
					"ent_8",
					's' 
			),
			"isApproved" => array (
					"isApproved",
					's' 
			),
			"ERJobNo1" => array (
					"jobNumber_1",
					's' 
			),
			"ERJobNo2" => array (
					"jobNumber_2",
					's' 
			),
			"ERJobNo3" => array (
					"jobNumber_3",
					's' 
			),
			"ERJobNo4" => array (
					"jobNumber_4",
					's' 
			),
			"ERJobNo5" => array (
					"jobNumber_5",
					's' 
			),
			"ERJobNo6" => array (
					"jobNumber_6",
					's' 
			),
			"ERJobNo7" => array (
					"jobNumber_7",
					's' 
			),
			"ERJobNo8" => array (
					"jobNumber_8",
					's' 
			),
			"ERCashAdvance" => array (
					"lessCashAdvance",
					's' 
			),
			"ERPAMilage1" => array (
					"milage_1",
					's' 
			),
			"ERPAMilage2" => array (
					"milage_2",
					's' 
			),
			"ERPAMilage3" => array (
					"milage_3",
					's' 
			),
			"ERPAMilage4" => array (
					"milage_4",
					's' 
			),
			"ERPAMilage5" => array (
					"milage_5",
					's' 
			),
			"ERPAMilage6" => array (
					"milage_6",
					's' 
			),
			"ERPAMilage7" => array (
					"milage_7",
					's' 
			),
			"ERPAMilage8" => array (
					"milage_8",
					's' 
			),
			"misc_1" => array (
					"misc_1",
					's' 
			),
			"misc_2" => array (
					"misc_2",
					's' 
			),
			"misc_3" => array (
					"misc_3",
					's' 
			),
			"misc_4" => array (
					"misc_4",
					's' 
			),
			"misc_5" => array (
					"misc_5",
					's' 
			),
			"misc_6" => array (
					"misc_6",
					's' 
			),
			"misc_7" => array (
					"misc_7",
					's' 
			),
			"misc_8" => array (
					"misc_8",
					's' 
			),
			"office_1" => array (
					"office_1",
					's' 
			),
			"office_2" => array (
					"office_2",
					's' 
			),
			"office_3" => array (
					"office_3",
					's' 
			),
			"office_4" => array (
					"office_4",
					's' 
			),
			"office_5" => array (
					"office_5",
					's' 
			),
			"office_6" => array (
					"office_6",
					's' 
			),
			"office_7" => array (
					"office_7",
					's' 
			),
			"office_8" => array (
					"office_8",
					's' 
			),
			"others1_1" => array (
					"others1_1",
					's' 
			),
			"others1_2" => array (
					"others1_2",
					's' 
			),
			"others1_3" => array (
					"others1_3",
					's' 
			),
			"others1_4" => array (
					"others1_4",
					's' 
			),
			"others1_5" => array (
					"others1_5",
					's' 
			),
			"others1_6" => array (
					"others1_6",
					's' 
			),
			"others1_7" => array (
					"others1_7",
					's' 
			),
			"others1_8" => array (
					"others1_8",
					's' 
			),
			"others2_1" => array (
					"others2_1",
					's' 
			),
			"others2_2" => array (
					"others2_2",
					's' 
			),
			"others2_3" => array (
					"others2_3",
					's' 
			),
			"others2_4" => array (
					"others2_4",
					's' 
			),
			"others2_5" => array (
					"others2_5",
					's' 
			),
			"others2_6" => array (
					"others2_6",
					's' 
			),
			"others2_7" => array (
					"others2_7",
					's' 
			),
			"others2_8" => array (
					"others2_8",
					's' 
			),
			"others3_1" => array (
					"others3_1",
					's' 
			),
			"others3_2" => array (
					"others3_2",
					's' 
			),
			"others3_3" => array (
					"others3_3",
					's' 
			),
			"others3_4" => array (
					"others3_4",
					's' 
			),
			"others3_5" => array (
					"others3_5",
					's' 
			),
			"others3_6" => array (
					"others3_6",
					's' 
			),
			"others3_7" => array (
					"others3_7",
					's' 
			),
			"others3_8" => array (
					"others3_8",
					's' 
			),
			"Project_id" => array (
					"project_id",
					's' 
			),
			"ERPARate1" => array (
					"rate_1",
					's' 
			),
			"ERPARate2" => array (
					"rate_2",
					's' 
			),
			"ERPARate3" => array (
					"rate_3",
					's' 
			),
			"ERPARate4" => array (
					"rate_4",
					's' 
			),
			"ERPARate5" => array (
					"rate_5",
					's' 
			),
			"ERPARate6" => array (
					"rate_6",
					's' 
			),
			"ERPARate7" => array (
					"rate_7",
					's' 
			),
			"ERPARate8" => array (
					"rate_8",
					's' 
			),
			"ERTotal1" => array (
					"totalAmount_1",
					's' 
			),
			"ERTotal2" => array (
					"totalAmount_2",
					's' 
			),
			"ERTotal3" => array (
					"totalAmount_3",
					's' 
			),
			"ERTotal4" => array (
					"totalAmount_4",
					's' 
			),
			"ERTotal5" => array (
					"totalAmount_5",
					's' 
			),
			"ERTotal6" => array (
					"totalAmount_6",
					's' 
			),
			"ERTotal7" => array (
					"totalAmount_7",
					's' 
			),
			"ERTotal8" => array (
					"totalAmount_8",
					's' 
			),
			"ERReimbursement" => array (
					"totalReimbursement",
					's' 
			),
			"travel_1" => array (
					"travel_1",
					's' 
			),
			"travel_2" => array (
					"travel_2",
					's' 
			),
			"travel_3" => array (
					"travel_3",
					's' 
			),
			"travel_4" => array (
					"travel_4",
					's' 
			),
			"travel_5" => array (
					"travel_5",
					's' 
			),
			"travel_6" => array (
					"travel_6",
					's' 
			),
			"travel_7" => array (
					"travel_7",
					's' 
			),
			"travel_8" => array (
					"travel_8",
					's' 
			),
			"userID" => array (
					"userID",
					's' 
			),
			"WeekEnding" => array (
					"week_Ending",
					's' 
			),
			"images_uploaded" => array (
					"images_uploaded",
					's' 
			),
			"signature" => array (
					"signature",
					's' 
			)
	);
	return $expense_report_array;
}