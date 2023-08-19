<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="/api/files" method="post" enctype="multipart/form-data">
                        <input type="file" name="uploaded_file">
                        <button type="submit" name="submit" class="bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 hover:from-red-500 hover:via-pink-500 hover:to-purple-500 text-white font-bold py-2 px-4 rounded-full shadow-md hover:shadow-lg">Submit</button>
                        <button type="submit" name="view" class="bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 hover:from-red-500 hover:via-pink-500 hover:to-purple-500 text-white font-bold py-2 px-4 rounded-full shadow-md hover:shadow-lg"><a href="{{route('view')}}">View Files</a></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
