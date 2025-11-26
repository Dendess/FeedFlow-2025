@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $survey->title }}</h1>
        <p>{{ $survey->description }}</p>

        <form method="POST" action="#">
            @csrf
            <p>Les questions du sondage viendront ici…</p>

            <button class="btn btn-primary">Envoyer</button>
        </form>
    </div>
@endsection
