<?php
class ModelDesignPageConfig extends Model {
	public function getPageConfig($code = '', $key = ''){
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "page_config` WHERE `code`='" . $this->db->escape($code) . "' AND `key`='" . $this->db->escape($key) . "'");
		
		if($query->num_rows){
			if((int)$query->row['serialized']){
				return json_decode($query->row['value'], true);
			}else{
				return $query->row['value'];
			}
		}else{
			return NULL;
		}
		
	}
}
