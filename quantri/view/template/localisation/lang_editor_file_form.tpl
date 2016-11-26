<?php $show_folder = ''; ?>
<?php $td_num = 0; ?>
<?php $folder = explode ( '/', $path ); ?>

<div class="row">
  <div class="col-sm-12" style="float: none;">
    <h3><?php echo isset($folder[1]) ? $folder[1] : $folder[0]; ?></h3>
  </div>
  <div class="files_block">
    <form method="post" action="<?php echo $action; ?>">
      <input type="hidden" name="type" value="<?php echo $type; ?>" />
      <input type="hidden" name="path" value="<?php echo $path; ?>" />
      <table class="t_lang">
        <tr class="title">
          <th></th>
          <?php $parseError = array (); ?>
          <?php foreach ($selected as $lang){ ?>
          <th><?php echo $lang; ?></th>
          <?php $parseError{$lang}=0;?>
          <?php } ?>
          <?php $parseErrorDis=0;?>
        </tr>
        <?php $input_val = getFilesByLang ( $path, $selected, $end_side, false ); ?>
        <?php $input_var = getFilesByLang ( $path, $selected, $end_side, true ); ?>
        <?php
            foreach ($input_var as $k => $v){
                foreach ($selected as $lang){
                if(isset($input_val{$lang}['parseError']))
                    $parseErrorDis=1;
                }
            }
        ?>
        <?php foreach ($input_var as $k => $v){ ?>
        <?php $td_num++; ?>
        <tr>
          <td class="tn<?php echo $td_num; ?>"><div class="remove-var"><i class="fa fa-minus-circle" aria-hidden="true"></i></div>
            <?php echo $v; ?></td>
          <?php
              $len = 0;
              foreach ( $selected as $lang ) {
                  if (isset ( $input_val {$lang} {$v} ) && !is_array($input_val {$lang} {$v}) && $len < mb_strlen ( htmlspecialchars ( $input_val {$lang} {$v} ), "UTF-8" ))
                      $len = mb_strlen ( htmlspecialchars ( $input_val {$lang} {$v} ), "UTF-8" );
              }
          ?>
          <?php $error_var = 0; ?>
          <?php foreach ($selected as $lang){ ?>
          <?php if($parseError{$lang}==0){ ?>
          <?php if(isset($input_val{$lang}['parseError'])) {?>
          <?php $parseError{$lang}=1;?>
          <td rowspan="<?php echo count($input_var);?>" style="width:1%;vertical-align:top;"><div style="padding:0 20px 0 0"><?php echo $input_val{$lang}['parseErrorText']; ?></div>
            <br />
            <?php $fileCont = file_get_contents($input_val{$lang}['parseErrorFile']); ?>
            <?php $errorLineTxt = explode('on line ', $input_val{$lang}['parseErrorText']);?>
            <?php $errorLine = trim(strip_tags ($errorLineTxt[1]))-1;?>
            <?php $getFileError = explode("\n",$fileCont); ?>
            <?php $getFileErrorLine = $getFileError[$errorLine]; ?>
            <div class="parseError">
              <textarea class="parseError"><?php echo trim($fileCont); ?></textarea>
              <a data-toggle="tooltip" data-url="<?php echo str_replace($end_side,'',$input_val{$lang}['parseErrorFile']); ?>" title="<?php echo $button_save; ?>" class="btn button button-save-lang_one" style="margin-top:10px;"><i class="fa fa-save"></i></a> </div>
            <?php if(mb_strlen($getFileErrorLine)>0){ ?>
            <script type="text/javascript">
			$(document).ready(function(){
				/* $errorLine: <?php echo $errorLine;?>*/
				/* $getFileErrorLine: <?php print_r(explode("\n",$fileCont));?>*/
				/* $getFileErrorLine: <?php echo $getFileErrorLine;?>*/
				$('.parseError').highlightTextarea({
					ranges: [{
						  color: '#FFFF00',
						  start: <?php echo mb_strpos($fileCont,$getFileErrorLine)+2?>,
						  length: <?php echo mb_strlen($getFileErrorLine)+1; ?>
					  }]
				});
			});	
			</script>
            <?php } ?></td>
          <?php }else{?>
          <td><?php if($len > 25){ ?>
            <textarea name="<?php echo 'data[' . $lang . '][' . $v . ']'; ?>" rows="<?php echo ceil($len / 25);?>"<?php if(!isset($input_val{$lang}{$v})) echo ' class="s"'; ?><?php if($parseErrorDis==1) echo ' disabled="disabled"';?>><?php echo (isset($input_val{$lang}{$v}) ? htmlspecialchars($input_val{$lang}{$v}) : '') ?></textarea>
            <?php }else{ ?>
            <input type="text" name="<?php echo 'data[' . $lang . '][' . $v . ']'; ?>" value="<?php echo (isset($input_val{$lang}{$v}) ? htmlspecialchars($input_val{$lang}{$v}) : '') ?>"<?php if(!isset($input_val{$lang}{$v})) echo ' class="s"'; ?><?php if($parseErrorDis==1) echo ' disabled="disabled"';?> />
            <?php } ?></td>
          <?php } ?>
          <?php } ?>
          <?php } ?>
        </tr>
        <?php } ?>
      </table>
      <div class="buttons-left"> <a class="button-add-var"><i class="fa fa-plus-circle"></i></a> </div>
      <div class="buttons-right">
        <?php if($parseErrorDis==1) { ?>
        <button title="" type="button" class="btn button button-save-lang" disabled="disabled" style="display: inline-block;"><i class="fa fa-save"></i></button>
        <div style="display: inline-block;float: right;padding:10px 10px 0 0;color:#BE2026;font-weight:bold;">You must first fix Parse errors:</div>
        <?php }else{?>
        <a data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn button button-save-lang"><i class="fa fa-save"></i></a>
        <?php }?>
        <div class="save_time"></div>
      </div>
    </form>
    <div class="clr"></div>
  </div>
</div>
<?php
	function syntax_check_php_file ($file) {
		@$code = file_get_contents($file);
		 
		if ($code === false) {
			throw new Exception('File '.$file.' does not exist');
		}
		 
		$braces = 0;
		$inString = 0;
		foreach ( token_get_all($code) as $token ) {
			if ( is_array($token) ) {
				switch ($token[0]) {
					case T_CURLY_OPEN:
					case T_DOLLAR_OPEN_CURLY_BRACES:
					case T_START_HEREDOC: ++$inString; break;
					case T_END_HEREDOC:	  --$inString; break;
				}
			}
			else if ($inString & 1) {
				switch ($token) {
					case '`':
					case '"': --$inString; break;
				}
			}
			else {
				switch ($token) {
					case '`':
					case '"': ++$inString; break;
	
					case '{': ++$braces; break;
					case '}':
						if ($inString) {
							--$inString;
						}
						else {
							--$braces;
							if ($braces < 0) {
								throw new Exception('Braces problem!');
							}
						}
						break;
				}
			}
		}
		 
		//if ($braces) {
		//	throw new Exception('Braces problem!');
		//}
		 
		$res = false;
		$codeByLineAr = array_filter(explode("\n",$code));
		$codeByLine = substr(trim(end($codeByLineAr)), -2);
		 
		ob_start();
	
		$res = eval('if (0) {?>' . $code . (($codeByLine=='?>') ? '<?php' : '') . ' }; return true;');
		$error_text = ob_get_clean();
		 
		header('HTTP/1.0 200 OK');
		 
		if (!$res) {
			throw new Exception($error_text);
		}
		 
		return true;
	}
	
	function getFilesByLang($file, $lang_array, $end_side, $vars) {
		// $vars get variables=true | get values=false
		ob_start ();
		$list = array ();
		foreach ( $lang_array as $lang ) {
			$parseError = false;
			if ($file == '#main_file') {
				$fileName = $end_side . $lang . '/' . $lang . '.php';
				if (file_exists ( $fileName )) {
					try{
						if (syntax_check_php_file ( $fileName )) {
							include $fileName;
						}
					} catch (Exception $e) {
						$parseError = true;
						$parseErrorFile = $fileName;
						$parseErrorText = $e->getMessage();
					}
				} else {
					$fileName = $end_side . $lang . '/default.php';
					if (file_exists ( $fileName )) {
						try{
							if (syntax_check_php_file ( $fileName )) {
								include $fileName;
							}
						} catch (Exception $e) {
							$parseError = true;
							$parseErrorFile = $fileName;
							$parseErrorText = $e->getMessage();
						}
					}
				}
			} else {
				$fileName = $end_side . $lang . '/' . $file;
				if (file_exists ( $fileName )) {
					try{
						if (syntax_check_php_file ( $fileName )) {
							include $fileName;
						}
					} catch (Exception $e) {
						$parseError = true;
						$parseErrorFile = $fileName;
						$parseErrorText = $e->getMessage();
					}
				}
			}
			$gdv = get_defined_vars ();
			if ($parseError && !$vars){
				$list {$lang} ['parseError'] = $parseError;
				$list {$lang} ['parseErrorFile'] = $parseErrorFile;
				$list {$lang} ['parseErrorText'] = $parseErrorText;
			}
			
			if (isset ( $gdv ['_'] )) {
				if (is_array ( $gdv ['_'] )) {
					foreach ( $gdv ['_'] as $k => $v ) {
						if ($vars) {
							$list [] = $k;
						} else {
							$list {$lang} {$k} = $v;
							unset ( $_ {$k} );
						}
					}
				}
			}
		}
		ob_end_clean ();
		
		if($vars)
			return array_unique ( $list );
		return $list;
	}
?>
