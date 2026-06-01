@props([
    'name' => '',
    'type' => 'confirm',
    'title' => '',
    'message' => '',
    'confirmText' => 'Ya',
    'cancelText' => 'Kembali',
    'confirmVariant' => 'primary',
    'maxWidth' => '480px',
    'show' => false,
])

@php
$confirmBtnBg = $confirmVariant === 'danger' ? '#dc2626' : '#00004D';
$confirmBtnHover = $confirmVariant === 'danger' ? '#b91c1c' : '#000066';
@endphp

@php
$icons = [
    'confirm' => '⚠️',
    'success' => '✅',
    'error'   => '❌',
    'info'    => 'ℹ️',
];
$icon = $icons[$type] ?? $icons['info'];
@endphp

<div
    x-data="{
        show: @js($show),
        loading: false,
        title: '{{ str_replace("'", "\\'", $title) }}',
        message: '{{ str_replace("'", "\\'", $message) }}',
        init() {
            this.$watch('show', v => {
                if (v) {
                    document.body.classList.add('overflow-hidden');
                } else {
                    document.body.classList.remove('overflow-hidden');
                    this.loading = false;
                }
            });
        },
        confirm() {
            this.loading = true;
            this.$dispatch('modal-confirmed', { name: '{{ $name }}' });
        },
        close() {
            this.show = false;
            this.$dispatch('modal-closed', { name: '{{ $name }}' });
        },
        setData(data) {
            if (data.title !== undefined) this.title = data.title;
            if (data.message !== undefined) this.message = data.message;
        }
    }"
    x-on:open-modal-{{ $name }}.window="if ($event.detail) setData($event.detail); show = true"
    x-on:close-modal-{{ $name }}.window="show = false"
    x-on:keydown.escape.window="close()"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
>
    {{-- Overlay --}}
    <div x-show="show"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm"
         @click="close()">
    </div>

    {{-- Card --}}
    <div x-show="show"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative bg-white rounded-[20px] shadow-2xl w-full"
         style="max-width: {{ $maxWidth }};"
         @click.away="close()"
    >
        <div class="p-6 text-center">
            <div class="text-5xl mb-5 leading-none">{{ $icon }}</div>

            <h3 class="text-xl font-bold text-[#1F2937] mb-2" x-text="title">{{ $title }}</h3>
            <p class="text-[#6B7280] text-sm leading-relaxed mb-7" x-text="message">{{ $message }}</p>

            {{ $slot ?? '' }}

            @if($type === 'confirm')
                <div class="flex gap-3">
                    <button type="button"
                            class="flex-1 py-3 px-6 rounded-xl font-bold text-sm transition-all duration-200 border-2 hover:bg-gray-50 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-[#00004D]/20"
                            style="border-color: #00004D; color: #00004D; background: white;"
                            @click="close()">
                        {{ $cancelText }}
                    </button>
                    <button type="button"
                            class="flex-1 py-3 px-6 rounded-xl font-bold text-sm transition-all duration-200 text-white disabled:opacity-60 disabled:cursor-not-allowed active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-white/50"
                            style="background: {{ $confirmBtnBg }};"
                            onmouseover="this.style.background='{{ $confirmBtnHover }}'"
                            onmouseout="this.style.background='{{ $confirmBtnBg }}'"
                            :disabled="loading"
                            @click="confirm()">
                        <span x-show="!loading">{{ $confirmText }}</span>
                        <span x-show="loading" class="inline-flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </div>
            @else
                <button type="button"
                        class="w-full py-3 px-6 rounded-xl font-bold text-sm transition-all duration-200 text-white hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-white/50"
                        style="background: #00004D;"
                        @click="close()">
                    {{ $cancelText }}
                </button>
            @endif
        </div>
    </div>
</div>
