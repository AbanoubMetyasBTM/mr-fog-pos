<?php if(is_array($results->all()) && count($results->all())): ?>
    <div class="col-md-12 order_component_div">
        {{$results->appends(\Illuminate\Support\Facades\Request::except('page'))}}
    </div>
<?php endif; ?>