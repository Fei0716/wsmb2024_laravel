@extends('layout.main')

@section('styles')
    <style>
        .card-img{
            height: 200px;
            object-fit: cover;
        }
    </style>
@endsection
@section('content')
  <section aria-label="Gallery Section">
      <h1 class="text-center mb-3">Gallery</h1>
      <div class="cards row">
          @foreach($albums as $a)
              @if($a->images->count() > 0)
                  <article class="col-3">
                      <div class="card shadow-sm h-100">
                          <a href="{{route('gallery.show', $a)}}"><img src="{{asset('storage/'.$a->images[0]->path)}}" alt="Thumbnail for album" class="card-img-top card-img"></a>
                          <div class="card-body">
                              <h2>{{$a->title}}</h2>
                              <div>{{$a->user->username}}</div>
                          </div>
                      </div>
                  </article>

              @endif
          @endforeach
      </div>
  </section>

@endsection
