<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');
		
		//ShortCodes
		
		$extensions = array_diff(scandir(DIR_SYSTEM."extension/"),array('.','..'));
		
		$data['shortcodes_script'] = '<script type="text/javascript">';
		
		foreach($extensions as $extension){
			include_once(DIR_SYSTEM."extension/".$extension);
			
			$class = str_replace(".php","",$extension);
			${$class} = new $class($this->registry);
			
			$data['shortcodes_script'] .= $this->shortcodes->parseInfo(${$class}->info());
		}
		
		$data['shortcodes_script'] .= '</script>';

		$data['text_footer'] = $this->language->get('text_footer');
		
		return $this->load->view('common/footer.tpl', $data);
	}
}
