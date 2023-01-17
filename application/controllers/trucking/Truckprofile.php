<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Truckprofile extends CI_Controller {

    public function __construct(){
        parent::__construct();
        middleware();
        $this->load->library('encryption');
        setHeader( 'trucking/Truckprofile_model' );
    }

    public function getTruckProfiles() {
        $params = getData(); 
        $view   = $this->model->getTruckProfiles($params);
        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view
				)
			)
        );
    }
 
    public function saveProfile() {
        $match  = 0;
        $params = getData(); 

        $partDetails  = json_decode($params['partDetails'], true);  unset($params['partDetails']);
        $fileViews    = json_decode($params['fileView']);           unset($params['fileView']);

        // SAVE PROFILE 
        $view   = $this->model->saveProfile($params);

        // SAVE PART DETAILS
        $this->model->deletePartDetails($view);
        foreach ($partDetails as $partDetail) {
            $detail = json_decode($partDetail, true);
            $detail['idTruckProfile'] = $view;

            $this->model->savePartDetails($detail);
        }

        // UPLOAD IMAGES
        $base_path = 'images/truck/' . $view . '/';
        if (!is_dir($base_path)) mkdir($base_path, 0777, TRUE);

        foreach ( $fileViews as $fileView ) {
            $photo = $_FILES[ 'upload' . $fileView . $params['module'] ];

            if( $photo['size'] ){
                $filename = $params['truck' . $fileView];
                $image_path = $base_path . $filename;
                resize_image( $photo['tmp_name'], $photo['size'], $image_path );
            }
        }
        
        // CHANGE ALBUM STATUS
        $this->model->unTemp($view);

        $this->setLogs( array( 'onEdit' => $params['onEdit'], 'idModule' => $params['idModule'] ) );

        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view
                    ,'match' => $match
				)
			)
        );
    }

    public function deleteTruckProfile() {
        $params = getData();
        $match  = $this->model->deleteTruckProfile($params);   
    
        if(!$match) {
            $base_path  = 'images/truck/' . $params['idTruckProfile'];
            $files      = glob($base_path.'/*'); 

            foreach($files as $file) {
                if(is_file($file)) unlink($file); 
            } 
            if (is_dir($base_path)) rmdir($base_path);
            $this->setLogs( array( 'delete' => 1, 'idModule' => $params['idModule'] ) );
        }    

        die(
			json_encode(
				array(
					'success' => true
                    ,'match' => $match 
				)
			)
        );
    }

    public function getAlbum(){
        $params = getData();
		$view	= $this->model->getAlbum( $params ); 

		die(
            json_encode(
                array('success'=>true, 'view'=>$view)
            )
        );
	}

    function getTruckPartDetails() {
        $params = getData();
        $view = $this->model->getTruckPartDetails( $params );

        die(
            json_encode(
                array(
                    'success' => true,
                    'view' => $view
                )
            )
        );
    }

    public function partDetailsGrid(){
        $params = getData(); 

		$view = $this->model->partDetailsGrid($params);
		die(
            json_encode(
                array(
                    'success'   => true, 
                    'view'      => $view
                )
            )
        );
	}

    public function getTruckTypeItems() {
        $params = getData(); 
        $view   = $this->model->getTruckTypeItems($params);
        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view
				)
			)
        );
    }

    function uploadTempPIC(){
		$params = getData(); 

		$folder = $params['idTruckProfile']? $params['idTruckProfile'] : 'temp';
		
        $photo  = $_FILES['fileAlbum' . $params['moduleParams']];
		
        if($photo['size']){
            $filename = rand(100000000,999999999) . '.' . pathinfo($photo['name'], PATHINFO_EXTENSION);
            $filepath = 'images/truck/' . $folder . '/' . $filename;
            
            if(resize_image($photo['tmp_name'], $photo['size'], $filepath, true, 200, 200)){
				$pic['position'] = 0;
				$pic['temp'] 	 = ($params['idTruckProfile'])? 0 : 1;
				$pic['idEu']	 = $this->USERID;
				$pic['filename'] = $filename;
				$pic['idTruckProfile']	 = ($params['idTruckProfile'])? $params['idTruckProfile'] : null;
			
                $this->model->uploadTempPIC($pic);
			}
		}
		
		die(
            json_encode( 
                array('success'=>true, 'match'=>$photo) )
            );		
	}

    function clearTemp() {
        $this->model->clearTemp();
		die(json_encode( array('success'=>true) ));	
    }

    private function setLogs( $params ){
		$header = ucfirst( $this->USERFULLNAME );
		$action = '';
	
        switch( true ){
            case isset( $params['delete'] ):
                $action = 'removed the';
            break;
            case isset( $params['cancelTag'] ) && (int)$params['cancelTag'] == 1:
                $action = 'cancelled the';
            break;
            default:
                if( isset( $params['action'] ) )
                    $action = $params['action'];
                else
                    $action = ( $params['onEdit'] == 1  ? 'modified the' : 'added a new' );
            break;
        }
        
        $params['actionLogDescription'] = $header . ' ' . $action . ' truck profile.';
		
		setLogs( $params );
    }

    function printPDF(){
        $params     = getData();
        $data       = $this->model->getTruckProfiles( $params );

        $col = array(
            array(   
                'header'        => 'Plate Number'
                ,'dataIndex'    => 'plateNumber'
                ,'width'        => '15%'
            ),
            array(   
                'header'        => 'Type'
                ,'dataIndex'    => 'type'
                ,'width'        => '15%'
            ),
            array(   
                'header'        => 'Axle'
                ,'dataIndex'    => 'axle'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => 'Color'
                ,'dataIndex'    => 'color'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => 'Date Acquired'
                ,'dataIndex'    => 'dateAcquired'
                ,'width'        => '15%'
            ),
            array(   
                'header'        => 'Date Deployed'
                ,'dataIndex'    => 'dateDeployment'
                ,'width'        => '15%'
            ),
            array(   
                'header'        => 'Capacity'
                ,'dataIndex'    => 'capacity'
                ,'width'        => '10%'
            ),
            array(   
                'header'        => 'Status'
                ,'dataIndex'    => 'status'
                ,'width'        => '10%'
            )
        );

        $header_fields = array(
            array(
                array(
                    'label'     => 'Date'
                    ,'value'    => date("Y-m-d")
                )
            )
        );

        // $this->setLogs( array( 'action' => 'Exported the generated Truck Profile Report (PDF)' ) );

        generateTcpdf(
            array(
                'file_name'      => $params['title']
                ,'folder_name'   => 'trucking'
                ,'records'       => $data
                ,'header'        => $col
                ,'orientation'   => 'P'
                ,'header_fields' => $header_fields
            )
        );
    }
    
    public function printExcel() {
        $params = getData();
        $data   = $this->model->getTruckProfiles( $params );
        
        $csvarray = array();

        $csvarray[] = array( 'title' => $params['title'] );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array( 'Date', date("Y-m-d")  );
        $csvarray[] = array( 'space' => '' );

        $csvarray[] = array(
            'Plate Number'
            ,'Type'
            ,'Axle'
            ,'Color'
            ,'Date Acquired'
            ,'Date Deployed'
            ,'Capacity'
            ,'Status'
        );

        foreach( $data as $d ){
            $csvarray[] = array(
                $d['plateNumber']
                ,$d['type']
                ,$d['axle']
                ,$d['color']
                ,$d['dateAcquired']
                ,$d['dateDeployment']
                ,$d['capacity']
                ,$d['status']
            );
        }

        // $this->setLogs( array( 'action' => 'Exported the generated Truck Type Report (Excel).' ) );

        writeCsvFile(
            array(
                'csvarray' 	 => $csvarray
                ,'title' 	 => $params['title']
                ,'directory' => 'trucking'
            )
        );
    }

    function download($title){
		force_download(
			array(
				'title'         => $title
				,'directory'    => 'trucking'
			)
		);
	}
}


