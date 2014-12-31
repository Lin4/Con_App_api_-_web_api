<?php
/**
 * @name         Database functions for API
 * @version      1.0
 * @author       lin <lkandasamy@primetgrp.com>
 * @about        Developed for PrimeEng
 * @lastModified 2014.09.04
 */
require_once dirname ( __FILE__ ) . '/conf.php';

// Returns an array containing database tables
function get_database_tables() {
	$table_array = array (
			"assign_project",
			"complianceForm",
			"dailyInspectionForm",
			"dailyInspection_item",			
			"expenseReportModel",
			"nonComplianceForm",
			"projects",			
			"quantityEstimateForm",
			"SummarySheet",
			"users" 
	);
	return $table_array;
}

// Return a mysqli database connection object
function get_database_connection() {
	global $conf;
	
	$dbCon = new mysqli ( $conf ['db_host'], $conf ['db_user'], $conf ['db_pass'], $conf ['db_name'], $conf ['db_port'] );
	// check connection
	if ($dbCon->connect_errno) {
		logDBError ( "Error connecting to MySQL database. Error = " . $mysqli->connect_error, "dbConnect" );
		return false;
	}
	return $dbCon;
}

// Convert given array to a json encoded string
function toJson($data) {
	if (is_array ( $data )) {
		return json_encode ( $data );
	}
	return false;
}

// Logs given message to a file with a tag identifier
function logDBError($msg, $tag) {
	file_put_contents ( dirname ( __FILE__ ) . '/db_errors.log', date("Y-m-d H:i:s") . " :: " .$tag . " :: " . $msg . PHP_EOL, FILE_APPEND );
}
