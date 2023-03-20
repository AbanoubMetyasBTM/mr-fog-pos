<?php
    /** @var App\form_builder\FormBuilder $builder */
    /** @var Eloquent $data_object */
    /** @var \Illuminate\Database\Eloquent\Collection $all_langs */

?>

<?php
    $custom_attrs=[];
    if (isset($builder->cust_translate_fields_attrs)){
        $custom_attrs=$builder->cust_translate_fields_attrs;
    }

?>

<div class="wizard6">
    <?php foreach($all_langs as $key=>$lang): ?>

    <h3>
        <?php if(false): ?>
            <img src="{{get_image_from_json_obj($lang->lang_img_obj)}}" width="25px" height="25px">
            &nbsp;&nbsp;
        <?php endif; ?>

        {{$lang->lang_text}} <span style="color: red;font-weight: bold;">*</span>
    </h3>

    <section data-parentSection="yes">
        <div class="row">
            @include("general_form_blocks.save_translate_fields_inner_block")
        </div>
    </section>
    <?php endforeach; ?>
</div>

