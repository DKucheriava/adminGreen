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
                        <table style="margin: 0px auto 0px; text-align: center; box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, 0.1); border-radius: 4px; padding: 20px;" width="500px" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td>
                                        <table style="margin: 10px auto; text-align: center; box-shadow: 0px 0px 3px 0px rgba(0, 0, 0, 0.1); border-radius: 4px;" width="100%" cellspacing="0" cellpadding="0">
                                            <tbody>
                                               <!--  <tr>
                                                    <td>
                                                        <h1 style="font-weight:normal;margin:20px 0 0;color:#664b13;">Recommened poem for you</h1>
                                                    </td>
                                                </tr>
                                                <br> -->
                                                <tr>
                                                    <td>
                                                        <!-- <h4 style="text-align:center; margin-bottom:0;color: #525252;"> Dear {{ ucfirst(@$name)}} </h4> -->

                                                       <!--  <p style="padding: 9px 30px 20px !important; line-height:22px !important; font-size:14px !important; color: #505050 !important; letter-spacing: 1px !important; margin: 0 !important;text-align: left !important;">
                                                            Dear {{$name}}, here is your recommended poem for today:<br><br>{{$poemtitle}}<br><span>By</span> {{$poemauthor}}, {{$poempublication}}<br><div style="text-align: left !important;">{!!$poemDetail!!}</div><br><br>
                                                            <p style="padding: 9px 30px 20px !important; line-height:22px !important; font-size:14px !important; color: #505050 !important; letter-spacing: 1px !important; margin: 0 !important;text-align: left !important;">Have a nice day,<br>
                                                            The Green Pheasants team</p>
                                                            <br><img src="{{ url('admin/images/appbird.png') }}" alt="" height="70" style="float: left !important;" > </p> -->
                                                            <p style="line-height:22px !important; font-size:14px !important; color: #505050 !important; letter-spacing: 1px !important; margin: 0 !important;text-align: left !important;">{{$poemtitle}}<br><span>By</span> {{$poemauthor}}, {{$poempublication}}<br><br><div style="text-align: left !important;">{!!$poemDetail!!}</div><br><br><div><img src="{{ url('admin/images/appbird.png') }}" alt="" height="70" style="float: left !important;" >
                                                                <p style="width: fit-content !important;">Green Pheasants</p></div></p>
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
                        <p><a href="https://www.greenpheasants.com/#/recommendedpoems">Click here.</a> to change the frequency of your poem recommendations.</p>
                        <p><a href="https://www.greenpheasants.com/#/unsubscribemail/{{$token}}/{{$user_id}}">Click here.</a> to unsubscribe.</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>