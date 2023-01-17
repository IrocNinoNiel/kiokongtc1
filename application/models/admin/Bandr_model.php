<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bandr_model extends CI_Model {
	
	/* This process retrieving information for auto backup settings. */
	function GetBackUp(){
		$this->db->select('*');
		return $this->db->get('autobackup')->result_array();
	}
	
	/* This executes retrieving of all tables in the database. */
	public function getDBTables(){
		return $this->db->query( "SHOW TABLES" )->result_array();
	}

	/* This is used to transform the specified table to a sql create statement */
	public function createDBStatement( $params ){
		return $this->db->query( "SHOW CREATE TABLE $params[tableName]" )->row_array();
	}

	/* This executes retrieving of all data recorded in the specified table */
	public function getTableData( $params ){
		return $this->db->get( $params['tableName'] )->result_array();
	}

	public function passwordChecking( $params ){
		$this->db->where( 'idEu', $this->USERID );
		$this->db->where( 'password', md5( $params['pass'] ) );
		$cnt = $this->db->count_all_results( 'eu' );
		if( $cnt > 0 ) return true;
		else return false;
	}

	public function executeQuery( $sqlString ){
		$this->db->query( $sqlString );
	}
	
	public function getUserFullName( $idEu ){
		if( $idEu == 0 ){
			return 'Automated';
		}
		else{
			$this->db->select( 'username as name' );
			$this->db->where( 'idEu', $idEu );
			$rec = $this->db->get( 'eu' )->row_array();
			if( count( (array)$rec ) > 0 ){
				return $rec['name'];
			}
			else{
				return '';
			}
		}
	}

	public function saveAutobackSchedule( $params ){
		$idAB = (int)$params['idAB'];
		unset( $params['idAB'] );
		if( $idAB > 0 ){
			$this->db->where( 'idAB', $idAB );
			$this->db->update( 'autobackup', unsetParams( $params, 'autobackup' ) );
		}
		else{
			$this->db->insert( 'autobackup', unsetParams( $params, 'autobackup' ) );
		}
	}
}
?>