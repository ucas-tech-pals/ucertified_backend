<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('File Links') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-gray-100 dark:bg-gray-800">
                    <div class="container mt-5">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>File Links</th>
                                    <th>File Names</th>
                                    <th>File types</th>
                                    <th>File sizes</th>
                                    <th>Created at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stored_files as $file)
                                <tr>
                                    <td class="text-white" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                        <a href="{{ $file['link'] }}" title="{{ $file['link'] }}">{{ $file['link'] }}</a>
                                    </td>
                                    <td class="text-white">{{ $file['name'] }}</td>
                                    <td class="text-white">{{ $file['type'] }}</td>
                                    <td class="text-white">{{ $file['size'] }}</td>
                                    <td class="text-white">{{ $file['created'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    /* Apply the same styles as Laravel Breeze's app.css */
    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #333;
        border-collapse: collapse;
        background-color: transparent;
    }
    .thead-light {
        color: #333;
        background-color: #f3f4f6;
    }
    .table th,
    .table td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #edf2f7;
    }
    .table a {
        color: #007bff;
        text-decoration: none;
        transition: color 0.3s;
    }
    .table a:hover {
        color: #0056b3;
    }
    .text-white {
        color: #fff;
    }
</style>
