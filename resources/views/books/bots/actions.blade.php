<a href="{{ route('bots.jobs.create', ['bot' => $id]) }}" class="btn btn-primary">Create a job</a>
<a href="{{ route('bots.jobs.index', $id) }}" class="btn btn-primary">Jobs</a>
<button class="btn" onclick="deleteBot('{{ route('bots.destroy', $id) }}', '{{ $title }}')"><i class="fa fa-trash"></i></button>
