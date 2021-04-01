@extends('assets.smdelayout')

@section('content')
<div class="bg-white shadow">
    <div class="container mx-auto px-4 py-2 md:py-3">
        <div class="flex justify-between items-center">
            <div class="text-lg md:text-xl font-bold text-gray-800">Hwackker</div>
            <a href="{{ route('logout') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100 hover:text-blue-600">
                Logout
            </a>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-10">
    <div class="flex items-center justify-center">
        <div class="w-12 h-12 rounded-full overflow-hidden">
            <img src="{{ $userProfile->profile_picture }}" alt="avatar">
        </div>
        <h1 class="font-bold text-3xl ml-4">{{ $userProfile->username }} profile</h1>
        @if ($user->id !== $userProfile->id)
        <form action="{{ route('follow') }}" method="POST">
            @csrf
            <input type="hidden" name="followed_id" id="followed_id" value="{{ $userProfile->id }}"></input>
            <button type="submit" class="bg-blue-500 py-2 px-3 font-bold text-white rounded-md">
                Follow
            </button>
        </form>
        @endif
    </div>
</div>
<div class="container mx-auto px-4 py-10">
    <div class="md:flex -mx-4">
        <div class="w-2/4 px-4">
            @if(!empty($errors))
            <ul class="text-red-500">
                @foreach ($errors->all() as $message)
                <li>- {{ $message }}</li>
                @endforeach
            </ul>
            @endif
            <form class="bg-white rounded-lg shadow px-4 py-4 mb-8" action="{{ route('user.hwack') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <textarea name="content" id="hwack_editor" class="mb-2 bg-gray-100 focus:outline-none focus:shadow-outline border border-transparent rounded-lg py-2 px-4 block w-full appearance-none leading-normal placeholder-gray-700" rows="3" placeholder="What's happening..."></textarea>

                <input multiple name="image" id="image" class="" type="file">

                <br>

                <label for="private" class="mt-6">
                    Private
                    <input type="checkbox" name="private" value="1">
                </label>

                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-blue-500 py-2 px-3 font-bold text-white rounded-md">
                        Send Hwack
                    </button>
                </div>
            </form>

            <div class="bg-white rounded-lg shadow mb-8">
                @foreach($hwacks as $hwack)
                <div class="px-6 py-5 border-b border-gray-200 last:border-none">
                    <div class="flex w-full">
                        <div class="flex-shrink-0 mr-5">
                            <div class="cursor-pointer font-bold w-12 h-12 bg-gray-300 flex items-center justify-center rounded-full overflow-hidden">
                                <img src="{{ $hwack->user->profile_picture }}" alt="avatar">
                            </div>
                        </div>
                        <div class="flex-1">
                            <div>
                                <a href="/user?username={{ $hwack->user->username }}" class="text-gray-600 font-bold">{{ '@' . $hwack->user->username }}</a>
                                <span class="mx-1 text-gray-500">&bull;</span>
                                <span class="text-gray-600">{{ $hwack->created_at->diffForHumans() }}</span>
                            </div>

                            <div class="mb-4">
                                <p class="text-gray-700">{!! $hwack->content !!}</p>
                            </div>

                            @if($hwack->image)
                            <div class="relative w-auto mb-2 border rounded-lg relative bg-gray-100 mb-4 shadow-inset overflow-hidden">
                                <img src="{{ $hwack->image }}" alt="Hwack image" loading="lazy">
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            {{ $hwacks->links() }}
        </div>

        <div class="w-2/3 px-4">
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="px-6 py-3 border-b border-gray-200">
                    <div class="font-bold text-gray-800">Following</div>
                </div>

                <div>
                    @foreach($followed as $follow)
                    <div class="bg-white rounded-lg shadow mb-8">
                        <div class="px-3 py-1 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="{{ $follow->profile_picture }}" alt="{{ $follow->username }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="/user?username={{ $follow->username }}" class="text-gray-600 font-bold">{{ $follow->username }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-lg shadow mb-8">
                <div class="px-6 py-3 border-b border-gray-200">
                    <div class="font-bold text-gray-800">Who to follow</div>
                </div>

                <div>
                    {{-- @TODO --}}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    new SimpleMDE({
        element: document.getElementById("hwack_editor")
    });
</script>
@endsection