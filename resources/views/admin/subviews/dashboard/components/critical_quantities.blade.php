<div class="row">
    <div class="col-lg-12 mg-t-20 mg-lg-t-0">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
            </ol>
            <h6 class="slim-pagetitle">Critical Quantities</h6>
        </div>
        <div class="card card-table">
            <div class="card-header">
                <h6 class="slim-card-title"> </h6>
            </div><!-- card-header -->
            <div class="table-responsive">
                <table class="table mg-b-0 tx-13">
                    <thead>
                    <tr class="tx-10">
                        <th class="wd-10p pd-y-5">#</th>
                        <th class="pd-y-5">Inventory Name</th>
                        <th class="pd-y-5">Product Name</th>
                        <th class="pd-y-5">Total Items Quantity</th>
                        <th class="pd-y-5">Quantity Limit</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($criticalQuantities as $key=>$item)

                        <tr>
                            <td> {{$key+1}} </td>
                            <td>
                                {{$item->inv_name}}
                            </td>
                            <td class="tx-12">
                                 {{$item->pro_name}} [{{$item->ps_selected_variant_type_values_text}}]
                            </td>
                            <td>
                                <span class="square-8 bg-success mg-r-5 rounded-circle"></span>

                                {{$item->total_items_quantity}}

                            </td>
                            <td>
                                <span class="square-8 bg-danger mg-r-5 rounded-circle"></span>
                             {{$item->quantity_limit}}
                            </td>
                        </tr>
                    @endforeach

                    </tbody>

                </table>

            </div>
            <div class="card-footer tx-12 pd-y-15 bg-transparent">
                <a href="{{langUrl('admin/inventories-products/show')}}">
                    <i class="fa fa-angle-down mg-r-5"></i>
                    View All Critical Quantities
                </a>
            </div>
        </div>
    </div>
</div>
