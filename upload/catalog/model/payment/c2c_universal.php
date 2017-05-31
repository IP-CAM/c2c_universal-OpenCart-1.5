<?php

class ModelPaymentC2cUniversal extends Model {
		
	//Sample DB access - Get all customers
	function getCustomerData() {
		$query = "SELECT * FROM " . DB_PREFIX . "customer";
		$result = $this->db->query($query);
		return $result->rows;
	}

	public function getMethod($address, $total) {

		$this->load->language('payment/c2c_universal');

		if ($total > 0) {
			if ($this->config->get('c2c_universal_status')) {
				$status = true;
			}
		}
		
		$title = $this->language->get('text_title');

		$method_data = array();

		if ($status)

		//TO-DO Сделать поле порядок сортировки

			$method_data = array(
				'code'       => 'c2c_universal',
				'title'      => $title,
				'sort_order' => 0
			);
		
		return $method_data;
	}

	
}

?>