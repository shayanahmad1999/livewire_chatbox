<?php

namespace App\Livewire;

use App\Models\Conversation as ModelsConversation;
use App\Models\MakeFriend;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Conversation')]
class Conversation extends Component
{

    use WithPagination;

    #[Url(history: true, keep: false)]
    public $search = '';

    #[Url(history: true)]
    public $searchFriend = '';

    public $perPage = 12;

    public function addFriend($userId)
    {
        MakeFriend::create([
            'make_friend_by' => Auth::id(),
            'user_id' => $userId,
        ]);
    }

    public function render()
    {
        return view('livewire.conversation', [
            'users' => User::where('id', '!=', Auth::id())
            ->whereDoesntHave('makefriends', function($query) {
                $query->where('make_friend_by', Auth::id());
            })
            ->where(function ($query) {
                if (!empty($this->search)) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                }
            })
                ->paginate($this->perPage),

                'friends' => MakeFriend::where('make_friend_by', Auth::id())
                ->where(function($q) {
                    if (!empty($this->searchFriend)) {
                        $q->where('user_id', 'like', '%' . $this->searchFriend . '%')
                            ->orWhereHas('users', function ($q) {
                                $q->where('name', 'like', '%' . $this->searchFriend . '%');
                            });
                    }
                })
                ->latest()->get()
        ]);
    }
}
