<?php
namespace Esperluette\View\Admin\Category;

use Esperluette\Model;
use Esperluette\View;
use Esperluette\Model\Helper;


class Homepage extends \Esperluette\View\PaginatedAdmin
{
    protected $section = 'category';

    public function render($content = '')
    {
        $output  = '<h1>' . Helper::i18n('admin.categories') . '</h1>';
        $output .= '<div class="action">';
        $output .= '    <a href="' . Helper::url('/admin/categories/add') . '">' . Helper::i18n('admin.categories.add') . '</a>';
        $output .= '</div>';
        
        if (count($this->model)) {
            $output .= '<ul>';
            foreach ($this->model as $currentCategory) {
                $output .= '<li>';
                $output .= $currentCategory->name;
                $output .= '</li>';
            }
            $output .= '</ul>';
        }
        $output .= $this->renderPagination();
        
        return parent::render($output);
    }
}
