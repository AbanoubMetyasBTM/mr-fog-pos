<div class="col-md-12">
    <input id="pac-input" class="controls" type="text" placeholder="Search Box">
    <div id="shop_map" class="col-md-12" style="height:400px;"></div>
</div>

<hr>
<div class="row">
    <?php
        echo generate_inputs_html(
            array("Lat","Long") ,
            array("map_lat","map_lng") ,
            array("required","required") ,
            array("text","text") ,
            array($lat,$lng),
            array("lat","lng"),
            array("6","6")
        );
    ?>
</div>
