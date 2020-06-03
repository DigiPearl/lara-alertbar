<?php

namespace DigiPearl\LaraAlertbar\Storage;

interface SessionStore
{
    /**
     * Flash some data into the session.
     *
     * @param $name
     * @param $data
     */
    public function flash($name, $data);
}
