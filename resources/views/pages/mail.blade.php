<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style type="text/css">
  .ReadMsgBody {
    width: 100%;
  }
  .ExternalClass {
    width: 100%;
  }    
  .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
    line-height: 100%;
  }    
  p {
    margin: 1em 0;
  }
  body, table, td, a {
    -webkit-text-size-adjust: 100%;
    -ms-text-size-adjust: 100%;
  }
  table, td {
    mso-table-lspace: 0pt;
    mso-table-rspace: 0pt;
  }
  img {
    -ms-interpolation-mode: bicubic;
  }
  @-ms-viewport {
    width: device-width;
  }
  body {
    margin: 0;
    padding: 0;
  }
  img {
    border: 0;
    height: auto;
    line-height: 100%;
    outline: none;
    text-decoration: none;
  }
  table, td {
    border-collapse: collapse !important;
  }
  body {
    height: 100% !important;
    margin: 0;
    padding: 0;
    width: 100% !important;
  }
  .btn-minuta {
    -webkit-border-radius: 0;
    -moz-border-radius: 0;
    border-radius: 0px;
    font-family: Arial;
    color: #ffffff;
    font-size: 20px;
    background: #3498db;
    padding: 10px 20px 10px 20px;
    text-decoration: none;
    width: 100%;
  }

  .btn-minuta:hover {
    background: #3cb0fd;
    text-decoration: none;
  }
</style>
<style type="text/css">
  @media only screen and (max-width: 480px) {
    .container {
      width: 320px !important;
      min-width: 100%!important;
    }
    .mobile-hidden {
      display: none !important;
    }
    .mobileonly  {
      display: block !important;
      width: 100% !important;
      height: auto !important;
      padding: 0;
      max-height: inherit !important;
      overflow: visible !important;
    }
    .mobile_stats {
      display: block !important;
      width: auto !important;
      height: auto !important;
      padding: 0;
      max-height: inherit !important;
      overflow: visible !important;
    }
    .paddingnone {
      padding:0px !important;
    }
    #houdini {
      display: none !important;
    }
    .footer_padding] {
      padding: 20px !important;
    } 
    .center_img {
      width: 100% !important;
      padding: 0 !important;
    }
    .body_padding {
      padding:30px 20px 20px !important;
    }
    .font22 {
      font-size:22px;
      line-height:27px;
    }
    .font14 {
      font-size:14px !important;
      line-height:19px !important;
    }
    .hero_img {
      width: 100% !important;
      height: auto !important;
    }
  }
</style>
</head>
<body>
<style type="text/css">
  div.preheader { display: none !important; }
</style>
  <div class="preheader" style="font-size: 1px; display: none !important;"></div>
  <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ebebeb">
    <tr>
      <td align="center" valign="top" width="100%" bgcolor="#ebebeb">
        <table border="0" class="container" cellpadding="0" cellspacing="0" width="550" bgcolor="#FFFFFF">
          <tr>
            <td align="center" valign="top" width="100%">
              <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                  <td  align="left" valign="top" width="100%">
                    <div align="center">
                      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" class="container">
                        <tr>
                          <td height="10"></td>
                        </tr>
                        <tr>
                          <td valign="bottom" style="padding-left:35px;" class="center_img" align="center" width="200"><img src="http://186.1.15.167:8448/gumanet/public/images/p20.png" alt="" width="200" height="auto" border="0" style="display:block;"/></td>
                          <td width="145" class="mobile-hidden"></td>
                          <td align="right" style="font: normal 13px Arial, Helvetica, sans-serif; color:gray; line-height:15px; padding-right:35px;" class="mobile-hidden" width="290"><em>GUMANET</em></td>
                        </tr>
                        <tr>
                          <td height="10"></td>
                        </tr>
                      </table>
                      <hr style="width:90%;">
                    </div>
                  </td>
                </tr>
                <tr>
                  <td align="center" style="border-bottom:solid 6px #000000;padding:30px 50px 20px;" class="body_padding">
                    <div align="center">
                      <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                          <td align="center">
                            <img src="http://186.1.15.167:8448/gumanet/public/images/success.png" alt="" width="100" height="auto" border="0"/>
                          </td>
                        </tr>
                        <tr>
                          <td align="center" style="font:25px Sans-Serif, sans-serif;font-weight: bold; line-height:36px; color:#000000; padding:20px 0px 20px;" class="font22">Nueva Minuta Corporativa:
                          </td>
                        </tr>
                        <tr>
                          <td style="font:13px normal Helvetica, sans-serif; line-height:18px; color:#000000; padding: 0px 0px 20px 0px;" class="font14">
                            Le informamos que el usuario {{$name}} ha creado una nueva Minuta <br>
                          </td>
                        </tr>
                        <tr>
                          <td style="font:13px normal Helvetica, sans-serif; line-height:18px; color:#000000; padding: 0px 0px 20px 0px;" class="font14">
                            <strong>Titulo: {{$title}}</strong><br>
                            <strong>Elaborado por: {{$name}}</strong><br>
                            <strong>Fecha y hora: {{$date}}</strong><br>
                          </td>
                        </tr>
                        <tr>
                          <td style="padding:20px 20px;">
                            <div style="text-align: center;">
                              <a href="{{$url}}" class="btn-minuta" style="width: 500px!important">Ver Minuta</a>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td align="left" style="font:13px normal Helvetica, sans-serif; line-height:18px; color:#000000;" class="font14">
                            <em>Saludos Cordiales,<br>Informatica UNIMARK S.A.<br></em>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>