<?php
class ModelDesignPage extends Model {
	public function getPage($page_id){
		$query = $this->db->query("SELECT DISTINCT *, (SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'page_id=" . (int)$page_id . "') AS keyword FROM `" . DB_PREFIX . "page`  WHERE `page_id`='" . (int)$page_id . "' AND `status`='1'");
		
		return $query->row;
	}
	
	public function getPagesByLayout($layout_id, $position = 'content'){
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "page` p LEFT JOIN `" . DB_PREFIX . "page_to_layout` ptl ON (`p`.`page_id` = `ptl`.`page_id`) WHERE `p`.`position`='".$position."' AND  `ptl`.`layout_id`='".(int)$layout_id."'");
		
		return $query->rows;
	}
	
	public function getPageDescriptions($page_id){
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "page_description` WHERE `page_id` = '" . (int)$page_id . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}
}
