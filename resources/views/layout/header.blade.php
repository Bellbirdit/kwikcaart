<header class="main-header navbar">
    <div class="col-search">
        <!-- <form class="searchform">
            <div class="input-group">
                <input list="search_terms" type="text" class="form-control" placeholder="Search term" />
                <button class="btn btn-light bg" type="button"><i class="material-icons md-search"></i></button>
            </div>
            <datalist id="search_terms">
                <option value="Products"></option>
                <option value="New orders"></option>
                <option value="Apple iphone"></option>
                <option value="Ahmed Hassan"></option>
            </datalist>
        </form> -->
    </div>
    <div class="col-nav">
        <button class="btn btn-icon btn-mobile me-auto" data-trigger="#offcanvas_aside"><i
                class="material-icons md-apps"></i></button>
        <ul class="nav">
            <!--<li class="nav-item">-->
            <!--    <a class="nav-link btn-icon" href="#">-->
            <!--        <i class="material-icons md-notifications animation-shake"></i>-->
            <!--        <span class="badge rounded-pill">0</span>-->
            <!--    </a>-->
            <!--</li>-->

            @php
                if(Auth::user()->hasRole('Store')){
                    $store_id = Auth()->user()->id;
                    
                
                }elseif(Auth::user()->hasRole('Admin')){
                    $store_id = Auth()->user()->id;
                
                }
                $stoid = App\Models\Notification::where('notifiable_id',$store_id)->where('read_at',NULL)->count();
               
            @endphp
                <li class="dropdown nav-item main-header-notification d-flex">
                        <a class="new nav-link" data-bs-toggle="dropdown" href="javascript:void(0);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" width="24" height="24"
                                viewBox="0 0 24 24">
                                <path
                                    d="M19 13.586V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v3.586l-1.707 1.707A.996.996 0 0 0 3 16v2a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2a.996.996 0 0 0-.293-.707L19 13.586zM19 17H5v-.586l1.707-1.707A.996.996 0 0 0 7 14v-4c0-2.757 2.243-5 5-5s5 2.243 5 5v4c0 .266.105.52.293.707L19 16.414V17zm-7 5a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22z" />
                            	</svg><span class="badge rounded-pill" id="unread">{{$stoid}}</span>
                        </a>
                        <div class="dropdown-menu notifications" style="min-width: 18rem; padding: 15px;">
                            <div class="menu-header-content text-start border-bottom">
                                <div class="d-flex">
                                    <h6 class="dropdown-title mb-1 tx-15 font-weight-semibold">Notifications</h6>
                                    <span class="badge badge-pill badge-success ms-auto my-auto float-end"><a
                                            href="{{ url('notifications') }}">View all </a></span>
                                    <!-- <span class="badge badge-pill badge-warning ms-auto my-auto float-end">Mark All Read</span> -->
                                </div>
                                <p class="dropdown-title-text subtext mb-0 op-6 pb-0 tx-12  " id="notification_data">You
                                    have no new Notifications</p>
                            </div>
                            <div class="main-notification-list Notification-scroll">
                                <!-- controller -->
                            </div>
                            <div class="dropdown-footer">
									<a class="btn  btn-sm btn-block" href="{{ url('notifications') }}">VIEW ALL</a>
								</div>
                        </div>
                    </li>

            <li class="nav-item">
                <a class="nav-link btn-icon darkmode" href="#"> <i class="material-icons md-nights_stay"></i> </a>
            </li>

            <li class="dropdown nav-item">
                <a class="" href="{{ url('/') }}" id="dropdownLanguage" aria-expanded="false"><i
                        class="material-icons md-public"></i></a>

            </li>
            <li class="dropdown nav-item">
                @if(auth()->user()->avatar == '')
                    <a class="dropdown-toggle" data-bs-toggle="dropdown" href="#" id="dropdownAccount"
                        aria-expanded="false"> <img class="img-xs rounded-circle"
                            src="{{ asset('assets/imgs/people/avatar-2.png') }}"
                            alt="User" /></a>
                @else
                    <a class="dropdown-toggle" data-bs-toggle="dropdown" href="#" id="dropdownAccount"
                        aria-expanded="false"> <img class="img-xs "
                            src="{{ asset('/uploads/files/'.auth()->user()->avatar) }}"
                            alt="User" /></a>
                @endif
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownAccount">
                    <a class="dropdown-item" href="{{ url('/profile/v/') }}"><i
                            class="material-icons md-perm_identity"></i>Profile</a>

                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="{{ route('login') }}"><i
                            class="material-icons md-exit_to_app"></i>Logout</a>
                </div>
            </li>
        </ul>
    </div>
</header>
