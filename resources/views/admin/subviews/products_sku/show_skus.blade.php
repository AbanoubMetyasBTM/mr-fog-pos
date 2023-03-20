<?php
/**
 *
 * @var $product_skus \Illuminate\Support\Collection
 */
?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/products")}}">Products</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/products/save/$product_obj->pro_id")}}">{{$product_obj->pro_name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Skus</li>
            </ol>
            <h6 class="slim-pagetitle">{{$product_obj->pro_name}} - Skus</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">


                <table id="datatable2" class="table display ">
                    <thead>
                    <tr>
                        <th class="wd-15p"><span>#</span></th>
                        <th class="wd-15p"><span>Item</span></th>
                        <th class="wd-15p"><span>Image</span></th>
                        <th class="wd-15p"><span>Edit</span></th>
                        <th class="wd-15p"><span>Is Active</span></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                        $editListFields = [
                            "ps_box_barcode"           => "text",
                            "ps_item_barcode"          => "text",
                            "ps_box_bought_price"      => "number",
                            "ps_item_bought_price"     => "number",
//                            "ps_item_retailer_price"   => "number",
//                            "ps_item_wholesaler_price" => "number",
//                            "ps_box_retailer_price"    => "number",
//                            "ps_box_wholesaler_price"  => "number",
                        ];
                    ?>

                    <?php foreach ($product_skus as $key => $item): ?>

                        <tr class="<?php echo !is_null($pro_sku_id) && $item->ps_id === $pro_sku_id? "table-danger" : "" ?>" id="row{{$item->ps_id}}">
                            <td>
                                {{$key+1}}
                            </td>

                            <td>
                                {{$item->ps_selected_variant_type_values_text}}
                            </td>

                            <td>
                                <?php if($item->use_default_images==1): ?>
                                    <img src="{{get_image_from_json_obj($product_obj->pro_img_obj)}}" width="200" alt="">
                                <?php else: ?>
                                    <img src="{{get_image_from_json_obj($item->ps_img_obj)}}" width="200" alt="">
                                <?php endif; ?>

                                <br>
                                <br>
                                <a class="btn btn-primary mg-b-6" href="{{url("admin/products-sku/save/$item->ps_id")}}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>

                            <td>
                                <?php foreach($editListFields as $fieldKey=>$fieldType): ?>


                                    <div class="form-group">
                                        <label for="">{{capitalize_string(str_replace("ps","",$fieldKey))}} :</label>

                                        <div>
                                            <?php if ( in_array($fieldKey, ['ps_box_barcode', 'ps_item_barcode'])) :?>
                                                <button type="button" data-field_name="{{$fieldKey}}" class="generate_barcode btn btn-primary mb-2" style="border-radius: 5px; padding: 6px">
                                                    Generate barcode
                                                </button>
                                                <br>
                                            <?php endif; ?>
                                            <?php
                                                $spanClass  = in_array($fieldKey, ['ps_box_barcode', 'ps_item_barcode'])? 'barcode' : '';
                                            ?>
                                            <span class="{{$spanClass}}">
                                                <?php
                                                    echo generate_self_edit_input(
                                                        $url = in_array(
                                                            $fieldKey,
                                                            [
                                                                'ps_box_barcode',
                                                                'ps_item_barcode',
                                                            ])
                                                            ?url("admin/products-sku/edit-sku"):"",
                                                        $item,
                                                        $item_primary_col="ps_id",
                                                        $item_edit_col=$fieldKey,
                                                        $modal_path = \App\models\product\product_skus_m::class,
                                                        $input_type = $fieldType,
                                                        $label = "Click To Edit",
                                                        $func_after_edit = "generate_barcode"
                                                    );
                                                ?>
                                            </span>
                                        </div>
                                    </div>

                                <?php endforeach; ?>
                            </td>

                            <td>
                                <?php
                                    echo generate_multi_accepters(
                                        $accepturl = "",
                                        $item,
                                        $item_primary_col="ps_id",
                                        $accept_or_refuse_col="is_active",
                                        $model = \App\models\product\product_skus_m::class,
                                        $accepters_data = [
                                            "0" => "<i class='fa fa-times'></i>",
                                            "1" => "<i class='fa fa-check'></i>",
                                        ]
                                    );
                                ?>
                            </td>

                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>


            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->
