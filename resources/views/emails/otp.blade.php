<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; padding: 40px 20px; margin: 0; }
        .container { max-width: 420px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 40px 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .logo { text-align: center; margin-bottom: 24px; }
        .logo span { font-size: 22px; font-weight: 700; color: #464d79; }
        h1 { font-size: 18px; color: #1a1a1a; text-align: center; margin: 0 0 8px; }
        .subtitle { font-size: 14px; color: #666; text-align: center; margin-bottom: 28px; }
        .otp-box { background: #f8f9fa; border: 2px dashed #464d79; border-radius: 8px; padding: 20px; text-align: center; margin: 24px 0; }
        .otp-code { font-size: 32px; font-weight: 700; letter-spacing: 6px; color: #464d79; font-family: monospace; }
        .expiry { font-size: 13px; color: #888; text-align: center; margin-top: 16px; }
        .footer { font-size: 12px; color: #999; text-align: center; margin-top: 32px; padding-top: 20px; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo"><span>Vaidyog</span></div>
        <h1>Your verification code</h1>
        <p class="subtitle">Use the code below to complete your verification.</p>
        <div class="otp-box">
            <div class="otp-code">{{ $otp }}</div>
        </div>
        <p class="expiry">This code is valid for <strong>10 minutes</strong>.</p>
        <div class="footer">
            If you did not request this, you can safely ignore this email.
        </div>
    </div>
</body>
</html>
