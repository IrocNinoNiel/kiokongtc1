<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salesordermonitoring_model extends CI_Model {

    public function getReleasable($data){
        if ( $data['idAffiliate'] != 0 ) {
            $this->db->where('invoices.idAffiliate', $data['idAffiliate']);
        }
        $this->db->where("DATE(invoices.date) <= '{$data['dateto']}'", NULL, false);
        if($data['filter'] != '' && $data['filter'] != null){
            switch($data['search']){
                case CUSTOMER:
                    $this->db->where('customer.idCustomer', $data['filter']);
                    break;
                case ITEM:
                    $this->db->where('item.idItem', $data['filter']);
                    break;
                default:
                    $this->db->where($data['typedSearched'] == 'item'? 'item.idItem': 'customer.idCustomer',$data['filter']);
                    break;
            }
        }
        if($data['status'] != '1'){

            switch($data['status']){
                case COMPLETE:
                    $this->db->having('balance = 0'); //MODIFIED BY CHRISTIAN
                    break;
                case INCOMPLETE:
                    $this->db->having('balance < so.qty and balance != 0', NULL, false); //MODIFIED BY CHRISTIAN
                    break;
                default:
                    $this->db->having('balance = so.qty', NULL, false); //MODIFIED BY CHRISTIAN
                    break;
            }
        }
        if(!isset($data['export'])){
            $this->db->select("
            so.idItem,
            invoices.idInvoice,
            invoices.idAffiliate,
            invoices.pCode
            ");
        }
        $this->db->select("
            affiliate.sk    AS affiliateSK,
            item.sk         AS itemSK,
            customer.sk     AS customerSK,
            invoices.idInvoice,
            invoices.idModule,
            affiliate.affiliateName AS affiliate,
            DATE_FORMAT(invoices.date, '%Y-%m-%d %h:%i %p') AS date,
            CONCAT(reference.code, '-', invoices.referenceNum) AS sonumber,
            customer.name AS customer,
            item.itemName AS item,
            unit.unitCode AS unit,
            so.qty AS expectedqty,
            IFNULL(releasing.qtyLeft,0) AS actualqty,
            so.qty - SUM(IFNULL(releasing.qtyLeft,0)) AS balance,
            CASE
                WHEN invoices.cancelTag IN( 1 )  THEN 'Cancelled'
                WHEN so.qty - SUM(IFNULL(releasing.qtyLeft,0)) = 0  THEN 'Complete'
                WHEN so.qty - SUM(IFNULL(releasing.qtyLeft,0)) < so.qty THEN 'Incomplete'
                ELSE 'Not Served'
            END AS status
        ")
            ->from('invoices')
            ->join('so','invoices.idInvoice = so.idInvoice','LEFT')
            ->join("(SELECT
                        invoices.fident,
                        SUM(releasing.qtyLeft) AS qtyLeft,
                        releasing.idItem
                    FROM
                        invoices
                    LEFT JOIN releasing ON releasing.idInvoice = invoices.idInvoice
                    WHERE invoices.idModule = '18'
                    AND DATE(invoices.date) <= '{$data['dateto']}'
                    GROUP BY invoices.fident, releasing.idItem
                ) AS releasing",'releasing.fident = invoices.idInvoice AND releasing.idItem = so.idItem','LEFT')
            ->join('item','item.idItem = so.idItem','LEFT')
            ->join('unit','unit.idUnit = item.idUnit','LEFT')
            ->join('affiliate','affiliate.idAffiliate = invoices.idAffiliate','LEFT')
            ->join('reference','reference.idReference = invoices.idReference','LEFT')
            ->join('customer','customer.idCustomer = invoices.pCode','LEFT')
            ->where('invoices.idModule', 17)
            // ->where('invoices.status', APPROVED)
            // ->where('invoices.archived', NEGATIVE)
            ->group_by('invoices.idInvoice, so.idItem , so.qty , releasing.qtyLeft')
            ->order_by('invoices.date asc');
        return $this->db->get()->result_array();
    }

    public function getCustomers($affiliate, $query){
        if($query != '')   $this->db->like('customer.name', $query);
        if($affiliate != 0)   $this->db->where('customeraffiliate.idAffiliate',$affiliate);

        return $this->db->select("
            customer.idCustomer AS id,
            customer.name AS name,
            'customer' AS type,
            customer.sk
        ")
        ->from('customer')
        ->join('customeraffiliate','customeraffiliate.idCustomer = customer.idCustomer','LEFT')
        ->group_by('customer.idCustomer') //MODIFIED BY CHRISTIAN
        ->get()
        ->result_array();
    }

    public function getItems($affiliate, $query){
        if($query != '')   $this->db->like('item.itemName', $query);
        if($affiliate != 0)   $this->db->where('itemaffiliate.idAffiliate',$affiliate);

        return $this->db->select("
            item.idItem AS id,
            item.itemName AS name,
            'item' AS type,
            item.sk
        ")
        ->from('item')
        ->join('itemaffiliate','itemaffiliate.idItem = item.idItem','LEFT')
        ->group_by('item.idItem') //MODIFIED BY CHRISTIAN
        ->get()
        ->result_array();
    }

    public function getItemsByInvoice($affiliate, $query, $invoice){
        if($query != '')   $this->db->like('item.itemName', $query);

        return $this->db->select("
            item.idItem AS id,
            item.itemName AS name,
            'item' AS type,
            item.sk
        ")
        ->from('invoices')
        ->join('so','so.idInvoice = invoices.idInvoice','LEFT')
        ->join('item','so.idInvoice = invoices.idInvoice','LEFT')
        ->join('itemaffiliate','item.idItem = so.idItem','LEFT')
        ->where('itemaffiliate.idAffiliate',$affiliate)
        ->where('invoices.idInvoice',$invoice)
        ->group_by('item.idItem')
        ->get()
        ->result_array();
    }

    public function getSOList($affiliate, $customer){
        if($customer != '') $this->db->where('invoices.pCode', $customer);
        $this->db->select("
            invoices.idInvoice AS id,
            CONCAT(reference.code, '-',invoices.referenceNum) AS name,
        ")
        ->from('so')
        ->join('invoices','so.idInvoice = invoices.idInvoice','LEFT')
        ->join('reference','reference.idReference = invoices.idReference','LEFT')
        ->where('invoices.idAffiliate', $affiliate)
        ->where('invoices.archived', NEGATIVE)
        ->where('invoices.status', APPROVED)
        ->group_by('invoices.idInvoice');
        return $this->db->get()->result_array();
    }

    public function getLedger($data){
        $this->db->query("SET @balance := 0");
        $notarchived = NEGATIVE;
        $approved = APPROVED;
        $nulled = NULL;
        $balance = $this->db->select("
            invoices.date AS date,
            0 AS expectedqty,
            'Running Balance' AS reference,
            0 AS deliveredqty,
            so.qty AS balance
        ", false)
        ->from('invoices')
        ->join('so','invoices.idInvoice = so.idInvoice','LEFT')
        ->where("DATE(invoices.date) BETWEEN '{$data['datefrom']}' AND '{$data['dateto']}'", NULL, FALSE)
        ->where('invoices.idInvoice', $data['sonumber'])
        ->where('so.idItem', $data['itemname'])
        ->get_compiled_select();

        $this->db->reset_query();

        $beforedate = $this->db->select("
            MAX(invoicesReceive.date) AS date,
            0 AS expectedqty,
            'Running Balances' AS reference,
            SUM(IFNULL(releasing.qtyLeft, 0)) AS deliveredqty,
            0 AS balance
        ", false)
        ->from('invoices')
        ->join('invoices AS invoicesReceive',"invoicesReceive.fident = invoices.idInvoice AND invoicesReceive.status = {$approved} AND invoicesReceive.archived = {$notarchived} AND invoicesReceive.idModule = 18 AND DATE(invoicesReceive.date) < '{$data['datefrom']}'",'LEFT')
        ->join('releasing','releasing.idInvoice = invoicesReceive.idInvoice','LEFT')
        ->where("DATE(invoices.date) BETWEEN '{$data['datefrom']}' AND '{$data['dateto']}'", NULL, FALSE) //MODIFIED BY CHRISTIAN
        ->where('invoices.idInvoice', $data['sonumber'])
        ->where('releasing.idItem', $data['itemname'])
        ->group_by('releasing.idItem')
        ->get_compiled_select();

        $this->db->reset_query();


        $runningbalance = $this->db->select("
            0 as idInvoice,
            0 as idModule,
            MAX(running.date) AS date,
            running.reference,
            0 AS expectedqty,
            0 AS deliveredqty,
            (running.balance - SUM(running.deliveredqty)) AS balance
        ", false)
        ->from("({$balance} UNION ALL {$beforedate}) AS running")
        ->group_by( " running.reference , running.balance" )
        ->get_compiled_select();

        $this->db->reset_query();


        $transaction = $this->db->select("
            invoices.idInvoice,
            invoices.idModule,
            invoices.date,
            CONCAT(reference.code, '-', invoices.referenceNum) AS reference,
            0 AS expectedqty,
            releasing.qtyLeft AS deliveredqty,
            COALESCE(NULL) AS balance
        ", false)
        ->from('invoices')
        ->join('reference', 'reference.idReference = invoices.idReference', 'LEFT')
        ->join('releasing', 'releasing.idInvoice = invoices.idInvoice', 'LEFT')
        ->where('invoices.fident', $data['sonumber'])
        ->where('releasing.idItem', $data['itemname'])
        ->where("DATE(invoices.date) BETWEEN '{$data['datefrom']}' AND '{$data['dateto']}'", NULL, FALSE) //MODIFIED BY CHRISTIAN
        ->get_compiled_select();

        $this->db->reset_query();

        return $this->db->query(
            " SELECT
            ledger.date,
            ledger.reference,
            ledger.expectedqty,
            ledger.deliveredqty,
            ledger.idInvoice,
            ledger.idModule,
            @balance := COALESCE(ledger.balance, @balance - IFNULL(ledger.deliveredqty, 0), 0) AS balance
            FROM (
                {$runningbalance} UNION ALL {$transaction}
            ) AS ledger
        ")->result_array();

        // return $this->db->select("
        //     ledger.date,
        //     ledger.reference,
        //     ledger.expectedqty,
        //     ledger.deliveredqty,
        //     @balance := COALESCE(ledger.balance, @balance - IFNULL(ledger.deliveredqty, 0), 0) AS balance
        // ", false)
        // ->get("({$runningbalance} UNION ALL {$transaction}) AS ledger")
        // ->result_array();
    }
}