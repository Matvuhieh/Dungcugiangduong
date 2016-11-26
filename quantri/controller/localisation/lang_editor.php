<?php
class ControllerlocalisationLangEditor extends Controller {
	private $error = array ();
	
	public function index() {
		$this->load->language ( 'localisation/lang_editor' );
		
		$this->document->setTitle ( strip_tags ( $this->language->get ( 'heading_title' ) ) . '(' . $this->language->get ( 'heading_version' ) . ')' );
		
		$this->load->model ( 'setting/setting' );
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' ) . '(' . $this->language->get ( 'heading_version' ) . ')';
		
		$data ['text_enabled'] = $this->language->get ( 'text_enabled' );
		$data ['text_disabled'] = $this->language->get ( 'text_disabled' );
		
		$data ['entry_frontend'] = $this->language->get ( 'entry_frontend' );
		$data ['entry_backend'] = $this->language->get ( 'entry_backend' );
		$data ['entry_languages'] = $this->language->get ( 'entry_languages' );
		$data ['entry_code'] = $this->language->get ( 'entry_code' );
		$data ['entry_directory'] = $this->language->get ( 'entry_directory' );
		$data ['entry_locale'] = $this->language->get ( 'entry_locale' );
		$data ['entry_permissions'] = $this->language->get ( 'entry_permissions' );
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_remove'] = $this->language->get('button_remove');
		
		if (isset ( $this->error ['warning'] )) {
			$data ['error_warning'] = $this->error ['warning'];
		} else {
			$data ['error_warning'] = '';
		}
		
		$data ['getFrontEndCatalogPath'] = $this->getFrontEndCatalogPath ();
		
		$data ['breadcrumbs'] = array ();
		$data ['breadcrumbs'] [] = array ('text' => $this->language->get ( 'text_home' ), 'href' => $this->url->link ( 'common/home', 'token=' . $this->session->data ['token'], 'SSL' ), 'separator' => false );
		$data ['breadcrumbs'] [] = array ('text' => $this->language->get ( 'heading_title' ), 'href' => $this->url->link ( 'localisation/lang_editor', 'token=' . $this->session->data ['token'], 'SSL' ), 'separator' => ' :: ' );
		
		$data['action'] = $this->url->link('localisation/lang_editor', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('localisation/lang_editor', 'token=' . $this->session->data['token'], 'SSL');
		
		
		unset ( $this->session->data ['selected'] );
		unset ( $this->session->data ['type'] );
		if (isset ( $this->request->post ['selected'] ) && isset ( $this->request->post ['type'] )) {
			$this->session->data ['selected'] = $this->request->post ['selected'];
			$this->session->data ['type'] = $this->request->post ['type'];
			$this->response->redirect ( $this->url->link ( 'localisation/lang_editor/edit', 'token=' . $this->session->data ['token'], 'SSL' ) );
		} elseif ($this->request->server ['REQUEST_METHOD'] == 'POST') {
			$data ['error_warning'] = $this->language->get ( 'error_no_selections' );
		}
		
		$this->load->model ( 'localisation/language' );
		$data ['languages'] = $this->model_localisation_language->getLanguages ();
		
		$data ['writable_main'] = '';
		if (! is_writable ( $data ['getFrontEndCatalogPath'] . 'language' ))
			$data ['writable_main'] .= '<li>' . $this->language->get ( 'error_unwritable' ) . ' - ' . $data ['getFrontEndCatalogPath'] . 'language</li>';
		else
			$data ['writable_main'] .= '';
		if (! is_writable ( DIR_APPLICATION . 'language' ))
			$data ['writable_main'] .= '<li>' . $this->language->get ( 'error_unwritable' ) . ' - ' . DIR_APPLICATION . 'language</li>';
		else
			$data ['writable_main'] .= '';

		foreach ( $data ['languages'] as $v ) {
			if (! is_dir ( $data ['getFrontEndCatalogPath'] . 'language/' . $v ['directory'] ))
				mkdir ( $data ['getFrontEndCatalogPath'] . 'language/' . $v ['directory'], 0755 );
			if ($this->checkPermission ( $data ['getFrontEndCatalogPath'] . 'language/' . $v ['directory'] ) == 'error_unwritable') {
				$data ['writable'] [$v ['code']] ['front'] = '<span class="error">' . $this->language->get ( 'entry_frontend' ) . ': ' . $this->language->get ( 'error_unwritable' ) . '</span>';
			} else {
				$data ['writable'] [$v ['code']] ['front'] = '<span class="error" style="color:#777">' . $this->language->get ( 'entry_frontend' ) . ': ' . $this->language->get ( 'error_writable' ) . '</span>';
			}
			
			if (! is_dir ( DIR_APPLICATION . 'language/' . $v ['directory'] ))
				mkdir ( DIR_APPLICATION . 'language/' . $v ['directory'], 0755 );
			if ($this->checkPermission ( DIR_APPLICATION . 'language/' . $v ['directory'] ) == 'error_unwritable') {
				$data ['writable'] [$v ['code']] ['back'] = '<span class="error">' . $this->language->get ( 'entry_backend' ) . ': ' . $this->language->get ( 'error_unwritable' ) . '</span>';
			} else {
				$data ['writable'] [$v ['code']] ['back'] = '<span class="error" style="color:#777">' . $this->language->get ( 'entry_backend' ) . ': ' . $this->language->get ( 'error_writable' ) . '</span>';
			}
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('localisation/lang_editor_list.tpl', $data));
	}
	
	public function edit() {
		
		if (! isset ( $this->session->data ['selected'] ))
			$this->response->redirect ( $this->url->link ( 'localisation/lang_editor', 'token=' . $this->session->data ['token'], 'SSL' ) );
		
		$this->load->language ( 'localisation/lang_editor' );
		$this->load->model ( 'setting/setting' );
		
		$this->document->setTitle ( strip_tags ( $this->language->get ( 'heading_title' ) ) . '(' . $this->language->get ( 'heading_version' ) . ')' );
		$this->document->addScript('view/javascript/jquery/jquery.fixer.js');
		$this->document->addStyle('view/stylesheet/lang-editor.css');
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' ) . '(' . $this->language->get ( 'heading_version' ) . ')';
		
		$data ['text_enabled'] = $this->language->get ( 'text_enabled' );
		$data ['text_disabled'] = $this->language->get ( 'text_disabled' );
		
		$data ['entry_frontend'] = $this->language->get ( 'entry_frontend' );
		$data ['entry_backend'] = $this->language->get ( 'entry_backend' );
		$data ['entry_languages'] = $this->language->get ( 'entry_languages' );
		$data ['entry_code'] = $this->language->get ( 'entry_code' );
		$data ['entry_directory'] = $this->language->get ( 'entry_directory' );
		$data ['entry_locale'] = $this->language->get ( 'entry_locale' );
		$data ['entry_permissions'] = $this->language->get ( 'entry_permissions' );
		$data ['entry_showhide'] = $this->language->get ( 'entry_showhide' );
		$data ['entry_showhideall'] = $this->language->get ( 'entry_showhideall' );
		$data ['entry_saving'] = $this->language->get ( 'entry_saving' );
		$data ['entry_saved'] = $this->language->get ( 'entry_saved' );
		$data ['entry_search'] = $this->language->get ( 'entry_search' );
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_remove'] = $this->language->get('button_remove');
		$data ['redirect'] = $this->url->link ( 'localisation/lang_editor/edit', 'token=' . $this->session->data ['token'], 'SSL' );
		$data ['redirectAmp'] = $this->url->link ( 'localisation/lang_editor/edit&token=' . $this->session->data ['token'], '', 'SSL' );
		
		if (isset ( $this->error ['warning'] )) {
			$data ['error_warning'] = $this->error ['warning'];
		} else {
			$data ['error_warning'] = '';
		}
		
		$this->request->post ['selected'] = $this->session->data ['selected'];
		$this->request->post ['type'] = $this->session->data ['type'];
		if (isset($this->request->post['selected'])) {
			$data['selected'] = $this->request->post['selected'];
		}
		if (isset($this->request->post['type'])) {
			$data['type'] = $this->request->post['type'];
		}
		
		$data ['getFrontEndCatalogPath'] = $this->getFrontEndCatalogPath ();
		
		$data ['breadcrumbs'] = array ();
		$data ['breadcrumbs'] [] = array ('text' => $this->language->get ( 'text_home' ), 'href' => $this->url->link ( 'common/home', 'token=' . $this->session->data ['token'], 'SSL' ), 'separator' => false );
		$data ['breadcrumbs'] [] = array ('text' => $this->language->get ( 'heading_title' ), 'href' => $this->url->link ( 'localisation/lang_editor', 'token=' . $this->session->data ['token'], 'SSL' ), 'separator' => ' :: ' );
		$data ['breadcrumbs'] [] = array ('text' => $this->language->get ( 'text_edit' ), 'href' => $this->url->link ( 'localisation/lang_editor', 'token=' . $this->session->data ['token'], 'SSL' ), 'separator' => ' :: ' );
		
		$data ['action'] = $this->url->link ( 'localisation/lang_editor/save', 'token=' . $this->session->data ['token'], 'SSL' );
		$data ['search'] = html_entity_decode($this->url->link ( 'localisation/lang_editor/search', 'token=' . $this->session->data ['token'], 'SSL' ));
		$data ['getForm'] = html_entity_decode($this->url->link ( 'localisation/lang_editor/getForm', 'token=' . $this->session->data ['token'], 'SSL' ));
		
		$data ['actionOneFile'] = $this->url->link ( 'localisation/lang_editor/saveOneFile/&token=' . $this->session->data ['token'], '', 'SSL' );
		
		$data['cancel'] = $this->url->link('localisation/lang_editor', 'token=' . $this->session->data['token'], 'SSL');
		
		if ($this->request->post ['type'] == 'front') {
			$data ['entry_title'] = $this->language->get ( 'entry_frontend' );
			$data ['end_side'] = $data ['getFrontEndCatalogPath'] . 'language/';
		} elseif ($this->request->post ['type'] == 'back') {
			$data ['entry_title'] = $this->language->get ( 'entry_backend' );
			$data ['end_side'] = DIR_APPLICATION . 'language/';
		}
		
		foreach ( $this->request->post ['selected'] as $v ) {
			if (! is_dir ( $data ['end_side'] . $v ))
				mkdir ( $data ['end_side'] . $v, 0755 );
			$files_end [] = $this->getStructure ( $data ['end_side'] . $v );
		}
		
		$data ['paths_array'] = $this->getUniquePath ( $files_end );
		
		foreach ( $this->request->post ['selected'] as $v ) {
			foreach ( $data ['paths_array'] as $end_value ) {
				$end_file = $data ['end_side'] . $v . '/' . $end_value;
				$end_file_folder = preg_replace ( '/\/\w+\.php/', '', trim ( $end_file ) );
				if (! file_exists ( $end_file_folder ))
					mkdir ( $end_file_folder, 0755 );
			}
		}
		
		$data['tree_path'] = $files_end[0];
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('localisation/lang_editor_form.tpl', $data));
	}
	
	public function search() {
		if ($this->session->data ['type'] == 'front') {
			$data ['end_side'] = $this->getFrontEndCatalogPath() . 'language/';
		} elseif ($this->session->data ['type'] == 'back') {
			$data ['end_side'] = DIR_APPLICATION . 'language/';
		}
		
		$files_end = array();
		
		foreach ($this->session->data ['selected'] as $directory ) {
			if (! is_dir ( $data ['end_side'] . $directory )) mkdir ( $data ['end_side'] . $directory, 0755 );
			$files_end = array_merge($files_end, glob($data ['end_side'] . $directory.'/*/*'));
		}
		
		$files = array();
		
		$keyword = mb_strtolower($this->request->post['keyword'], 'UTF-8');
		
		if(!$keyword) exit();
		
		foreach ($files_end as $file_path) {
			$basename = pathinfo($file_path, PATHINFO_BASENAME);
			$extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
			
			if($basename != '..' && $basename != '.' && $extension == 'php'){
				if(file_exists($file_path) && (int)filesize($file_path) != 0){
					
					$file = fopen($file_path, 'r') or die('Need permissions to read/write file: '.$file_path);
					$content = mb_strtolower(fread($file, filesize($file_path)), 'UTF-8');
					fclose($file);
					
					if (strpos($content, $this->request->post['keyword'])) {
						echo '<li><a class="get-language-form" ref="'.basename(dirname($file_path)).'/'.$basename.'">'.basename($basename , '.php').'</a></li>';
					}
				}
			}
		}
	}
	
	public function save() {
		$this->load->language ( 'localisation/lang_editor' );
		$this->load->model ( 'setting/setting' );
		
		$data ['getFrontEndCatalogPath'] = $this->getFrontEndCatalogPath ();
		
		if ($this->request->post ['type'] == 'front') {
			$data ['end_side'] = $data ['getFrontEndCatalogPath'] . 'language/';
		} elseif ($this->request->post ['type'] == 'back') {
			$data ['end_side'] = DIR_APPLICATION . 'language/';
		}
		
		$msg = 0;
		
		foreach ( $this->request->post ['data'] as $k => $v ) {
			if ($this->request->post ['path'] == '#main_file') {
				$save_file_path = $data ['end_side'] . $k . '/' . $k . '.php';
			} else {
				$save_file_path = $data ['end_side'] . $k . '/' . $this->request->post ['path'];
			}
			if (! file_exists ( $save_file_path ))
				file_put_contents ( $save_file_path, '' );
			
			$text = '<?php' . "\n";
			foreach ( $this->request->post ['data'] {$k} as $lang_var => $lang_value ) {
				if ($lang_value)
					$text .= '$_[\'' . $lang_var . '\']' . "\t" . '=' . "\t" . '\'' . htmlspecialchars_decode ( str_replace ( "'", "\'", str_replace ( "\r\n", " ", $lang_value ) ) ) . '\';' . "\n";
			}
			if (! file_put_contents ( $save_file_path, $text ))
				$msg ++;
		}
		if ($msg == 0)
			echo 'ok';
		else
			echo 'error';
	
	}
	
	public function saveOneFile() {
		$this->load->language ( 'localisation/lang_editor' );
		$this->load->model ( 'setting/setting' );
	
		$data ['getFrontEndCatalogPath'] = $this->getFrontEndCatalogPath ();
	
		if ($this->request->post ['type'] == 'front') {
			$data ['end_side'] = $data ['getFrontEndCatalogPath'] . 'language/';
		} elseif ($this->request->post ['type'] == 'back') {
			$data ['end_side'] = DIR_APPLICATION . 'language/';
		}
	
		$save_file_path = $data ['end_side'] . $this->request->post ['file'];
		if (! file_exists ( $save_file_path ))
			file_put_contents ( $save_file_path, '' );
		if (file_put_contents ( $save_file_path, htmlspecialchars_decode($this->request->post ['data']) ))
			echo 'ok';
		else
			echo 'error';
	
	}
	
	public function getForm(){
		$this->load->language ( 'localisation/lang_editor' );
		$this->load->model ( 'setting/setting' );
		
		$data['button_save'] = $this->language->get('button_save');
		
		$data['type'] = $this->session->data ['type'];
		$data['selected'] = $this->session->data ['selected'];
		
		$data ['getFrontEndCatalogPath'] = $this->getFrontEndCatalogPath ();
		
		if ($data['type'] == 'front') {
			$data ['end_side'] = $data ['getFrontEndCatalogPath'] . 'language/';
		} elseif ($data['type'] == 'back') {
			$data ['end_side'] = DIR_APPLICATION . 'language/';
		}
		
		$data ['path'] = $this->request->post['path'];
		
		$data ['action'] = $this->url->link ( 'localisation/lang_editor/save', 'token=' . $this->session->data ['token'], 'SSL' );
		
		$this->response->setOutput($this->load->view('localisation/lang_editor_file_form.tpl', $data));
	}
	
	private function getStructure($dir) {
		$listDir = array ();
		if ($handler = opendir ( $dir )) {
			while ( ($sub = readdir ( $handler )) !== FALSE ) {
				if ($sub != "." && $sub != ".." && $sub != "Thumb.db" && $sub != "Thumbs.db") {
					if (is_file ( $dir . "/" . $sub )) {
						$path_info = pathinfo ( $sub );
						if ($path_info ['extension'] == 'php')
							$listDir [] = $sub;
					} elseif (is_dir ( $dir . "/" . $sub )) {
						$listDir [$sub] = $this->getStructure ( $dir . "/" . $sub );
					}
				}
			}
			closedir ( $handler );
		}
		return $listDir;
	}
	
	private function getUniquePath($arr) {
		$path_arr = array();
		foreach ( $arr as $k_folders => $folders ) {
			foreach ( $folders as $k_files => $files ) {
				if (is_array ( $files ))
					foreach ( $files as $file ) {
						if (! is_array ( $file ))
							$path_arr [] = $k_files . '/' . $file;
					}
			}
		}
		$path_arr = array_unique ( $path_arr );
		sort ( $path_arr );
		return $path_arr;
	}
	
	private function checkPermission($dir) {
		if (! is_writable ( $dir ))
			return 'error_unwritable';
		
		if ($handler = opendir ( $dir )) {
			while ( ($sub = readdir ( $handler )) !== FALSE ) {
				if ($sub != "." && $sub != ".." && $sub != "Thumb.db" && $sub != "Thumbs.db") {
					if (is_file ( $dir . "/" . $sub )) {
						if (! is_writable ( $dir . "/" . $sub ))
							return 'error_unwritable';
					} elseif (is_dir ( $dir . "/" . $sub )) {
						$dir2 = $dir . "/" . $sub;
						if (! is_writable ( $dir2 ))
							return 'error_unwritable';
						
						if ($handler2 = opendir ( $dir2 )) {
							while ( ($sub2 = readdir ( $handler2 )) !== FALSE ) {
								if ($sub2 != "." && $sub2 != ".." && $sub2 != "Thumb.db" && $sub2 != "Thumbs.db") {
									if (! is_writable ( $dir2 . "/" . $sub2 ))
										return 'error_unwritable';
								}
							}
							closedir ( $handler2 );
						}
						$this->checkPermission ( $dir . "/" . $sub );
					}
				}
			}
			closedir ( $handler );
		}
	}
	
	private function getFrontEndCatalogPath() {
		$file = file ( '../config.php' );
		foreach ( $file as $v ) {
			if (strstr ( $v, 'DIR_APPLICATION' )) {
				$path = explode ( "'", $v );
			}
		}
		return $path [3];
	}
	
	private function isMultiArray($a) {
		foreach ( $a as $v ) {
			if (is_array ( $v ))
				return true;
		}
		return false;
	}
	
	public function showFiles($arr) {
		if ($this->isMultiArray ( $arr ))
			ksort ( $arr );
		else
			sort ( $arr );
		foreach ( $arr as $k => $v ) {
			if (is_array ( $v )) {
				ksort ( $v );
				echo '<div style="border:1px solid #ccc;margin:20px;"><h3>' . $k . '</h3>';
				echo '' . $this->showFiles ( $v ) . '</div>';
			} else {
				echo '<div>' . $v . '</div>';
			}
		}
	}
	
}