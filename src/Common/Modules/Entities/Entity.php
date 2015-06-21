<?php

namespace Common\Modules\Entities;

/**
 * Class Entity
 * @package Common\Modules\Entities
 */
class Entity extends AbstractEntity
{
    /**
     * @var array
     */
    protected $_primary = array('id');

    /**
     * @param array $parameters
     */
    function __construct($parameters = array())
    {
        parent::__construct();

        if (!empty($parameters)) {
            $this->load($parameters);
        }
    }
}
