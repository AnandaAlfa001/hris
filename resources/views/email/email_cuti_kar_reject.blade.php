<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="content-type">
    <meta content="width=device-width, initial-scale=1.0;" name="viewport">
    <meta content="telephone=no" name="format-detection">
    <!-- Responsive Mobile-First Email Template by Konstantin Savchenko, 2015.
  https://github.com/konsav/email-templates/  -->

    <style>
      /* Reset styles */ 
      body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important;}
      body, table, td, div, p, a { -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 130%; }
      table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse !important; border-spacing: 0; }
      img { border: 0; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
      #outlook a { padding: 0; }
      .ReadMsgBody { width: 100%; } .ExternalClass { width: 100%; }
      .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 130%; }

      /* Rounded corners for advanced mail clients only */ 
      @media all and (min-width: 560px) {
        .container { border-radius: 1px; -webkit-border-radius: 1px; -moz-border-radius: 1px; -khtml-border-radius: 1px;}
      }

      /* Set color for auto links (addresses, dates, etc.) */ 
      a, a:hover {
        color: #3c8dbc;
      }
      .footer a, .footer a:hover {
        color: #999999;
      }

      .judul {
        color: rgba(0,0,0,0.6);
        text-shadow: 2px 8px 6px rgba(0,0,0,0.2), 0px -5px 35px rgba(255,255,255,0.3);
      }

    </style><!-- MESSAGE SUBJECT -->
    <title>HRIS 2.0</title>
</head>

<!-- BODY -->
<!-- Set message background color (twice) and text color (twice) -->
<body bgcolor="#F0F0F0" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 130%; background-color: #F0F0F0; color: #202020;" text="#000000">
    <!-- SECTION / BACKGROUND -->
    <!-- Set message background color one again -->
    <table align="center" border="0" cellpadding="0" cellspacing="0" class="background" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;"
    width="100%">
        <tr>
            <td align="center" bgcolor="#F0F0F0" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;"
            valign="top">
                <!-- WRAPPER / CONTEINER -->
                <!-- Set conteiner background color -->
                <table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" class="container" style="border-collapse: collapse; border-spacing: 0; border: 1px solid #dddddd; border-top: 1px transparent; padding: 0; width: inherit; max-width: 560px;"
                width="560">
                    <!-- HEADER -->
                    <!-- Set text color and font family ("sans-serif" or "Georgia, serif") -->
                    <tr>
                        <td align="center" class="header" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 15px; padding-right: 15px; width: 87.5%; font-size: 24px; font-weight: bold; line-height: 100%; padding-top: 5px; color: #202020; font-family: sans-serif;"valign="top">
                            <h1 class="judul">HRIS EDII</h1>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" class="hero" style="background-color: #3c8dbc;border-collapse: collapse; border-spacing: 0; margin: 0; padding: 10px 0;" valign="top">
                            <img src="http://trade2gov.com/front/images/t2g-mail.png" width="200px">
                            <h2 style="color:#fff;font-family:Helvetica;">Request Cuti - Cuti Telah Ditolak</h2>
                        </td>
                    </tr>

                    <?php  
                        $url = "/historycuti";
                    ?>

                    <tr>
                        <td align="center" class="paragraph" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 15px; padding-right: 15px; width: 87.5%; font-size: 17px; font-weight: 400; line-height: 160%; padding-top: 15px; color: #202020; font-family: sans-serif;"
                        valign="top">
                        <p style="margin: 30px 0;">
                        <h3>Dear, {{ $nama_karyawan }}</h3>
                        </p>
                        <p style="margin: 30px 0;">

                        <b>Berikut detail cuti Anda yang telah ditolak:</b><br>
                        Nama : {{$nik_karyawan}} - {{$nama_karyawan}} <br>
                        Tanggal : {{$tanggal_awal}} s/d {{$tanggal_selesai}}<br>
                        Alamat Cuti : {{$alamat_cuti}} <br>
                        Keterangan : {{$keterangan}} <br>
                        Alasan Reject : {{$alasan_reject}}
                        </p>
                        </td>
                    </tr><!-- BUTTON -->
                    <td style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;padding-top: 25px;padding-bottom: 5px;" class="button" align="center" valign="top">
                        <a href="{{ url($url) }}" target="_blank">
                            <table style="max-width: 240px; min-width: 120px; border-collapse: collapse; border-spacing: 0; padding: 0;" align="center" border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td style="padding: 12px 24px; margin: 0; border-collapse: collapse; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;" align="center" bgcolor="#3c8dbc" valign="middle">
                                        <a target="_blank" style="font-weight:bold;color: #FFFFFF; font-family: sans-serif; font-size: 17px; font-weight: 400; line-height: 120%;" href="{{ url($url) }}">
                                        Check Data
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </a>
                    </td>
                    <tr>
                        <td align="center" class="line" style=
                        "border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 15px; padding-right: 15px; width: 87.5%; padding-top: 15px;"
                        valign="top">
                            <hr align="center" noshade size="1" style="border-color:#dddddd;margin: 0; padding: 0;" width="100%">
                        </td>
                    </tr><!-- LIST -->
                    <tr >
                        <td align="center" class="list-item" style="font-family: sans-serif;border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 15px; padding-right: 15px; color: #555555" valign="top">
                            <h4 style="font-weight:bold;">PT. Electronic Data Interchange Indonesia</h4>
                                <p style="line-height:120%; font-size:13px">
                                Wisma SMR Lt. 1, 3 &amp; 10<br>
                                Jl. Yos Sudarso, Kav. 89 Sunter - Jakarta Utara 14350<br>
                                Phone: +6221 650 5829 | Fax: +6221 650 5987<br>
                                </address>
                        </td>
                    </tr>
                    <!-- PARAGRAPH -->
                    <!-- Set text color and font family ("sans-serif" or "Georgia, serif"). Duplicate all text styles in links, including line-height -->
                    <!-- End of WRAPPER -->
                </table><!-- WRAPPER -->
                <!-- Set wrapper width (twice) -->
                <table align="center" border="0" cellpadding="0" cellspacing=
                "0" class="wrapper" style=
                "border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit; max-width: 560px;"
                width="560">
                    <!-- SOCIAL NETWORKS -->
                    <!-- Image text color should be opposite to background color. Set your url, image src, alt and title. Alt text should fit the image size. Real image size should be x2 -->
                    <tr>
                        <td align="center" class="social-icons" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 15px; padding-right: 15px; width: 87.5%; padding-top: 15px;"
                        valign="top">
                            <img src="http://trade2gov.com/front/images/edi-logo.png" width="100px">
                        </td>
                    </tr><!-- FOOTER -->
                    <!-- Set text color and font family ("sans-serif" or "Georgia, serif"). Duplicate all text styles in links, including line-height -->
                    <tr>
                        <td align="center" class="footer" style=
                        "border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 15px; padding-right: 15px; width: 87.5%; font-size: 13px; font-weight: 400; line-height: 150%; padding-top: 5px; padding-bottom: 20px; color: #999999; font-family: sans-serif;"
                        valign="top">
                            Copyright © 2017. PT. Electronic Data Interchange Indonesia.
                        </td>
                    </tr><!-- End of WRAPPER -->
                </table><!-- End of SECTION / BACKGROUND -->
            </td>
        </tr>
    </table>
</body>
</html>