<form method="POST" action="{{ route('profile.update.picture') }}" enctype="multipart/form-data" class="space-y-4">
    @csrf
    <div>
        <x-input-label for="profile_picture" :value="__('Profile Picture')" />
        <x-text-input id="profile_picture" name="profile_picture" type="file" class="mt-1 block w-full" accept="image/*" required />
        <x-input-error :messages="$errors->get('profile_picture')" class="mt-2" />
    </div>

    <div>
        <br>
        <x-primary-button class="bg-primary">{{ __('Upload Picture') }}</x-primary-button>
    </div>

    @if (session('status'))
        <div class="mt-4 p-4 bg-green-500 text-white rounded">
            {{ session('status') }}
        </div>
    @endif
</form>