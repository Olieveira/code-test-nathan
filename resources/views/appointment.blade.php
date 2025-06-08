@extends('layouts.main')
@section('title', 'Rusky Vet - A saúde do seu cão em primeiro lugar')
@section('content')
<section class="py-6 border-bottom">
    <div class="container text-center">
        <h1>Consulta - {{$appointment->id}}</h1>

        <div class="row mt-4 justify-content-center">
            <div class="col-md-10 text-left">

                <div class="text-center mb-4">
                    <img src="{{ $appointment->Patient->image_path ? '/storage/' . $appointment->Patient->image_path : 'https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcSDJVdoqib2dry6LTBDWU_0WWvWON_zdAMn_w&usqp=CAU'}}" class="radius" height="140">
                </div>

                <table class="table">
                    <tbody>
                        <tr>
                            <th>Consulta</th>
                            <td>{{$appointment->id}}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{$appointment->status->status}}</td>
                        </tr>
                        <tr>
                            <th>Data e hora</th>
                            <td>{{ $appointment->scheduled_time ? $appointment->scheduled_time->format('d/m/Y - H:i') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nome do paciente</th>
                            <td>{{$appointment->Patient->name}}</td>
                        </tr>
                        <tr>
                            <th>Raça</th>
                            <td>{{ $appointment->Patient->breed }}</td>
                        </tr>
                        <tr>
                            <th>Idade</th>
                            <td>{{$appointment->Patient->getAge()}}</td>
                        </tr>
                        <tr>
                            <th>Dono</th>
                            <td>{{$appointment->Patient->User->name}}</td>
                        </tr>
                        <tr>
                            <th>Observações</th>
                            <td>
                                @if(auth()->user()->type === 'VET' && $appointment->status_id == 1)
                                <form action="{{ route('client.view-appointment', $appointment->id) }}" method="POST">
                                    @csrf
                                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $appointment->notes) }}</textarea>
                                    <button type="submit" class="btn btn-primary btn-sm mt-2">Salvar observação</button>
                                </form>
                                @else
                                {{ $appointment->notes ? $appointment->notes : 'Nenhuma observação!' }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Veterinário responsável</th>
                            <td>
                                {{$appointment->doctor? $appointment->doctor->name : '-' }}
                                <p>{{$appointment->doctor? $appointment->doctor->crmv : ''}}</p>
                            </td>
                        </tr>

                        @if($appointment->closed_at)
                        <tr>
                            <th>Concluído por</th>
                            <td>{{$appointment->closedBy? $appointment->closedBy->name : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Concluído em</th>
                            <td>{{$appointment->closed_at->format('d/m/Y - H:i')}}</td>
                        </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
