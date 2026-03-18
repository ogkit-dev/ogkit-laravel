<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @ogPreview
    @ogMeta($title, $description, $canonicalUrl)
    <title>{{ $title }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
<main class="mx-auto grid min-h-screen w-full max-w-3xl place-items-center px-4 py-8">
    <section class="w-full rounded-3xl border border-slate-200 bg-white p-7 shadow-[0_18px_48px_rgba(15,23,42,0.08)]">
        <h1 class="text-4xl font-black tracking-tight text-slate-950 sm:text-5xl">{{ $title }}</h1>
        <p class="mt-3 text-base leading-7 text-slate-600">
            Use preview mode to inspect the OG template, or open the generated image directly.
        </p>

        <div class="mt-6 flex flex-wrap gap-3">
            <a
                class="inline-flex items-center rounded-full bg-indigo-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-800"
                href="{{ $canonicalUrl }}?ogkit-render"
            >
                Open Preview
            </a>
            <a
                class="inline-flex items-center rounded-full bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-700"
                href="{{ $imageUrl }}"
                target="_blank"
                rel="noreferrer"
            >
                Open Generated Image
            </a>
        </div>

        <pre class="mt-5 overflow-x-auto rounded-2xl bg-slate-950 px-4 py-4 text-sm leading-6 text-slate-100">{{ $imageUrl }}</pre>
    </section>
</main>

@ogTemplate
    <div class="flex h-full w-full flex-col justify-between bg-[radial-gradient(circle_at_top_right,_#818cf8_0%,_#312e81_35%,_#0f172a_100%)] p-14 text-slate-50">
        <div class="flex items-center justify-between">
            <div class="text-[28px] font-bold tracking-[-0.04em]">OG Kit Laravel</div>
            <div class="rounded-full bg-white/12 px-4 py-2.5 text-[20px]">
                Live Workbench Preview
            </div>
        </div>

        <div class="max-w-[860px]">
            <div class="text-[72px] leading-none font-extrabold tracking-[-0.05em]">
                {{ $title }}
            </div>
            <div class="mt-6 text-[30px] leading-[1.35] text-slate-300">
                {{ $description }}
            </div>
        </div>

        <div class="flex items-end justify-between gap-8">
            <div class="max-w-[760px] text-[24px] leading-[1.4] text-slate-200">
                This OG image is rendered from the same Blade view, CSS, and variables as the page itself.
            </div>
            <div class="text-[18px] whitespace-nowrap text-slate-300">
                {{ parse_url($canonicalUrl, PHP_URL_HOST) }}
            </div>
        </div>
    </div>
@endOgTemplate
</body>
</html>
