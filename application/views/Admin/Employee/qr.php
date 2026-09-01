<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Visitor</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }

        .container {
            text-align: center;
            background: #fff;
            padding: 40px 50px;
            border-radius: 10px;
            /* width: 400px; */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .qr-code {
            margin: 0 auto 10px auto;
        }

        .description {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 14px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin: 5px;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container" id="html-content-holder">
        <div class="title">QR Code Working Space</div>
        <center><div id="qrcode"></div></center>
        <br>
        <br>
        <div class="description">
            <center> 
                <table class="table">
                    
                    <tr>
                        <td>Username</td>
                        <td>:</td>
                        <td id="id_username"><?= @$username?></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td>:</td>
                        <td id="id_password"><?= @$password?></td>
                    </tr>
                </table>  
            </center>
            <br>
        </div>
        <hr>
        <br>

        <div class="title">Scan this QR for download SWS Apps</div>
        <center><div id="downloadapp"></div></center>
        <!-- <a id="btn-Preview-Image" href="#">
            
        </a> -->
        <!-- <button class="btn">Ok</button> -->
    </div>
    <input type="hidden" id="qrcode_text" value="<?= $code?>">
    <input type="hidden" id="username_text" value="<?= $username?>">
    <input type="hidden" id="password_text" value="<?= $password?>">
    <script src="<?php echo base_url("assets/theme/plugins/jquery/jquery.min.js");?>"></script> 
    <script src="<?= base_url()?>assets/external/qrcode.min.js"></script>
    <script src="<?php echo base_url('assets/external/jspdf.min.js');?>"></script>
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment.js"></script>

    <script type="text/javascript">
        var form = $('#html-content-holder'),
        cache_width = form.width(),
        a4 = [595.28, 841.89];
        var getCanvas; 
        var link = "http://10.100.17.11/assets/download/sws-app.apk";
        var code = document.getElementById('qrcode_text').value;
        var qrcode = new QRCode("qrcode", {
                text: code,
                width: 128,
                height: 128,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
        });
        var downloadapp = new QRCode("downloadapp", {
                text: link,
                width: 128,
                height: 128,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
        });

        function createPDF() {
        var title = "qr_working_space_account"+"_"+moment().format("DDMMMMYYYY")+"_"+moment().format("DDMMMMYYYY")+"_"+moment().format("YYYYMMDDHHmmss");
        getCanvas2().then(function(canvas) {
            var
                img = canvas.toDataURL("image/png"),
                doc = new jsPDF({
                    unit: 'px',
                    format: 'a4'
                });
            doc.addImage(img, 'JPEG', 20, 20);
            doc.save(title+'.pdf');
            form.width(cache_width);

            window.close();
        });

    }
    function getCanvas2() {
        form.width((a4[0] * 1.33333) - 80).css('max-width', 'none');
        return html2canvas(form, {
            imageTimeout: 2000,
            removeContainer: true
        });
    }


        $(document).ready(function() {
             setInterval(function(){
                createPDF()
            },100)
        });
        
        
        

        
       
        
    </script>
</body>
</html>
