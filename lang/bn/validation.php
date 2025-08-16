<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute গ্রহণ করতে হবে।',
    'accepted_if' => ':other যখন :value হয় তখন :attribute গ্রহণ করতে হবে।',
    'active_url' => ':attribute একটি বৈধ URL নয়।',
    'after' => ':attribute অবশ্যই :date এর পরের একটি তারিখ হতে হবে।',
    'after_or_equal' => ':attribute অবশ্যই :date এর পরের বা সমান একটি তারিখ হতে হবে।',
    'alpha' => ':attribute শুধুমাত্র অক্ষর থাকতে পারে।',
    'alpha_dash' => ':attribute শুধুমাত্র অক্ষর, সংখ্যা, ড্যাশ এবং আন্ডারস্কোর থাকতে পারে।',
    'alpha_num' => ':attribute শুধুমাত্র অক্ষর এবং সংখ্যা থাকতে পারে।',
    'array' => ':attribute একটি অ্যারে হতে হবে।',
    'before' => ':attribute অবশ্যই :date এর আগের একটি তারিখ হতে হবে।',
    'before_or_equal' => ':attribute অবশ্যই :date এর আগের বা সমান একটি তারিখ হতে হবে।',
    'between' => [
        'array' => ':attribute এর :min থেকে :max আইটেম থাকতে হবে।',
        'file' => ':attribute :min থেকে :max কিলোবাইট হতে হবে।',
        'numeric' => ':attribute :min থেকে :max এর মধ্যে হতে হবে।',
        'string' => ':attribute :min থেকে :max অক্ষরের মধ্যে হতে হবে।',
    ],
    'boolean' => ':attribute ক্ষেত্রটি সত্য বা মিথ্যা হতে হবে।',
    'confirmed' => ':attribute নিশ্চিতকরণ মেলে না।',
    'current_password' => 'পাসওয়ার্ডটি ভুল।',
    'date' => ':attribute একটি বৈধ তারিখ নয়।',
    'date_equals' => ':attribute অবশ্যই :date এর সমান একটি তারিখ হতে হবে।',
    'date_format' => ':attribute :format ফরম্যাটের সাথে মেলে না।',
    'different' => ':attribute এবং :other আলাদা হতে হবে।',
    'digits' => ':attribute :digits সংখ্যার হতে হবে।',
    'digits_between' => ':attribute :min থেকে :max সংখ্যার মধ্যে হতে হবে।',
    'dimensions' => ':attribute এর অবৈধ ছবির মাত্রা রয়েছে।',
    'distinct' => ':attribute ক্ষেত্রটির একটি ডুপ্লিকেট মান রয়েছে।',
    'email' => ':attribute একটি বৈধ ইমেইল ঠিকানা হতে হবে।',
    'ends_with' => ':attribute নিম্নলিখিতগুলির একটি দিয়ে শেষ হতে হবে: :values।',
    'exists' => 'নির্বাচিত :attribute অবৈধ।',
    'file' => ':attribute একটি ফাইল হতে হবে।',
    'filled' => ':attribute ক্ষেত্রটির একটি মান থাকতে হবে।',
    'gt' => [
        'array' => ':attribute এর :value এর চেয়ে বেশি আইটেম থাকতে হবে।',
        'file' => ':attribute :value কিলোবাইটের চেয়ে বড় হতে হবে।',
        'numeric' => ':attribute :value এর চেয়ে বড় হতে হবে।',
        'string' => ':attribute :value অক্ষরের চেয়ে বড় হতে হবে।',
    ],
    'gte' => [
        'array' => ':attribute এর :value আইটেম বা তার বেশি থাকতে হবে।',
        'file' => ':attribute :value কিলোবাইটের সমান বা বড় হতে হবে।',
        'numeric' => ':attribute :value এর সমান বা বড় হতে হবে।',
        'string' => ':attribute :value অক্ষরের সমান বা বড় হতে হবে।',
    ],
    'image' => ':attribute একটি ছবি হতে হবে।',
    'in' => 'নির্বাচিত :attribute অবৈধ।',
    'in_array' => ':attribute ক্ষেত্রটি :other এ বিদ্যমান নেই।',
    'integer' => ':attribute একটি পূর্ণসংখ্যা হতে হবে।',
    'ip' => ':attribute একটি বৈধ IP ঠিকানা হতে হবে।',
    'ipv4' => ':attribute একটি বৈধ IPv4 ঠিকানা হতে হবে।',
    'ipv6' => ':attribute একটি বৈধ IPv6 ঠিকানা হতে হবে।',
    'json' => ':attribute একটি বৈধ JSON স্ট্রিং হতে হবে।',
    'lt' => [
        'array' => ':attribute এর :value এর চেয়ে কম আইটেম থাকতে হবে।',
        'file' => ':attribute :value কিলোবাইটের চেয়ে ছোট হতে হবে।',
        'numeric' => ':attribute :value এর চেয়ে ছোট হতে হবে।',
        'string' => ':attribute :value অক্ষরের চেয়ে ছোট হতে হবে।',
    ],
    'lte' => [
        'array' => ':attribute এর :value এর চেয়ে বেশি আইটেম থাকতে পারবে না।',
        'file' => ':attribute :value কিলোবাইটের সমান বা ছোট হতে হবে।',
        'numeric' => ':attribute :value এর সমান বা ছোট হতে হবে।',
        'string' => ':attribute :value অক্ষরের সমান বা ছোট হতে হবে।',
    ],
    'max' => [
        'array' => ':attribute এর :max এর চেয়ে বেশি আইটেম থাকতে পারবে না।',
        'file' => ':attribute :max কিলোবাইটের চেয়ে বড় হতে পারবে না।',
        'numeric' => ':attribute :max এর চেয়ে বড় হতে পারবে না।',
        'string' => ':attribute :max অক্ষরের চেয়ে বড় হতে পারবে না।',
    ],
    'mimes' => ':attribute নিম্নলিখিত ধরনের একটি ফাইল হতে হবে: :values।',
    'mimetypes' => ':attribute নিম্নলিখিত ধরনের একটি ফাইল হতে হবে: :values।',
    'min' => [
        'array' => ':attribute এর কমপক্ষে :min আইটেম থাকতে হবে।',
        'file' => ':attribute কমপক্ষে :min কিলোবাইট হতে হবে।',
        'numeric' => ':attribute কমপক্ষে :min হতে হবে।',
        'string' => ':attribute কমপক্ষে :min অক্ষর হতে হবে।',
    ],
    'not_in' => 'নির্বাচিত :attribute অবৈধ।',
    'not_regex' => ':attribute ফরম্যাটটি অবৈধ।',
    'numeric' => ':attribute একটি সংখ্যা হতে হবে।',
    'password' => [
        'letters' => ':attribute এ কমপক্ষে একটি অক্ষর থাকতে হবে।',
        'mixed' => ':attribute এ কমপক্ষে একটি বড় হাতের এবং একটি ছোট হাতের অক্ষর থাকতে হবে।',
        'numbers' => ':attribute এ কমপক্ষে একটি সংখ্যা থাকতে হবে।',
        'symbols' => ':attribute এ কমপক্ষে একটি প্রতীক থাকতে হবে।',
        'uncompromised' => 'প্রদত্ত :attribute একটি ডেটা লিক এ দেখা গেছে। অনুগ্রহ করে একটি ভিন্ন :attribute বেছে নিন।',
    ],
    'present' => ':attribute ক্ষেত্রটি উপস্থিত থাকতে হবে।',
    'regex' => ':attribute ফরম্যাটটি অবৈধ।',
    'required' => ':attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'required_if' => ':other যখন :value হয় তখন :attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'required_unless' => ':other :values এ না থাকলে :attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'required_with' => ':values উপস্থিত থাকলে :attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'required_with_all' => ':values উপস্থিত থাকলে :attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'required_without' => ':values উপস্থিত না থাকলে :attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'required_without_all' => ':values এর কোনটি উপস্থিত না থাকলে :attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'same' => ':attribute এবং :other মিলতে হবে।',
    'size' => [
        'array' => ':attribute এ :size আইটেম থাকতে হবে।',
        'file' => ':attribute :size কিলোবাইট হতে হবে।',
        'numeric' => ':attribute :size হতে হবে।',
        'string' => ':attribute :size অক্ষর হতে হবে।',
    ],
    'starts_with' => ':attribute নিম্নলিখিতগুলির একটি দিয়ে শুরু হতে হবে: :values।',
    'string' => ':attribute একটি স্ট্রিং হতে হবে।',
    'timezone' => ':attribute একটি বৈধ সময় অঞ্চল হতে হবে।',
    'unique' => ':attribute ইতিমধ্যে নেওয়া হয়েছে।',
    'uploaded' => ':attribute আপলোড করতে ব্যর্থ।',
    'url' => ':attribute একটি বৈধ URL হতে হবে।',
    'uuid' => ':attribute একটি বৈধ UUID হতে হবে।',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "rule.attribute" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'নাম',
        'email' => 'ইমেইল',
        'password' => 'পাসওয়ার্ড',
        'password_confirmation' => 'পাসওয়ার্ড নিশ্চিতকরণ',
        'phone' => 'ফোন',
        'address' => 'ঠিকানা',
        'title' => 'শিরোনাম',
        'content' => 'বিষয়বস্তু',
        'message' => 'বার্তা',
        'subject' => 'বিষয়',
        'description' => 'বিবরণ',
        'image' => 'ছবি',
        'file' => 'ফাইল',
        'department' => 'বিভাগ',
        'class' => 'ক্লাস',
        'student' => 'ছাত্র',
        'teacher' => 'শিক্ষক',
        'parent' => 'অভিভাবক',
        'admin' => 'অ্যাডমিন',
        'role' => 'ভূমিকা',
        'status' => 'অবস্থা',
        'date' => 'তারিখ',
        'time' => 'সময়',
        'start_time' => 'শুরুর সময়',
        'end_time' => 'শেষের সময়',
        'exam_date' => 'পরীক্ষার তারিখ',
        'admission_date' => 'ভর্তির তারিখ',
        'birth_date' => 'জন্ম তারিখ',
        'attendance_date' => 'উপস্থিতির তারিখ',
        'priority' => 'অগ্রাধিকার',
        'is_active' => 'সক্রিয়',
        'is_read' => 'পঠিত',
        'order' => 'ক্রম',
        'button_text' => 'বোতামের লেখা',
        'button_url' => 'বোতামের URL',
        'subtitle' => 'উপশিরোনাম',
    ],

];
