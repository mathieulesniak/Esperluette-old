<?php
namespace Esperluette\Model\User;

use Esperluette\Model;
use Fwk;

class User extends Fwk\DBObject
{
    const TABLE_NAME    = 'users';
    const TABLE_INDEX   = 'id';

    public function __construct()
    {
        $this->dbVariables = array('id',
                                    'nickname',
                                    'first_name',
                                    'last_name',
                                    'email',
                                );
    
        
    }

   

    public function getDisplayName()
    {
        return ( $this->nickname != '' ) ? $this->nickname : $this->first_name . ' ' . $this->last_name;
    }
    public function getUrl()
    {
    }

    public function hasAvatar()
    {
        return is_file($_SERVER['DOCUMENT_ROOT'] . Property::getValueFor('avatarDir') . $this->id . '.pic');
    }

    public function getAvatarUrl()
    {
        if ($this->hasAvatar()) {
            return Property::getValueFor('avatarDir') . $this->id . '.pic';
        }
    }

    public function delete() {
        /**
         TODO : delete all written posts ?
         */
        
        parent::delete();
    }
}
