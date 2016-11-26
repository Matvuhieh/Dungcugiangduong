<?php
class ModelStaffStaff extends Model {
	public function addStaff($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "staff` SET staffname = '" . $this->db->escape($data['staffname']) . "', staff_group_id = '" . (int)$data['staff_group_id'] . "', salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', image = '" . $this->db->escape($data['image']) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	}

	public function editStaff($staff_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "staff` SET staffname = '" . $this->db->escape($data['staffname']) . "', staff_group_id = '" . (int)$data['staff_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', image = '" . $this->db->escape($data['image']) . "', status = '" . (int)$data['status'] . "' WHERE staff_id = '" . (int)$staff_id . "'");

		if ($data['password']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "staff` SET salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE staff_id = '" . (int)$staff_id . "'");
		}
	}

	public function editPassword($staff_id, $password) {
		$this->db->query("UPDATE `" . DB_PREFIX . "staff` SET salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE staff_id = '" . (int)$staff_id . "'");
	}

	public function editCode($email, $code) {
		$this->db->query("UPDATE `" . DB_PREFIX . "staff` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

	public function deleteStaff($staff_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "staff` WHERE staff_id = '" . (int)$staff_id . "'");
	}

	public function getStaff($staff_id) {
		$query = $this->db->query("SELECT *, (SELECT ug.name FROM `" . DB_PREFIX . "staff_group` ug WHERE ug.staff_group_id = u.staff_group_id) AS staff_group FROM `" . DB_PREFIX . "staff` u WHERE u.staff_id = '" . (int)$staff_id . "'");

		return $query->row;
	}

	public function getStaffByStaffname($staffname) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "staff` WHERE staffname = '" . $this->db->escape($staffname) . "'");

		return $query->row;
	}

	public function getStaffByCode($code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "staff` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");

		return $query->row;
	}

	public function getStaffs($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "staff`";
		
		if(!$this->staff->isRoot()) $sql .= " WHERE root='0'";

		$sort_data = array(
			'staffname',
			'status',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY staffname";
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

	public function getTotalStaffs() {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "staff`";
		
		if(!$this->staff->isRoot()) $sql .= " WHERE root='0'";
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalStaffsByGroupId($staff_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "staff` WHERE staff_group_id = '" . (int)$staff_group_id . "'");

		return $query->row['total'];
	}

	public function getTotalStaffsByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "staff` WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}
}