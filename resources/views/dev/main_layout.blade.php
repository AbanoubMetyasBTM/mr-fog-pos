@include("dev.components.header")

<div class="show_progress_bar"></div>
<div class="ajax_page_loader">
    <div class="sk-wave">
        <div class="sk-rect sk-rect1 bg-gray-800"></div>
        <div class="sk-rect sk-rect2 bg-gray-800"></div>
        <div class="sk-rect sk-rect3 bg-gray-800"></div>
        <div class="sk-rect sk-rect4 bg-gray-800"></div>
        <div class="sk-rect sk-rect5 bg-gray-800"></div>
    </div>
</div>

<?php if(config('menu_display') == "sidebar"): ?>

<div class="slim-body">

    @include("dev.components.sidebar_menu_links")

    <div class="container load_ajax_content">
        @yield('subview')
        <div>

        </div>

        <?php else : ?>

        <div class="load_ajax_content">
            @yield('subview')
        </div>

<?php endif; ?>


@include("dev.components.footer")



