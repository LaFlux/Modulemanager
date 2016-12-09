<div class="row">
  <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <div class="form-group {{ $errors->has('module_name') ? 'has-error' : '' }} control-required">

                    {!! Form::label('module', 'Module Name') !!}<span class="mand_star"> *</span>
                     {!! Form::text('module_name', isset($module_info->module_title) ? $module_info->module_title : null , [
                        'class'       => 'form-control',
                        'placeholder' => 'Your custom Title ',
                        'required'    => 'required'
                    ]) !!}
                    <span class="error_span">{{ $errors->first('module_name') }}</span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <div class="form-group {{ $errors->has('module_id') ? 'has-error' : '' }} control-required">
                <?php
                    if(isset($module_info->module_id)){
                        if(isset($module_info->custom_html))
                            $selected_module = -1;
                        else
                            $selected_module = $module_info->module_id;
                    }else{
                        $selected_module = null;
                    }
                ?>
                    {!! Form::label('modules', 'Modules') !!}<span class="mand_star"> *</span>
                    {!! Form::select('module_id',  array("0"=> "Select Module","1" => " Theme Static Content ","-1" => " Custom HTML Content ") + $allmodules->toArray(), $selected_module, [
                        'class'       => 'form-control dropdown-ajax-trigger',
                        'required'    => 'required',
                        'data-target' => '#dynamic_params',
                        'data-url'    => "getmoduleparam"
                    ]) !!}
                    <span class="error_span">{{ $errors->first('module_id') }}</span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-14 col-md-4 col-lg-4">
                <div class="form-group {{ $errors->has('ordering') ? 'has-error' : '' }} control-required">
                    {!! Form::label('ordering', 'Ordering') !!}<span class="mand_star"> *</span>
                    {!! Form::number('ordering', isset($module_info->ordering) ? $module_info->ordering : null, [
                        'class'       => 'form-control',
                        'placeholder' => 'Order of module',
                        'required'    => 'required'
                    ]) !!}
                    <span class="error_span">{{ $errors->first('ordering') }}</span>
                </div>
            </div>
  </div>
      <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <div class="form-group {{ $errors->has('layout_option') ? 'has-error' : '' }} control-required">
                <?php
                    if(isset($module_info->layout)){
                        if($theme_layout == $module_info->layout){
                            $temp_layout = 0;
                        }else{
                            $temp_layout = 1;
                        }
                    }else{
                        $temp_layout = 0;
                    }
                ?>
                    {!! Form::label('temp_layout', 'Layout Option') !!}<span class="mand_star"> *</span>
                     {!! Form::select('layout_option',  array('1' => 'Module Layout','0'=>'Theme Layout'), $temp_layout, [
                        'class'       => 'form-control',
                    ]) !!}
                    <span class="error_span">{{ $errors->first('layout_option') }}</span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-14 col-md-4 col-lg-4">
                <div class="form-group {{ $errors->has('theme_layout') ? 'has-error' : '' }} control-required">
                    {!! Form::label('theme_layout', 'Theme Layout') !!}<span class="mand_star"> *</span>
                    {!! Form::text('theme_layout', $theme_layout, [
                        'class'       => 'form-control',
                        'placeholder' => 'ThemeLayout',
                        'required'    => 'required',
                        'readonly'    => 'readonly'
                    ]) !!}
                    <span class="error_span">{{ $errors->first('theme_layout') }}</span>
                </div>
            </div>
             <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <div class="form-group {{ $errors->has('is_all_page') ? 'has-error' : '' }} control-required">
                    {!! Form::label('is_all_page', 'On All Pages') !!}<span class=" tooltip mand_star"> *</span>
                    <a data-toggle="tooltip" title="The module will display only if the page layout is proper and module position is avialable on that layout.">(?)</a>
                     {!! Form::select('is_all_page',  array('1'=>'Yes','0' => 'No'),  isset($module_info->is_all_page) ? $module_info->is_all_page : null, [
                        'class'       => 'form-control is_all_page',
                    ]) !!}
                    <span class="error_span">{{ $errors->first('is_all_page') }}</span>
                </div>
            </div>
    </div>
    <div class="row hide" id="menu_items_hidden">
    <?php
        if(isset($module_info->pages)){
            $page_list = explode(',',$module_info->pages);
        }else{
            $page_list = [];
        }
    ?>
    @if(sizeof($menuTypes))
    @foreach($menuTypes as $mtype)
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
            {!! Form::label('is_all_page', $mtype->title) !!}
            <input type="checkbox" id="check_all_{{$mtype->id}}" class="check_all_menu"/>
            @if(!empty($menulist))
                @foreach(\DB::table('menu_items')->WhereNull('deleted_at')->where('status',1)->where('menu_type',$mtype->id)->get() as $menuitems)
                        <div class="indi_items" style="margin-left: 20px;">
                        <input type="checkbox" class="check_all_{{$mtype->id}}" name="menu_items[]" value="{{$menuitems->source}}"
                         @if(in_array($menuitems->source,$page_list))
                            checked="checked"
                         @endif
                         > {{$menuitems->menu_name}}
                        </div>
                @endforeach
            @endif

        </div>
    @endforeach
    @endif
    </div>
    <div class="row" id="dynamic_params">
    </div>
    <div class="row hide" id="custom_html">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
         {!! Form::label('html_content', 'HTML Content') !!}
        <textarea id="custom_html" name="custom_html" class="texteditor">
            @if(isset($module_info->custom_html)) {{$module_info->custom_html}} @endif
        </textarea>
    </div>
    </div>
  <input type="hidden" name="position" value="{{$position}}" />
</div>
@if(isset($module_info->id))
<input type="hidden" id="update_flag" name="update_flag" value="1"/>
<input type="hidden" id="id" name="id" value="{{$module_info->id}}"/>
@endif
@if(isset($module_info->params))
    <?php
           $params_data =  json_decode($module_info->params);
           if(sizeof($params_data)){
           foreach($params_data as $key => $vals){
            echo '<input type="hidden" id="param_field" value="'.$key.'"/>';
            echo '<input type="hidden" id="param_vals" value="'.$vals.'"/>';
           }}
    ?>
@endif
<script type="text/javascript">
function loadEditor(){
    //tinyMCE.remove();
    tinymce.init({
            selector: 'textarea.texteditor', theme: "modern",
            subfolder: "",
            height : "200",
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor filemanager"
            ],
            image_advtab: true,
            content_css: '{{url("/")}}/packages/extensionsvalley/dashboard/js/tinymce/skins/lightgray/content.min.css',
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
            style_formats: [
                {title: 'Bold text', inline: 'b'},
                {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                {title: 'Example 1', inline: 'span', classes: 'example1'},
                {title: 'Example 2', inline: 'span', classes: 'example2'},
                {title: 'Table styles'},
                {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
            ],
        });

}
jQuery(document).ready(function(){
    loadEditor();
    jQuery('.is_all_page').trigger('change');
    jQuery('.dropdown-ajax-trigger').trigger('change');
    if(jQuery('select[name="module_id"]').val() == -1){
        loadEditor();
    }
});
jQuery(window).load(function() {
     loadEditor();
});
jQuery(document).on('focusin', function(e) {
    if ($(e.target).closest(".mce-window").length) {
        e.stopImmediatePropagation();
    }
});
</script>

