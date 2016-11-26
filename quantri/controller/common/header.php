<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		$data['title'] = $this->document->getTitle();

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$this->load->language('common/header');

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_navigation'] = $this->language->get('text_navigation');
		$data['text_controlpanel'] = $this->language->get('text_controlpanel');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_processing_status'] = $this->language->get('text_processing_status');
		$data['text_complete_status'] = $this->language->get('text_complete_status');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_user'] = $this->language->get('text_user');
		$data['text_online'] = $this->language->get('text_online');
		$data['text_approval'] = $this->language->get('text_approval');
		$data['text_instrument'] = $this->language->get('text_instrument');
		$data['text_stock'] = $this->language->get('text_stock');
		$data['text_review'] = $this->language->get('text_review');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_store'] = $this->language->get('text_store');
		$data['text_front'] = $this->language->get('text_front');
		$data['text_help'] = $this->language->get('text_help');
		$data['text_homepage'] = $this->language->get('text_homepage');
		$data['text_documentation'] = $this->language->get('text_documentation');
		$data['text_support'] = $this->language->get('text_support');
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->staff->getStaffName());
		$data['text_logout'] = $this->language->get('text_logout');
		
		if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
			$data['logged'] = ''; sourectime();

			$data['home'] = $this->url->link('common/dashboard', '', 'SSL');
			
		} else {
			$data['logged'] = true; sourectime();

			$data['home'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
			$data['logout'] = $this->url->link('common/logout', 'token=' . $this->session->data['token'], 'SSL');

			// Orders
			$this->load->model('manage/order');

			// Processing Orders
			$data['processing_status_total'] = $this->model_manage_order->getTotalOrders(array('filter_order_status' => implode(',', $this->config->get('config_processing_status'))));
			$data['processing_status'] = $this->url->link('manage/order', 'token=' . $this->session->data['token'] . '&filter_order_status=' . implode(',', $this->config->get('config_processing_status')), 'SSL');

			// Complete Orders
			$data['complete_status_total'] = $this->model_manage_order->getTotalOrders(array('filter_order_status' => implode(',', $this->config->get('config_complete_status'))));
			$data['complete_status'] = $this->url->link('manage/order', 'token=' . $this->session->data['token'] . '&filter_order_status=' . implode(',', $this->config->get('config_complete_status')), 'SSL');

			// Returns
			$this->load->model('manage/return');

			$return_total = $this->model_manage_return->getTotalReturns(array('filter_return_status_id' => $this->config->get('config_return_status_id')));

			$data['return_total'] = $return_total;

			$data['return'] = $this->url->link('manage/return', 'token=' . $this->session->data['token'], 'SSL');

			// Users
			$this->load->model('report/user');

			$data['online_total'] = $this->model_report_user->getTotalUsersOnline();

			$data['online'] = $this->url->link('report/user_online', 'token=' . $this->session->data['token'], 'SSL');

			$this->load->model('user/user');

			$user_total = $this->model_user_user->getTotalUsers(array('filter_approved' => false));

			$data['user_total'] = $user_total;
			$data['user_approval'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . '&filter_approved=0', 'SSL');

			// Instruments
			$this->load->model('catalog/instrument');

			$instrument_total = $this->model_catalog_instrument->getTotalInstruments(array('filter_quantity' => 0));

			$data['instrument_total'] = $instrument_total;

			$data['instrument'] = $this->url->link('catalog/instrument', 'token=' . $this->session->data['token'] . '&filter_quantity=0', 'SSL');

			// Reviews
			$this->load->model('catalog/review');

			$review_total = $this->model_catalog_review->getTotalReviews(array('filter_status' => false));

			$data['review_total'] = $review_total;

			$data['review'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . '&filter_status=0', 'SSL');

			// Affliate
			$this->load->model('marketing/affiliate');

			$affiliate_total = $this->model_marketing_affiliate->getTotalAffiliates(array('filter_approved' => false));

			$data['affiliate_total'] = $affiliate_total;
			$data['affiliate_approval'] = $this->url->link('marketing/affiliate', 'token=' . $this->session->data['token'] . '&filter_approved=1', 'SSL');

			$data['alerts'] = $user_total + $instrument_total + $review_total + $return_total + $affiliate_total;

			// Online Stores
			$data['stores'] = array();

			$data['stores'][] = array(
				'name' => $this->config->get('config_name'),
				'href' => HTTP_CATALOG
			);

			$this->load->model('setting/store');

			$results = $this->model_setting_store->getStores();

			foreach ($results as $result) {
				$data['stores'][] = array(
					'name' => $result['name'],
					'href' => $result['url']
				);
			}
			
			$data['navigation'] = $this->url->link('common/navigation', 'token=' . $this->session->data['token'], 'SSL');
			$data['controlpanel'] = $this->url->link('common/controlpanel', 'token=' . $this->session->data['token'], 'SSL');
		}

		return $this->load->view('common/header.tpl', $data);
	}
}
