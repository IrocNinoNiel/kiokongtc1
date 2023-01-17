<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salesreturn_model extends CI_Model { 
    
    public function getCustomerItem($data){
        $this->db->select('
            item.idItem AS id,
            item.sk,
            item.barcode AS code,
            item.itemName AS name,
            itemclassification.className AS class,
            unit.unitCode AS unit,
            receiving.cost,
            releasing.price,
            releasing.qtyLeft AS remaining,
            0 AS qty,
            0 AS amount,
            releasing.idReleasing AS releasedID
        ')
            ->from('invoices')
            ->join('releasing',"releasing.idInvoice = {$data['idInvoice']}",'LEFT')
            ->join('item','releasing.idItem = item.idItem','LEFT')
            ->join('itemclassification','itemclassification.idItemClass = item.idItemClass','LEFT')
            ->join('unit','unit.idUnit = item.idUnit','LEFT')
            ->join('receiving','receiving.idReceiving = releasing.fident','LEFT')
            ->where('invoices.archived', NEGATIVE)
		    ->where('invoices.status', APPROVED)
            ->where('invoices.idInvoice',$data['idInvoice']);
        return $this->db->get()->result_array();    
    }

    public function returnReceived($where){
        $items = $this->db->select("fident, qty")->from('receiving')->where($where)->get()->result_array();
        foreach($items as $key => $item){
            $this->db->set( 'qtyLeft', '( qtyLeft + ' . $item['qty'] . ' )', false );
            $this->db->where( 'idReleasing', $item['fident'] );
            $this->db->update( 'releasing' );
        }
    }

    public function received($releasedData, $affiliate){
        foreach ($releasedData as $key => $item){
			$items = $this->db->select('releasing.idReleasing, releasing.qtyLeft, releasing.idItem')
				->from('releasing')
				->join('invoices', 'invoices.idInvoice = releasing.idInvoice', 'LEFT')
				->where('releasing.qtyLeft > 0',NULL, false)
				->where('releasing.idItem', $item['idItem'])
				->where('invoices.idAffiliate', $affiliate)
				->get()->result_array();
			$released = (int) $item['qty'];
			foreach($items as $indx => $it){
				$this->db->where('idReleasing', $it['idReleasing']);
				$left = (int) $it['qtyLeft'];
				if($released <= 0 ) break;
				if($released > $left){
					$released = $released - $left;
                    $this->db->update('releasing',['qtyLeft' => 0]);
					$this->saveReceived($released, $it['idReleasing'],  $left);
				}else{
                    $total = $released;
					$qty = $left - $released;
                    $released = 0;
                    $this->db->update('releasing',['qtyLeft' => $qty]);
                    $this->saveReceived($item, $it['idReleasing'], $total);
				}
			}
		}
	}
    
    public function getCustomerInvoice($params){
        $this->db->select("
            invoices.idInvoice AS id,
            CONCAT(reference.code, ' - ', invoices.referenceNum) as refcode
        ")
            ->from('invoices')
            ->join('reference','reference.idReference = invoices.idReference', 'LEFT')
            ->join('releasing','releasing.idInvoice = invoices.idInvoice', 'LEFT')
            ->where('invoices.pCode', $params['customer'])
            ->where('invoices.idAffiliate', $params['idAffiliate'])
            ->where('releasing.qtyLeft > 0', NULL, false)
            ->where('invoices.archived', NEGATIVE)
		    ->where('invoices.status', APPROVED)
            ->where('invoices.idModule', 18)
            ->where_not_in('invoices.cancelTag', 1)
            ->where("invoices.date <= '{$params['transaction_date']}'", NULL, false)
            ->group_by('invoices.idInvoice');
        return $this->db->get()->result_array();
    }

    public function currentInvoice($id){
        $this->db->select("
            invoices.idInvoice AS id,
            CONCAT(reference.code, ' - ', invoices.referenceNum) as refcode
		")
		->from('invoices')
		->join('reference', 'reference.idReference = invoices.idReference', 'LEFT')
		->where('invoices.idInvoice', $id);

		return $this->db->get()->result_array();
    }

    public function deleteAssociateChild($params){
        $this->db->where_in('idInvoice', $params['id'])
            ->delete(['receiving', 'posting']);
	}
	
	public function insert($data,$table){
        $this->db->insert($table,$data);
        return $this->db->insert_id();
	}
	
	public function insertBulk($data, $table){
        $this->db->insert_batch($table, $data); 
    }
    public function updateBulk($data, $table, $fields){
        foreach($data as $inx=>$item){
            $this->db->where($fields, $item[$fields])
            ->update($table, $item);
        }
    }

    public function update($table, $data, $where){
        $this->db->update($table, $data, $where);
	}
    public function selectInvoices($select, $where){
        return $this->db->select($select)->from('invoices')->where($where)->get()->result_array();
    }
    public function viewAll($params){
        if( isset( $params['filterBy'] ) ){
            if( isset( $params['filterValue'] ) ){
                if( (int)$params['filterValue'] > 0 ) $this->db->where( $params['filterBy'], (int)$params['filterValue'] );
            }
        }
        if( isset( $params['subFilter0'] ) ){
            if( isset( $params['query0'] ) ){
                if( (int)$params['query0'] > 0 ) $this->db->where( 'invoices.'.$params['subFilter0'], (int)$params['query0'] );
            }
            $params['subFilter0'] = NULL;
        }
        $this->db->where( 'invoices.idAffiliate', $this->session->userdata('AFFILIATEID') );
        $this->db->select("
            invoices.idInvoice AS id,
            affiliate.affiliateName AS affiliate,
            costcenter.costCenterName AS cost_center,
            DATE_FORMAT(invoices.date, '%Y-%c-%d') AS date,
            CONCAT(reference.code,' - ', invoices.referenceNum) AS reference,
            customer.name AS customer,
            customer.sk as customerSK,
            employee.name AS prepared_by,
            noteEmp.name AS noted_by,
            (
                CASE
                    WHEN invoices.status = 1 THEN 'Pending'
                    WHEN invoices.status = 2 THEN 'Approved'
                    ELSE 'Cancelled'
                END
            ) AS status,
            invoices.amount AS total,
            invoices.amount AS amount

            ,invoices.idReference
            ,invoices.referenceNum
            ,invoices.idModule
        ")
            ->from('invoices')
            ->join('affiliate','affiliate.idAffiliate = invoices.idAffiliate','LEFT')
            ->join('costcenter','costcenter.idCostCenter = invoices.idCostCenter','LEFT')
            ->join('reference','reference.idReference = invoices.idReference','LEFT')
            ->join('customer','customer.idCustomer = invoices.pCode','LEFT')
            ->join('eu','eu.idEu = invoices.preparedBy','LEFT')
            ->join('employee','employee.idEmployee = eu.idEmployee','LEFT')
            ->join('eu AS noteEu','noteEu.idEu = invoices.notedby','LEFT')
            ->join('employee AS noteEmp','noteEmp.idEmployee = noteEu.idEmployee','LEFT')
            ->where('invoices.archived', NEGATIVE)
            ->where("invoices.idAffiliate IN (SELECT idAffiliate FROM employeeaffiliate WHERE idEmployee = {$this->session->userdata('EMPLOYEEID')})", NULL, FALSE)
            ->where('invoices.idModule', 21);
        $params['db'] = $this->db;
        $params['order_by'] = 'invoices.idInvoice DESC';
        // if(isset($params['pdf']) && $params['pdf']) return $this->db->get()->result_array();
        return getGridList($params);
    }

    public function retrieveAll($params){
        return  $this->db->select("
            idInvoice,
            cancelTag,
            idAffiliate,
            idReference,
            referenceNum,
            idCostCenter,
            idReferenceSeries,
            idModule AS idmodule,
            DATE_FORMAT(date, '%Y-%m-%d') AS tdate,
            DATE_FORMAT(date, '%h:%i %p') AS ttime,
            pCode,
            fident,
            status,
            remarks AS remark
        ")
        ->from('invoices')
        ->where('invoices.idInvoice', $params['id'])
        ->get()->result_array();

       

    }
    public function retrieveItems($params){
        return  $this->db->select("
            item.idItem AS id,
            item.sk,
            item.barcode AS code,
            item.itemName AS name,
            itemclassification.className AS class,
            unit.unitCode AS unit,
            releasing.cost,
            releasing.price,
            (releasing.qtyLeft + receiving.qtyLeft) AS remaining,
            receiving.qty,
            (receiving.qty* releasing.price) AS amount,
            releasing.idReleasing AS releasedID

        ")
        ->from('receiving')
        ->join('releasing', 'receiving.fident = releasing.idReleasing','LEFT')
        ->join('item', 'item.idItem = receiving.idItem','LEFT')
        ->join('itemclassification', 'itemclassification.idItemClass = item.idItemClass','LEFT')
        ->join('unit', 'unit.idUnit = item.idUnit','LEFT')
        ->where('receiving.idInvoice', $params['id'])
        ->get()->result_array();
    }

    private function saveReceived($item, $fident, $qty){
        $item['fIdent'] = $fident;
		$item['qty'] = $qty;
		$item['qtyLeft'] = $qty;
		$this->db->insert('releasing',$item); 
    }

    public function searchHistoryGrid( $params )
    {
        $this->db->select('
            invoices.idInvoice as id
            ,CONCAT( reference.code , " - " , invoices.referenceNum ) as name
        ');

        $this->db->from( 'invoices' );
        $this->db->join( 'reference'    , 'reference.idReference = invoices.idReference' );
        $this->db->where( 'invoices.idAffiliate', $this->session->userdata('AFFILIATEID') );
        $this->db->where( 'invoices.idModule' , SALES_RETURN );
        $this->db->where( 'invoices.archived' , 0 );

        if( isset( $params['query'] ) ) {
            $this->db->like("CONCAT( reference.code , ' - ' , invoices.referenceNum )", $params['query'], "both");
        }

        $this->db->group_by( 'invoices.idInvoice' );
        return $this->db->get()->result_array();
    }

    public function checkMutation($table , $where){
		$this->db->select("
			CASE
				WHEN SUM(qtyLeft) != SUM(qty) THEN 2
				ELSE 0
			END AS state
		")
		->from($table)
		->where($where);
		return $this->db->get()->result_array();
    }
    
    public function cancelTag( $id )
    {
        return $this->db->select( "cancelTag" )
        ->where( "invoices.idInvoice" , $id )
        ->get( "invoices" )->row()->cancelTag;
    }

    public function isClosedJE( $params )
    {
        $invMonth   = date( 'm' , strtotime( $params['tdate'] ) );
        $invYear    = date( 'Y' , strtotime( $params['tdate'] ) );
        
        return $this->db->select( "count(*) as count" )
        ->where( 'idModule' , 35 )
        ->where( 'month' , $invMonth )
        ->where( 'year' , $invYear )
        ->where_not_in( 'archived' , 1 )
        ->get( "invoices" )->row()->count;
    }
}