@extends('layouts.main')
@section('title', 'Rusky Vet - A saúde do seu cão em primeiro lugar')
@section('content')
<section class="py-6 border-bottom">
    <div class="container text-center">
        <h1>Agendar consulta</h1>

        <div class="row mt-6 justify-content-center">
            <div class="col-md-5 text-left">
                <form action="{{ route('client.create-appointment', $appointment->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="patient">Paciente</label>
                        <select name="patient" class="form-control @error('patient') is-invalid @enderror" id="patient">
                            <option value="">Selecione</option>
                            @foreach(auth()->User()->Patient()->where('name', '!=', null)->get() as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                            @endforeach
                        </select>
                        @error('patient')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="scheduled_at">Data da consulta</label>
                        <input type="text" name="scheduled_at" autocomplete="off" class="form-control @error('scheduled_at') is-invalid @enderror" id="scheduled_at" value="{{ old('scheduled_at', now()->format('d/m/Y')) }}">

                        @error('scheduled_at')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="time">Horário da consulta</label>
                        <select name="time" class="form-control @error('time') is-invalid @enderror" id="time">
                            <option value="">Selecione</option>
                            <option value="08:00" disabled>08:00</option>
                            <option value="09:00">09:00</option>
                            <option value="10:00">10:00</option>
                            <option value="11:00" disabled>11:00</option>
                            <option value="12:00">12:00</option>
                            <option value="13:00">13:00</option>
                            <option value="14:00">14:00</option>
                            <option value="15:00">15:00</option>
                            <option value="16:00">16:00</option>
                            <option value="17:00">17:00</option>
                        </select>
                        @error('time')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <p>Por favor, certifique-se de que poderá comparecer no horário marcado, pois não será possível desfazer o agendamento.</p>

                    <button type="submit" class="btn btn-primary btn-block mt-4">Agendar</button>

                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
    $(document).ready(() => {
        $('#date').datepicker({
            language: 'pt-BR',
            autoHide: true,
            filter: function(date, view) {
                if ((date.getDay() === 0 || date.getDay() === 6)) {
                    return false;
                }
            }
        });

        $('#date').datepicker('setStartDate', new Date());

        $('#date').change(() => {
            loadAppointmentTimes();
        });

        function loadAppointmentTimes() {
            const date = $('#date').val();
            // - TODO: Carregar os horários disponíveis via ajax de acordo com a data.
            console.log(date);
        }

        loadAppointmentTimes();
    });
</script>
@endpush
