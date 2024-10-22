<?php

/**
 * Encrypt a message using the Atbash Cipher.
 * The Atbash Cipher is a simple substitution cipher where each letter in the plaintext is
 * replaced with its corresponding letter from the end of the alphabet (reverse alphabet).
 * Non-alphabet characters are not modified.
 *
 * @param string $plainText The plaintext to encrypt.
 * @return string The encrypted message.
 */
function atbashEncrypt($plainText)
{
    $result = '';
    $plainText = strtoupper($plainText);
    for ($i = 0; $i < strlen($plainText); $i++) {
        $char = $plainText[$i];
        if (ctype_alpha($char)) {
            $offset = ord('Z') - ord($char);
            $encryptedChar = chr(ord('A') + $offset);
        } else {
            $encryptedChar = $char; // Non-alphabet characters remain unchanged
        }
        $result .= $encryptedChar;
    }
    return $result;
}

/**
 * Decrypt a message encrypted using the Atbash Cipher.
 * Since the Atbash Cipher is its own inverse, decryption is the same as encryption.
 *
 * @param string $cipherText The ciphertext to decrypt.
 * @return string The decrypted message.
 */
function atbashDecrypt($cipherText)
{
    return atbashEncrypt($cipherText); // Decryption is the same as encryption
}

// test
//$plainText = "HELLO";
//$encryptedText = atbashEncrypt($plainText);
//$decryptedText = atbashDecrypt($encryptedText);
//var_dump($plainText==$decryptedText);
