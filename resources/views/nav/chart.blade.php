<div class="graphsearch">
    <form method="get" action="{{ route('nav.index') }}">
        <div class="graph-div">
            <span>Scheme</span>
            <select id="selectScheme" name="category">
                @if(isset($categories) && !empty($categories))
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request()->has('category') && $category->id == request()->get('category') ? 'selected' : '' }}>{{ $category->title }}</option>
                @endforeach
                @endif
            </select>
        </div>

        <div class="graph-div">
            <span>Year</span>
            <select id="selectScheme" name="year">
                <option value="">All</option>
                @for($y = date('Y'); $y >= 2016; $y--) <option value="{{ $y }}" {{ request()->has('year') && $y ==request()->get('year') ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>

        <div class="graph-div">
            <span>Type</span>
            <select id="selectScheme" name="type">
                <option value="1" {{ request()->has('type') && request()->get('type') == 1 ? 'selected' : '' }}>Weekly</option>
                <option value="2" {{ request()->has('type') && request()->get('type') == 2 ? 'selected' : '' }}>Monthly</option>
            </select>
        </div>
        <div class="graph-div">
            <button>Search</button>
        </div>

    </form>
</div>

<div id="chartContainer" style="height: 400px; width: 100%;"></div>