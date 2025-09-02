<section
    class="relative overflow-hidden rounded-3xl p-6 md:p-10
         bg-base-100/60 border border-base-300/70 shadow-xl"
    id="hero">

    <!-- پس‌زمینه ثابت و رسمی -->
    <div class="pointer-events-none absolute inset-0 -z-10">
        <div class="absolute -top-24 -right-24 size-72 rounded-full blur-3xl bg-primary/5"></div>
        <div class="absolute -bottom-20 -left-16 size-64 rounded-full blur-3xl bg-secondary/5"></div>
    </div>

    <div class="container mx-auto flex flex-col-reverse md:flex-row items-center justify-between gap-8 px-2 md:px-4">

        <!-- Left -->
        <div class="w-full md:w-1/2 text-center md:text-left space-y-6">

            <!-- Badge کوچک -->
            <div class="inline-flex items-center gap-2 text-xs px-2.5 py-1.5 rounded-xl
                  border border-dashed border-base-300 bg-base-100">
                <svg class="size-4 opacity-70" viewBox="0 0 24 24" fill="currentColor"><path d="M11 17h2v2h-2zm0-12h2v10h-2z"/></svg>
                <span>Landing — Product Intro</span>
            </div>

            <!-- عنوان -->
            <h1 class="text-3xl md:text-5xl font-extrabold leading-tight tracking-tight">
                Complete Sustainability Reports
                <span class="text-primary font-bold">Faster & Compliant</span>
                with <span class="font-black">VSME</span>
            </h1>

            <!-- زیرعنوان -->
            <p class="text-base-content/70 max-w-xl md:pe-6 mx-auto md:mx-0">
                AI-drafted answers, dual-level guidance, multilingual (EN/FI), and progress saving—tailored for VSMEs.
            </p>

            <!-- CTA ها -->
            <div class="flex flex-wrap items-center gap-3 justify-center md:justify-start">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg rounded-xl shadow-md">
                    Get Started
                </a>
                <a href="#how-it-works" class="btn btn-ghost rounded-xl border-base-300">
                    See how it works
                </a>
            </div>

            <!-- پیِل‌ها -->
            <div class="flex flex-wrap gap-2 justify-center md:justify-start text-xs">
                @foreach (['Quick Signup','AI Drafting','Progress Save','PDF Reports'] as $pill)
                    <span class="px-2.5 py-1.5 rounded-xl border border-base-300 bg-base-100/70">
            {{ $pill }}
          </span>
                @endforeach
            </div>

            <!-- خط اعتماد -->
            <div class="text-xs text-base-content/60 flex items-center gap-3 justify-center md:justify-start">
        <span class="inline-flex items-center gap-1">
          <svg class="size-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l4 4-6 6-4-4 6-6zm8 8l-10 10H4v-6L14 4l6 6z"/></svg>
          Privacy-first
        </span>
                <span>•</span>
                <span>Role-based access</span>
                <span>•</span>
                <span>Export ready</span>
            </div>
        </div>

        <!-- Right -->
        <div class="w-full md:w-1/2">
            <div class="relative w-full max-w-md mx-auto">
                <div class="absolute -inset-1 rounded-3xl bg-base-200/40 blur opacity-60"></div>
                <img
                    src="{{ asset('static/img/vsme-hero.png') }}"
                    alt="VSME dashboard"
                    class="relative w-full rounded-2xl ring-1 ring-base-300 shadow-2xl" />
                <div class="absolute -bottom-3 right-3 md:-bottom-4 md:right-4">
                    <div class="badge badge-primary badge-lg shadow">AI Drafts</div>
                </div>
            </div>
        </div>

    </div>
</section>
