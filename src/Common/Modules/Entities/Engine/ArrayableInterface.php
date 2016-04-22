<?php

namespace Common\Modules\Entities\Engine;

/**
 * Interface ArrayableInterface
 * @package Common\Modules\Entities\Engine
 */
interface ArrayableInterface
{

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray();
}
