

     @if(session('success'))
        <div class="alert alert-success" style="margin-top:65px;">
          {{ session('success') }}
        </div> 
        @endif
        
        @if($errors->any())
        <div class="alert alert-danger" style="margin-top:65px;">
        {{ implode(' ', $errors->all(':message')) }}
       </div>
      @endif





