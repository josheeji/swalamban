      <!-- SEARCH BOX -->
      <div class="close-btn">
          <span class="fas fa-times"></span>
      </div>
      <div class="searchwrapper">
          <div class="search-data">
              <input type="text" id="search-keyword" required>
              <div class="line"></div>
              <label>Type to search.. </label>
              <span class="fas fa-search search-button"></span>
          </div>
      </div>
      <!-- SEARCH BOX END -->
      <!-- HEADER-TOPBAR--->
      <section id="header-topbar">
          <div class="container">
              <div class="row">
                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 ">
                      <div class="top-leftmenu">
                          <ul>
                              <li>
                                  @php
                                      $nepDateApi = new \NepaliDateApi();
                                      $nepaliDate = $nepDateApi->eng_to_nep(date('Y', strtotime(now())), date('m', strtotime(now())), date('d', strtotime(now())), true);
                                  @endphp
                                  <a href="Date: {{ date('M d, Y', strtotime(now())) }} {{ isset($nepaliDate) ? '(' . $nepaliDate['nmonth'] . ' ' . $nepaliDate['date'] . ',' . $nepaliDate['year'] . ')' : '' }}"
                                      class="">
                                      <i class="fal fa-calendar"></i> {{ date('M d, Y', strtotime(now())) }}
                                      {{ isset($nepaliDate) ? '(' . $nepaliDate['nmonth'] . ' ' . $nepaliDate['date'] . ',' . $nepaliDate['year'] . ')' : '' }}</a>
                              </li>
                              <li>
                                  {{-- <a href="tel- {!! SettingHelper::multiLangSetting('contact') ?? null !!}" class=""> --}}
                                  <i class="far fa-phone-alt"></i> {!! SettingHelper::multiLangSetting('contact') ?? null !!}
                                  {{-- </a> --}}
                              </li>
                          </ul>
                      </div>
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-8">
                      <div class="top-rightmenu">
                          <ul class="rightmenu-bar">
                              @if (isset($topMenuItems) && !empty($topMenuItems))

                                  @foreach ($topMenuItems['parent'] as $key => $item)
                                      <li><a href="{!! $item['url'] !!}"
                                              {!! $item['target'] !!}>{!! $item['title'] !!}</a>
                                      </li>
                                  @endforeach
                              @endif

                          </ul>
                          <div class="right-corner">
                              <div class="menu-line">
                                  <a href="javascript:void(0);" class="search-btn">
                                      <i class="far fa-search"></i> Search </a>
                              </div>

                              <ul>
                                  <li class="lan-switch-dropdown">
                                      <select id="languageLink" class="" required="">
                                          <option value="en"
                                              {{ session()->get('locale_code') == 'en' ? 'selected' : '' }}> EN
                                          </option>
                                          <option value="np"
                                              {{ session()->get('locale_code') == 'np' ? 'selected' : '' }}> NP
                                          </option>
                                      </select>
                                  </li>
                              </ul>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          </div>
      </section>
      <!-- HEADER-TOPBAR- END-->

      <!-- NAVIK-HEADER-->
      <section id="header">
          <div class="navik-header header-shadow">
              <div class="container">
                  <!-- Navik header -->
                  <div class="navik-header-container">
                      <!--Logo-->
                      <div class="logo" data-mobile-logo="{{ asset('swabalamban/images/websitelogo.svg') }}"
                          data-sticky-logo="{{ asset('swabalamban/images/websitelogo.svg') }}">
                          <a href="{{ route('home.index') }}" class="">
                              <img src="{{ asset('swabalamban/images/websitelogo.svg') }}" alt="logo" />
                          </a>
                      </div>
                      <!-- Burger menu -->
                      <div class="burger-menu">
                          <div class="line-menu line-half first-line"></div>
                          <div class="line-menu"></div>
                          <div class="line-menu line-half last-line"></div>
                      </div>

                      <!--Navigation menu-->
                      <nav class="navik-menu menu-caret submenu-top-border submenu-scale">
                          @if (isset($menuItems) && !empty($menuItems) && isset($menuItems['parent']) && !empty($menuItems['parent']))
                              <ul>
                                  {{--
                                  <li class="current-menu">
                                      <a href="{{ url('/') }}">Home </a>
                                  </li> --}}
                                  @foreach ($menuItems['parent'] as $key => $item)
                                      <li class="submenu-right">
                                          <a href="{!! $item['url'] !!}"
                                              {!! $item['target'] !!}>{!! $item['title'] !!}</a>
                                          @if (isset($menuItems['child']) && array_key_exists($key, $menuItems['child']))
                                              <ul>
                                                  @foreach ($menuItems['child'][$key] as $lvl2 => $item2)
                                                      <li>
                                                          <a href="{!! $item2['url'] !!}"
                                                              {!! $item2['target'] !!}>{!! $item2['title'] !!}</a>
                                                      </li>
                                                  @endforeach
                                              </ul>
                                          @endif
                                      </li>
                                  @endforeach

                              </ul>
                          @endif
                      </nav>
                  </div>
              </div>
          </div>
      </section>
      <!-- NAVIK-HEADER-- END-->
