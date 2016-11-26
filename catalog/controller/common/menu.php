<?php
class ControllerCommonMenu extends Controller {
	public function index() {
		$this->load->language('common/menu');
		$route = isset($this->request->get['route']) ? $this->request->get['route'] : null;
		$this->load->model('catalog/menu');
		$root_menus = $this->model_catalog_menu->getlist();
		$options = $this->model_catalog_menu->getOptions();
		foreach($options as $val){
			$data[$val['name']] = $val['value'];
		}
		if($root_menus){
			foreach($root_menus as $key=>$value){
				if($value['type']=='category')
				{
					$root_menus[$key]['url'] = $this->url->link('instrument/category', 'path=' . $value['type_id']);
					if($route=='instrument/category' and preg_match('/'.$value['type_id'].'$/i', $this->request->get['path']))
						$root_menus[$key]['active'] = 1;
				}
				if($value['type']=='infomation')
				{
					$root_menus[$key]['url'] = $this->url->link('information/information', 'information_id=' . $value['type_id']);
					if($route=='information/information' and $this->request->get['information_id']==$value['type_id'])
					$root_menus[$key]['active'] = 1;
				}
				if($value['type']=='manufacturer')
				{
					$root_menus[$key]['url'] = $this->url->link('instrument/manufacturer/info', 'manufacturer_id=' . $value['type_id']);
					if($route=='instrument/manufacturer/info' and $this->request->get['manufacturer_id']==$value['type_id'])
					$root_menus[$key]['active'] = 1;
				}
				if( (preg_match('/index.php$/i', $value['url']) || preg_match('/common\/home$/i', $value['url']) || preg_match('/\/$/i', $value['url'])) and (!$route or $route=='common/home') ){
					$root_menus[$key]['active'] = 1;
				}
				
			}
		}
		
		$data['menus_root'] = $root_menus;
		$data['responsive_title'] = $this->language->get('responsive_title');
		$data['MenuModel'] = $this->model_catalog_menu;
		$data['configObject'] = $this->config;
		$this->document->addStyle('view/stylesheet/menu/menu.css');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/menu.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/menu.tpl';
			return $this->load->view($this->config->get('config_template') . '/template/common/menu.tpl', $data);
		} else {
			return $this->load->view('default/template/common/menu.tpl', $data);
		}
	}
}
?>