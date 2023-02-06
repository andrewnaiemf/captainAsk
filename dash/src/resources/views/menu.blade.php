<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 {{ app()->getLocale()=='ar'?'fixed-end me-3 rotate-caret':'fixed-start ms-3' }}  bg-gradient-dark" id="sidenav-main">
  <div class="sidenav-header">
    <a href="#" class="d-none d-xl-none" id="iconSidenav">
    <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 " aria-hidden="true"></i>
    </a>
    <a class="navbar-brand m-0" href="{{ url($DASHBOARD_PATH) }}" >
      @if(!empty($DASHBOARD_ICON))
      <img src="{{asset('dashboard//assets/img/captainAsk.png')}}" class="navbar-brand-img h-100" alt="main_logo">
      @else
      <i class="fa fa-dashboard text-white {{ request()->segment(2) == 'dashboard'?'opacity-10':'opacity-5' }}"></i>
      @endif
      <span class="me-1 font-weight-bold text-white">{{ $APP_NAME }}</span>
    </a>
  </div>
  <hr class="horizontal light mt-0 mb-2">
  {{-- collapse navbar-collapse --}}
  <div class=" {{ app()->getLocale()=='ar'?'px-0':'' }} w-auto " id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link
          {{ request()->segment(2) == 'dashboard'?'active':'' }}
          " href="{{ url($DASHBOARD_PATH.'/dashboard') }}">
          <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-dashboard text-white {{ request()->segment(2) == 'dashboard'?'opacity-10':'opacity-5' }}"></i>
          </div>
          <span class="nav-link-text me-1 {{ app()->getLocale()=='ar'?'ps-2':'px-2' }}">@lang('dash::dash.dashboard') </span>
        </a>
      </li>
@if(isset($loadInNavigationPagesMenu['top']) && !empty($loadInNavigationPagesMenu['top']))
<!-- Pages top Start  -->
@foreach($loadInNavigationPagesMenu['top'] as $page)
@if($page['displayInMenu'])
<li class="nav-item">
        <a class="nav-link {{ request()->segment(3) == $page['RouteName']?'active':'' }}" href="{{ url($DASHBOARD_PATH.'/page/'.$page['RouteName']) }}">
          <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
            <i class="material-icons-round  {{ request()->segment(3) == $page['RouteName']?'opacity-10':'opacity-5' }}">{!! $page['icon'] !!}</i>
          </div>
          <span class="nav-link-text me-1 {{ app()->getLocale()=='ar'?'ps-2':'px-2' }}">
              @if(trans()->has($page['pageName']))
            {{ __($page['pageName']) }}
            @else
            {{ $page['pageName']??ucfirst($page['RouteName']) }}
            @endif
          </span>
        </a>
      </li>
@endif
@endforeach
<!-- Pages top End-->
@endif

<?php $x = 0;?>
@foreach($loadInNavigationMenu as $groups)
      <li class="nav-item mt-3">
        <h6 class="{{ app()->getLocale()=='ar'?'px-4':'ps-4' }} ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">
        {{ trans()->has('dash.'.$groups[$x]['group'])?
        trans('dash.'.$groups[$x]['group']):
        ucfirst($groups[$x]['group']) }}</h6>
      </li>
      @foreach($groups as $group)
      @if($group['displayInMenu'])
      <li class="nav-item">
        <a class="nav-link {{ request()->segment(3) == $group['resourceName']?'active':'' }}" href="{{ url($DASHBOARD_PATH.'/resource/'.$group['resourceName']) }}">
          <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
            <i class="material-icons-round {{ request()->segment(3) == $group['resourceName']?'opacity-10':'opacity-5' }}">{!! $group['icon']??'' !!}</i>
          </div>
          <span class="nav-link-text me-1 {{ app()->getLocale()=='ar'?'ps-2':'px-2' }}">
            @if(trans()->has($group['customName']))
            {{ __($group['customName']) }}
            @else
            {{ $group['customName']??ucfirst($group['group']) }}
            @endif
          </span>
        </a>
      </li>
      @endif
      @endforeach
<?php $x = 0;?>
      @endforeach
@if(isset($loadInNavigationPagesMenu['bottom']) && !empty($loadInNavigationPagesMenu['bottom']))
<!-- Pages Bottom Start  -->
@foreach($loadInNavigationPagesMenu['bottom'] as $page)
@if($page['displayInMenu'])
<li class="nav-item">
        <a class="nav-link {{ request()->segment(3) == $page['RouteName']?'active':'' }}" href="{{ url($DASHBOARD_PATH.'/page/'.$page['RouteName']) }}">
          <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
            <i class="material-icons-round {{ request()->segment(3) == $page['RouteName']?'opacity-10':'opacity-5' }}">{!! $page['icon'] !!}</i>
          </div>
          <span class="nav-link-text me-1 {{ app()->getLocale()=='ar'?'ps-2':'px-2' }}">
            @if(trans()->has($page['pageName']))
            {{ __($page['pageName']) }}
            @else
            {{ $page['pageName']??ucfirst($page['RouteName']) }}
            @endif
          </span>
        </a>
      </li>
@endif
@endforeach
<!-- Pages Bottom End-->
@endif
    </ul>
  </div>
</aside>
