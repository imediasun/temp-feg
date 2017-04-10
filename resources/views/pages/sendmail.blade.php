<section class="contact" id="contact">
    <div class="container">
        <div>
            <form action="{{route('sendmail')}}" method="post">

                {{csrf_field()}}
                <input type="hidden" name="token" value="{{$token}}">
                Email To : <input required value="{{old('to')}}" type="email" class="form-control" name="to">
                Message : <textarea required class="form-control" value="{{old('message')}}" name="message"></textarea>
                <button type="submit" class="button">Submit</button>
            </form>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>

<script>
    $.ajax({
            url:'https://accounts.google.com/o/oauth2/token',
            method:'POST',

        data:{
                grant_type:'authorization_code',
                code:'{{$token}}',
                client_id:'610459224217-5m5sg77d2fo8ujei3qkd9fhi6frqgs30.apps.googleusercontent.com',
                redirect_uri:'http://dev.fegllc.com/gmailcallback',
                client_secret:'i-jFM0NyMNrs1TeTBxoj0MBi'
        }
        })
    .done(function (data) {
        console.log(data);
    })
    .fail(function (data) {
        console.log(data);
    })
</script>














