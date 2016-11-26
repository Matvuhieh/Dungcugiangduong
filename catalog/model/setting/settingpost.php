<?php
class ModelSettingSettingpost extends Model {
	public function getSettingPosts($store_id = 0) {
		$all_setting_post_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "settingpost WHERE store_id = '" . (int)$store_id . "'");

		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$all_setting_post_data[$result['key']] = $result['value'];
			} else {
				$all_setting_post_data[$result['key']] = json_decode($result['value'], true);
			}
		}

		return $all_setting_post_data;
	}
	public function getSettingPost($code, $store_id = 0) {
		$settingpost_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "settingpost WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");

		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$settingpost_data[$result['key']] = $result['value'];
			} else {
				$settingpost_data[$result['key']] = json_decode($result['value'], true);
			}
		}

		return $settingpost_data;
	}

	public function editSettingPost($data) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "settingpost`");

		foreach ($data as $key => $value) {
				if (!is_array($value)) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "settingpost SET `code` = '" . $this->db->escape($key) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "settingpost SET `code` = '" . $this->db->escape($key) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(json_encode($value)) . "', serialized = '1'");
				}
			}
	
	}

	public function deleteSettingPost($code, $store_id = 0) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "settingpost WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");
	}

	public function editSettingPostValue($code = '', $key = '', $value = '', $store_id = 0) {
		if (!is_array($value)) {
			$this->db->query("UPDATE " . DB_PREFIX . "settingpost SET `value` = '" . $this->db->escape($value) . "', serialized = '0'  WHERE `code` = '" . $this->db->escape($code) . "' AND `key` = '" . $this->db->escape($key) . "' AND store_id = '" . (int)$store_id . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "settingpost SET `value` = '" . $this->db->escape(json_encode($value)) . "', serialized = '1' WHERE `code` = '" . $this->db->escape($code) . "' AND `key` = '" . $this->db->escape($key) . "' AND store_id = '" . (int)$store_id . "'");
		}
	}
}
