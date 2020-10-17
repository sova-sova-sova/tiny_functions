<?
/**
 * Htmlspecialchars
 * @param string $x
 * @return string
 */
function h($x = ''):string{
    return htmlspecialchars((string)$x);
}



function translit($s) {
    $s = (string) $s; // преобразуем в строковое значение
    $s = strip_tags($s); // убираем HTML-теги
    $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
    $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
    $s = trim($s); // убираем пробелы в начале и конце строки
    $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
    $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
    $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
    $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
    return $s; // возвращаем результат
}


/**
 * json_encode with Cyrillic
 * @param $obj
 * @return string
 */
function je($obj) : string
{
    $arrayUtf = array('\u0410', '\u0430', '\u0411', '\u0431', '\u0412', '\u0432', '\u0413', '\u0433', '\u0414', '\u0434', '\u0415', '\u0435', '\u0401', '\u0451', '\u0416', '\u0436', '\u0417', '\u0437', '\u0418', '\u0438', '\u0419', '\u0439', '\u041a', '\u043a', '\u041b', '\u043b', '\u041c', '\u043c', '\u041d', '\u043d', '\u041e', '\u043e', '\u041f', '\u043f', '\u0420', '\u0440', '\u0421', '\u0441', '\u0422', '\u0442', '\u0423', '\u0443', '\u0424', '\u0444', '\u0425', '\u0445', '\u0426', '\u0446', '\u0427', '\u0447', '\u0428', '\u0448', '\u0429', '\u0449', '\u042a', '\u044a', '\u042b', '\u044b', '\u042c', '\u044c', '\u042d', '\u044d', '\u042e', '\u044e', '\u042f', '\u044f');
    $arrayCyr = array('А', 'а', 'Б', 'б', 'В', 'в', 'Г', 'г', 'Д', 'д', 'Е', 'е', 'Ё', 'ё', 'Ж', 'ж', 'З', 'з', 'И', 'и', 'Й', 'й', 'К', 'к', 'Л', 'л', 'М', 'м', 'Н', 'н', 'О', 'о', 'П', 'п', 'Р', 'р', 'С', 'с', 'Т', 'т', 'У', 'у', 'Ф', 'ф', 'Х', 'х', 'Ц', 'ц', 'Ч', 'ч', 'Ш', 'ш', 'Щ', 'щ', 'Ъ', 'ъ', 'Ы', 'ы', 'Ь', 'ь', 'Э', 'э', 'Ю', 'ю', 'Я', 'я');
    return str_replace($arrayUtf, $arrayCyr, json_encode($obj));
}

function cfloatval($x){return floatval(str_replace(",",".",$x));}

function substr1251($x, $s = 0, $l = null)
{
    $x = iconv('UTF-8', 'windows-1251', $x); //Меняем кодировку на windows-1251
    $x = is_numeric($l) ? substr($x, $s, $l) : substr($x, $s); //Обрезаем строку
    return iconv('windows-1251', 'UTF-8', $x);
}
function strtolower1251($x){
    $x = iconv('UTF-8', 'windows-1251', $x); //Меняем кодировку на windows-1251
    $x = strtolower($x);
    return iconv('windows-1251', 'UTF-8', $x);
}

function text_id($x){ return preg_replace("/[^a-zA-Z0-9_]/","",strtolower1251(($x)));}


function limitIntRange($value,$min,$max){
    $value = intval($value);
    $min = intval($min);
    $max = intval($max);
    return min( $max, max( $value, $min ) );
}

function makePagination($count,$now,$wing = 3){
    $res = [];
    if($now!=0) $res[] = 'prev';
    if($count>0) {
        $a = array(1);
        $b = $wing;
        if ($count > 1) {
            $m1 = min($count, $b);
            for ($i = 2; $i <= $m1; $i++) $a[] = $i;
            $m1 = max(1, $now - $b);
            $m2 = min($count, $now + $b + 1);
            for ($i = $m1 + 1; $i <= $m2; $i++) $a[] = $i;
            $m1 = max(1, $count - $b);
            for ($i = $m1; $i <= $count; $i++) $a[] = $i;
        }
        $a = array_unique($a);
        $prev = null;
        foreach ($a as $k => $v) {
            $selected = $now == ($v - 1);
            if ($prev and $v - $prev > 1) {
                $res[] = "";
                //$res .= '<li class="page-item disabled"><a class="page-link" >...</a></li>';
            }
            $res[] = $v;
            //$res .= '<li class="page-item '.($selected?'active':'').'"><a data-page="'.($v-1).'" class="page-link">' . ($v) . '</a></li>';
            $prev = $v;
        }
    }
    if($now!=$count-1) $res[] = 'next';
    return $res;
    //return '<nav aria-label="Page navigation example" class="_module_table_pagination" data-id="'.$table_id.'"><ul class="pagination">'.$res.'</ul></nav>';
}


function sendEasyMail($name_from,
                      $email_from,
                      $name_to,
                      $email_to,
                      $data_charset,
                      $send_charset,
                      $subject,
                      $message,
                      $type,
                      $files = []
) {
    if (!isset($type)) $type="plain";
    $to      = mime_header_encode($name_to, $data_charset, $send_charset).' <' . $email_to . '>';
    $subject = mime_header_encode($subject, $data_charset, $send_charset);
    $from    = mime_header_encode($name_from, $data_charset, $send_charset).' <' . $email_from . '>';

    // if($data_charset != $send_charset) { $message = iconv($data_charset, $send_charset, $message); }

    $separator = md5(time());

    // carriage return type (RFC)
    $eol = "\r\n";

    $headers = "From: $from".$eol;
    $headers .= "MIME-Version: 1.0" . $eol;
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol. $eol;
    //$headers .= "Content-Transfer-Encoding: 8bit" . $eol. $eol;
    //$headers .= "This is a MIME encoded message." . $eol;
    //$headers .= "Content-type: text/".$type."; charset=$send_charset".$eol;

    $body = "--" . $separator . $eol;
    $body .= "Content-Type: text/".$type."; charset=\"UTF-8\"" . $eol;
    $body .= "Content-Transfer-Encoding: 7bit" . $eol. $eol;
    $body .= $message . $eol. $eol;

    foreach ($files as $path){

        $content = file_get_contents($path);
        $content = chunk_split(base64_encode($content));

        $body .= "--" . $separator . $eol;
        $body .= "Content-Type: application/octet-stream; name=\"" . basename($path) . "\"" . $eol;
        $body .= "Content-Transfer-Encoding: base64" . $eol;
        $body .= "Content-Disposition: attachment" . $eol. $eol;
        $body .= $content . $eol. $eol;

    }

    $body .= "--" . $separator . "--";

    return mail($to, $subject, $body, $headers);
}


function mime_header_encode($str, $data_charset, $send_charset) {
    if($data_charset != $send_charset) {
        $str = iconv($data_charset, $send_charset, $str);
    }
    return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
}

/*
function sendEasyMail($name_from, //
                    $email_from, // email
                    $name_to, //
                    $email_to, // email
                    $data_charset, //
                    $send_charset, //
                    $subject, //
                    $body, //
                    $type //  plain | html
) {
    if (!isset($type)) $type="plain";
    $to = mime_header_encode($name_to, $data_charset, $send_charset)
        . ' <' . $email_to . '>';
    $subject = mime_header_encode($subject, $data_charset, $send_charset);
    $from =  mime_header_encode($name_from, $data_charset, $send_charset)
        .' <' . $email_from . '>';
    if($data_charset != $send_charset) {
        $body = iconv($data_charset, $send_charset, $body);
    }
    $headers = "From: $from\r\n";
    $headers .= "Content-type: text/".$type."; charset=$send_charset\r\n";

    return mail($to, $subject, $body, $headers);
}

function mime_header_encode($str, $data_charset, $send_charset) {
    if($data_charset != $send_charset) {
        $str = iconv($data_charset, $send_charset, $str);
    }
    return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
}

/**/


function filesize_formatted($path)
{
    $size = filesize($path);
    $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $size > 0 ? floor(log($size, 1024)) : 0;
    return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}


function print_ru_price_as_text($value,$options = []) {
    $isRubWord = boolval($options['rub_word']);
    $isKop     = boolval($options['kop']);
    $isKopWord = boolval($options['kop_word']);
    $digits = array('один',
        'два',
        'три',
        'четыре',
        'пять',
        'шесть',
        'семь',
        'восемь',
        'девять');
    $decades  = array('десять',
        'двадцать',
        'тридцать',
        'сорок',
        'пятьдесят',
        'шестьдесят',
        'семьдесят',
        'восемьдесят',
        'девяносто');
    $decades_20  = array('одиннадцать',
        'двенадцать',
        'тринадцать',
        'четырнадцать',
        'пятнадцать',
        'шестнадцать',
        'семнадцать',
        'восемьнадцать',
        'девятнадцать');
    $hundreds = array('сто',
        'двести',
        'триста',
        'четыреста',
        'пятьсот',
        'шетьсот',
        'семьсот',
        'восемьсот',
        'девятьсот');
    $thousands = array('одна',
        'две',
        'три',
        'четыре',
        'пять',
        'шесть',
        'семь',
        'восемь',
        'девять');


    $rub = floor($value);
    $kop = round($value*100 - $rub*100);
    if ($kop < 10) $kop = '0'.$kop;
    $str = '';

    if ($rub > 999999999999) return false;

    if (strlen(strval($rub)) >= 2) {
        //десятки
        $dec = $rub-floor($rub/100)*100;
        if ($dec < 10 ) $str .= $digits[$dec-1];
        elseif ($dec > 10 && $dec < 20) $str .= $decades_20[$dec-11];
        else
        {
            $str .= $decades[floor($dec/10)-1];
            $dec_ = $dec - floor($dec/10)*10;
            if ($dec_ > 0) $str .= ' '.$digits[$dec_-1];
        }

        //сотни
        $hun = floor(($rub-floor($rub/1000)*1000)/100);
        if ($hun > 0) $str = $hundreds[$hun-1].' '.$str;

        //тысячи
        $thous = floor(($rub-floor($rub/1000000)*1000000)/1000);
        //добавление слова
        if ($thous > 0)
        {
            $thousand_word = 'тысяч';
            $last_symbol_thousand = $thous - floor($thous/10)*10;
            $last_two_symbols_thousand = $thous - floor($thous/100)*100;
            if ($last_two_symbols_thousand != 11 && $last_symbol_thousand == 1) $thousand_word = 'тысяча';
            elseif (($last_symbol_thousand == 2 || $last_symbol_thousand == 3 || $last_symbol_thousand == 4) && ($thous < 5 || $thous > 20)) $thousand_word = 'тысячи';
            $str = ' '.$thousand_word.' '.$str;
        }
        if ($thous < 10 ) $str = $thousands[$thous-1].$str;
        elseif ($thous > 10 && $thous < 20) $str = $decades_20[$thous-11].$str;
        elseif ($thous >= 20 && $thous < 100 || $thous == 10)
        {
            $dec_ = $thous - floor($thous/10)*10;
            if ($dec_ > 0) $str = $thousands[$dec_-1].' '.$str;
            $str = $decades[floor($thous/10)-1].' '.$str;
        }
        else
        {
            $dec_ = $thous - floor($thous/100)*100;
            if ($dec_ < 10) $str = $thousands[$dec_-1].' '.$str;
            elseif ($dec_ > 10 && $dec_ < 20) $str = $decades_20[$dec_-11].' '.$str;
            else //($dec_ >= 20 && $dec_ < 100 || $dec_ == 10)
            {
                $dig_ = $dec_ - floor($dec_/10)*10;
                if ($dig_ > 0) $str = $thousands[$dig_-1].' '.$str;
                $str = $decades[floor($dec_/10)-1].' '.$str;
            }
            $str = $hundreds[floor($thous/100)-1].' '.$str;
        }

        $xxx = function($value,&$str,$deca,$txt1,$txt2,$txt3,$hundreds,$decades_20,$decades,$digits) {
            if ($value >= $deca) {
                $mil = floor($value / $deca) . '';
                $hd = ($mil >= 100) ? $mil[0] : null;
                $dd = ($mil >= 100) ? ($mil[1]) : (($mil >= 10) ? $mil[0] : null);
                $nd = ($mil >= 100) ? ($mil[2]) : (($mil >= 10) ? $mil[1] : $mil[0]);
                $h = '';
                $d = '';
                $n = '';

                $is2Dec = ($dd == 1 && $nd);

                if ($hd) {
                    $h = $hundreds[$hd - 1];
                }
                if ($dd) {
                    if ($is2Dec) {
                        $d = $decades_20[$nd - 1];
                    } else {
                        $d = $decades[$dd - 1];
                    }
                }
                $n = $digits[$nd - 1];
                $ml = [];
                if ($c = $h) $ml[] = $c;
                if ($c = $d) $ml[] = $c;

                if (!$is2Dec) {
                    if ($c = $n) $ml[] = $c;
                }

                if ($is2Dec) {
                    $ml[] = $txt2;
                } else if ($nd == 1) {
                    $ml[] = $txt1;
                } else {
                    $ml[] = (in_array($nd, [2, 3, 4])) ? $txt3 : $txt2;
                }

                $str = implode(" ", $ml) . ' ' . $str;
            }
        };

        $xxx($value,$str,1000000,'миллион','миллионов','миллиона',$hundreds,$decades_20,$decades,$digits);
        $xxx($value,$str,1000000000,'миллиард','миллиардов','миллиарда',$hundreds,$decades_20,$decades,$digits);
        $xxx($value,$str,1000000000000,'триллион','триллионов','триллиарда',$hundreds,$decades_20,$decades,$digits);

    }
    else
    {
        if ($rub == 0) $str .= 'ноль';
        else $str .= $digits[$rub-1];
    }

    $rub_word = 'рублей';
    $last_symbol_rub = $rub - floor($rub/10)*10;
    $last_two_symbols_rub = $rub - floor($rub/100)*100;
    if ($last_two_symbols_rub != 11 && $last_symbol_rub == 1) $rub_word = 'рубль';
    elseif (($last_symbol_rub == 2 || $last_symbol_rub == 3 || $last_symbol_rub == 4) && ($rub < 5 || $rub > 20)) $rub_word = 'рубля';

    $kop_word = 'копеек';
    $last_symbol_kop = $kop - floor($kop/10)*10;
    $last_two_symbols_kop = $kop - floor($kop/100)*100;
    if ($last_two_symbols_kop != 11 && $last_symbol_kop == 1) $kop_word = 'копейка';
    elseif (($last_symbol_kop == 2 || $last_symbol_kop == 3 || $last_symbol_kop == 4) && ($kop < 5 || $kop > 20)) $kop_word = 'копейки';

    $str = trim(str_replace('  ',' ',$str));
    $valutes = ' '.$rub_word.' '.$kop.' '.$kop_word;

    if($isRubWord){
        $str .= ' '.$rub_word;
    }
    if($isKop){
        $kops = $value*100%100;
        $str .= ' '.($kops<10?"0":"").$kops;
        if($isKopWord) {
            $str .= ' '.$kop_word;
        }
    }


    return $str;

}
