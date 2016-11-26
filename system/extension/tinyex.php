<?php
// [tinyex name=string /]
class Tinyex extends Controller{
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
    public function index($atts){
		extract(array_merge(array(
		  'name' => '',
		),$atts));
		
        if ($name) {
			return $this->load->controller('tinyex/'.$name,$atts);
        }
		
		return '';
    }
	
	public function getTinyExtension(){
		$option = '';
		if(defined('DIR_CATALOG')){
			$tinyex = array_diff(scandir(DIR_CATALOG.'controller/tinyex/'),array('.','..'));
		}else{
			$tinyex = array_diff(scandir(DIR_APPLICATION.'controller/tinyex/'),array('.','..'));
		}
		
		foreach($tinyex as $key => $value){
			$option .= '<option value="'.str_replace('.php','',$value).'">'.str_replace('.php','',$value).'</option>';	
		}
		
		return $option;
	}
	
	public function info(){
		return array(
			'code' => 'tinyex',
			'return_html' => true,
			'editor' => true,
			'title' => 'Load tiny extenstion',
			'description' => '[tinyex name="string" /]',
			'icon' => '<i class="fa fa-cubes" aria-hidden="true"></i>',
			'param' => array(
				'name' => array('type' => 'select', 'title' => 'Name', 'description' => 'PHP name file of tiny extension in tinyex folder.', 'content' => $this->getTinyExtension())
			)
		);
	}
}