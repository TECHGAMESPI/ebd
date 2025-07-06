<div class="row ">
    <div class="col">
        <div class="form-body">
            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" id="titulo" wire:model='fields.titulo' class="form-control" placeholder=""
                    name="">
                @error('fields.titulo')
                    <p class="error">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="form-group ">
                <label for="descricao">Descrição</label>
                <input type="text" id="descricao" wire:model='fields.descricao' class="form-control" placeholder=""
                    name="">
                @error('fields.descricao')
                    <p class="error">
                        {{ $message }}
                    </p>
                @enderror
            </div>
            <div class="form-group ">
                <label for="data_publicacao">Data da publicação</label>
                <input type="datetime-local" id="data_publicacao" class="form-control" wire:model='fields.publicar_em'
                    name="">
                @error('fields.publicar_em')
                    <p class="error">
                        {{ $message }}
                    </p>
                @enderror
            </div>
            <div class="form-group ">
                <label for="turma_id">Turma</label>
                <select id="turma_id" class="form-control" wire:model='fields.turma_id'>
                    <option value="">Selecione a turma</option>
                    @foreach($turmas as $turma)
                        <option value="{{ $turma->id }}">{{ $turma->nome_turma }}</option>
                    @endforeach
                </select>
                @error('fields.turma_id')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group ">
                <label for="ano">Ano</label>
                <select id="ano" class="form-control" wire:model='fields.ano'>
                    <option value="">Selecione o ano</option>
                    @foreach($anos as $ano)
                        <option value="{{ $ano }}">{{ $ano }}</option>
                    @endforeach
                </select>
                @error('fields.ano')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
            @can('is_admin')

                <div class="row ">
                    <div class="col-12 col-lg-2">
                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    @if ($fields['material_global']) checked @endif wire:click='changeGlobalPublish'
                                    id="flexSwitchCheckDefault">
                                <label for="flexSwitchCheckDefault" class="fs-5 text">Publicação
                                    global?</label>
                            </div>

                        </div>
                    </div>
                    <span class="small">Publicações globais serão visiveis para todas
                        as
                        igrejas.</span>
                </div>
            @endcan
        </div>
    </div>
</div>
