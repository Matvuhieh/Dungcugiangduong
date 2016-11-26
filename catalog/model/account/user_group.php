<?php
class ModelAccountUserGroup extends Model {
	public function getUserGroup($user_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group cg LEFT JOIN " . DB_PREFIX . "user_group_description cgd ON (cg.user_group_id = cgd.user_group_id) WHERE cg.user_group_id = '" . (int)$user_group_id . "' AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getUserGroups() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user_group cg LEFT JOIN " . DB_PREFIX . "user_group_description cgd ON (cg.user_group_id = cgd.user_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY cg.sort_order ASC, cgd.name ASC");

		return $query->rows;
	}
}