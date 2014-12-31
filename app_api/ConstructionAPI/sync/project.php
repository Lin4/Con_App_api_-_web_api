<?php
/**
 * @name         Sync functions for projects table
 * @version      1.0
 * @author       lin <lkandasamy@primetgrp.com>
 * @about        Developed for PrimeEng
 * @lastModified 2014.09.04
 */

// Controller function
function sync_project($item, $dbCon) {
	if ($stmt = $dbCon->prepare ( "SELECT * FROM `projects` WHERE `projecct_id` = ? " )) {
		$val = $item ['projecct_id'];
		$bind_val = &$val;
		
		if (! $stmt->bind_param ( "s", $bind_val )) {
			logDBError ( "error = " . $stmt->error, "sync_project:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_project:execute" );
			$stmt->close ();
			return false;
		}
		
		if (! $result = $stmt->get_result ()) {
			logDBError ( "error = " . $stmt->error, "sync_project:result" );
			$stmt->close ();
			return false;
		}
		
		$row_count = $result->num_rows;
		$stmt->close ();
		
		if ($row_count > 0) {
			// first delete existing assign proj stuff
			if (! sync_remove_assign_projects ( $item, $dbCon )) {
				logDBError ( "Failed remove existing assign_project items", "sync_project:remove" );
				return false;
			}
			// update existing
			return sync_update_project ( $item, $dbCon );
		} else {
			// add new
			return sync_add_project ( $item, $dbCon );
		}
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_project:prepare" );
	}
	
	return false;
}

// Update function
function sync_update_project($item, $dbCon) {
	$project_array = get_project_array ();
	
	$values = array ();
	$updateSETArr = array ();
	
	foreach ( $project_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			
			$values [] = &$item [$v [0]];
		}
		$updateSETArr [] = "`" . $k . "` = ?";
	}
	
	$val = $item ['projecct_id'];
	$values [] = &$val;
	
	$query = "UPDATE `projects` SET " . implode ( $updateSETArr, ', ' ) . " WHERE `projecct_id` = ?";
	if ($stmt = $dbCon->prepare ( $query )) {
		
		$types = array (
				str_repeat ( 's', count ( $values ) ) 
		);
		$bind_values = array_merge ( $types, $values );
		
		if (! call_user_func_array ( array (
				$stmt,
				'bind_param' 
		), $bind_values )) {
			logDBError ( "error = " . $stmt->error, "sync_update_project:bind" );
			$stmt->close ();
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_update_project:execute" );
			$stmt->close ();
			return false;
		}
		
		$stmt->close ();
		return sync_add_assign_projects ( $item, $dbCon );
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_update_project" );
	}
	
	return false;
}

// Insert function
function sync_add_project($item, $dbCon) {
	$project_array = get_project_array ();
	
	$values = array ();
	$escaped_db_fields = array ();
	
	foreach ( $project_array as $k => $v ) {
		if (empty ( $item [$v [0]] )) {
			$val = "";
			$values [] = &$val;
		} else {
			$values [] = &$item [$v [0]];
		}
		
		$escaped_db_fields [] = "`" . $k . "`";
	}
	
	$placeholders = array_fill ( 0, count ( $escaped_db_fields ), '?' );
	$query = "INSERT INTO `projects` (" . implode ( ', ', $escaped_db_fields ) . ") VALUES (" . implode ( ', ', $placeholders ) . ")";
	
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
			logDBError ( "error = " . $stmt->error, "sync_add_project:execute" );
			$stmt->close ();
			return false;
		}
		$stmt->close ();
		return sync_add_assign_projects ( $item, $dbCon );
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_add_project:prepare" );
	}
	return false;
}

// Remove assign project records for a given project
function sync_remove_assign_projects($item, $dbCon) {
	$values = array ();
	$val = $item ['projecct_id'];
	$values [] = &$val;
	
	$query = "DELETE FROM `assign_project` WHERE `projectid` = ?";
	
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
			logDBError ( "error = " . $stmt->error, "sync_remove_assign_project_items:bind" );
			return false;
		}
		
		if (! $stmt->execute ()) {
			logDBError ( "error = " . $stmt->error, "sync_remove_assign_project_items:execute" );
			$stmt->close ();
			return false;
		}
		$stmt->close ();
		return true;
	} else {
		logDBError ( "error = " . $dbCon->error, "sync_remove_assign_project_items:prepare" );
	}
	return false;
}

// Add assign project records
function sync_add_assign_projects($item, $dbCon) {
	$values = array ();
	
	$inspectorArr = explode ( ',', $item ['inspecter'] );
	
	foreach ( $inspectorArr as $inspector ) {
		$values = array (
				"username" => $inspector,
				"projectid" => $item ['projecct_id'] 
		);
		
		if (! sync_assign_project ( $values, $dbCon )) {
			return false;
		}
	}
	
	return true;
}

// Returns an associate array containing JSON to DB fields mapping
function get_project_array() {
	$project_array = array (
			"address" => array (
					"address",
					's' 
			),
			"city" => array (
					"city",
					's' 
			),
			"client_name" => array (
					"client_name",
					's' 
			),
			"contract_no" => array (
					"contract_no",
					's' 
			),
			"created_date" => array (
					"created_date",
					's' 
			),
			"images" => array (
					"images",
					's' 
			),
			"inspecter" => array (
					"inspecter",
					's' 
			),
			"p_date" => array (
					"p_date",
					's' 
			),
			"p_description" => array (
					"p_description",
					's' 
			),
			"p_latitude" => array (
					"p_latitude",
					's' 
			),
			"p_longitude" => array (
					"p_longitude",
					's' 
			),
			"p_name" => array (
					"p_name",
					's' 
			),
			"p_title" => array (
					"p_title",
					's' 
			),
			"phone" => array (
					"phone",
					's' 
			),
			"projecct_id" => array (
					"projecct_id",
					's' 
			),
			"project_manager" => array (
					"project_manager",
					's' 
			),
			"state" => array (
					"state",
					's' 
			),
			"status" => array (
					"status",
					's' 
			),
			"street" => array (
					"street",
					's' 
			),
			"userID" => array (
					"userID",
					's' 
			),
			"zip" => array (
					"zip",
					's' 
			) 
	);
	
	return $project_array;
}
