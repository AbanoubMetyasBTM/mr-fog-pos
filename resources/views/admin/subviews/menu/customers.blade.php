<div class="slim-mainpanel admin_panel">
    <div class="container-fluid">

        <?php
            $links = menuCustomersLinks();
        ?>


        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
            </ol>
            <h6 class="slim-pagetitle">Clients</h6>
        </div>

        <?php if(isset($links["Taxes Groups"])): ?>
            <a href="{{$links["Taxes Groups"]}}" class="lp_link">
                <i class="fa-solid fa-percent"></i>
                <span>Taxes Groups</span>
            </a>
        <?php endif; ?>

        <?php if(isset($links["Clients"])): ?>
            <a href="{{$links["Clients"]}}" class="lp_link">
                <i class="fa-solid fa-person-walking-dashed-line-arrow-right"></i>
                <span>Clients</span>
            </a>
        <?php endif; ?>

        <?php if(
            isset($links["Money To Loyalty Points"]) ||
            isset($links["Loyalty Points To Money"])
        ): ?>

            <div class="slim-pageheader">
                <ol class="breadcrumb slim-breadcrumb">
                </ol>
                <h6 class="slim-pagetitle">Loyalty Points</h6>
            </div>

            <?php if(isset($links["Money To Loyalty Points"])): ?>
                <a href="{{$links["Money To Loyalty Points"]}}" class="lp_link">
                    <i class="fa-solid fa-money-bill-transfer"></i>
                    <span>Money To Points</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["Loyalty Points To Money"])): ?>
                <a href="{{$links["Loyalty Points To Money"]}}" class="lp_link">
                    <i class="fa-solid fa-arrows-to-circle"></i>
                    <span>Points To Money</span>
                </a>
            <?php endif; ?>

        <?php endif; ?>



    </div>
</div>
