<?php
namespace ExtensionsValley\Modulemanager\Validators;

class ModulemanagerValidation
{

    public function getRules()
    {
        return [
            'title' => 'required|max:200|unique:menu_types',
            'status' => 'required',
            'position' => 'required',
            'is_all_page' => 'required',
        ];
    }

    public function getUpdateRules($menutypes)
    {
        return [
            'title' => 'required|max:200|unique:menu_types,title,' . $menutypes->id,
            'status' => 'required',
            'position' => 'required',
            'is_all_page' => 'required',
        ];
    }

}
