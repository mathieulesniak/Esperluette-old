<?php
namespace Esperluette\Model\Blog;

use Esperluette\Model;

class Post extends \Fwk\DBObject
{
    const TABLE_NAME    = 'blog_posts';
    const TABLE_INDEX   = 'id';

    const STATUS_DRAFT      = 2;
    const STATUS_PUBLISHED  = 1;
    const STATUS_OFFLINE    = 0;

    private $statusList = array(
        self::STATUS_OFFLINE    => 'offline',
        self::STATUS_PUBLISHED  => 'published',
        self::STATUS_DRAFT      => 'draft'
    );
    public function __construct()
    {
        $this->dbVariables = array(
                                'id',
                                'category_id',
                                'title',
                                'slug',
                                'author_id',
                                'intro',
                                'content',
                                'date',
                                'status',
                                'comments',
                            );
        $this->protectedVariables = array(
                                        'owner',
                                        'category',
                                        'tags',
                                        'comments_list'
                            );

        $this->owner                = new Model\User\User();
        $this->category             = new Category();
        $this->tags                 = new TagList();
        $this->comments_list        = new Model\Comment\CommentList();
    }

    protected function accessToProtectedVariable($property_name)
    {
        switch ($property_name) {
            case 'owner':
                $result = $this->loadOwner();
                break;
            case 'owner_name':
                $this->owner_name = $this->owner->name;
                $result = true;
                break;
            case 'category':
                $result = $this->loadCategory();
                break;
            case 'tags':
                $result = $this->loadTags();
                break;
            case 'comments_list':
                $result = $this->loadComments();
                break;
            default:
                $result = false;
                break;
        }
        
        return $result;
    }

    private function loadOwner()
    {
        if ($this->author_id != '') {
            $owner = new Model\User\User();
            $owner->load($this->author_id);
            $this->owner = $owner;
        }

        return true;
    }
    
    private function loadCategory()
    {
        $category = new Category();
        $category->load($this->cat_id);
        $this->category = $category;

        return true;
    }

    private function loadTags()
    {
        $this->tags = TagList::loadForParentId($this->id);

        return true;
    }

    private function loadComments()
    {
        $this->comments_list = Model\Comment\CommentList::loadForParentId($this->id)->sort('date_added', 'ASC');

        return true;
    }

    public function loadFromSlug($slug)
    {
        $sql  = "SELECT *";
        $sql .= " FROM `" . self::TABLE_NAME . "`";
        $sql .= " WHERE";
        $sql .= "   slug = :slug";

        $sqlParams = array('slug' => $slug);
        $this->loadFromSql($sql, $sqlParams);
    }

    public function getURL()
    {
        
    }

    public function getStatus()
    {
        return $this->statusList[$this->status];
    }

    private function parse($content)
    {
        $markdownParser = new Model\Markdown\MarkdownExtraParser();
        
        return $markdownParser->transformMarkdown($content);
    }

    public function renderIntro()
    {
        return $this->parse($this->intro);
    }

    public function renderContent()
    {
        return $this->parse($this->content);
    }

    public function commentsEnabled()
    {
        $commentAutoclose = Config::get('comments_autoclose_after');
        if ($commentAutoclose) {
            return $this->comments && ((time() - $commentAutoclose) <= strtotime($this->date));
        } else {
            return $this->comments;
        }
    }

    public function delete()
    {
        if ($this->id !== null) {
            parent::delete();
            foreach ($this->comments as $currentComment) {
                $currentComment->delete();
            }
        }
        
    }
}
