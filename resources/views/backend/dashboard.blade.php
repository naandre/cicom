@extends(config('toaster.template'))

@section(config('toaster.content'))
    <div class="uk-container">
        <div>
            <h2>
                <b>{{config('app.name')}}</b>
                <br />
                Bienvenido al Administrador de Contenido
            </h2>
            <hr />
            <h4>
                Selecciona una de las opciones del menu, que se encuentra ubicado en la parte superior izquierda
            </h4>
        </div>
    </div>
@endsection
