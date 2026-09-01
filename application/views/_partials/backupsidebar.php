<!-- Sidebar -->
<!-- <?php var_dump($menu) ?> -->
<ul class="sidebar navbar-nav">
    <li class="nav-item <?php echo $this->uri->segment(2) == '' ? 'active': '' ?>">
        <a class="nav-link" href="<?php echo site_url('Halamanutama') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Overview</span>
        </a>
    </li>
    <li class="nav-item dropdown <?php echo $this->uri->segment(2) == 'products' ? 'active': '' ?>">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            <i class="fas fa-fw fa-boxes"></i>
            <span>Master</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">  
            <a class="dropdown-item" href="<?php echo site_url('Karyawan/tampilkaryawan') ?>">Karyawan</a>
            <a class="dropdown-item" href="<?php echo site_url('Lantai/tampillantai') ?>">Lantai</a>
            <a class="dropdown-item" href="<?php echo site_url('Pax/tampilpax') ?>">Pax</a>
            <a class="dropdown-item" href="<?php echo site_url('Cabin/tampilcabin') ?>">Cabin</a>
            <a class="dropdown-item" href="<?php echo site_url('Host/tampilhost') ?>">Host</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('Log/tampillog') ?>">
            <i class="fas fa-fw fa-users"></i>
            <span>LOG</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('form/index') ?>">
            <i class="fas fa-fw fa-cog"></i>
            <span>Monitor</span></a>
    </li>
</ul>
