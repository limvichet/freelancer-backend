{{--!  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['title'] }}</title>
</head>
<body>
    <p>{{ $data['body'] }}</p>
    <a href="{{ $data['url'] }}">Please click on below link to reset your password</a>
    <p>Thank You.</p>
     <h1></h1>
</body>
</html>
--}}

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{{ $data['title'] }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 20px;">
  
  <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 6px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    
    <p style="font-size: 16px; color: #333333; margin-top: 0;"><strong>Dear {{ $data['email'] }},</strong> <small>{{ $data['datetime'] }}</small></p>
    
    <p style="font-size: 15px; color: #333333;">
      We received a password reset request for your {{ $data['application'] }} account. 
      To reset your password open the application and enter the following token:
    </p>
    
    <p style="font-size: 16px; font-weight: bold; margin-bottom: 8px;">Your password reset token is:</p>
    
    <p style="font-size: 22px; font-weight: bold; color: #000; margin-top: 0;">{{ $data['token'] }}</p>
    
    <p style="font-size: 16px; margin-bottom: 6px;">Please do not reply to this email. This code will expire in 30 minutes.</p>

    <p style="font-size: 15px; color: #333333;">Thank you for using <a href="{{ $data['domain'] }}"><strong>{{ $data['application'] }}</strong></a></p>
    
    <p style="font-size: 15px; color: #333333; margin-bottom: 0;">
      Regards,<br><small>{{ $data['application'] }}</small>
    </p>
  </div>

</body>
</html>