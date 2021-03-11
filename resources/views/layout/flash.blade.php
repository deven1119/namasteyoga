@if(session()->has('message.level'))
    <div class="alert alert-{{ session('message.level') }}" style="margin-top:65px;">
    {!! session('message.content') !!}
    </div>
@endif
