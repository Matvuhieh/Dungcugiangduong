<?php
class ModelStaffStaffGroup extends Model {
	public function addStaffGroup($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "staff_group SET name = '" . $this->db->escape($data['name']) . "', permission = '" . (isset($data['permission']) ? $this->db->escape(json_encode($data['permission'])) : '') . "'");
	}

	public function editStaffGroup($staff_group_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "staff_group SET name = '" . $this->db->escape($data['name']) . "', permission = '" . (isset($data['permission']) ? $this->db->escape(json_encode($data['permission'])) : '') . "' WHERE staff_group_id = '" . (int)$staff_group_id . "'");
	}

	public function deleteStaffGroup($staff_group_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "staff_group WHERE staff_group_id = '" . (int)$staff_group_id . "'");
	}

	public function getStaffGroup($staff_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "staff_group WHERE staff_group_id = '" . (int)$staff_group_id . "'");

		$staff_group = array(
			'name'       => $query->row['name'],
			'permission' => json_decode($query->row['permission'], true)
		);

		return $staff_group;
	}

	public function getStaffGroups($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "staff_group";

		$sql .= " ORDER BY name";

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

	public function getTotalStaffGroups() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "staff_group");

		return $query->row['total'];
	}

	public function addPermission($staff_group_id, $type, $route) {
		$staff_group_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "staff_group WHERE staff_group_id = '" . (int)$staff_group_id . "'");

		if ($staff_group_query->num_rows) {
			$data = json_decode($staff_group_query->row['permission'], true);

			$data[$type][] = $route;

			$this->db->query("UPDATE " . DB_PREFIX . "staff_group SET permission = '" . $this->db->escape(json_encode($data)) . "' WHERE staff_group_id = '" . (int)$staff_group_id . "'");
		}
	}

	public function removePermission($staff_group_id, $type, $route) {
		$staff_group_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "staff_group WHERE staff_group_id = '" . (int)$staff_group_id . "'");

		if ($staff_group_query->num_rows) {
			$data = json_decode($staff_group_query->row['permission'], true);

			$data[$type] = array_diff($data[$type], array($route));

			$this->db->query("UPDATE " . DB_PREFIX . "staff_group SET permission = '" . $this->db->escape(json_encode($data)) . "' WHERE staff_group_id = '" . (int)$staff_group_id . "'");
		}
	}
}