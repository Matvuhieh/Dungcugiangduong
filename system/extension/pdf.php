<?php
// [pdf src=URL width=number height=number /]
class Pdf extends Controller{
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
    public function index($atts){
		extract(array_merge(array(
		  'src' => '',
		  'width' => 600,
		  'height' => 800
		),$atts));
		
		if(!empty($src)){
			return "<object data=\"$src\" type=\"application/pdf\" style=\"width: 100%; max-width: ".$width."px; height: ".$height."px;\" ><param name=\"allowFullScreen\" value=\"true\"/>Sorry! Your browser does not support Object HTML tag.</object>";
		}
		
		return "";
    }
	
	public function info(){
		return array(
			'code' => 'pdf',
			'return_html' => true,
			'editor' => true,
			'title' => 'PDF Embedder',
			'description' => '[pdf src="URL" width="number" height="number" /]',
			'icon' => '<i class="fa fa-file-pdf-o" aria-hidden="true"></i>',
			'param' => array(
				'src' => array('type' => 'text', 'title' => 'PDF links'),
				'width' => array('type' => 'number', 'title' => 'Width', 'description' => 'Only number'),
				'height' => array('type' => 'number', 'title' => 'Height', 'description' => 'Only number')
			)
		);
	}
}