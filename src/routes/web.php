<?php

$basePath = getenv('BASE_PATH');
$uri = str_replace($basePath, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));


ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();

if (!in_array($uri, ['', '/', '/home', '/signin', '/signup', '/forgot-password', '/reset-password', '/google-callback', '/logout']) && !isset($_SESSION['user'])) {
    header('Location: signin');
    exit;
}


switch ($uri) {
    // client
    case '':
    case '/':
        require __DIR__ . '/../controllers/controllers-frontend/client-contrller/controller-home/HomeController.php';
        (new LandingController)->index();
        break;
    case '/home':
        require __DIR__ . '/../controllers/controllers-frontend/client-contrller/controller-home/HomeController.php';
        (new HomeController)->index();
        break;
    case '/signin':
        require __DIR__ . '/../views/pages/client/content-auth/signin.php';
        break;
    case '/signup':
        require __DIR__ . '/../views/pages/client/content-auth/signup.php';
        break;
    case '/forgot-password':
        require __DIR__ . '/../views/pages/client/content-auth/forgot-password.php';
        break;
    case '/reset-password':
        require __DIR__ . '/../views/pages/client/content-auth/reset-password.php';
        break;
    case '/logout':
        require __DIR__ . '/../views/pages/client/content-auth/logout.php';
        break;
    case '/google-callback':
        require __DIR__ . '/../views/pages/client/content-auth/google-callback.php';
        break;
    case '/live-artworks':
        require __DIR__ . '/../controllers/controllers-frontend/client-contrller/controller-allArtwork/Allartwork-controller.php';
        (new AllArtworkController)->live();
        break;
    case '/upcoming-artworks':
        require __DIR__ . '/../controllers/controllers-frontend/client-contrller/controller-allArtwork/Allartwork-controller.php';
        (new AllArtworkController)->upcoming();
        break;
    case '/artwork-detail':
        require __DIR__ . '/../controllers/controllers-frontend/client-contrller/controller-artworkDetail/Artworkdetail-controller.php';
        (new artworkDetailController)->artworkDtail();
        break;
    case '/place-bid':
        require __DIR__ . '/../controllers/controllers-backend/content-client/BidController.php';
        BidController::placeBid();
        break;
    case '/toggle-favorite':
        require __DIR__ . '/../controllers/controllers-backend/content-client/FavoriteController.php';
        FavoriteController::toggleFavorite();
        break;
    case '/favorites':
        require __DIR__ . '/../controllers/controllers-frontend/client-contrller/controller-favorites/FavoritesController.php';
        (new FavoritesPageController)->index();
        break;
    case '/my-auctions':
        require __DIR__ . '/../controllers/controllers-frontend/client-contrller/controller-auctions/MyAuctionsController.php';
        (new MyAuctionsController)->index();
        break;
    case '/settings':
        require __DIR__ . '/../controllers/controllers-frontend/client-contrller/controller-settings/SettingsController.php';
        SettingsController::index();
        break;
    case '/update-profile':
        require __DIR__ . '/../controllers/controllers-frontend/client-contrller/controller-settings/SettingsController.php';
        SettingsController::updateProfile();
        break;
    case '/update-address':
        require __DIR__ . '/../controllers/controllers-frontend/client-contrller/controller-settings/SettingsController.php';
        SettingsController::updateAddress();
        break;
    case '/delete-profile-picture':
        require __DIR__ . '/../controllers/controllers-frontend/client-contrller/controller-settings/SettingsController.php';
        SettingsController::deleteProfilePicture();
        break;
    case '/update-payment':
        require __DIR__ . '/../controllers/controllers-backend/content-payment/PaymentAccountController.php';
        (new PaymentAccountController)->updateForUser();
        break;

    // seller
    case '/seller':
    case '/seller/dashboard':
        require __DIR__ . '/../controllers/controllers-frontend/seller-controller/controller-dashboard/DashboardController.php';
        (new DashboardController)->dashboaed();
        break;
    case '/seller/art-listings':
        require __DIR__ . '/../controllers/controllers-frontend/seller-controller/content-artList/ArtlistController.php';
        (new ArtlistController)->artlist();
        break;
    case '/seller/art-listings/form':
        require __DIR__ . '/../controllers/controllers-frontend/seller-controller/content-artList/ArtlistController.php';
        (new ArtlistController)->form();
        break;
    case '/seller/art-listings/update-artList':
        require __DIR__ . '/../controllers/controllers-frontend/seller-controller/content-artList/ArtlistController.php';
        (new ArtlistController)->update_form();
        break;
    case '/seller/sales-history':
        require __DIR__ . '/../controllers/controllers-frontend/seller-controller/content-sales-history/SalesHistoryController.php';
        (new SalesHistoryController)->index();
        break;
    case '/seller/bidders-list':
        require __DIR__ . '/../controllers/controllers-frontend/seller-controller/content-bidderlist/BidderlistController.php';
        (new BidderlistController)->bidderlist();
        break;
    case '/seller/payment':
        require __DIR__ . '/../controllers/controllers-frontend/seller-controller/content-payment/PaymentController.php';
        (new PaymentController)->payment();
        break;
    case '/verify':
        require __DIR__ . '/../views/pages/seller/content-verifyEmail/verify_action.php';
        break;
    case '/verify_email':
        require __DIR__ . '/../views/pages/seller/content-verifyEmail/verify.php';
        break;



    //admin
    case '/admin':
        require __DIR__ . '/../controllers/controllers-frontend/admin-controller/content-dashboard/DashboardController.php';
        (new DashboardAdminController)->dashboard();
        break;
    case '/admin/users':
        require __DIR__ . '/../controllers/controllers-frontend/admin-controller/content-users/UserManagementController.php';
        (new UserManagementController)->index();
        break;
    case '/admin/user-details':
        require __DIR__ . '/../controllers/controllers-frontend/admin-controller/content-users/UserManagementController.php';
        UserManagementController::getUserDetails();
        break;
    case '/admin/update-role':
        require __DIR__ . '/../controllers/controllers-frontend/admin-controller/content-users/UserManagementController.php';
        UserManagementController::updateUserRole();
        break;
    case '/admin/create-user':
        require __DIR__ . '/../controllers/controllers-frontend/admin-controller/content-users/UserManagementController.php';
        UserManagementController::createUser();
        break;
    case '/admin/payment/account':
        require __DIR__ . '/../controllers/controllers-backend/content-payment/PaymentAccountController.php';
        (new PaymentAccountController)->index();
        break;
    case '/admin/payment/account/update':
        require __DIR__ . '/../controllers/controllers-backend/content-payment/PaymentAccountController.php';
        (new PaymentAccountController)->update();
        break;
    case '/admin/payment/transactions':
        require __DIR__ . '/../controllers/controllers-backend/content-payment/PaymentManagementController.php';
        (new PaymentManagementController)->index();
        break;
    case '/admin/payment/verify':
        require __DIR__ . '/../controllers/controllers-backend/content-payment/PaymentManagementController.php';
        (new PaymentManagementController)->verifyPayment();
        break;
    case '/admin/payment/mark-paid':
        require __DIR__ . '/../controllers/controllers-backend/content-payment/PaymentManagementController.php';
        (new PaymentManagementController)->markPaid();
        break;
    case '/admin/payment/reject':
        require __DIR__ . '/../controllers/controllers-backend/content-payment/PaymentManagementController.php';
        (new PaymentManagementController)->rejectPayment();
        break;
    case '/admin/payment/revenue':
        require __DIR__ . '/../controllers/controllers-backend/content-payment/RevenueController.php';
        (new RevenueController)->index();
        break;
    case '/my-payments':
        require __DIR__ . '/../controllers/controllers-backend/content-payment/BuyerPaymentController.php';
        (new BuyerPaymentController)->index();
        break;
    case '/payment/upload-slip':
        require __DIR__ . '/../controllers/controllers-backend/content-payment/BuyerPaymentController.php';
        (new BuyerPaymentController)->uploadSlip();
        break;
    case '/pay':
        require __DIR__ . '/../controllers/controllers-backend/content-payment/BuyerPaymentController.php';
        (new BuyerPaymentController)->singlePayment();
        break;
    case '/collection':
        require __DIR__ . '/../controllers/controllers-backend/content-collection/CollectionController.php';
        (new CollectionController)->index();
        break;
    case '/filter-artworks':
        require __DIR__ . '/../views/pages/client/content-home/filter_artworks.php';
        break;
    default:
        require __DIR__ . '/../components/content-notfound/notfound-404.php';
        break;
}
