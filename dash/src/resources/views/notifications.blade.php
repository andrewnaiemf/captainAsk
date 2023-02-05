@if(!empty($dash_notifications) && count($dash_notifications))
<li class="nav-item dropdown px-2 d-flex align-items-center mr-3">
  <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fa fa-bell fa-2x cursor-pointer"></i>
    @php
    $DashtotalCount=0;
    foreach($dash_notifications as $dash_count){
    $DashtotalCount = $DashtotalCount + $dash_count::unreadCount();
    }
    @endphp
    <span class="position-absolute top-0 {{ app()->getLocale() == 'ar'?'start-75':'' }} translate-middle p-1  bg-danger {{ $DashtotalCount == 0 ?'d-none':'' }} border unread_count border-light rounded-circle text-white"
    style="
    width: 21px;
    height: 23px;
    font-size: 11px;
    font-weight: bold;
    text-align: center;
    ">{{ $DashtotalCount }}</span>
  </a>
  <ul class="dropdown-menu notifications_list {{ app()->getLocale()!='ar'?'dropdown-menu-end':'' }} px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
    @foreach($dash_notifications as $dash_notify)
    {!! $dash_notify::content() !!}
    @endforeach
    <li class="mb-2">
      <a class="dropdown-item border-radius-md" href="{{ url(app('dash')['DASHBOARD_PATH'].'/page/Notifications') }}">
        <center>
        <i class="fa fa-bell fa-1x cursor-pointer"></i>
        {{ __('dash::dash.Show_All_Notifications') }}
        </center>
      </a>
    </li>
  </ul>
</li>

@push('js')
<script type="text/javascript">
var dashUrl  = '{{ url('') }}';
var dashPath = '{{ app('dash')['DASHBOARD_PATH'] }}';
var _token   = '{{ csrf_token() }}';
</script>

@foreach($dash_notifications as $dash_notify)
  @foreach($dash_notify::stack() as $key=>$value)
    @if($key == 'blade')
      @foreach($value as $stackBlade)
        @includeIf($stackBlade)
      @endforeach
    @endif
    @if($key == 'js')
      @foreach($value as $stackJs)
      <script type="text/javascript" src="{{ $stackJs }}"></script>
      @endforeach
    @endif

  @endforeach
@endforeach

@endpush

@endif