<?php
// [pageinsert id=number /]
class PageInsert extends Controller{
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
    public function index($atts){
		if(isset($atts['id'])){
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "page_insert_description` WHERE `page_insert_id`='".(int)$atts['id']."' AND `language_id`='".$this->config->get('config_language_id')."'");
			
			if($query->row) return $query->row['content'];
		}
		
		return '';
    }
	
	public function info(){
		return array(
			'code' => 'pageinsert',
			'return_html' => false,
			'editor' => false,
			'title' => 'Insert Number',
			'description' => '[pageinsert id=number /]',
			'icon' => '<i class="fa fa-newspaper-o" aria-hidden="true"></i>',
			'param' => array(
				'id' => array('type' => 'number', 'title' => 'Page Insert ID', 'description' => 'Number Only')
			)
		);
	}
}