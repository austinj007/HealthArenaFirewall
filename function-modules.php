<?php

    function generateOTP() {
        $otpLength = 6;
        $otp = '';
        for ($i = 0; $i < $otpLength; $i++) {
            $otp .= mt_rand(0, 9);
        }
        return $otp;
    }

    function getMacAddress() {
        $os = strtoupper(substr(PHP_OS, 0, 3));
        $command = ($os === 'WIN') ? 'ipconfig /all' : 'ifconfig -a';

        ob_start();
        passthru($command);
        $output = ob_get_clean();

        if ($os === 'WIN') {
            if (preg_match('/Physical Address[^:]*:\s*([^\s]+)/i', $output, $matches)) {
                return strtoupper(str_replace('-', ':', $matches[1]));
            }
        } else {
            if (preg_match('/HWaddr\s+([^\s]+)/i', $output, $matches)) {
                return strtoupper(str_replace(':', '-', $matches[1]));
            }
        }

        return '00:00:00:00:00:00';
    }

?>