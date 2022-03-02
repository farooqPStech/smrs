<div class="container">
     <div class="row justify-content-center">
         <div class="col-md-8">
             <div class="card">
                 <div class="card-header"></div>
                   <div class="card-body">
                    @if (session('resent'))
                         <div class="alert alert-success" role="alert">
                            {{ __('A fresh mail has been sent to your email address.') }}
                        </div>
                    @endif

                    Subject : SMRS - Registration Successful<br><br>

                    Dear Sir/ Madam,<br><br>

                    Thank You, you have successfully registered with Spot Meter Reading System (SMRS). Your details as below.<br><br><br>

                     

                    UserID              :             {!! $username !!} <br><br>

                    Full Name           :             {!! $fullname !!} <br><br>

                    Email Address       :             {!! $email !!} <br><br>

                    Password            :             {!! $password !!} <br><br><br>

                     

                    Please login and change your temporary password immediately via below link <br><br><br>

                     

                    <a href="http://smrs.test">http://smrs.test</a> <br><br><br>

                     

                    (This is an automated respond, please do not reply to this email)<br><br><br><br>

                     

                     

                    Regards,<br><br>

                    SMRS Team.<br>

                </div>
            </div>
        </div>
    </div>
</div>