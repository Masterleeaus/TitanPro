@extends('titantalk::frame')

@section('content')
<div class="titantalk-webchat-frame" data-enabled="{{ $titantalkWebchatEnabled ? '1' : '0' }}">
    <div class="alert alert-info mb-0">
        TitanTalk webchat lane is enabled. Donor UI from Chatbot/ChatbotVoice is bundled under <code>donors/</code> for staged cleanup and extraction.
    </div>
</div>
@endsection
