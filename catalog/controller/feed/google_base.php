<?php
class ControllerFeedGoogleBase extends Controller {
	public function index() {
		if ($this->config->get('google_base_status')) {
			$output  = '<?xml version="1.0" encoding="UTF-8" ?>';
			$output .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
			$output .= '<channel>';
			$output .= '<title>' . $this->config->get('config_name') . '</title>';
			$output .= '<description>' . $this->config->get('config_meta_description') . '</description>';
			$output .= '<link>' . HTTP_SERVER . '</link>';

			$this->load->model('feed/google_base');
			$this->load->model('catalog/category');
			$this->load->model('catalog/instrument');

			$this->load->model('tool/image');

			$instrument_data = array();

			$google_base_categories = $this->model_feed_google_base->getCategories();

			foreach ($google_base_categories as $google_base_category) {
				$filter_data = array(
					'filter_category_id' => $google_base_category['category_id'],
					'filter_filter'      => false
				);

				$instruments = $this->model_catalog_instrument->getInstruments($filter_data);

				foreach ($instruments as $instrument) {
					if (!in_array($instrument['instrument_id'], $instrument_data) && $instrument['description']) {
						$output .= '<item>';
						$output .= '<title><![CDATA[' . $instrument['name'] . ']]></title>';
						$output .= '<link>' . $this->url->link('instrument/instrument', 'instrument_id=' . $instrument['instrument_id']) . '</link>';
						$output .= '<description><![CDATA[' . $instrument['description'] . ']]></description>';
						$output .= '<g:brand><![CDATA[' . html_entity_decode($instrument['manufacturer'], ENT_QUOTES, 'UTF-8') . ']]></g:brand>';
						$output .= '<g:condition>new</g:condition>';
						$output .= '<g:id>' . $instrument['instrument_id'] . '</g:id>';

						if ($instrument['image']) {
							$output .= '<g:image_link>' . $this->model_tool_image->resize($instrument['image'], 500, 500) . '</g:image_link>';
						} else {
							$output .= '<g:image_link></g:image_link>';
						}

						$output .= '<g:model_number>' . $instrument['model'] . '</g:model_number>';

						if ($instrument['mpn']) {
							$output .= '<g:mpn><![CDATA[' . $instrument['mpn'] . ']]></g:mpn>' ;
						} else {
							$output .= '<g:identifier_exists>false</g:identifier_exists>';
						}

						if ($instrument['upc']) {
							$output .= '<g:upc>' . $instrument['upc'] . '</g:upc>';
						}

						if ($instrument['ean']) {
							$output .= '<g:ean>' . $instrument['ean'] . '</g:ean>';
						}

						$currencies = array(
							'USD',
							'EUR',
							'GBP'
						);

						if (in_array($this->currency->getCode(), $currencies)) {
							$currency_code = $this->currency->getCode();
							$currency_value = $this->currency->getValue();
						} else {
							$currency_code = 'USD';
							$currency_value = $this->currency->getValue('USD');
						}

						if ((float)$instrument['special']) {
							$output .= '<g:price>' .  $this->currency->format($this->tax->calculate($instrument['special'], $instrument['tax_class_id']), $currency_code, $currency_value, false) . '</g:price>';
						} else {
							$output .= '<g:price>' . $this->currency->format($this->tax->calculate($instrument['price'], $instrument['tax_class_id']), $currency_code, $currency_value, false) . '</g:price>';
						}

						$output .= '<g:google_instrument_category>' . $google_base_category['google_base_category_id'] . '</g:google_instrument_category>';

						$categories = $this->model_catalog_instrument->getCategories($instrument['instrument_id']);

						foreach ($categories as $category) {
							$path = $this->getPath($category['category_id']);

							if ($path) {
								$string = '';

								foreach (explode('_', $path) as $path_id) {
									$category_info = $this->model_catalog_category->getCategory($path_id);

									if ($category_info) {
										if (!$string) {
											$string = $category_info['name'];
										} else {
											$string .= ' &gt; ' . $category_info['name'];
										}
									}
								}

								$output .= '<g:instrument_type><![CDATA[' . $string . ']]></g:instrument_type>';
							}
						}

						$output .= '<g:quantity>' . $instrument['quantity'] . '</g:quantity>';
						$output .= '<g:weight>' . $this->weight->format($instrument['weight'], $instrument['weight_class_id']) . '</g:weight>';
						$output .= '<g:availability><![CDATA[' . ($instrument['quantity'] ? 'in stock' : 'out of stock') . ']]></g:availability>';
						$output .= '</item>';
					}
				}
			}

			$output .= '</channel>';
			$output .= '</rss>';

			$this->response->addHeader('Content-Type: application/rss+xml');
			$this->response->setOutput($output);
		}
	}

	protected function getPath($parent_id, $current_path = '') {
		$category_info = $this->model_catalog_category->getCategory($parent_id);

		if ($category_info) {
			if (!$current_path) {
				$new_path = $category_info['category_id'];
			} else {
				$new_path = $category_info['category_id'] . '_' . $current_path;
			}

			$path = $this->getPath($category_info['parent_id'], $new_path);

			if ($path) {
				return $path;
			} else {
				return $new_path;
			}
		}
	}
}
