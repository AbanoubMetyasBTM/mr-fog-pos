<div class="slim-mainpanel">
    <div class="container-fluid">

        <div class="section-wrapper">

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <div class="panel panel-info">
                    <div class="panel-heading">Notes</div>
                    <div class="panel-body">

                        <p>This is new permissions version</p>
                        <p>
                            remove relations at permissions table
                        </p>
                        <p>
                            then
                            you should click at convert db to files if you use old db version
                            and you want to keep old data
                        </p>

                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        All Permission Pages
                    </div>
                    <div class="panel-body">
                        <table id="cat_table_1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <td>#</td>
                                <td>Permission Page</td>
                                <td>Sub System</td>
                                <td>Show in AdminPanel</td>
                                <td>Hide Accept Buttons</td>
                                <td>Edit</td>
                                <td>Remove</td>
                            </tr>
                            </thead>


                            <tbody>
                            <?php foreach ($all_permissions_pages as $key => $page): ?>
                            <tr id="row<?= string_safe($page->page_name) ?>">
                                <td><?=$key+1?></td>
                                <td><?=$page->page_name ?></td>
                                <td><?=$page->sub_sys ?></td>
                                <td>{{($page->show_in_admin_panel==1)?"Yes":"No"}}</td>
                                <td>{{($page->hide_accept_buttons==1)?"Yes":"No"}}</td>

                                <td>
                                    <a href="<?= url("dev/permissions/permissions_pages/save/".string_safe($page->page_name)) ?>">
                                        <span class="btn btn-info"> Edit Permissions </span>
                                    </a>
                                </td>
                                <td>
                                    <a
                                        href='#'
                                        class="btn btn-danger general_remove_item"
                                        data-deleteurl="<?= url("/dev/permissions/permissions_pages/delete") ?>"
                                        data-tablename="App\models\permissions\permission_pages_m"
                                        data-itemid="<?= string_safe($page->page_name) ?>"
                                    >
                                        Delete
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
