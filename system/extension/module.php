<?php
// [module id=number name=string /]
class Module extends Controller{
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
    public function index($atts){
		extract(array_merge(array(
		  'id' => 0,
		  'name' => '',
		),$atts));
		
        if ($name) {
			$this->load->model('extension/module');
			
			if(!(int)$id && $this->config->get($name . '_status')){
				return $this->load->controller('module/' . $name);
			}else{
				$setting_info = $this->model_extension_module->getModule($id);
				if ($setting_info && $setting_info['status']) {
					return $this->load->controller('module/' . $name, $setting_info);
				}
			}
        }
		
		return '';
    }
	
	public function getModuleNames(){
		$option = '';
		
		if(defined('DIR_CATALOG')){
				$modules = array_diff(scandir(DIR_CATALOG.'controller/module/'),array('.','..'));
		}else{
			$modules = array_diff(scandir(DIR_APPLICATION.'controller/module/'),array('.','..'));
		}
		
		foreach($modules as $key => $value){
			$option .= '<option value="'.str_replace('.php','',$value).'">'.str_replace('.php','',$value).'</option>';	
		}
		
		return $option;
	}
	
	public function info(){
		return array(
			'code' => 'module',
			'return_html' => true,
			'editor' => true,
			'title' => 'Load Modules',
			'description' => '[module id="number" name="string" /]',
			'icon' => '<i class="fa fa-puzzle-piece" aria-hidden="true"></i>',
			'param' => array(
				'id' => array('type' => 'number', 'title' => 'ID Module', 'description' => 'Only number'),
				'name' => array('type' => 'select', 'title' => 'Name', 'description' => 'PHP file name', 'content' => $this->getModuleNames())
			)
		);
	}
}