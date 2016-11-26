<?php
class Cart {
	private $data = array();

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->user = $registry->get('user');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');
		$this->tax = $registry->get('tax');
		$this->weight = $registry->get('weight');

		// Remove all the expired carts with no user ID
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE user_id = '0' AND date_added < DATE_SUB(NOW(), INTERVAL 1 HOUR)");

		if ($this->user->getId()) {
			// We want to change the session ID on all the old items in the users cart
			$this->db->query("UPDATE " . DB_PREFIX . "cart SET session_id = '" . $this->db->escape($this->session->getId()) . "' WHERE user_id = '" . (int)$this->user->getId() . "'");

			// Once the user is logged in we want to update the user ID on all items he has
			$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE user_id = '0' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

			foreach ($cart_query->rows as $cart) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$cart['cart_id'] . "'");

				// The advantage of using $this->add is that it will check if the instruments already exist and increaser the quantity if necessary.
				$this->add($cart['instrument_id'], $cart['quantity'], json_decode($cart['option']), $cart['recurring_id']);
			}
		}
	}

	public function getInstruments() {
		$instrument_data = array();

		$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE user_id = '" . (int)$this->user->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

		foreach ($cart_query->rows as $cart) {
			$stock = true;

			$instrument_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "instrument_to_store p2s LEFT JOIN " . DB_PREFIX . "instrument p ON (p2s.instrument_id = p.instrument_id) LEFT JOIN " . DB_PREFIX . "instrument_description pd ON (p.instrument_id = pd.instrument_id) WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p2s.instrument_id = '" . (int)$cart['instrument_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p.status = '1'");

			if ($instrument_query->num_rows && ($cart['quantity'] > 0)) {
				$option_price = 0;
				$option_points = 0;
				$option_weight = 0;

				$option_data = array();

				foreach (json_decode($cart['option']) as $instrument_option_id => $value) {
					$option_query = $this->db->query("SELECT po.instrument_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "instrument_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.instrument_option_id = '" . (int)$instrument_option_id . "' AND po.instrument_id = '" . (int)$cart['instrument_id'] . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

					if ($option_query->num_rows) {
						if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
							$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "instrument_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.instrument_option_value_id = '" . (int)$value . "' AND pov.instrument_option_id = '" . (int)$instrument_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

							if ($option_value_query->num_rows) {
								if ($option_value_query->row['price_prefix'] == '+') {
									$option_price += $option_value_query->row['price'];
								} elseif ($option_value_query->row['price_prefix'] == '-') {
									$option_price -= $option_value_query->row['price'];
								}

								if ($option_value_query->row['points_prefix'] == '+') {
									$option_points += $option_value_query->row['points'];
								} elseif ($option_value_query->row['points_prefix'] == '-') {
									$option_points -= $option_value_query->row['points'];
								}

								if ($option_value_query->row['weight_prefix'] == '+') {
									$option_weight += $option_value_query->row['weight'];
								} elseif ($option_value_query->row['weight_prefix'] == '-') {
									$option_weight -= $option_value_query->row['weight'];
								}

								if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
									$stock = false;
								}

								$option_data[] = array(
									'instrument_option_id'       => $instrument_option_id,
									'instrument_option_value_id' => $value,
									'option_id'               => $option_query->row['option_id'],
									'option_value_id'         => $option_value_query->row['option_value_id'],
									'name'                    => $option_query->row['name'],
									'value'                   => $option_value_query->row['name'],
									'type'                    => $option_query->row['type'],
									'quantity'                => $option_value_query->row['quantity'],
									'subtract'                => $option_value_query->row['subtract'],
									'price'                   => $option_value_query->row['price'],
									'price_prefix'            => $option_value_query->row['price_prefix'],
									'points'                  => $option_value_query->row['points'],
									'points_prefix'           => $option_value_query->row['points_prefix'],
									'weight'                  => $option_value_query->row['weight'],
									'weight_prefix'           => $option_value_query->row['weight_prefix']
								);
							}
						} elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {
							foreach ($value as $instrument_option_value_id) {
								$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "instrument_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.instrument_option_value_id = '" . (int)$instrument_option_value_id . "' AND pov.instrument_option_id = '" . (int)$instrument_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

								if ($option_value_query->num_rows) {
									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += $option_value_query->row['price'];
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= $option_value_query->row['price'];
									}

									if ($option_value_query->row['points_prefix'] == '+') {
										$option_points += $option_value_query->row['points'];
									} elseif ($option_value_query->row['points_prefix'] == '-') {
										$option_points -= $option_value_query->row['points'];
									}

									if ($option_value_query->row['weight_prefix'] == '+') {
										$option_weight += $option_value_query->row['weight'];
									} elseif ($option_value_query->row['weight_prefix'] == '-') {
										$option_weight -= $option_value_query->row['weight'];
									}

									if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
										$stock = false;
									}

									$option_data[] = array(
										'instrument_option_id'       => $instrument_option_id,
										'instrument_option_value_id' => $instrument_option_value_id,
										'option_id'               => $option_query->row['option_id'],
										'option_value_id'         => $option_value_query->row['option_value_id'],
										'name'                    => $option_query->row['name'],
										'value'                   => $option_value_query->row['name'],
										'type'                    => $option_query->row['type'],
										'quantity'                => $option_value_query->row['quantity'],
										'subtract'                => $option_value_query->row['subtract'],
										'price'                   => $option_value_query->row['price'],
										'price_prefix'            => $option_value_query->row['price_prefix'],
										'points'                  => $option_value_query->row['points'],
										'points_prefix'           => $option_value_query->row['points_prefix'],
										'weight'                  => $option_value_query->row['weight'],
										'weight_prefix'           => $option_value_query->row['weight_prefix']
									);
								}
							}
						} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
							$option_data[] = array(
								'instrument_option_id'       => $instrument_option_id,
								'instrument_option_value_id' => '',
								'option_id'               => $option_query->row['option_id'],
								'option_value_id'         => '',
								'name'                    => $option_query->row['name'],
								'value'                   => $value,
								'type'                    => $option_query->row['type'],
								'quantity'                => '',
								'subtract'                => '',
								'price'                   => '',
								'price_prefix'            => '',
								'points'                  => '',
								'points_prefix'           => '',
								'weight'                  => '',
								'weight_prefix'           => ''
							);
						}
					}
				}

				$price = $instrument_query->row['price'];

				// Instrument Discounts
				$discount_quantity = 0;

				$cart_2_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE user_id = '" . (int)$this->user->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

				foreach ($cart_2_query->rows as $cart_2) {
					if ($cart_2['instrument_id'] == $cart['instrument_id']) {
						$discount_quantity += $cart_2['quantity'];
					}
				}

				$instrument_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "instrument_discount WHERE instrument_id = '" . (int)$cart['instrument_id'] . "' AND user_group_id = '" . (int)$this->config->get('config_user_group_id') . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

				if ($instrument_discount_query->num_rows) {
					$price = $instrument_discount_query->row['price'];
				}

				// Instrument Specials
				$instrument_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "instrument_special WHERE instrument_id = '" . (int)$cart['instrument_id'] . "' AND user_group_id = '" . (int)$this->config->get('config_user_group_id') . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

				if ($instrument_special_query->num_rows) {
					$price = $instrument_special_query->row['price'];
				}

				// Reward Points
				$instrument_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "instrument_reward WHERE instrument_id = '" . (int)$cart['instrument_id'] . "' AND user_group_id = '" . (int)$this->config->get('config_user_group_id') . "'");

				if ($instrument_reward_query->num_rows) {
					$reward = $instrument_reward_query->row['points'];
				} else {
					$reward = 0;
				}

				// Downloads
				$download_data = array();

				$download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "instrument_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.instrument_id = '" . (int)$cart['instrument_id'] . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

				foreach ($download_query->rows as $download) {
					$download_data[] = array(
						'download_id' => $download['download_id'],
						'name'        => $download['name'],
						'filename'    => $download['filename'],
						'mask'        => $download['mask']
					);
				}

				// Stock
				if (!$instrument_query->row['quantity'] || ($instrument_query->row['quantity'] < $cart['quantity'])) {
					$stock = false;
				}

				$recurring_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring r LEFT JOIN " . DB_PREFIX . "instrument_recurring pr ON (r.recurring_id = pr.recurring_id) LEFT JOIN " . DB_PREFIX . "recurring_description rd ON (r.recurring_id = rd.recurring_id) WHERE r.recurring_id = '" . (int)$cart['recurring_id'] . "' AND pr.instrument_id = '" . (int)$cart['instrument_id'] . "' AND rd.language_id = " . (int)$this->config->get('config_language_id') . " AND r.status = 1 AND pr.user_group_id = '" . (int)$this->config->get('config_user_group_id') . "'");

				if ($recurring_query->num_rows) {
					$recurring = array(
						'recurring_id'    => $cart['recurring_id'],
						'name'            => $recurring_query->row['name'],
						'frequency'       => $recurring_query->row['frequency'],
						'price'           => $recurring_query->row['price'],
						'cycle'           => $recurring_query->row['cycle'],
						'duration'        => $recurring_query->row['duration'],
						'trial'           => $recurring_query->row['trial_status'],
						'trial_frequency' => $recurring_query->row['trial_frequency'],
						'trial_price'     => $recurring_query->row['trial_price'],
						'trial_cycle'     => $recurring_query->row['trial_cycle'],
						'trial_duration'  => $recurring_query->row['trial_duration']
					);
				} else {
					$recurring = false;
				}

				$instrument_data[] = array(
					'cart_id'         => $cart['cart_id'],
					'instrument_id'      => $instrument_query->row['instrument_id'],
					'name'            => $instrument_query->row['name'],
					'model'           => $instrument_query->row['model'],
					'shipping'        => $instrument_query->row['shipping'],
					'image'           => $instrument_query->row['image'],
					'option'          => $option_data,
					'download'        => $download_data,
					'quantity'        => $cart['quantity'],
					'minimum'         => $instrument_query->row['minimum'],
					'subtract'        => $instrument_query->row['subtract'],
					'stock'           => $stock,
					'price'           => ($price + $option_price),
					'total'           => ($price + $option_price) * $cart['quantity'],
					'reward'          => $reward * $cart['quantity'],
					'points'          => ($instrument_query->row['points'] ? ($instrument_query->row['points'] + $option_points) * $cart['quantity'] : 0),
					'tax_class_id'    => $instrument_query->row['tax_class_id'],
					'weight'          => ($instrument_query->row['weight'] + $option_weight) * $cart['quantity'],
					'weight_class_id' => $instrument_query->row['weight_class_id'],
					'length'          => $instrument_query->row['length'],
					'width'           => $instrument_query->row['width'],
					'height'          => $instrument_query->row['height'],
					'length_class_id' => $instrument_query->row['length_class_id'],
					'recurring'       => $recurring
				);
			} else {
				$this->remove($cart['cart_id']);
			}
		}

		return $instrument_data;
	}

	public function add($instrument_id, $quantity = 1, $option = array(), $recurring_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cart WHERE user_id = '" . (int)$this->user->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND instrument_id = '" . (int)$instrument_id . "' AND recurring_id = '" . (int)$recurring_id . "' AND `option` = '" . $this->db->escape(json_encode($option)) . "'");

		if (!$query->row['total']) {
			$this->db->query("INSERT " . DB_PREFIX . "cart SET user_id = '" . (int)$this->user->getId() . "', session_id = '" . $this->db->escape($this->session->getId()) . "', instrument_id = '" . (int)$instrument_id . "', recurring_id = '" . (int)$recurring_id . "', `option` = '" . $this->db->escape(json_encode($option)) . "', quantity = '" . (int)$quantity . "', date_added = NOW()");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "cart SET quantity = (quantity + " . (int)$quantity . ") WHERE user_id = '" . (int)$this->user->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND instrument_id = '" . (int)$instrument_id . "' AND recurring_id = '" . (int)$recurring_id . "' AND `option` = '" . $this->db->escape(json_encode($option)) . "'");
		}
	}

	public function update($cart_id, $quantity) {
		$this->db->query("UPDATE " . DB_PREFIX . "cart SET quantity = '" . (int)$quantity . "' WHERE cart_id = '" . (int)$cart_id . "' AND user_id = '" . (int)$this->user->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
	}

	public function remove($cart_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$cart_id . "' AND user_id = '" . (int)$this->user->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
	}

	public function clear() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE user_id = '" . (int)$this->user->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
	}

	public function getRecurringInstruments() {
		$instrument_data = array();

		foreach ($this->getInstruments() as $value) {
			if ($value['recurring']) {
				$instrument_data[] = $value;
			}
		}

		return $instrument_data;
	}

	public function getWeight() {
		$weight = 0;

		foreach ($this->getInstruments() as $instrument) {
			if ($instrument['shipping']) {
				$weight += $this->weight->convert($instrument['weight'], $instrument['weight_class_id'], $this->config->get('config_weight_class_id'));
			}
		}

		return $weight;
	}

	public function getSubTotal() {
		$total = 0;

		foreach ($this->getInstruments() as $instrument) {
			$total += $instrument['total'];
		}

		return $total;
	}

	public function getTaxes() {
		$tax_data = array();

		foreach ($this->getInstruments() as $instrument) {
			if ($instrument['tax_class_id']) {
				$tax_rates = $this->tax->getRates($instrument['price'], $instrument['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
						$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $instrument['quantity']);
					} else {
						$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $instrument['quantity']);
					}
				}
			}
		}

		return $tax_data;
	}

	public function getTotal() {
		$total = 0;

		foreach ($this->getInstruments() as $instrument) {
			$total += $this->tax->calculate($instrument['price'], $instrument['tax_class_id'], $this->config->get('config_tax')) * $instrument['quantity'];
		}

		return $total;
	}

	public function countInstruments() {
		$instrument_total = 0;

		$instruments = $this->getInstruments();

		foreach ($instruments as $instrument) {
			$instrument_total += $instrument['quantity'];
		}

		return $instrument_total;
	}

	public function hasInstruments() {
		return count($this->getInstruments());
	}

	public function hasRecurringInstruments() {
		return count($this->getRecurringInstruments());
	}

	public function hasStock() {
		$stock = true;

		foreach ($this->getInstruments() as $instrument) {
			if (!$instrument['stock']) {
				$stock = false;
			}
		}

		return $stock;
	}

	public function hasShipping() {
		$shipping = false;

		foreach ($this->getInstruments() as $instrument) {
			if ($instrument['shipping']) {
				$shipping = true;

				break;
			}
		}

		return $shipping;
	}

	public function hasDownload() {
		$download = false;

		foreach ($this->getInstruments() as $instrument) {
			if ($instrument['download']) {
				$download = true;

				break;
			}
		}

		return $download;
	}
}
