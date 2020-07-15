<?php

namespace Runhare\Admin\Controllers;

use Runhare\Admin\Auth\Database\Administrator;
use Runhare\Admin\Auth\Database\Permission;
use Runhare\Admin\Auth\Database\Role;
use Runhare\Admin\Facades\Admin;
use Runhare\Admin\Form;
use Runhare\Admin\Grid;
use Runhare\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class UserController extends Controller
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
            $content->header(trans('lang.administrator'));
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
            $content->header(trans('lang.administrator'));
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
            $content->header(trans('lang.administrator'));
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
        return Administrator::grid(function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->username(trans('lang.username'));
            $grid->name(trans('lang.name'));
            $grid->roles(trans('lang.roles'))->pluck('name')->label();
            $grid->created_at(trans('lang.created_at'));
            $grid->updated_at(trans('lang.updated_at'));

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                if ($actions->getKey() == 1) {
                    $actions->disableDelete();
                }
            });

            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });

            $grid->disableExport();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Administrator::form(function (Form $form) {
            $form->display('id', 'ID');

            $form->text('username', trans('lang.username'))->rules('required');
            $form->text('name', trans('lang.name'))->rules('required');
            $form->image('avatar', trans('lang.avatar'));
            $form->password('password', trans('lang.password'))->rules('required|confirmed');
            $form->password('password_confirmation', trans('lang.password_confirmation'))->rules('required')
                ->default(function ($form) {
                    return $form->model()->password;
                });

            $form->ignore(['password_confirmation']);

            $form->multipleSelect('roles', trans('lang.roles'))->options(Role::all()->pluck('name', 'id'));
            $form->multipleSelect('permissions', trans('lang.permissions'))->options(Permission::all()->pluck('name', 'id'));

            $form->display('created_at', trans('lang.created_at'));
            $form->display('updated_at', trans('lang.updated_at'));

            $form->saving(function (Form $form) {
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }
            });
        });
    }
}
