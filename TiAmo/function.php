<?php 
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
* 
*
*
*/

function widget_author($class,$loca,$screenName,$name,$p,$mail,$url,$social){
    
    $gravatar='https://secure.gravatar.com/avatar/'.md5($mail).'?s=100&r=G&d=mm';
    
    $author = '<aside class="'.$class.'">';
    $author .= '<div class="widget-author-inner row">';
    $author .= '<div class="author-avatar row"><img src="'.$gravatar.'" alt="" class="img-circle"></div>';
    $author .='<a href="'.$url.'"><h2 class="author-name">'.$screenName.'</h2></a><h5 class="author-title">'.$name.'</h5><p>'.$p.'</p>';
    
    //switch ($loca) {
        //case "index":
            $author .= '<ul class="nav social-nav">'.$social.'</ul>';
            
    //}
    
    $author .='</div>';
    $author .='</aside>'; 
    
    echo  $author;
}

function widget_title($describes){
    echo $describes;
}