<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Developer: Hazel Joy Alegbeleye
 * Module: Delivery Ticket
 * Date: Nov 09, 2021
 * Finished: 
 * Description: This module allows the authorized user to set (add, edit and delete) a delivery ticket
 * DB Tables: 
 * */
class Deliveryticket_model extends CI_Model {

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
        $this->db->select("customer.idCustomer AS id, customer.name, customer.sk");
        $this->db->where('customer.archived', 0 );
        return $this->db->get('customer')->result_array();
    }

    function getClassifications( $params ){
        $this->db->select("itemclassification.idItemClass AS id, itemclassification.classname as name");
        $this->db->where('itemclassification.archived', 0 );

        $this->db->order_by('itemclassification.classname asc');
        return $this->db->get('itemclassification')->result_array();
    }

    function getItems( $params ){
        $this->db->select("item.idItem AS id, item.itemName as name, item.sk");
        $this->db->where('item.archived', 0 );

        $this->db->order_by('item.itemName asc');
        return $this->db->get('item')->result_array();
    }

    function getLocation( $params ){
        $this->db->select("location.idLocation, location.locationName");
        // $this->db->where('item.archived', 0 );

        $this->db->order_by('location.locationName asc');
        return $this->db->get('location')->result_array();
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

    function saveDeliveryTickets( $params ){
        $params = (array) $params;

        if( isset($params['onEdit']) && $params['onEdit'] == 1 ) {
            $this->db->where('idDeliveryTicket', $params['idDeliveryTicket']);
            $this->db->update('deliveryticket', unsetParams( $params, 'deliveryticket' ) );
            $idDeliveryTicket = $params['idDeliveryTicket'];
        } else {
            if( isset( $params['idDeliveryTicket'] ) ) unset( $params['idDeliveryTicket'] );
            $this->db->insert( 'deliveryticket', unsetParams( $params, 'deliveryticket' ) );
            $idDeliveryTicket = $this->db->insert_id();
        }

        return $idDeliveryTicket;
    }

    function saveDeliveryTicketActivity( $params ){
        $params = (array) $params;

        if( count( $params ) > 0 ) {
            if( isset( $params['onEdit']) && $params['onEdit'] == 1 ) {
                $idDeliveryTicket   = $params['idDeliveryTicket'];

                unset( $params['idDeliveryTicket']);
                unset( $params['onEdit']);

                $this->db->delete( 'deliveryticketactivity', array( 'idDeliveryTicket' => $idDeliveryTicket ) );
                $this->db->insert_batch( 'deliveryticketactivity', $params );
            } else {
                $idDeliveryTicket   = $params['idDeliveryTicket'];

                unset( $params['idDeliveryTicket']);
                unset( $params['onEdit']);
                
                $this->db->insert_batch( 'deliveryticketactivity',  $params );
            }

            return $this->db->insert_id();
        }
    }

    function viewAll( $params ){
        $this->db->select("
                            invoices.idInvoice,
                            deliveryticket.idDeliveryTicket, 
                            affiliate.affiliateName,
                            affiliate.sk as affiliateSK,
                            convert(invoices.date, date) as date,
                            concat(reference.code, '-', invoices.referenceNum) as referenceNum,
                            employee.name AS driverName,
                            employee.sk AS employeeSK,
                            truckprofile.plateNumber,
                            trucktype.truckType,
                            ( case deliveryticket.isConstruction 
                                when 1 THEN constructionproject.projectName
                                when 0 THEN truckproject.projectName
                            end ) as projectName,
                            ( case deliveryticket.deliveryTicketType 
                                when 1 THEN 'Per Load'
                                when 2 THEN 'Per Day'
                            end ) as deliveryTicketType,
                            deliveryticket.odometer,
                            ( case invoices.pCode 
                                when 0 THEN 'WALK-IN'
                                else customer.name
                            end ) as customerName,
                            customer.sk as customerSK,
                            invoices.amount as totalAmount");
        $this->db->join('invoices', 'invoices.idInvoice = deliveryticket.idInvoice', 'LEFT OUTER');
        $this->db->join('reference', 'reference.idReference = invoices.idReference', 'LEFT OUTER');
        $this->db->join('affiliate', 'affiliate.idAffiliate = invoices.idAffiliate', 'LEFT OUTER');
        $this->db->join('employee', 'employee.idEmployee = deliveryticket.idDriver', 'LEFT OUTER');
        $this->db->join('trucktype', 'trucktype.idTruckType = deliveryticket.idTruckType', 'LEFT OUTER');
        $this->db->join('customer', 'customer.idCustomer = invoices.pCode', 'LEFT OUTER');
        $this->db->join('truckproject', 'truckproject.idTruckProject = deliveryticket.idProject', 'LEFT OUTER');
        $this->db->join('constructionproject', 'constructionproject.idConstructionProject = deliveryticket.idProject', 'LEFT OUTER');
        $this->db->join('truckprofile', 'truckprofile.idTruckProfile = deliveryticket.idTruckProfile', 'LEFT OUTER');

        $this->db->order_by('invoices.date desc, invoices.referenceNum desc');
        

        if ( isset($params['limit']) && $params['limit'] != '' && isset($params['start']) && $params['start'] != '') {
            $this->db->limit($params['limit'], $params['start']);
        }

        if( isset( $params['filterValue'] ) && $params['filterValue'] != '' ){
            $this->db->where( 'deliveryticket.idDeliveryTicket', $params['filterValue'] );
        } 

        $this->db->where( 'invoices.archived', 0);

        return $this->db->get('deliveryticket')->result_array();
    }

    function viewHistorySearch( $params ){
        $this->db->select("deliveryticket.idDeliveryTicket as id, concat(reference.code, '-', invoices.referenceNum) as name");
        $this->db->join('invoices', 'invoices.idInvoice = deliveryticket.idInvoice', 'LEFT OUTER');
        $this->db->join('reference', 'reference.idReference = invoices.idReference', 'LEFT OUTER');
        
        $this->db->order_by('invoices.date desc, invoices.referenceNum desc');
        if ($params['limit'] != '' && $params['start'] != '') {
            $this->db->limit($params['limit'], $params['start']);
         }
        return $this->db->get('deliveryticket')->result_array();
    }

    function retrieveData( $params ){
        $this->db->select("
                            invoices.idInvoice,
                            invoices.idCostCenter,
                            invoices.idReference,
                            invoices.idAffiliate,
                            CONVERT( invoices.date, DATE ) as tdate, 
                            TIME_FORMAT( CONVERT( invoices.date, TIME ), '%h:%i %p') as ttime,
                            deliveryticket.idDriver,
                            invoices.pCode,
                            deliveryticket.idTruckProfile as plateNumber,
                            invoices.referenceNum,
                            reference.code,
                            deliveryticket.idDeliveryTicket,
                            deliveryticket.idTruckType,
                            deliveryticket.idProject,
                            deliveryticket.deliveryTicketType,
                            deliveryticket.odometer,
                            deliveryticket.isConstruction,
                            deliveryticket.remarks,
                            invoices.amount as totalAmount");
        $this->db->join('invoices', 'invoices.idInvoice = deliveryticket.idInvoice', 'LEFT OUTER');
        $this->db->join('reference', 'reference.idReference = invoices.idReference', 'LEFT OUTER');

        $this->db->order_by('invoices.date desc, invoices.referenceNum desc');
        $this->db->where( 'deliveryticket.idDeliveryTicket', $params['id'] );

        return $this->db->get('deliveryticket')->result_array();
    }

    function getActivity( $params ){
        if( isset( $params['id'] ) ){
            $this->db->select("
                                deliveryticketactivity.idDeliveryTicketActivity,
                                deliveryticketactivity.activityName,
                                deliveryticketactivity.noOfLoads,
                                deliveryticketactivity.noOfDays,
                                deliveryticketactivity.fuelConsumed,
                                deliveryticketactivity.lubricant,
                                deliveryticketactivity.rate,
                                deliveryticketactivity.idDeliveryTicket,
                                deliveryticketactivity.idLocation,
                                deliveryticketactivity.description,
                                location.locationName as area,
                                (deliveryticketactivity.noOfDays * deliveryticketactivity.rate) as totalForDays,
                                (deliveryticketactivity.noOfLoads * deliveryticketactivity.rate) as totalForLoads
                                ");

            $this->db->order_by('deliveryticketactivity.activityName asc');
            $this->db->where('deliveryticketactivity.idDeliveryTicket', $params['id'] );
            $this->db->join('location', 'location.idLocation = deliveryticketactivity.idLocation', 'LEFT OUTER');
            $this->db->join('deliveryticket', 'deliveryticket.idDeliveryTicket = deliveryticketactivity.idDeliveryTicket', 'LEFT OUTER');

            return $this->db->get('deliveryticketactivity')->result_array();
        } else {
            return array();
        }
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