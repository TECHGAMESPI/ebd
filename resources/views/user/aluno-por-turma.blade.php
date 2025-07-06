<x-app-layout>

    @section('cabecalho')
        Aluno por Turma
    @endsection

    @include('components.flash-message')
    <div class="table-responsive">
        <table class="table" id="datatablesSimple">
            <thead>
                <tr class="">
                    <th scope="col">Aluno</th>
                    <th scope="col">Turma</th>
                    @if (Auth::user()->perfil_id == App\Models\Perfil::SUPERINTENDENTE ||
                            Auth::user()->perfil_id == App\Models\Perfil::ADMINISTRADOR)
                        <th scope="col">Ações</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($alunosPorTurma as $aluno)
                    <tr>
                        <td>{{ $aluno->aluno->name }}</td>
                        <td>{{ $aluno->turma->nome_turma }}</td>
                        @if (Auth::user()->perfil_id == App\Models\Perfil::SUPERINTENDENTE ||
                                Auth::user()->perfil_id == App\Models\Perfil::ADMINISTRADOR)
                            <td>
                                <form action="{{ route('excluir-aluno') }}" method="post" class="d-inline-block desassociar-form">
                                    @csrf
                                    <input type="hidden" name="aluno_id" value="{{ $aluno->user_id }}">
                                    <input type="hidden" name="turma_id" value="{{ $aluno->turma_id }}">
                                    <button type="button"
                                            class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1 desassociar-btn"
                                            title="Remover aluno desta turma (não apaga o cadastro)"
                                            aria-label="Desassociar aluno da turma">
                                        <i class="fa fa-user-minus"></i>
                                        <span class="d-none d-md-inline">Desassociar</span>
                                    </button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if ($loop->last)
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
        let formParaDesassociar = null;
        document.querySelectorAll('.desassociar-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                formParaDesassociar = this.closest('form');
                const modal = new bootstrap.Modal(document.getElementById('modalDesassociar'));
                modal.show();
            });
        });
        document.getElementById('confirmarDesassociar').onclick = function() {
            if (formParaDesassociar) formParaDesassociar.submit();
        };
    </script>
    @endif
</x-app-layout>
