<?php
// [iframe src=URL width=number height=number /]
class Iframe extends Controller{
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
    public function index($atts){
		header('X-Frame-Options: GOFORIT'); 
		
		extract(array_merge(array(
		  'src' => '',
		  'width' => 800,
		  'height' => 600
		),$atts));
		
		if(!empty($src)){
			return "<iframe src=\"$src\" style=\"width: 100%; max-width: ".$width."px; height: ".$height."px;\" frameborder=\"0\" allowfullscreen=\"true\" target=\"_parent\">Sorry! Your browser does not support Iframe HTML tag.</iframe>";
		}
		
		return "";
    }
	
	public function info(){
		return array(
			'code' => 'iframe',
			'return_html' => true,
			'editor' => true,
			'title' => 'Iframe',
			'description' => '[iframe src="URL" width="number" height="number" /]',
			'icon' => '<i class="fa fa-file-code-o" aria-hidden="true"></i>',
			'param' => array(
				'src' => array('type' => 'text', 'title' => 'URL'),
				'width' => array('type' => 'number', 'title' => 'Width', 'description' => 'Only number'),
				'height' => array('type' => 'number', 'title' => 'Height', 'description' => 'Only number')
			)
		);
	}
}