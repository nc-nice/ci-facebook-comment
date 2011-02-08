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

$config['table_comment_name'] = 'teq_comment';

$config['table_user_name'] = 'user';
$config['table_user_primarykey'] = 'user_id';
$config['table_user_line_lastname'] = 'nom';
$config['table_user_line_firstname'] = 'prenom';
$config['table_user_line_email'] = 'email';


$config['table_tag_name'] = 'tag';
$config['table_tag_primarykey'] = 'tag_id';