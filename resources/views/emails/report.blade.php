@forelse ($logs as $log)
    <li>{{ $log['message'] }}: {{ $log['count'] }}</li>
@empty
    {{ $text }}
@endforelse
