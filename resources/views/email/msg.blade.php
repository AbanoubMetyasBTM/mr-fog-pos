@extends("email.main_layout")
@section("subview")
    <h2 style="text-align: center;margin-top: 15px;">{{$header}}</h2>
    <div style="
        font-size: 19px;
        width: 80%;
        margin: 0 auto;
        text-align: left;
        line-height: 30px;"
    >
        {!! $body !!}
    </div>
@endsection
