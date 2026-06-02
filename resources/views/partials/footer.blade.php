<footer class="bg-surface-container-low border-t border-outline-variant/30 w-full rounded-t-lg">
    <div class="flex flex-col md:flex-row justify-between items-center px-margin-mobile md:px-margin-desktop py-lg gap-md max-w-[1440px] mx-auto">
        <div class="space-y-sm text-center md:text-left">
            <div class="font-headline-md text-headline-md text-primary">Spies Sport</div>
            <p class="font-label-md text-on-surface-variant max-w-[300px]">&copy; {{ date('Y') }} Spies Sport. Tingkatkan permainanmu di setiap langkah.</p>
        </div>
        <div class="flex gap-lg flex-wrap justify-center">
            <a class="font-label-md text-on-surface-variant hover:text-primary hover:underline underline-offset-4 transition-all" href="{{ route('kebijakanpriv') }}">Kebijakan Privasi</a>
            <a class="font-label-md text-on-surface-variant hover:text-primary hover:underline underline-offset-4 transition-all" href="{{ route('layanan') }}">Ketentuan Layanan</a>
            <a class="font-label-md text-on-surface-variant hover:text-primary hover:underline underline-offset-4 transition-all" href="{{ route('contact') }}">Hubungi Kami</a>
            <a class="font-label-md text-on-surface-variant hover:text-primary hover:underline underline-offset-4 transition-all" href="{{ route('about') }}">Tentang Kami</a>
        </div>
        <div class="flex gap-md">
            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center border border-outline-variant/20 hover:text-primary transition-all cursor-pointer">
                <span class="material-symbols-outlined">public</span>
            </div>
            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center border border-outline-variant/20 hover:text-primary transition-all cursor-pointer">
                <span class="material-symbols-outlined">alternate_email</span>
            </div>
        </div>
    </div>
</footer>
