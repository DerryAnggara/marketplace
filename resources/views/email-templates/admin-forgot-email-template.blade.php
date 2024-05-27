<p>dear {{ $admin->name}}</p>
<p>
    kami ngirim code reset password {{ $admin->email}}.
    kamu melakukan reset dengan link di bawah:
    <br>
    <a href="{{ $actionLink}}" target="_blank" style="color: #fff;border-color:#22bc66;border-style:solid;border-width:5px 10px;
    background-color:#22bc66;display:inline-block;text-decoration:none;border-radius:3px;box-shadow:0 2px 3px rgba(0,0,0,0.16);
    -webkit-text-size-adjust:none;box-sizing:border-box;">Reset password</a>
    <br>
    <b>NB:</b>link hanya valid selama 15menit
    <br>
    jika kamu tidak melakukan resrt password biarkan saja email tersebutl
</p>