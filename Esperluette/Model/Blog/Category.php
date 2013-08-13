<?php
namespace Esperluette\Model\Blog;

use Esperluette\Model;

class Category extends \Fwk\DBObject
{
    const TABLE_NAME    = 'blog_categories';
    const TABLE_INDEX   = 'id';
    
    public function __construct()
    {
        $this->dbVariables = array(
                                'id',
                                'name',
                                'slug',
                                'description',
                                'parent_id'
                            );

        $this->protectedVariables = array(
            'posts'
            );

        $this->posts = new PostList();
    }

    protected function accessToProtectedVariable($property_name)
    {
        switch ($property_name) {
            case 'posts':
                $result = $this->loadPosts();
                break;
            default:
                $result = false;
                break;
        }
        
        return $result;
    }

    private function loadPosts()
    {
        if ($this->id != '') {
            $postList = new PostList();
            $postList->loadForCategoryId($this->id);
            $this->posts = $postList;
        }

        return true;
    }
}
