<?php

    /**
     * @var $item_data object
    */

    $header_text        = "Add New";
    $item_id            = "";

    if (is_object($item_data)) {
        $header_text    = "Edit ".$item_data->template_title;
        $item_id        = $item_data->template_id;
    }

?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/gift-card-templates")}}">Gift Card Templates</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$header_text}}</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header_text}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">
            <?php if(havePermission("admin/gift_card_templates","add_action")): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/gift-card-templates/save")}}"> Add New <i class="fa fa-plus"></i></a>
                </label>
            <?php endif; ?>
            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <form id="save_form" class="ajax_form" action="{{url("admin/gift-card-templates/save/$item_id")}}" method="POST" enctype="multipart/form-data">

                    <?php
                        $data_object = $item_data;
                        $builder     = $builderObj;
                    ?>

                    <?php
                        $defaultPositions = '{"title":"left: 32.9773px; top: 29.9858px; font-size: 22px; color: rgb(255, 255, 255);","expiration_date":"left: 37.9886px; top: 209.986px;","card_number":"left: 38.9773px; top: 170.989px; font-size: 21px; color: rgb(255, 255, 255);","barcode":"left: 164.972px; top: 80.983px; width: 210px; filter: invert(100%);","card_amount":"left: 405.972px; top: 27.9602px; color: rgb(255, 255, 255); font-size: 31px;"}';
                    ?>
                    <input type="hidden" name="template_text_positions" class="template_text_positions_class" value="{{(!isset($item_data->template_text_positions)|| empty($item_data->template_text_positions))?$defaultPositions:$item_data->template_text_positions}}">

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

                                    @include("general_form_blocks.draw_img_fields_inner_block")

                                </div>



                                <div class="row">

                                    <div class="col-md-12">

                                        <div class="form-group">
                                            <label for="">change font</label>
                                            <button type="button" class="btn btn-info gift_card_template_decrease_font">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-info gift_card_template_increase_font">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>

                                        <div class="form-group">
                                            <label for="">change Barcode styles</label>
                                            <button type="button" class="btn btn-info gift_card_template_decrease_width">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-info gift_card_template_increase_width">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-info gift_card_template_change_barcode_color">
                                                Invert
                                            </button>

                                        </div>


                                        <div class="form-group">
                                            <label for="">Change Color</label>
                                            <input type="text" class="form-control gift_card_template_change_color">
                                        </div>

                                    </div>

                                    <div class="col-md-12">

                                        <div class="gift_card_template_display">
                                            <img class="bg_img" src="{{get_image_from_json_obj($item_data->template_bg_img_obj??"",url("public/images/visa.png"),true)}}" alt="">

                                            <div class="gift_card_template_position_item" data-name="title">
                                                Gift Card Title
                                            </div>
                                            <div class="gift_card_template_position_item" data-name="expiration_date">12-2030</div>
                                            <div class="gift_card_template_position_item" data-name="card_number">
                                                0000-0000-0000-0000
                                            </div>

                                            <?php
                                                $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                                            ?>
                                            <img class="gift_card_template_position_item" data-name="barcode" src="data:image/png;base64,{{base64_encode($generator->getBarcode("0000-0000-0000-0000", $generator::TYPE_CODABAR))}}" alt="" width="150">


                                            <div class="gift_card_template_position_item" data-name="card_amount">
                                                100 $
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>



                    {{csrf_field()}}

                    <div class="form-layout-footer">
                        <input id="submit" type="submit" value="save" class="btn btn-primary bd-0">
                    </div>

                </form>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->




