<div>

    @push('meta')
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => 'VSME',
                'url' => 'https://vsme.com',
                'description' => 'Complete Sustainability Reports Faster with VSME',
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'VSME',
                    'url' => 'https://vsme.com',
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => '',
                    ]
                ]
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>

        {{--        <meta name="description"--}}
        {{--              content="{{ '' }}">--}}
        {{--        <meta property="og:title" content="{{ $title ?? 'Byblos' }}">--}}
        {{--        <meta property="og:description"--}}
        {{--              content="">--}}
        {{--        <meta property="og:image" content="{{ asset('static/img/byblos-hero.webp') }}">--}}
        {{--        <meta property="og:url" content="{{ url()->current() }}">--}}
        {{--        <meta name="twitter:card" content="summary_large_image">--}}

    @endpush


    <x-ui.home.hero/>

    <x-ui.home.features/>

    <x-ui.home.timeline/>

    <x-ui.home.trusted/>


    @include('components.ui.home.scroll-up-button')


</div>
