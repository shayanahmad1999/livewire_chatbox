public function conversation() 
{
    $existing = Conversation::where(function($query) {
        $query->where('user1_id', Auth::id())
            ->where('user2_id', $this->conversationId);
    })->first();

    if($existing) {
        $messages = ModelsMessage::where('conversation_id', $existing->id)
            ->with('conversation', 'sender')
            ->first();
    } else {
        $existing = Conversation::create([
            'user1_id' => Auth::id(),
            'user2_id' => $this->conversationId,
        ]);

        $messages = ModelsMessage::where('conversation_id', $existing->id)
            ->with('conversation', 'sender')
            ->get();
    }

    $route = route('message', ['friendUserID' => $this->conversationId]);
    return $this->redirect($route, navigate: true)->with('messages', $messages);
}