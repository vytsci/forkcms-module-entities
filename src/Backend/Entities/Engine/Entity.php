<?php

namespace Backend\Modules\Entities\Engine;

/**
 * Class Entity
 * @package Backend\Modules\Entities\Engine
 */
class Entity extends AbstractEntity
{
    /**
     * @var array
     */
    protected $primary = array('id', 'language');

    /**
     * Language is optional variable, but its common.
     *
     * @var string
     */
    protected $language;

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }
}
