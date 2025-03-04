<head>
    <title>{{ env('APP_NAME') }} | DTR | Edit History</title>
</head>

<x-main-layout breadcumb="DTR / History" page="Edit History">

    @if (session('success'))
        <x-modal.flash-msg msg="success" />
    @elseif (session('error'))
        <x-modal.flash-msg msg="error" />
    @endif

    <div class="h-auto w-full flex flex-col gap-5 px-10 pt-10">
        {{-- Edit Form for a Single History Entry --}}
        <form id="historyForm" action="{{ route('admin.dtr.history.edit.post', ['id' => $history->id]) }}" method="POST"
            class="bg-white p-6 rounded border-l-8 border-[#F57D11] shadow-md">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dtr.history') }}"
                    class="border hover:border-[#f56d11] text-[#f56d11] transition flex items-center gap-1 px-3 py-2 text-sm rounded font-semibold w-fit">
                    <span class="eva--arrow-back-fill w-4 h-4"></span>
                    Back
                </a>
                <button type="submit"
                    class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit">
                    Save Changes
                </button>
            </div>

            <div class="user-history-card bg-white relative mt-10">
                <div class="flex items-center gap-4">
                    <img src="{{ \App\Models\File::where('id', \App\Models\Profile::where('id', $history->user->profile_id)->first()->file_id)->first()->path . '?t=' . time() ?? 'https://via.placeholder.com/50' }}"
                        class="w-12 h-12 rounded-full" alt="{{ $history->user->firstname }}">
                    <div>
                        <h3 class="text-lg font-semibold text-[#F57D11] capitalize">{{ $history->user->firstname }}
                            {{ $history->user->lastname }}</h3>
                        <p class="text-sm text-gray-600">{{ $history->user->email }}</p>
                    </div>
                </div>

                <div class="history-container mt-3 flex items-end gap-5">
                    <section class="space-y-2 w-full">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <select name="history_description"
                            class="w-full p-2 border rounded focus:ring-[#F57D11] focus:border-[#F57D11]" required>
                            <option value="Time In" {{ $history->description == 'Time In' ? 'selected' : '' }}>Time In
                            </option>
                            <option value="Time In | Late"
                                {{ $history->description == 'Time In | Late' ? 'selected' : '' }}>Time In | Late
                            </option>
                            <option value="Time Out" {{ $history->description == 'Time Out' ? 'selected' : '' }}>Time
                                Out
                            </option>
                        </select>
                    </section>

                    <section class="space-y-2 w-full">
                        <label class="block text-sm font-medium text-gray-700 mt-3">Date & Time</label>
                        <input type="datetime-local" name="history_datetime" value="{{ $history->datetime }}"
                            class="w-full px-4 py-3 border rounded focus:ring-[#F57D11] focus:border-[#F57D11]"
                            required>
                    </section>
                </div>
            </div>
        </form>
    </div>
</x-main-layout>
