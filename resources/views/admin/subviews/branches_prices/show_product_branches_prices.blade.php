<?php
/**
 *
 * @var $results \Illuminate\Support\Collection
 */

    $header_title = $product->pro_name

?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Branches</li>
                <li class="breadcrumb-item active" aria-current="page">Product Branches Prices</li>
                <li class="breadcrumb-item active" aria-current="page">{{$header_title}}</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header_title}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <?php if(is_array($results->all()) && count($results->all())): ?>

                    <table id="datatable2" class="table display ">
                        <thead>
                            <tr>
                                <th class="wd-15p"><span>#</span></th>
                                <th class="wd-15p" style="text-align: center"><span>Branch Name</span></th>
                                <th class="wd-15p" style="text-align: center"><span>Product Name</span></th>
                                <th class="wd-15p" style="text-align: center"><span>Online Item</span></th>
                                <th class="wd-15p" style="text-align: center"><span>Online Box</span></th>
                                <th class="wd-15p" style="text-align: center"><span>Retailer Item</span></th>
                                <th class="wd-15p" style="text-align: center"><span>Wholesaler Item</span></th>
                                <th class="wd-15p" style="text-align: center"><span>Retailer Box</span></th>
                                <th class="wd-15p" style="text-align: center"><span>Wholesaler Box</span></th>
                                <th class="wd-15p" style="text-align: center"><span>Is Active</span></th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                            $pricesFields = [
                                'online_item_price',
                                'online_box_price',
                                'item_retailer_price',
                                'item_wholesaler_price',
                                'box_retailer_price',
                                'box_wholesaler_price',
                            ];
                        ?>

                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->id}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    {{$item->branch_name}}
                                </td>
                                <td style="text-align: center">
                                    {{$item->product_name}} -
                                    {{$item->ps_selected_variant_type_values_text}}
                                </td>
                                @foreach($pricesFields as $field)
                                    <td style="text-align: center">
                                        <?php
                                            echo generate_self_edit_input(
                                                $url = url("admin/branches-prices/update-branch-prices"),
                                                $item,
                                                $item_primary_col="id",
                                                $item_edit_col="$field",
                                                $modal_path = \App\models\branch\branch_prices_m::class,
                                                $input_type = "number",
                                                $label = "Click To Edit",
                                                $func_after_edit = ""
                                            );
                                        ?>
                                    </td>

                                @endforeach
                                <td style="text-align: center">
                                    <?php
                                        echo generate_multi_accepters(
                                            $accepturl = "",
                                            $item,
                                            $item_primary_col="id",
                                            $accept_or_refuse_col="is_active",
                                            $model = \App\models\branch\branch_prices_m::class,
                                            $accepters_data = [
                                                "0" => "Not Active",
                                                "1" => "Active",
                                            ]
                                        );
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>

                <?php else : ?>

                    @include('global_components.no_results_found')

                <?php endif; ?>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->
