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

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Facebook Comment Demo</title>
    </head>
    <body id="container">
        <div class="container">
            <h1>Facebook Comment Demo</h1>
            <?php
            /*
             * Declare necessary parameters
             * @module_name = what is commented
             * @module_id = id is commented
             * @limit = comments number
             * @createdby = user session id
             */
            $CommentParam = array(
                'module_name' => 'My Module',
                'module_id' => 0,
                'limit' => 0,
                'createdby' => 26
            );

            /* Html view of TEQ_FacebookComponent */
            echo $teq_component->renderHtml($CommentParam);
            ?>
        </div>
    </body>
</html>
