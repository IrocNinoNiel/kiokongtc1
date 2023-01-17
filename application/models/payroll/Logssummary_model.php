<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Developer: Niño Niel B. Iroc
 * Module: Logs Summary
 * Date: Jan 11, 2023
 * Finished:
 * Description: 
 * DB Tables:
 * */

class Logssummary_model extends CI_Model {

	function getEmployees( $params ){
        $this->db->select("
                            employee.idEmployee,
                            employee.name as employeeName,
                            employee.sk as employeeSK,
                            (case employee.status
                                when 0 then 'Active'
                                when 1 then 'Inactive'
                            end) as status,
                            employeeclass.empClassName as classification,
                            employment.monthRate
        ");
        $this->db->join('employment', 'employment.idEmployee = employee.idEmployee', 'left');
        $this->db->join('employeeclass', 'employeeclass.idEmpClass = employment.classification', 'left');
        $this->db->where('employee.archived', 0 );

        $this->db->order_by('employee.name asc');
        return $this->db->get('employee')->result_array();
    }

    function getLogsummary( $params ) {

    }

}


