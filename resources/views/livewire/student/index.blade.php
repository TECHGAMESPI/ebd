<div>
    @section('cabecalho')
        Alunos por Turma
    @endsection
    <div class="row breadcrumbs-top d-inline-block">
        <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('principal') }}">Home</a>
                </li>
                <li class="breadcrumb-item active"><a href="#">Alunos por turma</a>
                </li>
            </ol>
        </div>
    </div>
    <section class="row d-md-flex justify-content-between align-items-center">

        <div class="col-12 col-sm-6 mt-2 mt-md-0">
            <input class="form-control" type="text" placeholder="Filtre" wire:model.live='search'>
        </div>
        <div class="col-12 col-md-6 text-right">
            <label for='perpage' class="ps-3">Registros por página</label>
            <select id='perpage' wire:model.live="perpage" class="form-select my-3">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
            </select>
        </div>
    </section>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr class="">
                            <th scope="col">Aluno</th>
                            <th scope="col">Turma</th>
                            @can('admin_superintendente')
                                <th scope="col">Ações</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($alunos as $aluno)
                            <tr>
                                <td>{{ $aluno->name }}</td>
                                <td>{{ $aluno->nome_turma }}</td>
                                @can('admin_superintendente')
                                    <td>
                                        <button type="button"
                                            class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1 desassociar-btn"
                                            title="Remover aluno desta turma (não apaga o cadastro)"
                                            aria-label="Desassociar aluno da turma"
                                            data-user="{{ $aluno->user_id }}" data-turma="{{ $aluno->turma_id }}">
                                            <i class="fa fa-user-minus"></i>
                                            <span class="d-none d-md-inline">Desassociar</span>
                                        </button>
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row d-flex justify-content-center">
        {{ $alunos->links() }}
    </div>

    <!-- Modal de confirmação -->
    <div class="modal fade" id="modalDesassociar" tabindex="-1" aria-labelledby="modalDesassociarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning-subtle">
                    <h5 class="modal-title" id="modalDesassociarLabel"><i class="fa fa-user-minus text-warning me-2"></i>Desassociar aluno da turma</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja <b>remover este aluno desta turma</b>?<br>
                    <small class="text-muted">O cadastro do aluno não será apagado, apenas o vínculo com esta turma.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-warning" id="confirmarDesassociar">Desassociar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        let userIdDesassociar = null;
        let turmaIdDesassociar = null;
        document.querySelectorAll('.desassociar-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                userIdDesassociar = this.getAttribute('data-user');
                turmaIdDesassociar = this.getAttribute('data-turma');
                const modal = new bootstrap.Modal(document.getElementById('modalDesassociar'));
                modal.show();
            });
        });
        document.getElementById('confirmarDesassociar').onclick = function() {
            if (userIdDesassociar && turmaIdDesassociar) {
                @this.delete(userIdDesassociar, turmaIdDesassociar);
            }
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalDesassociar'));
            if (modal) modal.hide();
        };
    </script>

</div>
