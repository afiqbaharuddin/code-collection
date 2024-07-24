@extends ('layotus.app')

@section('content')
<div class="container">
    @if (session('resent'))
        <div class="alert alert-success" role="alert">
            {{_('A fresh verification link has been sent to your email address.')}}
        </div>
    @endif

    {{_('Before proceeding, please check your email for verification link.')}}
    {{_('If you did not receive the email')}}
    <form method="POST" action="{{route('verification.send')}}" class="d-inline ">
        @csrf
        <button class="btn btn-link p-0 m-0 align-baseline">{{_('click here to request another')}}</button>
    </form>
</div>
@endsection