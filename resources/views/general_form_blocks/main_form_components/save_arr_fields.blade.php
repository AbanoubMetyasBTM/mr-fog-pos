<?php
    /** @var object $item_data  */
    /** @var \App\form_builder\FormBuilder $builderObj  */
    /** @var \Illuminate\Support\Collection $all_langs  */
?>

<?php if(count($builderObj->array_fields) > 0): ?>
    <?php foreach($builderObj->array_fields as $array_field_key=>$array_field): ?>
        <div class="section-wrapper mg-t-20">
            <label class="section-title">{{$array_field["label"]}}</label>
            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="row">

                <?php

                $arr_normal_fields = [];

                foreach ($array_field["fields"] as $normalField){
                    if(strpos($normalField,"trans") !== false){
                        foreach ($all_langs as $lang){
                            $arr_normal_fields[] = $array_field_key."_".$normalField."_".$lang->lang_title;
                        }
                    }
                    else{
                        $arr_normal_fields[] = $array_field_key."_".$normalField;
                    }
                }

                $attrs = generate_default_array_inputs_html(
                    $arr_normal_fields,
                    $data = "",
                    $key_in_all_fields="yes",
                    $requried="",
                    $grid_default_value=$array_field["default_grid"]
                );

                foreach ($arr_normal_fields as $field){
                    $attrs[0][$field]=capitalize_string($field);
                }

                $custom_attrs_fields=[
                    "custom_labels"   => "0",
                    "custom_required" => "2",
                    "custom_types"    => "3",
                    "custom_classes"  => "5",
                    "custom_grid"     => "6"
                ];


                foreach ($custom_attrs_fields as $custom_attrs_key=>$custom_attrs_field){

                    if(!isset($array_field[$custom_attrs_key]))continue;

                    foreach ($array_field[$custom_attrs_key] as $cust_key=>$cust_val){

                        if(strpos($cust_key,"trans") !== false){
                            foreach ($all_langs as $lang){
                                if($custom_attrs_field=="0"){
                                    $attrs[$custom_attrs_field][$array_field_key."_".$cust_key."_".$lang->lang_title] = $cust_val." - ".$lang->lang_title;
                                }
                                else{
                                    $attrs[$custom_attrs_field][$array_field_key."_".$cust_key."_".$lang->lang_title] = $cust_val;
                                }
                            }
                        }
                        else{
                            $attrs[$custom_attrs_field][$array_field_key."_".$cust_key]=$cust_val;
                        }

                    }
                }

                echo generate_array_input_v2(
                    reformate_arr_without_keys($attrs[0]),
                    reformate_arr_without_keys($attrs[1]),
                    reformate_arr_without_keys($attrs[2]),
                    reformate_arr_without_keys($attrs[3]),
                    reformate_arr_without_keys($attrs[4]),
                    reformate_arr_without_keys($attrs[5]),
                    $add_tiny_mce="",
                    $default_values=array(),
                    $data= $item_data->{$array_field_key} ?? "",
                    reformate_arr_without_keys($attrs[6])
                );

                ?>

            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
