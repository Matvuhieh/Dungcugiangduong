<?php
class ControllerCommonPage extends Controller {
	public function index() {
		$this->load->model('design/page');
		
		if(isset($this->request->get['page_id'])){
			$page_info = $this->model_design_page->getPage($this->request->get['page_id']);
		}else{
			$page_info = array();
		}
		
		if(!$page_info){
			$this->response->redirect($this->url->link('common/home', '', 'SSL'));
		}
		
		if($page_info['background_image']){
			if ($this->request->server['HTTPS']) {
				$data['background_image'] = $this->config->get('config_ssl') . 'image/' . $page_info['background_image'];
			} else {
				$data['background_image'] =  $this->config->get('config_url') . 'image/' . $page_info['background_image'];
			}
		}else{
			$data['background_image'] = '';
		}
		
		$data['background_color'] = $page_info['background_color'];
		$data['content'] = $page_info['content'];
		
		$page_description = $this->model_design_page->getPageDescriptions($this->request->get['page_id']);
		
		$this->document->setTitle($page_description['meta_title']);
		$this->document->setDescription($page_description['meta_keyword']);
		$this->document->setKeywords($page_description['meta_description']);

		if (isset($this->request->get['route'])) {
			$this->document->addLink(HTTP_SERVER, 'canonical');
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/page.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/page.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/page.tpl', $data));
		}
	}
}