<?php
namespace Esperluette\View;

use Esperluette\Model\Helper;
use Esperluette\Model\Notification;
use Fwk\Fwk;

class Admin extends \Fwk\Page
{
    protected $model;
    protected $removeTemplate = false;

    private $sections = array(
        'posts'         => array('name' => 'admin.posts', 'accessLevel' => 'editor', 'url' => '/admin/posts'),
        'comments'      => array('name' => 'admin.comments', 'accessLevel' => 'user', 'url' => '/admin/comments'),
        'categories'    => array('name' => 'admin.categories', 'accessLevel' => 'editor', 'url' => '/admin/categories')
    );
    private $section;

    public function __construct(&$model = null)
    {
        parent::__construct();
        $this->model = $model;
        /**
        TODO : handle stylesheet + scripts
         */
        $this->addStylesheet(
            'admin',
            Fwk::App()->getParameter('url') . '/'
            . Fwk::App()->getParameter('root')
            . '/View/Assets/css/admin.css'
        );

        $this->addScript(
            'zepto',
            Fwk::App()->getParameter('url') . '/'
            . Fwk::App()->getParameter('root')
            . '/View/Assets/scripts/zepto.min.js'
        );
        $this->addScript(
            'admin',
            Fwk::App()->getParameter('url') . '/'
            . Fwk::App()->getParameter('root')
            . '/View/Assets/scripts/admin.js');

    }

    private function renderNavigation()
    {
        /**
            TODO : manage users level 
         */
        $output = '<ul>'."\n";
        foreach ($this->sections as $sectionId => $sectionData) {
            $class = ($this->section == $sectionId) ? ' class="selected"' : '';
            $output .= '<li ' . $class . '>';
            $output .= '    <a href="' . $sectionData['url'] . '">' . Helper::i18n($sectionData['name'])  . '</a>';
            $output .= '</li>'."\n";
        }
        $output .= '</ul>'."\n";

        return $output;
    }

    public function render($content = '')
    {
        $output = '';

        if (!$this->removeTemplate) {
            $output  = '<header>'."\n";
            $output .=      $this->renderNavigation();
            $output .= '</header>' ."\n";

            $notifications = Notification::read();
            if ($notifications !== '') {
                // Output notifications
            }
        }


        $output .= $content;

        return parent::render($output);
    }
}