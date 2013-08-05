<?php
namespace Esperluette\Controller\Admin;

use \Esperluette\Model;
use \Esperluette\View;
use \Fwk\Helper;

class User extends \Esperluette\Controller\Base
{
    public function getUsers($page = '')
    {
        $model  = new Model\Blog\UserList();
        $view   = new View\Admin\UserHomepage($model);

        $this->response->setBody($view->render());
    }

    public function addUser()
    {
        $model  = new Model\User\User();
        $view   = new View\Admin\User($model);

        $this->response->setBody($view->render());
    }

    public function editUser($userId)
    {
        if ($userId != '') {
            $model = new Model\User\User();
            $model->load($userId)
            if ($model->id !== null) {

            } else {
                //Unknown post
            }
            $view = new View\Admin\User($model);
            $this->response->setBody($view->render());
        }
    }

    public function deleteUser($userId)
    {
        if ($userId != '') {
            $model = new Model\Blog\User();
            $model->load($userId);
            if ($model->id !== null) {
                $model->delete();
            }
        }

    }
}