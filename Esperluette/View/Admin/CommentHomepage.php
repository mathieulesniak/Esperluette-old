<?php
namespace Esperluette\View\Admin;

use Esperluette\Model;
use Experluette\View;
use Esperluette\Model\Helper;
use Fwk\FormItem;

class CommentHomepage extends \Esperluette\View\Admin
{
    public function render($content = '')
    {   
        $output = '<ul class="commentlist">'."\n";
        foreach ($this->model as $currentComment) {
            $output .= '<li>';
            $output .= '    <a href="#">';
            $output .= '        <time>NICE TIME GOES HERE</time>';
            $output .= '    </a>';
            $output .= '</li>';
        }

        $output .= '</ul>'."\n";
        return parent::render($output);
    }
}
