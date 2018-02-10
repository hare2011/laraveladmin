<?php

namespace Runhare\Admin\Grid\Filter;

use Runhare\Admin\Grid\Filter;
use Runhare\Admin\Grid\Filter\Field\DateTime;
use Runhare\Admin\Grid\Filter\Field\Select;
use Runhare\Admin\Grid\Filter\Field\Text;

abstract class AbstractFilter {

    /**
     * Element id.
     *
     * @var array|string
     */
    protected $id;

    /**
     * Label of field.
     *
     * @var string
     */
    protected $label;

    /**
     * @var array|string
     */
    protected $value;

    /**
     * @var string
     */
    protected $column;

    /**
     * Field object.
     *
     * @var
     */
    protected $field;

    /**
     * Query for filter.
     *
     * @var string
     */
    protected $query = 'where';

    /**
     * @var Filter
     */
    protected $parent;
    protected $display = '';
    protected $labelShow = true;

    /**
     * AbstractFilter constructor.
     *
     * @param $column
     * @param string $label
     */
    public function __construct($column, $label = '') {
        $this->column = $column;
        $this->label = $this->formatLabel($label);
        $this->id = $this->formatId($column);

        $this->setupField();
    }

    /**
     * Setup field.
     *
     * @return void
     */
    public function setupField() {
        $this->field = new Text();
        $this->field->setPlaceholder($this->label);
    }

    /**
     * Format label.
     *
     * @param string $label
     *
     * @return string
     */
    protected function formatLabel($label) {
        $label = $label ?: ucfirst($this->column);

        return str_replace(['.', '_'], ' ', $label);
    }

    /**
     * Format name.
     *
     * @param string $column
     *
     * @return string
     */
    protected function formatName($column) {
        $columns = explode('.', $column);

        if (count($columns) == 1) {
            return $columns[0];
        }

        $name = array_shift($columns);
        foreach ($columns as $column) {
            $name .= "[$column]";
        }

        return $name;
    }

    /**
     * Format id.
     *
     * @param $columns
     *
     * @return array|string
     */
    public function formatId($columns) {
        return str_replace('.', '_', $columns);
    }

    /**
     * @param Filter $filter
     */
    public function setParent(Filter $filter) {
        $this->parent = $filter;
    }

    /**
     * Get siblings of current filter.
     *
     * @param null $index
     *
     * @return AbstractFilter[]|mixed
     */
    public function siblings($index = null) {
        if (!is_null($index)) {
            return array_get($this->parent->filters(), $index);
        }

        return $this->parent->filters();
    }

    /**
     * Get previous filter.
     *
     * @param int $step
     *
     * @return AbstractFilter[]|mixed
     */
    public function previous($step = 1) {
        return $this->siblings(
                        array_search($this, $this->parent->filters()) - $step
        );
    }

    /**
     * Get next filter.
     *
     * @param int $step
     *
     * @return AbstractFilter[]|mixed
     */
    public function next($step = 1) {
        return $this->siblings(
                        array_search($this, $this->parent->filters()) + $step
        );
    }

    /**
     * Get query condition from filter.
     *
     * @param array $inputs
     *
     * @return array|mixed|null
     */
    public function condition($inputs) {
        $value = array_get($inputs, $this->column);

        if (!isset($value)) {
            return;
        }

        $this->value = $value;

        return $this->buildCondition($this->column, $this->value);
    }

    /**
     * Select filter.
     *
     * @param array $options
     *
     * @return $this
     */
    public function select($options = []) {
        $select = new Select($options);

        $select->setParent($this);

        return $this->setField($select);
    }

    /**
     * Datetime filter.
     *
     * @param array $options
     *
     * @return mixed
     */
    public function datetime($options = []) {
        return $this->setField(new DateTime($this, $options));
    }

    /**
     * Set field object of filter.
     *
     * @param $field
     */
    protected function setField($field) {
        return $this->field = $field;
    }

    /**
     * Get field object of filter.
     *
     * @return mixed
     */
    public function field() {
        return $this->field;
    }

    /**
     * Get element id.
     *
     * @return array|string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get column name of current filter.
     *
     * @return string
     */
    public function getColumn() {
        return $this->column;
    }

    /**
     * Get value of current filter.
     *
     * @return array|string
     */
    public function getValue() {
        return $this->value;
    }

    public function visibility($bool = true) {
        if ($bool) {
            $this->display = '';
        } else {
            $this->display = 'style="display:none;"';
        }
    }

    public function labelVisibility($bool = true) {
        if($bool){
            $this->labelShow = true;
        }else{
            $this->labelShow = false;
        }
    }

    /**
     * Build conditions of filter.
     *
     * @return array|mixed
     */
    protected function buildCondition() {
        if (is_null($this->parent->hasValueFilter)) {
            $this->parent->hasValueFilter = $this;
        }

        $column = explode('.', $this->column);

        if (count($column) == 1) {
            $query = func_get_args();
            if(isset($query[0])){
                $query[0] = str_replace('#', '.', $query[0]);
            }
            return [$this->query => $query];
        }

        return call_user_func_array([$this, 'buildRelationCondition'], func_get_args());
    }

    /**
     * Build query condition of model relation.
     *
     * @return array
     */
    protected function buildRelationCondition() {
        $args = func_get_args();

        list($relation, $args[0]) = explode('.', $this->column);

        return ['whereHas' => [$relation, function ($relation) use ($args) {
                    call_user_func_array([$relation, $this->query], $args);
                }]];
    }

    /**
     * @return array
     */
    protected function fieldVars() {
        if (method_exists($this->field(), 'variables')) {
            return $this->field()->variables();
        }

        return [];
    }

    public function getLabel() {
        return $this->label;
    }

    public function getName() {
        $name = $this->formatName($this->column);
        if (is_array($name)) {
            return implode('_', $name);
        }
        return $name;
    }

    /**
     * Variables for filter view.
     *
     * @return array
     */
    protected function variables() {
        $variables = [
            'id' => $this->id,
            'name' => $this->formatName($this->column),
            'label' => $this->label,
            'value' => $this->value,
            'field' => $this->field(),
            'display' => $this->display,
            'labelShow' => $this->labelShow
        ];

        return array_merge($variables, $this->fieldVars());
    }

    /**
     * Render this filter.
     *
     * @return \Illuminate\View\View|string
     */
    public function render() {
        $class = explode('\\', get_called_class());
        $view = 'admin::filter.' . strtolower(end($class));
        return view($view, $this->variables());
    }

    /**
     * @return \Illuminate\View\View|string
     */
    public function __toString() {
        return $this->render();
    }

    /**
     * @param $method
     * @param $params
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function __call($method, $params) {
        if (method_exists($this->field, $method)) {
            return call_user_func_array([$this->field, $method], $params);
        }

        throw new \Exception('Method "' . $method . '" not exists.');
    }

}
