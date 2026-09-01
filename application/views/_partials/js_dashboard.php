    <!-- Jquery Core Js -->
    <script src="<?php echo base_url("assets/theme/plugins/jquery/jquery.min.js");?>"></script> 
    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url("assets/theme/plugins/bootstrap/js/bootstrap.js");?>"></script>
    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo base_url("assets/theme/plugins/jquery-slimscroll/jquery.slimscroll.js");?>"></script>
    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url("assets/theme/plugins/node-waves/waves.js");?>"></script>
    <!-- Jquery CountTo Plugin Js -->
    <script src="<?php echo base_url("assets/theme/plugins/jquery-countto/jquery.countTo.js");?>" ></script>
    <!-- Custom Js -->
    <script src="<?php echo base_url("assets/theme/js/admin.js");?>" ></script>
    <script src="<?php echo base_url("assets/theme/js/demo.js");?>" ></script>
    <!-- Select Plugin Js -->
    <script src="<?php echo base_url("assets/theme/plugins/bootstrap-select/js/bootstrap-select.js");?>"></script>
    <script src="<?php echo base_url("assets/theme/plugins/bootstrap-notify/bootstrap-notify.js");?>"></script>
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment.js"></script>

    <script>
        
        // $('.block-header').remove();
        $('.block-header').hide();
        function showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit) {
            if (colorName === null || colorName === '') { colorName = 'bg-black'; }
            if (text === null || text === '') { text = 'Turning standard Bootstrap alerts'; }
            if (animateEnter === null || animateEnter === '') { animateEnter = 'animated fadeInDown'; }
            if (animateExit === null || animateExit === '') { animateExit = 'animated fadeOutUp'; }
            var allowDismiss = true;

            $.notify({
                message: text
            },
                {
                    type: colorName,
                    allow_dismiss: allowDismiss,
                    newest_on_top: true,
                    timer: 1000,
                    placement: {
                        from: placementFrom,
                        align: placementAlign
                    },
                    animate: {
                        enter: animateEnter,
                        exit: animateExit
                    },
                    template: '<div data-notify="container" class="bootstrap-notify-container alert alert-dismissible {0} ' + (allowDismiss ? "p-r-35" : "") + '" role="alert">' +
                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                    '<span data-notify="icon"></span> ' +
                    '<span data-notify="title">{1}</span> ' +
                    '<span data-notify="message">{2}</span>' +
                    '<div class="progress" data-notify="progressbar">' +
                    '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                    '</div>' +
                    '<a href="{3}" target="{4}" data-notify="url"></a>' +
                    '</div>'
                });
        }
        
function swalShowNotification(icon, title, loc = "", loc2 = "") {
    var ic = "";
    if (icon == "alert-success") {
        ic = "success";
    } else if (icon == "alert-danger") {
        ic = "danger";
    } else if (icon == "alert-warning") {
        ic = "warning";
    } else if (icon == "alert-info") {
        ic = "info";
    }
    Swal.fire(
        title,
        '',
        ic
    )
}
        // loadNotification() ;
        function loadNotification() {
            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"notification/get",
                type : "POST",
                dataType: "json",
                beforeSend: function(){
                    // $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    var html = "";
                    if(data.length > 0){
                        $.each(data, function(index, item){
                            var timest1 = moment();
                            var timest2 = moment(item.datetime);
                            var kettime = "";
                            if(timest1.diff(timest2, 'month')  > 0){
                                var _mm = timest1.diff(timest2, 'month');
                                kettime = _mm+" month ago";
                            }else if(timest1.diff(timest2, 'days') > 0){
                                var _mm = timest1.diff(timest2, 'days');
                                kettime = _mm+" days ago";
                            }else if(timest1.diff(timest2, 'minute') > 0){
                                var _mm = timest1.diff(timest2, 'minute');
                                kettime = _mm+" mins ago";
                                
                            }else if(timest1.diff(timest2, 'second') > 0){
                                var _mm = timest1.diff(timest2, 'seconds');
                                kettime = "recently";
                            }
                            console.log(timest1.diff(timest2, 'minute'));
                            html += '<li>\
                                        <a href="javascript:void(0);">\
                                            '+item.element+' \
                                            <div class="menu-info">\
                                                <h5>'+item.e_name+" "+item.body+'</h5>\
                                                <p>\
                                                    <i class="material-icons">access_time</i> '+kettime+'\
                                                </p>\
                                            </div>\
                                        </a>\
                                    </li>'
                            
                        })
                        $('#id_notif_menu').html(html);
                        $('#id_notif_menu_count').html(data.length);
                        // $.AdminBSB.navbar.activate();
                        $.AdminBSB.dropdownMenu.activate();
                    }
                },
                    // error: errorAjax
            })
        }
    </script>
    </script>
    <script src='<?php echo base_url("assets/theme/plugins/jquery-validation/jquery.validate.js");?>' ></script>