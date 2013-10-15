<?php
namespace Fwk;

class Collection implements  \Iterator, \Countable, \ArrayAccess, Interfaces\ICollection
{
    const TABLE_NAME            = '';   // Name of SQL table containing items
    const ITEM_TYPE             = '';   // Class of items in collection
    const PARENT_ID_NAME        = 'parent_id';  // Name of the field referencing to parent_id

    protected $parent_id;           // Id of the parent
    protected $parent_type;

    protected $items            = array();
    protected $mapping          = array();

    private $sort_field;
    private $sort_order;

    private $item_offset        = 0;
    private $iterator_position  = 0;

    /**
     * Load entire table into collection
     * @return Collection Loaded collection
     */
    public static function loadAll()
    {
        $calledClass    = get_called_class();
        $collection     = new $calledClass;

        $sql            = '';
        $sqlParams      = array();

        $sql  = "SELECT *";
        $sql .= "   FROM `" . $collection::TABLE_NAME . "`";

        if ($collection->parent_type !== '' && $collection->parent_type != null) {
            $sql .= "WHERE type=:type";
            $sqlParams['type'] = $collection->parent_type;
        }

        $results = Fwk::Database()->query($sql, $sqlParams)->fetchAll();

        if ($results !== false) {
            foreach ($results as $currentResult) {
                $item_name = $collection::ITEM_TYPE;
                $collection->addItem($item_name::buildFromArray($currentResult));
            }
        }

        return $collection;
    }

    /**
     * Static wrapper for loadFromSql
     * @param  string     $sql       SQL Statement
     * @param  array      $sqlParams SQL Parameters
     * @return Collection Loaded collection
     */
    public static function buildFromSql($sql, $sqlParams = array())
    {
        $called_class = get_called_class();
        $collection = new $called_class;

        $collection->loadFromSql($sql, $sqlParams);

        return $collection;
    }

    public function loadFromSql($sql, $sqlParams = array())
    {
        $results = Fwk::Database()->query($sql, $sqlParams)->fetchAll();

        if ($results !== false) {
            foreach ($results as $currentResult) {
                $item_name = $this::ITEM_TYPE;
                $this->addItem($item_name::buildFromArray($currentResult));
            }
        }

        return $this;
    }

    public function lazyLoadFromSql($sql, $sqlParams = array())
    {
        $results = Fwk::Database()->query($sql, $sqlParams)->fetchAll();

        if ($results !== false) {
            foreach ($results as $currentResult) {
                $this->addItemLink(current($currentResult));
            }
        }

        return $this;
    }

    public static function loadForParentId($parent_id)
    {
        $called_class   = get_called_class();
        $collection     = new $called_class;

        if ($parent_id != '') {
            $sql_params     = array();
            $db_handler     = Fwk::Database(true);

            $sql  = "SELECT *";
            $sql .= " FROM `" . $collection::TABLE_NAME . "`";
            $sql .= " WHERE";
            $sql .= "   " . $collection::PARENT_ID_NAME . "=:parent_id";

            if ($collection->parent_type !== null) {
                $sql .= "   AND type=:parent_type";
                $sql_params['parent_type'] = $collection->parent_type;
            }

            $sql_params['parent_id'] = $parent_id;
            $results = $db_handler->query($sql, $sql_params)->fetchAll();

            if ($results !== false) {
                foreach ($results as $currentResult) {
                    $item_name = $collection::ITEM_TYPE;
                    $collection->addItem($item_name::buildFromArray($currentResult));
                }
            }

            $collection->parent_id = $parent_id;
        }

        return $collection;
    }

    public function setParentIdForAll($parent_id)
    {
        $this->parent_id = $parent_id;
        foreach ($this->items as $key => $current_item) {
            $this->items[$key]->{static::PARENT_ID_NAME} = $parent_id;
        }
    }

    public function craftItem($item_data)
    {
        $item_name = static::ITEM_TYPE;

        foreach ($item_data as $data) {
            $new_item = new $item_name();
            $new_item->{static::PARENT_ID_NAME} = $this->parent_id;
            $has_data = false;
            foreach ($data as $field => $value) {
                $new_item->$field = $value;
                if ($value != '') {
                    $has_data = true;
                }
            }

            // Only add item if there's data inside
            if ($has_data) {
                $this->addItem($new_item);
            }
        }

    }

    public function save()
    {
        // 1st step : delete all records for current parent_id
        $sql  = "DELETE FROM `" . static::TABLE_NAME . "`";
        if (static::PARENT_ID_NAME != '') {
            $sql .= " WHERE";
            $sql .= "   " . static::PARENT_ID_NAME . "=:parent_id";

            $sqlParams = array('parent_id' => $this->parent_id);
        } else {
            $sqlParams = array();
        }

        Fwk::Database()->query($sql, $sqlParams);

        // 2nd step : save all current items
        foreach ($this->items as $current_item) {
            $current_item->save(true); // Force insert
        }
    }

    public function purgeItems()
    {
        $this->items        = array();
        $this->mapping      = array();
        $this->item_offset  = 0;
    }


    public function sort($field, $order)
    {
        $this->sort_field   = $field;
        $this->sort_order   = $order;

        uasort($this->items, array($this, 'sortFunction'));

        return $this;
    }

    public function getPossibleValuesFor($args, $withMapping = true)
    {
        if (!is_array($args)) {
            $args = array('format' => '%s', 'data' => array($args));
        }

        if ($withMapping) {
            $class = $this::ITEM_TYPE;
            $dummy_item = new $class();
            $mapping_key = $dummy_item::TABLE_INDEX;
        }
        $values = array();
        foreach ($this->items as $key => $item) {
            $item_values = array();
            foreach ($args['data'] as $arg) {
                $item_values[] = $item->$arg;
            }
            $array_key = ( $withMapping ) ? $item->$mapping_key: $key;
            $values[$array_key] = vsprintf($args['format'], $item_values);
        }

        return $values;
    }

    public function getValuesFor($name)
    {
        $values = array();
        foreach ($this->items as $item) {
            $values[] = $item->$name;
        }

        return $values;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function __toString()
    {
        $output = '<ul>'."\n";
        foreach ($this->items as $key => $item) {
            $output .= '<li>' . $key .' => ' . $item . "</li>\n";
        }
        $output .= '</ul>'."\n";

        return $output;
    }



    private function sortFunction($a, $b)
    {
        $first  = $this->cleanStr($a->{$this->sort_field});
        $second = $this->cleanStr($b->{$this->sort_field});

        if ($first === $second) {
            return 0;
        }

        if ($this->sort_order == self::SORT_ASC) {
            return ($first < $second) ? -1 : +1;
        } else {
            return ($first < $second) ? +1 : -1;
        }
    }

    public function addItemLink($link_id)
    {
        $this->items[$this->item_offset] = $link_id;
        // add mapping between item->index and $position in items pool
        $this->mapping[$this->item_offset] = $link_id;

        $this->item_offset++;
    }

    public function addItem(DBObject $item)
    {
        $key = $item::TABLE_INDEX;
        // Add item to items pool
        $this->items[$this->item_offset] = $item;

        // add mapping between item->index and $position in items pool
        $this->mapping[$this->item_offset] = $item->$key;

        $this->item_offset++;
    }

    public function getItemsType()
    {
        return static::ITEM_TYPE;
    }

    public function getParentIdName()
    {
        return static::PARENT_ID_NAME;
    }
    public function getParentId()
    {
        return $this->parent_id;
    }

    public function getItemFromKey($key)
    {
        $invertedMapping = array_flip($this->mapping);
        if (isset($invertedMapping[$key])) {
            return $this->items[$invertedMapping[$key]];
        }
    }


    // Implementation of Countable Interface
    public function count()
    {
        return count($this->items);
    }

    // Implementation of Iterator Interface
    public function current()
    {
        return $this->offsetGet($this->iterator_position);
    }

    public function next()
    {
        ++$this->iterator_position;
    }

    public function key()
    {
        return $this->iterator_position;
    }

    public function valid()
    {
        return isset($this->items[$this->iterator_position]);
    }

    public function rewind()
    {
        $this->iterator_position = 0;
    }

    // Implementation of ArrayAccess Interface
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        $item =isset($this->items[$offset]) ? $this->items[$offset] : null;
        if (gettype($item) == 'object' || $item == null) {
            return $item;
        } else {
            $itemType = $this::ITEM_TYPE;
            $itemToLoad = new $itemType;
            $itemToLoad->load($this->items[$offset]);

            $this->items[$offset] = $itemToLoad;

            return $this->items[$offset];
        }
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    private function cleanStr($str)
    {

        $str = mb_strtolower($str, 'utf-8');
        $str = strtr(
            $str,
            array(
                'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'a'=>'a', 'a'=>'a', 'a'=>'a', 'ç'=>'c', 'c'=>'c', 'c'=>'c', 'c'=>'c', 'c'=>'c', 'd'=>'d', 'd'=>'d', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'e'=>'e', 'e'=>'e', 'e'=>'e', 'e'=>'e', 'e'=>'e', 'g'=>'g', 'g'=>'g', 'g'=>'g', 'h'=>'h', 'h'=>'h', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'i'=>'i', 'i'=>'i', 'i'=>'i', 'i'=>'i', 'i'=>'i', '?'=>'i', 'j'=>'j', 'k'=>'k', '?'=>'k', 'l'=>'l', 'l'=>'l', 'l'=>'l', '?'=>'l', 'l'=>'l', 'ñ'=>'n', 'n'=>'n', 'n'=>'n', 'n'=>'n', '?'=>'n', '?'=>'n', 'ð'=>'o', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'o'=>'o', 'o'=>'o', 'o'=>'o', 'œ'=>'o', 'ø'=>'o', 'r'=>'r', 'r'=>'r', 's'=>'s', 's'=>'s', 's'=>'s', 'š'=>'s', '?'=>'s', 't'=>'t', 't'=>'t', 't'=>'t', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'u'=>'u', 'u'=>'u', 'u'=>'u', 'u'=>'u', 'u'=>'u', 'u'=>'u', 'w'=>'w', 'ý'=>'y', 'ÿ'=>'y', 'y'=>'y', 'z'=>'z', 'z'=>'z', 'ž'=>'z'
            )
        );

        return $str;
    }

    public function getSlice($start, $nbItems = null)
    {
        return array_slice($this->items, $start, $nbItems, true);
    }

    public function getFirstItem()
    {
        foreach ($this->items as $currentItem) {
            return $currentItem;
        }
    }

    public function getRandom($nb = 1)
    {
        $keys = (array) array_rand($this->items, $nb);
        $result = array();
        foreach ($keys as $currentKey) {
            $result[$currentKey] = $this->items[$currentKey];
        }

        return $result;
    }
}
