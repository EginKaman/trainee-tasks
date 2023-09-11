<button class="btn btn-default" onclick="stopWorker('{{ route('workers.update', $id) }}', '{{ $name }}')" @if($status !== \App\Enum\WorkerStatus::InWork->value) disabled="" @endif>Stop</button>
<a href="{{ route('workers.show', $id) }}" class="btn btn-default">Show logs</a>

