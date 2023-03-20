<?php
/**
 * @var boolean $show_year_input
 * @var boolean $show_from_date_and_to_date_inputs
 * @var \Illuminate\Support\Collection $sumQuantitiesAtInventories
 * @var \Illuminate\Support\Collection $all_cats
 */
?>
<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Total Quantities In Inventories</li>
            </ol>


            <h6 class="slim-pagetitle">Total Quantities In Inventories {{$type}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper mb-3">

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <?php if($total_inventory_quantities->count()): ?>
                <h6 class="slim-pagetitle mb-2">
                    Quantities On Inventories
                </h6>

                <table id="datatable2" class="table display ">
                    <thead>
                        <tr>
                            <th class="wd-15p"><span>NAME</span></th>
                            <th class="wd-15p"><span>TOTAL ITEMS QUANTITY</span></th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($total_inventory_quantities as $key => $item): ?>

                        <tr id="row">
                            <td>
                                {{$item->item_name}}
                            </td>
                            <td>
                                <span class="square-8 bg-success mg-r-5 rounded-circle"></span>
                                {{$item->total_items_quantity}}
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>

                <?php else : ?>

                @include('global_components.no_results_found')

                <?php endif; ?>

            </div>
        </div>


    </div><!-- container -->
</div><!-- slim-mainpanel -->
