<?php
namespace Esperluette\Controller;

use \Esperluette\Model;
use \Esperluette\View;
use \Fwk\Registry;

class Blog extends Base
{
    public function getPost($postName)
    {
        // Load model
        $model = new Model\Blog\Post();
        $model->load(1);
        Registry::set('post', $model);
        // Set Model data into registry
        
        $view = new View\Template('post');
        $this->response->setBody($view->render());
    }

    public function getPostsByTag($tagName)
    {
        $collection = new Model\Blog\PostList();
        $model = $collection->loadAll();

        Registry::set('posts', $model);
        $view = new View\Template('posts');
        if ($view->exists('posts-' . $tagName)) {
            $view->setTemplate('posts-' . $tagName);
        }
        $this->response->setBody($view->render());
    }

    public function getPostsByCategory()
    {
        $view = new View\Template('posts');
        if ($view->exists('posts-' . $category)) {
            $view->setTemplate('posts-' . $category);
        }
        $this->response->setBody($view->render());
    }

    public function getPostsByDate()
    {
        $view = new View\Template('posts');
        $this->response->setBody($view->render());
    }
}
