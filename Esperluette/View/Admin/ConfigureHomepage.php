<?php
namespace Esperluette\View\Admin;

use Esperluette\Model;
use Experluette\View;
use Esperluette\Model\Helper;
use Fwk\FormItem;
use Esperluette\Model\Config;


class ConfigureHomepage extends \Esperluette\View\Admin
{
    protected $section = 'setup';

    public function render($content = '')
    {   
        // Read notification of result
        
        $output  = '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">'."\n";

        // Site config
        $output .= '<fieldset>';
        $output .= '    <legend>' . Helper::i18n('admin.setup.site') . '</legend>';
        $output .= '    <p>' . FormItem::text('config[site_name]', Config::get('site_name'), Helper::i18n('admin.setup.site_name')) . '</p>';
        $output .= '    <p>' . FormItem::textarea('config[site_description]', Config::get('site_description'), Helper::i18n('admin.setup.site_description')) . '</p>';
        // Front page
        
        $output .= '    <p>' . FormItem::text('config[admin_email]', Config::get('admin_email'), Helper::i18n('admin.setup.admin_email')) . '</p>';
        // language
        // date format
        // timezone
        // time format
        $output .= '    <p>' . FormItem::text('config[posts_per_page]', Config::get('posts_per_page'), Helper::i18n('admin.setup.post_per_page')) . '</p>';
        $output .= '</fieldset>';

        // Comments
        $output .= '<fieldset>';
        $output .= '    <legend>' . Helper::i18n('admin.setup.comments') . '</legend>';
        $output .= '    <p>' . FormItem::checkbox('config[comments_enabled]', 1, Config::get('comments_enabled'), Helper::i18n('admin.setup.comments_enabled')) . '</p>';
        $output .= '    <p>' . FormItem::checkbox('config[comments_name_email_required]', 1, Config::get('comments_name_email_required'), Helper::i18n('admin.setup.comments_name_email_required')) . '</p>';
        $output .= '    <p>' . FormItem::text('config[comments_autoclose_after]', Config::get('comments_autoclose_after'), Helper::i18n('admin.setup.comments_autoclose_after')) . '</p>';
        $output .= '    <p>' . FormItem::select(
            'config[comments_order]',
            array('ASC' => Helper::i18n('admin.setup.comments_order_asc'), 'DESC' => Helper::i18n('admin.setup.comments_order_desc')),
            Config::get('comments_order'),
            Helper::i18n('admin.setup.comments_autoclose_after')
        ) . '</p>';
        
        $output .= '    <p>' . FormItem::checkbox('config[comments_autoallow]', 1, Config::get('comments_autoallow'), Helper::i18n('admin.setup.comments_autoallow')) . '</p>';
        $output .= '    <p>' . FormItem::checkbox('config[comments_notify]', 1, Config::get('comments_notify'), Helper::i18n('admin.setup.comments_notify')) . '</p>';
        $output .= '    <p>' . FormItem::number('config[comments_hold_links_nb]', Config::get('comments_hold_links_nb'), Helper::i18n('admin.setup.comments_hold_links_nb'), array('step' => 1, 'min' => 0)) . '</p>';
        $output .= '    <p>' . FormItem::textarea('config[comments_wordlist_hold]', Config::get('comments_wordlist_hold'), Helper::i18n('admin.setup.comments_wordlist_hold')) . '</p>';
        $output .= '    <p>' . FormItem::textarea('config[comments_wordlist_spam]', Config::get('comments_wordlist_spam'), Helper::i18n('admin.setup.comments_wordlist_spam')) . '</p>';
        $output .= '</fieldset>';

        // Themes
        $output .= '<fieldset>' . "\n";;
        $output .= '    <legend>' . Helper::i18n('admin.setup.themes') . '</legend>'."\n";
        // Theme selector
        $output .= '</fieldset>' . "\n";

        $output .= FormItem::submit('save_configuration', Helper::i18n('admin.setup.save'));
        
        return parent::render($output);
    }
}
