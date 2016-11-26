<?php
class ModelSettingApi extends Model {
	public function login($staffname, $password) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "api WHERE staffname = '" . $this->db->escape($staffname) . "' AND password = '" . $this->db->escape($password) . "'");

		return $query->row;
	}
}