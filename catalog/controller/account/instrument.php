<?php
class ControllerAccountInstrument extends Controller {
	public function index() {
		$this->load->model('design/block');

		$this->load->language('account/instrument');
		
		$this->load->model('catalog/category');

		$this->load->model('catalog/instrument');

		$this->load->model('tool/image');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_refine'] = $this->language->get('text_refine');
		$data['text_empty'] = $this->language->get('text_empty');
		$data['text_quantity'] = $this->language->get('text_quantity');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_model'] = $this->language->get('text_model');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['text_points'] = $this->language->get('text_points');
		$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		$data['text_sort'] = $this->language->get('text_sort');
		$data['text_limit'] = $this->language->get('text_limit');

		$data['button_order'] = $this->language->get('button_order');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_list'] = $this->language->get('button_list');
		$data['button_grid'] = $this->language->get('button_grid');

		// Set the last category breadcrumb
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		
		// Set the last category breadcrumb
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account')
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/instrument')
		);
		
		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_instrument_limit');
		}

		$data['instruments'] = array();

		$filter_data = array(
			'filter_filter'      => $filter,
			'sort'               => $sort,
			'order'              => $order,
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit
		);

		$instrument_total = $this->model_catalog_instrument->getTotalInstruments($filter_data);

		$results = $this->model_catalog_instrument->getInstruments($filter_data);

		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_instrument_width'), $this->config->get('config_image_instrument_height'));
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_instrument_width'), $this->config->get('config_image_instrument_height'));
			}

			if (($this->config->get('config_user_price') && $this->user->isLogged()) || !$this->config->get('config_user_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = false;
			}

			if ((float)$result['special']) {
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}

			if ($this->config->get('config_tax')) {
				$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
			} else {
				$tax = false;
			}

			if ($this->config->get('config_review_status')) {
				$rating = (int)$result['rating'];
			} else {
				$rating = false;
			}

			$data['instruments'][] = array(
				'instrument_id'  => $result['instrument_id'],
				'thumb'       => $image,
				'name'        => $result['name'],
				'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
				'quantity'        => $result['quantity'],
			);
		}

		$url = '';

		if (isset($this->request->get['filter'])) {
			$url .= '&filter=' . $this->request->get['filter'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
		
		$url = '';

		if (isset($this->request->get['filter'])) {
			$url .= '&filter=' . $this->request->get['filter'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['limits'] = array();

		$limits = array_unique(array($this->config->get('config_instrument_limit'), 25, 50, 75, 100));

		sort($limits);

		foreach($limits as $value) {
			$data['limits'][] = array(
				'text'  => $value,
				'value' => $value,
				'href'  => $this->url->link('account/instrument')
			);
		}

		$url = '';

		if (isset($this->request->get['filter'])) {
			$url .= '&filter=' . $this->request->get['filter'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$pagination = new Pagination();
		$pagination->total = $instrument_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('account/instrument', $url . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($instrument_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($instrument_total - $limit)) ? $instrument_total : ((($page - 1) * $limit) + $limit), $instrument_total, ceil($instrument_total / $limit));

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['limit'] = $limit;

		$data['continue'] = $this->url->link('common/home');

		$this->load->model('design/page_config');
		
		$instrument_item = $this->model_design_page_config->getPageConfig('item', 'instrument');
		
		if(!empty($instrument_item)){
			$instrument_item_block = $this->model_design_block->getBlockByCode($instrument_item);
			$data['instrument_item'] = $instrument_item_block['html'];
		}else{
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/instrument_item.tpl')) {
				$data['instrument_item'] = @file_get_contents(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/instrument_item.tpl');
			} else {
				$data['instrument_item'] = @file_get_contents(DIR_TEMPLATE . 'default/template/account/instrument_item.tpl');
			}
		}
		
		$data['_this'] = $this;

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/instrument.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/instrument.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/instrument.tpl', $data));
		}
	}
	
	public function borrow() {
		$json = array();
		$this->cart->clear();
		
		if(isset($this->request->post['instrument'])) {
			foreach ($this->request->post['instrument'] as $instrument) {
				$this->cart->add($instrument, 1);
			}
		} else {
			$json['error'] = 'Bạn chưa chọn Dụng cụ nào!';
		}
		
		if ($json) {
			echo json_encode($json);
			exit();
		}
		// Validate quantity.
		$instruments = $this->cart->getInstruments();

		foreach ($instruments as $instrument) {
			if (!$instrument['stock']) {
				$json['error'] = 'Dụng cụ đã hết. Vui lòng chọn dụng cụ khác!';
				break;
			}
		}
		
		
		if ($json) {
			echo json_encode($json);
			exit();
		}

		if ($this->user->isLogged()) {
			$this->load->model('account/user');

			$user_info = $this->model_account_user->getUser($this->user->getId());

			$order_data['user_id'] = $this->user->getId();
			$order_data['user_group_id'] = $user_info['user_group_id'];
			$order_data['code'] = $user_info['code'];
			$order_data['firstname'] = $user_info['firstname'];
			$order_data['lastname'] = $user_info['lastname'];
			$order_data['email'] = $user_info['email'];
			$order_data['telephone'] = $user_info['telephone'];
			$order_data['fax'] = $user_info['fax'];
			$order_data['custom_field'] = json_decode($user_info['custom_field'], true);
		} else {
			$json['error'] = 'Bạn cần đăng nhập để mượn dụng cụ!';
		}
		
		if ($json) {
			echo json_encode($json);
			exit();
		}
		
		$order_data['instruments'] = array();

		foreach ($this->cart->getInstruments() as $instrument) {
			$order_data['instruments'][] = array(
				'instrument_id' => $instrument['instrument_id'],
				'name'       => $instrument['name'],
				'model'      => $instrument['model'],
				'download'   => $instrument['download'],
				'quantity'   => $instrument['quantity'],
				'subtract'   => $instrument['subtract'],
				'total'      => $instrument['total'],
				'reward'     => $instrument['reward']
			);
		}
		
		$this->load->model('borrow/order');
		
		$order_id = $this->model_borrow_order->addOrder($order_data);
		
		$this->model_borrow_order->addOrderHistory($order_id, $this->config->get('config_order_status_id'));
		
		$this->cart->clear();
		
		$json['success'] = 'Bạn đã đặt mượn dụng cụ thành công!';
		
		echo json_encode($json);
	}
}
