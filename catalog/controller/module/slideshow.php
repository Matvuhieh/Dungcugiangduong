<?php
class ControllerModuleSlideshow extends Controller {
	public function index($setting) {
		static $module = 0;

		$this->load->model('design/banner');
		$this->load->model('tool/image');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/slideshow.tpl')) {
			$data['path'] = 'catalog/view/theme/' . $this->config->get('config_template') . '/template/module/slideshow/';
		}else{
			$data['path'] = 'catalog/view/theme/default/template/module/slideshow/';
		}
		
		
		$this->document->addStyle($data['path'].'css/camera.css');
		$this->document->addScript($data['path'].'scripts/jquery.mobile.customized.min.js');
		$this->document->addScript($data['path'].'scripts/jquery.easing.1.3.js');
		$this->document->addScript($data['path'].'scripts/camera.js');
		
		$data['width'] = $setting['width'];
		$data['height'] = $setting['height'];
		
		$data['banners'] = array();
		foreach ($setting['banners'] as $banner) {
			if (is_file(DIR_IMAGE . $banner['image'])) {
				$data['banners'][] = array(
					'image' => $this->model_tool_image->resize($banner['image'], $setting['width'], $setting['height']),
					'thumb' => $this->model_tool_image->resize($banner['image'], 200, 100),
					'video' => $this->getEmbedVideo($banner['video']),
					'link'  => $banner['link'],
					'html'  => html_entity_decode($banner['html'][$this->config->get('config_language_id')])
				);
			}
		}
		
		$data['module'] = $module++;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/slideshow.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/slideshow.tpl', $data);
		} else {
			return $this->load->view('default/template/module/slideshow.tpl', $data);
		}
	}
	
	private function getEmbedVideo($link){
		if($link){
			if(strpos($link, 'youtube.com') !== false || strpos($link, 'youtu.be') !== false){
				preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $link, $matches);
				
				$html = '<iframe src="https://www.youtube.com/embed/'.$matches[1].'" width="100%" height="100%" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
				
				return $html;
			}
		}else{
			return '';
		}
	}
}
