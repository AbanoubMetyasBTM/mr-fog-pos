<?php
/** @var object $item_data  */
/** @var \App\form_builder\FormBuilder $builderObj  */
/** @var \Illuminate\Support\Collection $all_langs  */
?>

<?php if(count($builderObj->translate_fields) > 0): ?>
<div class="row">
    <div class="col-md-12">
        <div class="section-wrapper mg-y-20">
            <label class="section-title">Translate Fields</label>
            <p class="mg-b-20 mg-sm-b-40"></p>

            <?php
            echo save_translate_fields_block(
                $builderObj,
                $all_langs,
                $item_data
            );
            ?>
        </div>

    </div>
</div>
<?php endif; ?>
