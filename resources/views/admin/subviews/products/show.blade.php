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
                <li class="breadcrumb-item active" aria-current="page">Products</li>
            </ol>
            <h6 class="slim-pagetitle">Products</h6>
        </div><!-- slim-pageheader -->


        <div class="section-wrapper mb-3">


            <form id="save_form" enctype="multipart/form-data">

                <div class="row">

                    <div class="col-md-12">
                        <p class="mg-b-20 mg-sm-b-40"></p>

                        <div class="row">

                            <?php
                                echo
                                generate_select_tags_v2([
                                    "field_name" => "cat_id",
                                    "label_name" => "Categories",
                                    "text"       => array_merge(["all"], $all_cats->pluck("combined_name")->all()),
                                    "values"     => array_merge(["all"], $all_cats->pluck("cat_id")->all()),
                                    "data"       => $request_data ?? "",
                                    "grid"       => "col-md-6",
                                    "class"      => "form-control select2_search"
                                ]);

                                echo
                                generate_select_tags_v2([
                                    "field_name" => "brand_id",
                                    "label_name" => "Brands",
                                    "text"       => array_merge(["all"], $all_brands->pluck("brand_name")->all()),
                                    "values"     => array_merge(["all"], $all_brands->pluck("brand_id")->all()),
                                    "data"       => $request_data ?? "",
                                    "grid"       => "col-md-6",
                                    "class"      => "form-control select2_search"
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

            <?php if(havePermission("admin/products","add_action")): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/products/save")}}">
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
                            <th class="wd-15p"><span>#</span></th>
                            <th class="wd-15p"><span>Image</span></th>
                            <th class="wd-15p"><span>Product Name</span></th>
                            <th class="wd-15p"><span>Category Name</span></th>
                            <th class="wd-15p"><span>Brand Name</span></th>
                            <th class="wd-15p"><span>Actions</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->pro_id}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    <img src="{{get_image_from_json_obj($item->pro_img_obj)}}" width="50" alt="">
                                </td>
                                <td>{{$item->pro_name}}</td>
                                <td>{{$item->cat_name}}</td>
                                <td>{{$item->brand_name}}</td>

                                <td>

                                    <?php if(havePermission("admin/products","edit_action")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/products-sku/show/$item->pro_id")}}">
                                            SKUs
                                        </a>
                                    <?php endif; ?>

                                    <?php if(havePermission("admin/products","edit_action")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/products/save/$item->pro_id")}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if(havePermission("admin/products","delete_action")): ?>
                                        <a href='#confirmModal'
                                           data-toggle="modal"
                                           data-effect="effect-super-scaled"
                                           class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                           data-tablename="{{\App\models\product\products_m::class}}"
                                           data-deleteurl="{{url("/admin/products/delete")}}"
                                           data-itemid="{{$item->pro_id}}">
                                            <i class="fa fa-remove"></i>
                                        </a>
                                    <?php endif; ?>


                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>

                    {!! $results->links() !!}

                <?php else : ?>

                    @include('global_components.no_results_found')

                <?php endif; ?>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->
