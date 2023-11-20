<div>
    <x-navbar />
    <div class="mt-20 ml-20">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a wire:navigate.hover href="{{ route('dashboard') }}">Chatbox</a>
        </h2>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex">

                    <div wire:poll.keep-alive.10s class="w-1/4 bg-gray-200 p-4">
                        <h2 class="text-lg font-semibold mb-4">Users</h2>
                        <input wire:model.live.debunce.300ms="searchFriend" type="text" placeholder="Search friends"
                            class="w-full p-2 mb-4 rounded-lg focus:outline-none" />
                        <ul>
                            @foreach ($friends as $friend)
                                <li class="mb-2 px-4 py-2 hover:bg-gray-300 cursor-pointer">
                                    <div class="flex">
                                        <div class="w-10 h-10 mr-2 rounded-full overflow-hidden">
                                            @if ($friend->users->profileImage)
                                                <img class="object-cover w-full h-full"
                                                    src="{{ asset($friend->users->profileImage) }}"
                                                    alt="Placeholder Image" />
                                            @else
                                                <img class="object-cover w-full h-full"
                                                    src="{{ asset('images/avatar.jpg') }}" alt="Placeholder Image" />
                                            @endif
                                        </div>
                                        <a wire:navigate.hover href="{{route('message', $friend->user_id)}}">
                                            {{$friend->users->name}}
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div wire:poll.keep-alive.10s class="w-3/4 p-4">
                        <div class="flex flex-col h-full bg-white rounded-lg">
                            <div class="flex-grow border-b mb-5">
                                <div class="flex flex-col h-full overflow-y-auto">
                                    <div class="flex items-start">
                                        <div class="w-24 h-24 rounded-full overflow-hidden">
                                            @if (auth()->user()->profileImage)
                                                <img class="object-cover w-full h-full"
                                                    src="{{ asset(auth()->user()->profileImage) }}"
                                                    alt="Placeholder Image" />
                                            @else
                                                <img class="object-cover w-full h-full"
                                                    src="{{ asset('images/avatar.jpg') }}" alt="Placeholder Image" />
                                            @endif
                                        </div>
                                        <div class="ml-3 mt-10">
                                            <h2 class="text-lg font-semibold mb-4">
                                                {{ auth()->user()->name }}
                                            </h2>
                                        </div>
                                    </div>
                                    @if ($messages)
                                        @foreach ($messages as $message)
                                            <div class="my-4 mx-6">
                                                @if ($message->sender_id == auth()->user()->id)
                                                    <div class="flex items-start">
                                                        <div class="w-24 h-24 rounded-full overflow-hidden">
                                                            @if ($message->sender->profileImage)
                                                                <img class="object-cover w-full h-full"
                                                                    src="{{ asset($message->sender->profileImage) }}"
                                                                    alt="Placeholder Image" />
                                                            @else
                                                                <img class="object-cover w-full h-full"
                                                                    src="{{ asset('images/avatar.jpg') }}"
                                                                    alt="Placeholder Image" />
                                                            @endif
                                                        </div>
                                                        <div class="ml-3">
                                                            <p class="text-sm font-medium">
                                                                {{ $message->sender->name }}
                                                            </p>
                                                            <div class="bg-gray-100 rounded-lg p-2 mt-1">
                                                                <span>
                                                                    <button @click="messageDelete(message.id)">
                                                                        <span
                                                                            v-if="message.content">{{ $message->content }}</span>
                                                                        <img v-if="shouldShowImage(message)"
                                                                            :src="`${message.uploadImage}`" />
                                                                    </button>
                                                                </span>

                                                                <span class="ml-5">{{ $message->created_at->format('Y-m-d H:i:s') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div v-else class="flex items-end justify-end">
                                                        <div class="mr-3">
                                                            <p class="text-sm font-medium">
                                                                {{ $message->sender->name }}
                                                            </p>
                                                            <div class="bg-gray-100 rounded-lg p-2 mt-1">
                                                                <span>
                                                                    <button @click="messageDelete(message.id)">
                                                                        <span
                                                                            v-if="message.content">{{ $message->content }}</span>
                                                                        <img v-if="shouldShowImage(message)"
                                                                            :src="`${message.uploadImage}`" />
                                                                    </button>
                                                                </span>

                                                                <span class="ml-5">{{ $message->created_at->format('Y-m-d H:i:s') }} </span>
                                                            </div>
                                                        </div>
                                                        <div class="w-24 h-24 rounded-full overflow-hidden">
                                                            @if ($message->sender->profileImage)
                                                                <img class="object-cover w-full h-full"
                                                                    src="{{ asset($message->sender->profileImage) }}"
                                                                    alt="Placeholder Image" />
                                                            @else
                                                                <img class="object-cover w-full h-full"
                                                                    src="{{ asset('images/avatar.jpg') }}"
                                                                    alt="Placeholder Image" />
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="flex-none grid grid-cols-4">
                                <form wire:submit.prevent="submit" wire:loading.attr="disabled">
                                    <div class="relative">
                                        <input type="text" placeholder="Type your message here"
                                            class="p-4 mt-2 w-full focus:outline-none" wire:model="content" />
                                        <div class="absolute top-0 right-0 mt-3 mr-3">
                                            <i class="fas fa-arrow-up cursor-pointer"></i>
                                            <i class="fas fa-arrow-down cursor-pointer"></i>
                                        </div>
                                    </div>
                                
                                    <div class="mt-2">
                                        <label for="image" class="text-gray-600">Upload Image:</label>
                                        <input type="file" id="image" wire:model="image" accept="image/*" class="mt-1" wire:change="submit" />
                                        @if($image)
                                            <img src="{{ $image->temporaryUrl() }}" alt="Uploaded Image" class="mt-2 max-w-full h-auto" />
                                        @endif
                                    </div>
                                
                                    {{-- <div class="mt-2">
                                        <label for="document" class="text-gray-600">Upload Document:</label>
                                        <input type="file" id="document" wire:model="document" accept=".pdf, .doc, .docx" class="mt-1" />
                                        @if($document)
                                            <p class="mt-2">Uploaded Document: {{ $document->getClientOriginalName() }}</p>
                                        @endif
                                    </div> --}}
                                
                                    <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Send</button>
                                </form>
                                
                            </div>
                            
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
