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

class Teq_FacebookComponent {

    protected $CI;

    /*
     * Function __construct
     * @description     Constructor
     */
    public function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->load->database();
        $this->CI->config->load('teq_comment');
        $this->checkExistTable();
    }

    /*
     * Function get
     * @description     Get all comments (SQL query)
     * @param           $module_name, $module_id, $limit, $setAcValues
     * @return          SQL result array
     */
    public function get($module_name, $module_id, $limit=0, $setAcValues=true)
    {

        $sql_limit = $limit == 0 ? '' : 'LIMIT 0,? ';
        // Getting comments
        $sql = "";
        $sql .= "SELECT  ";
        $sql .= "C.comment_id, C.body, C.created, C.createdby, C.tag_id, ";
        $sql .= "CONCAT(U.".$this->CI->config->item('table_user_line_firstname').", CONCAT(' ', U.".$this->CI->config->item('table_user_line_lastname').")) as user_full_name, U.".$this->CI->config->item('table_user_line_email').", ";
        $sql .= "T.label as tag_label ";
        $sql .= "FROM ".$this->CI->config->item('table_comment_name')." as C ";
        $sql .= "LEFT JOIN ".$this->CI->config->item('table_user_name')." as U ON (U.".$this->CI->config->item('table_user_primarykey')." = C.createdby) ";
        $sql .= "LEFT JOIN ".$this->CI->config->item('table_tag_name')." as T ON (T.".$this->CI->config->item('table_tag_primarykey')." = C.tag_id) ";
        $sql .= "WHERE 1 ";
        $sql .= "AND C.module_name = ? ";
        $sql .= "AND C.module_id = ? ";
        $sql .= "AND C.deleted = 0 ";
        $sql .= $sql_limit;

        $res = $this->CI->db->query($sql, array($module_name, $module_id, $limit));
        $res = $res->result_array();

        if ($setAcValues)
            $this->setTagAcValues($res);

        return $res;
    }

    /*
     * Function insert
     * @description     insert new comment in BDD
     * @param           comment array()
     * @return          if SQL query works return insert_id else return false
     */
    public function insert($comment)
    {
        $res = $this->CI->db->insert($this->CI->config->item('table_comment_name'), $comment);
        if ($res !== false)
        {
            return $this->CI->db->insert_id();
        }

        return false;
    }

    /*
     * Function softDelete
     * @description     Update column deleted in comment Table at 1
     * @param           comment array()
     * @return          SQL query result
     */
    public function softDelete($comment)
    {
        $comment_id = $comment['comment_id'];

        $data = array(
            'deleted' => 1,
            'updated' => $comment['updated'],
            'updatedby' => $comment['updatedby']
        );

        $this->CI->db->where('comment_id', $comment_id);
        $res = $this->CI->db->update($this->CI->config->item('table_comment_name'), $data);

        return $res;
    }

    /*
     * Function setTagAcValues
     * @description
     * @param
     * @return
     */
    public function setTagAcValues(& $res)
    {
        foreach ($res as &$item)
        {
            if ($item['tag_id'] < 1)
            {
                $item['tag_id_acvalue'] = '[]';
            }
            else
            {
                $item['tag_id_acvalue'] = teq_acvalue(array($item['tag_id'] => $item['tag_label']));
            }
        }
    }

    /*
     * Function checkExistTable
     * @description     Check if comment table exist in BDD, if not, create it
     * @return          if table already exist return true, else return string "db table created"
     */
    public function checkExistTable()
    {
        $query = "SHOW TABLES FROM ".$this->CI->db->database." WHERE `Tables_in_".$this->CI->db->database."` = '".$this->CI->config->item('table_comment_name')."'";
        $res = $this->CI->db->query($query)->num_rows();
        if($res > 0)
        {
            return true;
        } 
        else
        {
            $sql = "CREATE TABLE IF NOT EXISTS `".$this->CI->config->item('table_comment_name')."` (
              `comment_id` int(11) NOT NULL AUTO_INCREMENT,
              `module_id` int(11) NOT NULL,
              `module_name` varchar(10) NOT NULL,
              `tag_id` int(11) NOT NULL,
              `body` text NOT NULL,
              `created` datetime NOT NULL,
              `createdby` int(11) NOT NULL,
              `updated` datetime NOT NULL,
              `updatedby` int(11) NOT NULL,
              `relatedto` int(11) NOT NULL DEFAULT '-1',
              `deleted` tinyint(4) NOT NULL DEFAULT '0',
              PRIMARY KEY (`comment_id`),
              UNIQUE KEY `module_name_id` (`module_id`,`module_name`,`comment_id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

            $res = $this->CI->db->query($sql);
            return 'db table created';
        }
    }
    
}

?>