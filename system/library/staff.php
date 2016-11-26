<?php
class Staff {
	private $staff_id;
	private $staffname;
	private $permission = array();

	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['staff_id'])) {
			$staff_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "staff WHERE staff_id = '" . (int)$this->session->data['staff_id'] . "' AND status = '1'");

			if ($staff_query->num_rows) {
				$this->staff_id = $staff_query->row['staff_id'];
				$this->staffname = $staff_query->row['staffname'];
				$this->staff_group_id = $staff_query->row['staff_group_id'];
				$this->last_activity = strtotime($staff_query->row['last_activity']);
				$this->root = $staff_query->row['root'];

				$this->db->query("UPDATE " . DB_PREFIX . "staff SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE staff_id = '" . (int)$this->session->data['staff_id'] . "'");

				$staff_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "staff_group WHERE staff_group_id = '" . (int)$staff_query->row['staff_group_id'] . "'");

				$permissions = json_decode($staff_group_query->row['permission'], true);

				if (is_array($permissions)) {
					foreach ($permissions as $key => $value) {
						$this->permission[$key] = $value;
					}
				}
			} else {
				$this->logout();
			}
		}
	}

	public function login($staffname, $password) {
		$staff_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "staff WHERE staffname = '" . $this->db->escape($staffname) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1'");

		if ($staff_query->num_rows) {
			$this->session->data['staff_id'] = $staff_query->row['staff_id'];

			$this->staff_id = $staff_query->row['staff_id'];
			$this->staffname = $staff_query->row['staffname'];
			$this->staff_group_id = $staff_query->row['staff_group_id'];
			$this->last_activity = strtotime($staff_query->row['last_activity']);
			$this->root = $staff_query->row['root'];

			$staff_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "staff_group WHERE staff_group_id = '" . (int)$staff_query->row['staff_group_id'] . "'");

			$permissions = json_decode($staff_group_query->row['permission'], true);

			if (is_array($permissions)) {
				foreach ($permissions as $key => $value) {
					$this->permission[$key] = $value;
				}
			}

			return true;
		} else {
			return false;
		}
	}

	public function logout() {
		unset($this->session->data['staff_id']);

		$this->staff_id = '';
		$this->staffname = '';
	}

	public function hasPermission($key, $value) {
		if (isset($this->permission[$key])) {
			return in_array($value, $this->permission[$key]);
		} else {
			return false;
		}
	}

	public function isLogged() {
		return $this->staff_id;
	}
	
	public function isRoot() {
		return $this->root;
	}

	public function getId() {
		return $this->staff_id;
	}

	public function getStaffName() {
		return $this->staffname;
	}

	public function getGroupId() {
		return $this->staff_group_id;
	}
	
	public function setLastActivity() {
		$this->db->query("UPDATE " . DB_PREFIX . "staff SET last_activity=UTC_TIMESTAMP() WHERE staff_id = '" . (int)$this->staff_id . "'");
	}
	
	public function getLastActivity() {
		return $this->last_activity;
	}
}