<div style="padding: 10px;  border-top:1px solid #eee; margin-bottom: 10px;">
    <div class="form-inline">
        <form action="{!! $action !!}" method="get" pjax-container>
            <fieldset>

                @foreach($filters as $filter)
                    {!! $filter->render() !!}
                @endforeach

                <div class="btn-group btn-group-sm">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                    <a href="{!! $action !!}" class="btn btn-warning" ><i class="fa fa-undo"></i></a>
                </div>

            </fieldset>
        </form>
    </div>

    <div style="clear: both;"></div>
</div>