<?php

namespace Common\Modules\Entities;

use Common\Core\Model as CommonModel;

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
     * @var string
     */
    protected $language;

    /**
     * @var array
     */
    private $_meta = array();

    /**
     * @var
     */
    protected $metaId;

    /**
     * @param array $parameters
     */
    function __construct($parameters = array())
    {
        parent::__construct();

        if (!empty($parameters)) {
            $this->load($parameters);
            $this->loadMeta();
        }
    }

    /**
     *
     */
    public function loadMeta()
    {
        if ($this->hasMeta()) {
            $this->addRelation('meta');

            if (isset($this->metaId) && is_numeric($this->metaId)) {
                $meta = (array)CommonModel::getContainer()->get('database')->getRecord(
                    'SELECT m.* FROM meta AS m WHERE m.id = ?',
                    (int)$this->metaId
                );

                $this->setMeta($meta);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaId()
    {
        return $this->metaId;
    }

    /**
     * @param $metaId
     * @return $this
     */
    public function setMetaId($metaId)
    {
        $this->metaId = $metaId;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasMeta()
    {
        return $this->hasColumn('meta_id');
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->_meta;
    }

    /**
     * @param $meta
     * @return $this
     */
    public function setMeta($meta)
    {
        $this->_meta = $meta;

        return $this;
    }

    /**
     * @param $key
     * @return null
     */
    public function getMetaVariable($key)
    {
        if (!isset($this->_meta[$key])) {
            return null;
        }

        return $this->_meta[$key];
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setMetaVariable($key, $value)
    {
        $this->_meta[$key] = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasLanguage()
    {
        return $this->hasColumn('language');
    }

    /**
     * @return mixed
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
