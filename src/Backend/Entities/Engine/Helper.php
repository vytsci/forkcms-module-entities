<?php

namespace Backend\Modules\Entities\Engine;

use Common\Uri as CommonUri;

use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;

/**
 * Class Helper
 * @package Backend\Modules\Entities\Engine
 */
class Helper
{
    /**
     * @param $var
     * @return bool
     */
    public static function filterNotNull($var)
    {
        return null !== $var;
    }

    /**
     * @param $var
     * @return bool
     */
    public static function filterValuable($var)
    {
        return is_numeric($var) || is_string($var) || is_bool($var);
    }

    /**
     * @param $var
     * @return string
     */
    public static function toSnakeCase($var) {
        $var = preg_replace(
            '/(?!^)[[:upper:]][[:lower:]]/',
            '$0',
            preg_replace('/(?!^)[[:upper:]]+/', '_' . '$0', $var)
        );
        return strtolower($var);
    }
}
