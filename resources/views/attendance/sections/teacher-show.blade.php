@php
    use Illuminate\Support\Str;
    $role = auth()->user()->userable_type;
    $PATH = env('AWS_URL');
    $totalSubmissions = count($attendanceSubmissions);
    $presentCount = $attendanceSubmissions->where('status', 'present')->count();
    $attendanceRate = $totalSubmissions > 0 ? ($presentCount / $totalSubmissions) * 100 : 0;
@endphp
@props(['attendanceSubmissions'])

<div class="my-4">
    <strong>Attendance Rate: </strong> {{ number_format($attendanceRate, 2) }}%
</div>

<table class="table table-zebra">
    <thead>
        <tr>
            <th>Student</th>
            <th>Attendance</th>
            <th>Attends At</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($attendanceSubmissions as $item)
            <tr>
                <td class="flex gap-2 items-center">
                    <img src="
                        @if ($item->student->user->profile_photo) {{ $PATH . $item->student->user->profile_photo }}                        
                        @else
                             https://ui-avatars.com/api/?name={{ $item->student->user->name }}&color=7F9CF5&background=EBF4FF
                        @endif
                        "
                        class="rounded-full w-8 h-8 mr-2" alt="">
                    {{ $item->student->user->name }}
                </td>
                <td>
                    @if ($item->status == 'present')
                        <span class="text-green-500">Present</span>
                    @elseif ($item->status == 'late')
                        <span class="text-yellow-500">Late</span>
                    @else
                        <span class="text-red-500">Absent</span>
                    @endif
                </td>
                <td>{{ $item->updated_at->format('d M Y, H:i') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No attendance records found.</td>
            </tr>
        @endforelse
    </tbody>
</table>