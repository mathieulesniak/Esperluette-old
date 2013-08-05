<?php
namespace Esperluette\Controller\Admin;

use \Esperluette\Model;
use \Esperluette\View;

class Category extends \Esperluette\Controller\Base
{
    public function getCategories($page = '')
    {
        $model = Model\Blog\CategoryList();
        $view   = new View\Admin\CategoryHomepage($model);

        $this->response->setBody($view->render());
    }

    public function addCategory()
    {
        $model  = new Model\Blog\Category();
        $view   = new View\Admin\Category($model);
        $this->response->setBody($view->render());
    }

    public function editCategory($categoryId)
    {
        if ($categoryId != '') {
            $model = new Model\Blog\Category();
            $model->load($categoryId);
            if ($model->id !== null) {

            } else {
                // Unknown comment
            }
            $view = new View\Admin\Category($model);
            $this->response->setBody($view->render());
        }
    }

    function deleteCategory($categoryId)
    {
        if ($categoryId != '') {
            $model = Model\Blog\Category();
            $model->load($categoryId);
            if ($model->id !== null) {
                $model->delete();
            }
        }
    }
}