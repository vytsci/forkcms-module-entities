<?php

namespace Frontend\Modules\Entities\Engine;

use Common\Uri as CommonUri;

use Frontend\Core\Engine\Model as FrontendModel;
use Frontend\Core\Engine\Form as FrontendForm;
use Frontend\Core\Engine\Url as FrontendUrl;

/**
 * Class Meta
 * @package Frontend\Modules\Localization\Engine
 */
class Meta
{

    /**
     * @var string
     */
    protected $baseFieldName;

    /**
     * @var array
     */
    protected $callback = array();

    /**
     * @var array
     */
    protected $data;

    /**
     * @var FrontendForm
     */
    protected $frm;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var FrontendUrl
     */
    protected $URL;

    /**
     * @param FrontendForm $form
     * @param $module
     * @param null $metaId
     * @param string $baseFieldName
     * @throws \Exception
     */
    public function __construct(FrontendForm $form, $module, $metaId = null, $baseFieldName = 'title')
    {
        // check if URL is available from the reference
        if (!FrontendModel::getContainer()->has('url')) {
            throw new \Exception('URL should be available in the reference.');
        }

        // get FrontendUrl instance
        $this->URL = FrontendModel::getContainer()->get('url');

        // set form instance
        $this->frm = $form;

        // set base field name
        $this->baseFieldName = $baseFieldName;

        // metaId was specified, so we should load the item
        if ($metaId !== null) {
            $this->loadMeta($metaId);
        }

        // set default callback
        $this->setUrlCallback(
            'Frontend\\Modules\\'.$module.'\\Engine\\Model',
            'getURL'
        );
    }

    /**
     * @param $id
     * @throws \Exception
     */
    protected function loadMeta($id)
    {
        $this->id = (int)$id;

        // get item
        $this->data = (array)FrontendModel::getContainer()->get('database')->getRecord(
            'SELECT *
            FROM meta AS m
            WHERE m.id = ?',
            array($this->id)
        );

        // validate meta-record
        if (empty($this->data)) {
            throw new \Exception('Meta-record doesn\'t exist.');
        }

        // unserialize data
        if (isset($this->data['data'])) {
            $this->data['data'] = @unserialize($this->data['data']);
        }
    }

    /**
     * @param $className
     * @param $methodName
     * @param array $parameters
     */
    public function setURLCallback($className, $methodName, $parameters = array())
    {
        $className = (string)$className;
        $methodName = (string)$methodName;
        $parameters = (array)$parameters;

        // store in property
        $this->callback = array('class' => $className, 'method' => $methodName, 'parameters' => $parameters);
    }

    /**
     * @param $URL
     * @return mixed
     * @throws \Exception
     */
    public function generateURL($URL)
    {
        // validate (check if the function exists)
        if (!is_callable(array($this->callback['class'], $this->callback['method']))) {
            throw new \Exception('The callback-method doesn\'t exist.');
        }

        // when using ->getValue() in SpoonFormText fields the function is using htmlentities(),
        // so we must decode it again first!
        $URL = \SpoonFilter::htmlentitiesDecode($URL);

        // build parameters for use in the callback
        $parameters[] = CommonUri::getUrl($URL);

        // add parameters set by user
        if (!empty($this->callback['parameters'])) {
            foreach ($this->callback['parameters'] as $parameter) {
                $parameters[] = $parameter;
            }
        }

        // get the real url
        return call_user_func_array(array($this->callback['class'], $this->callback['method']), $parameters);
    }

    public function save($update = false)
    {
        // get title
        $title = $this->frm->getField($this->baseFieldName)->getValue();

        // build URL
        $URL = $this->generateURL(
            \SpoonFilter::htmlspecialcharsDecode(
                $this->frm->getField($this->baseFieldName)->getValue()
            )
        );

        // build meta
        $this->data['keywords'] = $title;
        $this->data['keywords_overwrite'] = 'N';
        $this->data['description'] = $title;
        $this->data['description_overwrite'] = 'N';
        $this->data['title'] = $title;
        $this->data['title_overwrite'] = 'N';
        $this->data['url'] = $URL;
        $this->data['url_overwrite'] = 'N';

        $db = FrontendModel::getContainer()->get('database');

        if ((bool)$update && isset($this->id)) {
            $db->update('meta', $this->data, 'id = ?', array($this->id));

            return $this->id;
        } else {
            $this->id = (int)$db->insert('meta', $this->data);

            return $this->id;
        }
    }
}
