<?php
namespace Esperluette\View\Admin\User;

use Esperluette\Model;
use Esperluette\View;
use Esperluette\Model\Helper;
use Fwk\Fwk;
use Fwk\FormItem;


class Edit extends \Esperluette\View\Admin
{
    protected $section = 'user';

    public function render($content = '')
    {
        $formValues = array(
            'nickname'          => Fwk::Request()->getPostParam('nickname', $this->model->nickname),
            'first_name'        => Fwk::Request()->getPostParam('first_name', $this->model->first_name),
            'last_name'         => Fwk::Request()->getPostParam('last_name', $this->model->last_name),
            'name_display'      => Fwk::Request()->getPostParam('name_display', $this->model->name_display),
            'email'             => Fwk::Request()->getPostParam('email', $this->model->email),
            'password'          => '',
            'active'            => Fwk::Request()->getPostParam('active', $this->model->active),
            'level'             => Fwk::Request()->getPostParam('level', $this->model->level),
        );

        if ($this->model->id !== null) {
            $output  = '<h1>' . Helper::i18n('admin.users.edit') . '</h1>';
            $nicknameAttributes = array('disabled' => 'disabled');
        } else {
            $output  = '<h1>' . Helper::i18n('admin.users.add') . '</h1>';
            $nicknameAttributes = array();
        }

        $output .= '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
        $output .= '    <p>';
        $output .=          FormItem::text('nickname', $formValues['nickname'], Helper::i18n('admin.users.nickname'), $nicknameAttributes);
        $output .= '    </p>';
        $output .= '    <p>';
        $output .=          FormItem::text('first_name', $formValues['first_name'], Helper::i18n('admin.users.first_name'));
        $output .= '    </p>';
        $output .= '    <p>';
        $output .=          FormItem::text('last_name', $formValues['last_name'], Helper::i18n('admin.users.last_name'));
        $output .= '    </p>';
        $output .= '<p>';
        $output .= FormItem::select(
            'name_display',
            Model\User\User::getNameDisplayOptions(),
            $formValues['name_display'],
            Helper::i18n('admin.users.name_display')
        );
        $output .= '</p>';
        $output .= '    <p>';
        $output .=          FormItem::text('email', $formValues['email'], Helper::i18n('admin.users.email'));
        $output .= '    </p>';
        $output .= '    <p>';
        $output .=          FormItem::password('password', $formValues['password'], Helper::i18n('admin.users.password'));
        $output .= '    </p>';
        /**
         TODO : handle user level to update these properties 
         */
        $output .= '    <p>';
        $output .=          FormItem::checkbox('active', 1, $formValues['active'] == 1, Helper::i18n('admin.users.active'));
        $output .= '    </p>';
        $output .= '<p>';
        $output .= FormItem::select(
            'level',
            Model\User\User::getLevels(),
            $formValues['level'],
            Helper::i18n('admin.users.level')
        );
        $output .= '</p>';

        $output .= FormItem::submit('save_user', Helper::i18n('admin.users.save'));
        $output .= '</form>';

        return parent::render($output);

    }
}
