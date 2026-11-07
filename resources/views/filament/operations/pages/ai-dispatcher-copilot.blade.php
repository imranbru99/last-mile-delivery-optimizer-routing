<x-filament-panels::page>
    <div class="space-y-6">
        <div class="max-h-[50vh] space-y-3 overflow-y-auto rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
            @foreach ($this->messages as $message)
                <div @class([
                    'rounded-lg px-3 py-2 text-sm',
                    'bg-primary-50 text-primary-950 dark:bg-primary-950/40' => $message['role'] === 'assistant',
                    'bg-gray-100 dark:bg-gray-800' => $message['role'] === 'user',
                ])>
                    <span class="text-xs font-semibold uppercase opacity-70">{{ $message['role'] }}</span>
                    <p class="mt-1 whitespace-pre-wrap">{{ $message['content'] }}</p>
                </div>
            @endforeach
        </div>

        <form wire:submit="send" class="space-y-4">
            <textarea
                wire:model="prompt"
                rows="3"
                class="w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-900"
                placeholder="Ask the dispatcher copilot..."
            ></textarea>
            <x-filament::button type="submit" icon="heroicon-o-paper-airplane">
                Send
            </x-filament::button>
        </form>
    </div>
</x-filament-panels::page>
