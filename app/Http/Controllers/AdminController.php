<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contact;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    // お問い合わせ一覧表示・検索 (PG04)
    public function index(Request $request)
    {
        $query = $this->buildSearchQuery($request);

        // 要件：1ページあたり7件表示、検索条件をページネーションリンクに保持
        $contacts = $query->paginate(7)->appends($request->query());
        $categories = Category::all();

        return view('admin.index', compact('contacts', 'categories'));
    }

    // お問い合わせ詳細（モーダルまたは詳細表示）
    public function show($id)
    {
        $contact = Contact::with('category')->findOrFail($id);
        return view('admin.show', compact('contact'));
    }

    // お問い合わせ削除処理
    public function destroy($id)
    {
        Contact::findOrFail($id)->delete();
        return redirect()->route('admin.index');
    }

    // CSVエクスポート
    public function export(Request $request)
    {
        $query = $this->buildSearchQuery($request);
        $contacts = $query->get();

        $response = new StreamedResponse(function () use ($contacts) {
            $handle = fopen('php://output', 'w');
            
            // Excel文字化け防止用BOM
            fwrite($handle, "\xEF\xBB\xBF");

            // CSVヘッダー行
            fputcsv($handle, ['お名前', '性別', 'メールアドレス', 'お問い合わせの種類', 'お問い合わせ内容']);

            $genderLabels = [1 => '男性', 2 => '女性', 3 => 'その他'];
            foreach ($contacts as $contact) {
                fputcsv($handle, [
                    $contact->first_name . ' ' . $contact->last_name,
                    $genderLabels[$contact->gender] ?? '',
                    $contact->email,
                    $contact->category->content ?? '',
                    $contact->detail,
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="contacts.csv"');

        return $response;
    }

    // 検索クエリ共通化
    private function buildSearchQuery(Request $request)
    {
        $query = Contact::with('category');

        // キーワード検索（お名前・メールアドレス）
        if ($request->filled('keyword')) {
            $keyword = str_replace([' ', ' '], '', $request->input('keyword'));
            $query->where(function ($q) use ($keyword, $request) {
                $rawKeyword = $request->input('keyword');
                $q->where('first_name', 'like', "%{$rawKeyword}%")
                  ->orWhere('last_name', 'like', "%{$rawKeyword}%")
                  ->orWhereRaw("CONCAT(first_name, last_name) like ?", ["%{$keyword}%"])
                  ->orWhere('email', 'like', "%{$rawKeyword}%");
            });
        }

        // 性別絞り込み
        if ($request->filled('gender') && $request->input('gender') != '0') {
            $query->where('gender', $request->input('gender'));
        }

        // お問い合わせ種類絞り込み
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // 日付絞り込み
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        return $query->latest();
    }
}