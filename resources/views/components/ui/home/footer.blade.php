<footer
    class="relative py-12 mt-10 border-t
         bg-gradient-to-b from-base-200 via-base-300 to-base-300/95
         text-base-content
         border-base-300
         dark:from-gray-900 dark:via-gray-950 dark:to-black
         dark:text-gray-200 dark:border-gray-800"
>
    <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="text-sm opacity-80">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
        <div class="flex gap-4">
            <a href="#" class="hover:text-primary transition-colors">Privacy</a>
            <a href="#" class="hover:text-primary transition-colors">Terms</a>
            <a href="#" class="hover:text-primary transition-colors">Support</a>
        </div>
    </div>

    <!-- decorative gradient line -->
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary/60 via-secondary/60 to-accent/60"></div>
</footer>
