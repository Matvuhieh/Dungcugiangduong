<?php
class User {
	private $user_id;
	private $firstname;
	private $lastname;
	private $user_group_id;
	private $email;
	private $telephone;
	private $fax;
	private $newsletter;
	private $address_id;

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['user_id'])) {
			$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$this->session->data['user_id'] . "' AND status = '1'");

			if ($user_query->num_rows) {
				$this->user_id = $user_query->row['user_id'];
				$this->firstname = $user_query->row['firstname'];
				$this->lastname = $user_query->row['lastname'];
				$this->user_group_id = $user_query->row['user_group_id'];
				$this->email = $user_query->row['email'];
				$this->telephone = $user_query->row['telephone'];
				$this->fax = $user_query->row['fax'];
				$this->newsletter = $user_query->row['newsletter'];
				$this->address_id = $user_query->row['address_id'];

				$this->db->query("UPDATE " . DB_PREFIX . "user SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . (int)$this->user_id . "'");

				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user_ip WHERE user_id = '" . (int)$this->session->data['user_id'] . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");

				if (!$query->num_rows) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "user_ip SET user_id = '" . (int)$this->session->data['user_id'] . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
				}
			} else {
				$this->logout();
			}
		}
	}

	public function login($email, $password, $override = false) {
		if ($override) {
			$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND status = '1'");
		} else {
			$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1' AND approved = '1'");
		}

		if ($user_query->num_rows) {
			$this->session->data['user_id'] = $user_query->row['user_id'];

			$this->user_id = $user_query->row['user_id'];
			$this->firstname = $user_query->row['firstname'];
			$this->lastname = $user_query->row['lastname'];
			$this->user_group_id = $user_query->row['user_group_id'];
			$this->email = $user_query->row['email'];
			$this->telephone = $user_query->row['telephone'];
			$this->fax = $user_query->row['fax'];
			$this->newsletter = $user_query->row['newsletter'];
			$this->address_id = $user_query->row['address_id'];

			$this->db->query("UPDATE " . DB_PREFIX . "user SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . (int)$this->user_id . "'");

			return true;
		} else {
			return false;
		}
	}

	public function logout() {
		unset($this->session->data['user_id']);

		$this->user_id = '';
		$this->firstname = '';
		$this->lastname = '';
		$this->user_group_id = '';
		$this->email = '';
		$this->telephone = '';
		$this->fax = '';
		$this->newsletter = '';
		$this->address_id = '';
	}

	public function isLogged() {
		return $this->user_id;
	}

	public function getId() {
		return $this->user_id;
	}

	public function getFirstName() {
		return $this->firstname;
	}

	public function getLastName() {
		return $this->lastname;
	}

	public function getGroupId() {
		return $this->user_group_id;
	}

	public function getUserCode() {
		return $this->email;
	}

	public function getTelephone() {
		return $this->telephone;
	}

	public function getFax() {
		return $this->fax;
	}

	public function getNewsletter() {
		return $this->newsletter;
	}

	public function getAddressId() {
		return $this->address_id;
	}

	public function getBalance() {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "user_transaction WHERE user_id = '" . (int)$this->user_id . "'");

		return $query->row['total'];
	}

	public function getRewardPoints() {
		$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "user_reward WHERE user_id = '" . (int)$this->user_id . "'");

		return $query->row['total'];
	}
}
