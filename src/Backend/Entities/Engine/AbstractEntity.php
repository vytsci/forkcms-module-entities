<?php

namespace Backend\Modules\Entities\Engine;

use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Entities\Engine\Helper as BackendEntitiesHelper;

/**
 * Class AbstractEntity
 * @package Backend\Modules\Entities\Engine
 */
abstract class AbstractEntity
{
    /**
     * @var \SpoonDatabase
     */
    protected $db;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $primary = array('id');

    /**
     * @var int
     */
    protected $id;

    /**
     *
     */
    function __construct()
    {
        $this->db = BackendModel::getContainer()->get('database');

        return $this;
    }

    /**
     * @param null $id
     * @return $this
     */
    public function load($id = null)
    {
        $table = $this->getTable();

        if (is_numeric($id) && !empty($table)) {
            $record = (array)BackendModel::getContainer()->get('database')->getRecord(
                "SELECT e.* FROM `{$table}` AS e WHERE e.id = ?",
                array($id)
            );

            foreach ($record as $recordKey => $recordValue) {
                $setMethod = 'set' . \SpoonFilter::toCamelCase($recordKey);
                if (method_exists($this, $setMethod)) {
                    $this->$setMethod($recordValue);
                }
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isLoaded()
    {
        return is_numeric($this->id);
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param $table
     * @return $this
     */
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = array();

        $variables = array_filter(
            get_object_vars($this),
            '\\Backend\\Modules\\Entities\\Engine\\Helper::filterNotNull'
        );

        unset($variables['db']);
        unset($variables['table']);

        foreach ($variables as $variablesKey => &$variablesValue) {
            $variablesKey = BackendEntitiesHelper::toSnakeCase($variablesKey);
            if (is_array($variablesValue)) {
                foreach ($variablesValue as $variablesValueKey => &$variablesValueValue) {
                    $variablesValueKey = BackendEntitiesHelper::toSnakeCase($variablesValueKey);
                    if ($variablesValueValue instanceof AbstractEntity) {
                        $result[$variablesKey][$variablesValueKey] = $variablesValueValue->toArray();
                    }
                }
                continue;
            }
            $result[$variablesKey] = $variablesValue;
        }

        return $result;
    }

    /**
     * @return int
     */
    public function save()
    {
        if (empty($this->id)) {
            $this->id = $this->insert();

            return $this->id;
        }

        $this->id = (int)$this->update();

        return $this->id;
    }

    /**
     * @return int
     * @throws \SpoonDatabaseException
     */
    public function insert()
    {
        $arrayToSave = array_filter($this->toArray(), '\\Backend\\Modules\\Entities\\Engine\\Helper::filterValuable');

        return (int)$this->db->insert($this->table, $arrayToSave);
    }

    /**
     * @return int
     * @throws \SpoonDatabaseException
     */
    public function update()
    {
        $arrayToSave = array_filter($this->toArray(), '\\Backend\\Modules\\Entities\\Engine\\Helper::filterValuable');

        $whereValues = array();
        $where = array();

        foreach ($this->primary as $primaryValue) {
            $where[] = "{$primaryValue} = ?";
            $whereValues[] = $arrayToSave[$primaryValue];
            unset($arrayToSave[$primaryValue]);
        }

        return (int)$this->db->update($this->table, $arrayToSave, implode(' AND ', $where), $whereValues);
    }
}
