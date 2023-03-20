@if(!$has_main_inventory_or_not)
    <div class="alert alert-danger">
        Call admin to attach your branch with at least with one main inventory
    </div>
@endif
@if(!$inventories_has_products_or_not)
    <div class="alert alert-danger">
        add products at inventory
    </div>
@endif
@if(!$has_registers_or_not)
    <div class="alert alert-danger">
        please add registers
    </div>
@endif
