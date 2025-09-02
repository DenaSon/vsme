<x-layouts.app title="{{ $title }}">
    <section class="min-h-screen grid place-items-center px-4 py-10 bg-base-200">
        <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-20 items-center">

            <!-- Left: Intro (بدون انیمیشن) -->
            <div class="order-2 lg:order-1 flex flex-col items-center lg:items-start gap-6">
                <img
                    src="{{ asset('static/img/signin-authenticate.svg') }}"
                    alt="Sign in illustration"
                    class="w-48 sm:w-64 md:w-80 lg:w-[420px] rounded-xl opacity-90" />

                <div class="space-y-3 text-center lg:text-left">
                    <h1 class="text-3xl md:text-4xl font-extrabold leading-tight text-base-content">
                        Sign in to your account
                    </h1>
                    <p class="text-base-content/70 max-w-prose">
                        Access your dashboard, continue questionnaires, and manage reports securely.
                    </p>
                    <ul class="text-sm text-base-content/60 space-y-1">
                        <li>• Privacy-first, role-based access</li>
                        <li>• Autosave & progress tracking</li>
                        <li>• Multilingual (EN/FI)</li>
                    </ul>
                </div>
            </div>

            <!-- Right: Auth Card -->
            <div class="order-1 lg:order-2">
                <x-card class="w-full max-w-sm mx-auto bg-base-100/90 border border-base-300 rounded-2xl shadow-xl">
                    <div class="card-body p-6">
                        <!-- Title -->
                        <div class="text-center mb-4">
                            <h2 class="text-2xl font-semibold">Sign In</h2>
                            <p class="text-xs text-base-content/60 mt-1">Use your email and password to continue</p>
                        </div>



                        <!-- Livewire Login -->
                        <div class="mt-2">
                            @livewire('auth.login')
                        </div>



                        <!-- Compliance note -->
                        <p class="mt-4 text-[11px] leading-5 text-base-content/60">
                            By signing in you agree to our <a href="" class="link">Terms</a> and
                            <a href="" class="link">Privacy Policy</a>.
                        </p>


                    </div>
                </x-card>
            </div>

        </div>
    </section>
</x-layouts.app>
