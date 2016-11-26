<?php
class ControllerCommonNavigation extends Controller {
	public function index() {
		$this->load->language('common/navigation');
		$this->load->language('common/menu');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_header'] = $this->language->get('text_header');
		
		$data['text_megamenu'] = $this->language->get('text_megamenu');
		$data['text_navigation'] = $this->language->get('text_navigation');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_instrument'] = $this->language->get('text_instrument');
		$data['text_logo'] = $this->language->get('text_logo');
		$data['text_favicon'] = $this->language->get('text_favicon');
		$data['text_address'] = $this->language->get('text_address');
		$data['text_featured_instrument'] = $this->language->get('text_featured_instrument');
		$data['text_slide'] = $this->language->get('text_slide');
		$data['text_news'] = $this->language->get('text_news');
		$data['text_footer_post'] = $this->language->get('text_footer_post');
		$data['text_social'] = $this->language->get('text_social');
		$data['text_footer_address'] = $this->language->get('text_footer_address');
		$data['text_footer_map'] = $this->language->get('text_footer_map');
		$data['text_header'] = $this->language->get('text_header');
		$data['text_body'] = $this->language->get('text_body');
		$data['text_footer'] = $this->language->get('text_footer');
		
		$data['logo'] = $this->url->link('setting/item/logo', 'token=' . $this->session->data['token'], 'SSL');
		$data['favicon'] = $this->url->link('setting/item/favicon', 'token=' . $this->session->data['token'], 'SSL');
		$data['address'] = $this->url->link('localisation/location', 'token=' . $this->session->data['token'], 'SSL');
		$data['news'] = $this->url->link('module/latest_information', 'token=' . $this->session->data['token'] . '&module_id=37', 'SSL');
		$data['post'] = $this->url->link('catalog/post', 'token=' . $this->session->data['token'], 'SSL');
		$data['footer_post'] = $this->url->link('catalog/information', 'token=' . $this->session->data['token'], 'SSL');
		$data['footer_social'] = $this->url->link('setting/item/community', 'token=' . $this->session->data['token'], 'SSL');
		$data['footer_address'] = $this->url->link('setting/item/address', 'token=' . $this->session->data['token'], 'SSL');
		$data['footer_map'] = $this->url->link('setting/item/map', 'token=' . $this->session->data['token'], 'SSL');
		$data['slide'] = $this->url->link('module/slideshow', 'token=' . $this->session->data['token'] . '&module_id=27', 'SSL');
		$data['megamenu'] = $this->url->link('design/megamenu', 'token=' . $this->session->data['token'], 'SSL');
		$data['navigation'] = $this->url->link('design/navigation', 'token=' . $this->session->data['token'], 'SSL');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/navigation', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/navigation', 'token=' . $this->session->data['token'], 'SSL')
		);

		// Check install directory exists
		if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
			$data['error_install'] = $this->language->get('error_install');
		} else {
			$data['error_install'] = '';
		}

		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		// Run currency update
		if ($this->config->get('config_currency_auto')) {
			$this->load->model('localisation/currency');

			$this->model_localisation_currency->refresh();
		}

		$this->response->setOutput($this->load->view('common/navigation.tpl', $data));
	}
}