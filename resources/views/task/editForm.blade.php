<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Form') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <!-- start form here -->
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Task Information') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Update your task's information.") }}
                            </p>
                        </header>
                        <!-- 
                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                            @csrf
                        </form> -->

                        <form method="post" action="{{ route('update.task') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                            @csrf
                            @method('post')

                            @if($errors->any())
                            {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
                            @endif

                            <x-text-input id="id" name="id" type="hidden" class="mt-1 block w-full" :value="$task->id" />

                            <div>
                                <x-input-label for="title" :value="__('Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $task->title)" required autofocus autocomplete="title" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="content" :value="__('Contents')" />
                                <textarea id="content" name="content" rows="4" cols="65">{{ $task->contents }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="parent" :value="__('Parent Task')" />
                                <select name="parent" id="parent">
                                    <option value="0">Select Parent</option>
                                    @foreach($availableTask as $value)
                                    <option @if($value['id'] == $task->parent_id) selected @else '' @endif value="{{ $value['id'] }}">{{ $value['title'] }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select name="status" id="status">
                                    @foreach($status as $value)
                                    <option @if($value->id == $task->status_id) selected @else '' @endif value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="publish" :value="__('Publish?')" />
                                <select name="publish" id="publish">
                                    <option @if($task->is_published === 1) selected @else '' @endif value="1">Yes</option>
                                    <option @if($task->is_published === 0) selected @else '' @endif value="0">No</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="image" :value="__('Image')" />
                                <x-text-input id="image" name="image" type="file" class="mt-1 block w-full" autofocus />
                                <img class="img-prev" src="{{ url('storage/uploads/'.$task->image) }}" alt="" title="" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>

                                @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>

                    <!-- end form here -->
                </div>
            </div>
        </div>
    </div>
    <style>
        .img-prev {
            max-width: 300px;
            max-height: 300px;
            object-fit: contain;
        }
    </style>
</x-app-layout>