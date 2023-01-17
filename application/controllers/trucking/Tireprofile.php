<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Tireprofile extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        $this->load->model('Home_model');
        setHeader('trucking/Tireprofile_model');
    }

    public function test() {
        $params = getData();
        $params['idTireProfile'] = 2;
        $params['serialNumber'] = 1312321;
        echo '<pre>';
        var_dump($this->model->isSerialExist( $params ));
        echo '</pre>';
    }

    public function getTireProfiles()
    {
        $params = getData();
        $view = $this->model->getTireProfiles($params);
        die(
            json_encode( 
                array(
                    'success' => true
                    , 'view' => $view,
                )
            )
        );
    }

    public function getTireProfile()
    {
        $params = getData();
        $view = $this->model->getTireProfile($params);
        die(
            json_encode( 
                array(
                    'success' => true
                    , 'view' => $view,
                )
            )
        );
    }

    public function gridRecap()
    {
        $params = getData();
        $view = $this->model->gridRecap($params);

        die(
            json_encode(
                array(
                    'success' => true
                    , 'view' => $view,
                )
            )
        );
    }

    public function getTruckProfile()
    {
        $params = getData();
        $view = $this->model->getTruckProfile($params);

        die(
            json_encode(
                array(
                    'success' => true
                    , 'view' => $view,
                )
            )
        );
    }

    public function saveRecord()
    { 
        $match = 0;
        $params = getData();

        $isExist = $this->model->isSerialExist( $params );

        if($isExist) {
            $match = 1;
            die(
                json_encode(
                    array(
                        'success' => true
                        ,'match' => $match
                        ,'test' => $isExist
                    )
                )
            );
        }

        $idTireProfile = $this->model->saveTire($params);

        $this->model->deleteRecap($idTireProfile);
        $gridRecap = json_decode($params['gridRecap']);
        if ($gridRecap) {
            foreach ($gridRecap as $rs) {
                $dataRecap[] = array(
                    'recapDate' => $rs->recapDate
                    , 'recapValue' => $rs->recapValue
                    , 'idTireProfile' => $idTireProfile,
                );
            } 
            $this->model->saveRecap($dataRecap);
        }

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $idTireProfile
                    ,'match' => $match
                )
            )
        );

    }

    public function deleteTireProfile() {
        $match = 0;
        $params = getData();
        
        $view = $this->model->deleteTireProfile($params);

        setLogs(
            array(
                'actionLogDescription' => $this->USERNAME . ' deleted a truck type'
                , 'idEu' => $this->USERID
                , 'moduleID' => 71
                , 'time' => date("H:i:s A"),
            )
        );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'match' => $match
                    ,'view' => $view
                )
            )
        );
    }
}
