<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<?php
function threadedComments($comments, $options) {
    $commentClass = '';
    if ($comments->authorId) {
        if ($comments->authorId == $comments->ownerId) {
            $commentClass .= ' comment-by-author';
        } else {
            $commentClass .= ' comment-by-user';
        }
    }
    $commentLevelClass = $comments->levels > 0 ? ' comment-child' : ' comment-parent';
    $depth = $comments->levels +1;
    if ($comments->url) {
        $author = '<a href="' . $comments->url . '"target="_blank"' . ' rel="external nofollow">' . $comments->author . '</a>';
    } else {
        $author = $comments->author;
    }
    ?>

    <li id="li-<?php $comments->theId(); ?>" class="comment even thread-even depth-<?php echo $depth ?> comment-body<?php
    if ($comments->levels > 0) {
        echo ' comment-child';
        $comments->levelsAlt(' comment-level-odd', ' comment-level-even');
    } else {
        echo ' comment-parent';
    }
    $comments->alt(' comment-odd', ' comment-even');
    ?>">
        <article id="<?php $comments->theId(); ?>" class="comment-body">
            <footer class="comment-meta">
                <div class="comment-author vcard">
                    <?php $comments->gravatar(40); ?>
                    <b class="fn <?php echo $commentClass; ?>" itemprop="author">
                        <?php echo $author; ?>
                    </b>
                </div>
                <!-- .comment-author -->

                <div class="comment-metadata">
                    <a href="" itemprop="url">
                        <time datetime="2017-05-14T17:25:49+08:00" itemprop="datePublished"><?php $comments->date('M j, Y'); ?></time>
                    </a>
                </div>
                <!-- .comment-metadata -->

            </footer>
            <!-- .comment-meta -->

            <div class="comment-content" itemprop="text">
                <p><?php $comments->content(); ?></p>
            </div>
            <!-- .comment-content -->

            <div class="comment-actions">
                <?php $comments->reply('回复'); ?>
                <!-- .comment-actions -->
        </article>

        <?php if ($comments->children) { ?>
            <div class="children">
                <?php $comments->threadedComments($options); ?>
            </div>
        <?php } ?>
    </li>
<?php } ?>

<div class="comment-respond">
    <?php $this->comments()->to($comments); ?>
    <?php if ($comments->have()): ?>
        <span class="comment-num"><?php $this->commentsNum(_t('暂无评论'), _t('仅有 1 条评论'), _t('已有 %d 条评论')); ?></span>

        <?php $comments->listComments(); ?>

        <?php $comments->pageNav('&laquo;', '&raquo;'); ?>

    <?php endif; ?>

    <?php if($this->allow('comment')): ?>
        <div id="<?php $this->respondId(); ?>" class="respond">
            <div class="cancel-comment-reply">
                <?php $comments->cancelReply(); ?>
            </div>
            <h3 id="reply-title" class="comment-reply-title">
                <span>发表评论</span>
                <small><?php $this->commentsNum(_t('暂无评论'), _t('仅有 1 条评论'), _t('已有 %d 条评论')); ?></small>
                <?php $comments->cancelReply('取消回复'); ?>
            </h3>
            <form action="<?php $this->commentUrl() ?>" method="post" id="commentform" class="comment-form">
                <div class="author-info guest"><img src="//gravatar.com/avatar/?d=mm&s=100" width="100" class="avatar"></div>
                <div class="comment-form-main">
                    <div class="comment-textarea-wrapper">
                        <p class="comment-form-comment"><label for="comment">评论</label>
                            <textarea id="comment" name="text" onclick='document.getElementById("comment-form-do").style.display="block";' cols="45" rows="8" aria-required="true" required="required" placeholder="发泄你的牢骚,留下你的笔言!"><?php $this->remember('text',false); ?></textarea>
                        </p>
                        <div class="comment-form-toolbar">

                        </div>
                    </div>

                    <?php if(!$this->user->hasLogin()): ?>
                        <div class="comment-form-fields" id="comment-form-do">
                            <p class="comment-form-author">
                                <label for="author">昵称</label> <span class="required">*</span>

                                <input type="text" name="author" maxlength="12" id="author" placeholder="昵称" value="" required>

                            </p>
                            <p class="comment-form-email">
                                <label for="email">邮箱</label> <span class="required">*</span>

                                <input type="email" name="mail" id="mail" placeholder="邮箱" value="" <?php if ($this->options->commentsRequireMail): ?> required<?php endif; ?>>
                            </p>
                            <p class="comment-form-url">
                                <label for="url">网站</label>

                                <input type="url" name="url" id="url" placeholder="网站" value="" <?php if ($this->options->commentsRequireURL): ?> required<?php endif; ?>>

                            </p>
                        </div>
                    <?php endif; ?>

                    <p class="form-submit">
                        <button name="submit" type="submit" id="submit" class="submit" title="发表评论"><i class="icon iconfont icon-send"></i></button>
                        <?php $security = $this->widget('Widget_Security'); ?>
                        <input type="hidden" name="_" value="<?php echo $security->getToken($this->request->getReferer())?>">
                    </p>
                </div>
                <div class="comment-form-extra">
                    <span class="response"><?php if($this->user->hasLogin()): ?> You are <a href="<?php $this->options->profileUrl(); ?>" data-no-instant><?php $this->user->screenName(); ?></a> here, do you want to <a href="<?php $this->options->logoutUrl(); ?>" title="Logout" data-no-instant>logout</a> ?<?php endif; ?></span>

                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<script type = "text/javascript">
    (function () {
        window.TypechoComment = {
            dom : function (id) {
                return document.getElementById(id);
            },
            create : function (tag, attr) {
                var el = document.createElement(tag);
                for (var key in attr) {
                    el.setAttribute(key, attr[key]);
                }
                return el;
            },

            reply : function (cid, coid) {
                var comment = this.dom(cid), parent = comment.parentNode,
                    response = this.dom('<?php echo $this->respondId(); ?>'),
                    input = this.dom('comment-parent'),
                    form = 'form' == response.tagName ? response : response.getElementsByTagName('form')[0],
                    textarea = response.getElementsByTagName('textarea')[0];

                if (null == input) {
                    input = this.create('input', {
                        'type' : 'hidden',
                        'name' : 'parent',
                        'id'   : 'comment-parent'
                    });

                    form.appendChild(input);
                }

                input.setAttribute('value', coid);

                if (null == this.dom('comment-form-place-holder')) {
                    var holder = this.create('div', {
                        'id' : 'comment-form-place-holder'
                    });

                    response.parentNode.insertBefore(holder, response);
                }

                comment.appendChild(response);
                this.dom('cancel-comment-reply-link').style.display = '';

                if (null != textarea && 'text' == textarea.name) {
                    textarea.focus();
                }

                return false;
            },

            cancelReply : function () {
                var response = this.dom('<?php echo $this->respondId(); ?>'),
                    holder = this.dom('comment-form-place-holder'),
                    input = this.dom('comment-parent');

                if (null != input) {
                    input.parentNode.removeChild(input);
                }

                if (null == holder) {
                    return true;
                }

                this.dom('cancel-comment-reply-link').style.display = 'none';
                holder.parentNode.insertBefore(response, holder);
                return false;
            }
        };
    })();

    function getCommentCookie(name){
        var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
        if(arr=document.cookie.match(reg))
            return unescape(decodeURI(arr[2]));
        else
            return null;
    }
    function addCommentInputValue(){
        document.getElementById('author').value = getCommentCookie('<?php echo md5($this->request->getUrlPrefix()); ?>__typecho_remember_author');
        document.getElementById('mail').value = getCommentCookie('<?php echo md5($this->request->getUrlPrefix()); ?>__typecho_remember_mail');
        document.getElementById('url').value = getCommentCookie('<?php echo md5($this->request->getUrlPrefix()); ?>__typecho_remember_url');
    }
    addCommentInputValue();
</script>