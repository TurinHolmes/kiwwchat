@include('Chatify::layouts.headLinks')
<div class="messenger">
    {{-- ----------------------Users/Groups lists side---------------------- --}}
    <div class="messenger-listView {{ !!$id ? 'conversation-active' : '' }}">
        <div class="m-header" style="padding-top: 15px; padding-bottom: 15px;">
            {{-- Flex container baru untuk Judul dan Tombol --}}
            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;margin-bottom: 8px;">

                {{-- BAGIAN KIRI: Judul App --}}
                <a href="#" style="display: flex; align-items: center; margin-left: 15px">
                    <i class="far fa-comments"></i>
                    {{-- Tambahkan jarak jika ikon sudah berfungsi --}}
                    <span class="messenger-headTitle" style="margin-left: 8px;">KiwwChat</span>
                </a>

                {{-- BAGIAN KANAN: Kontainer Tombol Avatar dan Tutup --}}
                @php
                    $uid = Auth::id();
                    $userModel = \App\Models\User::find($uid);
                    $currentUser = Chatify::getUserWithAvatar($userModel);
                    $navbarAvatar = $currentUser->avatar ?? Chatify::getUserAvatarUrl();
                @endphp

                <div style="display: flex; align-items: center;">

                    {{-- AVATAR SETTINGS --}}
                    <a href="#" class="settings-btn" style="margin-right: 10px;">
                        <img src="{{ $navbarAvatar }}" class="avatar av-s navbar-avatar" />
                    </a>

                    {{-- TOMBOL TUTUP --}}
                    <nav class="m-header-right">
                        <a href="#" class="listView-x"><i class="fas fa-times"></i></a>
                    </nav>
                </div>

            </div>

            {{-- Search input dan Tabs di luar flex container utama --}}
            <input type="text" class="messenger-search" placeholder="Search" />

            {{-- Tabs --}}
            {{-- <div class="messenger-listView-tabs">
        <a href="#" class="active-tab" data-view="users">
            <span class="far fa-user"></span> Contacts</a>
    </div> --}}
        </div>
        {{-- tabs and lists --}}
        <div class="m-body contacts-container">
            {{-- Lists [Users/Group] --}}
            {{-- ---------------- [ User Tab ] ---------------- --}}
            <div class="show messenger-tab users-tab app-scroll" data-view="users">
                {{-- Favorites --}}
                <div class="favorites-section">
                    <p class="messenger-title"><span>Favorites</span></p>
                    <div class="messenger-favorites app-scroll-hidden"></div>
                </div>
                {{-- Saved Messages --}}
                <p class="messenger-title"><span>Your Space</span></p>
                {!! view('Chatify::layouts.listItem', ['get' => 'saved']) !!}
                {{-- Contact --}}
                <p class="messenger-title"><span>All Messages</span></p>
                <div class="listOfContacts" style="width: 100%;height: calc(100% - 272px);position: relative;"></div>
            </div>
            {{-- ---------------- [ Search Tab ] ---------------- --}}
            <div class="messenger-tab search-tab app-scroll" data-view="search">
                {{-- items --}}
                <p class="messenger-title"><span>Search</span></p>
                <div class="search-records">
                    <p class="message-hint center-el"><span>Type to search..</span></p>
                </div>
            </div>
        </div>
    </div>

    {{-- ----------------------Messaging side---------------------- --}}
    <div class="messenger-messagingView">
        {{-- header title [conversation name] --}}
        <div class="m-header m-header-messaging">
            <nav class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                {{-- header left side --}}
                <div class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                    <a href="#" class="show-listView"><i class="fas fa-arrow-left"></i></a>

                    {{-- Always render the avatar container and username anchor so Chatify's JS can update them --}}
                    {{-- avatar container (Chatify will populate the background-image or inner img) --}}
                    <div class="avatar av-s header-avatar"
                        @if (empty($id)) style="
                            margin: 10px 10px;
                            margin-top: -5px;
                            margin-bottom: -5px;
                            background-image: url('{{ Auth::user()->dark_mode ? asset('storage/users-avatar/KiwwChat-Dark.svg') : asset('storage/users-avatar/KiwwChat-Light.svg') }}');
                            background-repeat: no-repeat;
                            background-position:center center;
                            background-size: 85%; /* adjust between 60–80 if it feels too big/small */
                            background-color: transparent; /* ensure the icon isn't hidden by a solid color */"
                        @else
                            style="margin: 0px 10px; margin-top: -5px; margin-bottom: -5px;" @endif
                        data-user="" aria-hidden="true">
                    </div>


                    {{-- username anchor — Chatify updates this by selector .user-name --}}
                    {{-- We include a fallback "Welcome..." text when there's no active conversation --}}
                    <a href="#" class="user-name show-infoSide"
                        style="font-weight:600; font-size:16px; cursor: default;">
                        @if (empty($id))
                            Welcome to KiwwChat
                        @else
                            {{-- When Blade renders with an $id, still render config name — Chatify will replace it immediately --}}
                            {{ config('chatify.name') }}
                        @endif
                    </a>
                </div>

                {{-- header buttons (keep star only when conversation active) --}}
                <nav class="m-header-right">
                    {{-- star exists in DOM but we mark it with data-visible for CSS control; Chatify toggles favorite state --}}
                    <a href="#" class="add-to-favorite" data-visible="{{ empty($id) ? 'false' : 'true' }}">
                        <i class="fas fa-star"></i>
                    </a>
                    <a href="/chatify"><i class="fas fa-home"></i></a>

                    {{-- 🌙 Theme Toggle --}}
                    <a href="{{ route('avatar.update') }}"
                        onclick="event.preventDefault(); 
                        fetch('{{ route('avatar.update') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                dark_mode: {{ Auth::user()->dark_mode ? 0 : 1 }}
                            })
                        }).then(() => location.reload());"
                        class="theme-toggle" title="Toggle Theme">
                        <i class="{{ Auth::user()->dark_mode ? 'fas' : 'far' }} fa-lightbulb"></i>
                    </a>

                    <a href="{{ route('avatar.update') }}"
                        onclick="event.preventDefault(); document.getElementById('privacy-form').submit();"
                        title="Toggle Privacy Mode" class="privacy-toggle d-none d-md-inline">
                        {{-- show slash icon when privacy ON, eye when OFF --}}
                        <i class="fas {{ Auth::user()->privacy_mode ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                    </a>

                    <form id="privacy-form" action="{{ route('avatar.update') }}" method="POST" style="display: none">
                        @csrf
                        {{-- Send the opposite state so clicking toggles it --}}
                        <input type="hidden" name="privacy_mode"
                            value="{{ Auth::user()->privacy_mode ? 'off' : 'on' }}">
                    </form>

                    <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none">
                        @csrf
                    </form>

                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        title="Logout">
                        <i class="fas fa-power-off"></i>
                    </a>
                </nav>

            </nav>

            {{-- Internet connection --}}
            <div class="internet-connection">
                <span class="ic-connected">Connected</span>
                <span class="ic-connecting">Connecting...</span>
                <span class="ic-noInternet">No internet access</span>
            </div>
        </div>




        {{-- Messaging area --}}
        <div class="m-body messages-container app-scroll">
            <div class="messages">
                <p class="message-hint center-el"><span>Please select a chat to start messaging</span></p>
            </div>
            {{-- Typing indicator --}}
            <div class="typing-indicator">
                <div class="message-card typing">
                    <div class="message">
                        <span class="typing-dots">
                            <span class="dot dot-1"></span>
                            <span class="dot dot-2"></span>
                            <span class="dot dot-3"></span>
                        </span>
                    </div>
                </div>
            </div>

        </div>
        {{-- Send Message Form --}}
        @include('Chatify::layouts.sendForm')
    </div>
    {{-- ---------------------- Info side ---------------------- --}}
    <div class="messenger-infoView app-scroll" style="display: none">
        {{-- nav actions --}}
        <nav>
            <p>User Details</p>
            <a href="#"><i class="fas fa-times"></i></a>
        </nav>
        {!! view('Chatify::layouts.info')->render() !!}
    </div>
</div>

@include('Chatify::layouts.modals')
@include('Chatify::layouts.footerLinks')
