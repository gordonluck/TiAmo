<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<?php $this->need('header.php'); ?>

<?php $format = tiamos_Plugin::getFormat(); ?>

    <section class="row content-wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-<?php switch ($format) { case "gallery": echo'12'; break; default: echo '8'; }?> single-post-contents">
                    <article class="single-post-content row m0 post">
                        <header class="row post-header">
                            <h2 class="post-title"><?php $this->title(); ?></h2>
                            <div class="post-meta">
                                <div class="post-meta-left">
                                    <div class="post-meta-left-head">
                                        <img src="<?php echo 'https://secure.gravatar.com/avatar/'.md5($this->author->mail).'?s=100&r=G&d=mm'; ?>">
                                    </div>
                                    <div class="post-meta-left-er">
                                        <span style="border-right:solid 1px #999; padding-right:5px;"><?php $this->date('F j, Y');?></span>
                                        <span>GOD</span>
                                    </div>
                                </div>
                                <div class="post-meta-right">
                                    <span style="margin-left:5px;"><?php $this->commentsNum(_t('暂无评论'), _t('仅有 1 条评论'), _t('已有 %d 条评论')); ?></span>
                                </div>
                            </div>
                        </header>
                        <div id="post-id" class="post-content row">
                            <?php $this->content(); ?>
                            <span class="tag-links">
                            <?php $this->tags(', ', true); ?>
                        </span>
                        </div>
                        <div class="comment-box" style="padding: 3px 0;"><?php $this->need('comments.php'); ?></div>
                    </article>
                </div>
                <?php if($format !=='gallery'):?>
                    <div class="col-md-4 sidebar">
                        <?php $this->need('sidebar.php'); ?>
                    </div>
                <?php endif;?>
            </div>
        </div>
    </section>

<?php $this->need('footer.php'); ?>