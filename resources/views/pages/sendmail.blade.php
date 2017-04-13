<section class="contact" id="contact">
    <div class="container">
        <div>
                {{csrf_field()}}
                <input type="hidden" id="token" name="token" value="{{$token2}}">
                Email To : <input id="to" required value="{{old('to')}}" type="email" class="form-control" name="to">
                Message : <textarea id="message" required class="form-control" value="{{old('message')}}" name="message"></textarea>
                <button id="submit" type="submit" class="button">Submit</button>

        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>

<script>
    $('#submit').click(function (e) {
        e.preventDefault();

        $.ajax({
            url:'{{route('sendmail')}}',
            method:'POST',
            data:{
                to:$('#to').val(),
                token:$('#token').val(),
                message:$('#message').val()
            }
        })
            .done(function (data) {
                console.log(data);
            })
            .fail(function (data) {
                console.log(data);
            })
    });
    $.ajax({
            url:'https://www.googleapis.com/oauth2/v4/token',
            method:'POST',

        data:{
                grant_type:'authorization_code',
                code:'{{$token}}',
                client_id:'610459224217-5m5sg77d2fo8ujei3qkd9fhi6frqgs30.apps.googleusercontent.com',
                redirect_uri:'http://localhost:8000/gmailcallback',
                client_secret:'i-jFM0NyMNrs1TeTBxoj0MBi'
        }
        })
    .done(function (data) {
        $('#token').val(data.access_token);
        console.log(data);
    })
    .fail(function (data) {
        console.log(data);
    });

</script>














