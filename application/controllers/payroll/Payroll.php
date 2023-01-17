<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * Developer: Niño Niel B. Iroc
 * Module: Logs Summary
 * Date: Jan 11, 2023
 * Finished:
 * Description: 
 * DB Tables:
 * */

class Payroll extends CI_Controller{

    public function __construct(){
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        setHeader('payroll/Payroll_model');
    }


}
