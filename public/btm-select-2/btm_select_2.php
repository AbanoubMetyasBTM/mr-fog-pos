<?php if(false): ?>
    <?php
        echo generateBTMSelect2([
            "field_name"       => "cityId",
            "label_name"       => showContent("hotels.destination"),
            "data-placeholder" => showContent("general_keywords.search"),
            "data-allow_cache" => "true",
            "data-url"         => langUrl("cities/hotels/list"),
            "grid"             => "col-md-4 mb-0"
        ]);
    ?>
<?php endif; ?>

<div class="form-group btm_select_2_parent_div">

    <input type="text" class="form-control btm_select_2_display_selected_text" readonly>
    <input type="hidden" class="btm_select_2_selected_value" name="" value="">
    <input type="hidden" class="btm_select_2_selected_obj" name="" value="">

    <i class="la la-caret-down right_icon"></i>

    <div class="filter_results">
        <div class="search_box_div">

            <input
                type="text"
                class="form-control search_box btm_select_2"
                placeholder="search for results"
                data-placeholder='search for results'
                data-static_list="false"
                data-url=""
                data-min_search_chars_msg="please enter CHAR_NUM or more characters"
                data-min_search_chars="false"
                data-allow_cache="false"
                data-search_keyword="q"
                data-action_type="GET"
                data-value_field_name=""
                data-text_field_name=""
            >
        </div>

        <div class="result_rows">
<!--            <div class="result_row" data-value="1">Test</div>-->
        </div>
    </div>

</div>

