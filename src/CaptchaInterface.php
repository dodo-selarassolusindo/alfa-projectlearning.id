<?php

namespace PHPMaker2024\prj_alfa;

/**
 * Captcha interface
 */
interface CaptchaInterface
{

    public function getHtml();

    public function getConfirmHtml();

    public function validate();

    public function getScript();
}
