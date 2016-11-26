<?php
class ModelAccountReward extends Model {
	public function getRewards($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "user_reward` WHERE user_id = '" . (int)$this->user->getId() . "'";

		$sort_data = array(
			'points',
			'description',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY date_added";
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

	public function getTotalRewards() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user_reward` WHERE user_id = '" . (int)$this->user->getId() . "'");

		return $query->row['total'];
	}

	public function getTotalPoints() {
		$query = $this->db->query("SELECT SUM(points) AS total FROM `" . DB_PREFIX . "user_reward` WHERE user_id = '" . (int)$this->user->getId() . "' GROUP BY user_id");

		if ($query->num_rows) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}
}