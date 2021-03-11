<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
  <div class="menu_section">
    @if(Auth::user()->role_id==1)
    <ul class="nav side-menu">
      <li><a href="{{ url('/') }}/home"><i class="fa fa-home"></i> Dashboard</a></li>     
      
      <li><a><i class="fa fa-calendar" aria-hidden="true"></i> View Events <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a href="{{ url('/') }}/events">View Events</a></li>
        </ul>
      </li>
      
      <li><a><i class="fa fa-users"></i> View Trainers<span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ url('/') }}/users">View Trainers</a></li>                  
        </ul>
      </li>   

      <li><a><i class="fa fa-users"></i> View Centers<span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ url('/') }}/users/center">View Centers</a></li>                  
        </ul>
      </li>   

      <li><a><i class="fa fa-users"></i> Manage Moderators<span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          
          <li><a href="{{ url('/') }}/users/add">Add Moderator</a></li>
          <li><a href="{{ url('/') }}/users/moderator_list">Moderator list</a></li>                          
        </ul>
      </li>   
         
      <li><a><i class="fa fa-history"></i> Audit Trail <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ url('/') }}/audittrails">Activity List</a></li>
        </ul>
      </li>      
    </ul>
    @endIf
    
   
    @if(Auth::user()->role_id==4)
      @php
            $assignedModerators = Session::get('moderatorTypes');
           
      @endphp
    <ul class="nav side-menu">
      <li><a href="{{ url('/') }}/home"><i class="fa fa-home"></i> Dashboard</a></li>           
      
      @if(in_array(1,$assignedModerators))
      <li><a><i class="fa fa-users"></i> Manage Yoga<span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ url('/') }}/users/pendings">Pending Approval</a></li>                  
          <li><a href="{{ url('/') }}/users">Approved Trainers</a></li>                  
          <li><a href="{{ url('/') }}/users/rejected">Rejected Trainers</a></li>                  
        </ul>
      </li>         
  @endif
  <!-- 
  @if(in_array(2,$assignedModerators))
      <li><a><i class="fa fa-users"></i> Manage Trainers<span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ url('/') }}/users/pendings">Pending Approval</a></li>                  
          <li><a href="{{ url('/') }}/users">Approved Trainers</a></li>                  
          <li><a href="{{ url('/') }}/users/rejected">Rejected Trainers</a></li>                  
        </ul>
      </li>         
  @endif
  @if(in_array(3,$assignedModerators))
      <li><a><i class="fa fa-users"></i> Manage Merchandise<span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ url('/') }}/users/pendings">Pending Approval</a></li>                  
          <li><a href="{{ url('/') }}/users">Approved Trainers</a></li>                  
          <li><a href="{{ url('/') }}/users/rejected">Rejected Trainers</a></li>                  
        </ul>
      </li>         
  @endif
  @if(in_array(4,$assignedModerators))
      <li><a><i class="fa fa-users"></i> Manage Video Content<span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ url('/') }}/users/pendings">Pending Approval</a></li>                  
          <li><a href="{{ url('/') }}/users">Approved Trainers</a></li>                  
          <li><a href="{{ url('/') }}/users/rejected">Rejected Trainers</a></li>                  
        </ul>
      </li>         
  @endif
  @if(in_array(5,$assignedModerators))
      <li><a><i class="fa fa-users"></i> Manage Quiz<span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ url('/') }}/users/pendings">Pending Approval</a></li>                  
          <li><a href="{{ url('/') }}/users">Approved Trainers</a></li>                  
          <li><a href="{{ url('/') }}/users/rejected">Rejected Trainers</a></li>                  
        </ul>
      </li>         
  @endif -->
    </ul>
    @endIf

  </div>

</div>
