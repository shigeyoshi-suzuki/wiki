<?php
// PukiWiki - Yet another WikiWikiWeb clone.
// newpage.inc.php
// Copyright 2002-2017 PukiWiki Development Team
// License: GPL v2 or (at your option) any later version
//
// Newpage plugin

function plugin_newpage_convert()
{
	global $vars, $_btn_edit, $_msg_newpage, $BracketName;
	static $id = 0;

	$script = get_base_uri();
	if (PKWK_READONLY) return ''; // Show nothing

	$newpage = '';
	if (func_num_args()) list($newpage) = func_get_args();
	if (! preg_match('/^' . $BracketName . '$/', $newpage)) $newpage = '';

	$s_page    = htmlsc(isset($vars['refer']) ? $vars['refer'] : $vars['page']);
	$s_newpage = htmlsc($newpage);
	++$id;

	$ret = <<<EOD
<form action="$script" method="post">
 <div>
  <input type="hidden" name="plugin" value="newpage" />
  <input type="hidden" name="refer"  value="$s_page" />
  <label for="_p_newpage_$id">$_msg_newpage:</label>
  <input type="text"   name="page" id="_p_newpage_$id" value="$s_newpage" size="30" />
  <input type="submit" value="$_btn_edit" />
 </div>
</form>
EOD;

	return $ret;
}

function plugin_newpage_action()
{
	global $vars, $_btn_edit, $_msg_newpage;

	if (PKWK_READONLY) die_message('PKWK_READONLY prohibits editing');

	if ($vars['page'] == '') {
		$retvars['msg']  = $_msg_newpage;
		$retvars['body'] = plugin_newpage_convert();
		return $retvars;
	} else {
		$page    = strip_bracket($vars['page']);
		$r_page  = rawurlencode(isset($vars['refer']) ?
			get_fullname($page, $vars['refer']) : $page);
		$r_refer = rawurlencode($vars['refer']);

		pkwk_headers_sent();
		header('Location: ' . get_base_uri(PKWK_URI_ROOT) .
			'?cmd=read&page=' . $r_page . '&refer=' . $r_refer);
		exit;
	}
}
