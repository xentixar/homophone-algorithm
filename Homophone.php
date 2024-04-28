<?php

require_once __DIR__ . "/config/plain_texts.php";

$map = $argv[1] ?? "one";
$plain_text = $array[$argv[2] ?? 0];

require_once __DIR__ . "/config/encryption_map_$map.php";

class Homophone
{
    private $encryption_set;
    private $decryption_set;

    public function __construct(array $encryption_set)
    {
        $this->encryption_set = $encryption_set;

        $this->decryption_set = array();
        foreach ($this->encryption_set as $key => $value) {
            foreach ($value as $v) {
                $this->decryption_set[$v] = $key;
            }
        }
    }

    public function encrypt($plain_text)
    {
        $encrypted_text = '';
        $plain_text = strtolower($plain_text);

        for ($i = 0; $i < strlen($plain_text); $i++) {
            $char = $plain_text[$i];
            if (isset($this->encryption_set[$char])) {
                $random_index = array_rand($this->encryption_set[$char]);
                $encrypted_char = $this->encryption_set[$char][$random_index];
                $encrypted_text .= $encrypted_char;
            } else {
                $encrypted_text .= $char;
            }
        }

        return $encrypted_text;
    }

    public function decrypt($encrypted_text)
    {
        $decryption_text = '';

        for ($i = 0; $i < strlen($encrypted_text);) {

            if (is_numeric($encrypted_text[$i])) {
                $char = $encrypted_text[$i] . ($encrypted_text[$i + 1] ?? '');
            } else {
                $char = $encrypted_text[$i];
            }
            if (isset($this->decryption_set[$char])) {
                $decryption_text .= $this->decryption_set[$char];
            } else {
                $decryption_text .= $char;
            }

            is_numeric($encrypted_text[$i]) ? $i += 2 : $i++;
        }

        return $decryption_text;
    }
}

$cipher = new Homophone($encryption_set);

$encrypted_text = $cipher->encrypt($plain_text);
echo "Encrypted text: " . $encrypted_text . "\n";

if (isset($argv[3]) && $argv[3] == 'true') {
    $decryption_text = $cipher->decrypt($encrypted_text);
    echo "Decrypted text: " . $decryption_text . "\n";
}
