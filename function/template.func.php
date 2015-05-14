<?php

class bstemp{
	var $pre_cache = 't_';
	var $initial_sec = "<?php if(define('common', true)) exit('Access Denine');?>";
	var $permission = 0777;
	function cache($tempname, $brtrim, $ftype='.htm', $dirr='/'){
		global $font;
		$imgcom = $font.'images/common/';
		$pre_cache = $this->pre_cache;
		$fname = $dirr.basename($tempname);
		$filename = $font.'template/'.$fname.'.htm';

		$exten = '.htm';
		if($ftype!=$exten){
			$exten = '.css';
			$filename = $font.'template/'.$fname.'.css';
		}elseif (!file_exists($filename)){
			$exten = '.css';
			$filename = $font.'template/'.$fname.'.css';
		}
		if(!file_exists($filename))
			return false;

		$data = $this->fileGetContents($filename);
		$fname = basename($tempname);
		if($exten == '.htm')
		$data = $this->initial_sec.$data;
		//-----------------------------------------------------------------------------
		//$patterns[] = '/<ignore>((?:(?!<)[\s\S])*)<\/ignore>/';
		$patterns[] = '/(<ignore>)(?:(?!(?:\1)).)*<\/ignore>/im';
		$replacements[] = '';
		/*$patterns[] = '/#\$((?:(?:\|\$[\w]+\$\|)|.)+)\$#/im';
		$replacements[] = "<?php echo lan('$1');?>";*/
		$preg = '/#\$((?:(?:(\|\$[\w]+\$\|))|(?!\$#).)+)\$#/im';
		$fstr='$open = \'<?php echo lan("\';
			$close = \'");?>\';
			$res = preg_replace('."'/\|\\$([\w]+)\\$\|/m'".', "\$1", $matches[2]);
			$ress = str_replace(array($matches[2],chr(34)), array("{\$".$res."}","&DBQUOTE"), $matches[1]);
			return "$open".$ress."$close";';
		$data = preg_replace_callback(
		        $preg,
		        create_function(
		            '$matches',
		            $fstr),$data);
		            
		$preg = '/\$\$((?:(?:(\|\$[\w]+\$\|))|(?!\$#).)+)\$\$/im';
		$fstr='$open = \'<?php echo moneyformat($\';
			$close = \');?>\';
			$res = preg_replace('."'/\|\\$([\w]+)\\$\|/m'".', "\$1", $matches[2]);
			$ress = str_replace(array($matches[2],chr(34)), array("{\$".$res."}","&DBQUOTE"), $matches[1]);
			return "$open".$ress."$close";';
		$data = preg_replace_callback(
		        $preg,
		        create_function(
		            '$matches',
		            $fstr),$data);
		$patterns = array(
			'/\{(\$[\w\'\[\]]+)\}/i',
			'/\{@(\$[\w\'\[\]]+)\}/i',
			'/\{#(\$[\w\'\[\]]+)\}/i',
			'/{@cutstr\(((?:(?!\)}).)+)\)}/',
			'/\{lang(?:\.)?((?:[\w]+)?)\[([^\]]+)\]}/i',
			'/\{lang(?:\.)?((?:[\w]+)?)\[((?:(?!\]}).)+)\]}/i',
			'/\[img\](\S\s)\[\/img\]/i',
			'/<!--\{csscase\}-->([$a-zA-Z0-9_]*)==([$a-zA-Z0-9_]*)\?([$a-zA-Z0-9_]*):([$a-zA-Z0-9_]*)<!--\{\/csscase\}-->/i',
			'/<json>(\$\w+)<\/json>/i',
			'/<!--\{include:([\w]+).htm(?:l)?\}-->/i',
			'/<!--\{foreach:(\$[\w]+) (\$[\w]+)\}-->/i',
			'/<!--\{foreach:(\$[\w]+) (\$[\w]+) (\$[\w]+)\}-->/i',
			'/<!--\{loop (\$[\w]+) (\$[\w]+)\}-->/i',
			'/<!--\{loop (\$[\w]+) (\$[\w]+) (\$[\w]+)\}-->/i',
			'/<!--\{\/foreach\}-->/i',
			//'/(\/--)((?!--\/)[\S\s])+--\//im',
			'/<!--\{loop (\$[\w]+)(?:[\s]*)=(?:[\s]*)([^~]+)(?:[\s]*)~(?:[\s]*)([\$\w\(\)\'\[\]]+)}-->/i',
			'/<ext[\s]*\/?>/i',
			'/<headerscript>/i',
			'/<\/headerscript>/i',
		);
		$replacements = array(
			'<?php echo $1;?>',
			'<?php echo htmlspecialchars($1, ENT_QUOTES);?>',
			'<?php echo str_replace(array(chr(34)), array("＂"), $1);?>',
			'<?php echo cutstr($1, "utf8")?>',
			'<?php echo __("$2","$1");?>',
			'<?php echo __($2,"$1");?>',
			'<img src="${1}">',
			'<?php echo $compo->cssSwitch($1,array("$2"=>"$3", ""=>"$4"));?>',
			'<?php echo htmlspecialchars(json_encode($1), ENT_NOQUOTES).chr(13).chr(10);?>',
			'<?php include template("$1",true);?>',
			'<?php foreach($1 as $2){?>',
			'<?php foreach($1 as $2=>$3){?>',
			'<?php loop($1 as $2){?>',
			'<?php loop($1 as $2=>$3){?>',
			'<?php } ?>',
			//'',
			'<?php for($1=$2;$i<$3;$i++){?>',
			'<?php $latest_file = __FILE__;include "ext";?>',
			'<?php $temp_ob = "";ob_start(function($ob){$GLOBALS["temp_ob"] = $ob;return "";});?>',
			'<?php ob_end_flush();$header_js.=$GLOBALS["temp_ob"];unset($GLOBALS["temp_ob"]); ?>',
		);
		$data = self::commentcontent($data);
		$data = preg_replace($patterns, $replacements, $data);
		$data = self::scrollcontent($data);
		/*$pattern = '/\{(\$[\w]+)\}/i';
		$replacement = '<?php echo $1;?>';
		$data = preg_replace($pattern, $replacement, $data);
		$pattern = '/\[img\](\S\s)\[\/img\]/i';
		$replacement = '<img src="${1}">';
		$data = preg_replace($pattern, $replacement, $data);
		$pattern = '/<!--\{csscase\}-->([$a-zA-Z0-9_]*)==([$a-zA-Z0-9_]*)\?([$a-zA-Z0-9_]*):([$a-zA-Z0-9_]*)<!--\{\/csscase\}-->/i';
		$replacement = '<?php echo $compo->cssSwitch($1,array("$2"=>"$3", ""=>"$4"));?>';
		$data = preg_replace($pattern, $replacement, $data);
		$pattern = '/<json>(\$\w+)<\/json>/i';
		$replacement = '<?php echo htmlspecialchars(json_encode($1), ENT_NOQUOTES).chr(13).chr(10);?>';
		$data = preg_replace($pattern, $replacement, $data);*/

		$html = array('<!--{if ', '<!--{elseif ', '<!--{else}-->', '<!--{/if}-->' , '<temp>', '</temp>', '{-#' , '#-}', '<!--{loop ', '<!--{/loop}-->', '|$', '$|', '{IMGDIR}' , '}-->', '');
		$php = array('<?php if(', '<?php }elseif(', '<?php }else{?>', '<?php }?>'  , '<?php include template("' , '",true);?>' , '<?php echo $language["' , '"];?>' , '<?php foreach(' , '<?php }?>', '<?php echo $', '?>', $imgcom, '){?>' ,'');
		$ffname = $this->brtrim || $brtrim ? 'order_form' : $fname;
		$data = str_replace($html, $php, $data);

		switch($ffname){
			case 'order_form_test':
			case 'order_form':
			case 'order_form2':
			case 'order_form3':
			case 'heading':
			case 'terms':
			break;
		}
		//-----------------------------------------------------------------------------
		$folder = $dirr;
		switch($exten){
			case '.css':
				$data = $this->compressor($data);
				$cat = $font . 'css/' . $folder;
				$filename = $cat.'t_'.$fname.'.css';
				break;
			default:
				$cat = $font . 'cache/' . $folder;
				$filename = $cat . $pre_cache . $fname . '.php';
				break;
		}
		if (!file_exists($cat) || !is_dir($cat)) 
			mkdir($cat, 0777);

		$this->writeFile($filename, $data);
		return $filename;
	}
	function scrollcontent($data){
		$reg = '/<scrollcontent(([\s]+[\w]+="[^"]+")*)>(.*)<\/scrollcontent>/i';
		$data = preg_replace_callback($reg,function($matches){
			preg_match_all('/[\s]+([\w]+)="([^"]*)"/i', $matches[1], $match);
			$render = '';
			$render .= '<?php '.chr(13);
			foreach($match[0] as $key=>$value){
				$value = $match[2][$key];
				$key = $match[1][$key];
				switch($key){
					case "onComplete":
						$render .= '$scroll_setting["'.$key.'"] = base64_decode("'.base64_encode($value).'");'.chr(13);
						break;
					default :
						$render .= '$scroll_setting["'.$key.'"] = "'.$value.'";'.chr(13);
						break;
				}
			}
			$render .= '$scroll_data = include("include/scroll_data.inc.php");'.chr(13);
			$render .= " include template('scrollcontent'); ".chr(13);
			$render .= '?>';
			return $render;
			return $matches[1].'('.$matches[0].')'.'('.$matches[2].')'.'('.$matches[3].')';
		},$data);
		return $data;
	}
	function decache($tempname, $brtrim, $ftype='.htm', $dirr='/'){
		global $font;
		$imgcom = $font . 'images/common/';
		$fname = $dirr . $this->pre_cache . basename($tempname);
		$filename = $font . 'cache' . $fname . '.php';
		if(!file_exists($filename))
			return false;

		$data = $this->fileGetContents($filename);
		$fname = basename($tempname);
		$patterns[] = $this->initial_sec;
		$replacements[] = '';
		$data = str_replace($patterns, $replacements, $data);
		$patterns = $replacements=array();
		//Template--------------------------------------------------------------------------
		$patterns[] = '/<\?(?:php[\s])?(?:include|require)_once[\s]*\(?\'\.\/cache\/'.$this->pre_cache.'([a-zA-Z0-9\._]*)\.php\'\)?[\s]*;[\s]*\?>/';
		$replacements[] = '<temp>$1</temp>';
		//Echo--------------------------------------------------------------------------
		$patterns[] = '/<\?(?:php echo|=)[\s]*\$([a-zA-Z0-9_\$>-\[\]\']+);?[\s]?\?>/';
		$replacements[] = '|$${1}$|';
		//Foreach--------------------------------------------------------------------------
		$patterns[] = '/(<\?(?:php)?[\s]*) foreach\((.*)\)\{\?>([^(\1)]*)<\?(?:php)? \}\?>/';
		$replacements[] = '<!--{loop $2}-->$3<!--{/loop}-->';
		//PHP If--------------------------------------------------------------------------
		$patterns[] = '/(<\?(?:php[\s])?[\s]*)if\((.*)\)\{[\s]*\?>([^(\1)]*)<\?(?:php)?[\s]*\}[\s]*\?>/';
		$replacements[] = '<!--{if $2}-->$3<!--{/if}-->';
		//PHP ELSE--------------------------------------------------------------------------
		$patterns[] = '/<\?(?:php)?[\s]?}[\s]?else([\s]?if\((.*)\))?[\s]?{[\s]?\?>/';
		$replacements[] = '<!--{else$1}-->';
		$data = preg_replace($patterns, $replacements, $data);
		$data = preg_replace($patterns, $replacements, $data);
		return $data;
	}
	public static function commentcontent($data){
		$x = explode("/--", $data);
		$finalstr = array_shift($x);
		foreach($x as $i=>$v){
			$y=explode("--/", $v);
			if(count($y)>2)
			exit;
			
			$finalstr .= $y[1];
		}
		return $finalstr;
		return $data;
	}
	function updateCache($brtrim){
		$this->brtrim = $brtrim;
		$dir = $font.'template/';
		$findme = '.htm';
		$findme2 = '.css';
		$htmldoc = $cssdoc=0;
		if (is_dir($dir)) {
		    if ($dh = opendir($dir)) {
		        while (($file = readdir($dh)) !== false) {
					$pos = strpos($file, $findme, 1);
					if($pos){
						$htmldoc ++;
						$docname=str_replace($findme, '', $file);
						$u=$this->cache($docname, $brtrim);
					}else{
						$pos = strpos($file, $findme2, 1);
						if($pos){
							$cssdoc ++;
							$docname = str_replace($findme2, '', $file);
							$u = $this->cache($docname, $brtrim);
						}
					}
					$doclist[]=$docname;
		        }
		        closedir($dh);
		    }
		}
		$ret[]='Update finish!<br />Updated '.$htmldoc.' HTML and '.$cssdoc.' CSS template totally';
		$ret[]=$doclist;
		return $ret;
	}
	function compress($path) {
		if(file_exists($path)){
			$file = basename($path);
			$exten = preg_replace('/\w+(?:\.[a-zA-Z]{2,6})*(\.[a-zA-Z]{2,6})/', '$1', $file);
			$data = $this->fileGetContents($path);
			switch($exten){
				case '.css':
					$data = $this->compressor($data);
					break;
				case '.js':
					$data = $this->jsCompressor($data);
					break;
			}
			$path = str_replace('s/org/', 's/', $path);
			$this->writeFile($path, $data);
		}
	    return $buffer;
	}
	function compressor($buffer) {
	    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
	    // remove tabs, spaces, newlines, etc.
	    $buffer = str_replace(array('\r\n', '\r', '\n', '\t', '  ', '    ', '    '), '', $buffer);
	    return $buffer;
	}
	function jsCompressor($buffer) {
		$buffer = preg_replace('!\/\*[^*]*\*+([^/][^*]*\*+)\*/!', '', $buffer);
		$buffer = preg_replace('/(\/\/.*)/', '', $buffer);
		echo $buffer;
		$buffer = str_replace(array('\r\n', '\r', '\n', '\t', '  ', '    ', '    '), array('', '', '', '', '', '', ''), $buffer);
		return $buffer;
	}
	function readFile($path){
		return self::fileGetContents($path);
	}
	function fileGetContents($path){
		if(!file_exists($path))
		return false;
		$of = fopen($path, 'rb');
		$data = '';
		while (!feof($of)) {
			$data .= fread($of, 8192);
		}
		fclose($of);
		return $data;
	}
	function writeFile($path,$data){
		chmod($path, 0777);
		unlink($path);
		$fp = fopen($path, 'wb');
		fwrite($fp, $data);
		fclose($fp);
		return true;
	}
}