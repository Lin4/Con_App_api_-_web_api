<?php
/**
 * @name         Sync functions for dailyInspectionForm table
 * @version      1.0
 * @author       lin <lkandasamy@primetgrp.com>
 * @about        Developed for PrimeEng
 * @lastModified 2014.09.04
 */

// Controller function
function sync_daily_inspection_form($item, $dbCon) {
	if ($stmt = $dbCon->prepare ( "SELECT * FROM `dailyInspectionForm` WHERE `dailyInspecNo` = ? " )) {
		$val = $item ['dailyInspecNo'];
		$bind_val = &$val;
		
		if (! $stmt->bind_param ( "s", $bind_val )) {
			logDBError ( "error = " . $stmt->error, "sync_daily_inspection_form:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_daily_inspection_form:execute" );
			$stmt->close ();
			return false;
		}
		
		if (! $result = $stmt->get_result ()) {
			logDBError ( "error = " . $stmt->error, "sync_daily_inspection_form:result" );
			$stmt->close ();
			return false;
		}
		
		$row_count = $result->num_rows;
		$stmt->close ();
		
		if ($row_count > 0) {
			// remove existing items
			//if (! sync_remove_daily_inspection_items ( $item, $dbCon )) {
			//	logDBError ( "Failed to remove existing daily inspection items", "sync_daily_inspection_form:remove" );
			//	return false;
			//}
			
			// update existing
			return sync_update_daily_inspection_form ( $item, $dbCon );
		} else {
			// add new
			return sync_add_daily_inspection_form ( $item, $dbCon );
		}
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_daily_inspection_form:prepare" );
	}
	
	return false;
}

// Update function
function sync_update_daily_inspection_form($item, $dbCon) {
	$daily_inspection_form_array = get_daily_inspection_form_array ();
	
	$values = array ();
	$updateSETArr = array ();
	
	foreach ( $daily_inspection_form_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			
			$values [] = &$item [$v [0]];
		}
		$updateSETArr [] = "`" . $k . "` = ?";
	}
	
	$val = $item ['dailyInspecNo'];
	$values [] = &$val;
	
	$query = "UPDATE `dailyInspectionForm` SET " . implode ( $updateSETArr, ', ' ) . " WHERE `dailyInspecNo` = ?";
	if ($stmt = $dbCon->prepare ( $query )) {
		
		$types = array (
				str_repeat ( 's', count ( $values ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				'bind_param' 
		), $bind_values )) {
			logDBError ( "error = " . $stmt->error, "sync_update_daily_inspection_form:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_update_daily_inspection_form:execute" );
			$stmt->close ();
			return false;
		}
		
		$stmt->close ();
		return true;
		//return sync_add_daily_inspections_items ( $item, $dbCon );
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_update_daily_inspection_form:prepare" );
	}
	
	return false;
}

// Insert function
function sync_add_daily_inspection_form($item, $dbCon) {
	$daily_inspection_form_array = get_daily_inspection_form_array ();
	
	$values = array ();
	$escaped_db_fields = array ();
	
	foreach ( $daily_inspection_form_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			$values [] = &$item [$v [0]];
		}
		
		$escaped_db_fields [] = "`" . $k . "`";
	}
	
	$placeholders = array_fill ( 0, count ( $escaped_db_fields ), '?' );
	$query = "INSERT INTO `dailyInspectionForm` (" . implode ( ', ', $escaped_db_fields ) . ") VALUES (" . implode ( ', ', $placeholders ) . ")";
	
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
			logDBError ( "error = " . $stmt->error, "sync_add_daily_inspection_form:bind" );
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_add_daily_inspection_form:execute" );
			$stmt->close ();
			return false;
		}
		$stmt->close ();
		return true;
		//return sync_add_daily_inspections_items ( $item, $dbCon );
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_add_daily_inspection_form:prepare" );
	}
	return false;
}

// Remove daily inspection items records for a given daily inspection form
/*
function sync_remove_daily_inspection_items($item, $dbCon) {
	$values = array ();
	$val = $item ['dailyInspecNo'];
	$values [] = &$val;
	
	$query = "DELETE FROM `dailyInspection_item` WHERE `dailyInspecNo` = ?";
	
	if ($stmt = $dbCon->prepare ( $query )) {
		$types = array (
				str_repeat ( 's', count ( $values ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				"bind_param" 
		), $bind_values )) {
			$stmt->close ();
			logDBError ( "error = " . $stmt->error, "sync_remove_daily_inspection_items:bind" );
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_remove_daily_inspection_items:execute" );
			$stmt->close ();
			return false;
		}
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_remove_daily_inspection_items:prepare" );
	}
	return false;
}*/

// Add daily inspection items
/*function sync_add_daily_inspections_items($item, $dbCon) {
	$values = array ();
	
	for($i = 1; $i <= 5; $i ++) {
		
		$item_no = "i_No$i";
		$item_desc = "i_Desc$i";
		$item_qty = "i_QTY$i";
		
		if (! empty ( $item [$item_no] ) && ! empty ( $item [$item_qty] )) {
			$values = array (
					"inspectionID" => $item ['inspectionID'],
					"no" => $item [$item_no],
					"desc" => $item [$item_desc],
					"qty" => $item [$item_qty],
					"date" => $item ["date"] 
			);
			
			if (! sync_daily_inspection_item ( $values, $dbCon )) {
				return false;
			}
		}
	}
	return true;
}*/

// Returns an associate array containing DB fields to JSON mapping
function get_daily_inspection_form_array() {
	$daily_inspection_form_array = array (
			"report_No" => array (
					"reportNumber",
					's' 
			),
			"address" => array (
					"address",
					's' 
			),
			"CEAJobNo" => array (
					"ceaJobNumber",
					's' 
			),
			"CompetentPerson" => array (
					"competentPerson",
					's' 
			),
			"contractNumber" => array (
					"contractNumber",
					's' 
			),
			"Contractor" => array (
					"contractor",
					's' 
			),
			"dailyInspecNo" => array (
					"dailyInspecNo",
					's' 
			),
			"Date" => array (
					"date",
					's' 
			),
			"calendar_Days_Used" => array (
					"daysUsed",
					's' 
			),
			"WDODepartmentOrCompany1" => array (
					"dept_or_comp1",
					's' 
			),
			"WDODepartmentOrCompany2" => array (
					"dept_or_comp2",
					's' 
			),
			"WDODepartmentOrCompany3" => array (
					"dept_or_comp3",
					's' 
			),
			"WDODepartmentOrCompany4" => array (
					"dept_or_comp4",
					's' 
			),
			"WDODepartmentOrCompany5" => array (
					"dept_or_comp5",
					's' 
			),
			"WDODepartmentOrCompany6" => array (
					"dept_or_comp6",
					's' 
			),			
			"WDODepartmentOrCompany7" => array (
					"dept_or_comp7",
					's' 
			),
			"WDODepartmentOrCompany8" => array (
					"dept_or_comp8",
					's' 
			),
			"WDODescriptionOfWork1" => array (
					"description_of_work1",
					's' 
			),
			"WDODescriptionOfWork2" => array (
					"description_of_work2",
					's' 
			),
			"WDODescriptionOfWork3" => array (
					"description_of_work3",
					's' 
			),
			"WDODescriptionOfWork4" => array (
					"description_of_work4",
					's' 
			),
			"WDODescriptionOfWork5" => array (
					"description_of_work5",
					's' 
			),
			"WDODescriptionOfWork6" => array (
					"description_of_work6",
					's' 
			),
			"WDODescriptionOfWork7" => array (
					"description_of_work7",
					's' 
			),
			"WDODescriptionOfWork8" => array (
					"description_of_work8",
					's' 
			),
			"I_QTY1" => array (
					"equipment_quantity1",
					's' 
			),
			"I_QTY2" => array (
					"equipment_quantity2",
					's' 
			),
			"I_QTY3" => array (
					"equipment_quantity3",
					's' 
			),
			"I_QTY4" => array (
					"equipment_quantity4",
					's' 
			),
			"I_QTY5" => array (
					"equipment_quantity5",
					's' 
			),
			"I_QTY6" => array (
					"equipment_quantity6",
					's' 
			),
			"I_QTY7" => array (
					"equipment_quantity7",
					's' 
			),
			"I_QTY8" => array (
					"equipment_quantity8",
					's' 
			),
			"I_Desc1" => array (
					"equipment_title1",
					's' 
			),
			"I_Desc2" => array (
					"equipment_title2",
					's' 
			),
			"I_Desc3" => array (
					"equipment_title3",
					's' 
			),
			"I_Desc4" => array (
					"equipment_title4",
					's' 
			),
			"I_Desc5" => array (
					"equipment_title5",
					's' 
			),
			"I_Desc6" => array (
					"equipment_title6",
					's' 
			),
			"I_Desc7" => array (
					"equipment_title7",
					's' 
			),
			"I_Desc8" => array (
					"equipment_title8",
					's' 
			),
			"ContractorsHoursOfWork" => array (
					"hoursOfWork",
					's' 
			),
			"IFName1" => array (
					"iFName1",
					's' 
			),
			"IFName2" => array (
					"iFName2",
					's' 
			),
			"IFName3" => array (
					"iFName3",
					's' 
			),
			"IFName4" => array (
					"iFName4",
					's' 
			),
			"IFTitle1" => array (
					"iFTitle1",
					's' 
			),
			"IFTitle2" => array (
					"iFTitle2",
					's' 
			),
			"IFTitle3" => array (
					"iFTitle3",
					's' 
			),
			"IFTitle4" => array (
					"iFTitle4",
					's' 
			),
			"isApproved" => array (
					"isApproved",
					's' 
			),
			"labor_qty_1" => array (
					"labore_quantity1",
					's' 
			),
			"labor_qty_2" => array (
					"labore_quantity2",
					's' 
			),
			"labor_qty_3" => array (
					"labore_quantity3",
					's' 
			),
			"labor_qty_4" => array (
					"labore_quantity4",
					's' 
			),
			"labor_qty_5" => array (
					"labore_quantity5",
					's' 
			),
			"labor_qty_6" => array (
					"labore_quantity6",
					's' 
			),
			"labor_qty_7" => array (
					"labore_quantity7",
					's' 
			),
			"labor_qty_8" => array (
					"labore_quantity8",
					's' 
			),
			"labor_1" => array (
					"labore_title1",
					's' 
			),
			"labor_2" => array (
					"labore_title2",
					's' 
			),
			"labor_3" => array (
					"labore_title3",
					's' 
			),
			"labor_4" => array (
					"labore_title4",
					's' 
			),
			"labor_5" => array (
					"labore_title5",
					's' 
			),
			"labor_6" => array (
					"labore_title6",
					's' 
			),
			"labor_7" => array (
					"labore_title7",
					's' 
			),
			"labor_8" => array (
					"labore_title8",
					's' 
			),
			"no_of_pages_report" => array (
					"no_of_pages_report",
					's' 
			),
			"original_Calendar_Days" => array (
					"org_calander_days",
					's' 
			),
			"OVJName1" => array (
					"oVJName1",
					's' 
			),
			"OVJName2" => array (
					"oVJName2",
					's' 
			),
			"OVJName3" => array (
					"oVJName3",
					's' 
			),
			"OVJName4" => array (
					"oVJName4",
					's' 
			),
			"OVJTitle1" => array (
					"oVJTitle1",
					's' 
			),
			"OVJTitle2" => array (
					"oVJTitle2",
					's' 
			),
			"OVJTitle3" => array (
					"oVJTitle3",
					's' 
			), 
			"OVJTitle4" => array (
					"oVJTitle4",
					's' 
			),
			"primeJobNumber" => array (
					"primeJobNumber",
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
			"reportBy" => array (
					"reportBy",
					's' 
			),
			"reviewedBy" => array (
					"reviewedBy",
					's' 
			),
			"Town_City" => array (
					"town_city",
					's' 
			),
			"userID" => array (
					"userID",
					's' 
			),
			"weather" => array (
					"weather",
					's' 
			),
			"WorkDoneBy" => array (
					"workDoneBy",
					's' 
			),
			"signature" => array (
					"signature",
					's' 
			),
			"images_uploaded" => array (
					"images_uploaded",
					's' 
			)


	);
	
	return $daily_inspection_form_array;
}
