@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <h2 class="font-bold text-[#5C6AC4] mb-6">Project Progress</h2>
        <table class="w-full border-collapse border border-gray-300 text-center">
            <tr class="bg-gray-200">
                <th class="border p-2">No</th>
                <th class="border p-2">Kelompok</th>
                <th class="border p-2">Link Laporan</th>
                <th class="border p-2">Link PPT</th>
            </tr>
            @foreach($groups as $index => $group)
            @php $isMyGroup = $group->members->contains('user_id', Auth::id()); @endphp
            <tr>
                <td class="border p-2">{{ $index + 1 }}</td>
                <td class="border p-2 font-bold">{{ $group->name }}</td>
                <td class="border p-2">
                    @if($isMyGroup)
                        <form action="{{ route('groups.updateLinks', $group->id) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="url" name="link_report" value="{{ $group->link_report }}" class="border p-1 w-full text-xs">
                    @else
                        @if($group->link_report) <a href="{{ $group->link_report }}" target="_blank">🔗</a> @else - @endif
                    @endif
                </td>
                <td class="border p-2">
                    @if($isMyGroup)
                            <input type="url" name="link_ppt" value="{{ $group->link_ppt }}" class="border p-1 w-full text-xs">
                            <button type="submit" class="bg-blue-500 text-white px-2 py-1 text-[10px] mt-1 rounded">Simpan</button>
                        </form>
                    @else
                        @if($group->link_ppt) <a href="{{ $group->link_ppt }}" target="_blank">🔗</a> @else - @endif
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
    </div>
@endsection