<?php
/**
 * @name         Sync functions for assign_project table
 * @version      1.0
 * @author       lin <lkandasamy@primetgrp.com>
 * @about        Developed for PrimeEng
 * @lastModified 2014.09.04
 */

// Controller function
function sync_assign_project($item, $dbCon) {
	if ($stmt = $dbCon->prepare ( "SELECT * FROM `assign_project` WHERE `username` = ? AND `projectid` = ?" )) {
		$username = $item ['username'];
		$projectid = $item ['projectid'];
		
		if (! $stmt->bind_param ( "ss", $username, $projectid )) {
			logDBError ( "error = " . $stmt->error, "sync_assign_project:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_assign_project:execute" );
			$stmt->close ();
			return false;
		}
		
		if (! $result = $stmt->get_result ()) {
			logDBError ( "error = " . $stmt->error, "sync_assign_project:result" );
			$stmt->close ();
			return false;
		}
		
		$row_count = $result->num_rows;
		$stmt->close ();
		
		if ($row_count > 0) {
			// update existing - nothing to do in assign_project
			return true;
		} else {
			// add new
			return sync_add_assign_project ( $item, $dbCon );
		}
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_assign_project:prepare" );
	}
	
	return false;
}

// Update function
function sync_update_assign_project($item, $dbCon) {
}

// Insert function
function sync_add_assign_project($item, $dbCon) {
	$assign_project_array = get_assign_project_array ();
	
	$values = array ();
	$escaped_db_fields = array ();
	
	foreach ( $assign_project_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			if ($k === "assign_date") {
				$date = date ( "Y-m-d" );
				$values [] = &$date;
			} else {
				$val = "";
				$values [] = &$val;
			}
		} else {
			$values [] = &$item [$v [0]];
		}
		
		$escaped_db_fields [] = "`" . $k . "`";
	}
	
	$placeholders = array_fill ( 0, count ( $escaped_db_fields ), '?' );
	$query = "INSERT INTO `assign_project` (" . implode ( ', ', $escaped_db_fields ) . ") VALUES (" . implode ( ', ', $placeholders ) . ")";
	
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
			logDBError ( "error = " . $stmt->error, "sync_add_compliance_form:bind" );
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_add_assign_project:execute" );
			$stmt->close ();
			return false;
		}
		
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_add_assign_project:prepare" );
	}
	return false;
}

// Returns an associate array containing JSON to DB fields mapping
function get_assign_project_array() {
	$assign_project_array = array (
			"username" => array (
					"username",
					's' 
			),
			"projectid" => array (
					"projectid",
					's' 
			),
			"assign_date" => array (
					"assign_date",
					's' 
			) 
	);
	return $assign_project_array;
}
