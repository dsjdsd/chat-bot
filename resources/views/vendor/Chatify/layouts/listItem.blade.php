{{-- -------------------- Saved Messages -------------------- --}}
@if($get == 'saved')
    <table class="messenger-list-item" data-contact="{{ Auth::user()->id }}">
        <tr data-action="0">
            <td>
                <div class="saved-messages avatar av-m">
                    <span class="far fa-bookmark"></span>
                </div>
            </td>
            <td>
                <p data-id="{{ Auth::user()->id }}" data-type="user">Saved Messages <span>You</span></p>
                <span>Save messages secretly</span>
            </td>
        </tr>
    </table>
@endif

{{-- -------------------- Contact list -------------------- --}}
@if($get == 'users')
<?php
    $lastMessageBody = isset($lastMessage) ? mb_convert_encoding($lastMessage->body, 'UTF-8', 'UTF-8') : '';
    $lastMessageBody = strlen($lastMessageBody) > 30 ? mb_substr($lastMessageBody, 0, 30, 'UTF-8').'..' : $lastMessageBody;
?>
<table class="messenger-list-item" data-contact="{{ $user->id }}">
    <tr data-action="0">
        <td style="position: relative">
            @if($user->active_status)
                <span class="activeStatus"></span>
            @endif
            <div class="avatar av-m" style="background-image: url('{{ $user->avatar }}');"></div>
        </td>
        <td>
            <p data-id="{{ $user->id }}" data-type="user">
                {{ strlen($user->name) > 12 ? trim(substr($user->name,0,12)).'..' : $user->name }}
                @if(isset($lastMessage))
                    <span class="contact-item-time" data-time="{{ $lastMessage->created_at }}">
                        {{ $lastMessage->created_at->diffForHumans() }}
                    </span>
                @endif
            </p>
            <span>
                @if(isset($lastMessage) && $lastMessage->from_id == Auth::user()->id)
                    <span class="lastMessageIndicator">You :</span>
                @endif

                @if(isset($lastMessage))
                    @if($lastMessage->attachment == null)
                        {!! $lastMessageBody !!}
                    @else
                        <span class="fas fa-file"></span> Attachment
                    @endif
                @else
                    <em>No messages yet</em>
                @endif
            </span>

            {!! $unseenCounter > 0 ? "<b>".$unseenCounter."</b>" : '' !!}
        </td>
    </tr>
</table>
@endif
