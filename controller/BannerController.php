<?php
require_once "model/BannerModel.php";

class BannerController
{
    private $bannerModel;

    public function __construct()
    {
        $this->bannerModel = new BannerModel();
    }

    public function index()
    {
        $banners = $this->bannerModel->getAllBanners();
        renderView("view/banner/list.php", compact('banners'), "Manage Banners");
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image_url = trim($_POST['image_url']);
            $link = trim($_POST['link']);

            if (!empty($image_url) && !empty($link)) {
                $this->bannerModel->addBanner($image_url, $link);
                $_SESSION['success'] = "Thêm banner thành công!";
            } else {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
            }
        }
        header("Location: /banners");
        exit();
    }
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image_url = trim($_POST['image_url']);
            $link = trim($_POST['link']);

            if (!empty($image_url) && !empty($link)) {
                $this->bannerModel->updateBanner($id, $image_url, $link);
                $_SESSION['success'] = "Banner updated successfully!";
                header("Location: /banners");
                exit();
            } else {
                $_SESSION['error'] = "Both fields are required.";
            }
        }

        $banner = $this->bannerModel->getBannerById($id);
        renderView("view/banner/edit.php", compact('banner'), "Edit Banner");
    }
    public function delete($id)
    {
        $this->bannerModel->deleteBanner($id);
        $_SESSION['success'] = "Xóa banner thành công!";
        header("Location: /banners");
        exit();
    }
}
