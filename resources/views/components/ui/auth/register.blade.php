<x-layouts.app title="{{ $title ?? '' }}">
    <section class="min-h-screen flex items-center justify-center px-4 py-12 bg-base-200">
        <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">

            <!-- Left content -->
            <div class="flex flex-col items-center lg:items-start space-y-6 text-center lg:text-left">
                <img src="{{ asset('static/img/signup-newsletter.svg') }}"
                     alt="SignUp Illustration"
                     class="w-48 sm:w-64 md:w-80 lg:w-[420px] opacity-90 rounded-xl" />

                <h1 class="text-3xl md:text-4xl font-extrabold leading-tight text-base-content">
                    Create your account
                </h1>

                <p class="text-base-content/70 max-w-prose">
                    Join VSME and manage compliance with ease. Quick setup, guided steps, and secure access.
                </p>

                <ul class="text-sm text-base-content/60 space-y-1">
                    <li>• AI-assisted questionnaires</li>
                    <li>• Save progress anytime</li>
                    <li>• Export professional reports</li>
                </ul>
            </div>

            <!-- Right form card -->
            <x-card class="w-full max-w-sm mx-auto bg-base-100/95 border border-base-300 shadow-xl rounded-2xl">
                <div class="card-body p-6">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-semibold">Sign Up</h2>
                        <p class="text-xs text-base-content/60 mt-1">
                            Start your free account today
                        </p>
                    </div>

                    @livewire('auth.register')

                    <div class="mt-6 text-center text-sm text-base-content/70">
                        Already have an account?
                        <a wire:navigate href="{{ route('login') }}" class="link link-primary font-semibold">
                            Log in
                        </a>
                    </div>
                </div>
            </x-card>
        </div>
    </section>
</x-layouts.app>
