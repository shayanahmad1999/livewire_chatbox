<?php

namespace App\Livewire;

use App\Models\Conversation;
use App\Models\MakeFriend;
use App\Models\Message as ModelsMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Messages')]
class Message extends Component
{

    #[Rule('required')]
    public $content;

    public $conversationId;

    public function mount($conversationId)
    {
        $this->conversationId = $conversationId;
    }

    public function submit() {
        $this->validate();
        ModelsMessage::create([
            'conversation_id' => 5,
            'sender_id' => Auth::id(),
            'content' => $this->content
        ]);
        $this->reset();
    }

    public function conversation($friendUserID) 
    {
        $existing = Conversation::where(function($query) use ($friendUserID) {
            $query->where('user1_id', Auth::id())
                ->where('user2_id', $friendUserID);
        })->first();

        if($existing) {
            $messages = Message::where('conversation_id', $existing->id)
            ->with('conversation', 'sender')
            ->first();
            $route = route('message', ['friendUserID' => $friendUserID]);
            return $this->redirect($route, navigate: true)->with('messages', $messages);
            // return $this->redirect('message', navigate: true);
        }

        else {
            Conversation::create([
                'user1_id' => Auth::id(),
                'user2_id' => $friendUserID,
            ]);
            $messages = Message::where('conversation_id', $existing->id)
            ->with('conversation', 'sender')
            ->get();
            $route = route('message', ['friendUserID' => $friendUserID]);
            return $this->redirect($route, navigate: true)->with('messages', $messages);
            // return $this->redirect('message', navigate: true);
        }

    }

    public function render()
    {

        $existingConversation = Conversation::where(function ($query){
            $query->where('user1_id', Auth::id())
                ->where('user2_id', $this->conversationId);
        })->first();

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

            'messages' => ModelsMessage::where('conversation_id', $existingConversation->id)->get()
            // 'messages' => ModelsMessage::where('conversation_id', 5)->get()
        ]);
    }
}











public function render()
    {

        $existingConversation = Conversation::where('user1_id', Auth::id())
        ->where('user2_id', $this->conversationId)
        ->first();
        
        $messages = $existingConversation
        ? ModelsMessage::where('conversation_id', $existingConversation->id)->get()
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

            'messages' => $messages
        ]);
    }





    public function render()
    {
        $conversations = Conversation::where('user1_id', Auth::id())
            ->orWhere('user2_id', Auth::id())
            ->get();
    
        $messages = [];
    
        foreach ($conversations as $conversation) {
            $messages[] = ModelsMessage::where('conversation_id', $conversation->id)->get();
        }
    
        $messages = collect($messages)->flatten();
    
        return view('livewire.message', [
            'friends' => MakeFriend::where('make_friend_by', Auth::id())
                ->where(function ($q) {
                    if (!empty($this->searchFriend)) {
                        $q->where('user_id', 'like', '%' . $this->searchFriend . '%')
                            ->orWhereHas('users', function ($q) {
                                $q->where('name', 'like', '%' . $this->searchFriend . '%');
                            });
                    }
                })
                ->latest()
                ->get(),
    
            'messages' => $messages
        ]);
    }