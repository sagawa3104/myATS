@if (session('success'))
    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{-- session('success') = [title, text] --}}
        @if (is_array(session('success')))
        <h5>{{ session('success')[0] }}</h5>
        {{ session('success')[1] }}
        @else {{-- session('success') = title --}}
        <h5>{{ session('success') }}</h5>
        @endif
    </div>
@endif
@if (session('failure'))
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{-- session('failure') = [title, text] --}}
        @if (is_array(session('failure')))
        <h5><i class="icon fas fa-exclamation-triangle"></i>{{ session('failure')[0] }}</h5>
        {{ session('failure')[1] }}
        @else {{-- session('failure') = title --}}
        <h5><i class="icon fas fa-exclamation-triangle"></i>{{ session('failure') }}</h5>
        @endif
    </div>
@endif
@if (session('import_results'))
    <div class="alert alert-warning alert-dismissible overflow-auto">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{-- session('failure') = [title, text] --}}
        @if (is_array(session('import_results')))
            @foreach (session('import_results') as $message)
            <h5><i class="icon fas fa-exclamation-triangle"></i>{{ $message }}</h5>
            @endforeach
        @endif
    </div>
@endif