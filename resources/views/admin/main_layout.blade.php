@include("admin.components.header")


<?php if(config('menu_display') == "sidebar"): ?>

    <div class="slim-body">

        @include("admin.components.sidebar_menu_links")

        <div class="container-fluid pl-0 pr-0 mb-5" style="position: relative;overflow-x: hidden;">
            <div class="ajax_page_loader">
                <div class="sk-wave">
                    <div class="sk-rect sk-rect1 bg-gray-800"></div>
                    <div class="sk-rect sk-rect2 bg-gray-800"></div>
                    <div class="sk-rect sk-rect3 bg-gray-800"></div>
                    <div class="sk-rect sk-rect4 bg-gray-800"></div>
                    <div class="sk-rect sk-rect5 bg-gray-800" ></div>
                </div>
            </div>

            @include("admin.components.navigation_header")
            <div class="show_progress_bar"></div>

            <div class="container-fluid load_ajax_content">
                @yield('subview')
            </div>
        </div>

    </div>

<?php else : ?>

    <div class="load_ajax_content">
        @yield('subview')
    </div>

<?php endif; ?>


@include("admin.components.footer")



