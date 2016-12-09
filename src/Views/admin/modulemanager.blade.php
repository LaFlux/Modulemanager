@extends('Dashboard::dashboard.dashboard')
@section('content-header')

    <!-- Navigation Starts-->
    @include('Dashboard::dashboard.partials.headersidebar')
    <!-- Navigation Ends-->

@stop
@section('content-area')

 <!-- page content -->
    <div class="right_col"  role="main">
          <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <h2>{{$title}}</h2>
                </div>
            </div>
        </div>

        <div class="row">
              <?php $count = 1;?>
              @foreach($active_template_positions as $item)
                  <div class="col-md-6 col-sm-6 col-xs-12 pull-left">
                    <div class="x_panel">
                      <div class="x_title">
                        <h2>{{$item['title']}} <small>({{$item['position']}})</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                          <li class="pull-right"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                          </li>
                          <li class="dropdown pull-right">
                            <a href="{{route('extensionsvalley.admin.addmodules',['position' => $item['position'],'theme_layout' => $item['layout']])}}" data-remote="false" data-toggle="modal" data-target="#module_popup"><i class="fa fa-plus-square"></i></a>

                          </li>
                        </ul>
                        <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                      @foreach(ExtensionsValley\Modulemanager\Models\Modulemanager::Where('position',$item['position'])->OrderBy('ordering','ASC')->get() as $moditem)
                          <div class="x_panel mod_{{$item['position']}}{{$moditem->id}}">
                            <div class="x_title" style="border-bottom:0px;">
                              <h2>{{$moditem->module_title}}
                              <small>({{$moditem->vendor}}- {{$moditem->module_name}})</small>
                              </h2>
                              <ul class="nav navbar-right panel_toolbox">
                              <li class="pull-right">
                              <a
                              onclick="cofirmModuleRemoval('{{$item['position']}}','{{$moditem->id}}')"><i class="fa fa-close"></i></a>
                              </li>
                              <li class="pull-right">
                              <a href="{{route('extensionsvalley.admin.addmodules',['position' => $item['position'],'theme_layout' => $item['layout'], 'id' => $moditem->id])}}" data-remote="false" data-toggle="modal" data-target="#module_popup"><i class="fa fa-pencil-square-o"></i></a>
                              </li>
                              </ul>
                               <div class="clearfix"></div>
                               <div class="x_content">
                               Ordering :{{$moditem->ordering}}
                               Display on all pages :{{($moditem->is_all_page == 1) ? 'Yes' : 'No'}}
                               </div>
                              </div>
                          </div>
                      @endforeach
                      </div>
                  </div>
                  </div>
              @endforeach
        </div>
      </div>

    </div>
<!-- Default bootstrap modal example -->
<div class="modal fade" id="module_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  {!!Form::open(array('route' => 'extensionsvalley.admin.savemodules', 'method' => 'post'))!!}
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Module Assignment to Pages</h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save & Close</button>
      </div>
    </div>
  </div>
   <input type="hidden" name="accesstoken" value="{{base64_encode('extensionsvalley.modulemanager.modulemanager')}}" />
  {!! Form::token() !!}
  {!! Form::close() !!}
</div>
<script type="text/javascript">
  $("#module_popup").on("show.bs.modal", function(e) {
    var link = $(e.relatedTarget);
    $(this).find(".modal-body").load(link.attr("href"));
  });
  $("#module_popup").on('hide.bs.modal', function () {
    tinyMCE.editors=[];
    });
  jQuery(document).ready(function(){
    jQuery(document).on('change','.dropdown-ajax-trigger',function(){
      if(jQuery(this).val() == -1){
        jQuery("#custom_html").removeClass('hide');
        return true;
      }else{
        jQuery("#custom_html").addClass('hide');
      }
      var target = jQuery(this).attr('data-target');
      var url = jQuery(this).attr('data-url');
      var data = "";
      jQuery.ajax({
        type: "GET",
        url: url,
        data:'id='+jQuery(this).val(),
          success: function(data) {
            if(data != 0){
              jQuery(target).html(data);
if(jQuery('#param_field').length){
    jQuery('select[name="module_params['+jQuery('#param_field').val()+']"]')
    .val(jQuery('#param_vals').val());
}
            }
          }
        });

    });

    jQuery(document).on('click','.check_all_menu',function(){
      var class_name = jQuery(this).attr('id');
      if(jQuery("."+class_name).length > 0){
        if(jQuery(this).is(':checked')){
          jQuery("."+class_name).prop('checked','checked');
        }else{
          jQuery("."+class_name).removeAttr('checked');
        }
      }
    });
    jQuery(document).on('change','.is_all_page',function(){
      if(jQuery(this).val() == 0){
        jQuery("#menu_items_hidden").removeClass('hide');
      }else{
        jQuery("#menu_items_hidden").addClass('hide');
      }
    });
  });
  function cofirmModuleRemoval(position,module_id){
    if(confirm('Confirm removing the module from this position ?')){
      var data = "position="+position+"&module_id="+module_id+"&accesstoken="+jQuery('input[name=accesstoken]').val();
      jQuery.ajax({
        type: "GET",
        url: "{{route('extensionsvalley.admin.removemodules')}}",
        data:data,
          success: function(response) {
            if(response == 1){
              jQuery(".mod_"+position+module_id).remove();
            }else{
              alert("Access Permission Denied!");
            }
          }
        });
    }
  }
</script>
    <!-- /page content -->
@stop

