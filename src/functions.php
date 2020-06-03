<?php

if (!function_exists('alert')) {

    /**
     * Return app instance of Alert.
     *
     * @param string $title
     * @param string $message
     * @param string $type
     * @author Digi Pearl
     */
    function alert($title = '', $message = '', $type = '')
    {
        $alert = app('alert');
        if (!is_null($title)) {
            return $alert->alert($title, $message, $type);
        }
        return $alert;
    }
}

if (!function_exists('toast')) {

    /**
     * Return app instance of Toast.
     *
     * @param string $title
     * @param string $type
     * @param string $position
     * @author Digi Pearl
     */
    function toast($title = '', $type = null, $position = 'bottom-right')
    {
        $alert = app('alert');
        if (!is_null($title)) {
            return $alert->toast($title, $type, $position);
        }
        return $alert;
    }
}
