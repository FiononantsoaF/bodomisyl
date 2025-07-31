@include('layouts.header')

@include('layouts.sidebar')

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 small" >
                @yield('content')
            </div>
        </main>
    </div>

@include('layouts.footer')
