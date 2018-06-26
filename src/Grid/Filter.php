<?php

namespace Runhare\Admin\Grid;

use Runhare\Admin\Facades\Admin;
use Runhare\Admin\Grid\Filter\AbstractFilter;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use ReflectionClass;

/**
 * Class Filter.
 *
 * @method Filter     equal($column, $label = '')
 * @method Filter     like($column, $label = '')
 * @method Filter     ilike($column, $label = '')
 * @method Filter     gt($column, $label = '')
 * @method Filter     lt($column, $label = '')
 * @method Filter     between($column, $label = '')
 * @method Filter     where(\Closure $callback, $label)
 */
class Filter
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var array
     */
    protected $supports = ['equal', 'is', 'ilike', 'like', 'gt', 'lt', 'between', 'where'];

    /**
     * If use a modal to hold the filters.
     *
     * @var bool
     */
    protected $useModal = false;

    /**
     * If use id filter.
     *
     * @var bool
     */
    protected $useIdFilter = true;

    /**
     * Action of search form.
     *
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $view = 'admin::grid.filter';

    /**
     * has value filter
     *
     */
    public $hasValueFilter = null;


    public $extension = [];
    /**
     * Create a new filter instance.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;

        $this->equal($this->model->eloquent()->getKeyName());
    }

    /**
     * Use modal to show filter form.
     */
    public function setModal($type='right')
    {
        $this->useModal = $type;
    }

    /**
     *
     */
    public function getModal()
    {
        return $this->useModal;
    }

    /**
     * Set action of search form.
     *
     * @param string $action
     *
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Disable Id filter.
     */
    public function disableIdFilter()
    {
        $this->useIdFilter = false;
    }

    /**
     * Remove ID filter if needed.
     */
    public function removeIDFilterIfNeeded()
    {
        if (!$this->useIdFilter) {
            array_shift($this->filters);
        }
    }

    /**
     * Get all conditions of the filters.
     *
     * @return array
     */
    public function conditions()
    {
        $this->removeIDFilterIfNeeded();

        $inputs = array_dot(Input::all());

        $inputs = array_filter($inputs, function ($input) {
            return $input !== '' && !is_null($input);
        });

        if (empty($inputs)) {
            return [];
        }

        $params = [];

        foreach ($inputs as $key => $value) {
            array_set($params, $key, $value);
        }

        $conditions = [];



        foreach ($this->filters() as $filter) {
            $conditions[] = $filter->condition($params);
        }

        return array_filter($conditions);
    }

    /**
     * Add a filter to grid.
     *
     * @param AbstractFilter $filter
     *
     * @return AbstractFilter
     */
    public function addFilter(AbstractFilter $filter)
    {
        $filter->setParent($this);

        return $this->filters[] = $filter;
    }

    /**
     * Get all filters.
     *
     * @return AbstractFilter[]
     */
    public function filters()
    {
        return $this->filters;
    }

    public function extendFilter($method,$class){
        $this->extension[$method]=$class;
    }


    /**
     * Execute the filter with conditions.
     *
     * @return array
     */
    public function execute()
    {
        return $this->model->addConditions($this->conditions())->buildData();
    }

    /**
     * Get the string contents of the filter view.
     *
     * @return \Illuminate\View\View|string
     */
    public function render($type)
    {

        if (empty($this->filters)) {
            return '';
        }
        
        if(($type === 'lineshow' && $this->useModal !='lineshow') || ($type !== 'lineshow' && $this->useModal ==='lineshow')){
            return '';
        }

        if ($this->useModal === 'modal') {
            $this->view = 'admin::filter.modal';

            $script = <<<'EOT'

        $("#filter-modal .submit").click(function () {
            $("#filter-modal").modal('toggle');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });

        $('.filterInputChoice').click(function(){
           obj = $(this);
           var name = obj.parent().attr('name');
           var placeholder = obj.html();
           var old_name = $('#filterColumn').attr('name');

           $('#filterColumn').attr('name',name);
           $('#filterColumn').html(placeholder);

           obj.parent().hide();
           $("li[name='"+old_name+"']").show();

           $('#inputbox').append($('.collectplace').find("div[name='"+old_name+"']").hide());
           $('.collectplace').append($('#inputbox').find("div[name='"+name+"']").show())
})

EOT;
            Admin::script($script);
        }
        elseif($this->useModal === 'lineshow'){
            $this->view = 'admin::filter.lineshow';
            

        }

        return view($this->view)->with([
            'action'  => $this->action ?: $this->urlWithoutFilters(),
            'filters' => $this->filters,
            'hasValueFilter'=>$this->hasValueFilter,
        ]);
    }

    /**
     * Get url without filter queryString.
     *
     * @return string
     */
    protected function urlWithoutFilters()
    {
        $columns = [];

        /** @var Filter\AbstractFilter $filter * */
        foreach ($this->filters as $filter) {
            $columns[] = $filter->getColumn();
        }

        /** @var \Illuminate\Http\Request $request * */
        $request = Request::instance();

        $query = $request->query();
        array_forget($query, $columns);

        $question = $request->getBaseUrl().$request->getPathInfo() == '/' ? '/?' : '?';

        return count($request->query()) > 0
            ? $request->url().$question.http_build_query($query)
            : $request->fullUrl();
    }

    /**
     * Generate a filter object and add to grid.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return $this
     */
    public function __call($method, $arguments)
    {
        if (in_array($method, $this->supports)) {
            $className = '\\Runhare\\Admin\\Grid\\Filter\\'.ucfirst($method);
            $reflection = new ReflectionClass($className);

            return $this->addFilter($reflection->newInstanceArgs($arguments));
        }
        elseif(key_exists($method, $this->extension)){
            $reflection = new ReflectionClass($this->extension[$method]);
            return $this->addFilter($reflection->newInstanceArgs($arguments));
        }
    }

    /**
     * Get the string contents of the filter view.
     *
     * @return \Illuminate\View\View|string
     */
    public function __toString()
    {
        return $this->render();
    }
}
