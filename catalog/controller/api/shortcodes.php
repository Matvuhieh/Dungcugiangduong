<?php
class ControllerApiShortCodes extends Controller {
	public function doShortCodes(){
		if(isset($this->request->post['shortcode'])){
			$content = $this->request->post['shortcode'];
			
			$pattern = $this->shortcodes->getShortCodeRegex();
			
			preg_match_all('/'.$pattern.'/s', $content, $match, PREG_PATTERN_ORDER);
			
			if($match) {
				if($this->shortcodes->ShortCodeExists($match[2][0])){
					$extension = $this->shortcodes->getShortCode($match[2][0]);
					$info = $extension->info();
					
					if(isset($info['return_html']) && $info['return_html'] == true){
						$attrs = $this->shortcodes->ShortCodeParseAtts($match[3][0]);
						
						$loaders = array(
							'common/header_content',
							'common/footer_content',
							'instrument/category_content',
							'instrument/instrument_content'
						);
						
						if (!empty($attrs['route']) && in_array($attrs['route'], $loaders)) {
							$loader = $attrs['route'];
							$loader_file = DIR_APPLICATION . 'controller/' . $loader . '.php';
							
							$original = str_replace('_content', '', $attrs['route']);
							$original_file = DIR_APPLICATION . 'controller/' . $original . '.php';
							
							if(filemtime($original_file) > filemtime($loader_file)) {
								include_once($original_file);
								
								$lines = file($original_file);
								
								$original_class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $original);
								
								$reflector = new ReflectionClass($original_class);
								
								$publish_method = $reflector->getMethod('index');
								
								$start_line = $publish_method->getStartLine();
								$end_line = $publish_method->getEndLine();
								
								$code = '';
								
								for ($i = $start_line; $i < $end_line; $i++) {
									if (strpos($lines[$i], '$data[\'_this\'] = $this;')) break;
									
									$code .= $lines[$i];
								}
								
								if($original == 'instrument/category'){
									$code .= '}';
									$code = str_replace('\'filter_category_id\' => $category_id,', '', $code);
								}
								
								$loader_code = file_get_contents($loader_file);
								
								$loader_code = preg_replace('/\/\/ Start Content(.*)\/\/ End Content/s', "// Start Content\n" . $code . "\n// End Content", $loader_code);
								
								file_put_contents($loader_file, $loader_code);
							}
						}
						
						$content = $this->shortcodes->doShortCode($content);
						
						if(isset($attrs['style'])) {
							$this->load->model('design/block');
							
							$block = $this->model_design_block->getBlockByCode($attrs['style']);
							
							$content = '<style type="text/css">' . $block['css'] . '</style>' . $content;
						}
					}
				}
			}
			
			foreach($this->document->getStyles() as $style){
				if ($this->request->server['HTTPS']) {
					$content .= '<link href="' . $this->config->get('config_ssl') . $style['href'] . '" type="text/css" rel="' . $style['rel'] . '" media="' . $style['media'] . '" />';
				}else{
					$content .= '<link href="' . $this->config->get('config_url') . $style['href'] . '" type="text/css" rel="' . $style['rel'] . '" media="' . $style['media'] . '" />';
				}
			}
			
			$scripts = $this->document->getScripts();
			
			foreach($scripts as $key => $script){
				if ($this->request->server['HTTPS']) {
					$content = '<script src="' . $this->config->get('config_ssl') . $script . '" type="text/javascript"></script>' . $content;
				}else{
					$content = '<script src="' . $this->config->get('config_url') . $script . '" type="text/javascript"></script>' . $content;
				}
			}
				
			echo preg_replace('#\.load\((.*?)\);#i', '', preg_replace('#\$\.ajax\(\{(.*?)\}\);#is', '', html_entity_decode($content)));
		}
	}
}
