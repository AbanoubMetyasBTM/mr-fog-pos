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

<?php foreach($all_langs as $key=>$lang): ?>
<div class="card card-default">
    <div class="card-heading cursor_pointer" data-toggle="collapse" href=".collapse_div_{{$lang->lang_id}}">
        Translate Data - {{$lang->lang_text}}
    </div>
    <div class="card-body collapse {{$key==0?"show":"show"}} collapse_div_{{$lang->lang_id}}">
        @include("general_form_blocks.save_translate_fields_inner_block")
    </div>
</div>
<?php endforeach; ?>
