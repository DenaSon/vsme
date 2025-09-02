<section class="py-16 bg-gradient-to-b from-base-100 to-base-200" id="how-it-works">
    <div class="max-w-6xl mx-auto px-4 text-center">

        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-14 relative inline-block">
            How VSME Works
            <span class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-24 h-1
               bg-gradient-to-r from-primary via-secondary to-accent rounded-full"></span>
        </h2>


        <div class="relative">

            <div class="absolute top-12 left-0 w-full h-[2px] hidden sm:block
                  bg-gradient-to-r from-primary via-secondary to-accent opacity-30"></div>
            <div class="absolute top-11 left-0 w-full h-[1px] hidden sm:block
                  bg-base-300/60"></div>

            <!-- Steps -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 relative z-10">

                <!-- Step -->
                <div class="flex flex-col items-center text-center relative">
                    <!-- Indicator -->
                    <div class="w-16 h-16 rounded-2xl bg-primary/10 border border-primary/30
                      grid place-items-center shadow-sm mb-5">
                        <x-icon name="o-user-plus" class="w-7 h-7 text-primary" />
                    </div>

                    <!-- Card -->
                    <div class="w-full max-w-xs bg-base-100/80 border border-base-300 rounded-2xl
                      p-5 shadow-xl">
                        <div class="text-xs badge badge-outline border-dashed mb-2">Step 1</div>
                        <h3 class="font-semibold text-lg mb-1 text-base-content">Sign Up</h3>
                        <p class="text-sm text-base-content/70">
                            Create your account and select language (EN/FI).
                        </p>
                    </div>
                </div>

                <!-- Step -->
                <div class="flex flex-col items-center text-center relative">
                    <div class="w-16 h-16 rounded-2xl bg-secondary/10 border border-secondary/30
                      grid place-items-center shadow-sm mb-5">
                        <x-icon name="o-building-office" class="w-7 h-7 text-secondary" />
                    </div>

                    <div class="w-full max-w-xs bg-base-100/80 border border-base-300 rounded-2xl
                      p-5 shadow-xl">
                        <div class="text-xs badge badge-outline border-dashed mb-2">Step 2</div>
                        <h3 class="font-semibold text-lg mb-1 text-base-content">Company Profile</h3>
                        <p class="text-sm text-base-content/70">
                            Add company details so AI can personalize responses.
                        </p>
                    </div>
                </div>

                <!-- Step -->
                <div class="flex flex-col items-center text-center relative">
                    <div class="w-16 h-16 rounded-2xl bg-accent/10 border border-accent/30
                      grid place-items-center shadow-sm mb-5">
                        <x-icon name="o-document-check" class="w-7 h-7 text-accent" />
                    </div>

                    <div class="w-full max-w-xs bg-base-100/80 border border-base-300 rounded-2xl
                      p-5 shadow-xl">
                        <div class="text-xs badge badge-outline border-dashed mb-2">Step 3</div>
                        <h3 class="font-semibold text-lg mb-1 text-base-content">
                            Questionnaire & Report
                        </h3>
                        <p class="text-sm text-base-content/70">
                            Review AI drafts, answer guided questions, export a PDF.
                        </p>
                    </div>
                </div>
            </div>


            <div class="sm:hidden mt-8 space-y-4">
                <div class="mx-auto h-px w-12 bg-base-300/70"></div>
                <div class="mx-auto h-px w-12 bg-base-300/70"></div>
            </div>
        </div>


        <div class="mt-12">
            <div class="mx-auto max-w-xl">
                <div class="w-full h-2 rounded-full bg-base-300 overflow-hidden">
                    <div class="h-full w-1/3 bg-gradient-to-r from-primary via-secondary to-accent"></div>
                </div>
                <div class="mt-2 text-xs text-base-content/60">
                    Setup progress: Sign Up → Profile → Questionnaire
                </div>
            </div>
        </div>
    </div>
</section>
