<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

define("TiAmo", "1.0.1");

/**
 * 主题外观ThemeConfig
 *
 *
 *
 */
function themeConfig($form) {

    echo '<p style="font-size:16px;text-align:center;">TiAmo [ '.TiAmo.' ] made by <a href="https://krait.cn">那他</a> </p>';

    $name = new Typecho_Widget_Helper_Form_Element_Text('name', NULL, '那他', _t('用名'), _t('此处填入你的名字,暂时没有调用它的地方'));
    $form->addInput($name);

    $headjpg = new Typecho_Widget_Helper_Form_Element_Text('headjpg', NULL, 'https://wx1.sinaimg.cn/large/006HxDxWgy1fg2llc4kd4j31iq0p0n2l.jpg', _t('头部大图'), _t('此处填入头部大图路径,若没有填写，加载文件只带图片'));
    $form->addInput($headjpg);

    $describes = new Typecho_Widget_Helper_Form_Element_Text('describes', NULL, '这里是一个描述我的.', _t('站长描述'), _t('此处填入头部描述,它只在首页、作者名片里才显示'));
    $form->addInput($describes);

    $socialnav = new Typecho_Widget_Helper_Form_Element_Textarea('socialnav', NULL, '<li><a href="https://github.com/kraity"><i class="icon iconfont icon-github"></i></a></li>
<li><a href=""><i class="icon iconfont icon-weibo"></i></a></li>
<li><a href="javascript:;"><i class="icon iconfont icon-weixin"></i></a></li>
<li><a href=""><i class="icon iconfont icon-mail"></i></a></li>', _t('Social-nav'), _t('此处填入social-nav, 格式: 以li为标签 然后 a标签 再次 i标签(图标必写)'));
    $form->addInput($socialnav);

    $leftright = new Typecho_Widget_Helper_Form_Element_Text('leftright', NULL, 'Copyright &copy; 2017 ', _t('页脚版权'), _t('此处填入页脚版权,它用于在页脚显示的版权声明。如有需要,亲手动去修改文件。'));
    $form->addInput($leftright);

    $logos = new Typecho_Widget_Helper_Form_Element_Text('logos', NULL, NULL, _t('Logo路径'), _t('此处填入Logo路径,它是在页脚下显示的Logo图片,建议务必填写'));
    $form->addInput($logos);

}


/**
* 显示下一篇 - 旧的
*
* @access public
* @param string $default 如果没有下一篇,显示的默认文字
* @return void
*/
function thePrev($widget, $default = NULL){
    $db = Typecho_Db::get();
    $sql = $db->select()->from('table.contents')
        ->where('table.contents.created < ?', $widget->created)
        ->where('table.contents.status = ?', 'publish')
        ->where('table.contents.type = ?', $widget->type)
        ->where('table.contents.password IS NULL')
        ->order('table.contents.created', Typecho_Db::SORT_DESC)
        ->limit(1);
    $content = $db->fetchRow($sql);

    if ($content) {
        $content = $widget->filter($content);
        $link = '<a href="' . $content['permalink'] . '" title="' . $content['title'] . '" rel="prev" class="post-nav-older">' . $content['title'] . '</a>';
        echo $link;
    } else {
        echo $default;
    }
}

/**
* 显示上一篇 - 新的
*
* @access public
* @param string $default 如果没有上一篇,显示的默认文字
* @return void
*/
function theNext($widget, $default = NULL){
    $db = Typecho_Db::get();
    $sql = $db->select()->from('table.contents')
        ->where('table.contents.created > ?', $widget->created)
        ->where('table.contents.status = ?', 'publish')
        ->where('table.contents.type = ?', $widget->type)
        ->where('table.contents.password IS NULL')
        ->order('table.contents.created', Typecho_Db::SORT_ASC)
        ->limit(1);
    $content = $db->fetchRow($sql);

    if ($content) {
        $content = $widget->filter($content);
        $link = '<a href="' . $content['permalink'] . '" title="' . $content['title'] . '" rel="next" class="post-nav-newer">' . $content['title'] . '</a>';
        echo $link;
    } else {
        echo $default;
    }
}

function getCommentAt($coid){
    $db   = Typecho_Db::get();
    $prow = $db->fetchRow($db->select('parent')
        ->from('table.comments')
        ->where('coid = ? AND status = ?', $coid, 'approved'));
    $parent = $prow['parent'];
    if ($parent != "0") {
        $arow = $db->fetchRow($db->select('author')
            ->from('table.comments')
            ->where('coid = ? AND status = ?', $parent, 'approved'));
        $author = $arow['author'];
        $href   = '<a href="#comment-'.$parent.'">@'.$author.'</a>';
        echo $href;
    } else {
        echo '';
    }
}

function formats($mat){
    switch ($mat) {
        case "post" :
            //标准 post
            echo 'text';
            break;
        case "quote" :
            //引语 quote
            echo 'quote';
            break;
        case "aside" :
            //日志 aside
            echo 'image';
            break;
        case "gallery" :
            //相册 gallery
            echo 'gallery';
            break;
        case "video" :
            //视频 video
            echo "video";
            break;
        case "link" :
            //链接 link
            echo "link";
            break;
        default:
            //其他 post
            echo 'text';
    }
}

/**
* 判断最新 - 指定
*
*@remarks: 判断文章是否为最近7天更新
*/
function timeZone($from){
    $now = new Typecho_Date(Typecho_Date::gmtTime());
    return  $now->timeStamp - $from < 7*24*60*60 ? true : false;
}

/**
* 客户判断 - 指定
*
*@remarks: 判断分文是否为WAP即输出
*/
function wap(){
    if(@stristr($_SERVER['HTTP_VIA'],"wap")){
        return true;
    }elseif(strpos(strtoupper($_SERVER['HTTP_ACCEPT']),"VND.WAP.WML") > 0){
        return true;
    }elseif(preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])){
        return true;
    }else{
        return false;
    }
}
