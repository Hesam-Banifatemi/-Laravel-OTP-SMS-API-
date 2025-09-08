# How This API WORKS
<p>In this API The goal is to, after receiving the necessary credentials from the user like
**mobile number**, and **vote**, a **sms** will be sent containing the **otp token** using a **thrid party api**
which is **sms.ir** api,and whose job is to sending sms to the user.</p>
<h4>so we have three end points</h4>
<p>
1) `/api/generate`<br>
whose job is the *receive the necessary credentials and validate them
and finally sends and sms with otp token*
2) `/api/verify`<br>
whose job is to *verify the user and datas from user based on mobile number
OTP token* and after that the user votes will set completed in DB
3) `/api/vote`<br>
whose job is to show the user's vote only to the user base on the sanctum token
the user have.
</p>
