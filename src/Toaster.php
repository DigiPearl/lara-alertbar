<?php

namespace DigiPearl\LaraAlertbar;

use DigiPearl\LaraAlertbar\Storage\SessionStore;

class Toaster
{
    /**
     * Session storage.
     *
     * @var DigiPearl\LaraAlertbar\Storage\Session
     * @author Digi Pearl
     */
    protected $session;

    /**
     * Configuration options.
     *
     * @var array
     * @author Digi Pearl
     */
    protected $config;

    /**
     * Setting up the session
     *
     * @param SessionStore $session
     * @author Digi Pearl
     */
    public function __construct(SessionStore $session)
    {
        $this->setDefaultConfig();
        $this->session = $session;
    }

    /**
     * The default configuration for alert
     *
     * @return void
     * @author Digi Pearl
     */
    protected function setDefaultConfig()
    {
        $this->config = [
            'title' => '',
            'text' => '',
            'timer' => config('laralertbar.timer'),
            'width' => config('laralertbar.width'),
            'heightAuto' => config('laralertbar.height_auto'),
            'padding' => config('laralertbar.padding'),
            'showConfirmButton' => config('laralertbar.show_confirm_button'),
            'showCloseButton' => config('laralertbar.show_close_button'),
            'customClass' => [
                'container' => config('laralertbar.customClass.container'),
                'popup' => config('laralertbar.customClass.popup'),
                'header' => config('laralertbar.customClass.header'),
                'title' => config('laralertbar.customClass.title'),
                'closeButton' => config('laralertbar.customClass.closeButton'),
                'icon' => config('laralertbar.customClass.icon'),
                'image' => config('laralertbar.customClass.image'),
                'content' => config('laralertbar.customClass.content'),
                'input' => config('laralertbar.customClass.input'),
                'actions' => config('laralertbar.customClass.actions'),
                'confirmButton' => config('laralertbar.customClass.confirmButton'),
                'cancelButton' => config('laralertbar.customClass.cancelButton'),
                'footer' => config('laralertbar.customClass.footer')
            ]
        ];
    }

    /**
     * The default configuration for middleware alert.
     *
     * @return $config
     * @author Digi Pearl
     */
    public function middleware()
    {
        unset($this->config['position'], $this->config['heightAuto'], $this->config['width'], $this->config['padding'], $this->config['showCloseButton'], $this->config['timer']);

        $this->config['position'] = config('laralertbar.middleware.toast_position');
        $this->config['showCloseButton'] = config('laralertbar.middleware.toast_close_button');
        $this->config['timer'] = config('laralertbar.middleware.alert_auto_close');

        $this->flash();

        return $this;
    }

    /**
     * Flash an alert message.
     *
     * @param  string $title
     * @param  string $text
     * @param  array  $icon
     * @return void
     * @author Digi Pearl
     */
    public function alert($title = '', $text = '', $icon = null)
    {
        $this->config['title'] = $title;
        $this->config['text'] = $text;
        if (!is_null($icon)) {
            $this->config['icon'] = $icon;
        }
        $this->flash();
        return $this;
    }

    /**
     * Display a success typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     * @author Digi Pearl
     */
    public function success($title = '', $text = '')
    {
        $this->alert($title, $text, 'success');
        return $this;
    }

    /**
     * Display a info typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     * @author Digi Pearl
     */
    public function info($title = '', $text = '')
    {
        $this->alert($title, $text, 'info');
        return $this;
    }

    /**
     * Display a warning typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     * @author Digi Pearl
     */
    public function warning($title = '', $text = '')
    {
        $this->alert($title, $text, 'warning');
        return $this;
    }

    /**
     * Display a question typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     * @author Digi Pearl
     */
    public function question($title = '', $text = '')
    {
        $this->alert($title, $text, 'question');
        $this->showCancelButton();
        return $this;
    }

    /**
     * Display a error typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     * @author Digi Pearl
     */
    public function error($title = '', $text = '')
    {
        $this->alert($title, $text, 'error');
        return $this;
    }

    /**
     * Display a message with a custom image and CSS animation disabled.
     *
     * @param string $title
     * @param string $text
     * @param string $imageUrl
     * @param integer $imageWidth
     * @param integer $imageHeight
     * @param string $imageAlt
     * @author Digi Pearl
     */
    public function image($title = '', $text = '', $imageUrl, $imageWidth = 400, $imageHeight = 200, $imageAlt = '')
    {
        $this->config['title'] = $title;
        $this->config['text'] = $text;
        $this->config['imageUrl'] = $imageUrl;
        $this->config['imageWidth'] = $imageWidth;
        $this->config['imageHeight'] = $imageHeight;
        if (!is_null($imageAlt)) {
            $this->config['imageAlt'] = $imageAlt;
        } else {
            $this->config['imageAlt'] = $title;
        }
        $this->config['animation'] = false;

        $this->flash();
        return $this;
    }

    /**
     * Display a html typed alert message with html code.
     *
     * @param string $title
     * @param string $code
     * @param string $icon
     * @author Digi Pearl
     */
    public function html($title = '', $code = '', $icon = '')
    {
        $this->config['title'] = $title;
        $this->config['html'] = $code;
        if (!is_null($icon)) {
            $this->config['icon'] = $icon;
        }

        $this->flash();
        return $this;
    }

    /**
     * Display a toast message
     *
     * @param string $title
     * @param string $icon
     * @author Digi Pearl
     */
    public function toast($title = '', $icon = '')
    {
        $this->config['toast'] = true;
        $this->config['title'] = $title;
        $this->config['icon'] = $icon;
        $this->config['position'] = config('laralertbar.toast_position');
        $this->config['showCloseButton'] = true;
        $this->config['showConfirmButton'] = false;

        unset($this->config['heightAuto']);
        $this->flash();
        return $this;
    }

    /**
     * Convert any alert modal to Toast
     *
     * @param string $position
     * @author Digi Pearl
     */
    public function toToast($position = '')
    {
        $this->config['toast'] = true;
        $this->config['showCloseButton'] = true;
        if (!empty($position)) {
            $this->config['position'] = $position;
        } else {
            $this->config['position'] = config('laralertbar.toast_position');
        }
        $this->config['showConfirmButton'] = false;
        unset($this->config['width'], $this->config['padding']);

        $this->flash();
        return $this;
    }

    /**
     * Convert any alert modal to html
     *
     * @author Digi Pearl
     */
    public function toHtml()
    {
        $this->config['html'] = $this->config['text'];
        unset($this->config['text']);

        $this->flash();
        return $this;
    }

    /**
     * Add a custom image to alert
     *
     * @param string $imageUrl
     * @author Digi Pearl
     */
    public function addImage($imageUrl)
    {
        $this->config['imageUrl'] = $imageUrl;
        $this->config['showCloseButton'] = true;
        unset($this->config['icon']);

        $this->flash();
        return $this;
    }

    /**
     * Add footer section to alert()
     *
     * @param string $code
     * @author Digi Pearl
     */
    public function footer($code)
    {
        $this->config['footer'] = $code;

        $this->flash();
        return $this;
    }

    /**
     * positioned alert dialog
     *
     * @param string $position
     * @author Digi Pearl
     */
    public function position($position = 'top-end')
    {
        $this->config['position'] = $position;

        $this->flash();
        return $this;
    }

    /**
     * Modal window width
     * including paddings
     * (box-sizing: border-box).
     * Can be in px or %. The default width is 32rem
     *
     * @param string $width
     * @author Digi Pearl
     */
    public function width($width = '32rem')
    {
        $this->config['width'] = $width;

        $this->flash();
        return $this;
    }

    /**
     * Modal window padding.
     * The default padding is 1.25rem.
     *
     * @param string $padding
     * @author Digi Pearl
     */
    public function padding($padding = '1.25rem')
    {
        $this->config['padding'] = $padding;

        $this->flash();
        return $this;
    }

    /**
     * Modal window background
     * (CSS background property).
     * The default background is '#fff'.
     *
     * @param string $background
     * @author Digi Pearl
     */
    public function background($background = '#fff')
    {
        $this->config['background'] = $background;

        $this->flash();
        return $this;
    }

    /**
     * Set to false if you want to
     * focus the first element in tab
     * order instead of "Confirm"-button by default.
     *
     * @param boolean $focus
     * @author Digi Pearl
     */
    public function focusConfirm($focus = true)
    {
        $this->config['focusConfirm'] = $focus;
        unset($this->config['focusCancel']);

        $this->flash();
        return $this;
    }

    /**
     * Set to true if you want to focus the
     * "Cancel"-button by default.
     *
     * @param boolean $focus
     * @author Digi Pearl
     */
    public function focusCancel($focus = false)
    {
        $this->config['focusCancel'] = $focus;
        unset($this->config['focusConfirm']);

        $this->flash();
        return $this;
    }

    /**
     * Custom animation with [Animate.css](https://daneden.github.io/animate.css/)
     * CSS classes for animations when showing a popup (fade in):
     * CSS classes for animations when hiding a popup (fade out):
     *
     * @param string $showAnimation
     * @param string $hideAnimation
     * @author Digi Pearl
     */
    public function animation($showAnimation, $hideAnimation)
    {
        if (!config('laralertbar.animation.enable')) {
            config(['laralertbar.animation.enable' => true]);
        }
        $this->config['showClass'] = ['popup' => "animated {$showAnimation}"];
        $this->config['hideClass'] = ['popup' => "animated {$hideAnimation}"];

        $this->flash();
        return $this;
    }

    /**
     * Persistent the alert modal
     *
     * @param boolean $showConfirmBtn
     * @param boolean $showCloseBtn
     * @author Digi Pearl
     */
    public function persistent($showConfirmBtn = true, $showCloseBtn = false)
    {
        $this->config['allowEscapeKey'] = false;
        $this->config['allowOutsideClick'] = false;
        $this->removeTimer();
        if ($showConfirmBtn) {
            $this->showConfirmButton();
        }
        if ($showCloseBtn) {
            $this->showCloseButton();
        }

        $this->flash();
        return $this;
    }

    /**
     * auto close alert modal after
     * specifid time
     *
     * @param integer $milliseconds
     * @author Digi Pearl
     */
    public function autoClose($milliseconds = 5000)
    {
        $this->config['timer'] = $milliseconds;

        $this->flash();
        return $this;
    }

    /**
     * Display confirm button
     *
     * @param string $btnText
     * @param string $btnColor
     * @author Digi Pearl
     */
    public function showConfirmButton($btnText = 'Ok', $btnColor = '#3085d6')
    {
        $this->config['showConfirmButton'] = true;
        $this->config['confirmButtonText'] = $btnText;
        $this->config['confirmButtonColor'] = $btnColor;
        $this->config['allowOutsideClick'] = false;
        $this->removeTimer();

        $this->flash();
        return $this;
    }

    /**
     * Display cancel button
     *
     * @param string $btnText
     * @param string $btnColor
     * @author Digi Pearl
     */
    public function showCancelButton($btnText = 'Cancel', $btnColor = '#aaa')
    {
        $this->config['showCancelButton'] = true;
        $this->config['cancelButtonText'] = $btnText;
        $this->config['cancelButtonColor'] = $btnColor;
        $this->removeTimer();

        $this->flash();
        return $this;
    }

    /**
     * Display close button
     *
     * @param string $closeButtonAriaLabel
     * @author Digi Pearl
     */
    public function showCloseButton($closeButtonAriaLabel = 'aria-label')
    {
        $this->config['showCloseButton'] = true;
        $this->config['closeButtonAriaLabel'] = $closeButtonAriaLabel;

        $this->flash();
        return $this;
    }

    /**
     * Hide close button from alert or toast
     *
     * @author Digi Pearl
     */
    public function hideCloseButton()
    {
        $this->config['showCloseButton'] = false;

        $this->flash();
        return $this;
    }

    /**
     * Apply default styling to buttons.
     * If you want to use your own classes (e.g. Bootstrap classes)
     * set this parameter to false.
     *
     * @param boolean $buttonsStyling
     * @author Digi Pearl
     */
    public function buttonsStyling($buttonsStyling)
    {
        $this->config['buttonsStyling'] = $buttonsStyling;

        $this->flash();
        return $this;
    }

    /**
     * Use any HTML inside icons (e.g. Font Awesome)
     *
     * @param string $iconHtml
     * @author Digi Pearl
     */
    public function iconHtml($iconHtml)
    {
        $this->config['iconHtml'] = $iconHtml;

        $this->flash();
        return $this;
    }

    /**
     *  If set to true, the timer will have a progress bar at the bottom of a popup.
     * Mostly, this feature is useful with toasts.
     *
     * @author Digi Pearl
     */
    public function timerProgressBar()
    {
        $this->config['timerProgressBar'] = true;

        $this->flash();
        return $this;
    }

    /**
     * Reverse buttons position
     *
     * @author Faber44 <https://github.com/Faber44>
     */
    public function reverseButtons()
    {
        $this->config['reverseButtons'] = true;

        $this->flash();
        return $this;
    }

    /**
     * Remove the timer from config option.
     *
     * @author Digi Pearl
     */
    protected function removeTimer()
    {
        if (array_key_exists('timer', $this->config)) {
            unset($this->config['timer']);
        }
    }

    /**
     * Flash the config options for alert.
     *
     * @author Digi Pearl
     */
    public function flash()
    {
        foreach ($this->config as $key => $value) {
            $this->session->flash("alert.config.{$key}", $value);
        }
        $this->session->flash('alert.config', $this->buildConfig());
    }

    /**
     * Build Flash config options for flashing.
     *
     * @author Digi Pearl
     */
    public function buildConfig()
    {
        $config = $this->config;
        return json_encode($config);
    }
}
