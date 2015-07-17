<?php

namespace Backend\Modules\Entities\Installer;

use Backend\Core\Installer\ModuleInstaller;

/**
 * Class Installer
 * @package Backend\Modules\Entities\Installer
 */
class Installer extends ModuleInstaller
{
    /**
     * Install the module
     */
    public function Install()
    {
        $this->addModule('Entities');
        $this->setModuleRights(1, 'Entities');
    }
}
