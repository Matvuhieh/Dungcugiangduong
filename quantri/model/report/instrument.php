<?php
class ModelReportInstrument extends Model {
	public function getInstrumentsViewed($data = array()) {
		$sql = "SELECT pd.name, p.model, p.viewed FROM " . DB_PREFIX . "instrument p LEFT JOIN " . DB_PREFIX . "instrument_description pd ON (p.instrument_id = pd.instrument_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.viewed > 0 ORDER BY p.viewed DESC";

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

	public function getTotalInstrumentViews() {
		$query = $this->db->query("SELECT SUM(viewed) AS total FROM " . DB_PREFIX . "instrument");

		return $query->row['total'];
	}

	public function getTotalInstrumentsViewed() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "instrument WHERE viewed > 0");

		return $query->row['total'];
	}

	public function reset() {
		$this->db->query("UPDATE " . DB_PREFIX . "instrument SET viewed = '0'");
	}

	public function getPurchased($data = array()) {
		$sql = "SELECT op.name, op.model, SUM(op.quantity) AS quantity, SUM((op.total + op.tax) * op.quantity) AS total FROM " . DB_PREFIX . "order_instrument op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id)";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$sql .= " GROUP BY op.instrument_id ORDER BY total DESC";

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

	public function getTotalPurchased($data) {
		$sql = "SELECT COUNT(DISTINCT op.instrument_id) AS total FROM `" . DB_PREFIX . "order_instrument` op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id)";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}