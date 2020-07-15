<?php

namespace Runhare\Admin\Controllers;

trait ModelForm
{
    public function show($id)
    {
        return $this->edit($id);
    }

    public function update($id)
    {
        return $this->form()->update($id);
    }

    public function destroy($id)
    {
        if ($this->form()->destroy($id)) {
            return response()->json([
                'status'  => true,
                'message' => trans('lang.delete_succeeded'),
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => trans('lang.delete_failed'),
            ]);
        }
    }

    public function store()
    {
        return $this->form()->store();
    }

    public function modelSingStore(){
        return $this->form()->modelSingStore();
    }
}
