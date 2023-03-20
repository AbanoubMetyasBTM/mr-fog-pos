<?php
/**
 *
 * @var $results \Illuminate\Support\Collection
 */
?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Products Promotions</li>
            </ol>
            <h6 class="slim-pagetitle">Products Promotions</h6>
        </div><!-- slim-pageheader -->


        <div class="section-wrapper mb-3">

            <form id="save_form" enctype="multipart/form-data">

                <div class="row">

                    <div class="col-md-12">

                        <p class="mg-b-20 mg-sm-b-40"></p>

                        <div class="row">

                            <?php


                                echo generate_select_tags_v2([
                                    "field_name"              => "available_promotions",
                                    "label_name"              => "Available Promotions",
                                    "text"                    => ["Yes", "No"],
                                    "values"                  => ["yes", 'no'],
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-6",
                                ]);

                            ?>

                            <div class="col-md-12">
                                <button id="submit" type="submit" class="btn btn-primary bd-0 mt-0 btn-search-date">Get Results</button>
                            </div>

                        </div>

                    </div>
                </div>

            </form>


        </div>

        <div class="section-wrapper">

            <?php if(havePermission("admin/product_promotions","add_action")): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/product-promotions/save")}}">
                        Add New <i class="fa fa-plus"></i>
                    </a>
                </label>
            <?php endif; ?>

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <?php if(is_array($results->all()) && count($results->all())): ?>

                    <table id="datatable2" class="table display ">
                        <thead>
                        <tr>
                            <th class="wd-10p"><span>#</span></th>
                            <th class="wd-10p"><span>Title</span></th>
                            <th class="wd-10p"><span>Discount Percent</span></th>
                            <th class="wd-10p"><span>Branch Name</span></th>
                            <th class="wd-10p"><span>Promo Start At</span></th>
                            <th class="wd-10p"><span>Promo End At</span></th>
                            <th class="wd-10p"><span>Products Names</span></th>
                            <th class="wd-10p"><span>Actions</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->promo_id}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    {{$item->promo_title}}
                                </td>
                                <td>
                                    {{$item->promo_discount_percent}} %
                                </td>
                                <td>
                                    <?php
                                        if (empty($item->branch_name) || is_null($item->branch_name)){
                                            $item->branch_name = 'all branches';
                                        }
                                    ?>
                                    {{$item->branch_name}}
                                </td>
                                <td>
                                    {{$item->promo_start_at}}
                                </td>
                                <td>
                                    {{$item->promo_end_at}}
                                </td>
                                <td>

                                    @if( empty($item->products_names) ||is_null($item->products_names) )
                                        <p>all products</p>

                                    @else
                                        <?php foreach ($item->products_names as $name): ?>
                                            <p>{{$name}}</p>
                                        <?php endforeach; ?>
                                    @endif

                                </td>

                                <td>
                                    <?php if(havePermission("admin/product_promotions", "edit_action")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/product-promotions/save/$item->promo_id")}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>


                                    <?php if(havePermission("admin/product_promotions","delete_action")): ?>
                                        <a href='#confirmModal'
                                           data-toggle="modal"
                                           data-effect="effect-super-scaled"
                                           class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                           data-tablename="{{\App\models\product\product_promotions_m::class}}"
                                           data-deleteurl="{{url("/admin/product-promotions/delete")}}"
                                           data-itemid="{{$item->promo_id}}">
                                            <i class="fa fa-remove"></i>
                                        </a>
                                    <?php endif; ?>


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
