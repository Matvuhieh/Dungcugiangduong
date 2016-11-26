<?php
class ControllerCommonNavigation extends Controller {
	public function index($data = array()) {
		$this->load->model('design/block');
		
		if(isset($data['style'])){
			$style_code = $data['style'];
			
			$block = $this->model_design_block->getBlockByCode($style_code);
			
			$script_libs = json_decode($block['script_lib'], true);
			
			foreach ($script_libs as $script_lib){
				$this->document->addScript('catalog/view/javascript/block/' . $script_lib);
			}
			
			$css_libs = json_decode($block['css_lib'], true);
			
			foreach ($css_libs as $css_lib){
				$this->document->addStyle('catalog/view/stylesheet/block/' . $css_lib);
			}
		}

		$data['header_top'] = $this->load->controller('common/header_top');

		$this->load->language('common/navigation');
		
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->user->getFirstName(), $this->url->link('account/logout', '', 'SSL'));

		$data['text_account'] = $this->language->get('text_account');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_all'] = $this->language->get('text_all');

		$data['logged'] = $this->user->isLogged();
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['login'] = $this->url->link('account/login', '', 'SSL');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');
		
		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		
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
		
		$data['telephone'] = $this->config->get('config_telephone');
		$data['email'] = $this->config->get('config_email');
		$data['address'] = $this->config->get('config_address');
		
		$data['_this'] = $this;
		
		if(isset($style_code)) return $this->load->style($style_code, $data);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/navigation.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/navigation.tpl', $data);
		} else {
			return $this->load->view('default/template/common/navigation.tpl', $data);
		}
	}
}
