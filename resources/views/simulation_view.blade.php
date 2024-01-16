@extends('app')

@section('content')
    <div class="container mx-auto p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Team Standings -->
            <div>
                <h2 class="text-xl font-bold mb-4 text-center">Team Standings</h2>
                <table class="min-w-full table-auto">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Team Name</th>
                            <th class="px-4 py-2">P</th>
                            <th class="px-4 py-2">W</th>
                            <th class="px-4 py-2">D</th>
                            <th class="px-4 py-2">L</th>
                            <th class="px-4 py-2">GD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teamStandings as $teamStanding)
                            <tr>
                                <td class="border px-4 py-2">{{ $teamStanding?->name }}</td>
                                <td class="border px-4 py-2">{{ $teamStanding?->standing?->played ?? 0 }}</td>
                                <td class="border px-4 py-2">{{ $teamStanding?->standing?->won ?? 0 }}</td>
                                <td class="border px-4 py-2">{{ $teamStanding?->standing?->drawn ?? 0 }}</td>
                                <td class="border px-4 py-2">{{ $teamStanding?->standing?->lost ?? 0 }}</td>
                                <td class="border px-4 py-2">{{ $teamStanding?->standing?->goal_difference ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- This Week's Fixtures -->
            <div>
                <h2 class="text-xl font-bold mb-4 text-center">This Week's Fixtures</h2>

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

                    @break;
                @endforeach
                <!-- Fixtures list -->
            </div>

            <!-- Championship Predictions -->
            <div>
                <h2 class="text-xl font-bold mb-4 text-center">Championship Predictions</h2>
                <table class="min-w-full table-auto">
                    <thead>
                        <tr>
                            <td class="px-4 py-2">Championship Prodictions</td>
                            <td class="px-4 py-2">%</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($championshipPredictions as $championshipPrediction)
                            <tr>
                                <td class="border px-4 py-2">{{ $championshipPrediction?->name }}</td>
                                <td class="border px-4 py-2">{{ $championshipPrediction?->championshipPrediction?->championship_probability ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <!-- Control Buttons -->
            <a href="#" class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 text-center">Play All Weeks</a>
            <a href="#" class="inline-block px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700 text-center">Play Next Week</a>
            <a href="#" class="inline-block px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700 text-center">Reset Data</a>
        </div>
    </div>
@endsection

