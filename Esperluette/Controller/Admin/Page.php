<?php
namespace Esperluette\Controller\Admin;

use \Esperluette\Model;
use \Esperluette\View;
use \Fwk\Helper;

class Page extends \Esperluette\Controller\Base
{
    public function getPages($statusName = '',$page = '')
    {
        $model  = new Model\Blog\PageList();
        $view   = new View\Admin\PageHomepage($model);

        $this->response->setBody($view->render());
    }

    public function addPage()
    {
        $model  = new Model\Blog\Page();
        $view   = new View\Admin\Page($model);

        $this->response->setBody($view->render());
    }

    public function editPage($pageId)
    {
        if ($pageId != '') {
            $model = new Model\Blog\Page();
            $model->load($postId);
            if ($model->id !== null) {

            } else {
                //Unknown post
            }
            $view = new View\Admin\Page($model);
            $this->response->setBody($view->render());
        }
    }

    public function deletePage($pageId)
    {
        if ($pageId != '') {
            $model = new Model\Blog\Page();
            $model->load($pageId);
            if ($model->id !== null) {
                $model->delete();
            }
        }

    }
}