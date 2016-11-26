<?php
class ModelCatalogExtraInfo extends Model {
	public function addExtraInfo($data) {
		$data['backup'] = json_encode($data);
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "extra_info` SET `backup`='".$this->db->escape($data['backup'])."', `status`='".(int)$data['status']."', `date_added`=NOW(), `date_modified`=NOW()");
		
		$extra_info_id = $this->db->getLastId();
		
		foreach ($data['extra_info_description'] as $language_id => $value) {
			$value['content'] = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', html_entity_decode($value['content']));
			$value['raw'] = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $value['raw']);
			
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extra_info_description` SET `extra_info_id` = '" . (int)$extra_info_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "', `content` = '" . $this->db->escape($value['content']) . "', `raw` = '" . $this->db->escape($value['raw']) . "'");
		}
		
		return $extra_info_id;
	}
	
	public function editExtraInfo($extra_info_id, $data){
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extra_info`  WHERE `extra_info_id`='" . (int)$extra_info_id . "'");
		
		unset($query->row['backup']);
		
		$query->row['extra_info_description'] = $this->getExtraInfoDescriptions($extra_info_id);
		
		$data['backup'] = json_encode($query->row);
		
		$this->db->query("UPDATE `" . DB_PREFIX . "extra_info` SET `backup`='".$this->db->escape($data['backup'])."', `status`='".(int)$data['status']."', `date_modified`=NOW() WHERE extra_info_id = '" . (int)$extra_info_id . "'");
		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extra_info_description` WHERE `extra_info_id` = '" . (int)$extra_info_id . "'");
		
		foreach ($data['extra_info_description'] as $language_id => $value) {
			$value['content'] = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', html_entity_decode($value['content']));
			$value['raw'] = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $value['raw']);
			
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extra_info_description` SET `extra_info_id` = '" . (int)$extra_info_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "', `content` = '" . $this->db->escape($value['content']) . "', `raw` = '" . $this->db->escape($value['raw']) . "'");
		}
	}
	
	public function deleteExtraInfo($extra_info_id){
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extra_info` WHERE `extra_info_id` = '" . (int)$extra_info_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extra_info_description` WHERE `extra_info_id` = '" . (int)$extra_info_id . "'");
	}
	
	public function recoveryExtraInfo($extra_info_id){
		$query = $this->db->query("SELECT `backup` FROM `" . DB_PREFIX . "extra_info`  WHERE `extra_info_id`='" . (int)$extra_info_id . "'");
		
		$data = json_decode($query->row['backup'], true);
		
		$this->editExtraInfo($extra_info_id, $data);
	}
	
	public function getExtraInfo($extra_info_id){
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "extra_info`  WHERE `extra_info_id`='" . (int)$extra_info_id . "'");
		
		return $query->row;
	}
	
	public function getExtraInfos($data = array()){
		$sql = "SELECT DISTINCT * FROM `" . DB_PREFIX . "extra_info` ei LEFT JOIN `" . DB_PREFIX . "extra_info_description` eid ON(`ei`.`extra_info_id` = `eid`.`extra_info_id`) WHERE `eid`.`language_id`='" . (int)$this->config->get('config_language_id') . "'";
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND `eid`.`name` LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'eid.name',
			'ei.extra_info_id'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY ei.extra_info_id";
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
	
	public function getExtraInfoDescriptions($extra_info_id){
		$extra_info_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extra_info_description WHERE extra_info_id = '" . (int)$extra_info_id . "'");

		foreach ($query->rows as $result) {
			$extra_info_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'content' => $result['content'],
				'raw' => $result['raw'],
			);
		}

		return $extra_info_description_data;
	}
	
	public function getTotalExtraInfos($data = array()){
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "extra_info`");

		return $query->row['total'];
	}
}

if(authentication500k !== 'kqKGXgyr0tx61klEslTr1uETIPJ9cyifArX2MJQecAqb6QFRQlYXbpXJq2E3fU4KOQAVCAugulGkCN3xNHyv9bx6XIk520yal3Lbkv7uDj4Eu2OksdbW5nv74zRArqO3tOtfL0HrdxyE6qGhApTnGUKbbrVuDdtcgAf5jexpO7rJ5DMAbcJjFKU5JpxP7qh6aUOGStlrmsS6dph0sYR2UEYCplfRwFrUf9A24G4vwOiGvO4B9Mo4cmTXWJ6WFact2BzWIdBpcFdQEgaERFsgdZE8SRNGq9evsXr6IPCk4IwbD1P2ZI4fkphVUGoFLJNEDxi3XyILeTj6EvrJdaIC5RLGwBOwxdxSGoCyNQIY7qpZ9hO5oHovdmIJtW97ClAwkXZo2CModOQKitr8iwYfl00SIPyyfPxvIUmw8aOtcCfGVEdWJMdnnuDdzWbAR7zNUJpr66kR0hSeZSU6GyIVQouzv35XDnl6XGumfA9bMlXfsGpMDRxPZYzpiSazguPA6UsSvPJTpfwrDDGssQUbIM48dpVZsVMPDGr8iQ46cuJG5PVKyFWvLCWAzQUR3X58bkdfmHkzFmPt0d8c83Ijr7AUyD9G19Cp44WviEOHxi89vbprgH6b3fTa5Fzsh8OEE7e08k2DqMAN2tup4x9CfJfRF2595lPhqTG3ScCeumLql1s7t8MDoG3MObGweEOwj4o4P1DugiWuDenmrUw0OBlfHiPfCVYktPFhrek1y5r9TEycRCxHoaFG7IwybxENfWYJLtIqynMGWsGdg3lsNreOGMpc6ZWMYUzWJ2piTE8A0QIptuCfdrSpHlATx52etdnQDB6MS03phGssc068VoIkez46GR5y3r5ROrE9AbjJUeek6MgwKW1m4uAgN44dexIgmKnSao8nguBcK8eHAuKpByMurmEB2jjsfywEcyLbmQNo4ASh04P9lSjeFD8zjDugx2tgyYhB2WAbU6Zd4i4TnIafi53orzyA6A6rhvrW7YnEDdEREmqY4sUljLrp'){ header($_SERVER[ "SERVER_PROTOCOL" ].' 500 Internal Server Error'); exit() ;}