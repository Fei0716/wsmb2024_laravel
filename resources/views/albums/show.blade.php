

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
    <section aria-label="Manage Album Section">
        <h1 class="text-center mb-3">Manage Album({{$album->title}})</h1>

        <form action="{{route('albums.update', $album)}}" method="post" class="border-bottom border-primary mb-4">
            @csrf
            @method('put')
            <div class="mb-2">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{$album->title}}" required>
            </div>
            <div class="mb-4">
                <label for="status">Status</label>
                <select  name="status" id="status" class="form-select" required>
                    <option value="0" {{$album->status ==='0' ? 'selected' :''}}>Public</option>
                    <option value="1" {{$album->status ==='1' ? 'selected' :''}}>Private</option>
                </select>
            </div>

            <div class="mb-2">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
        <div class="cards row" id="card-images">
            @foreach($album->images as $i)
                    <article class="col-3">
                        <div class="card shadow-sm position-relative">
                            <form action="{{route('images.destroy' , $i)}}" method="post">
                                @csrf
                                @method('delete')
                                <button class="btn-close position-absolute top-0 end-0" type="submit"></button>
                            </form>
                            <img src="{{asset('storage/'.$i->path)}}" alt="Thumbnail for album" class="card-img-top card-img">
                        </div>
                    </article>
            @endforeach
            <input type="file" name="images[]" id="images" accept=".png,.jpeg,.jpg" hidden multiple>
            <button id="btn-add" class="col-3">+</button>
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        jQuery(document).ready(function($){
            const album_id = @json($album->id);
            $('#btn-add').on('click',function(){
                $('#images').click();
            });
            $('#images').on('change',function(e){
                const files = e.target.files;
                if(files.length <= 0){
                    alert('No file selected');
                    return;
                }
                uploadImage(files);
            });
            function uploadImage(f){
                const formData = new FormData();
                for(let file of f){
                    formData.append('images[]' , file);
                }
                formData.append('_token' , "{{csrf_token()}}");
                formData.append('album_id' , album_id);

            //     ajax
                $.ajax({
                    url: '{{route('images.store')}}',
                    method: 'post',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(result){
                        console.log(result.message)
                    },
                    error: function(err){
                        console.error(err);
                    }
                })
            }

            getImages();
            function getImages(){
                $.ajax({
                    url: '{{route('images.index')}}',
                    method: 'get',
                    data:{
                        'album_id' : album_id,
                        '_token' : "{{csrf_token()}}",
                    },
                    success: function(result){
                        $('#card-images').empty();

                    //     append each of the image
                        for(let i of result.images){
                            let linkForm = "{{route('images.destroy' , ':id')}}".replace(':id' , i.id);
                            let linkImage = "/storage/:id".replace(':id' , i.path);
                            $('#card-images').append(`
                                <article class="col-3">
                                    <div class="card shadow-sm position-relative">
                                        <form action="${linkForm}" method="post">
                                            @csrf
                                            @method('delete')
                                        <button class="btn-close position-absolute top-0 end-0" type="submit"></button>
                                    </form>
                                    <img src="${linkImage}" alt="Thumbnail for album" class="card-img-top card-img">
                                    </div>
                                </article>
                            `)
                        }
                    },
                    error: function(err){
                        console.error(err);
                    }
                })
            }
        });
    </script>
@endsection
