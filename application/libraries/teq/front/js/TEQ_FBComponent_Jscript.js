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
function TEQ_FBComponent_JS(module_name, module_id, created_by, url_addcomment, url_delete) {

        var
        isDeletePending = false,
        placeholderText = 'Your comment...',
        elCommentBtnLabel = $('#commentBtnLabel'),
        elCommentBody= $('#commentBody'),
        elCommentUserImg = $('#commentUserImg'),
        elCommentBtn = $('#commentBtn'),
        elCommentBtnSpinner = $('#commentBtnSpinner'),

        // Spinner fragement
        spinnerHtml = '',
        spinner = $(spinnerHtml);

        // Time ago
        $("abbr.timeago").timeago().tipsy({fade: true, gravity: 'n'});;

        // Textarea autogrow
        $(elCommentBody).autoResize({});

        // Focus in
        $(elCommentBody).focus(function(){
            $(elCommentUserImg).show();
            $(elCommentBtnLabel).show();
            $(elCommentBody).val('');
            $(elCommentBody).width('342px');
        });

        // Focus out
        $(elCommentBody).focusout(function(){
            if(elCommentBody.val() != '') return;

            $(elCommentUserImg).hide();
            $(elCommentBtnLabel).hide();
            $(elCommentBody).val(placeholderText);

            $(elCommentBody).height(22);
            $(elCommentBody).width('100%');
        })

        // Inserting comment
        $(elCommentBtn).click(function(){
            if(elCommentBody.val() == '') return;

            elCommentBtn.hide();
            elCommentBtnSpinner.show();

            $.ajax({
                url: url_addcomment,
                type : 'post',
                dataType:'text',
                data:{
                    'module_id': module_id,
                    'module_name': module_name,
                    'body': $(elCommentBody).val(),
                    'tag_id': -1,
                    'relatedto': -1,
                    'createdby' : created_by
                },
                error:function() {

                    return false;
                    elCommentBtn.show();
                    elCommentBtnSpinner.hide();
                },
                success:function(data) {

                    if(data == 'ERROR')
                    {
                        return false;
                        elCommentBtn.show();
                        elCommentBtnSpinner.hide();
                    }
                    else
                    {
                        $('#teq-comments').replaceWith(data);
                    }
                }
            });
        })


        //
        // Suppression des commentaires
        // @todo: La suppression ne doit etre possible que si role autorise
        // ou utilisateur qui a cree le
        //
        $("#teq-comments").delegate("li.teq-comment-item", "hover", function(){
            if(isDeletePending) return;
            $(this).find('div.teq-comment-item-delete').show();
        });

        $("#teq-comments").delegate("li.teq-comment-item", "mouseleave", function(){
            if(isDeletePending) return;
            $(this).find('div.teq-comment-item-delete').hide();
        });

        $("a.teq-comment-item-delete-btn").click(function(){
            if(confirm('Are you sure to delete this comment ?')) {
                
                var self = $(this);

                self.hide();
                self.parent().append(spinner);
                var comment_id = $(this).data('comment_id');

                isDeletePending = true;

                $.ajax({
                    url:url_delete,
                    type : 'post',
                    dataType:'html',
                    data:{
                        'module_id': module_id,
                        'module_name': module_name,
                        'body': $(elCommentBody).val(),
                        'tag_id': -1,
                        'relatedto': -1,
                        'createdby' : created_by,
                        'comment_id': comment_id
                    },
                    success:function(data) {
                        isDeletePending = false;
                        self.show();

                        if(data == 'ERROR')
                        {
                            return false;
                            spinner.remove();
                        }
                        else
                        {
                            $('#teq-comments').replaceWith(data);
                        }
                    }
                });
            }
        });

}