<x-app-layout>
    <div class="min-h-screen bg-white py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto border border-gray-200 rounded p-6">
            <h2 class="text-xl font-serif text-amber-900 mb-6 text-center">お問い合わせ詳細</h2>

            <table class="w-full text-left border-collapse mb-6">
                <tr class="border-b">
                    <th class="py-2 text-gray-500 w-1/3">お名前</th>
                    <td class="py-2 text-gray-800">{{ $contact->first_name }} {{ $contact->last_name }}</td>
                </tr>
                <tr class="border-b">
                    <th class="py-2 text-gray-500">性別</th>
                    <td class="py-2 text-gray-800">
                        @php $genderLabels = [1 => '男性', 2 => '女性', 3 => 'その他']; @endphp
                        {{ $genderLabels[$contact->gender] ?? '' }}
                    </td>
                </tr>
                <tr class="border-b">
                    <th class="py-2 text-gray-500">メールアドレス</th>
                    <td class="py-2 text-gray-800">{{ $contact->email }}</td>
                </tr>
                <tr class="border-b">
                    <th class="py-2 text-gray-500">電話番号</th>
                    <td class="py-2 text-gray-800">{{ $contact->tell }}</td>
                </tr>
                <tr class="border-b">
                    <th class="py-2 text-gray-500">住所</th>
                    <td class="py-2 text-gray-800">{{ $contact->address }} {{ $contact->building }}</td>
                </tr>
                <tr class="border-b">
                    <th class="py-2 text-gray-500">お問い合わせの種類</th>
                    <td class="py-2 text-gray-800">{{ $contact->category->content ?? '' }}</td>
                </tr>
                <tr class="border-b">
                    <th class="py-2 text-gray-500">お問い合わせ内容</th>
                    <td class="py-2 text-gray-800 whitespace-pre-wrap">{{ $contact->detail }}</td>
                </tr>
            </table>

            <div class="flex justify-between items-center">
                <a href="/admin" class="px-5 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">戻る</a>

                <form action="/admin/contacts/{{ $contact->id }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded hover:bg-red-700" onclick="return confirm('本当に削除しますか？')">削除</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>