<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tienda online') }}</title>

    <!-- Styles -->


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-beta.40/css/uikit.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-beta.40/js/uikit.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-beta.40/js/uikit-icons.min.js"></script>

</head>
<body>

<div class="uk-offcanvas-content">


    <nav class="uk-navbar-container">
        <div class="uk-container">
            <div uk-navbar="" class="uk-navbar">
                <div class="uk-navbar-left">

                    <ul class="uk-navbar-nav">
                        <li class="uk-active"><a class="uk-navbar-toggle" href="#" uk-toggle="target: #offcanvas-nav">
                                <span uk-navbar-toggle-icon></span> <span class="uk-margin-small-left">Menu</span>
                            </a></li>
                    </ul>

                </div>
                <div class="uk-navbar-right">
                    <ul class="uk-navbar-nav">
                        <li>
                            <a href="#">{{ Auth::user()->name }}</a>
                            <div class="uk-navbar-dropdown">
                                <ul class="uk-nav uk-navbar-dropdown-nav">
                                    <li><a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Salir
                                        </a>
                                    </li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        @csrf
                                    </form>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    @if(Auth::check())
        <div class="uk-offcanvas-content">
            <div id="offcanvas-nav" uk-offcanvas="overlay: true">
                <div class="uk-offcanvas-bar">
                    <h3>ADMIN</h3>
                    <button class="uk-offcanvas-close" type="button" uk-close></button>
                    <ul class="uk-nav uk-nav-default uk-nav-parent-icon" uk-nav>
                        <li>
                            <a href="{!! route('admin.dashboard.index') !!}">
                                <span class="uk-margin-small-right" uk-icon="icon: home"></span>
                                Dashboard
                            </a>
                        </li>
                        @permission(['consultar_usu','crear_usu'])
                            <li>
                                <a href="{!! route('admin.users.index') !!}">
                                    <span class="uk-margin-small-right" uk-icon="icon: users"></span>
                                    Ususarios
                                </a>
                            </li>
                        @endpermission
                        @permission('config')
                            <li>
                                <a href="{!! route('admin.category.index') !!}">
                                    <span class="uk-margin-small-right" uk-icon="icon: bookmark"></span>
                                    Lista de categorías
                                </a>
                            </li>
                        @endpermission
                        @permission('config')
                            <li>
                                <a href="{!! route('admin.line.index') !!}">
                                    <span class="uk-margin-small-right" uk-icon="icon: album"></span>
                                    Lineas de investigación
                                </a>
                            </li>
                        @endpermission
                        @permission('config')
                            <li>
                                <a href="{!! route('admin.extension.index') !!}">
                                    <span class="uk-margin-small-right" uk-icon="icon: code"></span>
                                    Extensiones
                                </a>
                            </li>
                        @endpermission
                        @permission('config image')
                            <li>
                                <a href="{!! route('admin.image.index') !!}">
                                    <span class="uk-margin-small-right" uk-icon="icon: image"></span>
                                    Gestionar Imágenes
                                </a>
                            </li>
                        @endpermission
                        @permission('roles')
                            <hr class="uk-divider-icon">
                            <li class="uk-parent">
                                <a href="#"><span class="uk-margin-small-right" uk-icon="icon: cog"></span>
                                    Configuraciones
                                </a>
                                <ul class="uk-nav-sub">
                                    <li>
                                        <a href="{!! route('admin.roles.index') !!}">
                                            <span class="uk-margin-small-right" uk-icon="icon: settings"></span>
                                            Roles
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{!! route('admin.permissions.index') !!}">
                                            <span class="uk-margin-small-right" uk-icon="icon: album"></span>
                                            Permisos
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endpermission
                    </ul>
                </div>
            </div>
        </div>
    @endif


    <div class="uk-margin">
        @yield('content')
    </div>
</div>


</body>
</html>
