<?php
/**
 *
 * @author Mihkel Viilveer <mihkel@opus.ee>
 * @date 2.09.2014
 */

namespace opus\elastic\search;

use yii\base\InvalidValueException;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

/**
 * Class AbstractResultWidget
 *
 * @author Mihkel Viilveer <mihkel@opus.ee>
 * @package opus\elastic\search\hit
 */
abstract class AbstractResultWidget extends Widget
{
    /**
     * @var Model|null
     */
    public $source = null;

    /**
     * Result numerical identity, this is result number not source ID
     * @var int
     */
    public $resultId;

    /**
     * User search params
     * @var string
     */
    public $searchParams;

    /**
     * View path
     *
     * @var string
     */
    protected $viewPath = null;

    /**
     * Active record model where source is stored
     *
     * @return Model
     */
    abstract protected function getModel();

    /**
     * Returns source attribute
     *
     * @param string $attribute
     * @throws InvalidValueException
     * @return mixed
     */
    public function getAttribute($attribute)
    {
        if ($this->source->canGetProperty($attribute)) {
            return $this->source->$attribute;
        }
        throw new InvalidValueException('Unknown attribute: ' . $attribute);
    }

    /**
     * Generates source model and sets attributes into model
     *
     * @param array $source
     * @return $this
     */
    public function setSource(array $source)
    {
        $model = $this->getModel();
        $model->setAttributes($source, false);
        $this->source = $model;
        return $this;
    }

    /**
     * Overridden to set view file path
     *
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render($view, $params = [])
    {
        $renderParams = ArrayHelper::merge(
            [
                'source' => $this->source,
                'resultId' => $this->resultId,
            ],
            $params
        );
        return parent::render($view, $renderParams);
    }

    /**
     * Sets template
     *
     * @param string $template
     * @return AbstractResultWidget
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Returns path alias
     *
     * @return string
     */
    public function getViewFilePath()
    {
        return sprintf('%s/%s', $this->viewPath, $this->id);
    }

    /**
     * @param string $viewPath
     * @return AbstractResultWidget
     */
    public function setViewPath($viewPath)
    {
        $this->viewPath = $viewPath;
        return $this;
    }

    /**
     * @param int $resultId
     * @return AbstractResultWidget
     */
    public function setResultId($resultId)
    {
        $this->resultId = $resultId;
        return $this;
    }
}
