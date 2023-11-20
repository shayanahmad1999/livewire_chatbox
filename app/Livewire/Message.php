<?php

namespace App\Livewire;

use App\Models\Conversation;
use App\Models\MakeFriend;
use App\Models\Message as ModelsMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Messages')]
class Message extends Component
{

    use WithFileUploads;

    #[Rule('required')]
    public $content;

    #[Url(history: true)]
    public $searchFriend;

    public $conversationId;

    #[Rule('nullable|sometimes|image|max:1024')]
    public $image;
    public $document;

    public function mount($conversationId)
    {
        $this->conversationId = $conversationId;
    }

    public function submit() {
        $validated = $this->validate();
        $existingConversation = Conversation::firstOrCreate(
            ['user1_id' => Auth::id(), 'user2_id' => $this->conversationId],
            ['user1_id' => Auth::id(), 'user2_id' => $this->conversationId]
        );
        $uploadImage = null;
        if ($this->image) {
            $uploadImage = $this->image->store('uploads', 'public');
        }
        ModelsMessage::create([
            'conversation_id' => $existingConversation->id, // Use the existing conversation id
            'sender_id' => Auth::id(),
            'content' => $this->content,
            'uploadImage' => $uploadImage,
        ]);
        $this->content = '';
        $this->image = null;
    }
    

    public function render()
    {
        $existingConversation = Conversation::where(function ($query) {
            $query->where('user1_id', auth()->id())
                ->where('user2_id', $this->conversationId);
        })->orWhere(function ($query) {
            $query->where('user1_id', $this->conversationId)
                ->where('user2_id', auth()->id());
        })->first();
    
        if (!$existingConversation) {
            Conversation::create([
                'user1_id' => Auth::id(),
                'user2_id' => $this->conversationId,
            ]);
        } 
        
        $messages = $existingConversation
        ? ModelsMessage::where('conversation_id', $existingConversation->id)
        ->with('conversation', 'sender')
        ->get()
        : [];

        return view('livewire.message', [
            'friends' => MakeFriend::where('make_friend_by', Auth::id())
            ->where(function($q) {
                if (!empty($this->searchFriend)) {
                    $q->where('user_id', 'like', '%' . $this->searchFriend . '%')
                        ->orWhereHas('users', function ($q) {
                            $q->where('name', 'like', '%' . $this->searchFriend . '%');
                        });
                }
            })
            ->latest()->get(),

            'messages' => $messages,
        ]);
    }

    
}