<?php

namespace Common\Modules\Entities\Engine;

use Common\Core\Model as CommonModel;

/**
 * Class AbstractEntity
 * @todo use ReflectionClass where needed
 * @package Common\Modules\Entities\Engine
 */
abstract class AbstractEntity implements ArrayableInterface
{

    /**
     * @var \SpoonDatabase
     */
    protected $_db;

    /**
     * @var string
     */
    protected $_table;

    /**
     * @var string
     */
    protected $_query;

    /**
     * @var array
     */
    protected $_primary = array('id');

    /**
     * @var array
     */
    protected $_columns = array();

    /**
     * @var array
     */
    protected $_relations = array();

    /**
     * @var bool
     */
    protected $_loaded = false;

    /**
     * @var bool
     */
    protected $_saving = false;

    /**
     * @var int
     */
    protected $id;

    /**
     *
     */
    function __construct()
    {
        $this->_db = CommonModel::getContainer()->get('database');
        $this->_columns = array_unique(array_merge($this->_primary, $this->_columns));
    }

    /**
     * @param array $parameters
     * @return $this
     * @throws \SpoonDatabaseException
     */
    public function load($parameters = array())
    {
        if (!empty($this->_query)) {
            $record = (array)$this->_db->getRecord(
                $this->_query,
                $parameters
            );

            $this->assemble($record);
        }

        return $this;
    }

    /**
     *
     */
    public function unload()
    {
        $this->id = null;
        $this->_loaded = false;
    }

    /**
     * @param $record
     * @return $this
     */
    public function assemble($record)
    {
        if (empty($record)) {
            return $this;
        }

        $this->_loaded = true;

        if (empty($this->_columns)) {
            $this->_columns = array_keys($record);
        }

        foreach ($record as $recordKey => $recordValue) {
            $setMethod = 'set'.\SpoonFilter::toCamelCase($recordKey);
            if (method_exists($this, $setMethod) && $recordValue !== null) {
                $this->$setMethod($recordValue);
            }
        }

        return $this;
    }

    /**
     * @param bool $includeRelations
     * @return array
     */
    protected function getVariables($includeRelations = true)
    {
        $result = array();

        $entityVariables = array_merge($this->_primary, $this->_columns);

        if ($includeRelations) {
            $entityVariables = array_merge($entityVariables, $this->_relations);
        }

        $entityVariables = array_unique($entityVariables);

        foreach ($entityVariables as $entityVariable) {
            $methodGet = 'get'.\SpoonFilter::toCamelCase($entityVariable);
            $methodIs = 'is'.\SpoonFilter::toCamelCase($entityVariable);
            $methodHas = 'has'.\SpoonFilter::toCamelCase($entityVariable);
            $value = null;

            if (is_callable(array($this, $methodGet))) {
                $value = call_user_func(array($this, $methodGet));
            } elseif (is_callable(array($this, $methodIs))) {
                $value = call_user_func(array($this, $methodIs));
            } elseif (is_callable(array($this, $methodHas))) {
                $value = call_user_func(array($this, $methodHas));
            }

            $result[$entityVariable] = $value;
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function isAffected()
    {
        $variables = array_filter(
            $this->getVariables(),
            '\\Common\\Modules\\Entities\\Engine\\Helper::filterNotNull'
        );

        return !empty($variables);
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
        return $this->_table;
    }

    /**
     * @param $table
     * @return $this
     */
    public function setTable($table)
    {
        $this->_table = $table;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /**
     * @param $query
     * @return $this
     */
    public function setQuery($query)
    {
        $this->_query = $query;

        return $this;
    }

    /**
     * @param $column
     * @return bool
     */
    public function hasColumn($column)
    {
        return in_array($column, $this->_columns);
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->_columns;
    }

    /**
     * @param $relation
     * @return bool
     */
    public function hasRelation($relation)
    {
        return in_array($relation, $this->_relations);
    }

    /**
     * @return array
     */
    public function getRelations()
    {
        return $this->_relations;
    }

    /**
     * @param $relation
     * @return $this
     */
    public function addRelation($relation)
    {
        if (!in_array($relation, $this->_relations)) {
            $this->_relations[] = $relation;
        }

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
        $this->id = (int)$id;

        return $this;
    }

    /**
     * @param bool $onlyColumns
     * @return array
     */
    public function toArray($onlyColumns = false)
    {
        $result = array();

        foreach ($this->getVariables(!$onlyColumns) as $variablesKey => $variablesValue) {
            $variablesKey = Helper::toSnakeCase($variablesKey);
            $value = $variablesValue;

            if (is_array($variablesValue)) {
                foreach ($variablesValue as $variablesValueKey => &$variablesValueValue) {
                    $variablesValueKey = Helper::toSnakeCase($variablesValueKey);
                    if (is_callable(array($variablesValueValue, 'toArray'))) {
                        $array = call_user_func(array($variablesValueValue, 'toArray'));
                        $value[$variablesValueKey] = $this->_saving ? serialize($array) : $array;
                    } elseif (!is_object($variablesValueValue)) {
                        $value[$variablesValueKey] = $variablesValueValue;
                    }
                }
            } elseif ($variablesValue instanceof ArrayableInterface) {
                if ($this->_saving) {
                    if ($variablesValue instanceof EnumValue) {
                        $value = call_user_func(array($variablesValue, 'getValue'));
                    }
                } else {
                    $value = call_user_func(array($variablesValue, 'toArray'));
                }
            }

            $result[$variablesKey] = $value;
        }

        return $result;
    }

    /**
     * @return $this
     */
    public function save()
    {
        $this->_saving = true;

        if (is_callable(array($this, 'beforeSave'))) {
            call_user_func(array($this, 'beforeSave'));
        }

        if (empty($this->id) || !$this->_loaded) {
            $id = $this->insert();

            if ($this->id === null) {
                $this->setId($id);
            }
        } else {
            $this->update();
        }

        if (is_callable(array($this, 'afterSave'))) {
            call_user_func(array($this, 'afterSave'));
        }

        $this->_loaded = true;
        $this->_saving = false;

        return $this;
    }

    /**
     * @return int
     * @throws \SpoonDatabaseException
     */
    public function insert()
    {
        $arrayToSave = array_filter(
            $this->toArray(true),
            '\\Common\\Modules\\Entities\\Engine\\Helper::filterValuable'
        );

        if (is_callable(array($this, 'beforeInsert'))) {
            call_user_func(array($this, 'beforeInsert'));
        }

        $id = (int)$this->_db->insert($this->_table, $arrayToSave);

        if (is_callable(array($this, 'afterInsert'))) {
            call_user_func(array($this, 'afterInsert'));
        }

        return $id;
    }

    /**
     * @return int
     * @throws \Exception
     * @throws \SpoonDatabaseException
     */
    public function update()
    {
        $arrayToSave = $this->toArray(true);

        $where = array();
        $whereValues = array();

        Helper::generateWhereClauseVariables($this->_primary, $this->_table, $arrayToSave, $where, $whereValues);

        if (is_callable(array($this, 'beforeUpdate'))) {
            call_user_func(array($this, 'beforeUpdate'));
        }

        $result = (int)$this->_db->update($this->_table, $arrayToSave, implode(' AND ', $where), $whereValues);

        if (is_callable(array($this, 'afterUpdate'))) {
            call_user_func(array($this, 'afterUpdate'));
        }

        return $result;
    }

    /**
     * @throws \Exception
     * @throws \SpoonDatabaseException
     */
    public function delete()
    {
        $arrayToSave = array_filter($this->toArray(true), '\\Common\\Modules\\Entities\\Engine\\Helper::filterValuable');

        $where = array();
        $whereValues = array();

        Helper::generateWhereClauseVariables($this->_primary, $this->_table, $arrayToSave, $where, $whereValues);

        if (is_callable(array($this, 'beforeDelete'))) {
            call_user_func(array($this, 'beforeDelete'));
        }

        $this->_db->delete($this->_table, implode(' AND ', $where), $whereValues);

        if (is_callable(array($this, 'afterDelete'))) {
            call_user_func(array($this, 'afterDelete'));
        }

        $this->_loaded = false;
    }
}
