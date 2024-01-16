@extends('app')

@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-xl font-bold mb-4">Generated Fixtures</h2>
        @foreach ($weeks as $week => $matches)
            <div class="mb-6">
                <div class="bg-black text-white p-2">
                    <h3>Week {{ $week }}</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($matches as $match)
                        <div class="border p-4">
                            <p>{{ $match->homeTeam()->first()->name }} vs {{ $match->awayTeam()->first()->name }}</p>
                            <p>Date: {{ \Carbon\Carbon::parse($match->match_date)->toFormattedDateString() }}</p>
                            <p>Location: {{ $match->location }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="text-center mt-6">
            <a href="{{ route('simulation') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                Start Simulation
            </a>
        </div>
    </div>
@endsection
