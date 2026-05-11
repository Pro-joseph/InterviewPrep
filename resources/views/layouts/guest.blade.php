<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'InterviewPrep') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=JetBrains+Mono:wght@400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-body antialiased">
        <div class="min-h-screen flex">
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-[#1a1206] via-[#0F0F0F] to-[#0a0805]"></div>
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 20% 50%, rgba(242, 155, 31, 0.15) 0%, transparent 50%), radial-gradient(circle at 80% 80%, rgba(242, 155, 31, 0.1) 0%, transparent 40%);"></div>
                <div class="relative z-10 flex flex-col justify-center px-16">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-xl bg-primary flex items-center justify-center">
                            <svg class="w-7 h-7 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <span class="font-display text-3xl font-bold text-primary">InterviewPrep</span>
                    </div>
                    <h1 class="font-display text-4xl font-semibold text-primary mb-4 leading-tight">
                        Prépare ton entretien<br>technique avec confiance
                    </h1>
                    <p class="text-lg text-secondary max-w-md">
                        Organise tes connaissances, maîtrise chaque concept et génère des questions d'entretien alimentées par l'IA.
                    </p>
                    <div class="mt-12 flex items-center gap-8">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-primary">12+</div>
                            <div class="text-sm text-muted">Domaines</div>
                        </div>
                        <div class="w-px h-10 bg-default"></div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-primary">100+</div>
                            <div class="text-sm text-muted">Concepts</div>
                        </div>
                        <div class="w-px h-10 bg-default"></div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-primary">500+</div>
                            <div class="text-sm text-muted">Questions IA</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-secondary">
                <div class="w-full max-w-md">
                    <div class="lg:hidden flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center">
                            <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <span class="font-display text-xl font-semibold text-primary">InterviewPrep</span>
                    </div>

                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>