@php
    Helper::loadPermission();
    
    $secondParam = Request::segment(2);
    $thirdParam = Request::segment(3);
@endphp
<div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">
    <!--begin::Brand-->
    <div class="brand flex-column-auto " id="kt_brand">
        <!--begin::Logo-->
        <a href="{{ route('admin.dashboard') }}" class="brand-logo">
            <img alt="Logo" src="{{ asset('swabalamban/images/logo.svg') }}" />
        </a>
        <!--end::Logo-->

        <!--begin::Toggle-->
        <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
            <span class="svg-icon svg-icon svg-icon-xl">
                <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Angle-double-left.svg--><svg
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                    height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <path
                            d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z"
                            fill="#000000" fill-rule="nonzero"
                            transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) " />
                        <path
                            d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z"
                            fill="#000000" fill-rule="nonzero" opacity="0.3"
                            transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) " />
                    </g>
                </svg>
                <!--end::Svg Icon-->
            </span></button>
        <!--end::Toolbar-->
    </div>
    <!--end::Brand-->

    <!--begin::Aside Menu-->
    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">

        <!--begin::Menu Container-->
        <div id="kt_aside_menu" class="aside-menu my-4 " data-menu-vertical="1" data-menu-scroll="1"
            data-menu-dropdown-timeout="500">
            <!--begin::Menu Nav-->
            <ul class="menu-nav ">
                <li class="menu-item menu-item {{ $secondParam == 'dashboard' ? 'menu-item-active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="menu-link"><span class="svg-icon menu-icon"><svg
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                    <path
                                        d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z"
                                        fill="#000000" fill-rule="nonzero"></path>
                                    <path
                                        d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z"
                                        fill="#000000" opacity="0.3"></path>
                                </g>
                            </svg></span> <span class="menu-text">Dashboard</span></a>
                </li>
                <li class="menu-item"><a href="{{ URL::to('/') }}" target="_blank" class="menu-link ">
                        <span class="svg-icon menu-icon"><svg xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <rect fill="#000000" opacity="0.3" x="4" y="4" width="8"
                                        height="16"></rect>
                                    <path
                                        d="M6,18 L9,18 C9.66666667,18.1143819 10,18.4477153 10,19 C10,19.5522847 9.66666667,19.8856181 9,20 L4,20 L4,15 C4,14.3333333 4.33333333,14 5,14 C5.66666667,14 6,14.3333333 6,15 L6,18 Z M18,18 L18,15 C18.1143819,14.3333333 18.4477153,14 19,14 C19.5522847,14 19.8856181,14.3333333 20,15 L20,20 L15,20 C14.3333333,20 14,19.6666667 14,19 C14,18.3333333 14.3333333,18 15,18 L18,18 Z M18,6 L15,6 C14.3333333,5.88561808 14,5.55228475 14,5 C14,4.44771525 14.3333333,4.11438192 15,4 L20,4 L20,9 C20,9.66666667 19.6666667,10 19,10 C18.3333333,10 18,9.66666667 18,9 L18,6 Z M6,6 L6,9 C5.88561808,9.66666667 5.55228475,10 5,10 C4.44771525,10 4.11438192,9.66666667 4,9 L4,4 L9,4 C9.66666667,4 10,4.33333333 10,5 C10,5.66666667 9.66666667,6 9,6 L6,6 Z"
                                        fill="#000000" fill-rule="nonzero"></path>
                                </g>
                            </svg></span> <span class="menu-text">View Site</span></a></li>
                <li class="menu-section ">
                    <h4 class="menu-text">Catalog</h4>
                    <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                </li>
                @can('master-policy.performArray', [['content', 'menu', 'offer', 'service', 'banner', 'popup',
                    'advertisement', 'partner'], 'view'])
                    <li class="menu-item menu-item-submenu {{ in_array($secondParam, ['contents', 'menu', 'offers', 'services', 'banner', 'popup', 'ads', 'partners']) ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="#" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><i
                                    class="menu-icon flaticon2-menu-2"></i></span> <span class="menu-text">Content
                                Management</span><i class="menu-arrow"></i></a>
                        <div class="menu-submenu"><i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">Content Management</span>
                                    </span>
                                </li>
                                @can('master-policy.perform', ['content', 'view'])
                                    <li class="menu-item {{ $secondParam == 'contents' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.contents.index') }}" class="menu-link "><span
                                                class="menu-text">Content</span></a>
                                    </li>
                                @endcan
                                @can('master-policy.perform', ['menu', 'view'])
                                    <li class="menu-item {{ $secondParam == 'menu' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.menu.index') }}" class="menu-link "><span
                                                class="menu-text">Menu</span></a>
                                    </li>
                                @endcan
                                @can('master-policy.perform', ['offer', 'view'])
                                    <li class="menu-item {{ $secondParam == 'offers' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.offers.index') }}" class="menu-link "><span
                                                class="menu-text">Offer</span></a>
                                    </li>
                                @endcan
                                @can('master-policy.perform', ['service', 'view'])
                                    <li class="menu-item {{ $secondParam == 'services' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.services.index') }}" class="menu-link "><span
                                                class="menu-text">Service</span></a>
                                    </li>
                                @endcan
                                @can('master-policy.perform', ['banner', 'view'])
                                    <li class="menu-item {{ $secondParam == 'banner' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.banner.index') }}" class="menu-link "><span
                                                class="menu-text">Banner</span></a>
                                    </li>
                                @endcan
                                @can('master-policy.perform', ['popup', 'view'])
                                    <li class="menu-item d-none{{ $secondParam == 'popup' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.popup.index') }}" class="menu-link "><span
                                                class="menu-text">Popup Notice</span></a>
                                    </li>
                                @endcan
                                @can('master-policy.perform', ['advertisement', 'view'])
                                    <li class="menu-item d-none {{ $secondParam == 'ads' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.ads.index') }}" class="menu-link "><span
                                                class="menu-text">Advertisement</span></a>
                                    </li>
                                @endcan
                                @can('master-policy.perform', ['partner', 'view'])
                                    <li class="menu-item {{ $secondParam == 'partners' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.partners.index') }}" class="menu-link "><span
                                                class="menu-text">Partner</span></a>
                                    </li>
                                @endcan
                                @can('master-policy.perform', ['body-menu', 'view'])
                                    <li class="menu-item d-none {{ $secondParam == 'body-menu' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.body-menu.index') }}" class="menu-link "><span
                                                class="menu-text">Body Menu</span></a>
                                    </li>
                                @endcan
                                @can('master-policy.perform', ['testimonial', 'view'])
                                    <li
                                        class="menu-item d-none {{ $secondParam == 'testimonials' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.testimonials.index') }}" class="menu-link "><span
                                                class="menu-text">Testimonials</span></a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('master-policy.performArray', [['account-type', 'account-type-category'], 'view'])
                    <li class="menu-item menu-item-submenu {{ in_array($secondParam, ['account-type', 'account-type-category']) ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="#" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><i
                                    class="menu-icon la la-product-hunt"></i></span> <span class="menu-text">Product
                                Management</span><i class="menu-arrow"></i></a>
                        <div class="menu-submenu"><i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">Product Management</span>
                                    </span>
                                </li>
                                @can('master-policy.perform', ['account-type', 'view'])
                                    <li class="menu-item {{ $secondParam == 'account-type' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.account-type.index') }}" class="menu-link "><span
                                                class="menu-text">Product</span></a>
                                    </li>
                                @endcan

                                @can('master-policy.perform', ['account-type-category', 'view'])
                                    <li
                                        class="menu-item {{ $secondParam == 'account-type-category' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.account-type-category.index') }}" class="menu-link "><span
                                                class="menu-text">Product Category</span></a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('master-policy.performArray', [['nav-category', 'nav'], 'view'])
                    <li class="menu-item menu-item-submenu d-none {{ in_array($secondParam, ['nav-categories', 'navs']) ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="#" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><i
                                    class="menu-icon flaticon2-gear"></i></span> <span class="menu-text">NAV</span><i
                                class="menu-arrow"></i></a>
                        <div class="menu-submenu"><i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">NAV</span>
                                    </span>
                                </li>
                                @can('master-policy.perform', ['nav', 'view'])
                                    <li class="menu-item {{ $secondParam == 'navs' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.navs.index') }}" class="menu-link "><span
                                                class="menu-text">NAV</span></a>
                                    </li>
                                @endcan
                                @can('master-policy.perform', ['nav-category', 'view'])
                                    <li class="menu-item {{ $secondParam == 'nav-categories' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.nav-categories.index') }}" class="menu-link "><span
                                                class="menu-text">Category</span></a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('master-policy.performArray', [['branch-directory'], 'view'])
                    <li class="menu-item menu-item-submenu {{ in_array($secondParam, ['atm-location', 'branch-directory']) ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="#" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><i
                                    class="menu-icon la la-code-branch"></i></span> <span class="menu-text">Office Network
                                Management</span><i class="menu-arrow"></i></a>
                        <div class="menu-submenu"><i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">Branch Management</span>
                                    </span>
                                </li>
                                @can('master-policy.perform', ['branch-directory', 'view'])
                                    <li class="menu-item {{ $secondParam == 'branch-directory' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.branch-directory.index') }}" class="menu-link "><span
                                                class="menu-text">Manages</span></a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('master-policy.performArray', [['career', 'applicant'], 'view'])
                    <li class="menu-item  menu-item-submenu {{ in_array($secondParam, ['career', 'applicant']) ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="#" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><i
                                    class="menu-icon flaticon-imac"></i></span> <span class="menu-text">Career
                                Management</span><i class="menu-arrow"></i></a>
                        <div class="menu-submenu"><i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">Career Management</span>
                                    </span>
                                </li>
                                @can('master-policy.perform', ['career', 'view'])
                                    <li class="menu-item {{ $secondParam == 'career' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.career.index') }}" class="menu-link "><span
                                                class="menu-text">Career Opening</span></a>
                                    </li>
                                @endcan
                                @can('master-policy.perform', ['applicant', 'view'])
                                    <li class="menu-item {{ $secondParam == 'applicant' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.applicant.index') }}" class="menu-link "><span
                                                class="menu-text">Applicant</span></a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('master-policy.performArray', [['gallery', 'video-links'], 'view'])
                    <li class="menu-item menu-item-submenu {{ in_array($secondParam, ['gallery', 'gallery-video']) ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="#" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><i
                                    class="menu-icon la la-images"></i></span> <span class="menu-text">Gallery
                                Management</span><i class="menu-arrow"></i></a>
                        <div class="menu-submenu"><i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">Gallery Management</span>
                                    </span>
                                </li>
                                @can('master-policy.perform', ['gallery', 'view'])
                                    <li class="menu-item {{ $secondParam == 'gallery' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.gallery.index') }}" class="menu-link "><span
                                                class="menu-text">Photo Gallery</span></a>
                                    </li>
                                @endcan
                                @can('master-policy.perform', ['video-links', 'view'])
                                    <li class="menu-item {{ $secondParam == 'gallery-video' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.gallery-video.index') }}" class="menu-link "><span
                                                class="menu-text">Video Gallery</span></a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('master-policy.performArray', [['news', 'news-category', 'press-release', 'tender-notice', 'faq',
                    'blog', 'blog-category', 'forex', 'stock-info'], 'view'])
                    <li class="menu-item menu-item-submenu {{ in_array($secondParam, ['news', 'news-categories', 'press-release', 'tender-notice', 'faq-category', 'blog', 'blog-category', 'forex', 'stock-info']) ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="#" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><i
                                    class="menu-icon flaticon2-pie-chart-3"></i></span> <span
                                class="menu-text">Information Management</span><i class="menu-arrow"></i></a>
                        <div class="menu-submenu"><i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">News</span>
                                    </span>
                                </li>
                                @can('master-policy.perform', ['forex', 'view'])
                                    <li class="menu-item d-none {{ $secondParam == 'forex' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.forex.index') }}" class="menu-link "><span
                                                class="menu-text">Forex</span></a>
                                    </li>
                                @endcan
                                @can('master-policy.perform', ['news', 'view'])
                                    <li class="menu-item {{ $secondParam == 'news' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.news.index') }}" class="menu-link "><span
                                                class="menu-text">News</span></a>
                                    </li>
                                @endcan
                                @can('master-policy.perform', ['news-category', 'view'])
                                    <li
                                        class="menu-item d-none {{ $secondParam == 'news-categories' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.news-categories.index') }}" class="menu-link "><span
                                                class="menu-text">News Category</span></a>
                                    </li>
                                @endcan
                                @can('master-policy.perform', ['press-release', 'view'])
                                    <li class="menu-item {{ $secondParam == 'press-release' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.notice.index') }}" class="menu-link "><span
                                                class="menu-text">Notices</span></a>
                                    </li>
                                @endcan

                                @can('master-policy.perform', ['faq', 'view'])
                                    <li
                                        class="menu-item d-none  {{ $secondParam == 'faq-category' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.faq-category.index') }}" class="menu-link "><span
                                                class="menu-text">FAQ</span></a>
                                    </li>
                                @endcan
                                @can('master-policy.perform', ['blog', 'view'])
                                    <li class="menu-item  {{ $secondParam == 'stories' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.stories.index') }}" class="menu-link "><span
                                                class="menu-text">Stories</span></a>
                                    </li>
                                @endcan
                                @can('master-policy.perform', ['blog-category', 'view'])
                                    <li
                                        class="menu-item d-none {{ $secondParam == 'blog-categories' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.blog-categories.index') }}" class="menu-link "><span
                                                class="menu-text">Blog Category</span></a>
                                    </li>
                                @endcan
                                @can('master-policy.perform', ['stock-info', 'view'])
                                    <li
                                        class="menu-item d-none {{ $secondParam == 'stock-watch' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.stock-watch.index') }}" class="menu-link "><span
                                                class="menu-text">Stock Watch</span></a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('master-policy.perform', ['grievance', 'view'])
                    <li class="menu-item menu-item-submenu d-none {{ in_array($secondParam, ['grievance', 'department']) ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="#" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><i
                                    class="menu-icon flaticon2-chat-1"></i></span> <span class="menu-text">Grievance
                                Handling</span><i class="menu-arrow"></i></a>
                        <div class="menu-submenu"><i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">Grievance Handling</span>
                                    </span>
                                </li>
                                <li class="menu-item {{ $secondParam == 'grievance' ? 'menu-item-active' : '' }}">
                                    <a href="{{ route('admin.grievance.index') }}" class="menu-link "><span
                                            class="menu-text">Grievance</span></a>
                                </li>
                                @can('master-policy.perform', ['department', 'view'])
                                    <li class="menu-item {{ $secondParam == 'department' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.department.index') }}" class="menu-link "><span
                                                class="menu-text">Department</span></a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('master-policy.performArray', [['download', 'download-specific-category'], 'view'])
                    <li class="menu-item menu-item-submenu {{ in_array($secondParam, ['download', 'download-category']) ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="#" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><svg
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"></rect>
                                        <path
                                            d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z"
                                            fill="#000000" opacity="0.3"></path>
                                        <path
                                            d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z"
                                            fill="#000000"></path>
                                    </g>
                                </svg></span> <span class="menu-text">Download Management</span><i
                                class="menu-arrow"></i></a>
                        <div class="menu-submenu"><i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">Download</span>
                                    </span>
                                </li>
                                <li class="menu-item {{ $secondParam == 'download' ? 'menu-item-active' : '' }}">
                                    <a href="{{ route('admin.download.index') }}" class="menu-link "><span
                                            class="menu-text">Download</span></a>
                                </li>
                                @can('master-policy.perform', ['download-category', 'view'])
                                    <li class="menu-item {{ $secondParam == 'download-category' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.download-category.index') }}" class="menu-link "><span
                                                class="menu-text">Categories</span></a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item menu-item-submenu {{ in_array($secondParam, ['internal-web', 'internal-web-category']) ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="#" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><i
                                    class="menu-icon la la-cloud-download-alt"></i></span> <span
                                class="menu-text">Internal Web</span><i class="menu-arrow"></i></a>
                        <div class="menu-submenu"><i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">internal web</span>
                                    </span>
                                </li>
                                <li class="menu-item {{ $secondParam == 'internal-web' ? 'menu-item-active' : '' }}">
                                    <a href="{{ route('admin.internal-web.index') }}" class="menu-link "><span
                                            class="menu-text">Manages</span></a>
                                </li>
                                <li
                                    class="menu-item {{ $secondParam == 'internal-web-category' ? 'menu-item-active' : '' }}">
                                    <a href="{{ route('admin.internal-web-category.index') }}" class="menu-link "><span
                                            class="menu-text">Categories</span></a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('master-policy.perform', ['financial-report', 'view'])
                    <li class="menu-item menu-item-submenu d-none {{ in_array($secondParam, ['financial-report', 'financial-report-category', 'statistics']) ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="#" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><i
                                    class="menu-icon flaticon2-gear"></i></span> <span class="menu-text">Financial
                                Report</span><i class="menu-arrow"></i></a>
                        <div class="menu-submenu"><i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">Financial Report</span>
                                    </span>
                                </li>
                                <li class="menu-item {{ $secondParam == 'financial-report' ? 'menu-item-active' : '' }}">
                                    <a href="{{ route('admin.financial-report.index') }}" class="menu-link "><span
                                            class="menu-text">Manage</span></a>
                                </li>
                                @can('master-policy.perform', ['financial-report-category', 'view'])
                                    <li
                                        class="menu-item {{ $secondParam == 'financial-report-category' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.financial-report-category.index') }}"
                                            class="menu-link "><span class="menu-text">Categories</span></a>
                                    </li>
                                @endcan
                                @can('master-policy.perform', ['statistics', 'view'])
                                    <li class="menu-item {{ $secondParam == 'statistics' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.statistics.index') }}" class="menu-link "><span
                                                class="menu-text">Statistics</span></a>
                                    </li>
                                @endcan
                                @can('master-policy.perform', ['loan-graph', 'view'])
                                    <li class="menu-item {{ $secondParam == 'loan-graph' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.loan-graph.index') }}" class="menu-link "><span
                                                class="menu-text">Loan Graph</span></a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('master-policy.perform', ['agm-report', 'view'])
                    <li class="menu-item d-none menu-item-submenu {{ in_array($secondParam, ['agm-report', 'agm-report-category']) ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="#" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><i
                                    class="menu-icon flaticon2-gear"></i></span> <span class="menu-text">AGM
                                Report</span><i class="menu-arrow"></i></a>
                        <div class="menu-submenu"><i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">AGM Report</span>
                                    </span>
                                </li>
                                <li class="menu-item {{ $secondParam == 'agm-report' ? 'active' : '' }}">
                                    <a href="{{ route('admin.agm-report.index') }}" class="menu-link "><span
                                            class="menu-text">Manage</span></a>
                                </li>
                                <!-- @can('master-policy.perform', ['agm-report-category', 'view'])
        <li class="menu-item {{ $secondParam == 'agm-report-category' ? 'active' : '' }}">
                                                                                <a href="{{ route('admin.agm-report-category.index') }}" class="menu-link "><span class="menu-text">Categories</span></a>
                                                                            </li>
    @endcan -->
                            </ul>
                        </div>
                    </li>
                @endcan


                @can('master-policy.performArray', [['syllabus'], 'view'])
                    <li class="menu-item d-none menu-item-submenu {{ in_array($secondParam, ['syllabus']) ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="#" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><i
                                    class="menu-icon flaticon2-gear"></i></span> <span class="menu-text">Syllabus
                                Management</span><i class="menu-arrow"></i></a>
                        <div class="menu-submenu"><i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">Syllabus</span>
                                    </span>
                                </li>
                                @can('master-policy.perform', ['syllabus', 'view'])
                                    <li class="menu-item {{ $secondParam == 'syllabus' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.syllabus.index') }}" class="menu-link "><span
                                                class="menu-text">Syllabus</span></a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan

                @can('master-policy.performArray', [['contact', 'email-subscribe'], 'view'])
                    <li class="menu-item menu-item-submenu {{ in_array($secondParam, ['contact', 'email-subscribe']) ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="#" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><span
                                    class="svg-icon svg-icon-primarsy svg-icon-2x">
                                    <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Communication/Readed-mail.svg--><svg
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <path
                                                d="M4.875,20.75 C4.63541667,20.75 4.39583333,20.6541667 4.20416667,20.4625 L2.2875,18.5458333 C1.90416667,18.1625 1.90416667,17.5875 2.2875,17.2041667 C2.67083333,16.8208333 3.29375,16.8208333 3.62916667,17.2041667 L4.875,18.45 L8.0375,15.2875 C8.42083333,14.9041667 8.99583333,14.9041667 9.37916667,15.2875 C9.7625,15.6708333 9.7625,16.2458333 9.37916667,16.6291667 L5.54583333,20.4625 C5.35416667,20.6541667 5.11458333,20.75 4.875,20.75 Z"
                                                fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                            <path
                                                d="M12.9835977,18 C12.7263047,14.0909841 9.47412135,11 5.5,11 C4.98630124,11 4.48466491,11.0516454 4,11.1500272 L4,7 C4,5.8954305 4.8954305,5 6,5 L20,5 C21.1045695,5 22,5.8954305 22,7 L22,16 C22,17.1045695 21.1045695,18 20,18 L12.9835977,18 Z M19.1444251,6.83964668 L13,10.1481833 L6.85557487,6.83964668 C6.4908718,6.6432681 6.03602525,6.77972206 5.83964668,7.14442513 C5.6432681,7.5091282 5.77972206,7.96397475 6.14442513,8.16035332 L12.6444251,11.6603533 C12.8664074,11.7798822 13.1335926,11.7798822 13.3555749,11.6603533 L19.8555749,8.16035332 C20.2202779,7.96397475 20.3567319,7.5091282 20.1603533,7.14442513 C19.9639747,6.77972206 19.5091282,6.6432681 19.1444251,6.83964668 Z"
                                                fill="#000000" />
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span></span> <span class="menu-text">Report</span><i class="menu-arrow"></i></a>
                        <div class="menu-submenu"><i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">Report</span>
                                    </span>
                                </li>
                                @can('master-policy.perform', ['contact', 'view'])
                                    <li class="menu-item {{ $secondParam == 'contact' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.contact.index') }}" class="menu-link "><span
                                                class="menu-text">Contact</span></a>
                                    </li>

                                    <li
                                        class="menu-item d-none {{ $secondParam == 'jyoti-care' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.jyoti-care.index') }}" class="menu-link "><span
                                                class="menu-text">Jyoti Care</span></a>
                                    </li>
                                @endcan

                                @can('master-policy.perform', ['email-subscribe', 'view'])
                                    <li class="menu-item {{ $secondParam == 'email-subscribe' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.email-subscribe.index') }}" class="menu-link "><span
                                                class="menu-text">Email Subscribe</span></a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('master-policy.performArray', [['team', 'team-category'], 'view'])
                    <li class="menu-item menu-item-submenu {{ in_array($secondParam, ['team', 'team-category']) ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="#" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><i
                                    class="menu-icon flaticon2-browser-1"></i></span> <span class="menu-text">Management
                                Team</span><i class="menu-arrow"></i></a>
                        <div class="menu-submenu"><i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">Management Team</span>
                                    </span>
                                </li>
                                <li class="menu-item {{ $secondParam == 'team' ? 'menu-item-active' : '' }}">
                                    <a href="{{ route('admin.team.index') }}" class="menu-link "><span
                                            class="menu-text">Manage</span></a>
                                </li>
                                @can('master-policy.perform', ['team-category', 'view'])
                                    <li class="menu-item {{ $secondParam == 'team-category' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.team-category.index') }}" class="menu-link "><span
                                                class="menu-text">Categories</span></a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan

                @can('master-policy.performArray', [['interest-rate'], 'view'])
                    <li class="menu-item d-none menu-item-submenu {{ in_array($secondParam, ['interest-rates']) ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="#" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><i
                                    class="menu-icon flaticon2-gear"></i></span> <span class="menu-text">Interest Rates
                                Management</span><i class="menu-arrow"></i></a>
                        <div class="menu-submenu"><i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">Interest Rates Management</span>
                                    </span>
                                </li>
                                @can('master-policy.perform', ['interest-rate', 'view'])
                                    <li class="menu-item {{ $secondParam == 'account-type' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.interest-rates.index') }}" class="menu-link "><span
                                                class="menu-text">Interest Rates</span></a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan

                @can('master-policy.performArray', [['check-bank-guarantee'], 'view'])
                    <li class="menu-item d-none menu-item-submenu {{ in_array($secondParam, ['check-bank-guarantee']) ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="#" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><i
                                    class="menu-icon flaticon2-gear"></i></span> <span class="menu-text">Check Bank
                                Guarantee</span><i class="menu-arrow"></i></a>
                        <div class="menu-submenu"><i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">Check Bank Guarantee</span>
                                    </span>
                                </li>
                                <li
                                    class="menu-item {{ $secondParam == 'check-bank-guarantee' ? 'menu-item-active' : '' }}">
                                    <a href="{{ route('admin.check-bank-guarantee.index') }}" class="menu-link "><span
                                            class="menu-text">Manage</span></a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('master-policy.performArray', [['bonus', 'bonus-category'], 'view'])
                    <li class="menu-item d-none menu-item-submenu {{ in_array($secondParam, ['bonus', 'bonus-category']) ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="#" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><i
                                    class="menu-icon flaticon2-gear"></i></span> <span class="menu-text">Bonus Share
                                Management</span><i class="menu-arrow"></i></a>
                        <div class="menu-submenu"><i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">Bonus Share Management</span>
                                    </span>
                                </li>
                                <li class="menu-item {{ $secondParam == 'bonus' ? 'menu-item-active' : '' }}">
                                    <a href="{{ route('admin.bonus.index') }}" class="menu-link "><span
                                            class="menu-text">Manage</span></a>
                                </li>
                                @can('master-policy.perform', ['bonus-category', 'view'])
                                    <li class="menu-item {{ $secondParam == 'bonus-category' ? 'menu-item-active' : '' }}">
                                        <a href="{{ route('admin.bonus-category.index') }}" class="menu-link "><span
                                                class="menu-text">Categories</span></a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('master-policy.performArray', [['layout', 'site-setting'], 'view'])
                    <li class="menu-section ">
                        <h4 class="menu-text">Configuration</h4>
                    </li>

                    @can('master-policy.perform', ['layout', 'view'])
                        <li class="menu-item {{ $secondParam == 'layout' ? 'menu-item-active' : '' }}">
                            <a href="{{ route('admin.layout.index') }}" class="menu-link "><span
                                    class="svg-icon menu-icon">
                                    <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Design/PenAndRuller.svg--><svg
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <path
                                                d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z"
                                                fill="#000000" opacity="0.3" />
                                            <path
                                                d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z"
                                                fill="#000000" />
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span> <span class="menu-text">Layout</span></a>
                        </li>
                    @endcan
                    @can('master-policy.perform', ['site-setting', 'view'])
                        <li class="menu-item {{ $secondParam == 'layout' ? 'menu-item-active' : '' }}">
                            <a href="{{ route('admin.setting.index') }}" class="menu-link "><span
                                    class="svg-icon menu-icon"><i class="menu-icon flaticon2-console"></i></span><span
                                    class="menu-text">Setting</span></a>
                        </li>
                    @endcan
                @endcan
                @if (!Auth::guest() && Auth::user()->admin_type_id == 1)
                    <li class="menu-item menu-item-submenu menu-item {{ $secondParam == 'module' || $secondParam == 'admin-type' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="#" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><i
                                    class="menu-icon flaticon2-group"></i></span> <span class="menu-text">User
                                Management</span><i class="menu-arrow"></i></a>
                        <div class="menu-submenu"><i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">User Management</span>
                                    </span>
                                </li>
                                <li
                                    class="menu-item {{ $secondParam == 'module' ? 'menu-item-active' : '' }} d-none">
                                    <a href="{{ route('admin.module.index') }}" class="menu-link "><span
                                            class="menu-text">Module</span></a>
                                </li>
                                <li class="menu-item {{ $secondParam == 'admin-type' ? 'menu-item-active' : '' }}">
                                    <a href="{{ route('admin.admin-type.index') }}" class="menu-link "><span
                                            class="menu-text">Role</span></a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
            </ul>
            <!--end::Menu Nav-->
        </div>
        <!--end::Menu Container-->
    </div>
    <!--end::Aside Menu-->
</div>
<!--end::Aside-->
