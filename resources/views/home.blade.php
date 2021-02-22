@extends('layouts.app')

@section('content')
<Form-Component
    :data={{json_encode($data)}}
/>

@endsection
