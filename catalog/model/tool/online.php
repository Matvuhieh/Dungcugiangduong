<?php
class ModelToolOnline extends Model {
	public function addOnline($ip, $user_id, $url, $referer) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "user_online` WHERE date_added < '" . date('Y-m-d H:i:s', strtotime('-1 hour')) . "'");

		$this->db->query("REPLACE INTO `" . DB_PREFIX . "user_online` SET `ip` = '" . $this->db->escape($ip) . "', `user_id` = '" . (int)$user_id . "', `url` = '" . $this->db->escape($url) . "', `referer` = '" . $this->db->escape($referer) . "', `date_added` = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
	}
}
