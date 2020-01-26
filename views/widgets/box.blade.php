<div {!! $attributes !!}>
    <div class="box-header with-border">
        <h3 class="box-title">{{ $title }}</h3>
        <div class="box-tools pull-right">
            @foreach($tools as $tool)
                {!! $tool !!}
                @endforeach
        </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    <div class="box-body" style="display: block;">
        {!! $content !!}
    </div><!-- /.box-body -->
     @if(count($footer))
    <div class="box-footer clearfix">
              @foreach($footer as $ft)
              {!! $ft !!}
              @endforeach
    </div>
    @endif
</div>