@extends('layout.main')

@section('styles')
    <style>

    </style>
@endsection
@section('content')
    <section aria-label="Users Section" class="mt-5">
        <h1 class="text-center mb-3">Users</h1>
        <table class="table table-responsive table-hover table-border table-dark">
            <tr>
                <th>Username</th>
                <th>Albums</th>
                <th>Photos</th>
                <th>Likes</th>
                <th></th>
            </tr>
            @foreach($users as $u)
                <tr>
                    <td>{{$u->username}}</td>
                    <td>{{$u->albums->count()}}</td>
                    <td>{{$u->images->count()}}</td>
                    <td>
{{--                        @php--}}
{{--                            $count = 0;--}}
{{--                        @endphp--}}
{{--                        @foreach($u->images as $i)--}}
{{--                            @php--}}
{{--                              $count += $i->likes->count();--}}
{{--                            @endphp--}}
{{--                        @endforeach--}}
                        {{$u->likesCount}}
                    </td>
                    <td>
                        <form action="{{route('users.destroy' , $u)}}" method="post">
                            @csrf
                            @method('delete')
                            <button class="btn btn-primary" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </section>

@endsection
