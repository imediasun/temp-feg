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
                    Route::controller('sbticket', 'SbticketController');
                    Route::controller('department', 'DepartmentController');
                    Route::controller('location', 'LocationController');
                    Route::controller('game', 'GameController');
                    Route::controller('ticketcomment', 'TicketcommentController');
                    Route::controller('gamestitle', 'GamestitleController');
                    Route::controller('order', 'OrderController');
                    Route::controller('pendingrequest', 'PendingrequestController');
                    Route::controller('tablecols', 'TablecolsController');
                    Route::controller('mylocationgame', 'MylocationgameController');
                    Route::controller('shopfegrequeststore', 'ShopfegrequeststoreController');
                    Route::controller('addtocart', 'AddtocartController');
                    Route::controller('managefegrequeststore', 'ManagefegrequeststoreController');
                    Route::controller('managenewgraphicrequests', 'ManagenewgraphicrequestsController');
                    Route::controller('manageservicerequests', 'ManageservicerequestsController');
                    Route::controller('gamesintransit', 'GamesintransitController');
                    Route::controller('gamesdisposed', 'GamesdisposedController');
                    Route::controller('spareparts', 'SparepartsController');
                    Route::controller('submitservicerequest', 'SubmitservicerequestController');
                    Route::controller('gameservicehistory', 'GameservicehistoryController');
                    Route::controller('merchindisetheminggallary', 'MerchindisetheminggallaryController');
                    Route::controller('redemptioncountergallary', 'RedemptioncountergallaryController');
                    Route::controller('getfreightquote', 'GetfreightquoteController');
                    Route::controller('managefreightquoters', 'ManagefreightquotersController');
                    Route::controller('freightquoters', 'FreightquotersController');
                    Route::controller('trainingmaterial', 'TrainingmaterialController');

                    Route::controller('itemreceipt', 'ItemreceiptController');
                    
                    // Routes for Reports [start]
                    Route::controller('bottomgame', 'BottomgameController');                    // Bottom Games report - TODO: Not required, remove soon
                    Route::controller('bottomgamesreport', 'BottomgamesreportController');      // Bottom Games report - TODO: Not required, remove soon
                    Route::controller('bottomgamesavgplays', 'BottomgamesavgplaysController');  // Bottom Games by average report - TODO: Not required, remove soon
                    Route::controller('closedlocations', 'ClosedlocationsController');          // Cloased Locations report
                    Route::controller('gameplayreport', 'GameplayreportController');            // Game wise Game Play report
                    Route::controller('gameplayrankbylocation', 'GameplayrankbylocationController');// Location wise game play report
                    Route::controller('gamesdown', 'GamesdownController');                      // Games down report
                    Route::controller('gamesnotondebitcard', 'GamesnotondebitcardController');  // Games not on debit card report
                    Route::controller('gamesnotplayed', 'GamesnotplayedController');            // Games not played report
                    Route::controller('topgamesreport', 'TopgamesreportController');            // Game Title wise game play report
                    Route::controller('reports', 'ReportsController');                          // Location not reporting report
                    Route::controller('merchthrowsdetailed', 'MerchthrowsdetailedController');  // Merch throws deailed report (old but required)
                    Route::controller('merchthrowssimple', 'MerchthrowssimpleController');      // Merch throws simple report (old but required)
                    Route::controller('merchandisebudget', 'MerchandisebudgetController');      // Merch budget report - (old but required)
                    Route::controller('merchandiseexpensesreport', 'MerchandiseexpensesreportController');// Merch expenses report - (old but required)
                    Route::controller('potentialoverreportingerrors', 'PotentialoverreportingerrorsController');// Potential over reporting error report
                    Route::controller('productusagereport', 'ProductusagereportController');    // Product Usage report
                    Route::controller('productsindevelopmentreport', 'ProductsindevelopmentreportController');// Products in development report
                    Route::controller('nonfegreaders', 'NonfegreadersController');              // Non FEG Readers report
                    Route::controller('readersmissingassetidreport', 'ReadersmissingassetidreportController');// Readers with missing asset ids report
                    Route::controller('topgame', 'TopgameController');                          // top games report - TODO: Not required, remove soon
                    Route::controller('topgamesavgplays', 'TopgamesavgplaysController');        // top games report by average - TODO: Not required, remove soon
                    Route::controller('throwreport', 'ThrowreportController');
                    // Routes for Reports [end]

                    ?>