<?php
/**
 * TiAmo 主题配置
 * 
 * @package tiamos 
 * @author 权那他
 * @version 1.0.0
 * @link https://github.com/deerweak/TiAmo
 */
class tiamos_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        $db = Typecho_Db::get();
        $prefix = $db->getPrefix();

        // contents 表中若无 format 字段则添加
        if (!array_key_exists('format', $db->fetchRow($db->select()->from('table.contents'))))
            $db->query("ALTER TABLE `".$prefix."contents` ADD `format` varchar(16) DEFAULT 'post'");
			
		//添加文章
        Typecho_Plugin::factory('admin/write-post.php')->option = array('tiamos_Plugin', 'formatsSelect');       
		
		//编辑文章
		Typecho_Plugin::factory('Widget_Contents_Post_Edit')->write = array('tiamos_Plugin', 'formatsSet');    
        
        
        Typecho_Plugin::factory('index.php')->begin = array('tiamos_Plugin', 'Widget_index_begin');
        Typecho_Plugin::factory('admin/footer.php')->end = array('tiamos_Plugin', 'Footeradmin');
        Typecho_Plugin::factory('Widget_Archive')->indexHandle = array('tiamos_Plugin', 'sticky');

    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){
        $compression_open = new Typecho_Widget_Helper_Form_Element_Checkbox('compression_open', array('compression_open' => '开启gzip'), null, _t('是否开启gzip'));
        $form->addInput($compression_open);

        $compression_level = new Typecho_Widget_Helper_Form_Element_Select('compression_level', array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9'), '5', _t('gzip压缩级别:'), _t('这个参数值范围是0-9，0表示无压缩，9表示最大压缩，当然压缩程度越高越费CPU。*推荐：5'));
        $form->addInput($compression_level);

        $compress_html = new Typecho_Widget_Helper_Form_Element_Checkbox('compress_html', array('compress_html' => '开启压缩HTML'), null, _t('是否开启压缩HTML'), _t('当开启后页面与原来页面不一致时请关闭'));
        $form->addInput($compress_html);

        $keyword_replace = new Typecho_Widget_Helper_Form_Element_Textarea('keyword_replace', null, null, _t('HTML关键词替换'), _t('作用：主要把附件的内容转到七牛。一行一个。格式：关键词=替换关键词'));
        $form->addInput($keyword_replace);
        
        $sticky_cids = new Typecho_Widget_Helper_Form_Element_Text(
          'sticky_cids', NULL, '',
          '置顶文章的 cid', '按照排序输入, 请以半角逗号或空格分隔 cid.');
        $form->addInput($sticky_cids);

        $sticky_html = new Typecho_Widget_Helper_Form_Element_Textarea(
          'sticky_html', NULL, "<strong>[置顶]</strong>",
          '置顶标题的 html', '');
        $sticky_html->input->setAttribute('rows', '7')->setAttribute('cols', '80');
        $form->addInput($sticky_html);
		$formats = new Typecho_Widget_Helper_Form_Element_Checkbox('format', array(
            'aside'=>'日志', 
            'gallery'=>'相册', 
            'link'=>'链接', 
            'quote'=>'引语', 
            'status'=>'状态', 
            'video'=>'视频', 
            'audio'=>'音频', 
            'chat'=>'聊天'),array('aside','gallery','link','quote','video'),'选取支持的文章形式',_t('被选择的文章形式将会在编写文章时出现在选项里, 一项也不选择将会默认显示所有'));
		$form->addInput($formats->multiMode()); 
	}

    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 编辑文章页, 添加 "文章形式" 选项
     *
     *
     * @param string  $args 文章形式
     * @return void
     */
    public static function formatsSelect()
    {
		$options = Typecho_Widget::widget('Widget_Options');
		$formats = $options->plugin('tiamos');
		$custom_format =  $formats->format;
		$regular_format = array(
            'post'=>'标准', 
            'aside'=>'日志', 
            'gallery'=>'相册', 
            'link'=>'链接', 
            'quote'=>'引语', 
            'status'=>'状态', 
            'video'=>'视频', 
            'audio'=>'音频', 
            'chat'=>'聊天');
		if(count($custom_format)>0){
			$args = array('post'=>'标准');
			foreach($regular_format as $key=>$val){
				if(in_array($key,$custom_format)){
					$args[$key] = $val;
				}
			}
		}else{
			$args = array(
                'post'=>'标准', 
                'aside'=>'日志', 
                'gallery'=>'相册', 
                'link'=>'链接', 
                'quote'=>'引语', 
                'status'=>'状态', 
                'video'=>'视频', 
                'audio'=>'音频', 
                'chat'=>'聊天');
		}
		if(isset($_GET['cid'])){
			$cid = $_GET['cid'];
			$db = Typecho_Db::get();
			$row = $db->fetchRow($db->select('format')->from('table.contents')->where('cid = ?', $cid));
			$format = $row['format'];
		}else{
			$format = "post";
		}
		$output = '';
		foreach( $args as $key => $value ){
			$check = $key == $format ? 'checked="checked"' :'';
			$output .= '<li><input type="radio" name="format" id="format-'.$key.'" value="'.$key.'" '.$check.'><label for="format-'.$key.'">'.$value.'</label></li>';
		}
		echo '<li><label class="typecho-label">形式</label><ul>'.$output.'</ul></li>';
    }
	
    /**
     * 提交文章, 设置文章形式
     *
     * 
     * @return void
     */
	public static function formatsSet($contents, $inst)
	{
		$db = Typecho_Db::get();
		Typecho_Widget::widget('Widget_Contents_Post_Edit')->to($post);	
		$cid = $post->cid;
		if($cid!=null){ // 文章已存在, 直接修改 format 字段
			$db->query($db->update('table.contents')->rows(array('format' => $_POST['format']))->where('cid = ?', $cid));
			return $contents;
		}else{ // 文章不存在, 新建文章
			$options = Typecho_Widget::widget('Widget_Options');
			
			if( $contents['title']!="" ){
				$contents['status'] = $_POST['do']== 'publish' ? 'publish':'draft';
				$cid = $post->insert($contents);

				/** 插入分类 */
				if (array_key_exists('category', $contents)) {
					$post->setCategories($cid, !empty($contents['category']) && is_array($contents['category']) ?
					$contents['category'] : array($options->defaultCategory), false, true);
				}

				/** 插入标签 */
				if (array_key_exists('tags', $contents)) {
					$post->setTags($cid, $contents['tags'], false, true);
				}

				/** 同步附件 */
				$post->attach($cid);
				
				/** 文章形式 */
				$db->query($db->update('table.contents')->rows(array('format' => $_POST['format']))->where('cid = ?', $cid));
				
				$post->fetchRow($post->select()->where('table.contents.cid = ?', $cid)->limit(1), array($post, 'push'));
				
				/** 新建提示 */
				$newPost = $db->fetchRow($post->select()->where('table.contents.cid = ?', $cid)->limit(1));
				$result = Typecho_Widget::widget('Widget_Abstract_Contents')->push($newPost);
				$post->widget('Widget_Notice')->set('publish' == $result['status'] ?
				_t('文章 "<a href="%s">%s</a>" 已经发布', $result['permalink'], $result['title']) :
				_t('文章 "%s" 等待审核', $result['title']), NULL, 'success');

				/** 设置高亮 */
				$post->widget('Widget_Notice')->highlight($cid);
			}
			$post->response->redirect(Typecho_Common::url('manage-posts.php', $options->adminUrl));
			exit;
		}
	}

	/**
     * 输出文章形式
     *
     * 语法: tiamos_Plugin::getFormat();
     *
     * @access public
     * @return void
     */
	public static function getFormat()
	{
        $db = Typecho_Db::get();
        $cid = Typecho_Widget::widget('Widget_Archive')->cid;
        $row = $db->fetchRow($db->select('format')->from('table.contents')->where('cid = ?', $cid));
		return $row['format'];
	}
    
    
    public static function Widget_index_begin() {
        ob_start('tiamos_Plugin::qlwz_ob_handler');
    }

    public static function Widget_Archive_beforeRender() {
        ob_start('tiamos_Plugin::qlwz_ob_handler');
    }

    public static function qlwz_ob_handler($buffer) {
        $settings = Helper::options()->plugin('tiamos');

        if ($settings->keyword_replace) {
            $list = explode("\r\n", $settings->keyword_replace);
            foreach ($list as $tmp) {
                list($old, $new) = explode('=', $tmp);
                $buffer = str_replace($old, $new, $buffer);
            }
        }

        if ($settings->compress_html) {
            $buffer = self::qlwz_compress_html($buffer);
        }

        if ($settings->compression_open) {
            $buffer = self::ob_gzip($buffer, $settings->compression_level);
        } else {
            if (ini_get('zlib.output_compression')) {
                ini_set('zlib.output_compression', 'Off');
            }
        }
        return $buffer;
    }

    public static function ob_gzip($buffer, $level) {
        if (ini_get('zlib.output_compression')) {
            if (ini_get('zlib.output_compression_level') != $level) {
                ini_set('zlib.output_compression_level', $level);
            }
            return $buffer;
        }
        if (headers_sent() || !extension_loaded('zlib') || !strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
            return $buffer;
        }

        $out_buffer = gzencode($buffer, $level);
        if (strlen($out_buffer) < strlen($buffer)) {
            header('Content-Encoding: gzip');
            header('Vary: Accept-Encoding');
            header('Content-Length: ' . strlen($out_buffer));
        } else {
            $out_buffer = $buffer;
        }
        return $out_buffer;
    }

    /**
     * 压缩HTML代码
     *
     * @author 情留メ蚊子 <qlwz@qq.com>
     * @version 1.0.0.0 By 2016-11-23
     * @param string $html_source HTML源码
     * @return string 压缩后的代码
     */
    public static function qlwz_compress_html($html_source) {
        $chunks = preg_split('/(<!--<nocompress>-->.*?<!--<\/nocompress>-->|<nocompress>.*?<\/nocompress>|<pre.*?\/pre>|<textarea.*?\/textarea>|<script.*?\/script>)/msi', $html_source, -1, PREG_SPLIT_DELIM_CAPTURE);
        $compress = '';
        foreach ($chunks as $c) {
            if (strtolower(substr($c, 0, 19)) == '<!--<nocompress>-->') {
                $c = substr($c, 19, strlen($c) - 19 - 20);
                $compress .= $c;
                continue;
            } else if (strtolower(substr($c, 0, 12)) == '<nocompress>') {
                $c = substr($c, 12, strlen($c) - 12 - 13);
                $compress .= $c;
                continue;
            } else if (strtolower(substr($c, 0, 4)) == '<pre' || strtolower(substr($c, 0, 9)) == '<textarea') {
                $compress .= $c;
                continue;
            } else if (strtolower(substr($c, 0, 7)) == '<script' && strpos($c, '//') != false && (strpos($c, "\r") !== false || strpos($c, "\n") !== false)) { // JS代码，包含“//”注释的，单行代码不处理
                $tmps = preg_split('/(\r|\n)/ms', $c, -1, PREG_SPLIT_NO_EMPTY);
                $c = '';
                foreach ($tmps as $tmp) {
                    if (strpos($tmp, '//') !== false) { // 对含有“//”的行做处理
                        if (substr(trim($tmp), 0, 2) == '//') { // 开头是“//”的就是注释
                            continue;
                        }
                        $chars = preg_split('//', $tmp, -1, PREG_SPLIT_NO_EMPTY);
                        $is_quot = $is_apos = false;
                        foreach ($chars as $key => $char) {
                            if ($char == '"' && $chars[$key - 1] != '\\' && !$is_apos) {
                                $is_quot = !$is_quot;
                            } else if ($char == '\'' && $chars[$key - 1] != '\\' && !$is_quot) {
                                $is_apos = !$is_apos;
                            } else if ($char == '/' && $chars[$key + 1] == '/' && !$is_quot && !$is_apos) {
                                $tmp = substr($tmp, 0, $key); // 不是字符串内的就是注释
                                break;
                            }
                        }
                    }
                    $c .= $tmp;
                }
            }
            $c = preg_replace('/[\\n\\r\\t]+/', ' ', $c); // 清除换行符，清除制表符
            $c = preg_replace('/\\s{2,}/', ' ', $c); // 清除额外的空格
            $c = preg_replace('/>\\s</', '> <', $c); // 清除标签间的空格
            $c = preg_replace('/\\/\\*.*?\\*\\//i', '', $c); // 清除 CSS & JS 的注释
            $c = preg_replace('/<!--[^!]*-->/', '', $c); // 清除 HTML 的注释
            $compress .= $c;
        }
        return $compress;
    }
    
    
    /**
     * 选取置顶文章
     * 
     * @access public
     * @param object $archive, $select
     * @return void
     */
    public static function sticky($archive, $select)
    {
        $config  = Typecho_Widget::widget('Widget_Options')->plugin('tiamos');
        $sticky_cids = $config->sticky_cids ? explode(',', strtr($config->sticky_cids, ' ', ',')) : '';
        if (!$sticky_cids) return;

        $db = Typecho_Db::get();
        $paded = $archive->request->get('page', 1);
        $sticky_html = $config->sticky_html ? $config->sticky_html : "<strong>[置顶]</strong>";

        foreach($sticky_cids as $cid) {
          if ($cid && $sticky_post = $db->fetchRow($archive->select()->where('cid = ?', $cid))) {
              if ($paded == 1) {                               // 首頁 page.1 才會有置頂文章
                $sticky_post['sticky'] = $sticky_html;
                $archive->push($sticky_post);                  // 選取置頂的文章先壓入
              }
              $select->where('table.contents.cid != ?', $cid); // 使文章不重覆
          }
        }
    }
    
    
    /**
     * 后台参数
     *
     * @author 那他
     */
    public static function Footeradmin()
    {}
}
