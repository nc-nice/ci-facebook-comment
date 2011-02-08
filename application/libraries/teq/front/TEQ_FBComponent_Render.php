<?php
/**
 * TequilaRapido Facebook Component for CodeIgniter
 *
 * Intregrate Facebook comment in your CodeIngniter easly with the module
 * TEQ_FacebookComponent by TequilaRapido.
 *
 * @access       public
 * @author       TequilaRapido <author@email>
 * @copyright    TequilaRapido 2011
 * @example      /path/to/example
 * @exception    Javadoc-compatible, use as needed
 * @link         URL
 * @magic        phpdoc.de compatibility
 * @package      teq
 * @since        02/2011
 * @version      1.0
 */


if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . "/gravatar_helper.php");

class TEQ_FBComponent_Render {
    protected $CI;

    /*
     * Function __construct
     * @description     Constructor
     */
    public function __construct()
    {
        $this->CI = & get_instance();
    }

    /*
     * Function headers
     * @description     include CSS and JS files in HTML page
     * @return          string HTML balise
     */
    public function headers()
    {
        $headers = '
        <link media="screen" type="text/css" href="../application/libraries/teq/front/css/styles.css" rel="stylesheet">
        
        <script charset="utf-8" type="text/javascript" src="../application/libraries/teq/front/js/jquery-1.4.4.min.js"></script>
        <script charset="utf-8" type="text/javascript" src="../application/libraries/teq/front/js/jquery.timeago.js"></script>
        <script charset="utf-8" type="text/javascript" src="../application/libraries/teq/front/js/jquery.tipsy.js"></script>
        <script charset="utf-8" type="text/javascript" src="../application/libraries/teq/front/js/jquery.autogrow-textarea.js"></script>
        <script charset="utf-8" type="text/javascript" src="../application/libraries/teq/front/js/TEQ_FBComponent_Jscript.js"></script>
        ';

        return $headers;
    }

    /*
     * function html
     * @description     include comment list and form comment in HTML page
     * @param           comments array
     * @need            function headers, function js
     * @return          string HTML balise
     */
    public function html($comments)
    {
        $html = $this->headers();
        $html .= '<div id="flash"></div>';
        $html .= '<div id="teq-comments" class="teq-comments">';
        $html .= '<ul class="teq-comments-list">';

        if(!empty($comments))
        {
            foreach ($comments as $comment) :
                $gravatar = gravatar($comment['email'], 'X', 32);
                $html .= '
                    <li class="teq-comment-item">
                        <div class="teq-comment-item-container">
                            <div class="teq-comment-item-delete nodisplay">
                                <a rel="toggle" title="Delete" class="teq-comment-item-delete-btn" data-comment_id="'.$comment['comment_id'].'" >
                                    Delete
                                </a>
                            </div>
                            <div class="teq-comment-gravatar">
                                <img class="teq-comment-gravatar-img" alt="TequilaRapido" src="'.$gravatar.'" />
                            </div>
                            <div class="teq-comment-bloc">
                                <span class="teq-comment-name">'.$comment['user_full_name'].'</span>
                                <span class="teq-comment-text">'.nl2br($comment['body']).'</span>
                                <div class="teq-comment-date">
                                    <abbr class="timeago" title="'.date("c", strtotime($comment['created'])).'">'.$comment['created'].'</abbr>
                                </div>
                            </div>
                        </div>
                    </li>
                ';
            endforeach;
        }

        //$gravatar = gravatar($this->CI->session->userdata('email'), 'X', 32);
        $gravatar = 'http://gravatar.com/avatar.php?gravatar_id=5d2ab24de804420c3c1bc0bc4ab721f3&rating=X&size=32&default=http://gravatar.com/avatar.php';

        $html .= '
            <li class="teq-comment-addcomment">
                <div id="teq-comment-form">
                    <img id="commentUserImg" class="nodisplay teq-addcomment-gravatar-img"  src="'.$gravatar.'">
                    <div class="teq-comment-area">
                        <div class="teq-comment-box">
                            <textarea id="commentBody" name="comment[body]">Your comment...</textarea>
                        </div>
                        <label id="commentBtnLabel"  class="nodisplay  teq-comment-btn">
                            <input id="commentBtn" type="submit" name="comment" value="Add">
                            <span id="commentBtnSpinner" class="nodisplay"></span>
                        </label>
                    </div>
                    <div class="clear"></div>
                </div>
            </li>
        ';

        $html .= '</ul>';
        $html .= '</div>';

        return $html;
        
    }

    /*
     * Function js
     * @description     include js file in HTML page
     * @param           array()=> module_name, module_id, createdby, url_add_comment, url_delete_comment
     * @return          string HTML balise
     */
    public function js($param)
    {
        
        $js = '<script type="text/javascript">';
            $js .= 'TEQ_FBComponent_JS(\''.$param['module_name'].'\', \''.$param['module_id'].'\', \''.$param['createdby'].'\', \''.$param['url_add_comment'].'\', \''.$param['url_delete_comment'].'\');';
        $js .= '</script>';

        return $js;
    }


    
}

?>
