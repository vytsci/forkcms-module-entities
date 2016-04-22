<?php

namespace Common\Modules\Entities\Engine;

/**
 * Class EnumValue
 * @package Common\Modules\Entities\Engine
 */
class EnumValue implements ArrayableInterface
{

    /**
     * @var string
     */
    protected $defaultValue = null;

    /**
     * @var bool
     */
    private $loaded = false;

    /**
     * @var
     */
    private $value;

    /**
     * @var
     */
    private $label;

    /**
     * @var
     */
    private $message;

    /**
     * @var
     */
    private $error;

    /**
     * @var
     */
    private $action;

    /**
     * @param null $value
     */
    public function __construct($value = null)
    {
        $this->load($value);
    }

    /**
     * @param null $value
     */
    public function load($value)
    {
        if (is_callable(array($this, 'beforeLoad'))) {
            call_user_func(array($this, 'beforeLoad'));
        }

        $this->setValue($value);

        if (isset($value)) {
            $this->loaded = true;
        }

        if (is_callable(array($this, 'afterLoad'))) {
            call_user_func(array($this, 'afterLoad'));
        }
    }

    /**
     * @return boolean
     */
    public function isLoaded()
    {
        return $this->loaded;
    }

    /**
     * @param bool|false $toCamelCase
     * @return string
     */
    public function getValue($toCamelCase = false)
    {
        if (is_null($this->value)) {
            $this->value = $this->defaultValue;
        }

        if ($toCamelCase) {
            return \SpoonFilter::toCamelCase($this->value);
        }

        return $this->value;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = empty($value) ? $this->defaultValue : $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->message;
    }

    /**
     * @param $error
     * @return $this
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'value' => $this->getValue(),
            'label' => $this->getLabel(),
            'message' => $this->getMessage(),
            'action' => $this->getAction(),
        );
    }
}
