<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Nama Aplikasi
    |--------------------------------------------------------------------------
    |
    | Nilai ini adalah nama aplikasi yang akan digunakan ketika framework
    | perlu menampilkan nama aplikasi di notifikasi atau elemen UI lainnya.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Environment Aplikasi
    |--------------------------------------------------------------------------
    |
    | Nilai ini menentukan "environment" aplikasi saat ini. Ini dapat
    | menentukan bagaimana Anda ingin mengonfigurasi berbagai layanan
    | yang digunakan aplikasi. Atur di file ".env".
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Mode Debug Aplikasi
    |--------------------------------------------------------------------------
    |
    | Ketika aplikasi dalam mode debug, pesan error detail dengan stack trace
    | akan ditampilkan untuk setiap error. Jika dinonaktifkan, halaman error
    | generik sederhana akan ditampilkan.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | URL Aplikasi
    |--------------------------------------------------------------------------
    |
    | URL ini digunakan oleh console untuk menghasilkan URL dengan benar saat
    | menggunakan Artisan command line tool. Atur ke root aplikasi agar
    | tersedia dalam perintah Artisan.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Timezone Aplikasi
    |--------------------------------------------------------------------------
    |
    | Di sini Anda dapat menentukan timezone default untuk aplikasi, yang
    | akan digunakan oleh fungsi tanggal dan waktu PHP. Default adalah "UTC"
    | yang cocok untuk sebagian besar kasus penggunaan.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Locale Aplikasi
    |--------------------------------------------------------------------------
    |
    | Locale aplikasi menentukan locale default yang akan digunakan oleh
    | metode translasi/lokalisasi Laravel. Opsi ini dapat diatur ke locale
    | apa pun yang Anda rencanakan untuk memiliki string translasi.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is utilized by Laravel's encryption services and should be set
    | to a random, 32 character string to ensure that all encrypted values
    | are secure. You should do this prior to deploying the application.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
