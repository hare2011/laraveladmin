<?php

namespace Runhare\Admin\Controllers;

use Runhare\Admin\Auth\Database\Menu;
use Runhare\Admin\Auth\Database\Role;
use Runhare\Admin\Facades\Admin;
use Runhare\Admin\Form;
use Runhare\Admin\Layout\Column;
use Runhare\Admin\Layout\Content;
use Runhare\Admin\Layout\Row;
use Runhare\Admin\TreeRight as Tree;
use Runhare\Admin\Grid;
use Runhare\Admin\Widgets\Box;
use Illuminate\Routing\Controller;

class MenuRightController extends Controller
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
            $content->header('菜单权限');
            $content->description(trans('admin::lang.list'));

            $content->row(function (Row $row) {
                $row->column(12, $this->treeView()->render());
            });
        });
    }

    /**
     * Redirect to edit page.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        return redirect()->action(
            '\Runhare\Admin\Controllers\MenuController@edit', ['id' => $id]
        );
    }

    /**
     * @return \Runhare\Admin\Tree
     */
    protected function treeView()
    {
        return Menu::treeright(function (Tree $tree) {
            $tree->disableCreate();
            

            $tree->branch(function ($branch) {
                $payload = "<i class='fa {$branch['icon']}'></i>&nbsp;<strong>{$branch['title']}</strong>";

                if (!isset($branch['children'])) {
                    $uri = admin_url($branch['uri']);

                    $payload .= "&nbsp;&nbsp;&nbsp;<a href=\"$uri\" class=\"dd-nodrag\">$uri</a>";
                }

                return $payload;
            });
        });
    }

    /**
     * Edit interface.
     *
     * @param string $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header(trans('admin::lang.menu'));
            $content->description(trans('admin::lang.edit'));

            $content->row($this->form()->edit($id));
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Menu::form(function (Form $form) {
           // $form->display('id', 'ID');

            //$form->select('parent_id', trans('admin::lang.parent_id'))->options(Menu::selectOptions());
            //$form->text('title', trans('admin::lang.title'))->rules('required');
            //$form->icon('icon', trans('admin::lang.icon'))->default('fa-bars')->rules('required')->help($this->iconHelp());
            $form->text('uri', trans('admin::lang.uri'));
            $form->multipleSelect('roles', trans('admin::lang.roles'))->options(Role::all()->pluck('name', 'id'));

            //$form->display('created_at', trans('admin::lang.created_at'));
            //$form->display('updated_at', trans('admin::lang.updated_at'));
        });
    }

    /**
     * Help message for icon field.
     *
     * @return string
     */
    protected function iconHelp()
    {
        return 'For more icons please see <a href="http://fontawesome.io/icons/" target="_blank">http://fontawesome.io/icons/</a>';
    }
}
