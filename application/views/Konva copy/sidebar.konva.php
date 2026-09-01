
    <aside id="leftsidebar" class="sidebar">
     
      <!-- #User Info -->
        <!-- Menu -->
        <div class="menu">
          <ul class="list">
              <li class="header">Building</li>
              <?php foreach ($building as $key => $value): ?>
                <?php if ($value['id'] == $query['building']): ?>
                    <li class="active">
                      <a href="javascript:;">
                        <i class="material-icons">my_location</i>
                        <span><?= $value['name']?></span>
                      </a>
                    </li>
                <?php else: ?>
                  <li class="">
                      <a href="<?= base_url()."konva?building=".$value['id']?>">
                        <i class="material-icons">my_location</i>
                        <span><?= $value['name']?></span>
                      </a>
                    </li>
                <?php endif ?>
                
              <?php endforeach ?>
              <li class="header">Floor</li>
              <ul class="ml-menu" id="id_floor_list" style="display:block;">
                  
              </ul>

              <li class="header">Area</li>
                            <li class="">
                <a href="javascript:;">
                  <i class="material-icons">my_location</i>
                  <span>Add New Area</span>
                  
                  <div class="col-xs-4 align-right">
                    <button onclick="addNewArea()" id="id_btn_create_shape" type="button" class="btn btn-primary btn-circle waves-effect waves-circle waves-float">
                      <i class="material-icons">add</i>
                    </button>
                  </div>
                </a>
              </li>
              <ul class="ml-menu" id="id_floor_area" style="display:block;">
                  
              </ul>
             
             
              
              
          </ul>
        </div>
              
        </aside>
        <!-- #END# Left Sidebar -->

