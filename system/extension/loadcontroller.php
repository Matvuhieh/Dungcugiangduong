<?php
// [loadcontroller route=Opencart route /]
class LoadController extends Controller{
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
    public function index($atts){
		extract(array_merge(array(
		  'route' => '',
		),$atts));
		
        if ($route) {
			return $this->load->controller($route,$atts);
        }
		
		return '';
    }
	
	public function info(){
		return array(
			'code' => 'loadcontroller',
			'return_html' => true,
			'editor' => true,
			'title' => 'Load controller',
			'description' => '[loadcontroller route="Opencart route" /]',
			'icon' => '<i class="fa fa-newspaper-o" aria-hidden="true"></i>',
			'param' => array(
				'route' => array('type' => 'text', 'title' => 'Route', 'description' => 'Opencart route')
			)
		);
	}
}