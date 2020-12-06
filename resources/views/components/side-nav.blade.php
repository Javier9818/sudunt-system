<div class="sidebar sidebar-style-2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    <img src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/images/user.png" alt="..." class="avatar-images rounded-circle" width="100%">
                </div>
                <div class="info">
                    <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                        <span>
                            {{session('names')}}
                            <span class="user-level">
                                @if(Auth::user()->is_admin)
                                    Administrator
                                @else
                                    Personero
                                @endif
                            </span>
                            <span class="caret"></span>
                        </span>
                    </a>
                    <div class="clearfix"></div>

                    <div class="collapse in" id="collapseExample">
                        <ul class="nav">
                            <!-- <li>
                                <a href="#profile">
                                    <span class="link-collapse">Mi perfil</span>
                                </a>
                            </li>
                            <li>
                                <a href="#edit">
                                    <span class="link-collapse">Editar perfil</span>
                                </a>
                            </li> -->
                            <!-- <li>
                                <a href="#settings">
                                    <span class="link-collapse">Settings</span>
                                </a>
                            </li> -->
                        </ul>
                    </div>
                </div>
            </div>
            <ul class="nav nav-primary">
                @can('rol-admin')
                    <li class="nav-item {{ $isTab('1') ? 'active' : '' }}">
                        <a data-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
                            <i class="fas fa-home"></i>
                            <p>General</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{ $isTab('1') ? 'show' : '' }}" id="dashboard">
                            <ul class="nav nav-collapse">
                                <li class="{{ $isSelected('1', '1') ? 'active' : '' }}">
                                    <a href="/user">
                                        <span class="sub-item">Usuarios</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan
                
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Sistema</h4>
                </li>
                <li class="nav-item {{ $isTab('2') ? 'active' : '' }}" >
                    <a data-toggle="collapse" href="#base">
                        <i class="fas fa-layer-group"></i>
                        <p>Votaciones</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ $isTab('2') ? 'show' : '' }}" id="base">
                        <ul class="nav nav-collapse">
                            @can('rol-admin')
                            <li class="{{ $isSelected('2', '1') ? 'active' : '' }}">
                                <a href="/padron">
                                    <span class="sub-item">Padrón</span>
                                </a>
                            </li>
                            @endcan
                            <li class="{{ $isSelected('2', '2') ? 'active' : '' }}">
                                <a href="/form">
                                    <span class="sub-item">Formularios</span>
                                </a>
                            </li>
                            <!-- <li class="{{ $isSelected('2', '3') ? 'active' : '' }}">
                                <a href="/formularios">
                                    <span class="sub-item">Estado</span>
                                </a>
                            </li> -->
                            <!-- <li>
                                <a href="components/gridsystem.html">
                                    <span class="sub-item">Grid System</span>
                                </a>
                            </li>
                            <li>
                                <a href="components/panels.html">
                                    <span class="sub-item">Panels</span>
                                </a>
                            </li>
                            <li>
                                <a href="components/notifications.html">
                                    <span class="sub-item">Notifications</span>
                                </a>
                            </li>
                            <li>
                                <a href="components/sweetalert.html">
                                    <span class="sub-item">Sweet Alert</span>
                                </a>
                            </li>
                            <li>
                                <a href="components/font-awesome-icons.html">
                                    <span class="sub-item">Font Awesome Icons</span>
                                </a>
                            </li>
                            <li>
                                <a href="components/simple-line-icons.html">
                                    <span class="sub-item">Simple Line Icons</span>
                                </a>
                            </li>
                            <li>
                                <a href="components/flaticons.html">
                                    <span class="sub-item">Flaticons</span>
                                </a>
                            </li>
                            <li>
                                <a href="components/typography.html">
                                    <span class="sub-item">Typography</span>
                                </a>
                            </li> -->
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
