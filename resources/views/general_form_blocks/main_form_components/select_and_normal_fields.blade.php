<?php
/** @var object $item_data  */
/** @var \App\form_builder\FormBuilder $builderObj  */
/** @var \Illuminate\Support\Collection $all_langs  */
?>

<?php if(count($builderObj->select_fields) > 0 || count($builderObj->normal_fields) > 0): ?>
<div class="row">
    <div class="col-md-12">
        <div class="{{!isset($hideMainFormBuildTitle)?"section-wrapper mg-y-20":""}}">

            <?php if(!isset($hideMainFormBuildTitle)): ?>
                <label class="section-title">{{$mainFormBuildTitle ?? "Main Data"}}</label>
                <p class="mg-b-20 mg-sm-b-40"></p>
            <?php endif; ?>

            <div class="row">
                <?php
                echo save_select_fields_block(
                    $builderObj,
                    $item_data
                );
                ?>

                <?php
                echo save_normal_fields_block(
                    $builderObj,
                    $item_data
                );
                ?>
            </div>

        </div>
    </div>
</div>
<?php endif; ?>
