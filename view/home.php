<div class="container mt-5">
    <style>
        .product-card {
            display: block;
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s ease-in-out;
        }

        .product-card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-img-container {
            width: 100%;
            height: 200px;
            overflow: hidden;
        }

        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .rating {
            color: #ffcc00;
        }
        .swiper-container {
        width: 100%;
        height: 500px;
        /* Giảm chiều cao banner */
    }

    .swiper-slide {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .banner-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Cắt ảnh để vừa với chiều cao mới */
        border-radius: 10px;
        /* Làm tròn góc banner */
    }
    </style>
    <div class="container mt-4">
        
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php foreach ($banners as $banner): ?>
                    <div class="swiper-slide">
                        <a href="<?= htmlspecialchars($banner['link']) ?>" target="_blank">
                            <img src="<?= htmlspecialchars($banner['image_url']) ?>" class="banner-image" alt="Banner">
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Nút điều hướng -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>  
    <br>
    <br>
    <!-- Form Search, Filter, Sort -->
    <h2 class="text-center">Danh Sách Sản Phẩm</h2>

    <form method="GET" action="">
        <div class="row mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-control">
                    <option value="">Tất cả danh mục</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="sort" class="form-control">
                    <option value="">Sắp xếp theo</option>
                    <option value="price_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : '' ?>>Giá tăng dần</option>
                    <option value="price_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : '' ?>>Giá giảm dần</option>
                    <option value="name_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'name_asc') ? 'selected' : '' ?>>Tên A-Z</option>
                    <option value="name_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'name_desc') ? 'selected' : '' ?>>Tên Z-A</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Lọc</button>
            </div>
        </div>
    </form>

    <!-- Danh sách sản phẩm -->
    <div class="row row-cols-2 row-cols-md-5 g-4">
        <?php foreach ($products as $product): ?>
            <div class="col">
                <a href="/products/<?= $product['id'] ?>" class="product-card">
                    <div class="card shadow-sm">
                        <div class="card-img-container">
                            <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                        </div>
                        <div class="card-body text-center">
                            <h6 class="card-title text-truncate" style="max-width: 100%;">
                                <?= htmlspecialchars($product['name']) ?>
                            </h6>
                            <p class="text-danger fw-bold">$<?= number_format($product['price'], 2) ?></p>
                            <div class="rating">
                                &#9733; &#9733; &#9733; &#9733; &#9734;
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<!-- Thêm CSS để chỉnh chiều cao banner -->




<!-- Thêm thư viện SwiperJS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper('.swiper-container', {
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        }
    });
</script>