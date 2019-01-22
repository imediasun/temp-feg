<?php
Route::controller('pages', 'PagesController');
Route::controller('vendor', 'VendorController');
Route::controller('calendar', 'CalendarController');
Route::controller('sbinovice', 'SbinoviceController');
Route::controller('sbinvoiceitem', 'SbinvoiceitemController');
Route::controller('sbproduct', 'SbproductController');
Route::controller('employee', 'EmployeeController');
Route::controller('customer', 'CustomerController');
Route::controller('orderdetail', 'OrderdetailController');
Route::controller('product', 'ProductController');
Route::controller('reports', 'ReportsController');
Route::controller('sbticket', 'SbticketController');
Route::controller('department', 'DepartmentController');
Route::controller('location', 'LocationController');
Route::controller('game', 'GameController');
Route::controller('ticketcomment', 'TicketcommentController');
Route::controller('gamestitle', 'GamestitleController');
Route::controller('order', 'OrderController');
/*Route::controller('pendingrequest', 'PendingrequestController'); Bug-306 pending request module has been commented */
Route::controller('tablecols', 'TablecolsController');
Route::controller('mylocationgame', 'MylocationgameController');
Route::controller('merchandisebudget', 'MerchandisebudgetController');
Route::controller('closedlocations', 'ClosedlocationsController');
Route::controller('gamesnotondebitcard', 'GamesnotondebitcardController');
Route::controller('potentialoverreportingerrors', 'PotentialoverreportingerrorsController');
Route::controller('gamesnotplayed', 'GamesnotplayedController');
Route::controller('nonfegreaders', 'NonfegreadersController');
Route::controller('merchthrowssimple', 'MerchthrowssimpleController');
Route::controller('merchthrowsdetailed', 'MerchthrowsdetailedController');
Route::controller('shopfegrequeststore', 'ShopfegrequeststoreController');
Route::controller('gameplayrankbylocation', 'GameplayrankbylocationController');
Route::controller('addtocart', 'AddtocartController');
Route::controller('managefegrequeststore', 'ManagefegrequeststoreController');
Route::controller('managenewgraphicrequests', 'ManagenewgraphicrequestsController');
Route::controller('manageservicerequests', 'ManageservicerequestsController');
Route::controller('productusagereport', 'ProductusagereportController');
Route::controller('gamesintransit', 'GamesintransitController');
Route::controller('gamesdisposed', 'GamesdisposedController');
Route::controller('spareparts', 'SparepartsController');
Route::controller('submitservicerequest', 'SubmitservicerequestController');
Route::controller('gameservicehistory', 'GameservicehistoryController');
Route::controller('merchindisetheminggallary', 'MerchindisetheminggallaryController');
Route::controller('redemptioncountergallary', 'RedemptioncountergallaryController');
Route::controller('managefreightquoters', 'ManagefreightquotersController');
Route::controller('freightquoters', 'FreightquotersController');
Route::controller('trainingmaterial', 'TrainingmaterialController');
Route::controller('topgamesreport', 'TopgamesreportController');
Route::controller('productsindevelopmentreport', 'ProductsindevelopmentreportController');
Route::controller('gameplayreport', 'GameplayreportController');
Route::controller('readersmissingassetidreport', 'ReadersmissingassetidreportController');
Route::controller('merchandiseexpensesreport', 'MerchandiseexpensesreportController');
Route::controller('itemreceipt', 'ItemreceiptController');
Route::controller('ticketsetting', 'TicketsettingController');
Route::controller('throwreport', 'ThrowreportController');
Route::controller('throwreportpayout', 'ThrowreportpayoutController');
Route::controller('throwreportinstantwin', 'ThrowreportinstantwinController');
Route::controller('servicerequests', 'ServicerequestsController');
Route::controller('feg/system/tasks', 'Feg\System\TasksController');
Route::controller('feg/system/systememailreportmanager', 'Feg\System\SystemEmailReportManagerController');
Route::controller('training', 'TrainingController');
Route::controller('excludedreaders', 'ExcludedreadersController');
Route::controller('ordertyperestrictions', 'OrdertyperestrictionsController');
Route::controller('inventoryreport', 'InventoryreportController');
Route::controller('expensecategories', 'ExpensecategoriesController');
Route::controller('ordersetting', 'OrdersettingController');
Route::controller('productlog', 'ProductlogController');
Route::controller('gallery', 'GalleryController');
Route::controller('qatestordersdelete', 'QatestordersdeleteController');
Route::controller('locationgroups', 'LocationgroupsController');
Route::controller('reviewvendorimportlist', 'ReviewvendorimportlistController');
Route::controller('envconfiguration', 'EnvconfigurationController');
?>
