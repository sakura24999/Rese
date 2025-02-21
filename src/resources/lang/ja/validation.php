<?php

return [
    'attributes' => [
        'name' => '名前',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
    ],

    'custom' => [
        'name' => [
            'required' => '名前は必須です',
            'max' => '名前は191文字以内で入力してください',
        ],
        'email' => [
            'required' => 'メールアドレスは必須です',
            'email' => '正しいメールアドレス形式で入力してください',
            'max' => 'メールアドレスは191文字以内で入力してください',
            'unique' => 'このメールアドレスは既に使用されています',
        ],
        'password' => [
            'required' => 'パスワードは必須です',
            'min' => 'パスワードは8文字以上で入力してください',
            'max' => 'パスワードは191文字以内で入力してください',
        ],
    ],

    // 基本的なバリデーションメッセージ
    'required' => ':attributeは必須です',
    'email' => ':attributeには有効なメールアドレスを指定してください',
    'max' => [
        'string' => ':attributeは:max文字以内で指定してください',
    ],
    'min' => [
        'string' => ':attributeは:min文字以上で指定してください',
    ],
    'unique' => ':attributeの値は既に存在しています',
];
