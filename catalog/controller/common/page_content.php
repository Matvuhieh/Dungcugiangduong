<?php
class ControllerCommonPageContent extends Controller {
	public function index() {
		$this->load->model('design/layout');

		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = 'common/home';
		}
 
		$layout_id = 0;

		if ($route == 'instrument/category' && isset($this->request->get['path'])) {
			$this->load->model('catalog/category');

			$path = explode('_', (string)$this->request->get['path']);

			$layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));
		}

		if ($route == 'instrument/instrument' && isset($this->request->get['instrument_id'])) {
			$this->load->model('catalog/instrument');

			$layout_id = $this->model_catalog_instrument->getInstrumentLayoutId($this->request->get['instrument_id']);
		}

		if ($route == 'information/information' && isset($this->request->get['information_id'])) {
			$this->load->model('catalog/information');

			$layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->get['information_id']);
		}

		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}

		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}
		
		$this->load->model('design/page');
		
		$data['pages'] = $this->model_design_page->getPagesByLayout($layout_id);
		
		foreach($data['pages'] as $key => $value){
			$data['pages'][$key]['content'] = $this->shortcodes->doShortCode($data['pages'][$key]['content']);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/page_content.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/page_content.tpl', $data);
		} else {
			return $this->load->view('default/template/common/page_content.tpl', $data);
		}
	}
}