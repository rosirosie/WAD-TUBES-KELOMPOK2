@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <h2 class="font-bold text-[#5C6AC4] mb-6">Master Group Directory: {{ $courseName }}</h2>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">No</th>
                    <th class="border p-2">Kelompok</th>
                    <th class="border p-2">Ketua</th>
                    <th class="border p-2">Anggota</th>
                    <th class="border p-2">Tema</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupData as $group)
                    @foreach($group['members'] as $index => $member)
                    <tr>
                        @if($index === 0)
                            <td class="border p-2 text-center" rowspan="{{ count($group['members']) }}">{{ $group['no'] }}</td>
                            <td class="border p-2" rowspan="{{ count($group['members']) }}">{{ $group['name'] }}</td>
                            <td class="border p-2" rowspan="{{ count($group['members']) }}">{{ $group['leader'] }}</td>
                        @endif
                        <td class="border p-2">{{ $member }}</td>
                        @if($index === 0)
                            <td class="border p-2 text-center" rowspan="{{ count($group['members']) }}">{{ $group['theme'] }}</td>
                        @endif
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
@endsection