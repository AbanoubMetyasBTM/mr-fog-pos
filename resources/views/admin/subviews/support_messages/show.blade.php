<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Support Messages</li>
            </ol>
            <h6 class="slim-pagetitle">Support Messages</h6>
        </div><!-- slim-pageheader -->
        <form id="save_form" action="{{url("admin/support_messages")}}" enctype="multipart/form-data">

            <div class="row">

                <div class="col-md-12">


                    <p class="mg-b-20 mg-sm-b-40"></p>

                    <div class="row">

                        <?php

                            $normal_tags = [
                                "from", "to"
                            ];

                            $attrs                    = generate_default_array_inputs_html(
                                $fields_name          = $normal_tags,
                                $data                 = $request_data ?? "",
                                $key_in_all_fields    = "yes",
                                $required             = "required",
                                $grid_default_value   = 4
                            );

                            $attrs[3]["from"]    = "date";
                            $attrs[3]["to"]      = "date";

                            echo
                            generate_inputs_html_take_attrs($attrs);

                        ?>

                        <div>
                            <input id="submit" type="submit" value="Search" class="btn btn-primary bd-0 btn-search-date">
                        </div>

                    </div>

                </div>
            </div>

        </form>

        <div class="section-wrapper">

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

            <?php if(is_array($results->all()) && count($results->all())): ?>
                <table id="datatable2" class="table display ">
                    <thead>
                        <tr>
                            <th class="wd-15p"><span>#</span></th>
                            <th class="wd-20p"><span>Customer Data</span></th>
                            <th class="wd-20p"><span>Date</span></th>
                            <th class="wd-20p"><span>Message</span></th>
                            <th class="wd-15p"><span>Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $key => $item): ?>
                        <tr id="row{{$item->id}}" class="{{$item->is_seen?"":"support-message-seen1"}}">
                            <td>
                                {{$key+1}}
                            </td>

                            <td>
                                {{$item->full_name}} <br>
                                {{$item->email}} <br>
                                +{{$item->phone}}
                            </td>
                            <td>
                                {{$item->created_at}}
                            </td>
                            <td>
                                {!! $item->message !!}
                            </td>

                            <td>

                                <a href='#confirmModal'
                                   data-toggle="modal"
                                   data-effect="effect-super-scaled"
                                   class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                   data-tablename="App\models\support_messages_m"
                                   data-deleteurl="{{url("/admin/support_messages/delete")}}"
                                   data-itemid="{{$item->id}}">
                                    <i class="fa fa-remove"></i>
                                </a>

                            </td>
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

