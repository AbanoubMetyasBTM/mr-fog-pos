<div class="slim-mainpanel">
    <div class="container-fluid">

        <div class="section-wrapper">

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">


                <div class="panel panel-info">
                    <div class="panel-heading">
                        All Generators
                    </div>

                    <div class="panel-body">
                        <table id="cat_table_1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <td>#</td>
                                <td>method_name</td>
                                <td>Edit Content Link</td>
                                <td>Edit</td>
                                <td>Remove</td>
                            </tr>
                            </thead>

                            <tbody>
                            <?php foreach ($all_methods as $key => $method): ?>
                            <tr id="row<?= $method->method_name ?>">
                                <td><?= $key+1 ?></td>
                                <td><?= $method->method_name ?></td>
                                <td>
                                    <?php foreach ($all_langs as $lang_key => $lang_item): ?>
                                       <a href="{{url("/admin/site-content/edit_content/$lang_item->lang_title/$method->method_name")}}">
                                           Link_<?=$lang_item->lang_title;?>
                                       </a>
                                    <?php endforeach; ?>
                                </td>

                                <td>
                                    <a
                                        class="btn btn-info"
                                        href="<?= url("/dev/generate_edit_content/save/$method->method_name") ?>"
                                    >
                                        Edit
                                    </a>
                                </td>
                                <td>
                                    <a href='#confirmModal'
                                       data-toggle="modal"
                                       data-effect="effect-super-scaled"
                                       class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                       data-tablename="{{\App\models\generate_site_content_methods_m::class}}"
                                       data-deleteurl="{{url("/general_remove_item")}}"
                                       data-itemid="{{$method->method_name}}">
                                        <i class="fa fa-remove"></i>
                                    </a>
                                </td>

                            </tr>
                            <?php endforeach ?>
                            </tbody>

                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
