<?php
class ShortCodes {
	private $ShortCodeMethods = array();
	private $ShortCodeClass = array();
	private $authentication = false;
	
	public function authentication(){
		return $this->authentication;
	}
	
	public function addShortCode($tag, $method, $class) {
		if(method_exists($class,$method)){
			$this->ShortCodeMethods[$tag] = $method;
			$this->ShortCodeClass[$tag] = $class;
		}
	}
	
	public function getShortCode($tag){
		return  $this->ShortCodeClass[$tag];
	}
	
	public function removeShortCode($tag) {
		unset($this->ShortCodeMethods[$tag]);
		unset($this->ShortCodeClass[$tag]);
	}
	
	public function removeAllShortCodes() {
		$this->ShortCodeMethods = array();
		$this->ShortCodeClass = array();
	}
	
	public function ShortCodeExists($tag) {
		return array_key_exists($tag, $this->ShortCodeMethods);
	}
	
	public function hasShortCode($content) {
		preg_match_all( '/' . $this->getShortCodeRegex() . '/s', $content, $matches, PREG_SET_ORDER );
		if ( empty( $matches ) ) {
			return false;
		}
		
		foreach ( $matches as $ShortCode ) {
			if ( $this->ShortCodeExists($ShortCode[2]) ) {
				return true;
			}
		}
		
		return false;
	}
	
	public function doShortCode($content) {
		if (strpos( $content, '[' ) === false ) {
			return $content;
		}
		
		if (empty($this->ShortCodeMethods) || !is_array($this->ShortCodeMethods)) {
			return $content;
		}
		
		$pattern = $this->getShortCodeRegex();
		
		while ($this->hasShortCode($content)){
			$content = preg_replace_callback('/' . $pattern . '/s', 'ShortCodes::doShortCodeTag', $content);
		}
		
		return $content;
	}
	
	public function getShortCodeRegex() {
		$tagnames = array_keys($this->ShortCodeMethods);
		$tagregexp = join( '|', array_map('preg_quote', $tagnames) );

		// WARNING! Do not change this regex!
		return
		   '\\['								// Opening bracket
		 . '(\\[?)'							// 1: Optional second opening bracket for escaping ShortCodes: [[tag]]
		 . "($tagregexp)"					 // 2: ShortCode name
		 . '(?![\\w-])'						// Not followed by word character or hyphen
		 . '('								// 3: Unroll the loop: Inside the opening ShortCode tag
		 .	 '[^\\]\\/]*'					// Not a closing bracket or forward slash
		 .	 '(?:'
		 .		 '\\/(?!\\])'				// A forward slash not followed by a closing bracket
		 .		 '[^\\]\\/]*'				// Not a closing bracket or forward slash
		 .	 ')*?'
		 . ')'
		 . '(?:'
		 .	 '(\\/)'						// 4: Self closing tag ...
		 .	 '\\]'							// ... and closing bracket
		 . '|'
		 .	 '\\]'							// Closing bracket
		 .	 '(?:'
		 .		 '('						// 5: Unroll the loop: Optionally, anything between the opening and closing ShortCode tags
		 .			 '[^\\[]*+'			 // Not an opening bracket
		 .			 '(?:'
		 .				 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing ShortCode tag
		 .				 '[^\\[]*+'		 // Not an opening bracket
		 .			 ')*+'
		 .		 ')'
		 .		 '\\[\\/\\2\\]'			 // Closing ShortCode tag
		 .	 ')?'
		 . ')'
		 . '(\\]?)';							// 6: Optional second closing brocket for escaping ShortCodes: [[tag]]
	}
	
	private function doShortCodeTag($m) {
		if ( $m[1] == '[' && $m[6] == ']' ) {
			return substr($m[0], 1, -1);
		}

		$tag = $m[2];
		$attr = $this->ShortCodeParseAtts( $m[3] );
		
		if ( isset( $m[5] ) ) {
			// enclosing tag - extra parameter
			return $m[1] . call_user_func( array( $this->ShortCodeClass[$tag] ,$this->ShortCodeMethods[$tag]), $attr, $m[5], $tag ) . $m[6];
		} else {
			// self-closing tag
			return $m[1] . call_user_func( array( $this->ShortCodeClass[$tag] ,$this->ShortCodeMethods[$tag]), $attr, null,	$tag ) . $m[6];
		}
	}
	
	public function ShortCodeParseAtts($text) {
		$atts = array();
		$pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
		$text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
		if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {
			foreach ($match as $m) {
				if (!empty($m[1])) {
					$atts[strtolower($m[1])] = stripcslashes($m[2]);
				} elseif (!empty($m[3])) {
					$atts[strtolower($m[3])] = stripcslashes($m[4]);
				} elseif (!empty($m[5])) {
					$atts[strtolower($m[5])] = stripcslashes($m[6]);
				} elseif (isset($m[7]) and strlen($m[7])) {
					$atts[] = stripcslashes($m[7]);
				} elseif (isset($m[8])) {
					$atts[] = stripcslashes($m[8]);
				}
			}
		} else {
			$atts = ltrim($text);
		}
		
		return $atts;
	}
	
	public function ShortCodeAtts($pairs, $atts, $ShortCode = '') {
		$atts = (array)$atts;
		$out = array();
		foreach($pairs as $name => $default) {
			if ( array_key_exists($name, $atts) ) {
				$out[$name] = $atts[$name];
			} else {
				$out[$name] = $default;
			}
		}

		return $out;
	}
	
	public function stripShortCodes($content) {
		if (empty($this->ShortCodeMethods) || !is_array($this->ShortCodeMethods)) {
			return $content;
		}
		
		if (is_array($content)) {
			foreach ($content as $key => $value) {
				$content[$this->stripShortCodes($key)] = $this->stripShortCodes($value);
			}
		}

		$pattern = $this->getShortCodeRegex();

		return preg_replace_callback('/' . $pattern . '/s', 'ShortCodes::stripShortCodeTag', $content);
	}
	
	public function stripShortCodeTag($m) {
		if ( $m[1] == '[' && $m[6] == ']' ) {
			return substr($m[0], 1, -1);
		}

		return $m[1] . $m[6];
	}
	
	public function parseInfo($info){
		if(isset($info['title']) && !empty($info['title']) && isset($info['code']) && !empty($info['code']) && $info['editor']){
			if(!isset($info['description'])){
				$info['description'] = '';
			}
			
			if(!isset($info['icon'])){
				$info['icon'] = '';
			}
			
			if(!isset($info['param'])){
				$info['param'] = array();
			}
			$script = 'var elm, range, w_select;';
			$script .= 'setTimeout(function(){';
			
			if(empty($info['icon'])){
				$script .= '$(".note-toolbar .note-insert").append(\'';
				$script .= '<button type="button" id="shortcode-'.$info['code'].'" class="btn btn-default btn-sm btn-small" title="'.$info['title'].'">';
				$script .= '<i class="fa fa-file-image-o"></i>';
				$script .= '</button>';
				$script .= '\');';
			}else{
				if(strpos($info['icon'], '</i>') !== false){
					$script .= '$(".note-toolbar .note-insert").append(\'';
					$script .= '<button type="button" id="shortcode-'.$info['code'].'" class="btn btn-default btn-sm btn-small" title="'.$info['title'].'">'.$info['icon'].'</button>';
					$script .= '\');';
				}else{
					$script .= '$(".note-toolbar .note-insert").append(\'';
					$script .= '<button type="button" id="shortcode-'.$info['code'].'" class="btn btn-default btn-sm btn-small" title="'.$info['title'].'"><img src="'.$info['icon'].'" height="11" /></button>';
					$script .= '<img src="'.$info['icon'].'" height="11" />';
					$script .= '</button>';
					$script .= '\');';
				}
			}
			
			if(!empty($info['param'])){
				$script .= '$(".note-editor .note-dialog").append(\'';
				$script .= '<div class="shortcode-'.$info['code'].' modal">';
					$script .= '<div class="modal-dialog">';
						$script .= '<div class="modal-content">';
							$script .= '<div class="modal-header">';
								$script .= '<button type="button" class="close">×</button>';
								$script .= '<h4 class="modal-title">'.$info['title'].'</h4>';
								if(isset($info['description'])) $script .= '<small class="text-muted">'.$info['description'].'</small>';
							$script .= '</div>';
							$script .= '<form class="note-modal-form shortcode-'.$info['code'].'-form">';
								$script .= '<div class="modal-body">';
									$script .= '<div class="row-fluid">';
										foreach($info['param'] as $key => $value){
											$script .= '<div class="form-group">';
												$script .= '<label>'.$info['param'][$key]['title'].'</label>';
												
												if(isset($info['param'][$key]['description'])){
													$script .= ' (<small class="text-muted">'.$info['param'][$key]['description'].'</small>)';
												}
												
												if(!isset($info['param'][$key]['type']) || empty($info['param'][$key]['type'])){
													$info['param'][$key]['type'] = 'text';
												}
												
												if($info['param'][$key]['type'] == 'text'){
													
													$script .= '<input type="text" name="'.$key.'" class="form-control" />';
													
												}else if($info['param'][$key]['type'] == 'number'){
													
													$script .= '<input type="number" name="'.$key.'" class="form-control" />';
													
												}else if($info['param'][$key]['type'] == 'textarea'){
													
													$script .= '<textarea name="'.$key.'" class="form-control" /></textarea>';
													
												}else if($info['param'][$key]['type'] == 'select'){
													
													$script .= '<select name="'.$key.'" class="form-control" >';
													if(isset($info['param'][$key]['content'])) $script .= $info['param'][$key]['content'];
													$script .= '</select>';
													
												}
											$script .= '</div>';
										}
									$script .= '</div>';
								$script .= '</div>';
								$script .= '<div class="modal-footer">';
									$script .= '<button href="#" class="btn btn-primary shortcode-'.$info['code'].'-btn">Insert</button>';
								$script .= '</div>';
							$script .= '</form>';
						$script .= '</div>';
					$script .= '</div>';
				$script .= '</div>';
				$script .= '\');';
			}
			
			$script .= '$(document).delegate("#shortcode-'.$info['code'].'", "click", function() {';
				$script .= '$(".shortcode-'.$info['code'].'").css({"display":"block", "background":"rgba(0,0,0,0.4)"});';
				$script .= '$(this).parents(".note-editor").find(".note-editable").focus();';
				$script .= 'if (window.getSelection) {';
					$script .= 'elm = window.getSelection();';
					$script .= 'range = elm.getRangeAt(0);';
					$script .= 'w_select = true;';
				$script .= '}else{';
					$script .= 'elm = document.selection.createRange();';
					$script .= 'w_select = false;';
				$script .= '}';
			$script .= '});';
			
			$script .= '$(document).delegate(".close", "click", function() {';
				$script .= '$(this).parents(".shortcode-'.$info['code'].'").css("display","none");';
			$script .= '});';
			
			$script .= '$(document).delegate(".shortcode-'.$info['code'].'-btn", "click", function(event) {';
				$script .= '$(this).parents(".shortcode-'.$info['code'].'").css("display","none");';
			$script .= '});';
			
			$script .= '$(".shortcode-'.$info['code'].'-form").submit(function(event) {';
				$script .= 'var _this = $(this);';
				$script .= 'event.preventDefault();';
				$script .= 'var values = {};';
				$script .= '$.each($(this).serializeArray(), function(i, field) {values[field.name] = field.value;});';
				$script .= 'var shortcode = \'['.$info['code'].' \';';
				$script .= 'for(i in values){';
					$script .= 'shortcode += i+\'=\'+values[i]+\' \';';
				$script .= '}';
				$script .= 'shortcode += \'/]\';';
				$script .= '$.ajax({';
					$script .= 'url: \''.HTTPS_CATALOG.'index.php?route=api/shortcodes/doShortCodes\',';
					$script .= 'type: \'post\',';
					$script .= 'dataType: \'text\',';
					$script .= 'data: {';
						$script .= 'shortcode: shortcode';
					$script .= '},';
					$script .= 'success: function(text) {';
						$script .= 'insertShortCodes(text, elm, range, w_select);';
						$script .= '_this.parents(".shortcode-'.$info['code'].'").css("display","none");';
					$script .= '},';
					$script .= 'error: function(xhr, ajaxOptions, thrownError) {';
						$script .= 'alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);';
						$script .= '_this.parents(".shortcode-'.$info['code'].'").css("display","none");';
					$script .= '}';
				$script .= '});';
			$script .= '});';
			
			$script .= '}, 300);';
			
			return $script;
		}else{
			return '';
		}
	}
	
	public function __construct($authentication = '') {
		$this->authentication = true;
	}
}