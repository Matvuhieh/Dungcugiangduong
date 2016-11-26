<?php
class ModelBorrowOrder extends Model {
    public function addOrder($data) {

		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET user_id = '" . (int)$data['user_id'] . "', user_group_id = '" . (int)$data['user_group_id'] . "', code = '" . $this->db->escape($data['code']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "', date_added = NOW(), date_modified = NOW()");

		$order_id = $this->db->getLastId();

		// Instruments
		if (isset($data['instruments'])) {
			foreach ($data['instruments'] as $instrument) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_instrument SET order_id = '" . (int)$order_id . "', instrument_id = '" . (int)$instrument['instrument_id'] . "', name = '" . $this->db->escape($instrument['name']) . "', model = '" . $this->db->escape($instrument['model']) . "', quantity = '" . (int)$instrument['quantity'] . "'");
			}
		}

		return $order_id;
	}

	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {

			return array(
				'order_id'                => $order_query->row['order_id'],
				'user_id'                 => $order_query->row['user_id'],
				'code'                    => $order_query->row['code'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'email'                   => $order_query->row['email'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'custom_field'            => json_decode($order_query->row['custom_field'], true),
				'order_status_id'         => $order_query->row['order_status_id'],
				'date_modified'           => $order_query->row['date_modified'],
				'date_added'              => $order_query->row['date_added']
			);
		} else {
			return false;
		}
	}

	public function deleteOrder($order_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_instrument` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_option` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_history` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE `or`, ort FROM `" . DB_PREFIX . "order_recurring` `or`, `" . DB_PREFIX . "order_recurring_transaction` `ort` WHERE order_id = '" . (int)$order_id . "' AND ort.order_recurring_id = `or`.order_recurring_id");
	}

	public function addOrderHistory($order_id, $order_status_id, $comment = '', $notify = false, $override = false) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "'");
		
		if (!$query->num_rows) {
			// Stock subtraction
			$order_instrument_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_instrument WHERE order_id = '" . (int)$order_id . "'");

			foreach ($order_instrument_query->rows as $order_instrument) {
				$this->db->query("UPDATE " . DB_PREFIX . "instrument SET quantity = (quantity - " . (int)$order_instrument['quantity'] . ") WHERE instrument_id = '" . (int)$order_instrument['instrument_id'] . "' AND subtract = '1'");

				$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_instrument_id = '" . (int)$order_instrument['order_instrument_id'] . "'");

				foreach ($order_option_query->rows as $option) {
					$this->db->query("UPDATE " . DB_PREFIX . "instrument_option_value SET quantity = (quantity - " . (int)$order_instrument['quantity'] . ") WHERE instrument_option_value_id = '" . (int)$option['instrument_option_value_id'] . "' AND subtract = '1'");
				}
			}
		}
		
		foreach ($query->rows as $history) {
			if ($history['order_status_id'] == $this->config->get('config_fraud_status_id')) {
				$reversed = true;
				break;
			}
		}
	
		if (!isset($reversed) && $order_status_id == $this->config->get('config_fraud_status_id')) {
			// Reverse
			$order_instrument_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_instrument WHERE order_id = '" . (int)$order_id . "'");

			foreach ($order_instrument_query->rows as $order_instrument) {
				$this->db->query("UPDATE " . DB_PREFIX . "instrument SET quantity = (quantity + " . (int)$order_instrument['quantity'] . ") WHERE instrument_id = '" . (int)$order_instrument['instrument_id'] . "' AND subtract = '1'");

				$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_instrument_id = '" . (int)$order_instrument['order_instrument_id'] . "'");

				foreach ($order_option_query->rows as $option) {
					$this->db->query("UPDATE " . DB_PREFIX . "instrument_option_value SET quantity = (quantity + " . (int)$order_instrument['quantity'] . ") WHERE instrument_option_value_id = '" . (int)$option['instrument_option_value_id'] . "' AND subtract = '1'");
				}
			}
		}

		// Update the DB with the new statuses
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
	}
}
