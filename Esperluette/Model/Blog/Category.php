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
    }
}
