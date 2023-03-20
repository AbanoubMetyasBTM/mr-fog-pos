<?php


if($url==""){
    $url=url("/general_self_edit");
}

?>
<span class="general_self_edit"
      data-url="{{$url}}"
      data-row_primary_col="{{$item_primary_col}}"
      data-row_id="<?=$item_obj->{$item_primary_col}?>"
      data-tablename="{{$modal_path}}"
      data-field_name="{{$item_edit_col}}"
      data-field_value="<?=$item_obj->{$item_edit_col}?>"
      title="{{$label}}"
      data-input_type="{{$input_type}}"
      data-func_after_edit="{{$func_after_edit}}"
>

    <?=$item_obj->{$item_edit_col} ?>
    <i class="fa fa-edit"></i>
</span>
