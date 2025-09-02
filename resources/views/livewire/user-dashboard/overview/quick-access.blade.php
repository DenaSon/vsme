<section class="grid grid-cols-2 md:grid-cols-4 gap-5 mt-8" id="quickAccess">

    @php
        $quickLinks = [
            ['title' => 'My Company', 'icon' => 'building-office', 'link' => ''],
            ['title' => 'Questionnaires', 'icon' => 'document-text', 'link' => ''],
            ['title' => 'Reports', 'icon' => 'chart-bar', 'link' => ''],
            ['title' => 'Settings', 'icon' => 'cog-6-tooth', 'link' => ''],
        ];
    @endphp

    @foreach ($quickLinks as $item)
        <a wire:key="quick-card-{{$item['title']}}"
           href="{{ $item['link'] }}"
           class="group card bg-base-100 border border-base-300 hover:border-primary/60
                  shadow-md hover:shadow-xl transition duration-300 rounded-2xl">

            <div class="card-body items-center text-center px-6 py-8 relative overflow-hidden">
                {{-- Subtle Background Highlight --}}
                <div class="absolute -inset-1 bg-gradient-to-tr from-primary/10 to-accent/5
                            opacity-0 group-hover:opacity-100 blur-xl transition duration-500 rounded-2xl z-0"></div>

                {{-- Icon & Title --}}
                <div class="relative z-10">
                    <x-dynamic-component
                        :component="'heroicon-o-' . $item['icon']"
                        class="w-10 h-10 text-primary group-hover:scale-110 transform transition duration-300"
                    />
                    <h3 class="text-sm font-semibold mt-4 group-hover:text-primary transition">
                        {{ $item['title'] }}
                    </h3>
                </div>
            </div>
        </a>
    @endforeach

</section>
