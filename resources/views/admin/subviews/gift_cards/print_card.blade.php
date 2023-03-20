<style>

@media print {
    header, .slim-header, .slim-footer, .hide_at_print
    {
        display: none;
    }

    footer{
        display: none;
    }

    #invoice-POS{
        width: 100%;
    }

    @page {
        margin: 0;
    }

    *{
        margin: 0;
        padding: 0;
    }


}
</style>

<script>
    setTimeout(function (){
        window.print()
    },500);
</script>



<input type="hidden" name="template_text_positions" class="template_text_positions_class" value="{{$template_obj->template_text_positions}}">

<div class="row">
    <div class="col-md-12">
        <button type="button" onclick="window.print()" class="btn btn-primary hide_at_print">Print</button>
    </div>

    <div class="col-md-12">

        <div class="gift_card_template_display">
            <img class="bg_img" src="{{get_image_from_json_obj($template_obj->template_bg_img_obj)}}" alt="">

            <div class="gift_card_template_position_item" data-name="title">
                {{$card->card_title}}
            </div>
            <div class="gift_card_template_position_item" data-name="expiration_date">
                {{$card->card_expiration_date}}
            </div>
            <div class="gift_card_template_position_item" data-name="card_number">
                {{$card->card_unique_number}}
            </div>

            <?php
                $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
            ?>
            <img class="gift_card_template_position_item" data-name="barcode" src="data:image/png;base64,{{base64_encode($generator->getBarcode($card->card_unique_number, $generator::TYPE_CODABAR))}}" alt="" width="150">


            <div class="gift_card_template_position_item" data-name="card_amount">
                {{$card->card_price}}
            </div>
        </div>

    </div>
</div>
