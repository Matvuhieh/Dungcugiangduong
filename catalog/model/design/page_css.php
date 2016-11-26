<?php
class ModeldesignPageCSS extends Model {
	public function getCSS(){
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "page_css`");
		
		return $query->row['css'];
	}
}
