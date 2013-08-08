<?php
namespace Esperluette;

use Esperluette\Model\Meta;
use Esperluette\Model\Config;

class Esperluette
{
    public function setup()
    {
        $this->loadLibs();

        $this->loadConfig();
    }

    private function loadLibs()
    {
        require_once 'TemplateFunctions.php';
    }

    public function loadConfig()
    {
        Config::clean();
        $metaList   = Meta\MetaList::loadAll();

        foreach ($metaList as $currentMeta) {
            Config::set($currentMeta->key, $currentMeta->value);
        }
    }
}