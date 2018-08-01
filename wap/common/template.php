<?php
/****************
 * 核心文件
 * @discription: 编写简单的模板引擎
 */
define('INVIEW', true);
class Template {
    var $tpl_dir = 'template';
    var $cache_dir = 'cache';
    var $tpl_ext = '.html';
    var $var_left = '{{';
    var $var_right = '}}';
    var $common_header = '';
    var $data = array();
    function __construct($config=array()) {
        extract($config);
        if(isset($tpl_dir))$this->tpl_dir = $tpl_dir;
        if(isset($cache_dir))$this->cache_dir = $cache_dir;
        if(isset($tpl_ext))$this->tpl_ext = $tpl_ext;
        if(isset($var_left))$this->var_left = $var_left;
        if(isset($var_right))$this->var_right = $var_right;
        if(isset($common_header))$this->common_header = $common_header;
    }
    function assign($key,$val){
    	$this->data[$key] = $val;
    }
    function load($tplfilename) {
        $tplfile = $this->tpl_dir.'/'.$tplfilename.$this->tpl_ext;
        if(!file_exists($tplfile))
            die('Template not found: '.$tplfile);
        return $this->cache($tplfilename, $tplfile);
    }
    //判断模板是否缓存，如模板文件有更改则重新编译
    function cache($tplname, $tpl_file) {
        $cache_file = $this->cache_dir.'/'.md5($tplname).'.php';
        if(!file_exists($cache_file) || filemtime($tpl_file)>filemtime($cache_file))
            $this->compile($tpl_file, $cache_file);
        extract($this->data);
        include_once  $cache_file;
    }
    //编译模板内容到PHP格式，并写入缓存
    function compile($tpl, $cache) {
        $body = file_get_contents($tpl);
        $vl = $this->var_left;
        $vr = $this->var_right;
        $patterns = array(//#是定界符，#i代表大小写不敏感
            "#$vl\s*include:(.+?)\s*$vr#i",
            "#$vl\s*if\s+(.+?)\s*$vr#i",
            "#$vl\s*else\s*$vr#i",
            "#$vl\s*elseif\s+(\\$.+?)\s*$vr#i",
            "#$vl\s*endif\s*$vr#i",
            "#$vl\s*/if\s*$vr#i",
        	"#$vl\s*foreach\s+(\\$.+?):(\\$.+?):(\\$.+?)\s*$vr#i",
        	"#$vl\s*foreach\s+(\\$.+?):(\\$.+?)\s*$vr#i",
            "#$vl\s*endforeach\s*$vr#i",
            "#$vl\s*/foreach\s*$vr#i",
            "#$vl(\\$[0-9a-zA-Z_]+?)\.([0-9a-zA-Z_]+?)\.([0-9a-zA-Z_]+?)$vr#i",
            "#$vl(\\$[0-9a-zA-Z_]+?)\.([0-9a-zA-Z_]+?)$vr#i",
            "#$vl(\\$[0-9a-zA-Z_\[\]\'\"]+?)$vr#i",
        	"#$vl\s*(.+?)\s*$vr#i",
            "#$vl(\\$[0-9a-zA-Z_]+?):(.*?)$vr#i"
        );
        $replacements = array(
            "<?php include \\1; ?>",
            "<?php if(\\1): ?>",
            "<?php else: ?>",
            "<?php elseif(\\1): ?>",
            "<?php endif; ?>",
            "<?php endif; ?>",
        	"<?php if(count(\\1)>0):foreach(\\1 as \\2=>\\3): ?>",
        	"<?php if(count(\\1)>0):foreach(\\1 as \\2): ?>",
            "<?php endforeach;endif; ?>",
            "<?php endforeach;endif; ?>",
            "<?php echo \\1['\\2']['\\3']; ?>",
            "<?php echo \\1['\\2']; ?>",
            "<?php echo \\1; ?>",
        	"<?php echo \\1; ?>",
            "<?php echo \\1(\\2); ?>"
        );

        if($this->common_header){
        	$header = file_get_contents($this->common_header.$this->tpl_ext);
        	$body = $header.$body;
        }
        $body = preg_replace($patterns, $replacements, $body);

        file_put_contents($cache, "<?php if(!defined('INVIEW'))die('LAJI'); ?>".$body);
    }
}

?>
