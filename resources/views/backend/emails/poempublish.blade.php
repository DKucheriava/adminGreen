<!DOCTYPE html>
<html>
    <head>
    <meta http-equiv="content-type" content="text/html; charset=windows-1252">
    <title>Green Pheasants Admin Team</title>
        <style type="text/css">
            body{font-family: Helvetica,sans-Serif;color: #77798c;font-size: 14px;}
        </style>
    </head>
    <body>        
        <table style="padding: 0;" width="100%" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <td>
                        <table style="margin: 0px auto 0px; text-align: center; box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, 0.1); border-radius: 4px;background-color:#fff9ed; padding: 20px;" width="500px" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td>
                                        <table style="margin: 10px auto; text-align: center; box-shadow: 0px 0px 3px 0px rgba(0, 0, 0, 0.1); background-color: #fff; border-radius: 4px;" width="100%" cellspacing="0" cellpadding="0">
                                            <tbody>
                                              <!--   <tr>
                                                    <td>
                                                        <h1 style="font-weight:normal;margin:20px 0 0;color:#664b13;">Email Verification</h1>
                                                    </td>
                                                </tr>
                                                <br> -->
                                                <tr>
                                                    <td>
                                                       <!--  <h4 style="margin-bottom:0;color: #525252;"> Dear {{ $name }} </h4> -->
                                                      
                                                        <p style="padding: 9px 30px 20px; line-height:22px; font-size:14px; color: #505050; letter-spacing: 1px; margin: 0;text-align: left;">Dear {{ $name }} 
                                                            <!-- <br> Welcome to Green Pheasants! -->
                                                            <br><br>{{ $content }}<br><br>
                                                            {{$other}}<br>The Green Pheasants team</p>          
                                                        <br>
                                                        <img src="{{ url('admin/images/appbird.png') }}" alt="" height="70" style="float: left !important;">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="height:60px">
                                                    </td>
                                                </tr>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>