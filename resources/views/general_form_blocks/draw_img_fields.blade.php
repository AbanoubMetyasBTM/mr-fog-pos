<?php if(isset_and_array($builder->img_fields)||isset_and_array($builder->slider_fields)): ?>
<div class="section-wrapper mg-t-20">
    <label class="section-title">Images</label>
    <p class="mg-b-20 mg-sm-b-40"></p>

    <div class="row">
        @include("general_form_blocks.draw_img_fields_inner_block")
    </div>
</div>
<?php endif; ?>
