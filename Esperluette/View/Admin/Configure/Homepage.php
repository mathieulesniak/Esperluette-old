<?php
namespace Esperluette\View\Admin\Configure;

use Esperluette\Model;
use Esperluette\Model\Theme;
use Esperluette\Model\Blog\CategoryList;
use Esperluette\View;
use Esperluette\Model\Helper;
use Fwk\FormItem;
use Fwk\Fwk;
use Esperluette\Model\Config;


class Homepage extends \Esperluette\View\Admin
{
    protected $section = 'setup';

    public function render($content = '')
    {
        $formValues = array(
            'site_name'                     => Fwk::Request()->getPostParam('site_name', Config::get('site_name')),
            'site_description'              => Fwk::Request()->getPostParam('site_description', Config::get('site_description')),
            'admin_email'                   => Fwk::Request()->getPostParam('admin_email', Config::get('admin_email')),
            'language'                      => Fwk::Request()->getPostParam('language', Config::get('language')),
            'posts_default_category'        => Fwk::Request()->getPostParam('posts_default_category', Config::get('posts_default_category')),
            'posts_per_page'                => Fwk::Request()->getPostParam('posts_per_page', Config::get('posts_per_page')),
            'comments_enabled'              => Fwk::Request()->getPostParam('comments_enabled', Config::get('comments_enabled')),
            'comments_name_email_required'  => Fwk::Request()->getPostParam('comments_name_email_required', Config::get('comments_name_email_required')),
            'comments_autoclose_after'      => Fwk::Request()->getPostParam('comments_autoclose_after', Config::get('comments_autoclose_after')),
            'comments_order'                => Fwk::Request()->getPostParam('comments_order', Config::get('comments_order')),
            'comments_autoallow'            => Fwk::Request()->getPostParam('comments_autoallow', Config::get('comments_autoallow')),
            'comments_notify'               => Fwk::Request()->getPostParam('comments_notify', Config::get('comments_notify')),
            'comments_hold_links_nb'        => Fwk::Request()->getPostParam('comments_hold_links_nb', Config::get('comments_hold_links_nb')),
            'comments_wordlist_spam'        => Fwk::Request()->getPostParam('comments_wordlist_spam', Config::get('comments_wordlist_spam')),
            'comments_wordlist_hold'        => Fwk::Request()->getPostParam('comments_wordlist_hold', Config::get('comments_wordlist_hold')),
            'theme'                         => Fwk::Request()->getPostParam('theme', Config::get('theme')),
        );

        $output  = '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">'."\n";

        // Site config
        $output .= '<fieldset>';
        $output .= '    <legend>' . Helper::i18n('admin.setup.site') . '</legend>';
        
        $output .= '<p>';
        $output .= FormItem::text('site_name', $formValues['site_name'], Helper::i18n('admin.setup.site_name'));
        $output .= '</p>';

        $output .= '<p>';
        $output .= FormItem::textarea('site_description', $formValues['site_description'], Helper::i18n('admin.setup.site_description'));
        $output .= '</p>';
        

        // Front page
        
        $output .= '    <p>' . FormItem::text('admin_email', $formValues['admin_email'], Helper::i18n('admin.setup.admin_email')) . '</p>';
        // language
        $output .= '    <p>' . FormItem::text('language', $formValues['language'], Helper::i18n('admin.setup.language')) . '</p>';
        // date format
        // timezone
        // time format
        $output .= '</fieldset>';
        $output .= '<fieldset>';
        $output .= '    <legend>' . Helper::i18n('admin.setup.posts') . '</legend>';
        $output .= '    <p>' . FormItem::number('posts_per_page', $formValues['posts_per_page'], Helper::i18n('admin.setup.posts_per_page'), array('step' => 1, 'min' => 1)) . '</p>';
        $categoriesList = CategoryList::loadAll();
        $output .= '    <p>' . FormItem::select(
            'posts_default_category',
            $categoriesList->getAsArray(),
            $formValues['posts_default_category'],
            Helper::i18n('admin.setup.posts_default_category')
            ) . '</p>';
        $output .= '</fieldset>';

        // Comments
        $output .= '<fieldset>';
        $output .= '    <legend>' . Helper::i18n('admin.setup.comments') . '</legend>';
        $output .= '    <p>' . FormItem::checkbox('comments_enabled', 1, $formValues['comments_enabled'] == 1, Helper::i18n('admin.setup.comments_enabled')) . '</p>';
        $output .= '    <p>' . FormItem::checkbox('comments_name_email_required', 1, $formValues['comments_name_email_required'], Helper::i18n('admin.setup.comments_name_email_required')) . '</p>';
        $output .= '    <p>' . FormItem::number('comments_autoclose_after', $formValues['comments_autoclose_after'], Helper::i18n('admin.setup.comments_autoclose_after'), array('step' => 1, 'min' => 0)) . '</p>';
        $output .= '<p>';
        $output .= FormItem::select(
            'comments_order',
            array('ASC' => Helper::i18n('admin.setup.comments_order_asc'), 'DESC' => Helper::i18n('admin.setup.comments_order_desc')),
            $formValues['comments_order'],
            Helper::i18n('admin.setup.comments_order')
        );
        $output .= '</p>';
        
        $output .= '    <p>' . FormItem::checkbox('comments_autoallow', 1, $formValues['comments_autoallow'], Helper::i18n('admin.setup.comments_autoallow')) . '</p>';
        $output .= '    <p>' . FormItem::checkbox('comments_notify', 1, $formValues['comments_notify'], Helper::i18n('admin.setup.comments_notify')) . '</p>';
        $output .= '    <p>' . FormItem::number('comments_hold_links_nb', $formValues['comments_hold_links_nb'], Helper::i18n('admin.setup.comments_hold_links_nb'), array('step' => 1, 'min' => 0)) . '</p>';
        $output .= '    <p>' . FormItem::textarea('comments_wordlist_hold', $formValues['comments_wordlist_hold'], Helper::i18n('admin.setup.comments_wordlist_hold')) . '</p>';
        $output .= '    <p>' . FormItem::textarea('comments_wordlist_spam', $formValues['comments_wordlist_spam'], Helper::i18n('admin.setup.comments_wordlist_spam')) . '</p>';
        $output .= '</fieldset>';

        // Themes
        $themeList = new Theme\ThemeList();
        $output .= '<fieldset>' . "\n";;
        $output .= '    <legend>' . Helper::i18n('admin.setup.themes') . '</legend>'."\n";
        $output .= '    <p>';
        $output .= FormItem::select(
            'theme',
            $themeList->getAsArray(),
            $formValues['theme'],
            Helper::i18n('admin.setup.theme')
        );
        $output .= '</p>';
        $output .= '</fieldset>' . "\n";

        $output .= FormItem::submit('save_configuration', Helper::i18n('admin.setup.save'));
        
        return parent::render($output);
    }
}
