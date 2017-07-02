<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('function.php');
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta http-equiv="content-language" content="zh-CN" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php $this->archiveTitle(array(
            'category'  =>  _t('%s '),
            'search'    =>  _t('所属关键字 %s '),
            'tag'       =>  _t('所属标签 %s '),
            'author'    =>  _t('%s ')
        ), '', ' - '); ?><?php $this->options->title(); ?></title>
    <meta name="keywords" content="<?php $this->options->keywords(); ?>" />
    <meta name="description" content="<?php $this->options->description(); ?>"/>
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php $this->options->themeUrl(); ?>vendors/owl-carousel/assets/owl.carousel.css">
    <link rel="stylesheet" href="<?php $this->options->themeUrl(); ?>vendors/magnific-popup/magnific-popup.css">

    <link href="<?php $this->options->themeUrl(); ?>css/style.css" rel="stylesheet">
    <link href="<?php $this->options->themeUrl(); ?>css/theme/green.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <?php $this->header('description=&keywords=&generator=&template=&pingback=&xmlrpc=&wlw=&commentReply=&rss1=&rss2=&atom='); ?>
</head>

<body class="home">
<header class="row transparent black header1" data-spy="affix" data-offset-top="0" id="header">
    <div class="container">
        <div class="row top-header">
            <div class="col-sm-6 logo-col text-center">

            </div>
            <div class="col-sm-6 menu-trigger-col">
                <div class="menu-trigger pull-right">
                    <div class="hamburger hamburger--spin-r">
                        <div class="hamburger-box">
                            <div class="hamburger-inner"></div>
                        </div>
                    </div>
                </div>
                <div  class="menu-login">
                    <a href="<?php $this->options->adminUrl(); ?>"><i class="icon iconfont icon-userdefault"></i></a>
                </div>
                <div class="search-form-col">
                    <form method="post" action="./" role="search" class="search-form">
                        <div class="input-group">
                            <span class="input-group-addon"><img src="<?php $this->options->themeUrl(); ?>images/search-icon-white.png" alt=""></span>
                            <input type="search" name="s" class="form-control" placeholder="search">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row menu-section">
            <ul class="nav column-menu black-bg">
                <li <?php if($this->is('index')): ?>class="active"<?php endif; ?>><a href="<?php $this->options->siteUrl(); ?>">首页</a></li>
                <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
                <?php while($pages->next()): ?>
                    <li <?php if($this->is('page', $pages->slug)): ?>class="active"<?php endif; ?>>
                        <a href="<?php $pages->permalink(); ?>" title="<?php $pages->title(); ?>">
                            <?php $pages->title(); ?>[<?php $pages->slug(); ?>]
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
</header>
<script>
    var forEach=function(t,o,r){if("[object Object]"===Object.prototype.toString.call(t))for(var c in t)Object.prototype.hasOwnProperty.call(t,c)&&o.call(r,t[c],c,t);else for(var e=0,l=t.length;l>e;e++)o.call(r,t[e],e,t)};
    var hamburgers = document.querySelectorAll(".hamburger");
    if (hamburgers.length > 0) {
        forEach(hamburgers, function(hamburger) {
            hamburger.addEventListener("click", function() {

                this.classList.toggle("is-active");
                if ( $(this).hasClass('active') ){
                    $(this).removeClass('active');$('#header').removeClass('menu-active')
                }else{
                    $(this).addClass('active');$('#header').addClass('menu-active')
                }

            }, false);
        });
    }
</script>
<section class="row featured-post-carousel">
    <div class="item post">
        <div class="header-img">
            <svg version="1.1" viewBox="0 0 100 100" preserveAspectRatio="none" class="svg-top">
                <path class="large left" d="M0 0 L50 100 L0 100" fill="rgba(255,255,255, .1)"></path>
                <path class="large right" d="M100 0 L50 100 L100 100" fill="rgba(255,255,255, .1)"></path>
                <path class="medium left" d="M0 100 L50 100 L0 40" fill="rgba(255,255,255, .35)"></path>
                <path class="medium right" d="M100 100 L50 100 L100 40" fill="rgba(255,255,255, .35)"></path>
                <path class="small left" d="M0 100 L50 100 L0 70" fill="rgb(245, 244, 244)"></path>
                <path class="small right" d="M100 100 L50 100 L100 70" fill="rgb(245, 244, 244)"></path>
                <path d="M17 99.9 L50 77 L83 99.9 L0 100" fill="rgb(245, 244, 244)"></path>
            </svg>
        </div>
        <div class="post-content">
            <div class="container">
                <h5 class="post-meta"><i>in</i> me <?php if($this->is('post')){ $this->date('F j, Y'); }?></h5>
                <h2 class="title-white post-title" >
                    <a>
                        <?php if($this->is('index')){ echo widget_title($this->options->describes); }else{$this->archiveTitle(array('category'  => _t('%s '),'search'=> _t('所属关键字 %s '),'tag' => _t('所属标签 %s '),'author'=> _t('%s ')), ''); } ?>
                    </a>
                </h2>
            </div>
        </div>
    </div>
</section>
<style>
    .header-img {
        <?php if($this->options->headjpg){ echo 'background: url('.$this->options->headjpg.') no-repeat center;'; } ?>
        background-size: cover;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        -ms-background-size: cover;
    }
</style>