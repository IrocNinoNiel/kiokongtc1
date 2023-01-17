<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Attendance extends CI_Controller{

    public function __construct(){
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        setHeader('payroll/Attendance_model');
    }

    function getProjectNames() {
        $params = getData();
        switch($params['type']) {
            case "1":
                $view = $this->model->getTruckingProject($params);
                break;
            case "2":
                $view = $this->model->getConstructionProject($params);
                break;
            default:
                $view = [];
        }

        die(
            json_encode(
                array(
                     'success' => true
                    ,'view'    => $view
                )
            )
        );
    }

    function getEmployees() {
        $params = getData();
        $view   = $this->model->getEmployees( $params );
        $view   = decryptUserData( $view );

        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function importAttendance() {
        $params = getData();
        // $module = $params['module'];

        require_once APPPATH.'third_party/phpexcel/PHPExcel.php';
        if(isset($_FILES['file_import_Attendance'])) {

            $file = $_FILES['file_import_Attendance'];
            $path = $file['tmp_name'];
            $msg = 'No data!';
            $object = PHPExcel_IOFactory::load($path);

            foreach($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
				$duplicated = array();
                $viewData = [];
				$msg = ''; $addons = '<br></br>';

                if (   $worksheet->getCellByColumnAndRow(0, 1)->getValue() != 'Date'
                    || $worksheet->getCellByColumnAndRow(1, 1)->getValue() != 'Name'
                    || $worksheet->getCellByColumnAndRow(2, 1)->getValue() != 'Time Start'
                    || $worksheet->getCellByColumnAndRow(3, 1)->getValue() != 'Time End'
                    || $worksheet->getCellByColumnAndRow(4, 1)->getValue() != 'Number Regular of Hours'
                    || $worksheet->getCellByColumnAndRow(5, 1)->getValue() != 'Overtime Hours'
                    || $worksheet->getCellByColumnAndRow(6, 1)->getValue() != 'Total Hours'
                ) $this->dieFunc( true, 'Invalid Format!', 1 );

                for($row=2; $row<=$highestRow; $row++) {
                    if (   !$worksheet->getCellByColumnAndRow(0, $row)->getValue()
                        || !$worksheet->getCellByColumnAndRow(1, $row)->getValue()
                        || !$worksheet->getCellByColumnAndRow(2, $row)->getValue()
                        || !$worksheet->getCellByColumnAndRow(3, $row)->getValue()
                        || !$worksheet->getCellByColumnAndRow(4, $row)->getValue()
                        || !$worksheet->getCellByColumnAndRow(5, $row)->getValue()
                        || !$worksheet->getCellByColumnAndRow(6, $row)->getValue()
                    ) $this->dieFunc( true, 'Incomplete Data!', 1 );

                    $data['onEdit']             = 1;
                    $data['date']               = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $data['name']               = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $data['timeStart']          = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $data['timeEnd']            = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $data['numberRegularOfHrs'] = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $data['overtimeHrs']        = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $data['totalHrs']           = $worksheet->getCellByColumnAndRow(0, $row)->getValue();

                    $viewData[] = $data;
                }
                $msg    = 'SAVE_SUCCESS';
                $addons = '';
                $this->dieFunc( true, $msg.$addons, 1, $viewData );
            }
        }
        die(
            json_encode(
                array(
                    'success'   => true
                    ,'view'     => $view
                )
            )
        );
    }

    function dieFunc( $success, $msg, $match, $viewData=[] ) {
        $view['msg']        = $msg;
        $view['match']      = $match;
        $view['viewData']   = json_encode($viewData);
        $data['success']    = $success;
        $data['view']       = $view;
        die( json_encode($data) );
    }

}
