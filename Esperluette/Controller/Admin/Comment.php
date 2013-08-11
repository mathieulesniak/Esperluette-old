<?php
namespace Esperluette\Controller\Admin;

use \Esperluette\Model;
use \Esperluette\View;

class Comment extends \Esperluette\Controller\Base
{    
    public function getComments($statusName = '', $page = '')
    {
        $model  = new Model\Comment\CommentList();
        $view   = new View\Admin\Comment\Homepage($model);

        $this->response->setBody($view->render());
    }

    public function editComment($commentId)
    {
        if ($commentId != '') {
            $model = new Model\Comment\Comment();
            $model->load($commentId);
            if ($model->id !== null) {

            } else {
                // Unknown comment
            }
            $view = new View\Admin\Comment\Edit($model);
            $this->response->setBody($view->render());
        }
    }

    public function deleteComment($commentId)
    {
        if ($commentId != '') {
            $comment = new Model\Comment\Comment();
            $comment->load($commentId);
            if ($comment->id !== null) {
                $comment->delete();
            }
        }
    }
}