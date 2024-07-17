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
        #card-images{
            width: 800px;
            margin: 0 auto;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 2px solid transparent;
        }
        .dragging{
            animation: dragEffect 1s infinite;
        }
        @keyframes dragEffect {
            from{
                background-color: #6e6e6e;
                border: 2px solid transparent;
            }
            to{
                background-color: #a2a2a2;
                border: 2px solid #2f2f2f;
            }
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
{{--            load using ajax--}}
        </div>
    </section>

{{--    modal fullscreen image--}}
    <div class="modal fade" tabindex="-1" id="modal-fullscreen">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-header border-bottom-0">
                    <button class="btn-close" aria-label="Clock Modal Dialog" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body" id="fullscreen-image">
{{--                    image here--}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        jQuery(document).ready(function($){
            const album_id = @json($album->id);
            $(document).on('click' , '#btn-add',function(){
                $('#images').click();
            });
            $(document).on('change', '#images',function(e){
                const files = e.target.files;
                if(files.length <= 0){
                    alert('No file selected');
                    return;
                }
                uploadImage(files);
            });
            function uploadImage(f , type = 'multiple'){
                const formData = new FormData();
                if(type === 'multiple'){
                    for(let file of f){
                        formData.append('images[]' , file);
                    }
                }else{
                    formData.append('images[]' , f);
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
                        getImages();
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
                                        <button class="btn-close position-absolute top-0 end-0" data-image-id="${i.id}" id="btn-delete"></button>
                                    <img src="${linkImage}" alt="Thumbnail for album" class="card-img-top card-img">
                                    </div>
                                </article>
                            `)
                        }
                        $('#card-images').append('<input type="file" name="images[]" id="images" accept=".png,.jpeg,.jpg" hidden multiple><button id="btn-add" class="col-3">+</button>');
                    },
                    error: function(err){
                        console.error(err);
                    }
                })
            }

        //     drag and drop
            $(document).on('dragover' ,function(e){
                e.preventDefault();
                $('#card-images').addClass('dragging')
            })
            $(document).on('dragleave' ,function(e){
                $('#card-images').removeClass('dragging')
            })
            $(document).on('dragleave drop' , '#card-images' , function(e){
                e.preventDefault();
                e.target.classList.remove('dragging')
            });
            $('#card-images').on('drop' , function(e){
                e.preventDefault();
                e.target.classList.remove('dragging')
                const files = e.originalEvent.dataTransfer.files[0];
                if(files.length <= 0){
                    alert('No file selected');
                    return;
                }
                uploadImage(files , 'single');
            });

            //for delete image
            $(document).on('click' , '#btn-delete',function(e){
                const id = $(this).data('image-id');
                deleteImage(id);
            });

            function deleteImage(id){
                //     ajax
                const link = "{{route('images.destroy',':id')}}".replace(':id' , id);
                $.ajax({
                    url: link,
                    method: 'delete',
                    data:{
                        "_token": "{{csrf_token()}}",
                    },
                    success: function(result){
                        getImages();
                    },
                    error: function(err){
                        console.error(err);
                    }
                })
            }
            //for fullscreen image
            $(document).on('click', '.card-img' , function(e){
                const src = e.target.src;
                $('#fullscreen-image').html(`
                    <img src="${src}" alt="Fullscreen Image">
                `);
                $('#modal-fullscreen').modal('show');
            });
        });
    </script>
@endsection
