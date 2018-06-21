<?php

namespace Runhare\Admin\Controllers;

use Runhare\Admin\Auth\Database\Permission;
use Runhare\Admin\Auth\Database\Role;
use Runhare\Admin\Facades\Admin;
use Runhare\Admin\Form;
use Runhare\Admin\Grid;
use Runhare\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class RoleController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('lang.roles'));
            $content->description(trans('lang.list'));
            $content->body($this->grid()->render());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header(trans('lang.roles'));
            $content->description(trans('lang.edit'));
            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('lang.roles'));
            $content->description(trans('lang.create'));
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Role::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->slug(trans('lang.slug'));
            $grid->name(trans('lang.name'));

            $grid->created_at(trans('lang.created_at'));
            $grid->updated_at(trans('lang.updated_at'));

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                if ($actions->row->slug == 'administrator') {
                    $actions->disableDelete();
                }
            });

            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Admin::form(Role::class, function (Form $form) {
            $form->display('id', 'ID');

            $form->text('slug', trans('lang.slug'))->rules('required');
            $form->text('name', trans('lang.name'))->rules('required');
            $form->multipleSelect('permissions', trans('lang.permissions'))->options(Permission::all()->pluck('name', 'id'));

            $form->display('created_at', trans('lang.created_at'));
            $form->display('updated_at', trans('lang.updated_at'));
        });
    }
}
