@extends('web')

@section('content')
@include('/includes/flash_message_content')
    <h1>Show Users</h1>

    <div class="container">
        <table class="table-condensed" id="userTable">
            <tbody>
                <tr>
                    <td><h4>Name</h4></td>
                    <td><h4>Email</h4></td>
                    <td><h4>Role</h4></td>
                </tr>
            @foreach($users as $user)
            <tr>
                <td>
                    <a href='{{ action('UserController@edit', $user->id) }}'>{{ $user->full_name }}</a>
                </td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
<hr />
    </div>

    <a class="btn btn-success" href="{{ action('UserController@create') }}">
        New
    </a>
@stop

@section('footer')
<script language="javascript">

$(document).ready (function(){
    $('#userTable').on('mouseover', 'tbody tr', function(event) {
        $(this).addClass('bg-info').siblings().removeClass('bg-info');
    });
});

</script>
@stop
