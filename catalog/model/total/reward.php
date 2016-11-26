<?php
class ModelTotalReward extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if ($this->config->get('credit_status') && isset($this->session->data['reward'])) {
			$this->load->language('total/reward');

			$points = $this->user->getRewardPoints();

			if ($this->session->data['reward'] <= $points) {
				$discount_total = 0;

				$points_total = 0;

				foreach ($this->cart->getInstruments() as $instrument) {
					if ($instrument['points']) {
						$points_total += $instrument['points'];
					}
				}

				$points = min($points, $points_total);

				foreach ($this->cart->getInstruments() as $instrument) {
					$discount = 0;

					if ($instrument['points']) {
						$discount = $instrument['total'] * ($this->session->data['reward'] / $points_total);

						if ($instrument['tax_class_id']) {
							$tax_rates = $this->tax->getRates($instrument['total'] - ($instrument['total'] - $discount), $instrument['tax_class_id']);

							foreach ($tax_rates as $tax_rate) {
								if ($tax_rate['type'] == 'P') {
									$taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
								}
							}
						}
					}

					$discount_total += $discount;
				}

				$total_data[] = array(
					'code'       => 'reward',
					'title'      => sprintf($this->language->get('text_reward'), $this->session->data['reward']),
					'value'      => -$discount_total,
					'sort_order' => $this->config->get('reward_sort_order')
				);

				$total -= $discount_total;
			}
		}
	}

	public function confirm($order_info, $order_total) {
		$this->load->language('total/reward');

		$points = 0;

		$start = strpos($order_total['title'], '(') + 1;
		$end = strrpos($order_total['title'], ')');

		if ($start && $end) {
			$points = substr($order_total['title'], $start, $end - $start);
		}

		$this->load->model('account/user');

		if ($this->model_account_user->getRewardTotal($order_info['user_id']) < $points) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "user_reward SET user_id = '" . (int)$order_info['user_id'] . "', order_id = '" . (int)$order_info['order_id'] . "', description = '" . $this->db->escape(sprintf($this->language->get('text_order_id'), (int)$order_info['order_id'])) . "', points = '" . (float)-$points . "', date_added = NOW()");
		} else {
			return $this->config->get('config_fraud_status_id');
		}
	}

	public function unconfirm($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "user_reward WHERE order_id = '" . (int)$order_id . "' AND points < 0");
	}
}
