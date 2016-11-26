<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		
		$this->load->model('design/block');
		$this->load->model('design/layout');
		
		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = 'common/home';
		}
 
		$data['layout_id'] = 0;

		if ($route == 'instrument/category' && isset($this->request->get['path'])) {
			$this->load->model('catalog/category');

			$path = explode('_', (string)$this->request->get['path']);

			$data['layout_id'] = $this->model_catalog_category->getCategoryLayoutId(end($path));
		} else if ($route == 'instrument/instrument' && isset($this->request->get['instrument_id'])) {
			$this->load->model('catalog/instrument');

			$data['layout_id'] = $this->model_catalog_instrument->getInstrumentLayoutId($this->request->get['instrument_id']);
		} else if ($route == 'information/information' && isset($this->request->get['information_id'])) {
			$this->load->model('catalog/information');

			$data['layout_id'] = $this->model_catalog_information->getInformationLayoutId($this->request->get['information_id']);
		}

		if (!$data['layout_id']) {
			$data['layout_id'] = $this->model_design_layout->getLayout($route);
		}

		if (!$data['layout_id']) {
			$data['layout_id'] = $this->config->get('config_layout_id');
		}
		
		// Analytics
		$this->load->model('extension/extension');

		$data['analytics'] = array();

		$analytics = $this->model_extension_extension->getExtensions('analytics');

		foreach ($analytics as $analytic) {
			if ($this->config->get($analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('analytics/' . $analytic['code']);
			}
		}

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}
		
		$data['header_top'] = $this->load->controller('common/header_top');
		
		// Navigation
		$language_id = (int)$this->config->get('config_language_id');
		
		$this->load->model('design/navigation');
		
		$navigations = $this->model_design_navigation->getNavigations();
		
		$data['navigations'] = array();
		
		foreach ($navigations as $navigation){
			$children_data = array();
			
			$childrens = $this->model_design_navigation->getNavigations((int)$navigation['id']);
			
			foreach($childrens as $children){
				$children_data[] = array(
				  'navigation_id' => $children['id'],
				  'title' => isset($children['title'][$language_id]) ? html_entity_decode($children['title'][$language_id]) : '',
				  'url' => $children['url'],
				  'icon' => $children['icon']
				);
			}
			
			$data['navigations'][] = array(
			  'navigation_id' => $navigation['id'],
			  'title' => isset($navigation['title'][$language_id]) ? html_entity_decode($navigation['title'][$language_id]) : '',
			  'url' => $navigation['url'],
			  'icon' => $navigation['icon'],
			  'children' => $children_data
			);
		}
		
		// Menu
		$data['megamenu'] = $this->load->controller('common/megamenu');

		$data['title'] = $this->document->getTitle();

		$data['base'] = $server;
		$data['uri'] = html_entity_decode($server . $this->request->server['REQUEST_URI']);
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['image'] = $this->document->getImage();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');

		$data['text_home'] = $this->language->get('text_home');

		// Wishlist
		if ($this->user->isLogged()) {
			$this->load->model('account/wishlist');

			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->user->getFirstName(), $this->url->link('account/logout', '', 'SSL'));

		$data['text_account'] = $this->language->get('text_account');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_borrow'] = $this->language->get('text_borrow');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_all'] = $this->language->get('text_all');

		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$data['logged'] = $this->user->isLogged();
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['login'] = $this->url->link('account/login', '', 'SSL');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');
		$data['shopping_cart'] = $this->url->link('borrow/cart');
		$data['borrow'] = $this->url->link('borrow/borrow', '', 'SSL');
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');

		$status = true;

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$robots = explode("\n", str_replace(array("\r\n", "\r"), "\n", trim($this->config->get('config_robots'))));

			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;

					break;
				}
			}
		}

		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');

		// For page specific css
		if (isset($this->request->get['route'])) {
			if (isset($this->request->get['instrument_id'])) {
				$class = '-' . $this->request->get['instrument_id'];
			} elseif (isset($this->request->get['path'])) {
				$class = '-' . $this->request->get['path'];
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$class = '-' . $this->request->get['manufacturer_id'];
			} else {
				$class = '';
			}

			$data['class'] = str_replace('/', '-', $this->request->get['route']) . $class;
		} else {
			$data['class'] = 'common-home';
		}
		
		$data['_this'] = $this;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/header.tpl', $data);
		} else {
			return $this->load->view('default/template/common/header.tpl', $data);
		}
	}
}
