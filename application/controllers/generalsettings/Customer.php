<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('encryption');
        setHeader( 'generalsettings/Customer_model' );
    }

    function getAffiliates() {
        $params = getData();
        $view   = $this->model->getAffiliates( $params );
        $view['view'] = decryptAffiliate( $view['view'] );

        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view['view']
					,'total' => $view['count']
				)
			)
        );
    }

    function getCoa(){
        $params = getData();
        $view = $this->model->getCoa( $params );
        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view['view']
					,'total' => $view['count']
				)
			)
        );
    }

    function getItems(){
        $params = getData();
        $view = $this->model->getItems( $params );
        $view['view'] = decryptItem( $view['view'] );

        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view['view']
					,'total' => $view['count']
				)
			)
        );
    }

    function getCustomerItem(){
        $params = getData();
        $view = $this->model->getCustomerItem( $params );
        $view['view'] = decryptItem( $view['view'] );

        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view['view']
					,'total' => $view['count']
				)
			)
        );
    }

    function saveForm(){
        $params = getData();

        /**Encryption of fields**/
        if( array_key_exists( 'name', $params) && !empty( $params['name'] ) ){

            $params['sk'] = initializeSalt( $params['name'] );
            $this->encryption->initialize( array('key' => generateSKED( $params['sk'] ) ) );

            $params['name'] = $this->encryption->encrypt( $params['name'] );
            if( array_key_exists( 'email', $params ) && !empty( $params['email'] ) ) $params['email'] = $this->encryption->encrypt( $params['email'] );
            if( array_key_exists( 'contactNumber', $params ) && !empty( $params['contactNumber'] ) ) $params['contactNumber'] = $this->encryption->encrypt( $params['contactNumber'] );
            if( array_key_exists( 'address', $params ) && !empty( $params['address'] ) ) $params['address'] = $this->encryption->encrypt( $params['address'] );
            if( array_key_exists( 'tin', $params ) && !empty( $params['tin'] ) ) $params['tin'] = $this->encryption->encrypt( $params['tin'] );
        } else {
            die('Encryption: CUSTOMER NAME IS REQUIRED.');
        }

        $match = 0;

        /* Saving Customer */
        $idCustomer = $this->model->saveCustomer( $params );

        /* Saving Customer History */
        $params['idCustomer'] = $idCustomer;
        $idCustomerHistory = $this->model->saveCustomerHistory( unsetParams( $params, 'customer' ) );

        if( $idCustomer !== NULL) {
            /* Saving items, affiliates, and contacts if not empty. */

            #AFFILIATES
            $custAffiliates = [];
            $custAffiliatesHis = [];
            $params['affiliates'] = json_decode( $params['affiliates'] );


            if( !empty( $params['affiliates']) ) {
                $this->model->deleteCustomerDetails( 'customeraffiliate', $idCustomer );

                foreach( $params['affiliates'] as $affiliate ){
                    $affiliate = (array)$affiliate;

                    /* Saving for Customer Affiliates */
                    $affiliate['idCustomer'] = $idCustomer;
                    $affiliate = unsetParams( $affiliate, 'customeraffiliate' );

                     /* Saving for Customer Affiliates History */
                    $affiliateHistory = $affiliate;
                    $affiliateHistory['idCustomerHistory'] = $idCustomerHistory;

                    /* Insert Affiliate & retrieve ID here */
                    $idCustomerAffiliate = $this->model->saveCustomerAffiliates( $affiliate );

                    /* Insert Affiliate history here */
                    $affiliateHistory['idCustomerAffiliate'] = $idCustomerAffiliate;

                    array_push( $custAffiliatesHis, $affiliateHistory );
                }

                $this->model->saveCustomerAffiliatesHistory( $custAffiliatesHis );
            }

            
            
            #ITEMS 
            $custItems = [];
            $custItemsHis = [];
            $params['items'] = json_decode( $params['items'] );

            if( !empty( $params['items'] ) ){
                $this->model->deleteCustomerDetails( 'customeritems', $idCustomer );

                foreach( $params['items'] as $item ){
                    $item = (array)$item;

                     /* Saving for Customer Items */
                    $item['idCustomer'] = $idCustomer;
                    $item = unsetParams( $item, 'customeritems' );

                     /* Saving for Customer Items History */
                    $itemHistory = $item;
                    $itemHistory['idCustomerHistory'] = $idCustomerHistory;
                    
                    array_push( $custItems, $item );
                    array_push( $custItemsHis, $itemHistory );
                }

                $custItems['idCustomer'] = $idCustomer;
                $this->model->saveCustomerItems( $custItems, $custItemsHis );
            }

            #CONTACTS 
            $custContacts = [];
            $custContactsHis = [];
            $params['contacts'] = json_decode( $params['contacts'] );

            if( !empty( $params['contacts'] ) ){
                 /** Encryption of fields **/
                 foreach( $params['contacts'] as $index => $contact ){
                    if( isset($contact->contactPersonName) && !empty($contact->contactPersonName) ){
                        $params['contacts'][$index]->sk = initializeSalt( $contact->contactPersonName );
                        $this->encryption->initialize( array('key' => generateSKED( $params['contacts'][$index]->sk )));
                        $params['contacts'][$index]->contactPersonName = $this->encryption->encrypt( $contact->contactPersonName );
                    } else {
                        die('Encryption: CONTACT PERSON NAME IS REQUIRED.');
                    }
                 }
                


                $this->model->deleteCustomerDetails( 'custcontactperson', $idCustomer );

                foreach( $params['contacts'] as $contact ){
                    $contact = (array)$contact;

                    /* Saving for Customer Contacts */
                    $contact['idCustomer'] = $idCustomer;
                    $contact = unsetParams( $contact, 'custcontactperson' );

                    /* Saving for Customer Contacts History */
                    $contactHistory = unsetParams( $contact, 'custcontactpersonhistory' );
                    $contactHistory['idCustomerHistory'] = $idCustomerHistory;

                    array_push( $custContacts, $contact );
                    array_push( $custContactsHis, $contactHistory );
                }
                $custContacts['idCustomer'] = $idCustomer;
                $this->model->saveCustomerContacts( $custContacts, $custContactsHis );
            }

            $msg = ( $params['onEdit'] ) ? ' edited the customer details of ' : ' added a new customer ';
            $customer = decryptCustomer( $this->model->retrieveData( $params ));

            setLogs(
                array(
                    'actionLogDescription' => $this->USERNAME . $msg . $customer[0]['name']
                    ,'idEu'                => $this->USERID
                    ,'moduleID'            => 11
                    ,'time'                => date("H:i:s A")
                )
            );

        } else {
            $match = 1;
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

    function saveCustomerAffiliate(){
        $params = getData();
        $view = $this->model->saveCustomerAffiliate( $params );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    function saveCustomerItem() {
        $params = getData();
        $view = $this->model->saveCustomerItem( json_decode( $params['data'], true ), $params['idCustomer'] );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                    ,'match' => ( $view != '' > 0 ? 1 : 0  ) 
                )
            )
        );
    }

    function getCustomers() {
        $params = getData();
        $view = $this->model->getCustomers( $params );
        $view['view'] = decryptCustomer( $view['view'] );

        die(
			json_encode(
				array(
					'success' => true
                    ,'view' => $view['view']
					,'total' => $view['count']
				)
			)
        );
    }

    function retrieveData(){
        $params = getData();
        $view = $this->model->retrieveData( $params );
        $view = decryptCustomer( $view );

        // LQ();

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    function deleteCustomer(){
        $params = getData();
        $view = $this->model->deleteCustomer( $params );
        $customerName = $this->model->retrieveData( $params );
        $customerName = decryptCustomer( $customerName );

        setLogs(
            array(
                'actionLogDescription' => $this->USERNAME . ' deleted a customer ' . $customerName[0]['name']
                ,'idEu'                => $this->USERID
                ,'moduleID'            => 11
                ,'time'                => date("H:i:s A")
            )
        );

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }

    function deleteItem(){
        $params = getData();
        // $this->model->deleteItem( $params );

        $data = json_decode( $params['data'], true);

        die(
            json_encode(
                array(
                    'success' => true
                    ,'data' => $data
                )
            )
        );
    }

    function getContacts(){
        $params = getData();
        $view = $this->model->getContacts( $params );

        /**Decrypt fields**/
        foreach( $view as $index => $contact ){
			if( isset( $contact['sk'] ) && !empty ( $contact['sk'] ) ){
				$this->encryption->initialize( array( 'key' => generateSKED( $contact['sk'] )) );
                if(array_key_exists( 'contactPersonName', $contact ) && !empty($contact['contactPersonName'])) 
                $view[$index]['contactPersonName'] = $this->encryption->decrypt( $contact['contactPersonName']);
			}
		}

        // LQ();

        die(
            json_encode(
                array(
                    'success' => true
                    ,'view' => $view
                )
            )
        );
    }
}