    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?= HEAD_NAME?> <?= "-" .$pagename?></title>
    <!-- Favicon-->
    <link rel="icon" href="<?= base_url()?>download/media-icon" type="image/png" >
    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url("assets/theme/plugins/bootstrap/css/bootstrap.css") ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url("assets/theme/plugins/bs-select/select.css");?>">
    <link href="<?php echo base_url("assets/theme/plugins/iconfont/material-icons.css");?>" rel="stylesheet">
    <!-- Waves Effect Css -->
    <link href="<?php echo base_url("assets/theme/plugins/node-waves/waves.css") ?>" rel="stylesheet" />
    <!-- Animation Css -->
    <link href="<?php echo base_url("assets/theme/plugins/animate-css/animate.css") ?>" rel="stylesheet" />
    <!-- Morris Chart Css-->
    <!-- <link href="<?php echo base_url("assets/theme/plugins/morrisjs/morris.css") ?>" rel="stylesheet" /> -->
    <!-- Custom Css -->
    <link href="<?php echo base_url("assets/theme/css/style.css") ?>" rel="stylesheet">
    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?php echo base_url("assets/theme/css/themes/all-themes.css") ?>" rel="stylesheet" />
    <!-- Bootstrap Select Css -->
   <style>
        div.dropdown-menu.open{
            overflow: unset !important;
            z-index: 9999;
        }
        /*.bootstrap-select.form-control.input-group-btn {
            z-index: auto;
        }*/
        .form-control-big{
            font-size: 20px !important;
            font-weight: normal !important;
            height: 48px !important;
            padding-left: 5px !important;
            border: 1px solid #ccc !important;
            border-radius: 5px !important;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.10), 0 2px 10px rgba(0, 0, 0, 0.10) !important;
        }
        .pretend{
            padding-left: 40px !important;
        }
        .input-group-pretend {
            position: absolute !important;
            z-index: 3 !important;
            line-height: 3 !important;
            left: 12px !important;
        }
        .input-group-pretend i {
            font-size: 18px !important;
        }
        .headersitem{
            font-size: 18px;
            font-weight: normal;
            color: gainsboro;
        }
        .activeheaders{
            border-bottom: 2px solid #fff;
        }


        .radiogroup {
            background: #ececec;
            padding: 3px;
            border-radius: 5px;
            position: relative;
        }

        .radiogroup input {
            width: auto;
            height: 100%;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            outline: none;
            cursor: pointer;
            border-radius: 5px;
            padding: 10px 10px;
            background: #ececec;
            color: #333333;
            font-size: 16px;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
                "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji",
                "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            transition: all 100ms linear;
            opacity:1 !important;
            position:unset !important;
        }


        .radiogroup input:checked {
            background-image: linear-gradient(180deg, #fff, #fff);
            color:  #333333;
            border-radius: 5px;
            box-shadow: 0 1px 1px #0000002e;
            text-shadow: 0 1px 0px #79485f7a;
        }

        .radiogroup input:before {
            content: attr(label);
            display: inline-block;
            text-align: center;
            width: 100%;
        }

       .linePreloader{
            width:100%;
            height:2px;
            background:linear-gradient(to right,red,green,blue);
            background-color:#ccc;
            position:absolute;
            top:0;
            bottom:0;
            left:0;
            right:0;
            /*margin:auto;*/
            border-radius:4px;
            background-size:20%;
            background-repeat:repeat-y;
            background-position:-25% 0;
            animation:scroll 1.2s ease-in-out infinite;
          }
      
         @keyframes scroll{
            50%{background-size:80%}
            100%{background-position:125% 0;}
         }

        .fab-right{
            z-index: 1;
            position:fixed;
            width:60px;
            height:60px;
            bottom:30px;
            left:240px;
            border-radius:50px;
        }
        

   </style>