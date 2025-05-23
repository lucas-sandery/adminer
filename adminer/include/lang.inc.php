<?php
namespace Adminer;

/** Translate string
* @param literal-string $idf
* @param float|string $number
*/
function lang(string $idf, $number = null): string {
	$args = func_get_args();
	// this is matched by compile.php
	$args[0] = Lang::$translations[$idf] ?: $idf;
	return call_user_func_array('Adminer\lang_format', $args);
}

/** Format translation, usable also by plugins
* @param string|list<string> $translation
* @param float|string $number
*/
function lang_format($translation, $number = null): string {
	if (is_array($translation)) {
		// this is matched by compile.php
		$pos = ($number == 1 ? 0
			: (LANG == 'cs' || LANG == 'sk' ? ($number && $number < 5 ? 1 : 2) // different forms for 1, 2-4, other
			: (LANG == 'fr' ? (!$number ? 0 : 1) // different forms for 0-1, other
			: (LANG == 'pl' ? ($number % 10 > 1 && $number % 10 < 5 && $number / 10 % 10 != 1 ? 1 : 2) // different forms for 1, 2-4 except 12-14, other
			: (LANG == 'sl' ? ($number % 100 == 1 ? 0 : ($number % 100 == 2 ? 1 : ($number % 100 == 3 || $number % 100 == 4 ? 2 : 3))) // different forms for 1, 2, 3-4, other
			: (LANG == 'lt' ? ($number % 10 == 1 && $number % 100 != 11 ? 0 : ($number % 10 > 1 && $number / 10 % 10 != 1 ? 1 : 2)) // different forms for 1, 12-19, other
			: (LANG == 'lv' ? ($number % 10 == 1 && $number % 100 != 11 ? 0 : ($number ? 1 : 2)) // different forms for 1 except 11, other, 0
			: (in_array(LANG, array('bs', 'ru', 'sr', 'uk')) ? ($number % 10 == 1 && $number % 100 != 11 ? 0 : ($number % 10 > 1 && $number % 10 < 5 && $number / 10 % 10 != 1 ? 1 : 2)) // different forms for 1 except 11, 2-4 except 12-14, other
			: 1)))))))) // different forms for 1, other
		; // http://www.gnu.org/software/gettext/manual/html_node/Plural-forms.html
		$translation = $translation[$pos];
	}
	$translation = str_replace("'", '’', $translation); // translations can contain HTML or be used in optionlist (we couldn't escape them here) but they can also be used e.g. in title='' //! escape plaintext translations
	$args = func_get_args();
	array_shift($args);
	$format = str_replace("%d", "%s", $translation);
	if ($format != $translation) {
		$args[0] = format_number($number);
	}
	return vsprintf($format, $args);
}

// this is matched by compile.php
// not used in a single language version from here

/** Get available languages
* @return string[]
*/
function langs(): array {
	return array(
		'en' => 'English', // Jakub Vrána - https://www.vrana.cz
		'ar' => 'العربية', // Y.M Amine - Algeria - nbr7@live.fr
		'bg' => 'Български', // Deyan Delchev
		'bn' => 'বাংলা', // Dipak Kumar - dipak.ndc@gmail.com, Hossain Ahmed Saiman - hossain.ahmed@altscope.com
		'bs' => 'Bosanski', // Emir Kurtovic
		'ca' => 'Català', // Joan Llosas
		'cs' => 'Čeština', // Jakub Vrána - https://www.vrana.cz
		'da' => 'Dansk', // Jarne W. Beutnagel - jarne@beutnagel.dk
		'de' => 'Deutsch', // Klemens Häckel - http://clickdimension.wordpress.com
		'el' => 'Ελληνικά', // Dimitrios T. Tanis - jtanis@tanisfood.gr
		'es' => 'Español', // Klemens Häckel - http://clickdimension.wordpress.com
		'et' => 'Eesti', // Priit Kallas
		'fa' => 'فارسی', // mojtaba barghbani - Iran - mbarghbani@gmail.com, Nima Amini - http://nimlog.com
		'fi' => 'Suomi', // Finnish - Kari Eveli - http://www.lexitec.fi/
		'fr' => 'Français', // Francis Gagné, Aurélien Royer
		'gl' => 'Galego', // Eduardo Penabad Ramos
		'he' => 'עברית', // Binyamin Yawitz - https://stuff-group.com/
		'hi' => 'हिन्दी', // Joshi yogesh
		'hu' => 'Magyar', // Borsos Szilárd (Borsosfi) - http://www.borsosfi.hu, info@borsosfi.hu
		'id' => 'Bahasa Indonesia', // Ivan Lanin - http://ivan.lanin.org
		'it' => 'Italiano', // Alessandro Fiorotto, Paolo Asperti
		'ja' => '日本語', // Hitoshi Ozawa - http://sourceforge.jp/projects/oss-ja-jpn/releases/
		'ka' => 'ქართული', // Saba Khmaladze skhmaladze@uglt.org
		'ko' => '한국어', // dalli - skcha67@gmail.com
		'lt' => 'Lietuvių', // Paulius Leščinskas - http://www.lescinskas.lt
		'lv' => 'Latviešu', // Kristaps Lediņš - https://krysits.com
		'ms' => 'Bahasa Melayu', // Pisyek
		'nl' => 'Nederlands', // Maarten Balliauw - http://blog.maartenballiauw.be
		'no' => 'Norsk', // Iver Odin Kvello, mupublishing.com
		'pl' => 'Polski', // Radosław Kowalewski - http://srsbiz.pl/
		'pt' => 'Português', // André Dias
		'pt-br' => 'Português (Brazil)', // Gian Live - gian@live.com, Davi Alexandre davi@davialexandre.com.br, RobertoPC - http://www.robertopc.com.br
		'ro' => 'Limba Română', // .nick .messing - dot.nick.dot.messing@gmail.com
		'ru' => 'Русский', // Maksim Izmaylov; Andre Polykanine - https://github.com/Oire/
		'sk' => 'Slovenčina', // Ivan Suchy - http://www.ivansuchy.com, Juraj Krivda - http://www.jstudio.cz
		'sl' => 'Slovenski', // Matej Ferlan - www.itdinamik.com, matej.ferlan@itdinamik.com
		'sr' => 'Српски', // Nikola Radovanović - cobisimo@gmail.com
		'sv' => 'Svenska', // rasmusolle - https://github.com/rasmusolle
		'ta' => 'த‌மிழ்', // G. Sampath Kumar, Chennai, India, sampathkumar11@gmail.com
		'th' => 'ภาษาไทย', // Panya Saraphi, elect.tu@gmail.com - http://www.opencart2u.com/
		'tr' => 'Türkçe', // Bilgehan Korkmaz - turktron.com
		'uk' => 'Українська', // Valerii Kryzhov
		'uz' => 'Oʻzbekcha', // Junaydullaev Inoyatullokhon - https://av.uz/
		'vi' => 'Tiếng Việt', // Giang Manh @ manhgd google mail
		'zh' => '简体中文', // Mr. Lodar, vea - urn2.net - vea.urn2@gmail.com
		'zh-tw' => '繁體中文', // http://tzangms.com
	);
}

function switch_lang(): void {
	echo "<form action='' method='post'>\n<div id='lang'>";
	echo "<label>" . lang('Language') . ": " . html_select("lang", langs(), LANG, "this.form.submit();") . "</label>";
	echo " <input type='submit' value='" . lang('Use') . "' class='hidden'>\n";
	echo input_token();
	echo "</div>\n</form>\n";
}

if (isset($_POST["lang"]) && verify_token()) { // $error not yet available
	cookie("adminer_lang", $_POST["lang"]);
	$_SESSION["lang"] = $_POST["lang"]; // cookies may be disabled
	redirect(remove_from_uri());
}

$LANG = "en";
if (idx(langs(), $_COOKIE["adminer_lang"])) {
	cookie("adminer_lang", $_COOKIE["adminer_lang"]);
	$LANG = $_COOKIE["adminer_lang"];
} elseif (idx(langs(), $_SESSION["lang"])) {
	$LANG = $_SESSION["lang"];
} else {
	$accept_language = array();
	preg_match_all('~([-a-z]+)(;q=([0-9.]+))?~', str_replace("_", "-", strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"])), $matches, PREG_SET_ORDER);
	foreach ($matches as $match) {
		$accept_language[$match[1]] = (isset($match[3]) ? $match[3] : 1);
	}
	arsort($accept_language);
	foreach ($accept_language as $key => $q) {
		if (idx(langs(), $key)) {
			$LANG = $key;
			break;
		}
		$key = preg_replace('~-.*~', '', $key);
		if (!isset($accept_language[$key]) && idx(langs(), $key)) {
			$LANG = $key;
			break;
		}
	}
}

define('Adminer\LANG', $LANG);

class Lang {
	/** @var array<literal-string, string|list<string>> */ static array $translations;
}
