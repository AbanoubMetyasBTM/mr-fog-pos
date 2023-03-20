<?php
/**
 * @var bool $category_has_at_least_one
 * @var bool $brand_has_at_least_one
 * @var bool $inventory_has_at_least_one
 * @var bool $branch_has_at_least_one
 */
?>

@if(!$category_has_at_least_one)
    <div class="alert alert-danger">
        you should add categories
    </div>
@endif

@if(!$brand_has_at_least_one)
    <div class="alert alert-danger">
        you should add Brands
    </div>
@endif

@if(!$inventory_has_at_least_one)
    <div class="alert alert-danger">
        you should add inventories
    </div>
@endif

@if(!$branch_has_at_least_one)
    <div class="alert alert-danger">
        you should add branches
    </div>
@endif
