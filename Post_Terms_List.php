<?php
/*
Plugin Name: Post Term List
Plugin URI: http://dat.perdomani.net/
Description: A list of relevant keywords from the post. More info and usage on <a href="http://dat.perdomani.net/post-term-list-plugin/" title="dat blog">dat's blog plugin page</a>.
Version: 0.2
Author: dat
Author URI: http://dat.perdomani.net
Changelog: name conflict with Similar Posts resolved
*/

/*
Note:
 This plugin is a stripped down version of Similar Posts plugin.
 Nothing added apart from the stopword list that now includes italian stop words.

From where I stole the code:
 Plugin Name:Similar Posts
 Plugin URI: http://rmarsh.com/plugins/similar-posts/
 Author: Rob Marsh, SJ
 Author URI: http://rmarsh.com/

This plugin license:
 Copyright 2007  dat  (http://dat.perdomani.net)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details: http://www.gnu.org/licenses/gpl.txt
*/


// call this function from the theme file
function post_term_list($postID) {

$output = ptl_the_terms($postID);

echo "$output ";

}

function ptl_get_post_words($string, $utf8) {
	//tidy up the string a little 
	$string = strip_tags($string);
	//make an array of all the words in the string
	if ($utf8) {
		// handle extended characters...
		if (function_exists('mb_strtolower')) {
			// we can do it two ways: first if the mb functions are available ...
			@ $string = mb_strtolower($string, mb_detect_encoding($string));
			mb_regex_encoding("utf-8");
			$wordlist = mb_split("\W+" ,$string);
		} else {
			// ... a less reliable way
			preg_match_all('/\w+/u', $string, $matches);
			$wordlist = $matches[0];
		}
	} else {
		// plain ordinary PHP function
		$wordlist = str_word_count(strtolower($string), 1);
	}
	return $wordlist;
}

/*
	Takes a string, strips it of html, and produces list of the 20 most used words (common words ignored)
*/
function get_post_terms($content, $title, $utf8 = false, $biastitle = 'equal', $num_terms = 20) {
  // array of stop words, italian + english (since I occasionally write in english)
  $overusedwords = array('al', 'allo', 'alla', 'ai', 'agli', 'alle', 'dal', 'dallo', 'dalla', 'dai', 'dagli', 'dalle', 'del', 
'dello', 'della', 'dei', 'degli', 'delle', 'nel', 'nello', 'nella', 'nei', 'negli', 'nelle', 'sul', 'sullo', 'sulla', 'sui', 
'sugli', 'sulle', 'davanti', 'dietro', 'stante', 'durante', 'sopra', 'sotto', 'salvo', 'accanto', 'avanti', 'verso', 'presso', 
'contro', 'circa', 'intorno', 'fuori', 'malgrado', 'vicino', 'lontano', 'dentro', 'indietro', 'insieme', 'assieme', 'oltre', 
'senza', 'attraverso', 'nondimeno', 'mio', 'mia', 'miei', 'mie', 'tuo', 'tua', 'tuoi', 'tue', 'suo', 'sua', 'suoi', 'sue', 
'nostro', 'nostra', 'nostri', 'nostre', 'vostro', 'vostra', 'vostri', 'vostre', 'loro', 'questo', 'codesto', 'cotesto', 
'quello', 'ciò', 'questa', 'codesta', 'cotesta', 'quella', 'io', 'tu', 'egli', 'esso', 'ella', 'essa', 'noi', 'voi', 'essi', 
'esse', 'me', 'mi', 'te', 'ti', 'lui', 'lei', 'ce', 'ci', 've', 'vi', 'li', 'se', 'si', 'ne', 'colui', 'colei', 'cui', 'chi', 
'due', 'tre', 'quattro', 'cinque', 'sette', 'otto', 'nove', 'dieci', 'primo', 'secondo', 'terzo', 'quarto', 'quinto', 'sesto', 
'settimo', 'ottavo', 'nono', 'decimo', 'abbastanza', 'almeno', 'ancora', 'appunto', 'attualmente', 'certamente', 'comunque', 
'dove', 'dovunque', 'effettivamente', 'forse', 'generalmente', 'inoltre', 'insufficientemente', 'inutilmente', 'naturalmente', 
'no', 'non', 'nuovamente', 'ovunque', 'ovviamente', 'piuttosto', 'precedentemente', 'probabilmente', 'realmente', 'realmente', 
'semplicemente', 'se', 'sono', 'quelli', 'quel', 'tutto', 'niente', 'troppo', 'stanno', 'solitamente', 'soprattutto', 'specificamente', 'successivamente', 'sufficientemente', 'veramente', 
'gennaio', 'febbraio', 'marzo', 'aprile', 'maggio', 'giugno', 'luglio', 'agosto', 'settembre', 'ottobre', 'novembre', 
'dicembre', 'alcune', 'alcuni', 'alcuno', 'altri', 'altro', 'certo', 'chiunque', 'ciascuno', 'molti', 'nessun', 'nessuno', 
'ogni', 'ognuno', 'parecchi', 'parecchio', 'pochi', 'qualche', 'qualcosa', 'qualcuno', 'qualunque', 'tanto', 'tutti', 
'tutto', 'anno', 'bene', 'cosa', 'cose', 'data', 'esempio', 'male', 'scelta', 'vero', 'via', 'aperto', 'attuale', 'breve', 
'chiuso', 'corto', 'differente', 'difficile', 'dissimile', 'diverso', 'entrambe', 'entrambi', 'esterno', 'facile', 'falso', 
'grande', 'inusuale', 'inutile', 'lungo', 'impossibile', 'improbabile', 'insolito', 'insufficiente', 'maggiore', 'minore', 
'piccolo', 'pieno', 'possibile', 'probabile', 'pronto', 'semplice', 'siffatto', 'simile', 'sufficiente', 'usuale', 'utile', 
'vuoto', 'interno', 'mediante', 'modo', 'ovvio', 'precedente', 'primi', 'propri', 'proprio', 'prossimo', 'reale', 'scelto', 
'soli', 'solito', 'solo', 'soltanto', 'specifico', 'stessi', 'stesso', 'subito', 'successivo', 'super', 'tale', 'totale', 
'totali', 'uguale', 'uguali', 'ulteriore', 'ultimi', 'ultimo', 'vari', 'vario', 'verso', '-','able', 'about', 'above', 
'according', 'accordingly', 'across', 'actually', 'after', 'afterwards', 'again', 'against', 'allow', 'allows', 'almost', 
'alone', 'along', 'already', 'also', 'although', 'always', 'among', 'amongst', 'another', 'anybody', 'anyhow', 'anyone', 
'anything', 'anyway', 'anyways', 'anywhere', 'apart', 'appear', 'appreciate', 'appropriate', 'aren', 'around', 'aside', 
'asking', 'associated', 'available', 'away', 'awfully', 'became', 'because', 'become', 'becomes', 'becoming', 'been', 
'before', 'beforehand', 'behind', 'being', 'believe', 'below', 'beside', 'besides', 'best', 'better', 'between', 'beyond', 
'both', 'brief', 'came', 'cannot', 'cause', 'causes', 'certain', 'certainly', 'changes', 'clearly', 'come', 'comes', 
'concerning', 'consequently', 'consider', 'considering', 'contain', 'containing', 'contains', 'corresponding', 'could', 
'couldn', 'course', 'currently', 'definitely', 'described', 'despite', 'didn', 'different', 'does', 'doesn', 'doing', 
'done', 'down', 'downwards', 'during', 'each', 'eight', 'either', 'else', 'elsewhere', 'enough', 'entirely', 'especially', 
'even', 'ever', 'every', 'everybody', 'everyone', 'everything', 'everywhere', 'exactly', 'example', 'except', 'fifth', 
'first', 'five', 'followed', 'following', 'follows', 'former', 'formerly', 'forth', 'four', 'from', 'further', 'furthermore', 
'gets', 'getting', 'given', 'gives', 'goes', 'going', 'gone', 'gotten', 'greetings', 'hadn', 'happens', 'hardly', 'hasn', 
'have', 'haven', 'having', 'hello', 'help', 'hence', 'here', 'hereafter', 'hereby', 'herein', 'hereupon', 'hers', 'herself', 
'himself', 'hither', 'hopefully', 'howbeit', 'however', 'ignored', 'immediate', 'inasmuch', 'indeed', 'indicate', 'indicated', 
'indicates', 'inner', 'insofar', 'instead', 'into', 'inward', 'itself', 'just', 'keep', 'keeps', 'kept', 'know', 'known', 
'knows', 'last', 'lately', 'later', 'latter', 'latterly', 'least', 'less', 'lest', 'like', 'liked', 'likely', 'little', 
'look', 'looking', 'looks', 'mainly', 'many', 'maybe', 'mean', 'meanwhile', 'merely', 'might', 'more', 'moreover', 'most', 
'mostly', 'much', 'must', 'myself', 'name', 'namely', 'near', 'nearly', 'necessary', 'need', 'needs', 'neither', 'never', 
'nevertheless', 'next', 'nine', 'nobody', 'none', 'noone', 'normally', 'nothing', 'novel', 'nowhere', 'obviously', 'often', 
'okay', 'once', 'ones', 'only', 'onto', 'other', 'others', 'otherwise', 'ought', 'ours', 'ourselves', 'outside', 'over', 
'overall', 'particular', 'particularly', 'perhaps', 'placed', 'please', 'plus', 'possible', 'presumably', 'probably', 
'provides', 'quite', 'rather', 'really', 'reasonably', 'regarding', 'regardless', 'regards', 'relatively', 'respectively', 
'right', 'said', 'same', 'saying', 'says', 'second', 'secondly', 'seeing', 'seem', 'seemed', 'seeming', 'seems', 'seen', 
'self', 'selves', 'sensible', 'sent', 'serious', 'seriously', 'seven', 'several', 'shall', 'should', 'shouldn', 'since', 
'some', 'somebody', 'somehow', 'someone', 'something', 'sometime', 'sometimes', 'somewhat', 'somewhere', 'soon', 'sorry', 
'specified', 'specify', 'specifying', 'still', 'such', 'sure', 'take', 'taken', 'tell', 'tends', 'than', 'thank', 'thanks', 
'that', 'thats', 'their', 'theirs', 'them', 'themselves', 'then', 'thence', 'there', 'thereafter', 'thereby', 'therefore', 
'therein', 'theres', 'thereupon', 'these', 'they', 'think', 'third', 'this', 'thorough', 'thoroughly', 'those', 'though', 
'three', 'through', 'throughout', 'thru', 'thus', 'together', 'took', 'toward', 'towards', 'tried', 'tries', 'truly', 
'trying', 'twice', 'under', 'unfortunately', 'unless', 'unlikely', 'until', 'unto', 'upon', 'used', 'useful', 'uses', 
'using', 'usually', 'value', 'various', 'very', 'want', 'wants', 'wasn', 'welcome', 'well', 'went', 'were', 'weren', 'what', 
'whatever', 'when', 'whence', 'whenever', 'where', 'whereafter', 'whereas', 'whereby', 'wherein', 'whereupon', 'wherever', 
'whether', 'which', 'while', 'whither', 'whoever', 'whole', 'whom', 'whose', 'will', 'willing', 'wish', 'with', 'within', 
'without', 'wonder', 'would', 'wouldn', 'your', 'yours', 'yourself', 'yourselves', 'zero');

	if ($biastitle === 'equal') {
		$string = $content . ' ' . $title;
	} else {
		$string = $content;
	}
	$wordlist = ptl_get_post_words($string, false);
 	//count them, word=>count
	$wordtable = array_count_values($wordlist);
	//knock out the noise words ... the $overusedwords array was loaded from a file to allow for different languages
	foreach ($overusedwords as $word) {
		unset($wordtable[$word]); 
	}
	// knock out words of three or less characters since mysql ignores them for full text searches
	foreach ($wordtable as $word => $freq) {
		if (strlen($word) < 4) {
			unset($wordtable[$word]);
		}
	}
	//sort by count
	arsort($wordtable);
	
	//convert the most used words into a list 'term1 term1 term1 term2 term2 term3' etc.
	if ($num_terms < 1) $num_terms = 1;
	$terms = '';
	$num = 0;

	foreach ($wordtable as $word => $count) {
		$terms .= ' ' . $word;
		$num++;
		if ($num >= $num_terms) break;
	}

	$terms = ltrim($terms);
	
	if ($biastitle === 'high') {
		$titlewords = ptl_get_post_words($title, $utf8);
		$terms .= ' ' . implode(' ', $titlewords);
	}
	return ($terms);
}

/*
	Given the ID of a post, gets its most common words
*/
function ptl_the_terms($postID) {
	global $wpdb;
	//get the post content and title
	$content = $wpdb->get_row("SELECT post_content, post_title FROM $wpdb->posts WHERE ID = $postID", ARRAY_A);
	//extract its terms
	$options = get_option(basename(__FILE__, ".php"));
	$terms = get_post_terms($content['post_content'], $content['post_title'], $options['utf8'] == 'true', $options['bias_title'], 20);

	return $terms;
}
