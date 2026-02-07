<?php

class FavoritesPageController
{
    public function index()
    {
        require_once __DIR__ . '/../../../../controllers/controllers-backend/content-client/FavoriteController.php';

        $favorites = FavoriteController::getUserFavorites();
        $title = "รายการที่น่าสนใจของคุณ";

        ob_start();
        require __DIR__ . '/../../../../views/pages/client/content-favorites/favorites.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../../views/layouts/main.php';
    }
}
