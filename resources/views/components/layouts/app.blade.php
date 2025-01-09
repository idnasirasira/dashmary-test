<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{ isset($title) ? $title . " - " . config("app.name") : config("app.name") }}</title>

        @vite(["resources/css/app.css", "resources/js/app.js"])

        {{-- Cropper.js --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
        <script src="https://cdn.tiny.cloud/1/l55lnra8h83akv3oan66sno1bhh5josu7ipkvxqvthmgwmi7/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    </head>

    <body class="min-h-screen bg-base-200/50 font-sans antialiased dark:bg-base-200">
        {{-- NAVBAR mobile only --}}
        <x-nav sticky class="lg:hidden">
            <x-slot:brand>
                <x-app-brand />
            </x-slot>
            <x-slot:actions>
                <label for="main-drawer" class="me-3 lg:hidden">
                    <x-icon name="o-bars-3" class="cursor-pointer" />
                </label>
            </x-slot>
        </x-nav>

        {{-- MAIN --}}
        <x-main full-width>
            {{-- SIDEBAR --}}
            <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">
                {{-- BRAND --}}
                <x-app-brand class="p-5 pt-3" />

                {{-- MENU --}}
                <x-menu activate-by-route>
                    {{-- User --}}
                    @if ($user = auth()->user())
                        <x-menu-separator />

                        <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="!-my-2 -mx-2 rounded">
                            <x-slot:actions>
                                <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="logoff" no-wire-navigate link="/logout" />
                            </x-slot>
                        </x-list-item>

                        <x-menu-separator />
                    @endif

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
