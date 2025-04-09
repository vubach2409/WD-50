</ul>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-box"></i>
            <span>Quản lý sản phẩm</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.products.index') }}">
                    Sản phẩm
                </a>
                <a class="collapse-item" href="{{ route('admin.product_variants.list') }}">
                    Biến thể
                </a>
            </div>
        </div>
    </li>

    <!-- Quản lý thuộc tính -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVari"
            aria-expanded="true" aria-controls="collapseVari">
            <i class="fas fa-palette"></i>
            <span>Quản lý thuộc tính</span>
        </a>
        <div id="collapseVari" class="collapse" aria-labelledby="headingVari" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.colors.index') }}">
                    Màu sắc
                </a>
                <a class="collapse-item" href="{{ route('admin.sizes.index') }}">
                    Kích thước
                </a>
            </div>
        </div>
    </li>

    <!-- Quản lý danh mục -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCategory"
            aria-expanded="true" aria-controls="collapseCategory">
            <i class="fas fa-fw fa-list"></i>
            <span>Quản lý danh mục</span>
        </a>
        <div id="collapseCategory" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.categories.index') }}">
                    Danh sách danh mục
                </a>
                <a class="collapse-item" href="{{ route('admin.categories.create') }}">
                    Thêm danh mục
                </a>
            </div>
        </div>
    </li>

    <!-- Quản lý thương hiệu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBrand"
            aria-expanded="true" aria-controls="collapseBrand">
            <i class="fas fa-fw fa-tags"></i>
            <span>Quản lý thương hiệu</span>
        </a>
        <div id="collapseBrand" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.brands.index') }}">
                    Danh sách thương hiệu
                </a>
                <a class="collapse-item" href="{{ route('admin.brands.create') }}">
                    Thêm thương hiệu
                </a>
            </div>
        </div>
    </li>

    <!-- Quản lý người dùng -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFour"
            aria-expanded="true" aria-controls="collapseFour">
            <i class="fas fa-fw fa-users"></i>
            <span>Quản lý người dùng</span>
        </a>
        <div id="collapseFour" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.users.index') }}">
                    Danh sách người dùng
                </a>
                <a class="collapse-item" href="{{ route('admin.payment.history') }}">
                    Lịch sử mua hàng
                </a>
            </div>
        </div>
    </li>

    <!-- Quản lý thanh toán -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSeven"
            aria-expanded="true" aria-controls="collapseSeven">
            <i class="fas fa-fw fa-credit-card"></i>
            <span>Quản lý Thanh Toán</span>
        </a>
        <div id="collapseSeven" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.payment.show') }}">
                    Trạng thái thanh toán
                </a>
            </div>
        </div>
    </li>


    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEight"
            aria-expanded="true" aria-controls="collapseEight">
            <i class="bi bi-archive"></i>
            <span>Quản lý đơn hàng</span>
        </a>
        <div id="collapseEight" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.orders.show') }}">
                    Trạng thái đơn hàng
                </a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseNice"
            aria-expanded="true" aria-controls="collapseNice">
            <i class="bi bi-chat-left-text-fill"></i>
            <span>Quản lý Bình luận</span>
        </a>
        <div id="collapseNice" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.feedbacks.index') }}">
                    Danh sách
                </a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
