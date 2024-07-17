@extends('layout.main')

@section('styles')
    <style>
        #img-wrapper{
            width: fit-content;
            position: relative;
            margin: 0 auto;

        }
        button{
            transition: all 0.2s ease;
        }
        #btn-likes{
            position: absolute;
            bottom: 0;
            left: 0;
            background-color: #e6e6e6;
            padding: 0.5rem 1.0rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            border: none;
            cursor: pointer;
            font-size: 1.75rem;
        }
        #album-images-slide{
            position: relative;
            display: flex;
            width: 567px;
            align-items: center;
            overflow-x: visible;
            margin: 0 auto;
        }
        #img-slides-wrapper{
            display: flex;
            gap: 10px;
            width: calc((5 * 100px) + (2*18px) + (4*10px));
            overflow-x: hidden;
            padding: 0.5rem 1rem;
            background-color: #4f4f4f;
            border-radius: 0.25rem;
        }
        .img-slide{
            width: 100px;
            object-fit: cover;
            border: 4px solid transparent;
            cursor: pointer;
            transition: all 0.3s;
        }
        .img-slide.active{
            border: 4px solid #2e6dff;
        }
        #album-images-slide > button{
            position: absolute;
            cursor: pointer;
            background-color: #e6e6e6;
            width: 50px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border: none;
            font-size: 2rem;
            border-radius: 0.25rem;
        }
        #btn-slide-left{
            left: -14%;
        }
        #btn-slide-right{
            right: -14%;
        }
        button:hover{
            background-color: #9f9f9f !important;
        }
        #like{
            font-size: 3rem;
        }
        #like.liked{
            color: red;
        }
    </style>
@endsection
@section('content')
    <section aria-label="Gallery Section" class="mt-5">
        <h1 class="text-center mb-3">{{$album->title}} - {{$album->user->username}}</h1>
        <article id="img-wrapper" class="mb-3">
{{--            load image--}}
            <img src="{{asset('storage/'.$album->images[0]->path)}}" alt="Spotlight Image" id="img-spotlight">
            <button id="btn-likes">
                @if(count($album->images[0]->likes()->where('user_id' , Auth::user()->id)->get()) != 0)
                <span id="like" class="liked">&hearts; </span>
                @else
                    <span id="like">&hearts; </span>
                @endif
                X
                <span id="like-count"> {{$album->images[0]->likes->count()}}</span>
            </button>
        </article>
{{--        bottom section--}}
        <article id="album-images-slide">
            <button id="btn-slide-left" class="shadow-sm"> &lt;</button>
            <div id="img-slides-wrapper">
                @foreach($album->images as $key => $i)
                    @if($key === 0)
                        <img src="{{asset('storage/'.$i->path)}}" alt="Spotlight Image" class="img-slide active" data-index="{{$key}}">
                    @else
                        <img src="{{asset('storage/'.$i->path)}}" alt="Spotlight Image" class="img-slide" data-index="{{$key}}">
                    @endif

                @endforeach
            </div>
            <button id="btn-slide-right" class="shadow-sm"> &gt;</button>
        </article>
    </section>

@endsection

@section('scripts')
    <script>
        jQuery(document).ready(function($){
            let currentImageIndex =  0;
            let images = @json($album->images);
            let likes = @json($album->likes);
            let userId = "{{Auth::user()->id}}";
            $(document).on('click' , '.img-slide', function(){
                currentImageIndex = $(this).data('index');
                $('.img-slide.active').removeClass('active');
                $(this).addClass('active');
                loadImage();
            });

            function loadImage(){
                //replace image
                let src = '{{asset('storage/:path')}}'.replace(':path' , images[currentImageIndex].path);
                $('#img-spotlight').attr('src' , src);

                let imageLikes = likes.filter((l) => l.image_id === images[currentImageIndex].id);
                let imageLikesCount  = imageLikes.length;

                //replace like count
                $('#like-count').html(`${imageLikesCount}`);

                //check if user liked the image
                if(imageLikes.find((l) => l.user_id === {{Auth::user()->id}})){
                    $('#like').addClass('liked');
                    return;
                }

                $('#like').removeClass('liked');
            }

            $(document).on('click', '#btn-likes' , function(){
                updateLike();
            });

            function updateLike(){
                let imageId = images[currentImageIndex].id;
                let userId = {{Auth::user()->id}};
                let albumId = {{$album->id}};
                //     ajax
                $.ajax({
                    url: '{{route('likes.store')}}',
                    method: 'post',
                    data:{
                        imageId : imageId,
                        userId: userId,
                        albumId: albumId,
                        '_token': "{{csrf_token()}}",
                    },
                    success: function(result){
                        likes.splice(0 , likes.length);
                        likes.push(...result.data);

                        $('#like-count').html(`${likes.filter((l) => l.image_id === images[currentImageIndex].id).length}`);

                        //like
                        if(likes.find((l) => l.user_id === userId && l.image_id === images[currentImageIndex].id)){
                            $('#like').addClass('liked');
                            return;
                        }
                        //unlike
                        $('#like').removeClass('liked');

                    },
                    error: function(err){
                        console.error(err);
                    }
                })

            }

            //add click events to slide left right buttons
            $(document).on('click' , '#btn-slide-left' ,function(){
                if(currentImageIndex > 0){
                    currentImageIndex--;
                    let src = '{{asset('storage/:path')}}'.replace(':path' , images[currentImageIndex].path);
                    $('#img-spotlight').attr('src' , src);
                    $('.img-slide.active').removeClass('active');
                    $(`.img-slide:nth-child(${currentImageIndex + 1})`).addClass('active');

                    //slide the container left

                    let containerWidth = getComputedStyle(document.getElementById('img-slides-wrapper')).width;
                    let imgWidth = '100px';
                    let scrollAmount = (currentImageIndex * parseInt(imgWidth)) + (currentImageIndex * 10) - 110;
                    $('#img-slides-wrapper').animate({
                        scrollLeft: scrollAmount
                    }, 300);
                }
            })
            $(document).on('click' , '#btn-slide-right' ,function(){
                if(currentImageIndex < images.length - 1){
                    currentImageIndex++;
                    let src = '{{asset('storage/:path')}}'.replace(':path' , images[currentImageIndex].path);
                    $('#img-spotlight').attr('src' , src);
                    $('.img-slide.active').removeClass('active');
                    $(`.img-slide:nth-child(${currentImageIndex + 1})`).addClass('active');

                    //slide the container right
                    let containerWidth = getComputedStyle(document.getElementById('img-slides-wrapper')).width;
                    let imgWidth = '100px';
                    let scrollAmount = (currentImageIndex * parseInt(imgWidth)) + (currentImageIndex * 10);

                    $('#img-slides-wrapper').animate({
                        scrollLeft: scrollAmount
                    }, 300);
                }
            })
        });
    </script>

@endsection
