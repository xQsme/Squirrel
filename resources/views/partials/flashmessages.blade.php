@if(Session::has('message_error'))
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {{Session::get('message_error')}}
</div>
@elseif(Session::has('message_success'))
<div class="alert alert-success alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {!! nl2br(e(session::get('message_success'))) !!}
</div>
@elseif(Session::has('message_warning'))
  <div class="alert alert-warning alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {{Session::get('message_warning')}}
  </div>
@endif