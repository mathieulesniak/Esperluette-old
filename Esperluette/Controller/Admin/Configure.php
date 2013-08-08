<?php
namespace Esperluette\Controller\Admin;

use \Esperluette\Model;
use \Esperluette\View;
use \Fwk\Helper;
use \Fwk\FormItem;

class Configure extends \Esperluette\Controller\Base
{
    public function getHomepage()
    {

        if (isset($_POST['save_configuration'])) {
            Helper::debug($_POST);
            // Save config
            
            // Reload config & inject notification
        }
        $model = new Model\Meta\MetaList();
        $model->loadConfigurationMetas();

        $view = new View\Admin\ConfigureHomepage($model);

        $this->response->setBody($view->render());
    }
}
