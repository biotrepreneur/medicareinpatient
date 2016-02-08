<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inpatient_model extends CI_Model {

	// return list of providers by city
	public function query_city($city)
	{
		$this->db->distinct();
		$this->db->select('Provider_Id');
		//$this->db->where('Provider_City'=>$city);
		//$query = $this->db->get('inpatient');

		$query = $this->db->get_where('WNYHealth', array('Provider_City'=>$city));
		return $query->result_array();
	}

	// return metrics for specific provider on specific code
	public function query_drd($providerID, $DRD)
	{
		$query = $this->db->get_where('WNYHealth', array('Provider_Id'=>$providerID, 'DRD'=>$DRD));
		
		//if(count($query)==0){
		//	return false;
		//} else {
			return $query->result_array();
		//}

	}

	public function fetch_all() {
		$query = $this->db->get('WNYHealth');
		//$query = $this->db->get_where('inpatientWNY', array('Id'=>1));
		return $query->result_array();
	}

	public function update_drd($Id, $DRD)
	{

		$data = array(
			'DRD' => $DRD
		);

		$this->db->where('Id', $Id);
		$this->db->update('WNYHealth', $data);

	}

}

/* End of file inpatient_model.php */
/* Location: ./application/models/Inpatient_model.php */ 