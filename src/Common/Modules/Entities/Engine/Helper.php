<?php

namespace Common\Modules\Entities\Engine;

use Common\Core\Model as CommonModel;

/**
 * Class Helper
 * @package Common\Modules\Entities\Engine
 */
class Helper
{

    /**
     * @var
     */
    private static $formatDateTime;

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
     * @param $entities
     * @return array
     */
    public static function convertToArray($entities)
    {
        $result = array();

        foreach ($entities as $entitiesKey => $entity) {
            if (is_array($entity)) {
                self::convertToArray($entity);
            }
            if ($entity instanceof Entity) {
                $result[$entitiesKey] = $entity->toArray();
            }
        };

        return $result;
    }

    /**
     * @param $var
     * @return string
     */
    public static function toSnakeCase($var)
    {
        $var = preg_replace(
            '/(?!^)[[:upper:]][[:lower:]]/',
            '$0',
            preg_replace('/(?!^)[[:upper:]]+/', '_'.'$0', $var)
        );

        return strtolower($var);
    }

    /**
     * @param $primary
     * @param $table
     * @param $arrayToSave
     * @param $where
     * @param $whereValues
     * @throws \Exception
     */
    public static function generateWhereClauseVariables($primary, $table, &$arrayToSave, &$where, &$whereValues)
    {
        foreach ($primary as $primaryValue) {
            if (!isset($arrayToSave[$primaryValue])) {
                throw new \Exception("Field {$primaryValue} does not exist within {$table}");
            }

            $where[] = "{$primaryValue} = ?";
            $whereValues[] = $arrayToSave[$primaryValue];

            unset($arrayToSave[$primaryValue]);
        }
    }

    /**
     * @param $value
     * @return bool|string
     */
    public static function prepareDateTime($value)
    {
        if ($value === null) {
            $value = time();
        }

        return date('Y-m-d H:i:s', is_numeric($value) ? $value : strtotime(str_replace(array('/'), '-', $value)));
    }

    /**
     * @param $dateTime
     * @param null $format
     * @return bool|int|string
     */
    public static function getDateTime($dateTime, $format = null)
    {
        if (is_null($dateTime)) {
            return null;
        }

        $time = strtotime(str_replace('/', '-', $dateTime));

        if (isset($format)) {
            $time = date($format, $time);
        }

        return $time;
    }

    /***
     * @return string
     */
    public static function getFormatDateTime()
    {
        if (is_null(self::$formatDateTime)) {
            $dateFormat = CommonModel::getContainer()->get('fork.settings')->get('Core', 'date_format_short');
            $timeFormat = CommonModel::getContainer()->get('fork.settings')->get('Core', 'time_format');

            self::$formatDateTime = $dateFormat.' '.$timeFormat;
        }

        return self::$formatDateTime;
    }
}
