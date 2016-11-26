<?php
class ModelUserUserGroup extends Model {
	public function addUserGroup($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "user_group SET approval = '" . (int)$data['approval'] . "', sort_order = '" . (int)$data['sort_order'] . "'");

		$user_group_id = $this->db->getLastId();

		foreach ($data['user_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "user_group_description SET user_group_id = '" . (int)$user_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
	}

	public function editUserGroup($user_group_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "user_group SET approval = '" . (int)$data['approval'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE user_group_id = '" . (int)$user_group_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "user_group_description WHERE user_group_id = '" . (int)$user_group_id . "'");

		foreach ($data['user_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "user_group_description SET user_group_id = '" . (int)$user_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
	}

	public function deleteUserGroup($user_group_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "user_group_description WHERE user_group_id = '" . (int)$user_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_discount WHERE user_group_id = '" . (int)$user_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_special WHERE user_group_id = '" . (int)$user_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_reward WHERE user_group_id = '" . (int)$user_group_id . "'");
	}

	public function getUserGroup($user_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group cg LEFT JOIN " . DB_PREFIX . "user_group_description cgd ON (cg.user_group_id = cgd.user_group_id) WHERE cg.user_group_id = '" . (int)$user_group_id . "' AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getUserGroups($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "user_group cg LEFT JOIN " . DB_PREFIX . "user_group_description cgd ON (cg.user_group_id = cgd.user_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = array(
			'cgd.name',
			'cg.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY cgd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getUserGroupDescriptions($user_group_id) {
		$user_group_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user_group_description WHERE user_group_id = '" . (int)$user_group_id . "'");

		foreach ($query->rows as $result) {
			$user_group_data[$result['language_id']] = array(
				'name'        => $result['name'],
				'description' => $result['description']
			);
		}

		return $user_group_data;
	}

	public function getTotalUserGroups() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user_group");

		return $query->row['total'];
	}
}