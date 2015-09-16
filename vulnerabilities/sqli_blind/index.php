<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]  .= $page[ 'title_separator' ].'Vulnerability: SQL Injection (Blind)';
$page[ 'page_id' ] = 'sqli_blind';
$page[ 'help_button' ]   = 'sqli_blind';
$page[ 'source_button' ] = 'sqli_blind';

dvwaDatabaseConnect();

$vulnerabilityFile = '';
switch( $_COOKIE[ 'security' ] ) {
	case 'low':
		$vulnerabilityFile = 'low.php';
		break;
	case 'medium':
		$vulnerabilityFile = 'medium.php';
		break;
	case 'high':
		$vulnerabilityFile = 'high.php';
		break;
	default:
		$vulnerabilityFile = 'impossible.php';
		break;
}

// Anti-CSRF
if( $vulnerabilityFile == 'impossible.php' )
	generateTokens();

require_once DVWA_WEB_PAGE_TO_ROOT."vulnerabilities/sqli_blind/source/{$vulnerabilityFile}";

$magicQuotesWarningHtml = '';

// Check if Magic Quotes are on or off
if( ini_get( 'magic_quotes_gpc' ) == true ) {
	$magicQuotesWarningHtml = "<div class=\"warning\">Magic Quotes are on, you will not be able to inject SQL.</div>";
}

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: SQL Injection (Blind)</h1>

	{$magicQuotesWarningHtml}

	<div class=\"vulnerable_code_area\">
		<form action=\"#\" method=\"GET\">
			<p>
				User ID:
				<input type=\"text\" size=\"15\" name=\"id\">
				<input type=\"submit\" name=\"Submit\" value=\"Submit\">
			</p>";

if( $vulnerabilityFile == 'impossible.php' )
	$page[ 'body' ] .= "			" . tokenField();

$page[ 'body' ] .= "
		</form>
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>".dvwaExternalLinkUrlGet( 'http://www.securiteam.com/securityreviews/5DP0N1P76E.html' )."</li>
		<li>".dvwaExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/SQL_injection' )."</li>
		<li>".dvwaExternalLinkUrlGet( 'http://ferruh.mavituna.com/sql-injection-cheatsheet-oku/' )."</li>
		<li>".dvwaExternalLinkUrlGet( 'http://pentestmonkey.net/cheat-sheet/sql-injection/mysql-sql-injection-cheat-sheet' )."</li>
		<li>".dvwaExternalLinkUrlGet( 'https://www.owasp.org/index.php/Blind_SQL_Injection' )."</li>
		<li>".dvwaExternalLinkUrlGet( 'http://bobby-tables.com/' )."</li>
	</ul>
</div>
";

dvwaHtmlEcho( $page );

?>
