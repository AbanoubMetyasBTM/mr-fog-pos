<div class="slim-pageheader">
    <ol class="breadcrumb slim-breadcrumb">
    </ol>
    <h6 class="slim-pagetitle">Orders Today Grouped By Branches</h6>
</div>

<div class="card card-table">
    <div class="card-header">
        <h6 class="slim-card-title">Total Orders Group By Branch</h6>
    </div><!-- card-header -->
    <div class="table-responsive">
        <table class="table mg-b-0 tx-13">
            <thead>
            <tr class="tx-10">
                <th class="wd-10p pd-y-5">&nbsp;</th>
                <th class="pd-y-5">Branch Name</th>
                <th class="pd-y-5">Total Orders</th>
            </tr>
            </thead>
            <tbody>
            @foreach($limitOrdersGroupedByBranches as $item)

                <?php
                    $branch_img_obj=json_decode($item->branch_img_obj);
                ?>

                <tr>
                    <td class="pd-l-20">
                        <img src="{{url($branch_img_obj->path)}}"
                             class="wd-36 rounded-circle"
                             title="{{$branch_img_obj->title}}"
                             alt="{{$branch_img_obj->alt}}"
                        >
                    </td>
                    <td>
                        {{$item->branch_name}}
                    </td>

                    <td>
                        <a href="{{
                                langUrl('admin/clients-orders?branch_id='.
                                $item->branch_id.'&date_from='.
                                date('Y-m-d').'&date_to='.
                                date('Y-m-d'))
                            }}
                            "
                           class="btn btn-info">
                            {{$item->total_count}}
                        </a>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div><!-- table-responsive -->
</div><!-- card -->
