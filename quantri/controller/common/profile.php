<?php
class ControllerCommonProfile extends Controller {
	public function index() {
		$this->load->language('common/menu');

		$this->load->model('staff/staff');

		$this->load->model('tool/image');

		$staff_info = $this->model_staff_staff->getStaff($this->staff->getId());

		if ($staff_info) {
			$data['firstname'] = $staff_info['firstname'];
			$data['lastname'] = $staff_info['lastname'];
			$data['staffname'] = $staff_info['staffname'];

			$data['staff_group'] = $staff_info['staff_group'] ;

			if (is_file(DIR_IMAGE . $staff_info['image'])) {
				$data['image'] = $this->model_tool_image->resize($staff_info['image'], 45, 45);
			} else {
				$data['image'] = '';
			}
		} else {
			$data['staffname'] = '';
			$data['image'] = '';
		}

		return $this->load->view('common/profile.tpl', $data);
	}
}
