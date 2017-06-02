<?php

/**
 * Модуль для быстро перевода с карты на карту. Работает через iframe со страницами перевода любого банка.
 * @author Oleg Brizhanev<mr.brizhanev@yandex.ru>
 */

class ControllerPaymentC2cUniversal extends Controller {
	
	private $error = array(); 
	
	public function index() {   
		//Load language file
		$this->load->language('payment/c2c_universal');

		//Set title from language file
		$this->document->setTitle($this->language->get('heading_title'));
		
		//Load settings model
		$this->load->model('setting/setting');
		
		//Save settings
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('c2c_universal', $this->request->post);		

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		//Создание текстовых меток
		
		$text_strings = array(
			'heading_title',
			'button_save',
			'button_cancel',
			'button_add_module',
			'button_remove',
			'text_card_number',
			'text_select_bank',
			'entry_status',
			'entry_order_status',
			'entry_valid_order_status',
			'text_enabled',
			'text_disabled'
			);
		
		foreach ($text_strings as $text) {
			$this->data[$text] = $this->language->get($text);
		}

		//Установка банков
		//TO-DO сделать добавление из админки

		$this->data['banks'] = array(
			array(
				'code' => 1,
				'name' => 'Промсвязь банк'
				),
			array(
				'code' => 2,
				'name' => 'Бинбанк'
				),
			array(
				'code' => 4,
				'name' => 'Тинькофф'
				)		
			);

		//Установка списка статусов заказов

		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		//Установка значений полей 

		if (isset($this->request->post['c2c_universal_active_bank'])) {
			$this->data['c2c_universal_active_bank'] = $this->request->post['c2c_universal_active_bank'];
		} else {
			$this->data['c2c_universal_active_bank'] = $this->config->get('c2c_universal_active_bank');
		}

		if (isset($this->request->post['c2c_universal_card'])) {
			$this->data['c2c_universal_card'] = $this->request->post['c2c_universal_card'];
		} else {
			$this->data['c2c_universal_card'] = $this->config->get('c2c_universal_card');
		}

		if (isset($this->request->post['c2c_universal_status'])) {
			$this->data['c2c_universal_status'] = $this->request->post['c2c_universal_status'];
		} else {
			$this->data['c2c_universal_status'] = $this->config->get('c2c_universal_status');
		}

		if (isset($this->request->post['c2c_universal_wait_order_status_id'])) {
			$this->data['c2c_universal_wait_order_status_id'] = $this->request->post['c2c_universal_wait_order_status_id'];
		} else {
			$this->data['c2c_universal_wait_order_status_id'] = $this->config->get('c2c_universal_wait_order_status_id');
		}

		if (isset($this->request->post['c2c_universal_payed_order_status_id'])) {
			$this->data['c2c_universal_payed_order_status_id'] = $this->request->post['c2c_universal_payed_order_status_id'];
		} else {
			$this->data['c2c_universal_payed_order_status_id'] = $this->config->get('c2c_universal_payed_order_status_id');
		}

		//error handling
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
			);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
			);
		
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/c2c_universal', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
			);
		
		$this->data['action'] = $this->url->link('payment/c2c_universal', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');


		//Check if multiple instances of this module
		$this->data['modules'] = array();
		
		if (isset($this->request->post['c2c_universal_module'])) {
			$this->data['modules'] = $this->request->post['c2c_universal_module'];
		} elseif ($this->config->get('c2c_universal_module')) { 
			$this->data['modules'] = $this->config->get('c2c_universal_module');
		}		

		//Prepare for display
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		
		$this->template = 'payment/c2c_universal.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
			);

		//Send the output.
		$this->response->setOutput($this->render());
	}
	
	/*
	 * 
	 * Check that user actions are authorized
	 * 
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/c2c_universal')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}

	/**
	 * Вызывается движком во время установки модуля
	 */
	public function install() {

		$this->load->model('setting/setting');

		$settings = array();

		//Статус для созданного заказа по умолчанию - "В обработке"
		$settings['c2c_universal_wait_order_status_id'] =  2;

		//Статус для оплаченного заказа по умолчанию - "Оплачен"
		$settings['c2c_universal_payed_order_status_id'] = 18;

		//Порядок сортировки по умолчанию  - 0
		$settings['c2c_universal_sort_order'] = 0;

		//Номер карты, заполним тестовыми данными
		$settings['c2c_universal_card'] = "4XXX XXXX XXXX XXXX";

		$this->model_setting_setting->editSetting('c2c_universal', $settings);	


	}


}
?>