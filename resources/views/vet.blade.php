@extends('layouts.main')
@section('title', 'Rusky Vet - A saúde do seu cão em primeiro lugar')
@section('content')
<section class="py-6 border-bottom">
    <div class="container text-center">
        <h1>Olá {{ explode(' ', trim(auth()->User()->name))[0] }}!</h1>
    </div>
</section>

<section class="py-5 border-bottom">
    <div class="container text-center">
        <h3>Consultas agendadas</h3>
        <div class="row mt-5 justify-content-center">
            <div class="col-12 col-lg-10">
                <table class="table" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Nome do dono</th>
                            <th>Nome do cachorro</th>
                            <th>Data da consulta</th>
                            <th>Horário</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->status->status }}</td>
                            <td>{{ $appointment->patient->user->name }}</td>
                            <td>{{ $appointment->patient->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->scheduled_time)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->scheduled_time)->format('H:i') }}</td>
                            <td>
                                <a href="{{ route('client.view-appointment', $appointment->id) }}">Abrir</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</section>
@endsection
