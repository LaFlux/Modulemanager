<?php
namespace ExtensionsValley\Modulemanager;

use ExtensionsValley\Modulemanager\Validators\ModuleValidation;
use ExtensionsValley\Modulemanager\Models\Modulemanager;
use ExtensionsValley\Basetheme\Helpers\ThemeHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class ModuleController extends Controller
{



    protected $themHelper;

    public function __construct(ThemeHelper $themehelper)
    {

       $this->themeHelper = with(new $themehelper);

    }


    public function viewModuleManager()
    {

        $title = 'Module Manager';

        $positions = new Collection;
        \Event::fire('website.template.positions', [$positions]);
        $active_template_positions = [];

        if(!empty($positions[$this->themeHelper->active_template_name])){
            $template_positions = $positions[$this->themeHelper->active_template_name];
        }

        ##Filter same position
           $filter_array = [];
           $active_template_positions = [];
           foreach($template_positions as $item){
                if(!in_array($item['position'], $filter_array)){
                    $filter_array[] = $item['position'];
                    $active_template_positions[] = $item;
                }
           }

        return \View::make('Modulemanager::admin.modulemanager', compact('title','active_template_positions'));
    }

    public function addModules($position){

        $theme_layout = \Input::get('theme_layout');
        $allmodules = \DB::table('extension_manager')
                            ->selectRaw('CONCAT(vendor, "-", name) as module_name, id')
                            ->Where('status',1)
                            ->Where('package_type','laflux-module')
                            ->WhereNull('deleted_at')
                            ->pluck("module_name",'id');

         ##Get all menu items
        $menuTypes = \DB::table('menu_types')->WhereNull('deleted_at')->where('status',1)->get();
        $menulist = \DB::table('menu_items')->WhereNull('deleted_at')->where('status',1);

        $current_module_id = \Input::get('id');
        if($current_module_id > 0){
           $module_info =  Modulemanager::WhereNull('deleted_at')
                            ->Where('id',$current_module_id)
                            ->first();
        }else{
           $module_info = [];
        }


        return \View::make('Modulemanager::admin.modulepopup', compact('position','allmodules','theme_layout','menuTypes','menulist','module_info'));

    }

    public function getModuleParam(){
        $id = \Input::get('id');
        if($id > 0){
            $moduleinfo = \DB::table('extension_manager')->where('id',$id)->first();
            return \View::make("Modulemanager::admin.moduleparam",compact('moduleinfo'));
        }else{
            return 0;
        }


    }

    public function saveModules(Request $request){

        $module_id = $request->input('module_id');
        $position = $request->input('position');
        $layout_option = $request->input('layout_option');
        $module_title = $request->input('module_name');
        $is_all_page = $request->input('is_all_page');
        $theme_layout = $request->input('theme_layout');
        $module_layout = $request->input('module_layout');
        $menu_items = $request->input('menu_items');
        $module_params = $request->input('module_params');
        $ordering = $request->input('ordering');
        $update_flag = $request->input('update_flag');
        $id = $request->input('id');

        if($module_id == -1){
            $module_id = 1;
            $custom_html = $request->input('custom_html');
        }else{
            $custom_html = "";
        }

        if($module_id == 1){
             $module_layout = $theme_layout;
             $moduleinfo =  \DB::table('extension_manager')
                            ->Where('status',1)
                            ->Where('id',$module_id)
                            ->WhereNull('deleted_at')
                            ->first();
        }else{
             $moduleinfo =  \DB::table('extension_manager')
                            ->Where('status',1)
                            ->Where('id',$module_id)
                            ->Where('package_type','laflux-module')
                            ->WhereNull('deleted_at')
                            ->first();
        }

        //echo $module_id;exit;

        if((sizeof($moduleinfo) <= 0 || trim($module_layout) == "") && $module_id !=1){
                return redirect()->route('extensionsvalley.admin.viewmodulemanager')
                ->with(['error'=> 'Module or module Params are invalid!']);
        }else{

            $param_text = [];
            if(sizeof($module_params)){
                foreach ($module_params as $key => $value) {
                   $param_text[$key] = $value;
                }
            }
            if(sizeof($param_text)){
                $jsonparam = json_encode($param_text);
            }else{
                $jsonparam = '';
            }
            $pages = [];
            for($i = 0; $i < sizeof($menu_items) ; $i++){
                $pages[] = $menu_items[$i];
            }
            $menu_slug = implode(',', $pages);
            $layout = ($layout_option == 0) ? $theme_layout : $module_layout;

            if($update_flag == 1){
                Modulemanager::Where('id',$id)->Update([
                    'module_id' => $module_id
                    ,'module_title' => $module_title
                    ,'module_name' => $moduleinfo->name
                    ,'vendor'=> $moduleinfo->vendor
                    ,'layout' => $layout
                    ,'params' => $jsonparam
                    ,'custom_html' => trim($custom_html) ? $custom_html : NULL
                    ,'position' => $position
                    ,'pages' => $menu_slug
                    ,'ordering' => $ordering
                    ,'is_all_page' => $is_all_page
                    ,'updated_by' => \Auth::guard('admin')->user()->id
                    ]);
            }else{

                Modulemanager::Create([
                    'module_id' => $module_id
                    ,'module_title' => $module_title
                    ,'module_name' => $moduleinfo->name
                    ,'vendor'=> $moduleinfo->vendor
                    ,'layout' => $layout
                    ,'params' => $jsonparam
                    ,'custom_html' => trim($custom_html) ? $custom_html : NULL
                    ,'position' => $position
                    ,'pages' => $menu_slug
                    ,'ordering' => $ordering
                    ,'is_all_page' => $is_all_page
                    ,'created_by' => \Auth::guard('admin')->user()->id
                    ,'updated_by' => \Auth::guard('admin')->user()->id
                    ]);
            }
              return redirect()->route('extensionsvalley.admin.viewmodulemanager')
                ->with(['message'=> 'Module assigned successfully!']);
        }

    }

    public function removeModules(Request $request){

        $module_id = $request->input('module_id');
        $position = $request->input('position');

        Modulemanager::Where('id',$module_id)
                        ->Where('position',$position)
                        ->forceDelete();
        echo 1;
    }



}
