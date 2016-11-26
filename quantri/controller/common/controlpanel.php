<?php
class ControllerCommonControlpanel extends Controller {
	public function index() {
		$this->load->language('common/controlpanel');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
		
		$this->load->language('common/menu');
		
		$data['text_manages'] = $this->language->get('text_manages');
		$data['text_map'] = $this->language->get('text_map');
		$data['text_activity'] = $this->language->get('text_activity');
		$data['text_recent'] = $this->language->get('text_recent');
		$data['text_analytics'] = $this->language->get('text_analytics');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_api'] = $this->language->get('text_api');
		$data['text_attribute'] = $this->language->get('text_attribute');
		$data['text_attribute_group'] = $this->language->get('text_attribute_group');
		$data['text_backup'] = $this->language->get('text_backup');
		$data['text_banner'] = $this->language->get('text_banner');
		$data['text_captcha'] = $this->language->get('text_captcha');
		$data['text_catalog'] = $this->language->get('text_catalog');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_contact'] = $this->language->get('text_contact');
		$data['text_country'] = $this->language->get('text_country');
		$data['text_coupon'] = $this->language->get('text_coupon');
		$data['text_currency'] = $this->language->get('text_currency');
		$data['text_user'] = $this->language->get('text_user');
		$data['text_user_group'] = $this->language->get('text_user_group');
		$data['text_user_field'] = $this->language->get('text_user_field');
		$data['text_custom_field'] = $this->language->get('text_custom_field');
		$data['text_manage'] = $this->language->get('text_manage');
		$data['text_paypal'] = $this->language->get('text_paypal');
		$data['text_paypal_search'] = $this->language->get('text_paypal_search');
		$data['text_design'] = $this->language->get('text_design');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_error_log'] = $this->language->get('text_error_log');
		$data['text_export_import'] = $this->language->get('text_export_import');
		$data['text_folder_protect'] = $this->language->get('text_folder_protect');
		$data['text_extension'] = $this->language->get('text_extension');
		$data['text_feed'] = $this->language->get('text_feed');
		$data['text_fraud'] = $this->language->get('text_fraud');
		$data['text_filter'] = $this->language->get('text_filter');
		$data['text_geo_zone'] = $this->language->get('text_geo_zone');
		$data['text_dashboard'] = $this->language->get('text_dashboard');
		$data['text_help'] = $this->language->get('text_help');
		$data['text_extra_info'] = $this->language->get('text_extra_info');
		$data['text_features'] = $this->language->get('text_features');
		$data['text_pricing_tables'] = $this->language->get('text_pricing_tables');
		$data['text_content_info'] = $this->language->get('text_content_info');
		$data['text_page'] = $this->language->get('text_page');
		$data['text_information'] = $this->language->get('text_information');
		$data['text_installer'] = $this->language->get('text_installer');
		$data['text_language'] = $this->language->get('text_language');
		$data['text_language_editor'] = $this->language->get('text_language_editor');
		$data['text_layout'] = $this->language->get('text_layout');
		$data['text_megamenu'] = $this->language->get('text_megamenu');
		$data['text_navigation'] = $this->language->get('text_navigation');
		$data['text_block'] = $this->language->get('text_block');
		$data['text_menu'] = $this->language->get('text_menu');
		$data['text_localisation'] = $this->language->get('text_localisation');
		$data['text_location'] = $this->language->get('text_location');
		$data['text_marketing'] = $this->language->get('text_marketing');
		$data['text_modification'] = $this->language->get('text_modification');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_module'] = $this->language->get('text_module');
		$data['text_option'] = $this->language->get('text_option');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_order_status'] = $this->language->get('text_order_status');
		$data['text_opencart'] = $this->language->get('text_opencart');
		$data['text_payment'] = $this->language->get('text_payment');
		$data['text_instrument'] = $this->language->get('text_instrument');
		$data['text_reports'] = $this->language->get('text_reports');
		$data['text_reports1'] = $this->language->get('text_reports1');
		$data['text_report_manage_order'] = $this->language->get('text_report_manage_order');
		$data['text_report_manage_tax'] = $this->language->get('text_report_manage_tax');
		$data['text_report_manage_shipping'] = $this->language->get('text_report_manage_shipping');
		$data['text_report_manage_return'] = $this->language->get('text_report_manage_return');
		$data['text_report_manage_coupon'] = $this->language->get('text_report_manage_coupon');
		$data['text_report_manage_return'] = $this->language->get('text_report_manage_return');
		$data['text_report_instrument_viewed'] = $this->language->get('text_report_instrument_viewed');
		$data['text_report_instrument_purchased'] = $this->language->get('text_report_instrument_purchased');
		$data['text_report_user_activity'] = $this->language->get('text_report_user_activity');
		$data['text_report_user_online'] = $this->language->get('text_report_user_online');
		$data['text_report_user_order'] = $this->language->get('text_report_user_order');
		$data['text_report_user_reward'] = $this->language->get('text_report_user_reward');
		$data['text_report_user_credit'] = $this->language->get('text_report_user_credit');
		$data['text_report_user_order'] = $this->language->get('text_report_user_order');
		$data['text_report_affiliate'] = $this->language->get('text_report_affiliate');
		$data['text_report_affiliate_activity'] = $this->language->get('text_report_affiliate_activity');
		$data['text_review'] = $this->language->get('text_review');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_return_action'] = $this->language->get('text_return_action');
		$data['text_return_reason'] = $this->language->get('text_return_reason');
		$data['text_return_status'] = $this->language->get('text_return_status');
		$data['text_shipping'] = $this->language->get('text_shipping');
		$data['text_setting'] = $this->language->get('text_setting');
		$data['text_stock_status'] = $this->language->get('text_stock_status');
		$data['text_system'] = $this->language->get('text_system');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['text_tax_class'] = $this->language->get('text_tax_class');
		$data['text_tax_rate'] = $this->language->get('text_tax_rate');
		$data['text_tools'] = $this->language->get('text_tools');
		$data['text_total'] = $this->language->get('text_total');
		$data['text_upload'] = $this->language->get('text_upload');
		$data['text_tracking'] = $this->language->get('text_tracking');
		$data['text_staff'] = $this->language->get('text_staff');
		$data['text_staff_group'] = $this->language->get('text_staff_group');
		$data['text_staffs'] = $this->language->get('text_staffs');
		$data['text_voucher'] = $this->language->get('text_voucher');
		$data['text_voucher_theme'] = $this->language->get('text_voucher_theme');
		$data['text_weight_class'] = $this->language->get('text_weight_class');
		$data['text_length_class'] = $this->language->get('text_length_class');
		$data['text_zone'] = $this->language->get('text_zone');
		$data['text_recurring'] = $this->language->get('text_recurring');
		$data['text_order_recurring'] = $this->language->get('text_order_recurring');
		$data['text_openbay_extension'] = $this->language->get('text_openbay_extension');
		$data['text_openbay_dashboard'] = $this->language->get('text_openbay_dashboard');
		$data['text_openbay_orders'] = $this->language->get('text_openbay_orders');
		$data['text_openbay_items'] = $this->language->get('text_openbay_items');
		$data['text_openbay_ebay'] = $this->language->get('text_openbay_ebay');
		$data['text_openbay_etsy'] = $this->language->get('text_openbay_etsy');
		$data['text_openbay_amazon'] = $this->language->get('text_openbay_amazon');
		$data['text_openbay_amazonus'] = $this->language->get('text_openbay_amazonus');
		$data['text_openbay_settings'] = $this->language->get('text_openbay_settings');
		$data['text_openbay_links'] = $this->language->get('text_openbay_links');
		$data['text_openbay_report_price'] = $this->language->get('text_openbay_report_price');
		$data['text_openbay_order_import'] = $this->language->get('text_openbay_order_import');
		$data['text_visual_builder'] = $this->language->get('text_visual_builder');
		$data['text_logo'] = $this->language->get('text_logo');
		$data['text_favicon'] = $this->language->get('text_favicon');
		$data['text_address'] = $this->language->get('text_address');
		$data['text_featured_instrument'] = $this->language->get('text_featured_instrument');
		$data['text_slide'] = $this->language->get('text_slide');
		$data['text_news'] = $this->language->get('text_news');
		$data['text_footer_post'] = $this->language->get('text_footer_post');
		$data['text_social'] = $this->language->get('text_social');
		$data['text_footer_address'] = $this->language->get('text_footer_address');
		$data['text_footer_map'] = $this->language->get('text_footer_map');
		$data['text_header'] = $this->language->get('text_header');
		$data['text_downloadpost'] = 'Download File';
		$data['text_thread'] = $this->language->get('text_thread');
		$data['text_relatespost'] = $this->language->get('text_relatespost');
		$data['text_commentspost'] = $this->language->get('text_commentspost');
		$data['text_reportspost'] = $this->language->get('text_reportspost');
		$data['text_mangestaff'] = $this->language->get('text_mangestaff');
		$data['text_settingpost'] = $this->language->get('text_settingpost');

		$data['analytics'] = $this->url->link('extension/analytics', 'token=' . $this->session->data['token'], 'SSL');
		$data['xform_url'] = $this->url->link('module/xform', 'token=' . $this->session->data['token'], 'SSL');
		$data['home'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
		$data['visualbuilder'] = $this->url->link('module/visualbuilder', 'token=' . $this->session->data['token'], 'SSL');
		$data['affiliate'] = $this->url->link('marketing/affiliate', 'token=' . $this->session->data['token'], 'SSL');
		$data['api'] = $this->url->link('staff/api', 'token=' . $this->session->data['token'], 'SSL');
		$data['attribute'] = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'], 'SSL');
		$data['attribute_group'] = $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'], 'SSL');
		$data['backup'] = $this->url->link('tool/backup', 'token=' . $this->session->data['token'], 'SSL');
		$data['banner'] = $this->url->link('design/banner', 'token=' . $this->session->data['token'], 'SSL');
		$data['captcha'] = $this->url->link('extension/captcha', 'token=' . $this->session->data['token'], 'SSL');
		$data['category'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL');
		$data['country'] = $this->url->link('localisation/country', 'token=' . $this->session->data['token'], 'SSL');
		$data['contact'] = $this->url->link('marketing/contact', 'token=' . $this->session->data['token'], 'SSL');
		$data['coupon'] = $this->url->link('marketing/coupon', 'token=' . $this->session->data['token'], 'SSL');
		$data['currency'] = $this->url->link('localisation/currency', 'token=' . $this->session->data['token'], 'SSL');
		$data['user'] = $this->url->link('user/user', 'token=' . $this->session->data['token'], 'SSL');
		$data['user1'] = $this->url->link('user/user', 'token=' . $this->session->data['token'], 'SSL');
		$data['user_fields'] = $this->url->link('user/user_field', 'token=' . $this->session->data['token'], 'SSL');
		$data['user_group'] = $this->url->link('user/user_group', 'token=' . $this->session->data['token'], 'SSL');
		$data['custom_field'] = $this->url->link('user/custom_field', 'token=' . $this->session->data['token'], 'SSL');
		$data['download'] = $this->url->link('catalog/download', 'token=' . $this->session->data['token'], 'SSL');
		$data['error_log'] = $this->url->link('tool/error_log', 'token=' . $this->session->data['token'], 'SSL');
		$data['export_import'] = $this->url->link('tool/export_import', 'token=' . $this->session->data['token'], 'SSL');
		$data['folder_protect'] = $this->url->link('tool/folder_protect', 'token=' . $this->session->data['token'], 'SSL');
		$data['feed'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');
		$data['filter'] = $this->url->link('catalog/filter', 'token=' . $this->session->data['token'], 'SSL');
		$data['fraud'] = $this->url->link('extension/fraud', 'token=' . $this->session->data['token'], 'SSL');
		$data['geo_zone'] = $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'], 'SSL');
		$data['extra_info'] = $this->url->link('catalog/extra_info', 'token=' . $this->session->data['token'], 'SSL');
		$data['information'] = $this->url->link('catalog/information', 'token=' . $this->session->data['token'], 'SSL');
		$data['features'] = $this->url->link('catalog/feature', 'token=' . $this->session->data['token'], 'SSL');
		$data['pricing_table'] = $this->url->link('catalog/pricing_table', 'token=' . $this->session->data['token'], 'SSL');
		$data['content_info'] = $this->url->link('catalog/content_info', 'token=' . $this->session->data['token'], 'SSL');
		$data['installer'] = $this->url->link('extension/installer', 'token=' . $this->session->data['token'], 'SSL');
		$data['language'] = $this->url->link('localisation/language', 'token=' . $this->session->data['token'], 'SSL');
		$data['language_editor'] = $this->url->link('localisation/lang_editor', 'token=' . $this->session->data['token'], 'SSL');
		$data['layout'] = $this->url->link('design/layout', 'token=' . $this->session->data['token'], 'SSL');
		$data['page'] = $this->url->link('design/page', 'token=' . $this->session->data['token'], 'SSL');
		$data['megamenu'] = $this->url->link('design/megamenu', 'token=' . $this->session->data['token'], 'SSL');
		$data['navigation'] = $this->url->link('design/navigation', 'token=' . $this->session->data['token'], 'SSL');
		$data['block'] = $this->url->link('design/block', 'token=' . $this->session->data['token'], 'SSL');
		$data['menu'] = $this->url->link('design/menu', 'token=' . $this->session->data['token'], 'SSL');
		$data['location'] = $this->url->link('localisation/location', 'token=' . $this->session->data['token'], 'SSL');
		$data['modification'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'], 'SSL');
		$data['manufacturer'] = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'], 'SSL');
		$data['marketing'] = $this->url->link('marketing/marketing', 'token=' . $this->session->data['token'], 'SSL');
		$data['module'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$data['option'] = $this->url->link('catalog/option', 'token=' . $this->session->data['token'], 'SSL');
		$data['order'] = $this->url->link('manage/order', 'token=' . $this->session->data['token'], 'SSL');
		$data['order_status'] = $this->url->link('localisation/order_status', 'token=' . $this->session->data['token'], 'SSL');
		$data['payment'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		$data['paypal_search'] = $this->url->link('payment/pp_express/search', 'token=' . $this->session->data['token'], 'SSL');
		$data['instrument'] = $this->url->link('catalog/instrument', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_manage_order'] = $this->url->link('report/manage_order', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_manage_tax'] = $this->url->link('report/manage_tax', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_manage_shipping'] = $this->url->link('report/manage_shipping', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_manage_return'] = $this->url->link('report/manage_return', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_manage_coupon'] = $this->url->link('report/manage_coupon', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_instrument_viewed'] = $this->url->link('report/instrument_viewed', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_instrument_purchased'] = $this->url->link('report/instrument_purchased', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_user_activity'] = $this->url->link('report/user_activity', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_user_online'] = $this->url->link('report/user_online', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_user_order'] = $this->url->link('report/user_order', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_user_reward'] = $this->url->link('report/user_reward', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_user_credit'] = $this->url->link('report/user_credit', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_marketing'] = $this->url->link('report/marketing', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_affiliate'] = $this->url->link('report/affiliate', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_affiliate_activity'] = $this->url->link('report/affiliate_activity', 'token=' . $this->session->data['token'], 'SSL');
		$data['review'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'], 'SSL');
		$data['return'] = $this->url->link('manage/return', 'token=' . $this->session->data['token'], 'SSL');
		$data['return_action'] = $this->url->link('localisation/return_action', 'token=' . $this->session->data['token'], 'SSL');
		$data['return_reason'] = $this->url->link('localisation/return_reason', 'token=' . $this->session->data['token'], 'SSL');
		$data['return_status'] = $this->url->link('localisation/return_status', 'token=' . $this->session->data['token'], 'SSL');
		$data['shipping'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');
		$data['setting'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');
		$data['stock_status'] = $this->url->link('localisation/stock_status', 'token=' . $this->session->data['token'], 'SSL');
		$data['tax_class'] = $this->url->link('localisation/tax_class', 'token=' . $this->session->data['token'], 'SSL');
		$data['tax_rate'] = $this->url->link('localisation/tax_rate', 'token=' . $this->session->data['token'], 'SSL');
		$data['total'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');
		$data['upload'] = $this->url->link('tool/upload', 'token=' . $this->session->data['token'], 'SSL');
		$data['staff'] = $this->url->link('staff/staff', 'token=' . $this->session->data['token'], 'SSL');
		$data['staff_group'] = $this->url->link('staff/staff_permission', 'token=' . $this->session->data['token'], 'SSL');
		$data['voucher'] = $this->url->link('manage/voucher', 'token=' . $this->session->data['token'], 'SSL');
		$data['voucher_theme'] = $this->url->link('manage/voucher_theme', 'token=' . $this->session->data['token'], 'SSL');
		$data['weight_class'] = $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'], 'SSL');
		$data['length_class'] = $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'], 'SSL');
		$data['zone'] = $this->url->link('localisation/zone', 'token=' . $this->session->data['token'], 'SSL');
		$data['recurring'] = $this->url->link('catalog/recurring', 'token=' . $this->session->data['token'], 'SSL');
		$data['order_recurring'] = $this->url->link('manage/recurring', 'token=' . $this->session->data['token'], 'SSL');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		// Run currency update
		if ($this->config->get('config_currency_auto')) {
			$this->load->model('localisation/currency');

			$this->model_localisation_currency->refresh();
		}

		$this->response->setOutput($this->load->view('common/controlpanel.tpl', $data));
	}
}