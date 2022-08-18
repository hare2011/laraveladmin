<?php

if (!function_exists('admin_path')) {

    /**
     * Get admin path.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_path($path = '') {
        return ucfirst(config('admin.directory')) . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

}

if (!function_exists('admin_url')) {

    /**
     * Get admin url.
     *
     * @param string $path
     * @param mixed  $parameters
     * @param bool   $secure
     *
     * @return string
     */
    function admin_url($path = '', $parameters = [], $secure = null) {
        if (\Illuminate\Support\Facades\URL::isValidUrl($path)) {
            return $path;
        }

        $secure = $secure ?: config('admin.secure');

        return url(admin_base_path($path), $parameters, $secure);
    }

}

if (!function_exists('admin_base_path')) {

    /**
     * Get admin url.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_base_path($path = '') {
        $prefix = '/' . trim(config('admin.prefix'), '/');

        $prefix = ($prefix == '/') ? '' : $prefix;

        return $prefix . '/' . trim($path, '/');
    }

}

if (!function_exists('admin_toastr')) {

    /**
     * Flash a toastr message bag to session.
     *
     * @param string $message
     * @param string $type
     * @param array  $options
     *
     * @return string
     */
    function admin_toastr($message = '', $type = 'success', $options = []) {
        $toastr = new \Illuminate\Support\MessageBag(get_defined_vars());

        \Illuminate\Support\Facades\Session::flash('toastr', $toastr);
    }

}

if (!function_exists('admin_asset')) {

    /**
     * @param $path
     *
     * @return string
     */
    function admin_asset($path) {
        return asset($path, config('admin.secure'));
    }

}

if (!function_exists("sm4_encrypt")) {

    /**
     * sm4国密加密
     * @param type $key
     * @param type $value
     * @return type
     */
    function sm4_encrypt($key, $value) {
        
        if (!in_array('sm4-cbc', openssl_get_cipher_methods())) {
            throw new \Exception("不支持 sm4 加密");
        }

        $iv = random_bytes(openssl_cipher_iv_length('sm4-cbc'));
        return openssl_encrypt($value, 'sm4-cbc', $key, OPENSSL_RAW_DATA, $iv);
    }

}

if (!function_exists("sm4_decrypt")) {

    /**
     * sm4国密解密
     * @param type $key
     * @param type $value
     * @return type
     */
    function sm4_decrypt($key, $value) {
        
        if (!in_array('sm4-cbc', openssl_get_cipher_methods())) {
            throw new Exception('不支持 sm4 解密');
        }

        $iv = random_bytes(openssl_cipher_iv_length('sm4-cbc'));
        return openssl_decrypt($value, 'sm4-cbc', $key, OPENSSL_RAW_DATA, $iv);
    }

}
