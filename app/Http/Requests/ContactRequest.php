<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // tel1, tel2, tel3 または tel_1, tel_2, tel_3 のどちらで送信されても結合
        $tel1 = $this->input('tel1', $this->input('tel_1'));
        $tel2 = $this->input('tel2', $this->input('tel_2'));
        $tel3 = $this->input('tel3', $this->input('tel_3'));

        if ($tel1 || $tel2 || $tel3) {
            $this->merge([
                'tel' => $tel1 . $tel2 . $tel3,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'last_name'   => ['required', 'string', 'max:255'],
            'first_name'  => ['required', 'string', 'max:255'],
            'gender'      => ['required', 'integer', 'in:1,2,3'],
            'email'       => ['required', 'string', 'email', 'max:255'],
            'tel'         => ['required', 'string', 'regex:/^[0-9]{10,11}$/'],
            'address'     => ['required', 'string', 'max:255'],
            'building'    => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'detail'      => ['required', 'string', 'max:120'],
        ];
    }

    public function messages(): array
    {
        return [
            'last_name.required'   => '姓を入力してください',
            'first_name.required'  => '名を入力してください',
            'gender.required'      => '性別を選択してください',
            'gender.in'            => '性別を選択してください',
            'email.required'       => 'メールアドレスを入力してください',
            'email.email'          => 'メールアドレスはメール形式で入力してください',
            'tel.required'         => '電話番号を入力してください',
            'tel.regex'            => '電話番号は10桁から11桁の数字で入力してください',
            'address.required'     => '住所を入力してください',
            'category_id.required' => 'お問い合わせの種類を選択してください',
            'category_id.exists'   => 'お問い合わせの種類を選択してください',
            'detail.required'      => 'お問い合わせ内容を入力してください',
            'detail.max'           => 'お問い合わせ内容は120文字以内で入力してください',
        ];
    }
}