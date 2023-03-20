<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Currencies</li>
            </ol>
            <h6 class="slim-pagetitle">Currencies</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <?php if(havePermission("admin/currencies", "add_action")): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/currencies/save")}}"> New Currency <i class="fa fa-plus"></i></a>
                </label>
            <?php endif; ?>

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">
                <?php if(is_array($results->all()) && count($results->all())): ?>

                    <table id="datatable2" class="table display ">
                        <thead>
                            <tr>
                                <th class="wd-15p"><span>#</span></th>
                                <th class="wd-15p"><span>Name</span></th>
                                <th class="wd-15p"><span>Code</span></th>
                                <th class="wd-15p"><span>Rate from {{config("default_currency.label")}}</span></th>
                                <th class="wd-15p"><span>Last Updated</span></th>
                                <th class="wd-15p"><span>Status</span></th>
                                <th class="wd-15p"><span>Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $key => $item): ?>

                                <tr id="row{{$item->currency_id}}" >
                                    <td>
                                        {{$key+1}}
                                    </td>
                                    <td>
                                        {{$item->currency_name}}
                                    </td>
                                    <td>
                                        {{$item->currency_code}}
                                    </td>
                                    <td>
                                       1x<b>{{config("default_currency.label")}}</b> = {{$item->currency_rate}} <b>{{$item->currency_code}}</b>
                                    </td>
                                    <td>
                                        {{$item->updated_at}}
                                    </td>
                                    <td>
                                        <?php if($item->currency_is_active == 1): ?>
                                            <span class="alert alert-success">Active</span>
                                            <?php else: ?>
                                            <span class="alert alert-danger">Not Active</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>

                                        <?php if(havePermission("admin/currencies", "edit_action")): ?>
                                            <a class="btn btn-primary mg-b-6" href="{{url("admin/currencies/save/$item->currency_id")}}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if(havePermission("admin/currencies", "delete_action")): ?>
                                            <?php if(strtolower($item->currency_code) != config("default_currency.value")): ?>
                                                <a href='#confirmModal'
                                                   data-toggle="modal"
                                                   data-effect="effect-super-scaled"
                                                   class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                                   data-tablename="{{\App\models\currencies_m::class}}"
                                                   data-deleteurl="{{url("/admin/currencies/delete")}}"
                                                   data-itemid="{{$item->currency_id}}">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            <?php endif; ?>
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
