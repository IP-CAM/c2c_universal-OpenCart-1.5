<?php

class ControllerPaymentC2cUniversal extends Controller {
	
	protected function index() {
		
		//Load language file
		$this->language->load('payment/c2c_universal');

		//Set title from language file
      	$this->data['heading_title'] = $this->language->get('heading_title');

		//Load model
		$this->load->model('payment/c2c_universal');

		//Sample - get data from loaded model
		$this->data['customers'] = $this->model_payment_c2c_universal->getCustomerData();

		//Select template
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/c2c_universal.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/c2c_universal.tpl';
		} else {
			$this->template = 'default/template/payment/c2c_universal.tpl';
		}

		//Render page
		$this->render();
	}
	
}
?>