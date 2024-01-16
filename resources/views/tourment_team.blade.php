@extends('app')

@section('content')
    <div class="container mx-auto my-8">
        <h1 class="text-3xl font-bold text-center mb-6">Tournament Teams</h1>

        <ul class="max-w-md mx-auto bg-white rounded-lg border border-gray-200">
            @foreach ($teams as $team)
                <li class="px-6 py-4 border-b border-gray-200">{{ $team->name }} ({{ $team->team_stadium }})</li>
            @endforeach
        </ul>

        <div class="text-center mt-6">
            <form action="{{ url('/generate-fixtures') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Generate Fixtures</button>
            </form>
        </div>
    </div>
@endsection