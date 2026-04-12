<div class="sidebar">
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Dashboard -->
            <li class="nav-item">
                @if (Auth::guard('web')->check())
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('home', 'dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Painel Inicial</p>
                    </a>
                @elseif (Auth::guard('professor')->check())
                    <a href="{{ route('professor.dashboard') }}" class="nav-link {{ request()->is('professor/dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Painel Inicial</p>
                    </a>
                @endif
            </li>

            @if (Auth::guard('web')->check())
                <!-- Professores -->
                <li class="nav-item {{ request()->is('professores*') ? 'menu-open' : '' }}">
                    <a href="{{ route('Listar-Professores') }}" class="nav-link {{ request()->is('professores*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>Professores</p>
                    </a>
                </li>

                <!-- Alunos -->
                <li class="nav-item {{ request()->is('alunos*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('alunos*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>Alunos <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('Listar-Alunos') }}" class="nav-link {{ request()->is('alunos') || request()->is('alunos/search*') || request()->is('alunos/create') || request()->is('alunos/edit*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Listagem de Alunos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('Alunos-Biometria') }}" class="nav-link {{ request()->is('alunos/biometria') ? 'active' : '' }}">
                                <i class="fas fa-camera nav-icon"></i>
                                <p>Biometria Facial</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Responsáveis -->
                <!-- <li class="nav-item {{ request()->is('responsaveis*') ? 'menu-open' : '' }}">
                    <a href="{{ route('Listar-Responsaveis') }}" class="nav-link {{ request()->is('responsaveis*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-friends"></i>
                        <p>Responsáveis</p>
                    </a>
                </li> -->

                <!-- Turmas -->
                <li class="nav-item {{ request()->is('turmas*') ? 'menu-open' : '' }}">
                    <a href="{{ route('Listar-Turmas') }}" class="nav-link {{ request()->is('turmas*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Turmas</p>
                    </a>
                </li>

                <!-- Frequências -->
                <li class="nav-item {{ request()->is('frequencias*') ? 'menu-open' : '' }}">
                    <a href="{{ route('Listar-Frequencias') }}" class="nav-link {{ request()->is('frequencias*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-check-square"></i>
                        <p>Frequências</p>
                    </a>
                </li>
                <!-- Conteúdo Ministrado -->
                <li class="nav-item {{ request()->is('conteudos*') ? 'menu-open' : '' }}">
                    <a href="{{ route('Listar-Conteudos') }}" class="nav-link {{ request()->is('conteudos*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>Conteúdo Ministrado</p>
                    </a>
                </li>


                <!-- Matrículas -->
                <li class="nav-item {{ request()->is('matriculas*') ? 'menu-open' : '' }}">
                    <a href="{{ route('Listar-Matriculas') }}" class="nav-link {{ request()->is('matriculas*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Matrículas</p>
                    </a>
                </li>

                <!-- Notas -->
                <!-- <li class="nav-item {{ request()->is('notas*') ? 'menu-open' : '' }}">
                    <a href="{{ route('Listar-Notas') }}" class="nav-link {{ request()->is('notas*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-graduation-cap"></i>
                        <p>Notas</p>
                    </a>
                </li> -->

                <!-- Relatórios -->
                <li class="nav-item {{ request()->is('relatorios*') ? 'menu-open' : '' }}">
                    <a href="{{ route('Listar-Relatorios') }}" class="nav-link {{ request()->is('relatorios*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Relatórios</p>
                    </a>
                </li>

                <!-- Calendários -->
                <li class="nav-item {{ request()->is('calendarios*') ? 'menu-open' : '' }}">
                    <a href="{{ route('Listar-Calendarios') }}" class="nav-link {{ request()->is('calendarios*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>Calendários</p>
                    </a>
                </li>

                <!-- Dias Letivos -->
                <li class="nav-item {{ request()->is('letivos*') ? 'menu-open' : '' }}">
                    <a href="{{ route('Listar-Letivos') }}" class="nav-link {{ request()->is('letivos*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-day"></i>
                        <p>Dias Letivos</p>
                    </a>
                </li>
               

                <li class="nav-item {{ request()->is('tipo-avaliacoes*') ? 'menu-open' : '' }}">
                    <a href="{{ route('Listar-Tipo-Avaliacoes') }}" class="nav-link {{ request()->is('tipo-avaliacoes*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Tipos de Avaliações</p>
                    </a>
                </li>

                <!-- Configurações -->
                <li class="nav-item {{ request()->is('cursos*', 'niveis*', 'tipo-avaliacoes*', 'turnos*', 'bairros*', 'cidades*', 'estados*', 'paises*', 'papeis*', 'permissoes*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('cursos*', 'niveis*', 'tipo-avaliacoes*', 'turnos*', 'bairros*', 'cidades*', 'estados*', 'paises*', 'papeis*', 'permissoes*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Configurações<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('Listar-Cursos') }}" class="nav-link {{ request()->is('cursos*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cursos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('Listar-Niveis') }}" class="nav-link {{ request()->is('niveis*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Níveis</p>
                            </a>
                        </li>
                       
                        <li class="nav-item">
                            <a href="{{ route('Listar-Turnos') }}" class="nav-link {{ request()->is('turnos*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Turnos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('Listar-Bairros') }}" class="nav-link {{ request()->is('bairros*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Bairros</p>
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a href="{{ route('Listar-Cidades') }}" class="nav-link {{ request()->is('cidades*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cidades</p>
                            </a>
                        </li> -->
                        <!-- <li class="nav-item">
                            <a href="{{ route('Listar-Estados') }}" class="nav-link {{ request()->is('estados*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Estados</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('Listar-Paises') }}" class="nav-link {{ request()->is('paises*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Países</p>
                            </a>
                        </li> -->
                        <li class="nav-item">
                        <a href="{{ route('Listar-Usuarios') }}" class="nav-link {{ request()->is('users*', 'usuarios*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Usuários</p>
                         </a>
                       </li>
                        <li class="nav-item">
                            <a href="{{ route('Listar-Papeis') }}" class="nav-link {{ request()->is('papeis*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Papéis</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('permissions.index') }}" class="nav-link {{ request()->is('permissoes*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-key"></i>
                                <p>Permissões</p>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            @if (Auth::guard('professor')->check())
                <!-- Minhas Turmas -->
                <li class="nav-item {{ request()->is('professor/turmas*') ? 'menu-open' : '' }}">
                    <a href="{{ route('Mostrar-Turmas-Professor') }}" class="nav-link {{ request()->is('professor/turmas*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Minhas Turmas</p>
                    </a>
                </li>

                <!-- Frequências -->
                <li class="nav-item {{ request()->is('professor/frequencias*') ? 'menu-open' : '' }}">
                    <a href="{{ route('Listar-Frequencias-Professor') }}" class="nav-link {{ request()->is('professor/frequencias*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-check-square"></i>
                        <p>Frequências</p>
                    </a>
                </li>
                <!-- Conteúdos -->
                <li class="nav-item {{ request()->is('professor/conteudos*') ? 'menu-open' : '' }}">
                    <a href="{{ route('Listar-Conteudos-Professor') }}" class="nav-link {{ request()->is('professor/conteudos*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>Reg. de Aulas</p>
                    </a>
                </li>
                
            @endif
        </ul>
    </nav>
</div>
