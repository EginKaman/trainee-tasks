<button class="btn" onclick="editJob('{{ route('jobs.update', $id) }}', '{{ $name }}', '{{ $type }}', {{ $workers_count }}, '{{ $cron }}')"><i class="fa fa-edit"></i></button>
<a href="{{ route('jobs.workers.index', $id) }}" class="btn"><i class="fa fa-eye"></i></a>
<button class="btn" onclick="deleteJob('{{ route('jobs.destroy', $id) }}', '{{ $name }}')"><i class="fa fa-trash"></i></button>
{{--<form action="{{ route('jobs.destroy', $id) }}" method="POST" style="display: inline-block">--}}
{{--    @csrf--}}
{{--    @method('DELETE')--}}
{{--    <button class="btn"><i class="fa fa-trash"></i></button>--}}
{{--</form>--}}
