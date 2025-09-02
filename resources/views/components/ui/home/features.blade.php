<section class="w-full px-4 md:px-0 my-8" id="features">
    <div class="flex flex-col md:flex-row gap-4 text-center w-full">

        <!-- Feature 1: AI-drafted Answers -->
        <div
            x-data="{ shown: false }"
            x-intersect.once="shown = true"
            class="flex-1 backdrop-blur-lg bg-base-300/60 rounded-2xl p-6 shadow-inner flex flex-col items-center space-y-3 transition-all duration-700 transform"
            :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
        >
            <!-- icon -->
            <div class="size-12 rounded-xl bg-base-100/70 ring-1 ring-base-300 flex items-center justify-center">
                <!-- sparkles -->
                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 3l1.5 3.5L17 8l-3.5 1.5L12 13l-1.5-3.5L7 8l3.5-1.5L12 3zM19 14l.8 1.8L22 16.6l-1.8.8L19 19l-.8-1.8L16.6 17l1.6-.6L19 14zM5 14l.8 1.8L8 16.6l-1.8.8L5 19l-.8-1.8L2.6 17l1.6-.6L5 14z"/>
                </svg>
            </div>

            <h3 class="text-lg md:text-xl font-semibold">AI‑drafted Answers</h3>
            <p class="text-base-content/70 max-w-sm">
                Start faster with context‑aware drafts prefilled from your company profile. Edit, confirm, and move on.
            </p>

            <ul class="text-sm text-base-content/70 space-y-1">
                <li>• Autofill per question</li>
                <li>• Accept / override anytime</li>
                <li>• Learns from past reports</li>
            </ul>

            <a href="#how-it-works" class="btn btn-ghost btn-sm rounded-xl border-base-300 mt-2">
                See details
            </a>
        </div>

        <!-- Feature 2: Dual-level Guidance -->
        <div
            x-data="{ shown: false }"
            x-intersect.once="shown = true"
            class="flex-1 backdrop-blur-lg bg-base-300/60 rounded-2xl p-6 shadow-inner flex flex-col items-center space-y-3 transition-all duration-700 transform"
            :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
            style="transition-delay:120ms"
        >
            <!-- icon -->
            <div class="size-12 rounded-xl bg-base-100/70 ring-1 ring-base-300 flex items-center justify-center">
                <!-- book-open -->
                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M4 5.5A2.5 2.5 0 016.5 3H12v15H6.5A2.5 2.5 0 014 15.5v-10zM12 3h5.5A2.5 2.5 0 0120 5.5v10A2.5 2.5 0 0117.5 18H12"/>
                </svg>
            </div>

            <h3 class="text-lg md:text-xl font-semibold">Dual‑level Guidance</h3>
            <p class="text-base-content/70 max-w-sm">
                Every datapoint ships with two views: <span class="font-medium">Official</span> (VSME wording) and <span class="font-medium">Friendly</span> (plain‑language tips).
            </p>

            <ul class="text-sm text-base-content/70 space-y-1">
                <li>• Tooltip + inline helper</li>
                <li>• Copy exact phrasing safely</li>
                <li>• Links to standards section</li>
            </ul>

            <a href="#questionnaire" class="btn btn-ghost btn-sm rounded-xl border-base-300 mt-2">
                Explore in wizard
            </a>
        </div>

        <!-- Feature 3: Progress & Reports -->
        <div
            x-data="{ shown: false }"
            x-intersect.once="shown = true"
            class="flex-1 backdrop-blur-lg bg-base-300/60 rounded-2xl p-6 shadow-inner flex flex-col items-center space-y-3 transition-all duration-700 transform"
            :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
            style="transition-delay:240ms"
        >
            <!-- icon -->
            <div class="size-12 rounded-xl bg-base-100/70 ring-1 ring-base-300 flex items-center justify-center">
                <!-- chart-bar -->
                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M3 19h18M7 16V8m5 8V5m5 11v-6"/>
                </svg>
            </div>

            <h3 class="text-lg md:text-xl font-semibold">Progress & PDF Reports</h3>
            <p class="text-base-content/70 max-w-sm">
                Save drafts, track completion, clone last year’s responses, and export a clean PDF for clients and banks.
            </p>

            <ul class="text-sm text-base-content/70 space-y-1">
                <li>• Autosave & resume</li>
                <li>• Clone / edit old reports</li>
                <li>• Branded PDF export</li>
            </ul>

            <div class="flex gap-2">

                <a href="#download-sample" class="btn btn-ghost btn-sm rounded-xl border-base-300">Sample PDF</a>
            </div>
        </div>

    </div>
</section>
