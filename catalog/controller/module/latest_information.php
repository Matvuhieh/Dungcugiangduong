<?php
class ControllerModuleLatestInformation extends Controller {
	public function index($setting) {
		$this->load->language('module/latest_information');

		$data['heading_title'] = $this->language->get('heading_title');

		$this->load->model('catalog/information');
		
		$results = $this->model_catalog_information->getLastestInformations();
		
		if ($results) {
			
			$data['informations'] = array();
			
			foreach ($results as $result) {
				$data['informations'][] = array(
					'information_id' => $result['information_id'],
					'title'          => $result['title'],
					'description'    => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_instrument_description_length')) . '..',
					'href'           => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/latest_information.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/latest_information.tpl', $data);
			} else {
				return $this->load->view('default/template/module/latest_information.tpl', $data);
			}
		}
	}
}
