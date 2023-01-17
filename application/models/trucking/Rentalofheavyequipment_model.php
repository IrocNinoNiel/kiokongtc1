<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Joy Alegbeleye
 * Module: Rental of Heavy Equipment
 * Date: Nov 25, 2021
 * Finished: 
 * Description: This module allows the authorized user to set (add, edit and delete) a Rental of Heavy Equipment.
 * DB Tables: 
 * */
class Rentalofheavyequipment_model extends CI_Model {

    function getDrivers( $params ){
        $this->db->select("employee.idEmployee AS id, employee.name, employee.sk");
        $this->db->join("employment", "employment.idEmployee = employee.idEmployee", "left");
        $this->db->where('employee.archived', 0 ); //Driver
        $this->db->where('employment.classification', 42 ); //Driver
        return $this->db->get('employee')->result_array();
    }

    function getTruckTypes( $params ){
        $this->db->select("idTruckType as id, truckType as name");
        if( isset( $params['idAffiliate'] ) ) $this->db->where('idAffiliate', $params['idAffiliate'] );

        $this->db->order_by('truckType asc');
        return $this->db->get('trucktype')->result_array();
    }

    function getCustomers( $params ){
        $this->db->select("customer.idCustomer AS id, customer.name, customer.sk, customer.contactNumber, customer.address");
        $this->db->where('customer.archived', 0 );
        return $this->db->get('customer')->result_array();
    }

    function getSuppliers( $params ){
        $this->db->select("supplier.idSupplier AS id, supplier.name, supplier.sk, supplier.contactNumber, supplier.address");
        $this->db->where('supplier.archived', 0 );
        return $this->db->get('supplier')->result_array();
    }

    function getClassifications( $params ){
        $this->db->select("itemclassification.idItemClass AS id, itemclassification.classname as name");
        $this->db->where('itemclassification.archived', 0 );

        $this->db->order_by('itemclassification.classname asc');
        return $this->db->get('itemclassification')->result_array();
    }

    function getItems( $params ){
        $this->db->select("item.idItem, item.itemName, item.sk, item.itemPrice as price");
        $this->db->where('item.archived', 0 );

        $this->db->order_by('item.itemName asc');
        return $this->db->get('item')->result_array();
    }

    function getTruckProjects(){
        $this->db->select("truckproject.idTruckProject AS id, truckproject.projectName as name");
        // if( isset( $params['idAffiliate'] ) ) $this->db->where('idAffiliate', $params['idAffiliate'] );
        $this->db->where('truckproject.archived', 0 );
        $this->db->order_by('truckproject.projectName asc');
        return $this->db->get('truckproject')->result_array();
    }

    function getConstructionProjects(){
        $this->db->select("constructionproject.idConstructionProject AS id, constructionproject.projectName as name");
        // if( isset( $params['idAffiliate'] ) ) $this->db->where('idAffiliate', $params['idAffiliate'] );

        $this->db->order_by('constructionproject.projectName asc');
        return $this->db->get('constructionproject')->result_array();
    }

    function getPlateNumber( $params ){
        $this->db->select("truckprofile.idTruckProfile AS id, truckprofile.plateNumber as name");

        $this->db->order_by('truckprofile.plateNumber asc');
        return $this->db->get('truckprofile')->result_array();
    }

    function saveInvoice( $params ){
        $params = (array) $params;

        if( isset($params['onEdit']) && $params['onEdit'] == 1 ) {
            $this->db->where('idInvoice', $params['idInvoice']);
            $this->db->update('invoices', unsetParams( $params, 'invoices' ) );
            $idInvoice = $params['idInvoice'];

            $this->db->insert( 'invoiceshistory', unsetParams( $params, 'invoiceshistory' ) );
        } else {
            if( isset( $params['idInvoice'] ) ) unset( $params['idInvoice'] );
            $this->db->insert( 'invoices', unsetParams( $params, 'invoices' ) );
            $idInvoice = $this->db->insert_id();
        }

        return $idInvoice;
    }

    function saveRental( $params ){
        $params = (array) $params;

        if( isset($params['onEdit']) && $params['onEdit'] == 1 ) {
            $this->db->where('idRental', $params['idRental']);
            $this->db->update('rental', unsetParams( $params, 'rental' ) );
            $idRental = $params['idRental'];
        } else {
            if( isset( $params['idRental'] ) ) unset( $params['idRental'] );
            $this->db->insert( 'rental', unsetParams( $params, 'rental' ) );
            $idRental = $this->db->insert_id();
        }

        return $idRental;
    }

    function saveRentalDeduction( $params ){
        $params = (array) $params;

        if( count( $params ) > 0 ) {
            if( isset( $params['onEdit']) && $params['onEdit'] == 1 ) {
                $idRental   = $params['idRental'];

                unset( $params['idRental']);
                unset( $params['onEdit']);

                $this->db->delete( 'rentaldeduction', array( 'idRental' => $idRental ) );
                $this->db->insert_batch( 'rentaldeduction', $params );
            } else {
                $idRental   = $params['idRental'];

                unset( $params['idRental']);
                unset( $params['onEdit']);

                $this->db->insert_batch( 'rentaldeduction',  $params );
            }

            return $this->db->insert_id();
        }
    }

    function saveJournalEntries( $params ){
        $params = (array) $params;

        if( count( $params ) > 0 ) {
            if( isset( $params['onEdit']) && $params['onEdit'] == 1 ) {
                $idInvoice   = $params['idInvoice'];

                unset( $params['idInvoice']);
                unset( $params['onEdit']);

                $this->db->delete( 'posting', array( 'idInvoice' => $idInvoice ) );
                $this->db->insert_batch( 'posting', $params );

                $this->db->insert_batch( 'postinghistory',  $params );
            } else {
                $idInvoice   = $params['idInvoice'];

                unset( $params['idInvoice']);
                unset( $params['onEdit']);

                $this->db->insert_batch( 'posting',  $params );
            }

            return $this->db->insert_id();
        }
    }

    function getOtherDeductions( $params ){
        if( !isset($params['idRental']) || empty($params['idRental']) ) return array();
        $this->db->select("
                            rentaldeduction.idRental, 
                            rentaldeduction.idItem, 
                            item.itemName,
                            item.sk,
                            rentaldeduction.description, 
                            rentaldeduction.qty, 
                            rentaldeduction.price, 
                            rentaldeduction.amount, 
                            rentaldeduction.fident,
                            concat(reference.code, '-', invoices.referenceNum) as refNum
                            ");
        $this->db->join('invoices', 'invoices.idInvoice = rentaldeduction.fident', 'LEFT OUTER');
        $this->db->join('reference', 'reference.idReference = invoices.idReference', 'LEFT OUTER');
        $this->db->join('item', 'item.idItem = rentaldeduction.idItem', 'LEFT OUTER');
        $this->db->where( 'idRental', $params['idRental'] );
        return $this->db->get('rentaldeduction')->result_array();
    }

    function getJournalEntries( $params ){
        if( !isset($params['idRental']) || empty($params['idRental']) ) return array();
        $this->db->select("
                            rentaldeduction.idRental, 
                            rentaldeduction.idItem, 
                            item.itemName,
                            item.sk,
                            rentaldeduction.description, 
                            rentaldeduction.qty, 
                            rentaldeduction.price, 
                            rentaldeduction.amount, 
                            rentaldeduction.fident,
                            concat(reference.code, '-', invoices.referenceNum) as refNum
                            ");
        $this->db->join('invoices', 'invoices.idInvoice = rentaldeduction.fident', 'LEFT OUTER');
        $this->db->join('reference', 'reference.idReference = invoices.idReference', 'LEFT OUTER');
        $this->db->join('item', 'item.idItem = rentaldeduction.idItem', 'LEFT OUTER');
        $this->db->where( 'idRental', $params['idRental'] );
        return $this->db->get('rentaldeduction')->result_array();
    }

    function viewAll( $params ){
        $this->db->select("
                            invoices.idInvoice,
                            rental.idRental,
                            affiliate.affiliateName,
                            affiliate.sk AS affiliateSK,
                            convert(invoices.date, date) AS 'date', 
                            concat(reference.code, '-', invoices.referenceNum) as referenceNum, 
                            ( case rental.isConstruction 
                                when 1 THEN constructionproject.projectName 
                                when 0 THEN truckproject.projectName 
                            end ) as projectName,
                            employee.name AS driverName,
                            employee.SK AS employeeSK,
                            truckprofile.plateNumber,
                            trucktype.truckType,
                            ( case rental.status when 1 then 'Rented' ELSE 'Returned' END ) AS 'status',
                            ( case invoices.pType
                                when 1 then customer.name
                                when 2 then supplier.name
                            END ) AS customerName,
                            ( case invoices.pType
                                when 1 then customer.contactNumber
                                when 2 then supplier.contactNumber
                            END ) AS customerContactNumber,
                            ( case invoices.pType
                                when 1 then customer.sk
                                when 2 then supplier.sk
                            END ) AS customerSK");
        $this->db->join('invoices', 'invoices.idInvoice = rental.idInvoice', 'LEFT OUTER');
        $this->db->join('reference', 'reference.idReference = invoices.idReference', 'LEFT OUTER');
        $this->db->join('affiliate', 'affiliate.idAffiliate = invoices.idAffiliate', 'LEFT OUTER');
        $this->db->join('employee', 'employee.idEmployee = rental.idDriver', 'LEFT OUTER');
        $this->db->join('truckprofile', 'truckprofile.idTruckProfile = rental.idTruckProfile', 'LEFT OUTER');
        $this->db->join('trucktype', 'trucktype.idTruckType = rental.idTruckType', 'LEFT OUTER');
        $this->db->join('customer', 'customer.idCustomer = invoices.pCode and invoices.pType = 1', 'LEFT OUTER');
        $this->db->join('supplier', 'supplier.idSupplier = invoices.pCode and invoices.pType = 2', 'LEFT OUTER');
        $this->db->join('truckproject', 'truckproject.idTruckProject = rental.idProject', 'LEFT OUTER');
        $this->db->join('constructionproject', 'constructionproject.idConstructionProject = rental.idProject', 'LEFT OUTER');

        $this->db->order_by('invoices.date desc, invoices.referenceNum desc');
        

        if ( isset($params['limit']) && $params['limit'] != '' && isset($params['start']) && $params['start'] != '') {
            $this->db->limit($params['limit'], $params['start']);
        }

        if( isset( $params['filterValue'] ) && $params['filterValue'] != '' ){
            $this->db->where( 'rental.idrental', $params['filterValue'] );
        } 

        $this->db->where( 'invoices.archived', 0);

        return $this->db->get('rental')->result_array();
    }

    function viewHistorySearch( $params ){
        $this->db->select("rental.idRental as id, concat(reference.code, '-', invoices.referenceNum) as name");
        $this->db->join('invoices', 'invoices.idInvoice = rental.idInvoice', 'LEFT OUTER');
        $this->db->join('reference', 'reference.idReference = invoices.idReference', 'LEFT OUTER');
        $this->db->where( 'invoices.archived', 0 );
        
        $this->db->order_by('invoices.date desc, invoices.referenceNum desc');
        if ($params['limit'] != '' && $params['start'] != '') {
            $this->db->limit($params['limit'], $params['start']);
         }
        return $this->db->get('rental')->result_array();
    }

    function retrieveData( $params ){
        $this->db->select("
                            invoices.idInvoice,
                            invoices.idCostCenter,
                            invoices.idReference,
                            invoices.referenceNum,
                            reference.code,
                            CONVERT( invoices.date, DATE ) as tdate, 
                            TIME_FORMAT( CONVERT( invoices.date, TIME ), '%h:%i %p') as ttime,
                            rental.striker,
                            customer.idCustomer,
                            supplier.idSupplier,
                            ( case rental.striker
                                when 0 then customer.contactNumber
                                ELSE supplier.contactNumber
                            END ) AS contactNumber,
                            ( case rental.striker
                                when 0 then customer.address
                                ELSE supplier.address
                            END ) AS address,
                            ( case rental.striker
                                when 0 then customer.address
                                ELSE supplier.address
                            END ) AS address,
                            ( case rental.striker
                                when 0 then customer.sk
                                ELSE supplier.sk
                            END ) AS customerSK,
                            rental.isConstruction,
                            rental.idProject,
                            rental.remarks,
                            rental.idDriver,
                            CONVERT( rental.dateFrom, DATE ) as sdate,
                            CONVERT( rental.dateTo, DATE ) as edate,
                            rental.idTruckType,
                            truckprofile.plateNumber,
                            rental.model,
                            rental.status,
                            rental.rateType,
                            rental.rate,
                            rental.hours,
                            rental.trip,
                            rental.kilometer,
                            rental.totalRate,
                            rental.mileage,
                            rental.fuelLevel,
                            rental.fuelUsage,
                            CONVERT( rental.returnDate, DATE ) as returnDate,
                            rental.mileage,
                            rental.fuelLevel,
                            rental.penalty,
                            rental.idRental,
                            rental.returnMileage,
                            rental.returnFuelLevel
                            ");
        $this->db->join('invoices', 'invoices.idInvoice = rental.idInvoice', 'LEFT OUTER');
        $this->db->join('reference', 'reference.idReference = invoices.idReference', 'LEFT OUTER');
        $this->db->join('customer', 'customer.idCustomer = invoices.pCode AND rental.striker = 0', 'LEFT OUTER');
        $this->db->join('supplier', 'supplier.idSupplier = invoices.pCode AND rental.striker = 1', 'LEFT OUTER');
        $this->db->join('truckprofile', 'truckprofile.idTruckProfile = rental.idTruckProfile', 'LEFT OUTER');

        $this->db->order_by('invoices.date desc, invoices.referenceNum desc');
        $this->db->where( 'rental.idRental', $params['id'] );

        return $this->db->get('rental')->result_array();
    }

    function getReferences(){
        $this->db->select("
                            invoices.idInvoice as fident, 
                            concat(reference.code, '-', invoices.referenceNum) as refNum,
                            (case invoices.idModule
                                when 57 then 'This item is a Vouchers Payable transaction.'
                                when 58 then 'This item is a Vouchers Receivable transaction.'
                            END ) AS description,
                            invoices.amount");
        $this->db->join('reference', 'reference.idReference = invoices.idReference', 'LEFT OUTER');
        
        $this->db->order_by('invoices.date desc, invoices.referenceNum desc');
        $this->db->where_in('invoices.idModule', array(57,58));
        return $this->db->get('invoices')->result_array();
    }

    function getReturnDetails( $params ){
        $this->db->select("
                            CONVERT( rental.returnDate, DATE ) as returnDate,
                            rental.penalty,
                            rental.idRental,
                            rental.returnMileage,
                            rental.returnFuelLevel");
        $this->db->where_in('rental.idRental', $params['id']);
        return $this->db->get('rental')->result_array();
    }
    
    function saveReturn( $params ){
        $params = (array) $params;

        $this->db->where('idRental', $params['idRental']);
        $this->db->update('rental', unsetParams( $params, 'rental' ) );
        $idRental = $params['idRental'];

        return $idRental;
    }

    function deleteRecord( $params ){
        $match = 0;

        $this->db->select("*");
        $this->db->where( array(
                                "fident" => $params['id']
                                ,"archived" => 0) );
        $usedTransactions = $this->db->get('invoices')->num_rows();

        if( $usedTransactions > 0 ){
            $match = 1;
        } else {
            /* SOFT DELETE ONLY */
            $this->db->set('archived', 1, false );
            $this->db->where('idInvoice', $params['id'] );
            $this->db->update('invoices');
        }
        
        return $match;
    }


}


// ,array(
//     'label' => 'Project Name'
//     ,'value' => $formDetails['pdf_idProject']
// )
// ,array(
//     'label' => "Driver's Name"
//     ,'value' => $formDetails['pdf_idDriver']
// )
// ,array(
//     'label' => "Truck Type"
//     ,'value' => $formDetails['pdf_idTruckType']
// )
// ,array(
//     'label' => "Plate Number"
//     ,'value' => $formDetails['pdf_plateNumber']
// )