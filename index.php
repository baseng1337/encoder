<?php
/*
    XSHIKATA ENCODER V6 (INSANE EDITION)
    UI: Cyberpunk V1 (Preserved)
    New Methods: RC4, Invisible Ink, Emoji Crypt
*/

$result = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'];
    $method = $_POST['method'];

    if (empty($code)) {
        $error = "Input code cannot be empty!";
    } else {
        // 1. BERSIHKAN TAG PHP (Wajib agar eval jalan)
        $clean_code = trim($code);
        $clean_code = preg_replace('/^<\?php\s*/i', '', $clean_code);
        $clean_code = preg_replace('/\?>$/', '', $clean_code);
        $clean_code = trim($clean_code);

        // ======================================================
        // METHOD 1: MATHEMATICAL SHIFT (Classic)
        // ======================================================
        if ($method == 'math') {
            $key = "xshikata";
            $key_len = strlen($key);
            $encoded_array = [];
            for ($i = 0; $i < strlen($clean_code); $i++) {
                $encoded_array[] = (ord($clean_code[$i]) + ord($key[$i % $key_len])) * 3;
            }
            $payload = implode('.', $encoded_array);
            $decoder = 'function xshikata($d){$k="xshikata";$p=explode(".",$d);$o="";$l=strlen($k);$i=0;foreach($p as $v){if($v==""||!is_numeric($v))continue;$o.=chr(($v/3)-ord($k[$i%$l]));$i++;}eval($o);}';
            $result = '<?php' . "\n" . '/* Encoded: Math Shift */' . "\n" . $decoder . "\n" . 'xshikata(\'' . $payload . '\');' . "\n" . '?>';
        }

        // ======================================================
        // METHOD 2: QUANTUM GZIP (Compression)
        // ======================================================
        elseif ($method == 'gzip') {
            $compressed = gzdeflate($clean_code, 9);
            $payload = base64_encode($compressed);
            $decoder = 'function xshikata($z){$b=base64_decode($z);$c=gzinflate($b);if($c)eval($c);}';
            $result = '<?php' . "\n" . '/* Encoded: Quantum Gzip */' . "\n" . $decoder . "\n" . 'xshikata(\'' . $payload . '\');' . "\n" . '?>';
        }

        // ======================================================
        // METHOD 3: BINARY XOR (Strong)
        // ======================================================
        elseif ($method == 'xor') {
            $key = substr(md5(time()), 0, 8);
            $xor_res = "";
            for($i=0; $i<strlen($clean_code); $i++) {
                $xor_res .= $clean_code[$i] ^ $key[$i % 8];
            }
            $payload = base64_encode($xor_res);
            $decoder = 'function xshikata($x){$k="' . $key . '";$d=base64_decode($x);$o="";for($i=0;$i<strlen($d);$i++){$o.=$d[$i]^$k[$i%8];}eval($o);}';
            $result = '<?php' . "\n" . '/* Encoded: Binary XOR */' . "\n" . $decoder . "\n" . 'xshikata(\'' . $payload . '\');' . "\n" . '?>';
        }

        // ======================================================
        // METHOD 4: RC4 GHOST (MILITARY GRADE-ISH STREAM CIPHER)
        // ======================================================
        elseif ($method == 'rc4') {
            $key = md5(microtime()); // Kunci dinamis
            
            // Fungsi Enkripsi RC4 PHP Native
            $s = array(); for($i=0;$i<256;$i++){$s[$i]=$i;}
            $j = 0;
            for($i=0;$i<256;$i++){
                $j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
                $temp = $s[$i]; $s[$i] = $s[$j]; $s[$j] = $temp;
            }
            $i = 0; $j = 0; $res = '';
            for($y=0;$y<strlen($clean_code);$y++){
                $i = ($i + 1) % 256;
                $j = ($j + $s[$i]) % 256;
                $temp = $s[$i]; $s[$i] = $s[$j]; $s[$j] = $temp;
                $res .= $clean_code[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
            }
            
            $payload = base64_encode($res);
            
            // Decoder RC4 (Minified)
            $decoder = 'function xshikata($d,$k){$s=range(0,255);$j=0;for($i=0;$i<256;$i++){$j=($j+$s[$i]+ord($k[$i%strlen($k)]))%256;$t=$s[$i];$s[$i]=$s[$j];$s[$j]=$t;}$i=0;$j=0;$r="";$d=base64_decode($d);for($y=0;$y<strlen($d);$y++){$i=($i+1)%256;$j=($j+$s[$i])%256;$t=$s[$i];$s[$i]=$s[$j];$s[$j]=$t;$r.=$d[$y]^chr($s[($s[$i]+$s[$j])%256]);}eval($r);}';
            
            $result = '<?php' . "\n" . '/* Encoded: RC4 Ghost */' . "\n" . $decoder . "\n" . 'xshikata(\'' . $payload . '\', \'' . $key . '\');' . "\n" . '?>';
        }

        // ======================================================
        // METHOD 5: INVISIBLE INK (ZERO WIDTH STEGANOGRAPHY)
        // ======================================================
        elseif ($method == 'invisible') {
            // Konsep: Ubah binary 0 jadi ZeroWidthSpace, 1 jadi ZeroWidthNonJoiner
            $bin = '';
            for($i=0; $i<strlen($clean_code); $i++) {
                $bin .= sprintf("%08d", decbin(ord($clean_code[$i])));
            }
            
            // Mapping: 0 -> \xE2\x80\x8B, 1 -> \xE2\x80\x8C
            $payload = str_replace(['0', '1'], ["\xE2\x80\x8B", "\xE2\x80\x8C"], $bin);
            
            // Decoder Invisible
            $decoder = 'function xshikata($s){$b=str_replace(["\xE2\x80\x8B","\xE2\x80\x8C"],["0","1"],$s);$o="";for($i=0;$i<strlen($b);$i+=8){$o.=chr(bindec(substr($b,$i,8)));}eval($o);}';
            
            // Karena payloadnya karakter tak terlihat, kita bungkus hati-hati
            $result = '<?php' . "\n" . '/* Encoded: Invisible Ink (Select Empty Space Below to See) */' . "\n" . $decoder . "\n" . 'xshikata("' . $payload . '");' . "\n" . '?>';
        }

        // ======================================================
        // METHOD 6: EMOJI CRYPT (VISUAL OBFUSCATION)
        // ======================================================
        elseif ($method == 'emoji') {
            $hex = bin2hex($clean_code);
            // Peta 0-f ke Emoji
            $map = ['0'=>'🌑','1'=>'🌒','2'=>'🌓','3'=>'🌔','4'=>'🌕','5'=>'🌖','6'=>'🌗','7'=>'🌘','8'=>'🌙','9'=>'🌚','a'=>'🌛','b'=>'🌜','c'=>'🌝','d'=>'🌞','e'=>'⭐','f'=>'🌟'];
            
            $payload = strtr($hex, $map);
            
            // Decoder Emoji
            $decoder = 'function xshikata($e){$m=["🌑"=>"0","🌒"=>"1","🌓"=>"2","🌔"=>"3","🌕"=>"4","🌖"=>"5","🌗"=>"6","🌘"=>"7","🌙"=>"8","🌚"=>"9","🌛"=>"a","🌜"=>"b","🌝"=>"c","🌞"=>"d","⭐"=>"e","🌟"=>"f"];$h=strtr($e,$m);eval(pack("H*",$h));}';
            
            $result = '<?php' . "\n" . '/* Encoded: Emoji Crypt */' . "\n" . $decoder . "\n" . 'xshikata(\'' . $payload . '\');' . "\n" . '?>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XSHIKATA V6 INSANE</title>
    <style>
        :root {
            --bg: #0d1117;
            --panel: #161b22;
            --border: #30363d;
            --accent: #238636;
            --text: #c9d1d9;
            --code: #58a6ff;
            --neon: #00ff00;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
            display: flex;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 900px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid var(--accent);
            padding-bottom: 15px;
        }
        h1 { color: var(--neon); letter-spacing: 2px; text-shadow: 0 0 10px rgba(0,255,0,0.3); }
        .box {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        textarea {
            width: 100%;
            background: #000;
            color: #0f0;
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 15px;
            font-family: inherit;
            font-size: 14px;
            resize: vertical;
            outline: none;
        }
        textarea:focus { border-color: var(--code); }
        .controls {
            display: flex;
            gap: 15px;
            margin-top: 15px;
            align-items: center;
        }
        select, button {
            padding: 12px 20px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-family: inherit;
        }
        select {
            background: var(--bg);
            color: white;
            border: 1px solid var(--border);
            flex: 1;
        }
        button.btn-main {
            background: var(--accent);
            color: white;
            border: none;
            flex: 1;
            transition: 0.3s;
        }
        button.btn-main:hover { background: #2ea043; box-shadow: 0 0 15px rgba(46, 160, 67, 0.4); }
        
        .result-area {
            margin-top: 30px;
            animation: fadeIn 0.5s ease;
        }
        .copy-btn {
            background: #1f6feb;
            color: white;
            border: none;
            padding: 8px 15px;
            float: right;
            margin-bottom: 5px;
            font-size: 12px;
        }
        .label { color: #8b949e; font-size: 12px; margin-bottom: 5px; display: block; }
        
        @keyframes fadeIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
        .error { color: #ff5555; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>[ XSHIKATA ENCODER V6 ]</h1>
        <p style="color:#8b949e; font-size:12px; margin-top:5px;">INSANE POLYMORPHIC OBFUSCATOR</p>
    </div>

    <div class="box">
        <?php if($error): ?>
            <div class="error">ERROR: <?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <span class="label">PASTE PHP CODE (FULL SOURCE):</span>
            <textarea name="code" rows="12" placeholder="<?php echo "<?php\n// Paste code here...\n?>"; ?>"></textarea>
            
            <div class="controls">
                <select name="method">
                    <option value="math">METHOD 1: Math Shift (Classic)</option>
                    <option value="gzip">METHOD 2: Quantum Gzip (Short)</option>
                    <option value="xor">METHOD 3: Binary XOR (Strong)</option>
                    <option value="rc4">METHOD 4: RC4 Ghost (Military Grade)</option>
                    <option value="invisible">METHOD 5: Invisible Ink (Stealth)</option>
                    <option value="emoji">METHOD 6: Emoji Crypt (Visual)</option>
                </select>
                <button type="submit" class="btn-main">ENCODE PAYLOAD</button>
            </div>
        </form>
    </div>

    <?php if ($result): ?>
    <div class="result-area">
        <button onclick="copyResult()" class="copy-btn">COPY CODE</button>
        <span class="label" style="color:var(--neon)">ENCRYPTION SUCCESSFUL (READY TO DEPLOY):</span>
        <textarea id="out" rows="15" readonly><?php echo htmlspecialchars($result); ?></textarea>
    </div>
    <script>
        function copyResult() {
            var t=document.getElementById('out');
            t.select(); t.setSelectionRange(0,99999);
            navigator.clipboard.writeText(t.value);
            alert('Code Copied!');
        }
    </script>
    <?php endif; ?>
    
    <div style="text-align:center; margin-top:30px; color:#30363d; font-size:11px;">
        XSHIKATA V6 | INSANE EDITION
    </div>
</div>

</body>
</html>
