<?php

class ModelPaymentC2cUniversal extends Model {
		
	/**
	 * Вызывается движком, возвращает код, наименование и позицию в списке методов оплаты
	 */
	public function getMethod($address, $total) {

		$this->load->language('payment/c2c_universal');

		if ($total > 0) {
			if ($this->config->get('c2c_universal_status')) {
				$status = true;
			}
		}
		
		$title = $this->language->get('text_title');

		$method_data = array();

		if ($status) {

			$method_data = array(
				'code'       => 'c2c_universal',
				'title'      => $title,
				'sort_order' => $this->config->get('c2c_universal_sort_order')
			);

		}
		
		return $method_data;
	}

	
}

?>