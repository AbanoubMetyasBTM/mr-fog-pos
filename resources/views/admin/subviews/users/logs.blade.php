<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">سجل العمليات بالمsaveه</li>
            </ol>
            <h6 class="slim-pagetitle">سجل العمليات بالمحفظه</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">
            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <?php if(is_array($results->all()) && count($results->all())): ?>

                    <table id="datatable2" class="table display ">
                        <thead>
                        <tr>
                            <th class="wd-15p"><span>#</span></th>
                            <th class="wd-15p"><span>العنوان</span></th>
                            <th class="wd-15p"><span>نوع العمليه</span></th>
                            <th class="wd-15p"><span>المبلغ</span></th>
                            <th class="wd-15p"><span>التاريخ</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>
                            <tr class="{{($item->transaction_operation == "increase") ? "successColor" : "errorColor"}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>{{$item->transaction_notes}}</td>
                                <td>{{($item->transaction_operation == "increase") ? "اضافه للمحفظه" : "خصم من المحفظه"}}</td>
                                <td>
                                    <b>{{number_format($item->transaction_amount, 2)}}</b>
                                </td>
                                <td>{{$item->transaction_at}}</td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>

                    @include('global_components.pagination')

                <?php else : ?>

                    @include('global_components.no_results_found')

                <?php endif; ?>


            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->
