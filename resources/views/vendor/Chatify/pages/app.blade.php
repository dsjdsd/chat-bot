@include('Chatify::layouts.headLinks')

<div class="messenger">
    {{-- ----------------------Users/Groups lists side---------------------- --}}
    <div class="messenger-listView {{ !!$id ? 'conversation-active' : '' }}">
        {{-- Header and search bar --}}
        <div class="m-header">
            <nav>
                <a href="#"><i class="fas fa-inbox"></i> <span class="messenger-headTitle">MESSAGES</span> </a>
                {{-- header buttons --}}
                <nav class="m-header-right">
                    <a href="#"><i class="fas fa-cog settings-btn"></i></a>
                    <a href="#" class="listView-x"><i class="fas fa-times"></i></a>
                </nav>
            </nav>
            {{-- Search input --}}
            <input type="text" class="messenger-search" placeholder="Search" />
            {{-- Tabs --}}
            <div class="messenger-listView-tabs">
                <a href="#" class="active-tab" data-view="users">
                    <span class="far fa-user"></span> Peoples</a>
                <a href="#" data-view="groups">
                    <span class="fas fa-users"></span> Groups</a>
            </div>
        </div>

        {{-- tabs and lists --}}
        <div class="m-body contacts-container">
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
                <div class="listOfContacts app-scroll" style="width: 100%;height: calc(100% - 272px);position: relative;">
    @foreach($users as $user)
        @php
            $lastMessage = App\Models\ChMessage::where(function ($q) use ($user) {
                $q->where('from_id', auth()->id())->where('to_id', $user->id);
            })->orWhere(function ($q) use ($user) {
                $q->where('from_id', $user->id)->where('to_id', auth()->id());
            })->orderBy('created_at', 'desc')->first();

            $unseenCounter = App\Models\ChMessage::where('from_id', $user->id)
                ->where('to_id', auth()->id())
                ->where('seen', 0)
                ->count();
        @endphp

        @include('Chatify::layouts.listItem', [
            'get' => 'users',
            'user' => $user,
            'lastMessage' => $lastMessage ?? null,
            'unseenCounter' => $unseenCounter ?? 0,
        ])
    @endforeach
</div>

            </div>

            {{-- ---------------- [ Group Tab ] ---------------- --}}
            <div class="messenger-tab groups-tab app-scroll" data-view="groups" style="display: none">
                <p class="messenger-title"><span>Groups</span></p>
                <div class="listOfContacts" style="width: 100%; height: calc(100% - 70px); position: relative;">
                    {{-- You can load groups dynamically here if needed --}}
                </div>
            </div>

            {{-- ---------------- [ Search Tab ] ---------------- --}}
            <div class="messenger-tab search-tab app-scroll" data-view="search">
                <p class="messenger-title"><span>Search</span></p>
                <div class="search-records">
                    <p class="message-hint center-el"><span>Type to search..</span></p>
                </div>
            </div>
        </div>
    </div>

    {{-- ----------------------Messaging side---------------------- --}}
    <div class="messenger-messagingView">
        <div class="m-header m-header-messaging">
            <nav class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                <div class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                    <a href="#" class="show-listView"><i class="fas fa-arrow-left"></i></a>
                    <div class="avatar av-s header-avatar" style="margin: 0px 10px; margin-top: -5px; margin-bottom: -5px;"></div>
                    <a href="#" class="user-name">{{ config('chatify.name') }}</a>
                </div>
                <nav class="m-header-right">
                    <a href="#" class="add-to-favorite"><i class="fas fa-star"></i></a>
                    <a href="/"><i class="fas fa-home"></i></a>
                    <a href="#" class="show-infoSide"><i class="fas fa-info-circle"></i></a>
                </nav>
            </nav>
            <div class="internet-connection">
                <span class="ic-connected">Connected</span>
                <span class="ic-connecting">Connecting...</span>
                <span class="ic-noInternet">No internet access</span>
            </div>
        </div>

        <div class="m-body messages-container app-scroll">
            <div class="messages">
                <p class="message-hint center-el"><span>Please select a chat to start messaging</span></p>
            </div>
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

        @include('Chatify::layouts.sendForm')
    </div>

    {{-- ---------------------- Info side ---------------------- --}}
    <div class="messenger-infoView app-scroll">
        <nav>
            <p>User Details</p>
            <a href="#"><i class="fas fa-times"></i></a>
        </nav>
        {!! view('Chatify::layouts.info')->render() !!}
    </div>
</div>

{{-- Tab switch JS --}}
<script>
    document.querySelectorAll('.messenger-listView-tabs a').forEach(tab => {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelectorAll('.messenger-listView-tabs a').forEach(t => t.classList.remove('active-tab'));
            this.classList.add('active-tab');

            document.querySelectorAll('.messenger-tab').forEach(view => view.style.display = 'none');

            const view = this.getAttribute('data-view');
            document.querySelector(`.messenger-tab.${view}-tab`).style.display = 'block';
        });
    });
</script>

@include('Chatify::layouts.modals')
@include('Chatify::layouts.footerLinks')
