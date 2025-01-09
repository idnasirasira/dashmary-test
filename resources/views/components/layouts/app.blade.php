<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Cropper.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />

    {{-- TinyMCE --}}
    <script src="https://cdn.tiny.cloud/1/l55lnra8h83akv3oan66sno1bhh5josu7ipkvxqvthmgwmi7/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>

    {{-- Easy MDE --}}
    <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
    <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>

    {{-- Chart --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>

<body class="min-h-screen bg-base-200/50 font-sans antialiased dark:bg-base-200">
    {{-- NAVBAR mobile only --}}
    <x-nav sticky>
        <x-slot:brand>
            <x-app-brand />
        </x-slot>
        <x-slot:actions>
            <label for="main-drawer" class="me-3 lg:hidden">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>

            <x-button label="Messages" icon="o-envelope" link="###" class="btn-ghost btn-sm" responsive />
            <x-button label="Notifications" icon="o-bell" link="###" class="btn-ghost btn-sm" responsive />
            @if ($user = auth()->user())
                <x-dropdown class="">
                    <x-slot:trigger>
                        <div class="flex items-center gap-2 cursor-pointer">
                            <x-avatar :image="$user->avatar" class="w-8 h-8" />
                        </div>
                    </x-slot:trigger>

                    <div class="flex flex-col items-center p-4">
                        {{-- Email --}}
                        <x-avatar :image="$user->avatar" class="h-10 w-10" />
                        <span class="text-xl font-semibold">{{ $user->name }}</span>
                        <small>{{ $user->email }}</small>
                    </div>

                    <div @click.stop="" class="flex items-center justify-center gap-5 mt-3">
                        <x-theme-toggle />
                    </div>

                    <x-menu-separator />

                    <x-menu-item @click.stop="" icon="o-power" label="{{ __('Sign Out') }}" tooltip-left="logoff"
                        no-wire-navigate link="/logout">
                    </x-menu-item>

                </x-dropdown>
            @endif

        </x-slot>
    </x-nav>

    {{-- MAIN --}}
    <x-main full-width>
        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible collapse-text="{{ __('Hide') }}"
            class="bg-base-100 lg:bg-inherit" right-mobile>
            {{-- MENU --}}
            <x-menu activate-by-route>
                <br>
                <x-menu-item title="Home" icon="o-sparkles" link="/" />
                <x-menu-item title="Users" icon="o-users" link="/users" />
                <x-menu-sub title="Settings" icon="o-cog-6-tooth">
                    <x-menu-item title="Wifi" icon="o-wifi" link="####" />
                    <x-menu-item title="Archives" icon="o-archive-box" link="####" />
                </x-menu-sub>
            </x-menu>
        </x-slot>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot>
    </x-main>

    {{-- TOAST area --}}
    <x-toast />

    {{-- Spotlight --}}
    <x-spotlight />
</body>

</html>
