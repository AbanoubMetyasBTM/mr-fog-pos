<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Site Content</li>
            </ol>
            <h6 class="slim-pagetitle">Site Content</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <h2>Do you want badges to show site content keywords?</h2>
            <form class="ajax_form" method="post" action="{{url("/admin/site-content/show-content-spans-at-front")}}">
                {!! csrf_field() !!}
                <button class="btn btn-info" type="submit" name="show_admin_content" value="yes">show badges</button>
                <button class="btn btn-warning" type="submit" name="show_admin_content" value="no">hide badges</button>
            </form>

            <p class="mg-b-20 mg-sm-b-40"></p>

            <table id="cat_table_1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td>#</td>
                    <td>الموضوع</td>
                    <td>العمليات</td>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($methods as $key => $method): ?>
                <tr>
                    <td><?= $key+1; ?></td>
                    <td><?= capitalize_string($method->method_name) ?></td>
                    <td>
                        <?php foreach ($all_langs as $lang_key => $lang_item): ?>
                        <a class="btn btn-info" href="<?= url('/admin/site-content/edit_content/'.$lang_item->lang_title.'/'.$method->method_name) ?>">
                            تعديل
                             - {{$lang_item->lang_title}}
                        </a>
                        <?php endforeach; ?>
                    </td>

                </tr>
                <?php endforeach ?>
                </tbody>

            </table>

        </div>
    </div>
</div>
