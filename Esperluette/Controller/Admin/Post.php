<?php
namespace Esperluette\Controller\Admin;

use \Esperluette\Model;
use \Esperluette\View;
use \Fwk\Helper;
use \Fwk\FormItem;

class Post extends \Esperluette\Controller\Base
{
    public function getPosts($categoryName = '',$page = '')
    {
        if ($categoryName != '') {
            $model  = new Model\Blog\PostList();
            $model->loadForCategorySlug(urldecode($categoryName));
        } else {
            $model = Model\Blog\PostList::loadAll();
        }
        $view   = new View\Admin\PostHomepage($model);

        $this->response->setBody($view->render());
    }

    public function addPost()
    {
        $model  = new Model\Blog\Post();
        $view   = new View\Admin\Post($model);

        $this->response->setBody($view->render());
    }

    public function previewPost($postId)
    {
        if ($postId != '') {
            $model = new Model\Blog\Post();
            $model->load($postId);

            if ($model->id !== null) {
                $view = new View\Admin\PostPreview($model);
            }
        }

        $this->response->setBody($view->render());
    }

    public function editPost($postId)
    {
        echo 'TOTOT';
        echo FormItem::date('test', '123', 'mon champ');

        $tmp = new Formitem();

        echo "DEBUG " . $tmp->toto;

        if ($postId != '') {
            $model = new Model\Blog\Post();
            $model->load($postId);
            if ($model->id !== null) {

            } else {
                //Unknown post
            }
            $view = new View\Admin\Post($model);
            $this->response->setBody($view->render());
        }
    }

    public function deletePost($postId)
    {
        if ($postId != '') {
            $model = new Model\Blog\Post();
            $model->load($postId);
            if ($model->id !== null) {
                $model->delete();
            }
        }

    }
}