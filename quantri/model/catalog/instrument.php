<?php
class ModelCatalogInstrument extends Model {
	public function addInstrument($data) {
		$this->event->trigger('pre.admin.instrument.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "instrument SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW()");

		$instrument_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "instrument SET image = '" . $this->db->escape($data['image']) . "' WHERE instrument_id = '" . (int)$instrument_id . "'");
		}

		foreach ($data['instrument_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_description SET instrument_id = '" . (int)$instrument_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['instrument_store'])) {
			foreach ($data['instrument_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_to_store SET instrument_id = '" . (int)$instrument_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['instrument_attribute'])) {
			foreach ($data['instrument_attribute'] as $instrument_attribute) {
				if ($instrument_attribute['attribute_id']) {
					foreach ($instrument_attribute['instrument_attribute_description'] as $language_id => $instrument_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_attribute SET instrument_id = '" . (int)$instrument_id . "', attribute_id = '" . (int)$instrument_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($instrument_attribute_description['text']) . "'");
					}
				}
			}
		}

		if (isset($data['instrument_option'])) {
			foreach ($data['instrument_option'] as $instrument_option) {
				if ($instrument_option['type'] == 'select' || $instrument_option['type'] == 'radio' || $instrument_option['type'] == 'checkbox' || $instrument_option['type'] == 'image') {
					if (isset($instrument_option['instrument_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_option SET instrument_id = '" . (int)$instrument_id . "', option_id = '" . (int)$instrument_option['option_id'] . "', required = '" . (int)$instrument_option['required'] . "'");

						$instrument_option_id = $this->db->getLastId();

						foreach ($instrument_option['instrument_option_value'] as $instrument_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_option_value SET instrument_option_id = '" . (int)$instrument_option_id . "', instrument_id = '" . (int)$instrument_id . "', option_id = '" . (int)$instrument_option['option_id'] . "', option_value_id = '" . (int)$instrument_option_value['option_value_id'] . "', quantity = '" . (int)$instrument_option_value['quantity'] . "', subtract = '" . (int)$instrument_option_value['subtract'] . "', price = '" . (float)$instrument_option_value['price'] . "', price_prefix = '" . $this->db->escape($instrument_option_value['price_prefix']) . "', points = '" . (int)$instrument_option_value['points'] . "', points_prefix = '" . $this->db->escape($instrument_option_value['points_prefix']) . "', weight = '" . (float)$instrument_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($instrument_option_value['weight_prefix']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_option SET instrument_id = '" . (int)$instrument_id . "', option_id = '" . (int)$instrument_option['option_id'] . "', value = '" . $this->db->escape($instrument_option['value']) . "', required = '" . (int)$instrument_option['required'] . "'");
				}
			}
		}

		if (isset($data['instrument_discount'])) {
			foreach ($data['instrument_discount'] as $instrument_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_discount SET instrument_id = '" . (int)$instrument_id . "', user_group_id = '" . (int)$instrument_discount['user_group_id'] . "', quantity = '" . (int)$instrument_discount['quantity'] . "', priority = '" . (int)$instrument_discount['priority'] . "', price = '" . (float)$instrument_discount['price'] . "', date_start = '" . $this->db->escape($instrument_discount['date_start']) . "', date_end = '" . $this->db->escape($instrument_discount['date_end']) . "'");
			}
		}

		if (isset($data['instrument_special'])) {
			foreach ($data['instrument_special'] as $instrument_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_special SET instrument_id = '" . (int)$instrument_id . "', user_group_id = '" . (int)$instrument_special['user_group_id'] . "', priority = '" . (int)$instrument_special['priority'] . "', price = '" . (float)$instrument_special['price'] . "', date_start = '" . $this->db->escape($instrument_special['date_start']) . "', date_end = '" . $this->db->escape($instrument_special['date_end']) . "'");
			}
		}

		if (isset($data['instrument_image'])) {
			foreach ($data['instrument_image'] as $instrument_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_image SET instrument_id = '" . (int)$instrument_id . "', image = '" . $this->db->escape($instrument_image['image']) . "', sort_order = '" . (int)$instrument_image['sort_order'] . "'");
			}
		}

		if (isset($data['instrument_download'])) {
			foreach ($data['instrument_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_to_download SET instrument_id = '" . (int)$instrument_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		if (isset($data['instrument_category'])) {
			foreach ($data['instrument_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_to_category SET instrument_id = '" . (int)$instrument_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		if (isset($data['instrument_filter'])) {
			foreach ($data['instrument_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_filter SET instrument_id = '" . (int)$instrument_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		if (isset($data['instrument_related'])) {
			foreach ($data['instrument_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_related WHERE instrument_id = '" . (int)$instrument_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_related SET instrument_id = '" . (int)$instrument_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_related WHERE instrument_id = '" . (int)$related_id . "' AND related_id = '" . (int)$instrument_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_related SET instrument_id = '" . (int)$related_id . "', related_id = '" . (int)$instrument_id . "'");
			}
		}

		if (isset($data['instrument_reward'])) {
			foreach ($data['instrument_reward'] as $user_group_id => $instrument_reward) {
				if ((int)$instrument_reward['points'] > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_reward SET instrument_id = '" . (int)$instrument_id . "', user_group_id = '" . (int)$user_group_id . "', points = '" . (int)$instrument_reward['points'] . "'");
				}
			}
		}

		if (isset($data['instrument_layout'])) {
			foreach ($data['instrument_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_to_layout SET instrument_id = '" . (int)$instrument_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if (!empty($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'instrument_id=" . (int)$instrument_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}else{
			$language_id = (int)$this->config->get('config_language_id');
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'instrument_id=" . (int)$instrument_id . "', keyword = '" . $this->db->escape(seo_alias($data['instrument_description'][$language_id]['name'])) . "'");
		}

		if (isset($data['instrument_recurrings'])) {
			foreach ($data['instrument_recurrings'] as $recurring) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "instrument_recurring` SET `instrument_id` = " . (int)$instrument_id . ", user_group_id = " . (int)$recurring['user_group_id'] . ", `recurring_id` = " . (int)$recurring['recurring_id']);
			}
		}

		$this->cache->delete('instrument');

		$this->event->trigger('post.admin.instrument.add', $instrument_id);

		return $instrument_id;
	}

	public function editInstrument($instrument_id, $data) {
		$this->event->trigger('pre.admin.instrument.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "instrument SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE instrument_id = '" . (int)$instrument_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "instrument SET image = '" . $this->db->escape($data['image']) . "' WHERE instrument_id = '" . (int)$instrument_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_description WHERE instrument_id = '" . (int)$instrument_id . "'");

		foreach ($data['instrument_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_description SET instrument_id = '" . (int)$instrument_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_to_store WHERE instrument_id = '" . (int)$instrument_id . "'");

		if (isset($data['instrument_store'])) {
			foreach ($data['instrument_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_to_store SET instrument_id = '" . (int)$instrument_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_attribute WHERE instrument_id = '" . (int)$instrument_id . "'");

		if (!empty($data['instrument_attribute'])) {
			foreach ($data['instrument_attribute'] as $instrument_attribute) {
				if ($instrument_attribute['attribute_id']) {
					foreach ($instrument_attribute['instrument_attribute_description'] as $language_id => $instrument_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_attribute SET instrument_id = '" . (int)$instrument_id . "', attribute_id = '" . (int)$instrument_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($instrument_attribute_description['text']) . "'");
					}
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_option WHERE instrument_id = '" . (int)$instrument_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_option_value WHERE instrument_id = '" . (int)$instrument_id . "'");

		if (isset($data['instrument_option'])) {
			foreach ($data['instrument_option'] as $instrument_option) {
				if ($instrument_option['type'] == 'select' || $instrument_option['type'] == 'radio' || $instrument_option['type'] == 'checkbox' || $instrument_option['type'] == 'image') {
					if (isset($instrument_option['instrument_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_option SET instrument_option_id = '" . (int)$instrument_option['instrument_option_id'] . "', instrument_id = '" . (int)$instrument_id . "', option_id = '" . (int)$instrument_option['option_id'] . "', required = '" . (int)$instrument_option['required'] . "'");

						$instrument_option_id = $this->db->getLastId();

						foreach ($instrument_option['instrument_option_value'] as $instrument_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_option_value SET instrument_option_value_id = '" . (int)$instrument_option_value['instrument_option_value_id'] . "', instrument_option_id = '" . (int)$instrument_option_id . "', instrument_id = '" . (int)$instrument_id . "', option_id = '" . (int)$instrument_option['option_id'] . "', option_value_id = '" . (int)$instrument_option_value['option_value_id'] . "', quantity = '" . (int)$instrument_option_value['quantity'] . "', subtract = '" . (int)$instrument_option_value['subtract'] . "', price = '" . (float)$instrument_option_value['price'] . "', price_prefix = '" . $this->db->escape($instrument_option_value['price_prefix']) . "', points = '" . (int)$instrument_option_value['points'] . "', points_prefix = '" . $this->db->escape($instrument_option_value['points_prefix']) . "', weight = '" . (float)$instrument_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($instrument_option_value['weight_prefix']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_option SET instrument_option_id = '" . (int)$instrument_option['instrument_option_id'] . "', instrument_id = '" . (int)$instrument_id . "', option_id = '" . (int)$instrument_option['option_id'] . "', value = '" . $this->db->escape($instrument_option['value']) . "', required = '" . (int)$instrument_option['required'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_discount WHERE instrument_id = '" . (int)$instrument_id . "'");

		if (isset($data['instrument_discount'])) {
			foreach ($data['instrument_discount'] as $instrument_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_discount SET instrument_id = '" . (int)$instrument_id . "', user_group_id = '" . (int)$instrument_discount['user_group_id'] . "', quantity = '" . (int)$instrument_discount['quantity'] . "', priority = '" . (int)$instrument_discount['priority'] . "', price = '" . (float)$instrument_discount['price'] . "', date_start = '" . $this->db->escape($instrument_discount['date_start']) . "', date_end = '" . $this->db->escape($instrument_discount['date_end']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_special WHERE instrument_id = '" . (int)$instrument_id . "'");

		if (isset($data['instrument_special'])) {
			foreach ($data['instrument_special'] as $instrument_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_special SET instrument_id = '" . (int)$instrument_id . "', user_group_id = '" . (int)$instrument_special['user_group_id'] . "', priority = '" . (int)$instrument_special['priority'] . "', price = '" . (float)$instrument_special['price'] . "', date_start = '" . $this->db->escape($instrument_special['date_start']) . "', date_end = '" . $this->db->escape($instrument_special['date_end']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_image WHERE instrument_id = '" . (int)$instrument_id . "'");

		if (isset($data['instrument_image'])) {
			foreach ($data['instrument_image'] as $instrument_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_image SET instrument_id = '" . (int)$instrument_id . "', image = '" . $this->db->escape($instrument_image['image']) . "', sort_order = '" . (int)$instrument_image['sort_order'] . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_to_download WHERE instrument_id = '" . (int)$instrument_id . "'");

		if (isset($data['instrument_download'])) {
			foreach ($data['instrument_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_to_download SET instrument_id = '" . (int)$instrument_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_to_category WHERE instrument_id = '" . (int)$instrument_id . "'");

		if (isset($data['instrument_category'])) {
			foreach ($data['instrument_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_to_category SET instrument_id = '" . (int)$instrument_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_filter WHERE instrument_id = '" . (int)$instrument_id . "'");

		if (isset($data['instrument_filter'])) {
			foreach ($data['instrument_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_filter SET instrument_id = '" . (int)$instrument_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_related WHERE instrument_id = '" . (int)$instrument_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_related WHERE related_id = '" . (int)$instrument_id . "'");

		if (isset($data['instrument_related'])) {
			foreach ($data['instrument_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_related WHERE instrument_id = '" . (int)$instrument_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_related SET instrument_id = '" . (int)$instrument_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_related WHERE instrument_id = '" . (int)$related_id . "' AND related_id = '" . (int)$instrument_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_related SET instrument_id = '" . (int)$related_id . "', related_id = '" . (int)$instrument_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_reward WHERE instrument_id = '" . (int)$instrument_id . "'");

		if (isset($data['instrument_reward'])) {
			foreach ($data['instrument_reward'] as $user_group_id => $value) {
				if ((int)$value['points'] > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_reward SET instrument_id = '" . (int)$instrument_id . "', user_group_id = '" . (int)$user_group_id . "', points = '" . (int)$value['points'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_to_layout WHERE instrument_id = '" . (int)$instrument_id . "'");

		if (isset($data['instrument_layout'])) {
			foreach ($data['instrument_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "instrument_to_layout SET instrument_id = '" . (int)$instrument_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'instrument_id=" . (int)$instrument_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'instrument_id=" . (int)$instrument_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "instrument_recurring` WHERE instrument_id = " . (int)$instrument_id);

		if (isset($data['instrument_recurring'])) {
			foreach ($data['instrument_recurring'] as $instrument_recurring) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "instrument_recurring` SET `instrument_id` = " . (int)$instrument_id . ", user_group_id = " . (int)$instrument_recurring['user_group_id'] . ", `recurring_id` = " . (int)$instrument_recurring['recurring_id']);
			}
		}

		$this->cache->delete('instrument');

		$this->event->trigger('post.admin.instrument.edit', $instrument_id);
	}

	public function copyInstrument($instrument_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "instrument p LEFT JOIN " . DB_PREFIX . "instrument_description pd ON (p.instrument_id = pd.instrument_id) WHERE p.instrument_id = '" . (int)$instrument_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		if ($query->num_rows) {
			$data = $query->row;

			$data['sku'] = '';
			$data['upc'] = '';
			$data['viewed'] = '0';
			$data['keyword'] = '';
			$data['status'] = '0';

			$data['instrument_attribute'] = $this->getInstrumentAttributes($instrument_id);
			$data['instrument_description'] = $this->getInstrumentDescriptions($instrument_id);
			$data['instrument_discount'] = $this->getInstrumentDiscounts($instrument_id);
			$data['instrument_filter'] = $this->getInstrumentFilters($instrument_id);
			$data['instrument_image'] = $this->getInstrumentImages($instrument_id);
			$data['instrument_option'] = $this->getInstrumentOptions($instrument_id);
			$data['instrument_related'] = $this->getInstrumentRelated($instrument_id);
			$data['instrument_reward'] = $this->getInstrumentRewards($instrument_id);
			$data['instrument_special'] = $this->getInstrumentSpecials($instrument_id);
			$data['instrument_category'] = $this->getInstrumentCategories($instrument_id);
			$data['instrument_download'] = $this->getInstrumentDownloads($instrument_id);
			$data['instrument_layout'] = $this->getInstrumentLayouts($instrument_id);
			$data['instrument_store'] = $this->getInstrumentStores($instrument_id);
			$data['instrument_recurrings'] = $this->getRecurrings($instrument_id);

			$this->addInstrument($data);
		}
	}

	public function deleteInstrument($instrument_id) {
		$this->event->trigger('pre.admin.instrument.delete', $instrument_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument WHERE instrument_id = '" . (int)$instrument_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_attribute WHERE instrument_id = '" . (int)$instrument_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_description WHERE instrument_id = '" . (int)$instrument_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_discount WHERE instrument_id = '" . (int)$instrument_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_filter WHERE instrument_id = '" . (int)$instrument_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_image WHERE instrument_id = '" . (int)$instrument_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_option WHERE instrument_id = '" . (int)$instrument_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_option_value WHERE instrument_id = '" . (int)$instrument_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_related WHERE instrument_id = '" . (int)$instrument_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_related WHERE related_id = '" . (int)$instrument_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_reward WHERE instrument_id = '" . (int)$instrument_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_special WHERE instrument_id = '" . (int)$instrument_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_to_category WHERE instrument_id = '" . (int)$instrument_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_to_download WHERE instrument_id = '" . (int)$instrument_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_to_layout WHERE instrument_id = '" . (int)$instrument_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_to_store WHERE instrument_id = '" . (int)$instrument_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "instrument_recurring WHERE instrument_id = " . (int)$instrument_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE instrument_id = '" . (int)$instrument_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'instrument_id=" . (int)$instrument_id . "'");

		$this->cache->delete('instrument');

		$this->event->trigger('post.admin.instrument.delete', $instrument_id);
	}

	public function getInstrument($instrument_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'instrument_id=" . (int)$instrument_id . "') AS keyword FROM " . DB_PREFIX . "instrument p LEFT JOIN " . DB_PREFIX . "instrument_description pd ON (p.instrument_id = pd.instrument_id) WHERE p.instrument_id = '" . (int)$instrument_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getInstruments($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "instrument p LEFT JOIN " . DB_PREFIX . "instrument_description pd ON (p.instrument_id = pd.instrument_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " GROUP BY p.instrument_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
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

	public function getInstrumentsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "instrument p LEFT JOIN " . DB_PREFIX . "instrument_description pd ON (p.instrument_id = pd.instrument_id) LEFT JOIN " . DB_PREFIX . "instrument_to_category p2c ON (p.instrument_id = p2c.instrument_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function getInstrumentDescriptions($instrument_id) {
		$instrument_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "instrument_description WHERE instrument_id = '" . (int)$instrument_id . "'");

		foreach ($query->rows as $result) {
			$instrument_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'tag'              => $result['tag']
			);
		}

		return $instrument_description_data;
	}

	public function getInstrumentCategories($instrument_id) {
		$instrument_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "instrument_to_category WHERE instrument_id = '" . (int)$instrument_id . "'");

		foreach ($query->rows as $result) {
			$instrument_category_data[] = $result['category_id'];
		}

		return $instrument_category_data;
	}

	public function getInstrumentFilters($instrument_id) {
		$instrument_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "instrument_filter WHERE instrument_id = '" . (int)$instrument_id . "'");

		foreach ($query->rows as $result) {
			$instrument_filter_data[] = $result['filter_id'];
		}

		return $instrument_filter_data;
	}

	public function getInstrumentAttributes($instrument_id) {
		$instrument_attribute_data = array();

		$instrument_attribute_query = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "instrument_attribute WHERE instrument_id = '" . (int)$instrument_id . "' GROUP BY attribute_id");

		foreach ($instrument_attribute_query->rows as $instrument_attribute) {
			$instrument_attribute_description_data = array();

			$instrument_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "instrument_attribute WHERE instrument_id = '" . (int)$instrument_id . "' AND attribute_id = '" . (int)$instrument_attribute['attribute_id'] . "'");

			foreach ($instrument_attribute_description_query->rows as $instrument_attribute_description) {
				$instrument_attribute_description_data[$instrument_attribute_description['language_id']] = array('text' => $instrument_attribute_description['text']);
			}

			$instrument_attribute_data[] = array(
				'attribute_id'                  => $instrument_attribute['attribute_id'],
				'instrument_attribute_description' => $instrument_attribute_description_data
			);
		}

		return $instrument_attribute_data;
	}

	public function getInstrumentOptions($instrument_id) {
		$instrument_option_data = array();

		$instrument_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "instrument_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.instrument_id = '" . (int)$instrument_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($instrument_option_query->rows as $instrument_option) {
			$instrument_option_value_data = array();

			$instrument_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "instrument_option_value WHERE instrument_option_id = '" . (int)$instrument_option['instrument_option_id'] . "'");

			foreach ($instrument_option_value_query->rows as $instrument_option_value) {
				$instrument_option_value_data[] = array(
					'instrument_option_value_id' => $instrument_option_value['instrument_option_value_id'],
					'option_value_id'         => $instrument_option_value['option_value_id'],
					'quantity'                => $instrument_option_value['quantity'],
					'subtract'                => $instrument_option_value['subtract'],
					'price'                   => $instrument_option_value['price'],
					'price_prefix'            => $instrument_option_value['price_prefix'],
					'points'                  => $instrument_option_value['points'],
					'points_prefix'           => $instrument_option_value['points_prefix'],
					'weight'                  => $instrument_option_value['weight'],
					'weight_prefix'           => $instrument_option_value['weight_prefix']
				);
			}

			$instrument_option_data[] = array(
				'instrument_option_id'    => $instrument_option['instrument_option_id'],
				'instrument_option_value' => $instrument_option_value_data,
				'option_id'            => $instrument_option['option_id'],
				'name'                 => $instrument_option['name'],
				'type'                 => $instrument_option['type'],
				'value'                => $instrument_option['value'],
				'required'             => $instrument_option['required']
			);
		}

		return $instrument_option_data;
	}

	public function getInstrumentOptionValue($instrument_id, $instrument_option_value_id) {
		$query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "instrument_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.instrument_id = '" . (int)$instrument_id . "' AND pov.instrument_option_value_id = '" . (int)$instrument_option_value_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getInstrumentImages($instrument_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "instrument_image WHERE instrument_id = '" . (int)$instrument_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getInstrumentDiscounts($instrument_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "instrument_discount WHERE instrument_id = '" . (int)$instrument_id . "' ORDER BY quantity, priority, price");

		return $query->rows;
	}

	public function getInstrumentSpecials($instrument_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "instrument_special WHERE instrument_id = '" . (int)$instrument_id . "' ORDER BY priority, price");

		return $query->rows;
	}

	public function getInstrumentRewards($instrument_id) {
		$instrument_reward_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "instrument_reward WHERE instrument_id = '" . (int)$instrument_id . "'");

		foreach ($query->rows as $result) {
			$instrument_reward_data[$result['user_group_id']] = array('points' => $result['points']);
		}

		return $instrument_reward_data;
	}

	public function getInstrumentDownloads($instrument_id) {
		$instrument_download_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "instrument_to_download WHERE instrument_id = '" . (int)$instrument_id . "'");

		foreach ($query->rows as $result) {
			$instrument_download_data[] = $result['download_id'];
		}

		return $instrument_download_data;
	}

	public function getInstrumentStores($instrument_id) {
		$instrument_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "instrument_to_store WHERE instrument_id = '" . (int)$instrument_id . "'");

		foreach ($query->rows as $result) {
			$instrument_store_data[] = $result['store_id'];
		}

		return $instrument_store_data;
	}

	public function getInstrumentLayouts($instrument_id) {
		$instrument_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "instrument_to_layout WHERE instrument_id = '" . (int)$instrument_id . "'");

		foreach ($query->rows as $result) {
			$instrument_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $instrument_layout_data;
	}

	public function getInstrumentRelated($instrument_id) {
		$instrument_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "instrument_related WHERE instrument_id = '" . (int)$instrument_id . "'");

		foreach ($query->rows as $result) {
			$instrument_related_data[] = $result['related_id'];
		}

		return $instrument_related_data;
	}

	public function getRecurrings($instrument_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "instrument_recurring` WHERE instrument_id = '" . (int)$instrument_id . "'");

		return $query->rows;
	}

	public function getTotalInstruments($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.instrument_id) AS total FROM " . DB_PREFIX . "instrument p LEFT JOIN " . DB_PREFIX . "instrument_description pd ON (p.instrument_id = pd.instrument_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalInstrumentsByTaxClassId($tax_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "instrument WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalInstrumentsByStockStatusId($stock_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "instrument WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		return $query->row['total'];
	}

	public function getTotalInstrumentsByWeightClassId($weight_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "instrument WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalInstrumentsByLengthClassId($length_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "instrument WHERE length_class_id = '" . (int)$length_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalInstrumentsByDownloadId($download_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "instrument_to_download WHERE download_id = '" . (int)$download_id . "'");

		return $query->row['total'];
	}

	public function getTotalInstrumentsByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "instrument WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row['total'];
	}

	public function getTotalInstrumentsByAttributeId($attribute_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "instrument_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");

		return $query->row['total'];
	}

	public function getTotalInstrumentsByOptionId($option_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "instrument_option WHERE option_id = '" . (int)$option_id . "'");

		return $query->row['total'];
	}

	public function getTotalInstrumentsByProfileId($recurring_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "instrument_recurring WHERE recurring_id = '" . (int)$recurring_id . "'");

		return $query->row['total'];
	}

	public function getTotalInstrumentsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "instrument_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
}
