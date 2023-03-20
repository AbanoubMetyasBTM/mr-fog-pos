<?php

use Carbon\Carbon;


function editContentLinks()
{

    $links = [];

    if (havePermission("admin/site_content", "can_edit_content")) {
        $links["site content"] = url("admin/site-content/show_methods");
    }

    if (havePermission("admin/site_content", "copy_from_lang_to_another")) {
        $links["Copy & Translate Content"] = url("admin/site-content/copy_from_lang_to_another");
    }

    if (count($links) == 0) {
        return [];
    }

    return $links;
}

//done
function menuSettingsLinks()
{

    $links = [];

    if (havePermission("admin/settings", "show_action")) {
        $links["Settings"] = url("admin/settings");
    }

    if (havePermission("admin/languages", "show_action")) {
        $links["Languages"] = url("admin/langs");
    }

    if (havePermission("admin/admins", "show_action")) {
        $links["Admins"] = url("admin/admins");
    }

    if (havePermission("admin/currencies", "show_action")) {
        $links["Currencies"] = url("admin/currencies");
    }

    if (count($links) == 0) {
        return [];
    }

    return $links;

}

//done
function menuBranchSettingsLinks()
{

    $links = [];

    if (havePermission("admin/branches", "show_action")) {
        $links["Branches"] = url("admin/branches");
    }

    if (havePermission("admin/branches_inventories", "show_action")) {
        $links["Branch Inventories"] = url("admin/branches-inventories");
    }

    if (havePermission("admin/registers", "show_action")) {
        $links["Registers"] = url("admin/registers");
    }


    if (count($links) == 0) {
        return [];
    }

    return $links;

}

//done
function menuInventoryLinks()
{

    $links = [];

    if (havePermission("admin/brands", "show_action")) {
        $links["Brands"] = url("admin/brands");
    }

    if (havePermission("admin/categories", "show_action")) {
        $links["Categories"] = url("admin/categories");
    }


    if (havePermission("admin/products", "show_action")) {
        $links["Products"] = url("admin/products");
    }


    if (havePermission("admin/inventories", "show_action")) {
        $links["Inventories"] = url("admin/inventories");
    }

    if (havePermission("admin/inventories_products", "show_inventories_products")) {
        $links["Inventory Products"] = url("admin/inventories-products/show");
    }

    if (havePermission("admin/inventories_products", "show_inventories_products")) {
        $links["Inventory Products - Quantity Limit"] = url("admin/inventories-products/show?quantity_limit=yes");
    }

    if (havePermission("admin/inventories_log", "show_logs")) {
        $links["Inventory History"] = url("admin/inventories-log/show-log");
    }

    if (havePermission("admin/suppliers", "show_action")) {
        $links["Suppliers"] = url("admin/suppliers");
    }

    if (havePermission("admin/suppliers_orders", "show_action")) {
        $links["Supplier Orders"] = url("admin/suppliers-orders");
    }

    if (count($links) == 0) {
        return [];
    }

    return $links;

}

//done
function menuSalesLinks()
{

    $links = [];

    if (havePermission("admin/clients_orders", "show_action")) {
        $links["Clients Orders"] = url("admin/clients-orders");
    }

    if (havePermission("admin/clients_orders", "show_action")) {

        $dateFrom                                        = Carbon::today()->format('Y-m-d');
        $dateTo                                          = Carbon::tomorrow()->format('Y-m-d');
        $links["Pick Up Clients Orders Today"] = url("admin/clients-orders?order_status=pick_up&date_from=$dateFrom&date_to=$dateTo");
    }

    if (havePermission("admin/gift_cards", "show_action")) {
        $links["Gift Cards"] = url("admin/gift-cards");
    }

    if (havePermission("admin/coupons", "show_action")) {
        $links["Coupons"] = url("admin/coupons");
    }

    if (havePermission("admin/product_promotions", "show_action")) {
        $links["Product Promotions"] = url("admin/product-promotions");
    }


    if (count($links) == 0) {
        return [];
    }

    return $links;

}

//done
function menuCustomersLinks(){

    $links = [];

    if (havePermission("admin/taxes_groups", "show_action")) {
        $links["Taxes Groups"] = url("admin/taxes-groups");
    }

    if (havePermission("admin/clients", "show_action")) {
        $links["Clients"] = url("admin/clients");
    }

    if (havePermission("admin/money_to_loyalty_points", "show_action")) {
        $links["Money To Loyalty Points"] = url("admin/money_to_loyalty_points");
    }

    if (havePermission("admin/loyalty_points_to_money", "show_action")) {
        $links["Loyalty Points To Money"] = url("admin/loyalty_points_to_money");
    }

    if (count($links) == 0) {
        return [];
    }

    return $links;

}

//done
function menuHrLinks()
{

    $links = [];

    if (havePermission("admin/national_holidays", "show_action")) {
        $links["National Holidays"] = url("admin/national-holidays");
    }

    if (havePermission("admin/employees", "show_action")) {
        $links["Employees"] = url("admin/employees");
    }

    if (havePermission("admin/employee_action_log", "show_action")) {
        $links["Employees Actions"] = url("admin/employee-action-log");
    }


    if (havePermission("admin/employees_tasks", "show_action")) {
        $links["Employees Tasks"] = url("admin/employees-tasks");
    }

    if (havePermission("admin/employees_warnings", "show_action")) {
        $links["Employees Warnings"] = url("admin/employees-warnings");
    }

    if (havePermission("admin/delay_early_requests", "show_action")) {
        $links["Employees Delay Requests"] = url("admin/delay-early-requests/delay");
    }

    if (havePermission("admin/delay_early_requests", "show_action")) {
        $links["Employees Early Requests"] = url("admin/delay-early-requests/early");
    }

    if (havePermission("admin/holiday_requests", "show_action")) {
        $links["Employees Sick Requests"] = url("admin/holiday-requests/sick");
    }

    if (havePermission("admin/holiday_requests", "show_action")) {
        $links["Employees Vacation Requests"] = url("admin/holiday-requests/vacation");
    }

    if (count($links) == 0) {
        return [];
    }

    return $links;

}

function menuMyHrLinks($current_user_data)
{

    if (is_object($current_user_data) && $current_user_data->user_type != "employee") {
        return [];
    }

    $links = [];


    if (havePermission("admin/my_hr_national_holidays", "show_action")) {
        $links["National Holidays"] = url("admin/employee-hr/national-holidays");
    }

    if (havePermission("admin/my_hr_employees_tasks", "show_action")) {
        $links["My Tasks"] = url("admin/employee-hr/employees-tasks");
    }

    if (havePermission("admin/my_hr_employees_warnings", "show_action")) {
        $links["My Warnings"] = url("admin/employee-hr/employees-warnings");
    }


    if (havePermission("admin/my_hr_delay_early_requests", "show_action")) {
        $links["My Delay Requests"] = url("admin/employee-hr/delay-early-requests/show/delay");
    }


    if (havePermission("admin/my_hr_delay_early_requests", "show_action")) {
        $links["My Early Requests"] = url("admin/employee-hr/delay-early-requests/show/early");
    }


    if (havePermission("admin/my_hr_holiday_requests", "show_action")) {
        $links["My Sick Requests"] = url("admin/employee-hr/holiday-requests/show/sick");
    }

    if (havePermission("admin/my_hr_holiday_requests", "show_action")) {
        $links["My Vacation Requests"] = url("admin/employee-hr/holiday-requests/show/vacation");
    }

    if (havePermission("admin/my_hr_employee_login_logout", "show_action")) {
        $links["My Login Logout Table"] = url("admin/employee-hr/employee-login-logout");
    }

    if (count($links) == 0) {
        return [];
    }

    return $links;

}
