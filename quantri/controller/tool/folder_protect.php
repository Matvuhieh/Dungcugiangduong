<?php
class ControllerToolFolderProtect extends Controller {
	private $error = array();

	public function index() {
		
		$this->language->load('tool/folder_protect');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = $this->language->get('text_form');
		
		$data['entry_staff'] = $this->language->get('entry_staff');
		$data['entry_password'] = $this->language->get('entry_password');
		
		$data['button_submit'] = $this->language->get('button_submit');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			$apache_version  = $this->getVersion();
			
			$staff = html_entity_decode($this->request->post['staff']);
			$password =  html_entity_decode($this->request->post['password']);
			
			if(version_compare($apache_version, '2.2.17') <= 0) {
				$encrypted_password = crypt($password, base64_encode($password));
			} else {
				$encrypted_password = $this->crypt_apr1_md5($password);
			}
			
			$htaccess = fopen(DIR_APPLICATION . '.htaccess','w+');
			$htaccess_content = "AuthType Basic \nAuthName \"Password Protected Area\" \nAuthStaffFile " . DIR_APPLICATION . "/.htpasswd \nRequire valid-staff";
			fwrite($htaccess,$htaccess_content);
			fclose($htaccess);
			
			$htpasswd = fopen(DIR_APPLICATION . '.htpasswd','w+');
			$htpasswd_content = $staff.':'.$encrypted_password;
			fwrite($htpasswd,$htpasswd_content);
			fclose($htpasswd);
			
			$this->session->data['success'] = $this->language->get('text_success');
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/folder_protect', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['staff'])) {
			$data['error_staff'] = $this->error['staff'];
		} else {
			$data['error_staff'] = '';
		}
		
		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		if (isset($this->request->post['staff'])) {
			$data['staff'] = $this->request->post['staff'];
		} else {
			$data['staff'] = '';
		}
		
		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}
		
		$data['action'] = $this->url->link('tool/folder_protect', 'token=' . $this->session->data['token'], 'SSL');
		$data['remove'] = $this->url->link('tool/folder_protect/remove', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/folder_protect.tpl', $data));
	}
	
	public function remove() {
		$this->language->load('tool/folder_protect');
		
		@unlink(DIR_APPLICATION . '.htaccess');
		@unlink(DIR_APPLICATION . '.htpasswd');
		
		$this->session->data['success'] = $this->language->get('text_success');
		
		$this->response->redirect($this->url->link('tool/folder_protect', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	
	protected function validateForm() {
		if (!$this->staff->hasPermission('modify', 'tool/folder_protect')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ((utf8_strlen($this->request->post['staff']) < 3) || (utf8_strlen($this->request->post['staff']) > 64)) {
			$this->error['staff'] = $this->language->get('error_staff');
		}
		
		if ((utf8_strlen($this->request->post['password']) < 8) || (utf8_strlen($this->request->post['password']) > 64)) {
			$this->error['password'] = $this->language->get('error_password');
		}
		
		return !$this->error;
	}
	
	private function getVersion(){
		$software_version = explode(' ', $_SERVER['SERVER_SOFTWARE']);
		
		$apache_version = explode('/', $software_version[0]);
		
		return $apache_version[1];
	}
	
	private function crypt_apr1_md5($plainpasswd){
		$salt = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);
		$len = strlen($plainpasswd);
		$text = $plainpasswd.'$apr1$'.$salt;
		$bin = pack("H32", md5($plainpasswd.$salt.$plainpasswd));
		
		for($i = $len; $i > 0; $i -= 16) { $text .= substr($bin, 0, min(16, $i)); }
		for($i = $len; $i > 0; $i >>= 1) { $text .= ($i & 1) ? chr(0) : $plainpasswd{0}; }
		$bin = pack("H32", md5($text));
		
		for($i = 0; $i < 1000; $i++){
			$new = ($i & 1) ? $plainpasswd : $bin;
			if ($i % 3) $new .= $salt;
			if ($i % 7) $new .= $plainpasswd;
			$new .= ($i & 1) ? $bin : $plainpasswd;
			$bin = pack("H32", md5($new));
		}
		
		$tmp = '';
		
		for ($i = 0; $i < 5; $i++){
			$k = $i + 6;
			$j = $i + 12;
			if ($j == 16) $j = 5;
			$tmp = $bin[$i].$bin[$k].$bin[$j].$tmp;
		}
		
		$tmp = chr(0).chr(0).$bin[11].$tmp;
		$tmp = strtr(strrev(substr(base64_encode($tmp), 2)),
		"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
		"./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
	 
		return "$"."apr1"."$".$salt."$".$tmp;
	}
}
