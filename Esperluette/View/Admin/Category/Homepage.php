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
        $output .= '    <a href="' . Helper::url('/admin/categories/add') . '" class="btn btn-primary">' . Helper::i18n('admin.categories.add') . '</a>';
        $output .= '</div>';
        /**
         TODO : add js confirm
         */
        if (count($this->model)) {
            $output .= '<div class="well">';
            $output .= '<ul class="category-list">';
            foreach ($this->model as $currentCategory) {
                $output .= '<li>';
                $output .= '    <div class="name">' . str_repeat('—', $currentCategory->depth) . $currentCategory->name . '</div>';
                $output .= '    <div class="count">' . count($currentCategory->posts) . '</div>';
                $output .= '    <div class="action">';
                $output .= '        <a href="' . Helper::url('/admin/categories/edit/' . $currentCategory->id) . '" class="btn btn-info">' . Helper::i18n('admin.categories.edit') . '</a>';
                $output .= '        <a href="' . Helper::url('/admin/categories/delete/' . $currentCategory->id) . '" class="btn btn-danger" onclick="return confirm(\'message\')">' . Helper::i18n('admin.categories.delete') . '</a>';
                $output .= '    </div>';
                $output .= '</li>';
            }
            $output .= '</ul>';
            $output .= $this->renderPagination();
            $output .= '</div>';
        }
        
        
        return parent::render($output);
    }
}
