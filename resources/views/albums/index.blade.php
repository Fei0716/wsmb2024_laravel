

@extends('layout.main')

@section('styles')
    <style>
        .card-img{
            height: 200px;
            object-fit: cover;
        }
        .btn-close{
            font-size: 1.25rem!important;
            padding: 12px;
            background-color: white!important;
            opacity: 1!important;
        }
        #btn-add{
            font-size: 6rem;
            border: 0;
            border-radius: 0.25rem;
            transition: all 0.2s;
        }
        #btn-add:hover{
            background-color: #a2a2a2;
        }
    </style>
@endsection
@section('content')
    <section aria-label="Gallery Section">
        <h1 class="text-center mb-3">Albums</h1>
        <div class="cards row">
            @foreach($albums as $a)
                @if($a->images->count() > 0)
                    <article class="col-3">
                        <div class="card shadow-sm position-relative h-100">
                            <form action="{{route('albums.destroy' , $a)}}" method="post">
                                @csrf
                                @method('delete')
                                <button class="btn-close position-absolute top-0 end-0" type="submit"></button>
                            </form>
                            <a href="{{route('albums.show', $a)}}"><img src="{{asset('storage/'.$a->images[0]->path)}}" alt="Thumbnail for album" class="card-img-top card-img"></a>
                            <div class="card-body">
                                <h2>{{$a->title}}</h2>
                            </div>
                        </div>
                    </article>
                    @else
                    <article class="col-3">
                        <div class="card shadow-sm position-relative h-100">
                            <form action="{{route('albums.destroy' , $a)}}" method="post">
                                @csrf
                                @method('delete')
                                <button class="btn-close position-absolute top-0 end-0" type="submit"></button>
                            </form>
                            <a href="{{route('albums.show', $a)}}"><div class="bg-primary card-img"></div></a>
                            <div class="card-body">
                                <h2>{{$a->title}}</h2>
                            </div>
                        </div>
                    </article>
                @endif
            @endforeach

            <button id="btn-add" class="col-3" data-bs-target="#modal-add" data-bs-toggle="modal">+</button>
        </div>
    </section>

    <div class="modal fade" id="modal-add">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bottom-0">
                 <div class="modal-header">
                     <h2 class="text-center">Add Album</h2>
                 </div>
                <div class="modal-body">
                    <form action="{{route('albums.store')}}" method="post">
                        @csrf
                        <div class="mb-2">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label for="status">Status</label>
                            <select  name="status" id="status" class="form-select" required>
                                <option value="0">Public</option>
                                <option value="1">Private</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <button type="submit" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
