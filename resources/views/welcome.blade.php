<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>موتور جستجوی سایت ویکی شیعه</title>
        <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css">

    </head>
    <body style="background-image: url('/1.jpg'); background-size: 400px;" class="rtl">
    <div class="container">
        <div class="row justify-content-center" style="height: 100vh;">
            <div class="card border-primary p-3" style="width: 100%; height: auto; top:150px;">
                <p class="text-center text-lg">موتور جستجوی سایت ویکی شیعه</p>
                <form method="get" action="{{route('search')}}">
                    <div class="row justify-content-center">
                        <div class="form-group col-12">
                            <input type="text" name="text" id="text" class="form-control" placeholder="عبارتی وارد کنید" value="@isset($text){{$text}}@endisset" onkeyup="suggest(this)">
                        </div>
                        <div class="col-12" style="">
                            <ul id="suggestions" class="list-group" style="padding: 0px;">
                            </ul>
                        </div>
                        <div class="col-3">
                            <input type="submit" class="btn btn-block btn-primary mt-2" value="جستجو">
                        </div>
                    </div>
                </form>
                @isset($articles)
                <div class="row justify-content-end">
                    <div class="col-2 p-0 text-center">
                        تعداد نتایج: {{$total}}
                    </div>
                </div>
                <hr/>
                <div class="results">
                        <ul class="list-group list-group-flush p-0">
                        @foreach($articles as $article)
                            <li class="list-group-item">
                                <div class="row justify-content-between">
                                    <h4 class="card-title col-4"><a class="card-link" href="http://fa.wikishia.net{{$article->link}}" target="_blank">{{$article->title}}</a></h4>
                                    <p class="col-3 text-right text-primary p-0">تعداد بازدید: <span>{{$article->viewCount}}</span></p>
                                </div>
                            <p>{!! $article->text !!}</p>
                            </li>
                            @endforeach
                        </ul>
                </div>
                    {{$articles->appends(["text"=>$text])->links()}}
                @endisset
            </div>
        </div>
    </div>
        <script type="text/javascript" src="{{asset('js/app.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#suggestions').hide();
        })
        function suggest(t) {
            if(t.value === ""){
                $('#suggestions').hide();
                return;
            }
            axios.post('http://localhost:8000/api/suggest/', {
                "text":t.value
            }).then(function (response) {
                let i=0;
                let text = "";
                let data = response.data;
                for(i;i<data.length;i++){
                    text += `<li class='list-group-item p-2' style="cursor:pointer;" onclick="replace(this)">${response.data[i]}</li>`
                }
                $("#suggestions").html(text).fadeIn();
            }).catch(function (error) {
                console.log(error);
            })
        }
        function replace(t) {
            $('#text').val($(t).text());
            $('#suggestions').fadeOut();
        }
    </script>
    </body>
</html>
