<?php
namespace Esperluette\Controller\Admin;

use Esperluette\Model;
use Esperluette\View;
use Esperluette\Model\Helper;
use Esperluette\Model\Notification;
use Fwk\Fwk;
use Fwk\Validator;

class Post extends \Esperluette\Controller\Base
{
    public function getPosts($categoryName = '', $page = null)
    {
        if ($page == null) {
            $page = 1;
        }

        if ($categoryName != '') {
            $model  = new Model\Blog\PostList();
            $model->loadForCategorySlug(urldecode($categoryName));
        } else {
            $model = Model\Blog\PostList::loadAll();
        }
        $subModel = $model->getSlice(($page - 1) * ADMIN_NB_POSTS_PER_PAGE, ADMIN_NB_POSTS_PER_PAGE);

        $view   = new View\Admin\Post\Homepage($subModel);
        $view
            ->setCurrentPage($page)
            ->setNbItems(count($model))
            ->setNbPerPage(ADMIN_NB_POSTS_PER_PAGE)
            ->setUrl(Helper::url('/admin/posts'));

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

    public function editPost($postId = null)
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