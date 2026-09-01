
    <aside id="leftsidebar" class="sidebar">
      <!-- User Info -->
      <div class="user-info" style="background:url(<?= base_url()?>download/media-menubar) no-repeat no-repeat;    background-size: cover !important;">
          <div class="image">
            <div style="height: 48px; width: 48px;border-radius: 50%;" class="bg-light-blue">
              <center>
                <b style="line-height: 48px;font-size: 28px;"><?php echo strtoupper(substr($this->session->userdata('name-nya'), 0,1))?></b>
              </center>  
            </div>
            <!-- <img src="../../images/user.png" width="48" height="48" alt="User"> -->
          </div>
          <div class="info-container">
              <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $this->session->userdata('name-nya')?></div>
              <div class="email"><?php echo $this->session->userdata('level-nya')?></div>
              <div class="btn-group user-helper-dropdown">
                  <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                  <ul class="dropdown-menu pull-right">
                      <li><a class="dropdown-item" href="<?php echo site_url('profile') ?>">
                        Profile<i class="material-icons">person</i></a></li>
                      <li><a class="dropdown-item" href="<?php echo site_url('authentication/logout') ?>">
                        Sign Out<i class="material-icons">input</i></a></li>
                  </ul>
              </div>
          </div>
      </div>
      <!-- #User Info -->
      <!-- Menu -->
      <div class="menu">
        <ul class="list">
              <li class="header">MAIN NAVIGATION</li>
              <?php foreach ($menumaster as $x): ?>
                <?php $class = ""; ?>
                
                <?php  
                if (count($x['child']) >= 1 ) {
                ?>
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="material-icons"><?= $x['icon'] ?></i>
                    <span><?= $x['name'] ?></span>
                  </a>
                   <ul class="ml-menu">
                     <?php foreach ($x['child'] as $m): ?>
                       <li>
                            <a href="<?= site_url($m['url'])?>"><?= $m['name']?></a>
                       </li>
                     <?php endforeach ?>
                   </ul>
                <?php
                }else{
                  ?>
                  <?php if($x['active'] == 1) $class = "active"; ?>
                  <li class="<?= $class?>">
                    <a href="<?= site_url($x['url']) ?>">
                      <i class="material-icons"><?= $x['icon']?></i>
                      <span><?= $x['name']?></span>
                    </a>
                  </li>
                  <?php
                }
                ?>
                
              <?php endforeach ?>
          </ul>
              </div>
              <!-- #Menu -->
              <!-- Footer -->
              <div class="legal">
              <div class="copyright">
                      &copy; <?= date('Y')?> <a><?= HEAD_NAME?></a>.
              </div>
              <!-- <center><img style="width: 180px;height: 60px; margin-top:5px;" src="<?php echo base_url('')."./images/wifi.png"; ?>"></center>  -->
              </div>
              <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->

