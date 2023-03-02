{{--@extends('layouts.app')--}}
{{--@section('content')--}}
    <label for="code">Copy and paste this token to GET /callback method</label><br>
    <textarea type="text" id="code" cols="100" rows="10">{{ request('code') }}</textarea>

{{--@endsection--}}
