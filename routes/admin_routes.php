<?php

Route::group([
    'middleware' => 'check_admin',
    'namespace'  => 'admin'
], function () {

    #region Start General Function Routing

    Route::post('/general_remove_item', 'DashboardController@general_remove_item');
    Route::post('/reorder_items', 'DashboardController@reorder_items');
    Route::post('/accept_item', 'DashboardController@accept_item');
    Route::post('/new_accept_item', 'DashboardController@new_accept_item');
    Route::post('/remove_admin', 'DashboardController@remove_admin');
    Route::post('/general_self_edit', 'DashboardController@general_self_edit');
    Route::post("/edit_slider_item", 'DashboardController@edit_slider_item');

    #endregion

});

Route::group([
    'middleware' => 'check_admin',
    'prefix'     => 'admin',
    'namespace'  => 'admin'
], function () {

    Route::get('dashboard', 'DashboardController@index');
    Route::get('select-lang', 'DashboardController@selectLanguage');

    Route::get('menu-sales', 'DashboardController@menuSales');
    Route::get('menu-inventory', 'DashboardController@menuInventory');
    Route::get('menu-customers', 'DashboardController@menuCustomers');
    Route::get('menu-reports', 'DashboardController@menuReports');
    Route::get('menu-settings', 'DashboardController@menuSettings');


    #region edit_content
    Route::get('site-content/show_methods', 'EditContent@show_methods');
    Route::post('site-content/show-content-spans-at-front', 'EditContent@showContentSpansAtFront');

    Route::get('site-content/edit_content/{lang_title}/{slug}', 'EditContent@check_function')->where("lang_title", "([a-z-]*)*");;
    Route::post('site-content/edit_content/{lang_title}/{slug}', 'EditContent@check_function')->where("lang_title", "([a-z-]*)*");;

    Route::get("site-content/copy_from_lang_to_another", 'EditContent@copy_from_lang_to_another');
    Route::post("site-content/copy_from_lang_to_another", 'EditContent@copy_from_lang_to_another');
    #endregion

    #region admin theme settings
    Route::get('theme/change_direction/{locale}', 'ThemeController@changeDirectionController');
    Route::get('theme/change_menu/{menu_display}', 'ThemeController@changeMenuController');
    Route::post('theme/dark_mode', 'ThemeController@DarkModeController');
    #endregion

    #region settings
    Route::get('settings', 'SettingsController@index');
    Route::post('settings', 'SettingsController@index');
    #endregion

    #region notifications

    Route::get('notifications/show_all/{not_type}', 'NotificationController@index');
    Route::post('notifications/delete', 'NotificationController@delete');
    Route::post('notifications_seen', 'NotificationController@seen_notifications');

    #endregion

    #region Languages
    Route::get('/langs', 'LanguagesController@index');
    Route::get('/langs/save/{lang_id?}', 'LanguagesController@save');
    Route::post('/langs/save/{lang_id?}', 'LanguagesController@save');
    Route::post('/langs/delete_lang', 'LanguagesController@delete');
    #endregion

    #region static pages
    Route::get('site-pages', 'PagesController@index');
    Route::get('site-pages/save/{page_id?}', 'PagesController@save')->where('page_id', '([0-9]*)*');
    Route::post('site-pages/save/{page_id?}', 'PagesController@save')->where('page_id', '([0-9]*)*');
    Route::post('site-pages/delete', 'PagesController@delete');

    #endregion

    #region admins
    Route::get('admins', 'AdminController@index');
    Route::get('admins/save/{admin_id?}', 'AdminController@save')->where("admin_id", "([0-9]*)*");
    Route::post('admins/save/{admin_id?}', 'AdminController@save')->where("admin_id", "([0-9]*)*");
    Route::post('admins/delete', 'AdminController@delete');
    #endregion

    #region permissions
    Route::get('admins/assign_permission/{user_id}', 'PermissionsController@assign_permission');
    Route::post('admins/assign_permission/{user_id}', 'PermissionsController@assign_permission');
    Route::get('admins/assign-all-permission/{user_id}', 'PermissionsController@assign_all_permission');
    Route::post('admins/permissions-multi-accepters', 'PermissionsController@permissionsMultiAccepters');
    #endregion



    Route::get('support-messages', 'SupportMessagesController@index');


    #region categories
    Route::get('/categories', 'CategoriesController@index');
    Route::get('/categories/save/{item_id?}', 'CategoriesController@save');
    Route::post('/categories/save/{item_id?}', 'CategoriesController@save');
    Route::post('/categories/delete', 'CategoriesController@delete');
    #endregion


    #region branches
    Route::get('/branches', 'BranchesController@index');
    Route::get('/branches/save/{item_id?}', 'BranchesController@save');
    Route::post('/branches/save/{item_id?}', 'BranchesController@save');
    Route::post('/branches/delete', 'BranchesController@delete');

    Route::get('/branches/access-branch-as-branch-admin/{branch_id}', 'BranchesController@accessBranchAsBranchAdmin');
    Route::get('/branches/back-to-main-admin-panel', 'BranchesController@backToMainAdminPanel');

    #endregion

    #region brands
    Route::get('/brands', 'BrandsController@index');
    Route::get('/brands/save/{item_id?}', 'BrandsController@save');
    Route::post('/brands/save/{item_id?}', 'BrandsController@save');
    Route::post('/brands/delete', 'BrandsController@delete');
    #endregion

    #region clients
    Route::get('/clients', 'ClientsController@index');
    Route::get('/clients/save/{item_id?}', 'ClientsController@save');
    Route::post('/clients/save/{item_id?}', 'ClientsController@save');
    Route::post('/clients/delete', 'ClientsController@delete');
    Route::get('clients-orders/get-client-by-name-or-phone', 'ClientsController@getClientByNameOrPhone');
    Route::get('clients-orders/get-order-by-name', 'ClientsController@getOrderByName');

    #endregion

    #region suppliers
    Route::get('/suppliers', 'SuppliersController@index');
    Route::get('/suppliers/save/{item_id?}', 'SuppliersController@save');
    Route::post('/suppliers/save/{item_id?}', 'SuppliersController@save');
    Route::post('/suppliers/delete', 'SuppliersController@delete');
    #endregion

    #region coupons
    Route::get('/coupons', 'CouponsController@index');
    Route::get('/coupons/save/{item_id?}', 'CouponsController@save');
    Route::post('/coupons/save/{item_id?}', 'CouponsController@save');
    Route::post('/coupons/delete', 'CouponsController@delete');
    #endregion

    #region inventories
    Route::get('/inventories', 'InventoriesController@index');
    Route::get('/inventories/save/{item_id?}', 'InventoriesController@save');
    Route::post('/inventories/save/{item_id?}', 'InventoriesController@save');
    Route::post('/inventories/delete', 'InventoriesController@delete');
    #endregion

    #region registers
    Route::get('/registers', 'RegistersController@index');
    Route::get('/registers/save/{item_id?}', 'RegistersController@save');
    Route::post('/registers/save/{item_id?}', 'RegistersController@save');
    Route::post('/registers/delete', 'RegistersController@delete');
    #endregion

    #region registers sessions
    Route::get('/register-sessions/start-session/{register_id}', 'RegisterSessionsController@startRegisterSession');
    Route::get('/register-sessions', 'RegisterSessionsController@index');
    Route::post('/register-sessions/start-session/{register_id}', 'RegisterSessionsController@startRegisterSession');
    Route::get('/register-sessions/end-session/{register_id}', 'RegisterSessionsController@endRegisterSession');
    Route::post('/register-sessions/end-session/{register_id}', 'RegisterSessionsController@endRegisterSession');
    Route::get('/register-sessions/add-change/{register_id}', 'RegisterSessionsController@addChangeAmount');
    Route::post('/register-sessions/add-change/{register_id}', 'RegisterSessionsController@addChangeAmount');
    #endregion

    #region registers sessions logs
    Route::get('/register-sessions-logs/show-register-session-logs', 'RegisterSessionLogController@showRegisterSessionLogsOfRegister');
    #endregion

    #region expenses
    Route::get('/expenses/save/{item_id?}', 'ExpensesController@save');
    Route::post('/expenses/save/{item_id?}', 'ExpensesController@save');
    Route::get('/expenses/refund/{item_id}', 'ExpensesController@refund');
    Route::post('/expenses/refund/{item_id}', 'ExpensesController@refund');
    Route::post('/expenses/delete', 'ExpensesController@delete');
    #endregion

    #region currencies
    Route::get('currencies', 'CurrenciesController@index');
    Route::get('currencies/save/{id?}', 'CurrenciesController@save');
    Route::post('currencies/save/{id?}', 'CurrenciesController@save');
    Route::post('currencies/delete', 'CurrenciesController@delete');
    #endregion

    #region branches_inventories
    Route::get('/branches-inventories', 'BranchesInventoriesController@index');
    Route::get('/branches-inventories/save/{item_id?}', 'BranchesInventoriesController@save');
    Route::post('/branches-inventories/save/{item_id?}', 'BranchesInventoriesController@save');
    Route::post('/branches-inventories/delete', 'BranchesInventoriesController@delete');
    #endregion

    #region transactions-log
    Route::get('transactions-log/show-log/{wallet_owner_name}/{wallet_ids}','TransactionsLogController@index');
    Route::get('transactions-log/deposit-money/{wallet_owner_name}/{wallet_ids}','TransactionsLogController@depositMoney');
    Route::post('transactions-log/deposit-money/{wallet_owner_name}/{wallet_ids}','TransactionsLogController@depositMoney');
    Route::get('transactions-log/withdraw-money/{wallet_owner_name}/{wallet_ids}','TransactionsLogController@withdrawMoney');
    Route::post('transactions-log/withdraw-money/{wallet_owner_name}/{wallet_ids}','TransactionsLogController@withdrawMoney');
    #endregion

    #region inventories-products
    Route::get('/inventories-products/show', 'InventoryProductsController@index');
    Route::get('/inventories-products/add-product', 'InventoryProductsController@addProductToInventory');
    Route::post('/inventories-products/add-product', 'InventoryProductsController@addProductToInventory');
    Route::get('/inventories-products/move-product', 'InventoryProductsController@moveProductFromInvToAnotherInv');
    Route::post('/inventories-products/move-product', 'InventoryProductsController@moveProductFromInvToAnotherInv');
    Route::get('/inventories-products/add-broken-product', 'InventoryProductsController@addBrokenProductToInventory');
    Route::post('/inventories-products/add-broken-product', 'InventoryProductsController@addBrokenProductToInventory');
    Route::post('/inventories-products/update-quantity-limit', 'InventoryProductsController@updateQuantityLimit');
    #endregion

    #region inventories-log
    Route::get('inventories-log/show-log','InventoriesLogController@index');
    Route::get('inventories-log/invalid-entry/{item_id}','InventoriesLogController@addInvalidEntry');
    Route::post('inventories-log/invalid-entry/{item_id}','InventoriesLogController@addInvalidEntry');
    #endregion

    #region branches-prices
    Route::get('branches-prices/show-branch-prices/{item_id}','BranchesPricesController@showBranchPrices');
    Route::post('branches-prices/update-branch-prices','BranchesPricesController@updateBranchPrices');
    Route::get('branches-prices/show-product-branches-prices/{product_id}','BranchesPricesController@showProductBranchesPrices');
    #endregion

    #region money-installments
    Route::get('money-installments/show-schedule-money/{wallet_owner_name}/{wallet_id}','MoneyInstallmentsController@index');
    Route::get('money-installments/get-schedule-all-debt-money/{wallet_owner_name}/{wallet_id}','MoneyInstallmentsController@scheduleAllDebtMoney');
    Route::post('money-installments/add-schedule-all-debt-money/{wallet_owner_name}/{wallet_id}','MoneyInstallmentsController@scheduleAllDebtMoney');
    Route::get('money-installments/get-schedule-debt-money/{wallet_owner_name}/{wallet_id}','MoneyInstallmentsController@addScheduleDebtMoney');
    Route::post('money-installments/add-schedule-debt-money/{wallet_owner_name}/{wallet_id}','MoneyInstallmentsController@addScheduleDebtMoney');
    Route::get('money-installments/get-schedule-all-owed-money/{wallet_owner_name}/{wallet_id}','MoneyInstallmentsController@scheduleAllOwedMoney');
    Route::post('money-installments/add-schedule-all-owed-money/{wallet_owner_name}/{wallet_id}','MoneyInstallmentsController@scheduleAllOwedMoney');
    Route::get('money-installments/get-schedule-owed-money/{wallet_owner_name}/{wallet_id}','MoneyInstallmentsController@addScheduleOwedMoney');
    Route::post('money-installments/add-schedule-owed-money/{wallet_owner_name}/{wallet_id}','MoneyInstallmentsController@addScheduleOwedMoney');
    Route::get('money-installments/edit-schedule-money/{wallet_owner_name}/{wallet_id}/{installment_id}','MoneyInstallmentsController@edit');
    Route::post('money-installments/edit-schedule-money/{wallet_owner_name}/{wallet_id}/{installment_id}','MoneyInstallmentsController@edit');
    Route::get('money-installments/receive-money-installment/{wallet_owner_name}/{wallet_id}/{installment_id}','MoneyInstallmentsController@receiveInstallment');
    Route::post('money-installments/receive-money-installment/{wallet_owner_name}/{wallet_id}/{installment_id}','MoneyInstallmentsController@receiveInstallment');
    Route::post('money-installments/delete-schedule-money','MoneyInstallmentsController@delete');
    #endregion

    #region products

    Route::get('products', 'ProductsController@index');
    Route::get('products/save/{id?}', 'ProductsController@save');
    Route::post('products/save/{id?}', 'ProductsController@save');
    Route::post('products/delete', 'ProductsController@delete');
    Route::post('products/delete-variant-type', 'ProductsController@deleteVariantType');
    Route::post('products/delete-variant-type-value', 'ProductsController@deleteVariantTypeValue');

    Route::get('products/get-product-by-name', 'ProductsController@getProductByName');

    Route::get('clients-orders/get-product-sku-by-name-barcode-client', 'ProductsController@getProductSkusByNameOrBarcodeOfOrderClient');
    #endregion

    #region product-sku
    Route::get('products-sku/show/{pro_id}/{pro_sku_id?}', 'ProductsSkuController@showProductSkus')->where("pro_id", "([0-9]*)*");
    Route::get('products-sku/save/{sku_id}', 'ProductsSkuController@save')->where("sku_id", "([0-9]*)*");
    Route::post('products-sku/save/{sku_id}', 'ProductsSkuController@save')->where("sku_id", "([0-9]*)*");
    Route::post('products-sku/edit-sku', 'ProductsSkuController@editProductSku');
    Route::get('products-sku/get-product-sku-by-barcode', 'ProductsSkuController@getProductSkusByBarcode');
    #endregion


    #region product-promotions
    Route::get('/product-promotions', 'ProductPromotionsController@index');
    Route::get('/product-promotions/save/{item_id?}', 'ProductPromotionsController@save');
    Route::post('/product-promotions/save/{item_id?}', 'ProductPromotionsController@save');
    Route::post('/product-promotions/delete', 'ProductPromotionsController@delete');
    #endregion


    #region national-holidays
    Route::get('/national-holidays', 'NationalHolidaysController@index');
    Route::get('/national-holidays/save/{item_id?}', 'NationalHolidaysController@save');
    Route::post('/national-holidays/save/{item_id?}', 'NationalHolidaysController@save');
    Route::post('/national-holidays/delete', 'NationalHolidaysController@delete');
    #endregion


    #region delay-early-requests
    Route::get('/delay-early-requests/{request_type}', 'DelayEarlyRequestsController@index');
    Route::post('/delay-early-requests/change-delay-early-request-status', 'DelayEarlyRequestsController@changeStatusOfRequest');
    #endregion

    #region holiday-requests
    Route::get('/holiday-requests/{request_type}', 'HolidayRequestsController@index');
    Route::post('/holiday-requests/change-holiday-request-status', 'HolidayRequestsController@changeStatusOfRequest');
    #endregion

    #region employees
    Route::get('/employees', 'EmployeesController@index');
    Route::get('/employees/save/{item_id?}', 'EmployeesController@save');
    Route::post('/employees/save/{item_id?}', 'EmployeesController@save');
    Route::post('/employees/delete', 'EmployeesController@delete');
    Route::post('/employees/show-employee-data', 'EmployeesController@getEmployeeData');
    #endregion

    #region employees-warnings
    Route::get('/employees-warnings', 'EmployeeWarningsController@index');
    Route::get('/employees-warnings/save/{item_id?}', 'EmployeeWarningsController@save');
    Route::post('/employees-warnings/save/{item_id?}', 'EmployeeWarningsController@save');
    Route::post('/employees-warnings/delete', 'EmployeeWarningsController@delete');
    #endregion

    #region employees-tasks
    Route::get('/employees-tasks', 'EmployeeTasksController@index');
    Route::get('/employees-tasks/save/{item_id?}', 'EmployeeTasksController@save');
    Route::post('/employees-tasks/save/{item_id?}', 'EmployeeTasksController@save');
    Route::post('/employees-tasks/delete', 'EmployeeTasksController@delete');
    Route::get('/employees-tasks/show/{item_id?}', 'EmployeeTasksController@showEmployeeTask');
    Route::post('/employees-tasks/change-status-task', 'EmployeeTasksController@changeStatusOfTask');
    #endregion


    #region employees-tasks-comments
    Route::post('/employees-tasks-comments/add', 'EmployeeTaskCommentsController@addNewComment');
    Route::post('/employees-tasks-comments/delete', 'EmployeeTaskCommentsController@delete');
    #endregion


    #region employee-login-logout-tbl
    Route::get('/employee-login-logout/employee/{employee_id}', 'EmployeeLoginLogoutController@index');
    #endregion

    #region suppliers-orders
    Route::get('/suppliers-orders', 'SuppliersOrdersController@index');
    Route::get('/suppliers-orders/add-order', 'SuppliersOrdersController@addOrder');
    Route::post('/suppliers-orders/add-order', 'SuppliersOrdersController@addOrder');
    Route::get('/suppliers-orders/show-order/{order_id}', 'SuppliersOrdersController@showOrder');
    Route::post('/suppliers-orders/make-order-done/{order_id}', 'SuppliersOrdersController@makeOrderDone');
    Route::get('/suppliers-orders/make-order-done/{order_id}', 'SuppliersOrdersController@makeOrderDone');
    Route::post('/suppliers-orders/return-items/{order_id}', 'SuppliersOrdersController@returnOrderItems');
    Route::post('/suppliers-orders/check-product-price', 'SuppliersOrdersController@checkProductPrice');
    Route::post('/suppliers-orders/cancel-order/{order_id}', 'SuppliersOrdersController@cancelOrder');
    #endregion


    #region gift-card-templates
    Route::get('/gift-card-templates', 'GiftCardTemplatesController@index');
    Route::get('/gift-card-templates/save/{item_id?}', 'GiftCardTemplatesController@save');
    Route::post('/gift-card-templates/save/{item_id?}', 'GiftCardTemplatesController@save');
    Route::post('/gift-card-templates/delete', 'GiftCardTemplatesController@delete');
    #endregion


    #region gift-cards
    Route::get('/gift-cards', 'GiftCardsController@index');
    Route::get('/gift-cards/add-card', 'GiftCardsController@addCard');
    Route::post('/gift-cards/add-card', 'GiftCardsController@addCard');
    Route::get('/gift-cards/show-card/{card_id}', 'GiftCardsController@showCard');
    Route::get('/gift-cards/print-card/{card_id}', 'GiftCardsController@printCard');
    #endregion


    #region clients-orders
    Route::get('/clients-orders', 'ClientsOrdersController@index');

    Route::get('/clients-orders/add-order', 'ClientsOrdersController@addOrder');
    Route::post('/clients-orders/add-order', 'ClientsOrdersController@addOrder');
    Route::post('/clients-orders/check-if-coupon-is-usable', 'ClientsOrdersController@checkIfCouponIsUsable');
    Route::post('/clients-orders/check-if-gift-card-is-usable', 'ClientsOrdersController@checkIfGiftCardIsUsable');

    Route::get('/clients-orders/show-order/{order_id}', 'ClientsOrdersController@showOrder');

    Route::post('/clients-orders/make-order-done/{order_id}', 'ClientsOrdersController@makeOrderDone');
    Route::get('/clients-orders/make-order-done/{order_id}', 'ClientsOrdersController@makeOrderDone');

    Route::post('/clients-orders/return-order/{order_id}', 'ClientsOrdersController@returnOrderItems');
    Route::get('/clients-orders/return-order/{order_id}', 'ClientsOrdersController@returnOrderItems');

    Route::get('/clients-orders/print-order-invoice/{order_id}', 'ClientsOrdersController@printOrderInvoice');
    #endregion


    #region paycheck
    Route::get('/paycheck/add-paycheck/{employee_id}/{weeks_indexes}', 'PaychecksController@addPaycheck')->where("employee_id", "([0-9]*)*");
    Route::post('/paycheck/add-paycheck/{employee_id}/{weeks_indexes}', 'PaychecksController@addPaycheck')->where("employee_id", "([0-9]*)*");
    Route::post('/paycheck/change-is-received', 'PaychecksController@changePaycheckIsReceived');
    #endregion


    #region money_to_loyalty_points
    Route::get('/money_to_loyalty_points', 'MoneyToLoyaltyPointsController@index');
    Route::get('/money_to_loyalty_points/save/{item_id?}', 'MoneyToLoyaltyPointsController@save');
    Route::post('/money_to_loyalty_points/save/{item_id?}', 'MoneyToLoyaltyPointsController@save');
    Route::post('/money_to_loyalty_points/delete', 'MoneyToLoyaltyPointsController@delete');
    #endregion

    #region loyalty_points_to_money
    Route::get('/loyalty_points_to_money', 'LoyaltyPointsToMoneyController@index');
    Route::get('/loyalty_points_to_money/save/{item_id?}', 'LoyaltyPointsToMoneyController@save');
    Route::post('/loyalty_points_to_money/save/{item_id?}', 'LoyaltyPointsToMoneyController@save');
    Route::post('/loyalty_points_to_money/delete', 'LoyaltyPointsToMoneyController@delete');
    #endregion

    #region reports
    Route::get('reports/sold-products', 'reports\ClientOrdersReportsController@soldProducts');
    Route::get('reports/sold-products-monthly', 'reports\ClientOrdersReportsController@soldProductsMonthly');
    Route::get('reports/sold-products-yearly', 'reports\ClientOrdersReportsController@soldProductsYearly');


    #region reports
    Route::get('reports/sold-products-sales-sum', 'reports\ClientOrdersReportsController@reportSoldProductsSalesSum');
    Route::get('reports/sold-products-sales-sum-monthly', 'reports\ClientOrdersReportsController@reportSoldProductsSalesSumMonthly');
    Route::get('reports/sold-products-sales-sum-yearly', 'reports\ClientOrdersReportsController@reportSoldProductsSalesSumYearly');


    Route::get('reports/sold-products-sku', 'reports\ClientOrdersReportsController@soldProductsSKU');
    Route::get('reports/sold-products-sku-monthly', 'reports\ClientOrdersReportsController@soldProductsSKUMonthly');
    Route::get('reports/sold-products-sku-yearly', 'reports\ClientOrdersReportsController@soldProductsSKUYearly');

    Route::get('reports/sold-products-sku-sales-sum', 'reports\ClientOrdersReportsController@soldProductsSKUSalesSum');
    Route::get('reports/sold-products-sku-sales-sum-monthly', 'reports\ClientOrdersReportsController@soldProductsSKUSalesSumMonthly');
    Route::get('reports/sold-products-sku-sales-sum-yearly', 'reports\ClientOrdersReportsController@soldProductsSKUSalesSumYearly');


    Route::get('reports/client-orders-count', 'reports\ClientOrdersReportsController@clientOrdersCount');
    Route::get('reports/client-orders-count-monthly', 'reports\ClientOrdersReportsController@clientOrdersCountMonthly');
    Route::get('reports/client-orders-count-yearly', 'reports\ClientOrdersReportsController@clientOrdersCountYearly');


    Route::get('reports/client-orders-sum', 'reports\ClientOrdersReportsController@clientOrdersSum');
    Route::get('reports/client-orders-sum-monthly', 'reports\ClientOrdersReportsController@clientOrdersSumMonthly');
    Route::get('reports/client-orders-sum-yearly', 'reports\ClientOrdersReportsController@clientOrdersSumYearly');


    Route::get('reports/count-client', 'reports\ClientReportsController@clientCount');
    Route::get('reports/count-client-monthly', 'reports\ClientReportsController@clientCountMonthly');
    Route::get('reports/count-client-yearly', 'reports\ClientReportsController@clientCountYearly');

    Route::get('reports/orders-items-categories-quantities', 'reports\ClientOrdersReportsController@clientOrdersItemsQuantitiesByCategories');
    Route::get('reports/orders-items-categories-quantities-monthly', 'reports\ClientOrdersReportsController@clientOrdersItemsQuantitiesByCategoriesMonthly');
    Route::get('reports/orders-items-categories-quantities-yearly', 'reports\ClientOrdersReportsController@clientOrdersItemsQuantitiesByCategoriesYearly');


    Route::get('reports/orders-items-categories-sales-sum', 'reports\ClientOrdersReportsController@clientOrdersItemsSalesSumByCategories');
    Route::get('reports/orders-items-categories-sales-sum-monthly', 'reports\ClientOrdersReportsController@clientOrdersItemsSalesSumByCategoriesMonthly');
    Route::get('reports/orders-items-categories-sales-sum-yearly', 'reports\ClientOrdersReportsController@clientOrdersItemsSalesSumByCategoriesYearly');


    Route::get('reports/orders-items-brands-quantities', 'reports\ClientOrdersReportsController@clientOrdersItemsQuantitiesByBrands');
    Route::get('reports/orders-items-brands-quantities-monthly', 'reports\ClientOrdersReportsController@clientOrdersItemsQuantitiesByBrandsMonthly');
    Route::get('reports/orders-items-brands-quantities-yearly', 'reports\ClientOrdersReportsController@clientOrdersItemsQuantitiesByBrandsYearly');

    Route::get('reports/orders-items-brands-sales-sum', 'reports\ClientOrdersReportsController@clientOrdersItemsSalesSumByBrands');
    Route::get('reports/orders-items-brands-sales-sum-monthly', 'reports\ClientOrdersReportsController@clientOrdersItemsSalesSumByBrandsMonthly');
    Route::get('reports/orders-items-brands-sales-sum-yearly', 'reports\ClientOrdersReportsController@clientOrdersItemsSalesSumByBrandsYearly');

    Route::get('reports/inventory-quantities-grouped-by-brand', 'reports\InventoryReportsController@getInventoryQuantitiesGroupedByBrand');
    Route::get('reports/inventory-quantities-grouped-by-category', 'reports\InventoryReportsController@getInventoryQuantitiesGroupedByCategory');

    #endregion

    #region states
    Route::get('/taxes-groups', 'TaxesGroupsController@index');
    Route::get('/taxes-groups/save/{group_id?}', 'TaxesGroupsController@save');
    Route::post('/taxes-groups/save/{group_id?}', 'TaxesGroupsController@save');
    Route::post('/taxes-groups/delete', 'TaxesGroupsController@delete');
    #endregion

    #region employee_action_log
    Route::get('/employee-action-log', 'EmployeeActionLogController@index');
    #endregion

});



Route::group([
    'middleware' => 'check_admin',
    'namespace'  => 'admin\employee_hr',
    'prefix'     => 'admin/employee-hr',

], function () {

    #region national-holidays
    Route::get('/national-holidays', 'NationalHolidaysController@index');
    #endregion


    #region delay-early-requests
    Route::get('/delay-early-requests/save', 'DelayEarlyRequestsController@save');
    Route::post('/delay-early-requests/save', 'DelayEarlyRequestsController@save');
    Route::get('/delay-early-requests/show/{request_type}', 'DelayEarlyRequestsController@index');
    Route::post('/delay-early-requests/change-delay-early-request-status', 'DelayEarlyRequestsController@changeStatusOfRequest');

    #endregion

    #region holiday-requests
    Route::get('/holiday-requests/show/{request_type}', 'HolidayRequestsController@index');
    Route::get('/holiday-requests/save', 'HolidayRequestsController@save');
    Route::post('/holiday-requests/save', 'HolidayRequestsController@save');
    Route::post('/holiday-requests/change-holiday-request-status', 'HolidayRequestsController@changeStatusOfRequest');
    #endregion

    #region employees
    Route::get('/employees', 'EmployeesController@index');
    Route::post('/employees/show-employee-data', 'EmployeesController@getEmployeeData');
    #endregion

    #region employees-warnings
    Route::get('/employees-warnings', 'EmployeeWarningsController@index');
    #endregion

    #region employees-tasks
    Route::get('/employees-tasks', 'EmployeeTasksController@index');
    Route::get('/employees-tasks/show/{item_id?}', 'EmployeeTasksController@showEmployeeTask');
    Route::post('/employees-tasks/change-status-task', 'EmployeeTasksController@changeStatusOfTask');
    #endregion


    #region employees-tasks-comments
    Route::post('/employees-tasks-comments/add', 'EmployeeTaskCommentsController@addNewComment');
    Route::post('/employees-tasks-comments/delete', 'EmployeeTaskCommentsController@delete');
    #endregion


    #region employee-login-logout-tbl
    Route::get('/employee-login-logout', 'EmployeeLoginLogoutController@index');
    Route::get('/employee-login-logout/login', 'EmployeeLoginLogoutController@loginEmployeeWork');
    Route::get('/employee-login-logout/logout', 'EmployeeLoginLogoutController@logoutEmployeeWork');
    Route::post('/employee-login-logout/edit-login-logout', 'EmployeeLoginLogoutController@editLoginLogoutEmployeeWork');
    #endregion

});
