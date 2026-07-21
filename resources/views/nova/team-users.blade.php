<div class="space-y-2">
    @foreach($users as $user)
        <div class="flex items-center space-x-2">
            <div>
                <div class="text-sm font-medium">{{ $user->name }}</div>
                <div class="text-xs text-gray-500">{{ $user->email }}</div>
            </div>
        </div>
        <hr>
    @endforeach
</div>