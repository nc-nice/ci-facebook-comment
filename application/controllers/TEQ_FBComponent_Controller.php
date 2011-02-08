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

class TEQ_FBComponent_Controller extends CI_Controller {

    /*
     * Function __construct
     * @description     Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('teq/server/Teq_FacebookComponent');
    }

    /*
     * Function index
     * @description     load "TEQ_FBComponent_Demo" view, new TEQ_FBComponent_Controller object
     */
    function index()
    {
        $data['teq_component'] = $this;
        $this->load->view('TEQ_FBComponent_Demo',$data);
    }

    /*
     * Function PostComment
     * @description     catch comment POST informations
     * @return          comment array
     */
    public function postComment()
    {
        /* Comment array with Post values */
        $comment = array(
            'module_id' => $_POST['module_id'],
            'module_name' => $_POST['module_name'], 
            'body' => $_POST['body'],
            'tag_id' => $_POST['tag_id'],
            'relatedto' => $_POST['relatedto'],
            'createdby' => $_POST['createdby'],
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
            'updatedby' => $_POST['createdby']
        );

        return $comment;
    }

    /*
     * Function add
     * @description     Insert new comment in BDD
     * @use             postComment() for comment POST information
     * @return          comments HTML
     */
    public function add()
    {
        $comment = $this->postComment();
        
        /* Insert comment */
        if($this->teq_facebookcomponent->insert($comment) != 'false')
        {
            $comment['limit'] = 0;
            $comment['url_add_comment'] = $this->config->site_url('/TEQ_FBComponent_Controller/add');
            $comment['url_delete_comment'] = $this->config->site_url('/TEQ_FBComponent_Controller/delete');

            $getComments = $this->renderHtml($comment);
            echo $getComments;
        } 
        else
        {
            echo 'ERROR';
        }
    }

    /*
     * Function Delete
     * @description     Update column deleted in comment Table at 1
     * @return          comments HTML
     */
    public function delete()
    {
        $comment = $this->postComment();
       
        $comment['comment_id'] = $_POST['comment_id'];

        /* Delete comment */
        if($this->teq_facebookcomponent->softDelete($comment))
        {
            $comment['limit'] = 0;
            $comment['url_add_comment'] = $this->config->site_url('/TEQ_FBComponent_Controller/add');
            $comment['url_delete_comment'] = $this->config->site_url('/TEQ_FBComponent_Controller/delete');

            $getComments = $this->renderHtml($comment);
            echo $getComments;
        } 
        else
        {
            echo 'ERROR';
        }
    }

    /*
     * Function renderHtml
     * @param       array()=> module_name, module_id, limit, createdby
     * @need        TEQ_FBComponent_Render library
     * @return      headers, HTML, javascript
     *
     */
    public function renderHtml($param)
    {
        $this->load->library('teq/front/TEQ_FBComponent_Render');

        $comments = $this->teq_facebookcomponent->get($param['module_name'], $param['module_id'], $param['limit']);

        $res = $this->teq_fbcomponent_render->html($comments);

        $param['url_add_comment'] = $this->config->site_url('/TEQ_FBComponent_Controller/add');
        $param['url_delete_comment'] = $this->config->site_url('/TEQ_FBComponent_Controller/delete');

        $res .= $this->teq_fbcomponent_render->js($param);

        return $res;
    }


}

?>
