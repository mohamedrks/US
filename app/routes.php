<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//$router->get('news/{id}','NewsController@index');
//header('Access-Control-All-Headers: Content-Type, Authorization, X-Requested-With, Cache-Control, Accept, Origin, X-Session-ID');
//header('Access-Control-Allow-Methods', 'GET,POST,PUT,HEAD,DELETE,TRACE,COPY,LOCK,MKCOL,MOVE,PROPFIND,PROPPATCH,UNLOCK,REPORT,MKACTIVITY,CHECKOUT,MERGE,M-SEARCH,NOTIFY,SUBSCRIBE,UNSUBSCRIBE,PATCH');
//header('Access-Control-Allow-Credentials', 'false');
//header('Access-Control-Max-Age', '1000');

//Cartalyst\Sentry\Users\Eloquent\User::setStripeKey('sk_test_1E1w55OxswP6V24fL5fqx8Z5');


Route::group(array('prefix' => 'api/v1'), function () {

    Route::get('getOrderDetailsByOrderId/{orderId}','OrderController@getOrderDetailsByOrderId');
    Route::get('getInvoicesByCustomer/{clientId}', 'InvoiceController@getInvoicesByCustomer');

    Route::get('getInvoicesBySupplier/{clientId}', 'PurchaseInvoiceController@getInvoicesBySupplier');


    Route::group(['before' => 'oauth|GlobalAdmin'], function () {

        Route::get('getCategories','ProductCategoryController@getCategories');

        Route::post('buyMore','StockController@buyMore');

        Route::resource('vendor','VendorController');
        Route::get('getSupplierSearch/{req}','VendorController@getSupplierSearch');

        Route::get('getAllOrders','OrderController@getAllOrders');

        Route::get('getAllProfessionals', 'UsersController@getAllProfessionals');


        Route::get('featuredProduct/{product_id}','ProductController@featuredProduct');
        Route::get('unFeaturedProduct/{product_id}','ProductController@unFeaturedProduct');
        Route::get('publishProduct/{product_id}','ProductController@publishProduct');
        Route::get('unPublishProduct/{product_id}','ProductController@unPublishProduct');

        Route::get('getProfessionalDetails/{professionalId}','UsersController@getProfessionalDetails');

    });


    Route::post('getPasswordResetCode','RemindersController@getPasswordResetCode');
    Route::post('postReset','RemindersController@postReset');
    Route::post('postRemind','RemindersController@postRemind');

    Route::resource('productOrder','ProductOrderController');

    Route::get('getCommentsByProduct/{product_id}','ProductCommentController@getCommentsByProduct');

    Route::resource('productCategory','ProductCategoryController');

    Route::get('getProductAverageRating/{product_id}','ProductRatingController@getProductAverageRating');
    Route::get('getProductRatingByUser/{product_id}','ProductRatingController@getProductRatingByUser');
    Route::resource('productRating','ProductRatingController');

    Route::get('getStockById/{stockId}','StockController@getStockById');

    Route::get('testPagination','ProductController@testPagination');
    Route::get('getProductById/{productId}','ProductController@getProductById');
    Route::get('getProductByCategoryWithThumbnail/{category_id}','ProductController@getProductByCategoryWithThumbnail');
    Route::get('getProductByCategory/{category_id}','ProductController@getProductByCategory');
    Route::get('getAllProducts','ProductController@getAllProducts');
    Route::resource('products','ProductController');

    Route::resource('cart','CartController');


    Route::get('mainPic','ApplyController@mainPic');
    Route::post('delete','ApplyController@delete');
    Route::get('getPicturesByProduct/{product_id}','ApplyController@getPicturesByProduct');
    Route::get('showImageByProduct/{product_id}','ApplyController@showImageByProduct');

    Route::post('postUploadFile', 'ApplyController@postUploadFile');
    Route::resource('apply','ApplyController');

    Route::get('isSubscribed', 'UsersController@isSubscribed');
    Route::resource('businessTips', 'BusinessTipsController');

    Route::resource('tips', 'TipsController');

    Route::post('uploadDocument', 'ApplyController@upload');
    Route::post('uploadKeyFacts', 'ApplyController@uploadKeyFacts');
    Route::post('uploadVideos','ApplyController@uploadVideos');

    Route::get('searchItemByIMEINumber/{req}','ItemDetailsController@searchItemByIMEINumber');
    Route::get('searchByIMEINumber/{req}','ItemDetailsController@searchByIMEINumber');
    Route::get('searchByIMEINumberUnsold/{req}','ItemDetailsController@searchByIMEINumberUnsold');


    Route::resource('itemDetails','ItemDetailsController');
    Route::get('getItemDetailsHistoryById','ItemDetailsController@getItemDetailsHistoryById');

    Route::get('getAllSalesCreditors','CreditorsController@getAllSalesCreditors');

    Route::get('getPaymentHistoryByRepairId','RepairDebtorsController@getPaymentHistoryByRepairId');
    Route::get('getPaymentHistoryByRepairOutsourceId','RepairCreditorsController@getPaymentHistoryByRepairOutsourceId');

    Route::resource('repairCreditors','RepairCreditorsController');

    Route::get('getAllRepairCreditors','RepairCreditorsController@getAllRepairCreditors');
    Route::get('getAllSalesDebtors','DebtorsController@getAllSalesDebtors');
    Route::get('getAllRepairDebtors','RepairDebtorsController@getAllRepairDebtors');
    Route::resource('invoice','InvoiceController');
    Route::get('getInvoiceDetailsByInvoiceID','DebtorsController@getInvoiceDetailsByInvoiceID');
    Route::get('invoiceReport/{invoiceId}','InvoiceController@invoiceReport');

    Route::get('getParentCategoryProductsByParentId/{parentCategoryId}','ProductCategoryController@getParentCategoryProductsByParentId');



    Route::group(['before' => 'oauth'], function () {


        Route::get('getCustomerDetailsByInvoiceId','DebtorsController@getCustomerDetailsByInvoiceId');


        Route::get('DailyMoneyMade','StatsController@DailyMoneyMade');
        Route::get('getPurchaseHistoryByVendor','StockController@getPurchaseHistoryByVendor');


        Route::resource('repairDebtors','RepairDebtorsController');

        Route::resource('repair','RepairController');
        Route::resource('exchange','ExchangeController');


        Route::resource('debtors','DebtorsController');
        Route::resource('creditors','CreditorsController');

        Route::get('stockAlert','StockController@stockAlert');

        Route::get('stockNotification','StockController@stockNotification');



        Route::get('getAppointmentsByUser', 'AppointmentController@getAppointmentsByUser');

        Route::get('getSendRequestDetailsFromConsumer','UsersController@getSendRequestDetailsFromConsumer');

        Route::post('getCurrentUserDetails', 'UsersController@getCurrentUserDetails');

        Route::get('getContactById/{contactId}', 'ContactController@getContactById');

        Route::get('getContactTypeById/{id}', 'ContactController@getContactTypeById');
        Route::get('getAllContactsByType/{contactType}','ContactController@getAllContactsByType');

        Route::get('getAcceptedFriendships','FriendshipController@getAcceptedFriendships');
        Route::get('getAllFriendships','FriendshipController@getAllFriendships');
        Route::get('getDeniedFriendships','FriendshipController@getDeniedFriendships');
        Route::get('getPendingFriendships','FriendshipController@getPendingFriendships');
        Route::post('requestByEmail','FriendshipController@requestByEmail');
        Route::post('existFriendship','FriendshipController@existFriendship');
        Route::post('deleteFriendship','FriendshipController@deleteFriendship');
        Route::post('denyFriendship','FriendshipController@denyFriendship');
        Route::post('acceptFriendship','FriendshipController@acceptFriendship');
        Route::post('createFriendship','FriendshipController@createFriendship');
        Route::post('addNewFriendship','FriendshipController@addNewFriendship');

        Route::post('sendOrderToVendor','OrderController@sendOrderToVendor');
        Route::post('payAmount','UsersController@payAmount');

        Route::get('getRecommendedProductByConsumer','ConsumerProductRecommendationsController@getRecommendedProductByConsumer');

        Route::resource('order','OrderController');

        Route::get('rememberedCardDetails','UsersController@rememberedCardDetails');

        Route::get('getChildCategoryProductsByChildId/{childCategoryId}','ProductCategoryController@getChildCategoryProductsByChildId');

        Route::get('getAllChildCategoryByParentCategory/{parentCategoryId}','ProductCategoryController@getAllChildCategoryByParentCategory');
        Route::get('getAllParentCategories','ProductCategoryController@getAllParentCategories');


        Route::post('updateMeal','MealPlanController@updateMeal');
        Route::post('deleteMeal','MealPlanController@deleteMeal');

        Route::get('getAllMealTypes','MealPlanController@getAllMealTypes');
        Route::get('getMealByType/{mealType}', 'MealPlanController@getMealByType');

        Route::resource('mealPlan', 'MealPlanController');
        Route::get('getAllMeal','MealPlanController@getAllMeal');

        Route::get('getAllResourcesByType/{resourceType}', 'NutritionResourceController@getAllResourcesByType');

        Route::get('checkFriendshipForNutritionalCategory','UsersController@checkFriendshipForNutritionalCategory');
        Route::get('checkRequestSentForNutritionalCategory','UsersController@checkRequestSentForNutritionalCategory');

        Route::get('getEstimatedEnergyRequirement', 'UserDetailsController@getEstimatedEnergyRequirement');

        Route::get('getResourceListByType/{resourceType}', 'NutritionResourceController@getResourceListByType');
    });


    Route::resource('friendshipController','FriendshipController');

    Route::get('getNewSearchClients/{req}', 'UsersController@getNewSearchClients');



    Route::group(['before' => 'oauth|Professionals'], function () {

        Route::get('getAllBusinessDaysByProfessional','ProfessionalDetailsController@getAllBusinessDaysByProfessional');
        Route::resource('ProfessionalDetails','ProfessionalDetailsController');


        Route::get('getAppointmentByClientConfirm/{clientId}', 'AppointmentController@getAppointmentByClientConfirm');
        Route::get('getAppointmentByExceptClient/{clientId}', 'AppointmentController@getAppointmentByExceptClient');
        Route::get('getAppointmentByClient/{clientId}', 'AppointmentController@getAppointmentByClient');
        Route::get('getAppointedClientDetails/{appointmentId}', 'AppointmentController@getAppointedClientDetails');

        Route::get('getSearchFriendConsumers/{req}','UsersController@getSearchFriendConsumers');

        Route::get('getClientsAccumulativeCountForProfessional','StatsController@getClientsAccumulativeCountForProfessional');
        Route::get('getAccumulatedMoneyMadeInMonth','StatsController@getAccumulatedMoneyMadeInMonth');
        Route::get('getMoneyMadeInMonth','StatsController@getMoneyMadeInMonth');
        Route::get('getProductsPurchaseInMonth','StatsController@getProductsPurchaseInMonth');
        Route::get('getRecommendationsMadeForDaily','StatsController@getRecommendationsMadeForDaily');
        Route::get('getRecommendationsResultedForDaily','StatsController@getRecommendationsResultedForDaily');
        Route::get('getClientsCountForProfessional','StatsController@getClientsCountForProfessional');

//        Route::post('chargeCustomer','UsersController@chargeCustomer');
        Route::get('getClientDetails/{clientId}','UsersController@getClientDetails');




        Route::get('getRecommendedProductDetails/{consumerId}','ConsumerProductRecommendationsController@getRecommendedProductDetails');

        Route::resource('productRecommendations','ConsumerProductRecommendationsController');

        Route::get('getClientCategory/{clientId}', 'UserDetailsController@getClientCategory');
        Route::get('getClientBMI/{client_id}', 'UserDetailsController@getClientBMI');

        Route::get('getResourceListStatSentByProfessional', 'NutritionResourceController@getResourceListStatSentByProfessional');
        Route::get('getAppointmentStats', 'AppointmentController@getAppointmentStats');


        Route::post('sendResources', 'NutritionResourceController@sendResources');
        Route::get('getSelectedResourceByProfessional/{resourceType}', 'NutritionResourceController@getSelectedResourceByProfessional');
        Route::post('resourceUpdateStatusOfProfessionals', 'NutritionResourceController@resourceUpdateStatusOfProfessionals');
        Route::get('getStatusOfAllResourcesByType/{resourceType}', 'NutritionResourceController@getStatusOfAllResourcesByType');

        Route::get('getSearchClients/{req}', 'UsersController@getSearchClients');



    });

    Route::get('clientReport/{clientId}','ReportController@clientReport');

    Route::get('getAllBusinessDaysByProfessionalId','ProfessionalDetailsController@getAllBusinessDaysByProfessionalId');

    Route::group(['before' => 'oauth|Consumers'], function () {



        Route::get('getAppointmentByConsumer','AppointmentController@getAppointmentByConsumer');

        Route::post('acceptNewFriendship','FriendshipController@acceptNewFriendship');

        Route::get('getClosestGeoLocations', 'ContactController@getClosestGeoLocations');

        Route::resource('productComment','ProductCommentController');

        Route::post('payByPinForRecipients','UsersController@payByPinForRecipients');
        Route::post('payCommission','UsersController@payCommission');


        Route::post('resourceUpdateStatus', 'NutritionResourceController@resourceUpdateStatus');

        Route::get('getSelectedResourceByConsumer/{resourceType}', 'NutritionResourceController@getSelectedResourceByConsumer');


        Route::get('searchCountry/{req}', 'ContactController@searchCountry');


        Route::get('getContactByType/{contactType}', 'ContactController@getContactByType');
        Route::resource('contact', 'ContactController');

        Route::get('getUserDetailsByMeasurementNameForConsumer/{consumerId}/{measurementName}', 'UserDetailsController@getUserDetailsByMeasurementName');
        Route::post('updateMedicalHistory','UserDetailsController@updateMedicalHistory');
    });



    Route::resource('getLocationByIp', 'SuburbController@getLocationByIp');
    Route::get('searchPostalCode/{req}','SuburbController@searchPostalCode');
    Route::get('searchSuburb/{req}','SuburbController@searchSuburb');
    Route::resource('stock','StockController');
    Route::get('getProductSearch/{req}','ProductController@getProductSearch');

    Route::resource('customer','CustomerController');

    Route::get('getCustomerSearch/{req}','CustomerController@getCustomerSearch');


    Route::get('getAllRepairDebtorsPaidPerDay','DebtorsController@getAllRepairDebtorsPaidPerDay');
    Route::get('getAllRepairCreditorsPaidPerDay','CreditorsController@getAllRepairCreditorsPaidPerDay');
    Route::resource('purchaseInvoice','PurchaseInvoiceController');

    Route::get('getPurchaseInvoiceDetailsByPurchaseInvoiceId','CreditorsController@getPurchaseInvoiceDetailsByPurchaseInvoiceId');
    Route::get('getCreditPaymentHistoryByInvoiceId','CreditorsController@getCreditPaymentHistoryByInvoiceId');
    Route::get('getPaymentHistoryByInvoiceId','DebtorsController@getPaymentHistoryByInvoiceId');

    Route::group(['before' => 'oauth|GlobalAdmin,Professionals,Consumers'], function () {

        Route::resource('repairOutsource','RepairOutsourceController');
        Route::get('getTotalRepairOutsourcePaidPerDay','RepairOutsourceController@getTotalRepairOutsourcePaidPerDay');
        Route::get('getTotalRepairOutsourceBalancePerDay','RepairOutsourceController@getTotalRepairOutsourceBalancePerDay');
        Route::get('getTotalRepairOutsourceChargesPerDay','RepairOutsourceController@getTotalRepairOutsourceChargesPerDay');


        Route::get('getAllCreditorsPaidPerDay','CreditorsController@getAllCreditorsPaidPerDay');



        Route::resource('purchaseOrder','PurchaseOrderController');



        Route::get('getTotalPurchaseBalancePerDay','PurchaseInvoiceController@getTotalPurchaseBalancePerDay');
        Route::get('getTotalPurchasePaidPerDay','PurchaseInvoiceController@getTotalPurchasePaidPerDay');
        Route::get('getTotalPurchasePerDay','PurchaseInvoiceController@getTotalPurchasePerDay');



        Route::get('getAllDebtorsPaidPerDay','DebtorsController@getAllDebtorsPaidPerDay');

        Route::get('getTotalRepairPaidPerDay','RepairController@getTotalRepairPaidPerDay');
        Route::get('getTotalRepairBalancePerDay','RepairController@getTotalRepairBalancePerDay');
        Route::get('getTotalRepairChargesPerDay','RepairController@getTotalRepairChargesPerDay');

        Route::get('getTotalBalancePerDay','InvoiceController@getTotalBalancePerDay');
        Route::get('getTotalPaidPerDay','InvoiceController@getTotalPaidPerDay');
        Route::get('getTotalSalesPerDay','InvoiceController@getTotalSalesPerDay');

        Route::get('getAllCreditsForVendor','VendorController@getAllCreditsForVendor');
        Route::get('getAllPurchaseInvoicesByVendor','VendorController@getAllPurchaseInvoicesByVendor');

        Route::get('getAllDebtsForCustomer','CustomerController@getAllDebtsForCustomer');
        Route::get('getAllSalesInvoicesByCustomer','CustomerController@getAllSalesInvoicesByCustomer');



        Route::resource('appointment', 'AppointmentController');

        Route::resource('sms','SmsController');

        Route::resource('notification', 'NotificationController');

        Route::resource('nutritionResource', 'NutritionResourceController');

        Route::get('getClients', 'UsersController@getClients');

        Route::get('getTipsByCategory/{categoryId}', 'TipsController@getTipsByCategory');
        Route::get('getContactTypes', 'ContactController@getContactTypes');

        Route::get('getMealSelected/{mealType}', 'MealPlanController@getMealSelected');

        Route::get('getClientFamilyMedicalReport', 'UserDetailsController@getClientFamilyMedicalReport');
        Route::get('getFamilyMedicalReport', 'UserDetailsController@getFamilyMedicalReport');
        Route::get('getClientMedicalReport','UserDetailsController@getClientMedicalReport');
        Route::get('getMedicalReport', 'UserDetailsController@getMedicalReport');

        Route::get('getCategory', 'UserDetailsController@getCategory');
        Route::get('getBMI', 'UserDetailsController@getBMI');
        Route::get('getClientEstimatedEnergyRequirement','UserDetailsController@getClientEstimatedEnergyRequirement');

        Route::get('getClientUserDetailsByMeasurementName','UserDetailsController@getClientUserDetailsByMeasurementName');
        Route::get('getUserDetailsByMeasurementName/{measurementName}', 'UserDetailsController@getUserDetailsByMeasurementName');

        Route::resource('userDetails', 'UserDetailsController');

        Route::get('getNotificationByConsumer', 'NotificationController@getNotificationByConsumer');

        Route::resource('suburb', 'SuburbController');

        Route::resource('resourceType', 'ResourceTypeController');


        Route::resource('sms', 'SmsController');

        Route::resource('email', 'EmailController');

        Route::resource('country', 'CountryController');

        Route::get('getUserDetailsByUserId/{userId}/{measurementName}', 'UserDetailsController@getUserDetailsByUserId');

        Route::post('receiveDetails', 'UsersController@receiveDetails');
        Route::get('getPermissions/{req}', 'UsersController@getPermissions');



        Route::get('getMenuByUser', 'MenuController@getMenuByUser');
        Route::resource('menu', 'MenuController');


        Route::get('createGroupPermission', 'CreateGroupController@createGroupPermission');
        Route::resource('groupPermission', 'CreateGroupController');

        Route::post('removeGroupMenu', 'MenuController@removeGroupMenu');
        Route::post('addGroupMenu', 'MenuController@addGroupMenu');
        Route::get('getMenuStatus/{groupId}/{menuId}', 'MenuController@getMenuStatus');

        Route::get('getGroupMenu/{groupId}', 'GroupsController@getGroupMenu');
    });

    Route::get('getContactTypes', 'ContactController@getContactTypes');

    Route::resource('user', 'UsersController');

    Route::resource('group', 'GroupsController');

    Route::resource('consumerSummary', 'ConsumerSummaryController');

//    Route::post('stripe/webhook', 'WebhookController@handleWebhook');
    Route::post('stripe/webhook', 'WebhookController@handleInvoicePaymentSucceeded');
    Route::post('stripe/failedPayment','WebhookController@handleInvoicePaymentFailed');


//    Route::post('removeToTempResources','NutritionResourceController@removeToTempResources');
//    Route::post('addToTempResources','NutritionResourceController@addToTempResources');


    Route::get('testMail', function () {

        Mail::send('emails.OrderConfirmationToVendorEmail', array('msg' => 'This is the body of my email'), function ($message) {
            $message->from('admin@diamatic.com.au', 'admin');
            $message->to('mohamedrks@gmail.com', 'John Smith')->subject('Welcome!'); // tony.t.lucas@gmail.com
        });

    });

    Route::get('payReferralPaymentsInBulk','PaymentController@payReferralPaymentsInBulk');


    Route::post('oauth/access_token', function () {
        return Response::json(Authorizer::issueAccessToken());
    });

    Route::get('/sendDetails', function () {
        return View::make('hello');

    });

});