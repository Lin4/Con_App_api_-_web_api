<?php
/**
 * @name         Sync functions for summarySheet1 table
 * @version      1.0
 * @author       lin <lkandasamy@primetgrp.com>
 * @about        Developed for PrimeEng
 * @lastModified 2014.09.04
 */

// Controller function
function sync_summary1($item, $dbCon) {
	if ($stmt = $dbCon->prepare ( "SELECT * FROM `SummarySheet` WHERE `summarySheetNo` = ? " )) {
		$val = $item ['summarySheetNo'];
		$bind_val = &$val;
		
		if (! $stmt->bind_param ( "s", $bind_val )) {
			logDBError ( "error = " . $stmt->error, "sync_summary1:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_summary1:execute" );
			$stmt->close ();
			return false;
		}
		
		if (! $result = $stmt->get_result ()) {
			logDBError ( "error = " . $stmt->error, "sync_summary1:result" );
			$stmt->close ();
			return false;
		}
		
		$row_count = $result->num_rows;
		$stmt->close ();
		
		if ($row_count > 0) {
			// update existing
			return sync_update_summary1 ( $item, $dbCon );
		} else {
			// add new
			return sync_add_summary1 ( $item, $dbCon );
		}
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_summary1:prepare" );
	}
	
	return false;
}

// Update function
function sync_update_summary1($item, $dbCon) {
	$summary1_array = get_summary1_array ();
	
	$values = array ();
	$updateSETArr = array ();
	
	foreach ( $summary1_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			
			$values [] = &$item [$v [0]];
		}
		$updateSETArr [] = "`" . $k . "` = ?";
	}
	
	$val = $item ['summarySheetNo'];
	$values [] = &$val;
	
	$query = "UPDATE `SummarySheet` SET " . implode ( $updateSETArr, ', ' ) . " WHERE `summarySheetNo` = ?";
	if ($stmt = $dbCon->prepare ( $query )) {
		
		$types = array (
				str_repeat ( 's', count ( $values ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				'bind_param' 
		), $bind_values )) {
			logDBError ( "error = " . $stmt->error, "sync_update_summary1:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_update_summary1:execute" );
			$stmt->close ();
			return false;
		}
		
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_update_summary1" );
	}
	
	return false;
}

// Insert function
function sync_add_summary1($item, $dbCon) {
	$summary1_array = get_summary1_array ();
	
	$values = array ();
	$escaped_db_fields = array ();
	
	foreach ( $summary1_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			$values [] = &$item [$v [0]];
		}
		
		$escaped_db_fields [] = "`" . $k . "`";
	}
	
	$placeholders = array_fill ( 0, count ( $escaped_db_fields ), '?' );
	$query = "INSERT INTO `SummarySheet` (" . implode ( ', ', $escaped_db_fields ) . ") VALUES (" . implode ( ', ', $placeholders ) . ")";
	
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
			logDBError ( "error = " . $stmt->error, "sync_add_summary1:execute" );
			$stmt->close ();
			return false;
		}
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_add_summary1:prepare" );
	}
	return false;
}

// Returns an associate array containing JSON to DB fields mapping
function get_summary1_array() {
	$summary1_array = array (
			"project_id" => array (
					"project_id",
					's' 
			),
			"summarySheetNo" => array (
					"summarySheetNo",
					's' 
			),
			"additionalDiscount" => array (
					"additionalDiscount",
					's' 
			),
			"address" => array (
					"address",
					's' 
			),
			"checkedBy" => array (
					"checkedBy",
					's' 
			),
			"constructionOrder" => array (
					"constructionOrder",
					's' 
			),
			"contractor_date" => array (
					"contractor_date",
					's' 
			),
			"contractorPerformingWork" => array (
					"contractorPerformingWork",
					's' 
			),
			"contractorRepresentative" => array (
					"contractorRepresentative",
					's' 
			),
			"contractorSign" => array (
					"contractorSign",
					's' 
			),
			"dailyTotal" => array (
					"dailyTotal",
					's' 
			),
			"date" => array (
					"date",
					's' 
			),
			"dateOfWork" => array (
					"dateOfWork",
					's' 
			),
			"descriptionOfWork" => array (
					"descriptionOfWork",
					's' 
			),
			"eQAmount1" => array (
					"eQAmount1",
					's' 
			),
			"eQAmount1" => array (
					"eQAmount2",
					's' 
			),
			"eQAmount3" => array (
					"eQAmount3",
					's' 
			),
			"eQAmount4" => array (
					"eQAmount4",
					's' 
			),
			"eQAmount5" => array (
					"eQAmount5",
					's' 
			),
			"eQAmount6" => array (
					"eQAmount6",
					's' 
			),
			"eQAmount7" => array (
					"eQAmount7",
					's' 
			),
			"eQAmount8" => array (
					"eQAmount8",
					's' 
			),
			"eQIdleActive1" => array (
					"eQIdleActive1",
					's' 
			),
			"eQIdleActive2" => array (
					"eQIdleActive2",
					's' 
			),
			"eQIdleActive3" => array (
					"eQIdleActive3",
					's' 
			),
			"eQIdleActive4" => array (
					"eQIdleActive4",
					's' 
			),
			"eQIdleActive5" => array (
					"eQIdleActive5",
					's' 
			),
			"eQIdleActive6" => array (
					"eQIdleActive6",
					's' 
			),
			"eQIdleActive7" => array (
					"eQIdleActive7",
					's' 
			),
			"eQIdleActive8" => array (
					"eQIdleActive8",
					's' 
			),
			"eQNo1" => array (
					"eQNo1",
					's' 
			),
			"eQNo2" => array (
					"eQNo2",
					's' 
			),
			"eQNo3" => array (
					"eQNo3",
					's' 
			),
			"eQNo4" => array (
					"eQNo4",
					's' 
			),
			"eQNo5" => array (
					"eQNo5",
					's' 
			),
			"eQNo6" => array (
					"eQNo6",
					's' 
			),
			"eQNo7" => array (
					"eQNo7",
					's' 
			),
			"eQNo8" => array (
					"eQNo8",
					's' 
			),
			"eQRAte1" => array (
					"eQRAte1",
					's' 
			),
			"eQRAte2" => array (
					"eQRAte2",
					's' 
			),
			"eQRAte3" => array (
					"eQRAte3",
					's' 
			),
			"eQRAte4" => array (
					"eQRAte4",
					's' 
			),
			"eQRAte5" => array (
					"eQRAte5",
					's' 
			),
			"eQRAte6" => array (
					"eQRAte6",
					's' 
			),
			"eQRAte7" => array (
					"eQRAte7",
					's' 
			),
			"eQRAte8" => array (
					"eQRAte8",
					's' 
			),
			"eQSizeandClass1" => array (
					"eQSizeandClass1",
					's' 
			),
			"eQSizeandClass2" => array (
					"eQSizeandClass2",
					's' 
			),
			"eQSizeandClass3" => array (
					"eQSizeandClass3",
					's' 
			),
			"eQSizeandClass4" => array (
					"eQSizeandClass4",
					's' 
			),
			"eQSizeandClass5" => array (
					"eQSizeandClass5",
					's' 
			),
			"eQSizeandClass6" => array (
					"eQSizeandClass6",
					's' 
			),
			"eQSizeandClass7" => array (
					"eQSizeandClass7",
					's' 
			),
			"eQSizeandClass8" => array (
					"eQSizeandClass8",
					's' 
			),
			"eQTotalHours1" => array (
					"eQTotalHours1",
					's' 
			),
			"eQTotalHours2" => array (
					"eQTotalHours2",
					's' 
			),
			"eQTotalHours3" => array (
					"eQTotalHours3",
					's' 
			),
			"eQTotalHours4" => array (
					"eQTotalHours4",
					's' 
			),
			"eQTotalHours5" => array (
					"eQTotalHours5",
					's' 
			),
			"eQTotalHours6" => array (
					"eQTotalHours6",
					's' 
			),
			"eQTotalHours7" => array (
					"eQTotalHours7",
					's' 
			),
			"eQTotalHours8" => array (
					"eQTotalHours8",
					's' 
			),
			"equipment_total" => array (
					"equipment_total",
					's' 
			),
			"federalAidNumber" => array (
					"federalAidNumber",
					's' 
			),
			"healWelAndPension" => array (
					"healWelAndPension",
					's' 
			),
			"insAndTaxesOnItem1" => array (
					"insAndTaxesOnItem1",
					's' 
			),
			"inspector" => array (
					"inspector",
					's' 
			),
			"inspector_date" => array (
					"inspector_date",
					's' 
			),
			"inspectorSign" => array (
					"inspectorSign",
					's' 
			),
			"isApproved" => array (
					"isApproved",
					's' 
			),
			"itemDescount20per" => array (
					"itemDescount20per",
					's' 
			),
			"lAAmount1" => array (
					"lAAmount1",
					's' 
			),
			"lAAmount2" => array (
					"lAAmount2",
					's' 
			),
			"lAAmount3" => array (
					"lAAmount3",
					's' 
			),
			"lAAmount4" => array (
					"lAAmount4",
					's' 
			),
			"lAAmount5" => array (
					"lAAmount5",
					's' 
			),
			"lAAmount6" => array (
					"lAAmount6",
					's' 
			),
			"lAAmount7" => array (
					"lAAmount7",
					's' 
			),
			"lAAmount8" => array (
					"lAAmount8",
					's' 
			),
			"lAClass1" => array (
					"lAClass1",
					's' 
			),
			"lAClass2" => array (
					"lAClass2",
					's' 
			),
			"lAClass3" => array (
					"lAClass3",
					's' 
			),
			"lAClass4" => array (
					"lAClass4",
					's' 
			),
			"lAClass5" => array (
					"lAClass5",
					's' 
			),
			"lAClass6" => array (
					"lAClass6",
					's' 
			),
			"lAClass7" => array (
					"lAClass7",
					's' 
			),
			"lAClass8" => array (
					"lAClass8",
					's' 
			),
			"lANo1" => array (
					"lANo1",
					's' 
			),
			"lANo2" => array (
					"lANo2",
					's' 
			),
			"lANo3" => array (
					"lANo3",
					's' 
			),
			"lANo4" => array (
					"lANo4",
					's' 
			),
			"lANo5" => array (
					"lANo5",
					's' 
			),
			"lANo6" => array (
					"lANo6",
					's' 
			),
			"lANo7" => array (
					"lANo7",
					's' 
			),
			"lANo8" => array (
					"lANo8",
					's' 
			),
			"lARate1" => array (
					"lARate1",
					's' 
			),
			"lARate2" => array (
					"lARate2",
					's' 
			),
			"lARate3" => array (
					"lARate3",
					's' 
			),
			"lARate4" => array (
					"lARate4",
					's' 
			),
			"lARate5" => array (
					"lARate5",
					's' 
			),
			"lARate6" => array (
					"lARate6",
					's' 
			),
			"lARate7" => array (
					"lARate7",
					's' 
			),
			"lARate8" => array (
					"lARate8",
					's' 
			),
			"lATotalHours1" => array (
					"lATotalHours1",
					's' 
			),
			"lATotalHours2" => array (
					"lATotalHours2",
					's' 
			),
			"lATotalHours3" => array (
					"lATotalHours3",
					's' 
			),
			"lATotalHours4" => array (
					"lATotalHours4",
					's' 
			),
			"lATotalHours5" => array (
					"lATotalHours5",
					's' 
			),
			"lATotalHours6" => array (
					"lATotalHours6",
					's' 
			),
			"lATotalHours7" => array (
					"lATotalHours7",
					's' 
			),
			"lATotalHours8" => array (
					"lATotalHours8",
					's' 
			),
			"lessDiscount" => array (
					"lessDiscount",
					's' 
			),
			"material_total1" => array (
					"material_total1",
					's' 
			),
			"material_total2" => array (
					"material_total2",
					's' 
			),
			"material_total3" => array (
					"material_total3",
					's' 
			),
			"mEAmount1" => array (
					"mEAmount1",
					's' 
			),
			"mEAmount2" => array (
					"mEAmount2",
					's' 
			),
			"mEAmount3" => array (
					"mEAmount3",
					's' 
			),
			"mEAmount4" => array (
					"mEAmount4",
					's' 
			),
			"mEAmount5" => array (
					"mEAmount5",
					's' 
			),
			"mEAmount6" => array (
					"mEAmount6",
					's' 
			),
			"mEAmount7" => array (
					"mEAmount7",
					's' 
			),
			"mEAmount8" => array (
					"mEAmount8",
					's' 
			),
			"mEDescription1" => array (
					"mEDescription1",
					's' 
			),
			"mEDescription2" => array (
					"mEDescription2",
					's' 
			),
			"mEDescription3" => array (
					"mEDescription3",
					's' 
			),
			"mEDescription4" => array (
					"mEDescription4",
					's' 
			),
			"mEDescription5" => array (
					"mEDescription5",
					's' 
			),
			"mEDescription6" => array (
					"mEDescription6",
					's' 
			),
			"mEDescription7" => array (
					"mEDescription7",
					's' 
			),
			"mEDescription8" => array (
					"mEDescription8",
					's' 
			),
			"mEQuantity1" => array (
					"mEQuantity1",
					's' 
			),
			"mEQuantity2" => array (
					"mEQuantity2",
					's' 
			),
			"mEQuantity3" => array (
					"mEQuantity3",
					's' 
			),
			"mEQuantity4" => array (
					"mEQuantity4",
					's' 
			),
			"mEQuantity5" => array (
					"mEQuantity5",
					's' 
			),
			"mEQuantity6" => array (
					"mEQuantity6",
					's' 
			),
			"mEQuantity7" => array (
					"mEQuantity7",
					's' 
			),
			"mEQuantity8" => array (
					"mEQuantity8",
					's' 
			),
			"mEUnitPrice1" => array (
					"mEUnitPrice1",
					's' 
			),
			"mEUnitPrice2" => array (
					"mEUnitPrice2",
					's' 
			),
			"mEUnitPrice3" => array (
					"mEUnitPrice3",
					's' 
			),
			"mEUnitPrice4" => array (
					"mEUnitPrice4",
					's' 
			),
			"mEUnitPrice5" => array (
					"mEUnitPrice5",
					's' 
			),
			"mEUnitPrice6" => array (
					"mEUnitPrice6",
					's' 
			),
			"mEUnitPrice7" => array (
					"mEUnitPrice7",
					's' 
			),
			"mEUnitPrice8" => array (
					"mEUnitPrice8",
					's' 
			),
			"printedName" => array (
					"printedName",
					's' 
			),
			"reportNumber" => array (
					"reportNumber",
					's' 
			),
			"total_to_date" => array (
					"total_to_date",
					's' 
			),
			"totalItems1through4" => array (
					"totalItems1through4",
					's' 
			),
			"totalLabor" => array (
					"totalLabor",
					's' 
			),
			"userID" => array (
					"userID",
					's' 
			),
			"images_uploaded" => array (
					"images_uploaded",
					's' 
			)
	);
	
	return $summary1_array;
}
