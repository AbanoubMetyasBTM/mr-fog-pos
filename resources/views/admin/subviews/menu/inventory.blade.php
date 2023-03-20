<div class="slim-mainpanel admin_panel">
    <div class="container-fluid">

        <?php
            $links = menuInventoryLinks();
        ?>

        <?php if(
            isset($links["Brands"]) ||
            isset($links["Categories"]) ||
            isset($links["Products"])
        ): ?>
            <div class="slim-pageheader">
                <ol class="breadcrumb slim-breadcrumb">
                </ol>
                <h6 class="slim-pagetitle">Products</h6>
            </div>

            <?php if(isset($links["Brands"])): ?>
                <a href="{{$links["Brands"]}}" class="lp_link">
                    <i class="fa fa-flag"></i>
                    <span>Brands</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["Categories"])): ?>
                <a href="{{$links["Categories"]}}" class="lp_link">
                    <i class="fa fa-tree"></i>
                    <span>Categories</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["Products"])): ?>
                <a href="{{$links["Products"]}}" class="lp_link">
                    <i class="fa-brands fa-product-hunt"></i>
                    <span>Products</span>
                </a>
            <?php endif; ?>

            <?php if(havePermission("admin/products","add_action")): ?>
                <a href="{{url("admin/products/save")}}" class="lp_link">
                    <i class="fa-brands fa-product-hunt"></i>
                    <i class="fa fa-plus abs_i"></i>
                    <span>Add Product</span>
                </a>
            <?php endif; ?>
        <?php endif; ?>



        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
            </ol>
            <h6 class="slim-pagetitle">Inventories</h6>
        </div>

        <?php if(isset($links["Inventories"])): ?>
            <a href="{{$links["Inventories"]}}" class="lp_link">
                <i class="fa-solid fa-tents"></i>
                <span>Inventories</span>
            </a>
        <?php endif; ?>

        <?php if(isset($links["Inventory Products"])): ?>
            <a href="{{$links["Inventory Products"]}}" class="lp_link">
                <i class="fa-solid fa-list"></i>
                <span>Inventory Products</span>
            </a>
        <?php endif; ?>


        <?php if(isset($links["Inventory Products - Quantity Limit"])): ?>
            <a href="{{$links["Inventory Products - Quantity Limit"]}}" class="lp_link">
                <i class="fas fa-sort-amount-down"></i>
                <span>Quantity Limit</span>
            </a>
        <?php endif; ?>

        <?php if(isset($links["Inventory History"])): ?>
            <a href="{{$links["Inventory History"]}}" class="lp_link">
                <i class="fa-solid fa-monument"></i>
                <span>History</span>
            </a>
        <?php endif; ?>

        <?php if($current_branch_id == null && havePermission("admin/inventories_products", "add_product")): ?>
            <a href="{{url("/admin/inventories-products/add-product")}}" class="lp_link">
                <i class="fa-solid fa-list"></i>
                <i class="fa-solid fa-plus abs_i"></i>
                <span>Add Product</span>
            </a>
        <?php endif; ?>

        <?php if(havePermission("admin/inventories_products", "move_product")): ?>
            <a href="{{url("/admin/inventories-products/add-product")}}" class="lp_link">
                <i class="fa-solid fa-list"></i>
                <i class="fa-solid fa-up-down-left-right abs_i"></i>
                <span>Move Product</span>
            </a>
        <?php endif; ?>

        <?php if(havePermission("admin/inventories_products", "add_broken_product")): ?>

            <a href="{{url("/admin/inventories-products/add-broken-product")}}" class="lp_link">
                <i class="fa-solid fa-list"></i>
                <i class="fa-solid fa-heart-crack abs_i"></i>
                <span>Broken Product</span>
            </a>
        <?php endif; ?>



        <?php if(
            isset($links["Suppliers"]) ||
            isset($links["Supplier Orders"])
        ): ?>

            <div class="slim-pageheader">
                <ol class="breadcrumb slim-breadcrumb">
                </ol>
                <h6 class="slim-pagetitle">Suppliers</h6>
            </div>

            <?php if(isset($links["Suppliers"])): ?>
                <a href="{{$links["Suppliers"]}}" class="lp_link">
                    <i class="fa-solid fa-industry"></i>
                    <span>Suppliers</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["Supplier Orders"])): ?>
                <a href="{{$links["Supplier Orders"]}}" class="lp_link">
                    <i class="fa-solid fa-truck-field"></i>
                    <span>Supplier Orders</span>
                </a>
            <?php endif; ?>

            <?php if(havePermission("admin/suppliers_orders","add_action")): ?>

                <a href="{{url("admin/suppliers-orders/add-order")}}" class="lp_link">
                    <i class="fa-solid fa-truck-field"></i>
                    <i class="fa fa-plus abs_i"></i>
                    <span>Add New Order</span>
                </a>
            <?php endif; ?>

        <?php endif; ?>




    </div>
</div>
