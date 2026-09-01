<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <meta
        name="theme-color"
        content="#f53003"
    >

    <meta
        name="color-scheme"
        content="light dark"
    >

    <title>
        {{ $seo['title'] ?? config('app.name') }}
    </title>

    {{-- SEO --}}
    <meta
        name="description"
        content="{{ $seo['description'] ?? '' }}"
    >

    <meta
        name="keywords"
        content="{{ $seo['keywords'] ?? '' }}"
    >

    <meta
        name="author"
        content="{{ config('seo.site_name') }}"
    >

    {{-- Canonical --}}
    @if (!empty($seo['canonical']))
        <link
            rel="canonical"
            href="{{ $seo['canonical'] }}"
        >
    @endif

    {{-- Robots --}}
    @if (!empty($seo['robots']))
        <meta
            name="robots"
            content="{{ $seo['robots'] }}"
        >
    @else
        <meta
            name="robots"
            content="index,follow"
        >
    @endif

    {{-- Open Graph --}}
    <meta
        property="og:site_name"
        content="{{ config('seo.site_name') }}"
    >

    <meta
        property="og:title"
        content="{{ $seo['og_title'] ?? $seo['title'] ?? '' }}"
    >

    <meta
        property="og:description"
        content="{{ $seo['og_description'] ?? $seo['description'] ?? '' }}"
    >

    @if (!empty($seo['og_image']))

        <meta
            property="og:image"
            content="{{ $seo['og_image'] }}"
        >

        @if (!empty($seo['og_image_alt']))
            <meta
                property="og:image:alt"
                content="{{ $seo['og_image_alt'] }}"
            >
        @endif

        <meta
            property="og:image:type"
            content="image/jpeg"
        >

        <meta
            property="og:image:width"
            content="1200"
        >

        <meta
            property="og:image:height"
            content="630"
        >

    @endif

    <meta
        property="og:type"
        content="{{ $seo['og_type'] ?? 'website' }}"
    >

    @if (!empty($seo['og_locale']))
        <meta
            property="og:locale"
            content="{{ $seo['og_locale'] }}"
        >
    @endif

    <meta
        property="og:url"
        content="{{ $seo['url'] ?? url()->current() }}"
    >

    {{-- Twitter --}}
    <meta
        name="twitter:card"
        content="{{ $seo['twitter_card'] ?? 'summary_large_image' }}"
    >

    <meta
        name="twitter:title"
        content="{{ $seo['twitter_title'] ?? $seo['title'] ?? '' }}"
    >

    <meta
        name="twitter:description"
        content="{{ $seo['twitter_description'] ?? $seo['description'] ?? '' }}"
    >

    @if (!empty($seo['og_image']))

        <meta
            name="twitter:image"
            content="{{ $seo['og_image'] }}"
        >

        @if (!empty($seo['og_image_alt']))
            <meta
                name="twitter:image:alt"
                content="{{ $seo['og_image_alt'] }}"
            >
        @endif

    @endif

    {{-- Structured Data --}}
    @if (!empty($structuredData))

        @foreach ($structuredData as $schema)

            <script type="application/ld+json">
                @json($schema)
            </script>

        @endforeach

    @endif

    <link
        rel="icon"
        href="/favicon.ico"
        type="image/x-icon"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    @vite(['resources/js/app.js'])

</head>

<body>

    <div id="app"></div>

</body>

</html>