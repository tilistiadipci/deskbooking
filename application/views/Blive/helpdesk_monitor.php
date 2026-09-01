<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=320, initial-scale=1" />
    <?php $this->load->view("_partials/head_css_dashboard.php", array('pagename'=>"Monitor Helpdesk"));?>
    <link href="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
   <link href="<?= base_url()?>assets/external/daterangepicker/daterangepicker.css" rel="stylesheet" />

</head>
<body>
  <section class="content" style="margin: 100px 15px 0 0px !important;">
    <div class="container">
            <div class="row clearfix">
              <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <img src="<?= base_url()?>assets/Logo_Telkom_Full_Teks.png" style="height: 120px;">
              </div>
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div>
                    <h2>Receptionist Apps</h2>
                    <h3>Telkom Smart Office Lt. 52</h3>
                </div>
              </div>
            </div>
            <br>
            <br>
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-6">
                                    <!-- <h2>Access List</h2> -->
                                </div>
                                <div class="col-xs-12 col-sm-6 align-right">
                    
                                </div>
                            </div>
                        </div>
                        <div class="body ">
                            <div class="row">
                              <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons">date_range</i>
                                        </span>
                                        <div class="form-line">
                                            <input id="daterangepicker" type="text" class="form-control " >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix" style="max-height: 400px;overflow-y: scroll; ">
                                <div class="table-responsive responsive">
                                    <table class="table table-hover table-bordered" id="tbldata">
                                      <thead>
                                              <th>No</th>
                                              <th>Room/Location</th>
                                              <th>Time</th>
                                              <th>Action</th>
                                              <th>Response</th>
                                              <th>Comment Section</th>
                                      </thead>
                                      <tbody>
                                                 
                                      </tbody>
                                    </table>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
               
            </div>
            <!-- ROW -->
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="body " style="background-color: #FF922E !important;">
                            <div class="row">
                              <div class="col-xs-12">
                                <center><h2 style="color:#000  !important;">SLIDING DOOR</h2></center>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-xs-2"></div>
                              <div class="col-xs-4">
                                <button style="height: 50px; background-color: #181818 !important; border-radius: 10px !important; " type="button"  onclick="onActionGate('off')" class="btn btn-lg btn-block btn-primary  waves-effect">OPEN</button>
                              </div>
                              <div class="col-xs-4">
                                <button style="height: 50px; background-color: #fff !important; border-radius: 10px !important; color: black;" type="button"  onclick="onActionGate('on')" class="btn btn-lg btn-block btn-primary  waves-effect">CLOSE</button>
                              </div>
                              <div class="col-xs-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
               
            </div>
            <!-- ROW -->

        </div>
  </section>

  <div class="modal fade" id="id_mdl_create" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="idmdlcrLabel">Helpdesk Monitor Detail </h4>
                        </div>
                        <div class="modal-body " id="id_mdl_create_body">
                            <form id="frm_editcreate">
                                
                                <div class="row clearfix">
                                  <div class="col-sm-6 col-xs-12">
                                    <label for="">Room Name</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text"  class="form-control" autocomplete="off" id="id_crt_room_name" readonly="">
                                        </div>
                                    </div>
                                  </div>
                                  <div class="col-sm-6 col-xs-12">
                                    <label for="">Time</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input  class="form-control" type="text" autocomplete="off" id="id_crt_datetime" readonly="">
                                        </div>
                                    </div>
                                  </div>
                                </div>
                                <label for="">Response</label>
                                <div class="form-group">
                                  <select id="id_crt_response" name="response"  required="" class="form-control"  >
                                    
                                  </select>
                                </div>
                                <label for="">Comment</label>
                                <div class="form-group">
                                  <div class="form-line">
                                    <textarea  class="form-control" id="id_crt_reason_response" name="reason_response"></textarea>
                                  </div>
                                  <input type="hidden" autocomplete="off" name="id" id="id_crt_id" required=""  >
                                
                                </div>
                                <br>
                                <button type="submit" style="display: none;" id="id_btn_type_crt_submit" class="btn btn-primary m-t-15 waves-effect">Submit</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <div class="row clearfix">
                                <div class="col-xs-6 align-left">
                                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                                </div>
                                <div class="col-xs-6 align-right">
                                    <button onclick="clickSubmit('id_btn_type_crt_submit')" type="button" class="btn btn-primary waves-effect " >SAVE</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
  <input type="hidden" id="id_baseurl" value="<?= base_url()?>">
  <?php $this->load->view("_partials/js_dashboard.php");?>
  <!-- Moment Plugin Js -->
  <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment.js"></script>
  <script src="<?= base_url()?>assets/external/daterangepicker/daterangepicker.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/sweatalert2/sweetalert2.all.min.js"></script>
  <script type="text/javascript">
    var gResponse = {
      0 : "Request", 
      1 : "On process", 
      2 : "Solved/Done"
    }
    $(function(){
      enable_datetimepicker()
      init();
    }) 
    setInterval(function(){
      var d =  $('#daterangepicker').val();
      var spdate = d.split(" - ");
      init(spdate[0], spdate[1]);

    }, 3000);
    function clickSubmit(id){
      $('#'+id).click();
    }
    function initTable(selector){
            selector.DataTable();
        }
        function clearTable(selector){
            selector.DataTable().destroy();
        }
        function select_enable(){
            $('select').selectpicker("refresh");
            $('select').selectpicker("initialize");
        }
        function enable_datetimepicker(){
            $('.input-group #daterangepicker').daterangepicker({
                "locale" : {
                    format:"YYYY-MM-DD"
                },
                "showWeekNumbers": true,
                "showISOWeekNumbers": true,
                "opens": "center",
                "drops": "down",
                
                ranges: {
                    'Today': [moment(), moment()],
                },
                "alwaysShowCalendars": true,
            }, function(start, end, label) {
              init(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))
            });
    }
function onActionGate(data){
      var bs = $('#id_baseurl').val();
      // var id = t.data('id')
      $.ajax({
        url : bs+"blive/action/gate1/"+data,
        type : "GET",
        dataType: "json",
        
        beforeSend: function(){
        },
        success:function(data){
          if(data.status == "success"){
            
          }else{
          }
        },
        error: errorAjax
      })
    }
    function init(start = "",end = ""){
      var bs = $('#id_baseurl').val();
      if (start == ""){
        start = moment().format('YYYY-MM-DD');
      }
      if (end == ""){
        end = moment().format('YYYY-MM-DD');
      }
      $.ajax({
        url : bs+"blive/data/helpdesk",
        type : "GET",
        dataType: "json",
        data : {
          start : start,
          end : end,
        },
        beforeSend: function(){
        },
        success:function(data){
          if(data.status == "success"){
            var html ="";
            var num =0;
            $.each(data.collection, function(index, item){
                            num ++;
                            var datet = moment(item.datetime).format('DD MMM,YYYY');
                            var action = item.action == 1 ? "Trigger":"";
                            var response = ""
                            if(item.response == 1){
                              response = "On process"
                            }else if(item.response == 2){
                              response = "Solved"
                            }else if(item.response == 0){
                              response = "Help Request"
                            }
                            var response = item.response == 1 ? "Solved":"In process";
                            var warna  = "#fff";
                            var warnatext  = "#000";
                            if(item.action == 1){
                              warna = "red";
                            }
                            if(item.action == 1){
                              warnatext = "#fff";
                            }
                            html += '<tr onclick="onCllickRow($(this))" style=cursor:pointer;background-color:'+warna+'; " data-id="'+item.id+'">';
                            html += '<td style="color:'+warnatext+'" >'+num+'</td>';
                            html += '<td style="color:'+warnatext+'" >'+item.room_name+'</td>';
                            html += '<td style="color:'+warnatext+'" >'+datet+'</td>';
                            html += '<td style="color:'+warnatext+'" >'+action+ '</td>';
                            html += '<td style="color:'+warnatext+'" >'+response+'</td>';
                            html += '<td style="color:'+warnatext+'" >'+item.reason_response+'</td>';
                            html += '</tr>';
            })
            $('#tbldata tbody').html(html)

          }else{
          }
        },
        error: errorAjax
      })  
            
    }
    function onCllickRow(t){
      var bs = $('#id_baseurl').val();
      var id = t.data('id')
      $.ajax({
        url : bs+"blive/data/helpdesk/detail",
        type : "GET",
        dataType: "json",
        data : {
          id : id,
        },
        beforeSend: function(){
        },
        success:function(data){
          if(data.status == "success"){
            var html ="";
            var num =0;
            var col = data.collection;
            var datet = moment(col.datetime).format('DD MMM,YYYY');
            $('#id_crt_room_name').val(col.room_name);
            $('#id_crt_datetime').val(datet);
            $('#id_crt_id').val(col.id);
            $('#id_crt_reason_response').val(col.reason_response);
            var htmlresponse = "";
            for(var x in gResponse){
              var s = col.response ==x? "selected":"";
              htmlresponse += "<option "+s+" value='"+x+"'>"+gResponse[x]+"</option>";
            }
            $('#id_crt_response').html(htmlresponse);
            select_enable();
            $('#id_mdl_create').modal('show')
          }else{
          }
        },
        error: errorAjax
      })  
    }

    $('#frm_editcreate').submit(function(e){
            e.preventDefault();
            var form = $('#frm_editcreate').serialize();
            Swal.fire({
                title:'Are you sure you want save it?',
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Save !',
                cancelButtonText: 'Cancel !',
                reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        var bs = $('#id_baseurl').val();
                        $.ajax({
                            url : bs+"blive/data/helpdesk/detail/submit",
                            type: "POST",
                            data : form,
                            dataType :"json",
                            beforeSend: function(){
                            },
                            success:function(data){
                                if (data.status == "success") {
                                    showNotification('alert-success', "Succes deleted room "+name ,'top','center')
                                    var d =  $('#daterangepicker').val();
                                    var spdate = d.split(" - ");
                                    init(spdate[0], spdate[1]);
                                    $('#id_mdl_create').modal('hide')
                                }else{
                                    showNotification('alert-danger', "Data not found",'bottom','left')
                                }
                            },
                            error: errorAjax,
                        })
                    }
                else{

                }
            })
        })
    function errorAjax(xhr, ajaxOptions, thrownError){
            $('#id_loader').html('');
            if(ajaxOptions == "parsererror"){
                var msg = "Status Code 500, Error Server bad parsing";
                // showNotification('alert-danger', msg,'bottom','left')
            }else{
                var msg ="Status Code "+ xhr.status + " Please check your connection !!!";
                // showNotification('alert-danger', msg,'bottom','left')
            }
        }
  </script>
</body>
</html>
