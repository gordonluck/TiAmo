<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!--Footer-->
<footer class="row" id="footer">
    <div class="container">
        <div class="row top-footer">
            <div class="widget col-sm-3 widget-about">
                <div class="row m0"><a href="<?php $this->options->siteUrl(); ?>"><img src="<?php if($this->options->logos): $this->options->logos(); else : $this->options->themeUrl(); echo 'images/logo-white-green.png'; endif; ?>" alt=""></a></div>
            </div>
            <?php if($this->is('index')):?>
            <div class="widget col-sm-5 widget-menu">
                <div class="row">
                    <ul class="nav column-menu white-bg">
                        <li <?php if($this->is('index')): ?>class="active"<?php endif; ?>><a href="<?php $this->options->siteUrl(); ?>">首页</a></li>

                        <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
                        <?php while($pages->next()): ?>
                        <li>
                            <a <?php if($this->is('page', $pages->slug)): ?>class="active"<?php endif; ?> href="<?php $pages->permalink(); ?>" title="<?php $pages->title(); ?>">
                                <?php $pages->title(); ?>[<?php $pages->slug(); ?>]
                            </a>
                        </li>
                        <?php endwhile; ?>
                        
                    </ul>
                    <ul class="nav column-menu white-bg">
                        <!--li></li-->
                    </ul>
                </div>
            </div>
            <div class="widget col-sm-4 widget-subscribe">
                <h5 class="widget-title">搜索我写的文章吧!</h5>
                <form action="./" method="post" class="form-inline subscribe-form" role="search">                    
                    <div class="form-group">
                        <input type="search" name="s" class="form-control" placeholder="搜索吧">
                    </div>
                </form>
                
            </div>
             <?php endif;?>
        </div>
        <h5 class="copyright"><?php $this->options->leftright();?> / Theme made with by <a href="https://github.com/kraity/TiAmo" rel="nofollow" target="_blank">Krait</a></h5>
    </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pangu/3.2.1/pangu.min.js"></script>
<script>pangu.spacingElementByClassName('content-wrap'); /*英文间加上空格*/</script>

<?php $this->footer(); ?>

<script src="<?php $this->options->themeUrl(); ?>vendors/owl-carousel/owl.carousel.min.js"></script>
<script src="<?php $this->options->themeUrl(); ?>vendors/magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="<?php $this->options->themeUrl(); ?>vendors/imagesLoaded/imagesloaded.pkgd.min.js"></script>
<script src="<?php $this->options->themeUrl(); ?>vendors/isotope/isotope.pkgd.min.js"></script>
<script src="<?php $this->options->themeUrl(); ?>js/theme.js"></script>

</body>
</html>
