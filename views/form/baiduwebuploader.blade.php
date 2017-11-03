<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">
  
    <label for="{{$id}}" class="col-sm-{{$width['label']}} control-label">{{$label}}</label>

    <div class="col-sm-{{$width['field']}}">

        @include('admin::form.error')


        <!--dom结构部分-->
        <div id="uploader-hare">
            <!--用来存放item-->
            <div id="fileList" class="uploader-list" style="height: 260px;border:1px solid #eee; clear: both;">
                @if($value)
                @foreach($value as $key=>$v)
                <div id="OLDWU_FILE_0{{$loop->index}}" class="file-item thumbnail">
                    <img src="{{config('admin.upload.host')}}{{$v}}">
                    <div class="file-panel" storageid="OLDWU_FILE_{{$loop->index}}" style="display: none;"><span class="cancel">删除</span></div>
                    <input id="IOLDWU_FILE_{{$loop->index}}" type="hidden"  name="imageid[]" value="{{$key}}"/>
                    
                </div>
                @endforeach
                @endif
            </div>
            <div id="filePicker" style="margin-top:10px;">选择图片</div>
        </div>


        <!--input type="file" class="{{$class}}" name="{{$name}}" {!! $attributes !!} /-->

        @include('admin::form.help-block')

    </div>
</div>
