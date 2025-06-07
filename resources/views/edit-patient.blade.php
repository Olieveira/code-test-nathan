@extends('layouts.main')
@section('title', 'Rusky Vet - A saúde do seu cão em primeiro lugar')
@section('content')
<section class="py-6 border-bottom">
    <div class="container text-center">
        <h1>Cadastro de paciente canino</h1>

        <div class="row mt-6 justify-content-center">
            <div class="col-md-5 text-left">
                <form action="{{ route('client.edit-patient', $patient->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nome</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', $patient->name) }}">
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="gender">Sexo</label>
                        <select name="gender" class="form-control @error('gender') is-invalid @enderror" id="gender">
                            <option value="">Selecione</option>
                            <option @if(old('gender', $patient->gender) == 'M') selected @endif value="M">Macho</option>
                            <option @if(old('gender', $patient->gender) == 'F') selected @endif value="F">Fêmea</option>
                        </select>
                        @error('gender')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="breed">Raça</label>
                        <select name="breed" class="form-control @error('breed') is-invalid @enderror" id="breed">
                            <?php
                            $breeds = ['Afegão Hound', 'Affenpinscher', 'Airedale Terrier', 'Akita', 'American Staffordshire Terrier', 'Basenji', 'Basset Hound', 'Beagle', 'Beagle Harrier', 'Bearded Collie', 'Bedlington Terrier', 'Bichon Frisé', 'Bloodhound', 'Bobtail', 'Boiadeiro Australiano', 'Boiadeiro Bernês', 'Border Collie', 'Border Terrier', 'Borzoi', 'Boston Terrier', 'Boxer', 'Buldogue Francês', 'Buldogue Inglês', 'Bull Terrier', 'Bulmastife', 'Cairn Terrier', 'Cane Corso', 'Cão de Água Português', 'Cão de Crista Chinês', 'Cavalier King Charles Spaniel', 'Chesapeake Bay Retriever', 'Chihuahua', 'Chow Chow', 'Cocker Spaniel Americano', 'Cocker Spaniel Inglês', 'Collie', 'Coton de Tuléar', 'Dachshund', 'Dálmata', 'Dandie Dinmont Terrier', 'Dobermann', 'Dogo Argentino', 'Dogue Alemão', 'Fila Brasileiro', 'Fox Terrier (Pelo Duro e Pelo Liso)', 'Foxhound Inglês', 'Galgo Escocês', 'Galgo Irlandês', 'Golden Retriever', 'Grande Boiadeiro Suiço', 'Greyhound', 'Grifo da Bélgica', 'Husky Siberiano', 'Jack Russell Terrier', 'King Charles', 'Komondor', 'Labradoodle', 'Labrador Retriever', 'Lakeland Terrier', 'Leonberger', 'Lhasa Apso', 'Lulu da Pomerânia', 'Malamute do Alasca', 'Maltês', 'Mastife', 'Mastim Napolitano', 'Mastim Tibetano', 'Norfolk Terrier', 'Norwich Terrier', 'Papillon', 'Pastor Alemão', 'Pastor Australiano', 'Pinscher Miniatura', 'Poodle', 'Pug', 'Rottweiler', 'Sem Raça Definida (SRD)', 'ShihTzu', 'Silky Terrier', 'Skye Terrier', 'Staffordshire Bull Terrier', 'Terra Nova', 'Terrier Escocês', 'Tosa', 'Weimaraner', 'Welsh Corgi (Cardigan)', 'Welsh Corgi (Pembroke)', 'West Highland White Terrier', 'Whippet', 'Xoloitzcuintli', 'Yorkshire Terrier'];
                            ?>
                            <option value="">Selecione</option>
                            @foreach ($breeds as $breed)
                            <option @if(old('breed', $patient->breed) == $breed) selected @endif value="{{ $breed }}">{{ $breed }}</option>
                            @endforeach
                        </select>
                        @error('breed')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="birthdate">Data de nascimento</label>
                        <input type="text" name="birthdate" autocomplete="off" class="form-control @error('birthdate') is-invalid @enderror" id="birthdate" value="{{ old('birthdate', $patient->birthdate ? $patient->birthdate->format('d/m/Y') : '') }}">
                        @error('birthdate')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="image_path">Foto</label>
                        <input type="file" name="image_path" class="form-control @error('image_path') is-invalid @enderror" id="image_path" value="{{ old('image_path') }}">
                        @error('image_path')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div>
                        <p id="text_preview">Preview</p>
                        <img id="image_preview" class="img-paciente" src="{{ asset('storage/' . $patient->image_path) }}" alt="Foto do paciente">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block mt-4">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
    $(document).ready(() => {

        $('#birthdate').datepicker({
            language: 'pt-BR',
            autoHide: true,
            yearFirst: true
        });

        $('#birthdate').datepicker('setEndDate', new Date());

        // Listener para o preview
        const input = document.getElementById('image_path');
        const imgPreview = document.getElementById('image_preview');
        const textPreview = document.getElementById('text_preview');
        const currentImage = @json($patient -> image_path); // imagem atual

        if (input && imgPreview) {

            if (currentImage) {
                textPreview.style.display = 'block';
                imgPreview.src = '/storage/' + currentImage;
                imgPreview.style.display = 'block';
            } else {
                textPreview.style.display = 'none';
                imgPreview.src = '';
                imgPreview.style.display = 'none';
            }

            // atualiza o preview
            input.addEventListener('change', function(event) {
                const file = event.target.files[0]

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imgPreview.src = e.target.result;
                        imgPreview.style.display = 'block';
                        textPreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    imgPreview.src = '';
                    imgPreview.style.display = 'none';
                    textPreview.style.display = 'none';
                }
            })
        };
    });
</script>
@endpush
