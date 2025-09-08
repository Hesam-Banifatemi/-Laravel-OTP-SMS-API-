# How This API WORKS
<p>In this API The goal is to, after receiving the necessary credentials from the user like
<strong>mobile number</strong>, and <strong>vote</strong>, a <strong>sms</strong> will be sent containing the <strong>otp token</strong> using a <strong>thrid party api</strong>
which is <strong>sms.ir</strong> api,and whose job is to sending sms to the user.</p>
<h4>so we have three end points</h4>
<p>
1) `/api/generate`<br>
whose job is the <em>receive</em> the necessary credentials and <em>validate</em> them
and finally sends and sms with otp token
</p>
<p>
2) `/api/verify`<br>
whose job is to <em>verify the user and datas from user based on mobile number
OTP token</em> and after that the user votes will set completed in DB
</p>
<p>
3) `/api/vote`<br>
whose job is to show the user's vote only to the user base on the sanctum token
the user have.
</p>
