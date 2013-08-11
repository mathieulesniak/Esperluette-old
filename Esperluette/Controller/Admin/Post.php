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
        $view   = new View\Admin\Post\Homepage($model);

        $this->response->setBody($view->render());
    }

    public function addPost()
    {
        $model  = new Model\Blog\Post();
        $view   = new View\Admin\Post\Edit($model);

        $this->response->setBody($view->render());
    }

    public function previewPost($postId)
    {
        if ($postId != '') {
            $model = new Model\Blog\Post();
            $model->load($postId);

            if ($model->id !== null) {
                $view = new View\Admin\Post\Preview($model);
            }
        }

        $this->response->setBody($view->render());
    }

    public function editPost($postId)
    {
        
        $tmp = new Formitem();

        if ($postId != '') {
            $model = new Model\Blog\Post();
            $model->load($postId);
            if ($model->id !== null) {

            } else {
                //Unknown post
            }
            $view = new View\Admin\Post\Edit($model);
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