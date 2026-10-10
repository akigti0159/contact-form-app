<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Category;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    // 入力画面の表示 (PG01)
    public function index()
    {
        $categories = Category::all();
        return view('contact.index', compact('categories'));
    }

    // 確認画面の表示 (PG02)
    public function confirm(ContactRequest $request)
    {
        $validated = $request->validated();

        $category = Category::find($request->category_id);
        
        return view('contact.confirm', compact('validated', 'category'));
    }

    // データを保存してサンクス画面へリダイレクト (PG03)
    public function store(ContactRequest $request)
    {
        // 「修正する」ボタンが押された場合は入力値を保持してトップに戻る
        if ($request->has('back')) {
            return redirect()->route('contact.index')->withInput();
        }

        // お問い合わせ内容の保存
        $contactData = $request->only([
            'category_id', 'first_name', 'last_name', 'gender',
            'email', 'tel', 'address', 'building', 'detail'
        ]);
        Contact::create($contactData);

        // 二重送信防止のため、サンクス画面（GET /thanks）へ転送
        return redirect()->route('contact.thanks');
    }

    // サンクス画面の表示 (PG03)
    public function thanks()
    {
        return view('contact.thanks');
    }
}