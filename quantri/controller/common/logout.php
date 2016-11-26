<?php
class ControllerCommonLogout extends Controller {
	public function index() {
		$this->staff->logout();

		unset($this->session->data['token']);

		$this->response->redirect($this->url->link('common/login', '', 'SSL'));
	}
}