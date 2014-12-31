<?php
/**
 * @name         Synchronization Controller for API
 * @version      1.0
 * @author       lin <lkandasamy@primetgrp.com>
 * @about        Developed for PrimeEng
 * @lastModified 2014.09.21
 */
require_once dirname ( __FILE__ ) . '/sync/assign_project.php';
require_once dirname ( __FILE__ ) . '/sync/compliance_form.php';
require_once dirname ( __FILE__ ) . '/sync/daily_inspection_form.php';
require_once dirname ( __FILE__ ) . '/sync/daily_inspection_item.php';
require_once dirname ( __FILE__ ) . '/sync/expense_report.php';
require_once dirname ( __FILE__ ) . '/sync/image.php';
require_once dirname ( __FILE__ ) . '/sync/non_compliance.php';
require_once dirname ( __FILE__ ) . '/sync/project.php';
require_once dirname ( __FILE__ ) . '/sync/quantity_estimate_form.php';
require_once dirname ( __FILE__ ) . '/sync/summary1.php';
//require_once dirname ( __FILE__ ) . '/sync/daily_Inspection_Item_Report.php';


// Send complete json back to the client
function syncPull($app, &$response) {
	if (! $dbCon = get_database_connection ()) {
		return false;
	}
	$table_array = get_database_tables ();
	
	foreach ( $table_array as $table_item ) {
		$result = $dbCon->query ( "SELECT * FROM `$table_item`;" );
		
		if ($result) {
			while ( $row = $result->fetch_assoc () ) {
				$response [$table_item] [] = $row;
			}
		} else {
			logDBError ( "error = " . $dbCon->error, "syncPull" );
			$dbCon->close ();
			return false;
		}
	}
	$dbCon->close ();
	return true;
}

// Store table array to database
function saveRecords($json_arr, $dbCon, &$response) {
	foreach ( $json_arr ['Projects'] as $proj_item ) {
		if (! sync_project ( $proj_item, $dbCon )) {
			$response ['error'] = array (
					'message' => "Failed to sync Projects records" 
			);
			return false;
		}
	}
	
	/*
	 * foreach ( $json_arr ['Assign_project'] as $assp_item ) { if (! sync_assign_project ( $assp_item, $dbCon )) { $response ['error'] = array ( 'message' => "Failed to sync Assign_project records" ); return false; } }
	 */
	
	foreach ( $json_arr ['ComplianceForm'] as $compliance_item ) {
		if (! sync_compliance_form ( $compliance_item, $dbCon )) {
			$response ['error'] = array (
					'message' => "Failed to sync ComplianceForm records" 
			);
			return false;
		}
	}
	
	foreach ( $json_arr ['DailyInspectionForm'] as $daily_inspection_item ) {
		if (! sync_daily_inspection_form ( $daily_inspection_item, $dbCon )) {
			$response ['error'] = array (
					'message' => "Failed to sync DailyInspectionForm records" 
			);
			return false;
		}
	}
	
	
	foreach ( $json_arr ['DailyInspection_item'] as $daily_inspection ) { 
		if (! sync_daily_inspection_item ( $daily_inspection, $dbCon )) { 
			$response ['error'] = array (
					'message' => "Failed to sync DailyInspectionItem records" 
			); 
			return false;
		} 
	} 

	/*		
	*foreach ( $json_arr ['Expensedata'] as $expensedata_item ) { if (! sync_expensedata ( $expensedata_item, $dbCon )) { $response ['error'] = array ( 'message' => "Failed to sync Expensedata records" ); return false; } }
	*/	
	foreach ( $json_arr ['ExpenseReportModel'] as $expensereport_item ) {
		if (! sync_expense_report ( $expensereport_item, $dbCon )) {
			$response ['error'] = array (
					'message' => "Failed to sync ExpenseReportModel records" 
			);
			return false;
		}
	}
	
	foreach ( $json_arr ['NonComplianceForm'] as $non_compliance_item ) {
		if (! sync_non_compliance_form ( $non_compliance_item, $dbCon )) {
			$response ['error'] = array (
					'message' => "Failed to sync NonComplianceForm records" 
			);
			return false;
		}
	}
	
	/*
	 * foreach ( $json_arr ['Image'] as $img_item ) { if (! sync_image ( $img_item, $dbCon )) { $response ['error'] = array ( 'message' => "Failed to sync Image records" ); return false; } } foreach ( $json_arr ['QuantitySummaryDetails'] as $qty_summary_details_item ) { if (! sync_quantity_summary_details ( $qty_summary_details_item, $dbCon )) { $response ['error'] = array ( 'message' => "Failed to sync QuantitySummaryDetails records" ); return false; } } foreach ( $json_arr ['QuantitySummaryItems'] as $qty_summary_item ) { if (! sync_quantity_summary_items ( $qty_summary_item, $dbCon )) { $response ['error'] = array ( 'message' => "Failed to sync QuantitySummaryItems records" ); return false; } }
	 */
	
	foreach ( $json_arr ['QuantityEstimateForm'] as $qty_estimate_item ) {
		if (! sync_qty_estimate_form ( $qty_estimate_item, $dbCon )) {
			$response ['error'] = array (
					'message' => "Failed to sync QuantityEstimateForm records" 
			);
			return false;
		}
	}
	
	foreach ( $json_arr ['SummarySheet'] as $summary1_item ) {
		if (! sync_summary1 ( $summary1_item, $dbCon )) {
			$response ['error'] = array (
					'message' => "Failed to sync SummarySheet records" 
			);
			return false;
		}
	}
	
	return true;
}

// Get complete json from the POST data and store in database
function syncPush($app, &$response) {
	$data = $app->request ()->getBody ();
	
	if (empty ( $data )) {
		return false;
	}
	
	if (! $json_arr = json_decode ( $data, true )) {
		return false;
	}
	
	if (! $dbCon = get_database_connection ()) {
		return false;
	}
	
	try {
		// Start synchronization transaction
		// Only supported in PHP >= 5.5
		// $dbCon->begin_transaction ();
		
		$dbCon->autocommit ( FALSE );
		$saveStatus = saveRecords ( $json_arr, $dbCon, $response );
		
		// commit the queries only if all queries were successful
		if ($saveStatus && $dbCon->commit ()) {
			$dbCon->close ();
			return true;
		}
	} catch ( Exception $e ) {
		// rollback the transaction if anything goes wrong
		$dbCon->rollback ();
	}
	
	// Only supported in PHP >= 5.5
	/*
	 * finally { $dbCon->close (); }
	 */
	if ($dbCon) {
		$dbCon->close ();
	}
	
	return false;
}

// Get images sent from the POST data and store in database
function syncPushImage($app, &$response) {
	global $conf;
	
	$imgs = array ();
	$files = $_FILES ['userfile'];
	$cnt = count ( $files );
	$status = true;
	
	if ($cnt > 0) {
		
		if (empty ( $files ['error'] )) {
			
			$name = $files ['name'];
			$target = $conf ['image_path'] . '/' . $name;
			
			if (move_uploaded_file ( $files ['tmp_name'], $target ) === true) {
				$imgs [] = array (
						'url' => '/images/' . $name,
						'name' => $files ['name'] 
				);
			} else {
				$status = false;
			}
		} else {
			logDBError ( "error = " . $files ['error'], "sync_pushImage:file_error" );
			$status = false;
		}
	}
	
	return $status;
}

// Retreive available images from the request body and send the missing images name list that are in the server back to the client
function syncPullImages($app, &$response) {
	global $conf;
	
	$data = $app->request ()->getBody ();
	$imgResponse = array ();
	$imgRequest = array ();
	
	if (empty ( $data )) {
		return false;
	}
	
	if (! $json_arr = json_decode ( $data, true )) {
		return false;
	}
	
	foreach ( $json_arr ['Image'] as $imgInRequest ) {
		$imgRequest [] = $imgInRequest ['imgName'];
	}
	
	$filesInServer = array_diff ( scandir ( $conf ['image_path'] ), array (
			'.',
			'..' 
	) );
	
	foreach ( $filesInServer as $imgStored ) {
		
		if (! in_array ( $imgStored, $imgRequest )) {
			$imgResponse [] = $imgStored;
		}
	}
	
	$response ['Image'] = $imgResponse;
	
	return true;
}
