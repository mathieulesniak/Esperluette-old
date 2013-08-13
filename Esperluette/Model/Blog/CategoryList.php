<?php
namespace Esperluette\Model\Blog;

use Esperluette\Model;

class CategoryList extends \Fwk\Collection
{
    const TABLE_NAME    = 'blog_categories';
    const ITEM_TYPE     = '\Esperluette\Model\Blog\Category';

    private $tree;

    public static function loadAllSorted()
    {
        $sql  = "SELECT *";
        $sql .= "   FROM `" . self::TABLE_NAME . "`";
        $sql .= "   ORDER BY parent_id ASC, name ASC";
        $sqlParams  = array();

        return parent::buildFromSql($sql, $sqlParams);
    }

    public function generateTree()
    {
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

        $this->tree = $tree[0];
        
        $it = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($this->tree));
        
        foreach ($it as $el) {
              $this->getItemFromKey($it->key())->depth = $it->getDepth() - 1;
        }

        return $this;
    }

    public function getAsArray()
    {
        foreach ($this->items as $currentItem) {
            $result[$currentItem->id] = str_repeat('—', $currentItem->depth) . $currentItem->name;
        }
        
        return $result;
    }
}
