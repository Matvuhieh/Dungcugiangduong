<?php
class ModelDesignNavigation extends Model {
	
	public function getNavigations($parent_id = 0) {
      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "navigation  WHERE " . DB_PREFIX . "navigation.parent_id = '".$parent_id."' ORDER BY " . DB_PREFIX . "navigation.id ASC");

	  foreach ($query->rows as $key => $value){
		  $query->rows[$key]['title'] = json_decode($query->rows[$key]['title'], true);
	  }
	  
	  return $query->rows;
	}
}
?>