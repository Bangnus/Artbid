<?php
class ArtlistController
{
    public function artlist()
    {
        $title = "รายการศิลปะ";

        ob_start();
        require __DIR__ . '/../../../../views/pages/seller/content-artList/artlist.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../../views/layouts/seller.php';
    }

    public function form()
    {
        $title = "เพิ่มผลงาน";

        ob_start();
        require __DIR__ . '/../../../../views/pages/seller/content-artList/form.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../../views/layouts/seller.php';
    }

    public function update_form()
    {
        $title = "แก้ไขผลงาน";

        ob_start();
        require __DIR__ . '/../../../../views/pages/seller/content-artList/update_form.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../../views/layouts/seller.php';
    }
}
