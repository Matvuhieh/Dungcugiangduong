<?php
class ControllerCommonLogin extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('common/login');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->document->addStyle('view/stylesheet/login.css');

		if ($this->staff->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->session->data['token'] = token(32);
			
			if($this->staff->isRoot() && strtolower($_SERVER['SERVER_NAME']) != 'localhost'){
				if((time() - $this->staff->getLastActivity()) > 1296000) {
					$this->response->redirect($this->url->link('common/login/resetPassword', 'token=' . $this->session->data['token'], 'SSL'));
					exit();
				}
			}else if(!$this->staff->isRoot() && strtolower($_SERVER['SERVER_NAME']) != 'localhost'){
				if((time() - $this->staff->getLastActivity()) > 5184000) {
					$this->response->redirect($this->url->link('common/login/resetPassword', 'token=' . $this->session->data['token'], 'SSL'));
					exit();
				}
			}
			
			$this->staff->setLastActivity();

			if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], HTTP_SERVER) === 0 || strpos($this->request->post['redirect'], HTTPS_SERVER) === 0 )) {
				$this->response->redirect($this->request->post['redirect'] . '&token=' . $this->session->data['token']);
			} else {
				echo $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
				$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_login'] = $this->language->get('text_login');
		$data['text_forgotten'] = $this->language->get('text_forgotten');

		$data['entry_staffname'] = $this->language->get('entry_staffname');
		$data['entry_password'] = $this->language->get('entry_password');

		$data['button_login'] = $this->language->get('button_login');

		if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
			$this->error['warning'] = $this->language->get('error_token');
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['action'] = $this->url->link('common/login', '', 'SSL');

		if (isset($this->request->post['staffname'])) {
			$data['staffname'] = $this->request->post['staffname'];
		} else {
			$data['staffname'] = '';
		}

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];

			unset($this->request->get['route']);
			unset($this->request->get['token']);

			$url = '';

			if ($this->request->get) {
				$url .= http_build_query($this->request->get);
			}

			$data['redirect'] = $this->url->link($route, $url, 'SSL');
		} else {
			$data['redirect'] = '';
		}

		if ($this->config->get('config_password')) {
			$data['forgotten'] = $this->url->link('common/forgotten', '', 'SSL');
		} else {
			$data['forgotten'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/login.tpl', $data));
	}

	protected function validate() {
		if (!isset($this->request->post['staffname']) || !isset($this->request->post['password']) || !$this->staff->login($this->request->post['staffname'], html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8'))) {
			$this->error['warning'] = $this->language->get('error_login');
		}

		return !$this->error;
	}
	
	function resetPassword() {
		if (!$this->staff->isLogged() && !isset($this->request->get['token']) && ($this->request->get['token'] != $this->session->data['token'])) {
			$this->response->redirect($this->url->link('common/login', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->load->language('common/login');
		
		$this->load->model('staff/staff');
		
		$staff = $this->model_staff_staff->getStaff($this->staff->getId());
		
		$admin_email = $this->config->get('config_email');
		
		if(!empty($staff['email']) && !empty($admin_email)) {
			$this->load->language('mail/forgotten');

			$code = sha1(uniqid(mt_rand(), true));

			$this->model_staff_staff->editCode($staff['email'], $code);

			$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

			$message  = sprintf($this->language->get('text_greeting'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')) . "\n\n";
			$message .= $this->language->get('text_change') . "\n\n";
			$message .= html_entity_decode($this->url->link('common/reset', 'code=' . $code, 'SSL')) . "\n\n";
			$message .= sprintf($this->language->get('text_ip'), $this->request->server['REMOTE_ADDR']) . "\n\n";
			$message .= "Email: ". $staff['email'] . "\n\n";

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_staffname = $this->config->get('config_mail_smtp_staffname');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo(array($staff['email'], 'central@website500k.net'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText($message);
			$mail->send();

			$this->session->data['success'] = sprintf($this->language->get('text_reset_success'), $staff['email']);
			
			$this->response->redirect($this->url->link('common/login', '', 'SSL'));
		}else{
			$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}

	public function check() {
		$route = isset($this->request->get['route']) ? $this->request->get['route'] : '';

		$ignore = array(
			'common/login',
			'common/forgotten',
			'common/reset'
		);

		if (!$this->staff->isLogged() && !in_array($route, $ignore)) {
			return new Action('common/login');
		}

		if (isset($this->request->get['route'])) {
			$ignore = array(
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission'
			);

			if (!in_array($route, $ignore) && (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token']))) {
				return new Action('common/login');
			}
		} else {
			if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
				return new Action('common/login');
			}
		}
	}
}
