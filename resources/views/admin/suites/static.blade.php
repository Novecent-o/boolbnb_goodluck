{{-- Layouts app --}}
@extends('layouts.app')

{{-- Yeld Main Content --}}
@section("content")
  <h1>Statistiche appartamento "{{ $suite->title }}"</h1>
  <div class="container-fluid">
    <div class="row">
      <div class="col-6">
        <h3>Visualizzazioni totali</h3>
        <div>
          <h1>{{$n_vis_totali}}</h1>
        </div>
        <div>
          <canvas id="line_visual" width="250" height="100" aria-label="Hello ARIA World" role="img"></canvas>
        </div>
        <div>
          <canvas id="bar_visual" width="250" height="100" aria-label="Hello ARIA World" role="img"></canvas>
        </div>
      </div>
      <div class="col-6">
        <h3>Messaggi ricevuti</h3>
        <div>
          <h1>{{$n_mess_totali}}</h1>
        </div>
        <div>
          <canvas id="line_message" width="250" height="100" aria-label="Hello ARIA World" role="img"></canvas>
        </div>
        <div>
          <canvas id="bar_message" width="250" height="100" aria-label="Hello ARIA World" role="img"></canvas>
        </div>
      </div>

    </div>
  </div>
  <div>
    <a href="{{ route("suites.index")}}"> Torna a Index</a>
  </div>

@endsection
