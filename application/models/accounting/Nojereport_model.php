<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Nojereport_model extends CI_Model
{
    public function getModules( $params )
    {
        $this->db->select("
              module.idModule as id
            , moduleName as name
        ");
    
        $this->db->join( "invoices" , "invoices.idModule = module.idModule and cancelTag = 0" );
        $this->db->where( "invoices.idAffiliate" , $params['idAffiliate'] );
        $this->db->where_not_in( "invoices.archived" , 1 );

        $this->db->where( "module.isTransaction" , 0 );
        return $this->db->get( "module" )->result_array();
    }

    public function getNojereport( $params )
    {
        $this->db->select(
            " invoices.idInvoice
            , invoices.idModule
            , DATE( invoices.date ) as date
            , moduleName
            , CONCAT( reference.code , ' - ' , invoices.referenceNum ) as reference "
        );

        $this->db->join( "reference", "reference.idReference = invoices.idReference" );
        $this->db->join( "module"   , "module.idModule = invoices.idModule" );
        $this->db->join( "posting"  , "posting.idInvoice = invoices.idInvoice" , "left");

        if( $params['idModule'] != 0 ) $this->db->where( "invoices.idModule" , $params['idModule'] );
        $this->db->where( "invoices.idAffiliate" , $params['idAffiliate'] );
        $this->db->where( "DATE(invoices.date) BETWEEN '{$params['sdate']}' AND '{$params['edate']}'" , NULL ,FALSE );

        $this->db->where_not_in( "invoices.archived" , 1 ); 
        $this->db->where_not_in( "invoices.cancelTag" , 1 ); 
        $this->db->where( "IFNULL( posting.idPosting , 0 ) = 0 " );
        $this->db->group_by( "invoices.idInvoice" );
        $this->db->order_by( "invoices.date" , "DESC");

        return $this->db->get( "invoices" )->result_array();
    }
}