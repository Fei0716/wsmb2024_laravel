<header aria-label="Header">
    <nav class="navbar navbar-expand-lg bg-dark text-white">
        <div class="container-fluid d-flex justify-content-between">
            <a class="navbar-brand text-white" href="#">Photo Albums</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
                @if(!Auth::user())
                <div class="navbar-nav">
                    <a class="nav-link text-white" href="{{route('loginPage')}}"><button class="btn btn-primary">Index</button></a>
                    <a class="nav-link text-white" href="{{route('registerPage')}}"><button class="btn btn-primary">Register</button></a>
                </div>
                @elseif(Auth::user()->role === '0')
                    <div class="navbar-nav">
                        <a class="nav-link text-white" href="{{route('gallery.index')}}"><button class="btn btn-primary">Gallery</button></a>
                        <a class="nav-link text-white" href="{{route('albums.index')}}"><button class="btn btn-primary">Album</button></a>
                        <a href="#" class="nav-link">
                            <form action="{{route('logout')}}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-primary">Logout</button>
                            </form>
                        </a>

                    </div>
                    <div class="h3">{{Auth::user()->username}}</div>
                @elseif(Auth::user()->role === '1')
                    <div class="navbar-nav">
                        <a class="nav-link text-white" href="{{route('users.index')}}"><button class="btn btn-primary">Users</button></a>
                        <a href="#" class="nav-link d-flex ">
                            <form action="{{route('logout')}}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-primary my-auto">Logout</button>
                            </form>
                        </a>
                    </div>
                    <div class="h3">{{Auth::user()->username}}</div>
                @endif
            </div>
    </nav>
</header>

