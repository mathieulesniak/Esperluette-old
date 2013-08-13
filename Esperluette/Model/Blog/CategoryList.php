<?php
namespace Esperluette\Model\Blog;

use Esperluette\Model;

class CategoryList extends \Fwk\Collection
{
    const TABLE_NAME    = 'blog_categories';
    const ITEM_TYPE     = '\Esperluette\Model\Blog\Category';

    public function getAsArray()
    {
        $resultId   = array();
        $results    = array();

        $this->sort('name', self::SORT_ASC);

        $tree = array();
        foreach ($this->items as $currentItem) {
            $id = $currentItem->id;
            
            if (!isset($tree[$id])) {
                $tree[$id] = array();
            }

            $tree[$id][$id] = $currentItem->name;

            $parent = $currentItem->parent_id;
            if (!isset($tree[$parent])) {
                $tree[$parent] = array();
            }

            $tree[$parent][$id] =& $tree[$id];
        }

        $it = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($tree[0]));
        
        foreach ($it as $el) {
            $result[$it->key()] = str_repeat('—', $it->getDepth() - 1) . $el;
        }
        
        return $result;
    }
}
