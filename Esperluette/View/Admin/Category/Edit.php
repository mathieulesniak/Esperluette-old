<?php
namespace Esperluette\View\Admin\Category;

use Esperluette\Model;
use Esperluette\View;
use Esperluette\Model\Helper;
use Fwk\Fwk;
use Fwk\FormItem;


class Edit extends \Esperluette\View\Admin
{
    protected $section = 'category';

    public function render($content = '')
    {
        $formValues = array(
            'name'          => Fwk::Request()->getPostParam('name', $this->model->name),
            'slug'          => Fwk::Request()->getPostParam('slug', $this->model->slug),
            'description'   => Fwk::Request()->getPostParam('description', $this->model->description),
            'parent_id'     => Fwk::Request()->getPostParam('parent_id', $this->model->parent_id),
        );

print_r($formValues);
        if ($this->model->id !== null) {
            $output  = '<h1>' . Helper::i18n('admin.categories.edit') . '</h1>';
        } else {
            $output  = '<h1>' . Helper::i18n('admin.categories.add') . '</h1>';
        }

        $output .= '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">'."\n";
        $output .= '    <p>';
        $output .=          FormItem::text('name', $formValues['name'], Helper::i18n('admin.categories.category_name'));
        $output .= '    </p>';
        $output .= '    <p>';
        $output .=          FormItem::text('slug', $formValues['slug'], Helper::i18n('admin.categories.slug'));
        $output .= '    </p>';
        $output .= '    <p>';
        $output .=          FormItem::textarea('description', $formValues['description'], Helper::i18n('admin.categories.description'));
        $output .= '    </p>';
        $output .= '    <p>';
        $output .=          FormItem::select(
            'parent_id',
            array(),
            $formValues['parent_id'],
            Helper::i18n('admin.categories.parent_id')
        );
        $output .= '    </p>';
        $output .=      FormItem::submit('save_category', Helper::i18n('admin.categories.save'));
        $output .= '</form>';
        
        return parent::render($output);
    }
}
