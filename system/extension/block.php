<?php
// [block code=text /]
class Block extends Controller{
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
    public function index($atts){
		extract($atts);
		
		if(!empty($code)){
			$this->load->model('design/block');
			
			$block = $this->model_design_block->getBlockByCode($code);
			
			if ($block) {
				if(!empty($block['css'])){
					$css = '<style type="text/css">' . $block['css'] . '</style>';
				}else{
					$css = '';
				}
				
				echo $css . $block['html'];
			}
		}
    }
	
	public function info(){
		return array(
			'code' => 'extrainformation',
			'return_html' => true,
			'editor' => false,
			'title' => 'Blocks',
			'description' => '[block code=text /]',
			'icon' => '<i class="fa fa-cube" aria-hidden="true"></i>',
			'param' => array(
				'code' => array('type' => 'text', 'title' => 'Block Code', 'description' => ''),
			)
		);
	}
}