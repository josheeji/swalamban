    @if ($other->count())
        <div class="latestnews-events">
            <div class="cns-title">
                <a href="javascript:void(0);" class="">{{ trans('general.otherNews') }}
                    {{ trans('general.product-and-services') }}
                </a>
            </div>
            <ul class="list-categories ">
                @foreach ($other as $item)
                    <li>
                        <a href="{{ route('product.show', ['slug' => $item->slug]) }}"
                            class="">
                            {{ $item->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="latest-notice">
        <div class="cns-title">
            <a href="javascript:void(0);" class="">{{ trans('general.latest-notice') }}
            </a>
        </div>
        @foreach ($notices as $item)
            <div class="notice-1">

                <div class="notice-date">
                    @isset($item->end_date)
                        <span>Expires On {{ date('d M, Y', strtotime($item->end_date)) }}
                        </span>
                    @endisset
                    {{-- <a href="{{ asset('storage/' . $item->link) }}"
                        class="time-update"> --}}
                        {{ $item->start_date->diffForHumans() }}
                    {{-- </a> --}}
                </div>
                <div class="notice">
                    <a href="{{ asset('storage/' . $item->link) }}" class="">{{ $item->title }}
                    </a>
                    @if (now()->subDays(7)->format('y-m-d') <= $item->start_date->format('y-m-d'))
                        <span>New
                        </span>
                    @endif

                </div>
                <div class="dotted">
                </div>
            </div>
        @endforeach
    </div>
