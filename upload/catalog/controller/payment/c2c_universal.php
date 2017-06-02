<?php

class ControllerPaymentC2cUniversal extends Controller {

	//Функция генерации инструкции и кнопки оформления заказа
	
	protected function index() {
		
		//Load language file
		$this->language->load('payment/c2c_universal');

		//Set title from language file
		$this->data['heading_title'] = $this->language->get('heading_title');

		//Load model
		$this->load->model('payment/c2c_universal');
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$this->data['continue'] =  $order_info['store_url'] . 'index.php?route=payment/c2c_universal/success&order='.$order_info['order_id'] . '&first=1';

		//Select template
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/c2c_universal.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/c2c_universal.tpl';
		} else {
			$this->template = 'default/template/payment/c2c_universal.tpl';
		}

		//Render page
		$this->render();
	}

	//Функция подтверждения заказа, пишет заказ в БД

	public function confirm() {

		$this->load->model('checkout/order');

		$wait_order_status_id = $this->config->get('c2c_universal_wait_order_status_id');

		$this->model_checkout_order->confirm($this->session->data['order_id'], $wait_order_status_id, '', true);
	}

	//Функция, вызываемая при успешном подтверждении заказа, рендерит страницу с формой оплаты

	public function success() {

		$this->language->load('payment/c2c_universal');

		$this->data['text_4number_button'] = $this->language->get('text_4number_button');
		$this->data['text_4number_label'] = $this->language->get('text_4number_label');

		//Если заказ оформлен успешно

		if (isset($this->request->get['order'])) {

			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($this->request->get['order']);

			$secure_code = substr(md5($order_info['order_id'] . "c2c_universal"), 0, 12);

			$this->load->model('payment/c2c_universal');

			if ($this->customer->isLogged()) {
				$this->data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/order/info&order_id=' . $this->request->get['order'], '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('information/contact'));
			} else {
				$this->data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
			}

			$this->data['order'] = $order_info['order_id'];
			$this->data['secure_code'] = $secure_code;
			$this->data['action_url'] = $order_info['store_url'] . 'index.php';

			$this->data['defaulttext'] = sprintf($this->language->get('text_default'), $this->config->get('c2c_universal_card'), (int)$order_info['total']);

		} else {
			if ($this->customer->isLogged()) {
				$this->data['text_message'] = sprintf($this->language->get('text_customernotorder'), $this->url->link('account/order', '', 'SSL'), $this->url->link('information/contact'));
			} else {
				$this->data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
			}
		}

		if (isset($this->request->get['first'])){

			if (isset($this->session->data['order_id'])) {
				$this->cart->clear();

				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
				unset($this->session->data['guest']);
				unset($this->session->data['comment']);
				unset($this->session->data['order_id']);	
				unset($this->session->data['coupon']);
				unset($this->session->data['reward']);
				unset($this->session->data['voucher']);
				unset($this->session->data['vouchers']);
			}

			$this->data['heading_title'] = sprintf($this->language->get('text_neworder'), $this->request->get['order']);
			$this->document->setTitle(sprintf($this->language->get('text_neworder'), $this->request->get['order']));
		}
		else {

			if (isset($this->request->get['order'])) {
				$this->data['heading_title'] = sprintf($this->language->get('heading_title_customer'), $this->request->get['order']);
				$this->document->setTitle(sprintf($this->language->get('heading_title_customer'), $this->request->get['order']));
			} else {
				$this->data['heading_title'] = $this->language->get('heading_title');
				$this->document->setTitle($this->language->get('heading_title'));
			}
		}


		$this->data['continue'] = $this->url->link('common/home');
		$this->data['button_continue'] = $this->language->get('button_continue');

		$bank_code = $this->config->get('c2c_universal_active_bank');

		switch ($bank_code) {
			case 1:
			$this->data['iframe']['url'] = "https://3ds.payment.ru/P2P_ACTION/card_form.html";
			$this->data['iframe']['height'] = "500";
			$this->data['iframe']['margin-top'] = "-140";
			break;
			case 2:
			$this->data['iframe']['url'] = "https://p2p.mdm.ru/";
			$this->data['iframe']['height'] = "2100";
			$this->data['iframe']['margin-top'] = "-120";
			break;
			case 4:
			$this->data['iframe']['url'] = "https://www.tinkoff.ru/cardtocard/";
			$this->data['iframe']['height'] = "1020";
			$this->data['iframe']['margin-top'] = "-195";
			break;
			default:
				# code...
			break;
		}

		if (isset($this->request->get['first'])){

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/c2c_success.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/payment/c2c_success.tpl';
			} else {
				$this->template = 'default/template/payment/c2c_success.tpl';
			}
		} else{

			if (isset($this->request->get['order'])){

				if ($this->config->get('cardtocard_order_status_id') != $order_info['order_status_id']) {
					$this->data['defaulttext'] = sprintf($this->language->get('text_nopay'), $this->request->get['order'], $this->url->link('information/contact'));
					$this->data['heading_title'] = sprintf($this->language->get('heading_title_customer_nopay'), $this->request->get['order']);
					$this->document->setTitle(sprintf($this->language->get('heading_title_customer_nopay'), $this->request->get['order']));
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cardtocard_nopay.tpl')) {
						$this->template = $this->config->get('config_template') . '/template/payment/cardtocard_nopay.tpl';
					} else {
						$this->template = 'default/template/payment/cardtocard_nopay.tpl';
					}
				} else {

					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cardtocard_again.tpl')) {
						$this->template = $this->config->get('config_template') . '/template/payment/cardtocard_again.tpl';
					} else {
						$this->template = 'default/template/payment/cardtocard_again.tpl';
					}
				}
			} else{
				$this->data['defaulttext'] = sprintf($this->language->get('text_static'));
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cardtocard_static.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/payment/cardtocard_static.tpl';
				} else {
					$this->template = 'default/template/payment/cardtocard_static.tpl';
				}
			}

		}

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'			
			);

		$this->response->setOutput($this->render());
	}

	//Функция вызывается при подтверждении отплаты
	
	public function payed() {

		if (!isset($this->request->get['code']) || !isset($this->request->get['order']) || !isset($this->request->get['d4num'])){
			$this->redirect($this->url->link('error/not_found'));
		}

		$this->load->model('checkout/order');
		$this->language->load('payment/c2c_universal');

		$order_info = $this->model_checkout_order->getOrder($this->request->get['order']);


		$platp = substr(md5($order_info['order_id'] . "c2c_universal"), 0, 12);
		if ($this->request->get['code'] != $platp) {
			$this->redirect($this->url->link('error/not_found'));
		}
		if ($this->config->get('c2c_universal_payed_order_status_id') != $order_info['order_status_id']){

			$message = sprintf($this->language->get('success_customer_alert'), $order_info['order_id'], $this->request->get['d4num']) . "\n";
			$this->model_checkout_order->update($order_info['order_id'], $this->config->get('c2c_universal_payed_order_status_id'), $message, true);


			$subject = sprintf(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $order_info['order_id']);

						        		// Text 
			$text = sprintf($this->language->get('success_admin_alert'), $order_info['order_id'], $this->request->get['d4num']) . "\n";



			$mail = new Mail(); 
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');
			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($order_info['store_name']);
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
			$mail->send();

								        // Send to additional alert emails
			$emails = explode(',', $this->config->get('config_alert_emails'));

			foreach ($emails as $email) {
				if ($email && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}

		$this->data['continue'] = $this->url->link('common/home');
		$this->data['button_continue'] = $this->language->get('button_continue');

		$this->data['heading_title'] = $this->language->get('heading_payed');
		$this->document->setTitle($this->language->get('heading_payed'));


		$this->data['defaulttext'] = sprintf($this->language->get('text_payed'), $this->request->get['order'], $this->url->link('information/contact'));

		if ($this->customer->isLogged()) {
			$this->data['text_message'] = sprintf($this->language->get('text_customernotorder'), $this->url->link('account/order', '', 'SSL'), $this->url->link('information/contact'));
		} else {
			$this->data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/c2c_payed.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/c2c_payed.tpl';
		} else {
			$this->template = 'default/template/payment/c2c_payed.tpl';
		}

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'			
			);

		$this->response->setOutput($this->render());
	}

}
?>