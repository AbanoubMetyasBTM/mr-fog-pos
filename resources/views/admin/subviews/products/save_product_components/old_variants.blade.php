<?php if(isset($old_variants)): ?>
    <?php foreach($old_variants as $key=>$old_variant): ?>
        <div class="row variation_type_parent_div" id="variant_type_row_{{$old_variant->variant_type_id}}">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Variant Name</label>
                    <br>

                    <span>
                        <?php
                            echo generate_self_edit_input(
                                $url = "",
                                $old_variant,
                                $item_primary_col="variant_type_id",
                                $item_edit_col="variant_type_name",
                                $modal_path = \App\models\product\product_variant_types_m::class,
                                $input_type = "text",
                                $label = "Click To Edit",
                                $func_after_edit = ""
                            );
                        ?>
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Add Variant Values</label>
                    <input type="text" class="form-control add_new_variant_value">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group variant_values_parent_div">
                    <label for="">Variant Values</label> <br>


                    <div class="original_variant_value_div mb-2 mr-2 hide">
                        <div class="btn btn-info btn_shape">
                            <input type="hidden" class="new_variant_values" name="new_variant_values_for_old_items_{{$old_variant->variant_type_id}}[]" value="">
                            <span class="variant_title">
                                tag
                            </span>

                            <a href="#" class="text-danger remove_variant_value">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>

                    <?php
                        $variantValues = $old_variants_values->where("variant_type_id",$old_variant->variant_type_id)->all();
                    ?>

                    <?php foreach($variantValues as $key2=>$variantValue): ?>
                        <div class="original_variant_value_div mb-2 mr-2" id="original_variant_value_div_{{$variantValue->vt_value_id}}">
                            <div class="btn btn-info btn_shape">
                                <span class="variant_title">

                                    <span>
                                        <?php
                                            echo generate_self_edit_input(
                                                $url = "",
                                                $variantValue,
                                                $item_primary_col="vt_value_id",
                                                $item_edit_col="vt_value_name",
                                                $modal_path = \App\models\product\product_variant_type_values_m::class,
                                                $input_type = "text",
                                                $label = "Click To Edit",
                                                $func_after_edit = ""
                                            );
                                        ?>
                                    </span>

                                </span>

                                <?php if(havePermission("admin/products","delete_variant")): ?>
                                    <a href='#confirmModal'
                                       data-toggle="modal"
                                       data-effect="effect-super-scaled"
                                       class="text-danger modal-effect confirm_remove_item"
                                       data-tablename="{{\App\models\product\product_variant_type_values_m::class}}"
                                       data-deleteurl="{{url("/admin/products/delete-variant-type-value")}}"
                                       data-itemid="{{$variantValue->vt_value_id}}"
                                       data-trid="original_variant_value_div_{{$variantValue->vt_value_id}}"
                                    >
                                        <i class="fa fa-remove"></i>
                                    </a>
                                <?php endif; ?>

                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
            <div class="col-md-2">
                <?php if(havePermission("admin/products","delete_variant")): ?>
                    <div class="form-group">
                        <label for="">Delete Variation</label> <br>


                        <a href='#confirmModal'
                           data-toggle="modal"
                           data-effect="effect-super-scaled"
                           class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                           data-tablename="{{\App\models\product\product_variant_types_m::class}}"
                           data-deleteurl="{{url("/admin/products/delete-variant-type")}}"
                           data-itemid="{{$old_variant->variant_type_id}}"
                           data-trid="variant_type_row_{{$old_variant->variant_type_id}}"
                        >
                            <i class="fa fa-remove"></i>
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
