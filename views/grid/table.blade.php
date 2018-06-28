<div class="box">
    <div class="box-header">

        <h3 class="box-title"></h3>

        <div class="pull-right">
            {!! $grid->renderFilter('right') !!}
            {!! $grid->renderExportButton() !!}
            {!! $grid->renderCreateButton() !!}
        </div>

        <span>
            {!! $grid->renderHeaderTools() !!}
        </span>
        

    </div>
    
    
    {!! $grid->renderFilter('lineshow') !!}
       
    
    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding" style="overflow:scroll;max-height:500px;" id="grad_table">
        <table class="table table-hover table-striped table-condensed">
            <tr>
                @foreach($grid->columns() as $column)
                <th>{{$column->getLabel()}}{!! $column->sorter() !!}</th>
                @endforeach
            </tr>

            @foreach($grid->rows() as $row)
            <tr {!! $row->getRowAttributes() !!}>
                @foreach($grid->columnNames as $name)
                <td {!! $row->getColumnAttributes($name) !!}>
                    {!! $row->column($name) !!}
                </td>
                @endforeach
            </tr>
            @endforeach
        </table>
    </div>
    <div class="box-footer clearfix">
        {!! $grid->paginator() !!}
    </div>
    <!-- /.box-body -->
</div>
<script type="text/javascript">
    var windowHeight = $(window).height();
    var maxHeight = windowHeight * 500 / 703;
    $('#grad_table').css('max-height',maxHeight);
</script>
