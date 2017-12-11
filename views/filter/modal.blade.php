@php
$firstFilter = current($filters);
@endphp
<div class="form-inline pull-right">
    <form action="{!! $action !!}" method="get" pjax-container>
        <fieldset>
             <div class="input-group input-group-sm collectplace">
                 <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span id="filterColumn" name='{{$firstFilter->getName()}}'>{{$firstFilter->getLabel()}}</span> 
                        <span class="caret"></span>
                    </button>
                   <ul class="dropdown-menu">
                      @foreach($filters as $filter)             
                            <li  name="{{$filter->getName()}}" @if($filter->getName() === $firstFilter->getName()) style="display:none;" @endif><a href="javascript:void(0)" class="filterInputChoice" >{{$filter->getLabel()}}</a></li>
                       @endforeach
                   </ul>
               </div>
                @php
                    $firstFilter->visibility();
                    $firstFilter->labelVisibility(false);
                    echo $firstFilter->render();
                @endphp
             </div>
            <div class="btn-group btn-group-sm">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                    <a href="{!! $action !!}" class="btn btn-sm btn-warning"><i class="fa fa-undo"></i></a>
    <a href="" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#filter-modal"><i class="fa fa-filter"></i>&nbsp;&nbsp;{{ trans('admin::lang.filter') }}</a>

            </div>

        </fieldset>
    </form>
    <div id="inputbox">
            @foreach($filters as $filter)
              @php
               if($filter->getName() === $firstFilter->getName()) continue;
              @endphp
                {{$filter->visibility(false)}}
                {{$filter->labelVisibility(false)}}
                {!! $filter->render() !!}
            @endforeach
    </div>
</div>
<div class="modal fade" id="filter-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('admin::lang.filter') }}</h4>
            </div>
            <form action="{!! $action !!}" method="get" pjax-container>
                <div class="modal-body">
                    <div class="form">
                        @foreach($filters as $filter)
                            <div class="form-group">
                                {{$filter->visibility()}}
                                {{$filter->labelVisibility()}}
                                {!! $filter->render() !!}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary submit">{{ trans('admin::lang.submit') }}</button>
                    <button type="reset" class="btn btn-warning pull-left">{{ trans('admin::lang.reset') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>