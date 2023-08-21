@foreach($getRecord()->tourguides as $tourguide)
    <div class="mt-2 px-4 py-3 bg-gray-100 rounded-lg w-full">
        {{-- 2 Columns on the left it's name of the tourguide and on the right it's the price --}}
        <div class="flex justify-between">
            <div class="flex">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full" src="{{ $tourguide->avatar }}"
                         alt="{{ $tourguide->full_name }}">
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">
                        {{ $tourguide->full_name }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $tourguide->email }}
                    </div>
                </div>
            </div>
            <div class="my-auto">
                <span class="text-sm">Tourguide Status:</span>
                <span @class([
                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full ',
                    'bg-green-100 text-green-800' => $tourguide->pivot->status === 'approved',
                    'bg-red-100 text-red-800'     => in_array($tourguide->pivot->status, ['declined', 'rejected']),
                    'bg-warning-100 text-warning-800' => $tourguide->pivot->status === 'pending',
                ])>
                    {{ ucwords($tourguide->pivot->status) }}
                </span>
                <span class="text-sm">Agent Status:</span>
                <span @class([
                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full ',
                    'bg-green-100 text-green-800' => $tourguide->pivot->status === 'approved',
                    'bg-red-100 text-red-800'     => in_array($tourguide->pivot->status, ['declined', 'rejected']),
                    'bg-warning-100 text-warning-800' => $tourguide->pivot->status === 'pending',
                ])>
                    {{ ucwords($tourguide->pivot->agent_status) }}
                </span>
            </div>
        </div>
    </div>
@endforeach
