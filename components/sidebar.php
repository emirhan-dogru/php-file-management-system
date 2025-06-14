<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo $domain ?>">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-server"></i>
                </div>
                <div class="sidebar-brand-text mx-3">ED Admin</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?= Utils::isActiveRoute('') ?>">
                <a class="nav-link" href="<?php echo $domain ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Charts -->
            <li class="nav-item <?= Utils::isActiveRoute('home') ?>">
                <a class="nav-link" href="<?php echo $domain ?>/home">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Dosya Yönetimi</span></a>
            </li>

             <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Hesabım
            </div>

            <!-- Nav Item - Charts -->
            <li class="nav-item <?= Utils::isActiveRoute('profile') ?>">
                <a class="nav-link" href="<?php echo $domain ?>/profile">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profilim</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="./logout">
                    <i class="fas fa-fw fa-sign-out-alt"></i>
                    <span>Çıkış Yap</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>