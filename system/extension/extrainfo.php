<?php
// [extrainfo id=number /]
class ExtraInfo extends Controller{
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
    public function index($atts){
		extract($atts);
		
		if($id) { 
			$this->load->model('catalog/extra_info');
			$extra_info = $this->model_catalog_extra_info->getExtraInfoDescriptions($id);
			
			return $extra_info['content'];
		}
    }
	
	public function info(){
		return array(
			'code' => 'extrainfo',
			'return_html' => true,
			'editor' => false,
			'title' => 'Extra Informations',
			'description' => '[extrainfo id=number /]',
			'icon' => '<i class="fa fa-newspaper-o" aria-hidden="true"></i>',
			'param' => array(
				'id' => array('type' => 'number', 'title' => 'Extra Information ID', 'description' => 'Number Only'),
			)
		);
	}
}