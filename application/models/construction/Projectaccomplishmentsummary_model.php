<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Developer: Niño Niel B. Iroc
 * Module: Logs Summary
 * Date: Jan 11, 2023
 * Finished:
 * Description: 
 * DB Tables:
 * */

class Projectaccomplishmentsummary_model extends CI_Model {

    function getConstructionProject( $params ) {
        $this->db->select('idConstructionProject as id, projectName as name');
		$this->db->join('invoices', 'invoices.idInvoice = project.idInvoice', 'LEFT OUTER');
		$this->db->where( 'invoices.archived', 0);
		$this->db->order_by('projectName ASC');
		return $this->db->get('constructionproject as project')->result_array();
    }

    function getProjectAccomplishmentSummary( $params ) {

        $statement = ($params['idProject'] != 0) ? ' WHERE acc.idConstructionProject = '.$params['idProject'] : ' GROUP BY idConstructionProject ';

        $this->db->select('
            affiliate.affiliateName as affiliateName
            ,affiliate.sk
            ,project.dateStart AS date
            ,concat(reference.code, "-", invoices.referenceNum) as referenceNum 
            ,project.projectName AS projectName
            ,project.dateStart AS dateFrom
            ,project.dateCompleted AS dateTo
            ,totalReleasedAmnt AS percentAccomplished
            ,(CASE 
                WHEN project.status = 1 THEN "Suspended"
                WHEN project.status = 2 THEN "Ongoing"
                ELSE "Completed"
            END) projectStatus
            ,(CASE 
                WHEN project.statusType = 1 THEN "Slippage"
                WHEN project.statusType = 2 THEN "Advance"
                ELSE "On-Time"
            END) AS statusType'
        );

        $this->db->join('invoices'  , 'invoices.idInvoice = project.idInvoice'      , 'left outer');
        $this->db->join('affiliate' , 'affiliate.idAffiliate = invoices.idAffiliate', 'left outer');
        $this->db->join('reference' , 'reference.idReference = invoices.idReference', 'left outer');
        $this->db->join('(
            SELECT 
                SUM(releasing.qty * releasing.unitCost ) as totalReleasedAmnt
                ,idConstructionProject
            FROM
                consprojectaccomplishment AS acc
            LEFT JOIN constructionreleasing as releasing 
                ON releasing.idConsprojectAccomplishment = acc.idConsprojectAccomplishment
            '.$statement.'
        ) AS projectaccomplishment' , 'projectaccomplishment ON  projectaccomplishment.idConstructionProject = projectaccomplishment.idConstructionProject', 'left outer');

        if($params['idProject'] != 0 ){
            $this->db->where('project.idConstructionProject', $params['idProject'] );
        }else {
            $this->db->group_by('project.idConstructionProject,percentAccomplished');
        }

        return $this->db->get('constructionproject as project')->result_array();
        
    }

}


