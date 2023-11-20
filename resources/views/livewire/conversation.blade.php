<div>
    <div class="mt-20 ml-20">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{auth()->user()->name}}
        </h2>
    </div>
  
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="flex h-screen">
              <div wire:poll.keep-alive.10s class="w-1/4 bg-gray-200 p-4">
                <h2 class="text-lg font-semibold mb-4">Users</h2>
                <input
                  wire:model.live.debunce.300ms="searchFriend"
                  type="text"
                  placeholder="Search friends"
                  class="w-full p-2 mb-4 rounded-lg focus:outline-none"
                />
                <ul>
                  @foreach ($friends as $friend)
                  <li
                  class="mb-2 px-4 py-2 hover:bg-gray-300 cursor-pointer"
                >
                <div class="flex">
                  <div class="w-10 h-10 mr-2 rounded-full overflow-hidden">
                    @if ($friend->users->profileImage)
                    <img
                    class="object-cover w-full h-full"
                    src="{{asset($friend->users->profileImage)}}"
                    alt="Placeholder Image"
                  />
                    @else
                    <img
                    class="object-cover w-full h-full"
                    src="{{asset('images/avatar.jpg')}}"
                    alt="Placeholder Image"
                  />
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
              <div class="w-3/4 p-4">
                <input
                    wire:model.live.debounce.300ms="search"
                  type="text"
                  placeholder="Search friends"
                  class="w-full p-2 mb-4 mt-4 rounded-lg focus:outline-none"
                />
                <div class="grid grid-cols-6">
                  @foreach ($users as $user)
                  <div class="flex flex-col items-center">
                    <div class="w-24 h-24 rounded-full overflow-hidden">
                      @if ($user->profileImage)
                      <img
                      class="object-cover w-full h-full"
                      src="{{asset($user->profileImage)}}"
                      alt="Placeholder Image"
                    />
                      @else
                      <img
                      class="object-cover w-full h-full"
                      src="{{asset('images/avatar.jpg')}}"
                      alt="Placeholder Image"
                    />
                      @endif
                    </div>
                    <p class="mt-2 text-lg font-medium">{{$user->name}}</p>
                    <div class="flex items-center mt-2">
                      <button wire:click="addFriend({{$user->id}})">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          class="h-6 w-6 mr-1"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke="currentColor"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                          />
                        </svg>
                      </button>
                    </div>
                  </div>
                  @endforeach
                </div>
                <div class="mt-10">
                    {{$users->links()}}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
</div>
