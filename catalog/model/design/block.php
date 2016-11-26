<?php
class ModelDesignBlock extends Model {
	
	public function getBlock($block_id = 0){
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "block`  WHERE `block_id`='" . (int)$block_id . "'");
		
		return $query->row;
	}
	
	public function getBlockByCode($code = ''){
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "block`  WHERE `code`='" . $this->db->escape($code) . "'");
		
		return $query->row;
	}
}
