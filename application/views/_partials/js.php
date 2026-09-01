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
    <!-- <script src="<?php echo base_url("assets/theme/js/pages/index.js");?>" ></script> -->
    <!-- Demo Js -->
    <script src="<?php echo base_url("assets/theme/js/demo.js");?>" ></script>
    <!-- Select Plugin Js -->
    <script src="<?php echo base_url("assets/theme/plugins/bootstrap-select/js/bootstrap-select.js");?>"></script>
    <script src="<?php echo base_url("assets/theme/plugins/bootstrap-notify/bootstrap-notify.js");?>"></script>
    <script>
        // $(function () {
        //     $.AdminBSB.browser.activate();
        //     $.AdminBSB.leftSideBar.activate();
        //     $.AdminBSB.rightSideBar.activate();
        //     $.AdminBSB.navbar.activate();
        //     $.AdminBSB.dropdownMenu.activate();
        //     $.AdminBSB.input.activate();
        //     $.AdminBSB.select.activate();
        //     $.AdminBSB.search.activate();

        //     setTimeout(function () { $('.page-loader-wrapper').fadeOut(); }, 50);
        // });
        // 
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
                    z_index: 9999,
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
    </script>
    <script src='<?php echo base_url("assets/theme/plugins/jquery-validation/jquery.validate.js");?>'></script>