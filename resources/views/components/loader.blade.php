{{-- @see https://cssloaders.github.io --}}

<span class="loader" hidden>
    <div class="sr-only">Chargement, veuillez patienter...</div>
</span>

@once
    @push('styles')
        <style>
            .loader {
                width: var(--loader-size, 2rem);
                height: var(--loader-size, 2rem);
                border: 3px solid rgb(var(--gray-500));
                border-bottom-color: transparent;
                border-radius: 50%;
                cursor: progress;
                animation: rotation 1s linear infinite;
            }
            .loader:not([hidden]) {
                display: inline-block;
            }

            @keyframes rotation {
                0% {
                    transform: rotate(0deg);
                }
                100% {
                    transform: rotate(360deg);
                }
            } 
        </style>
    @endPush
@endonce